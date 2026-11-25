<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query  = StudentRegistration::with(['user', 'financialData', 'vehicleAssets'])->orderByDesc('submitted_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $registrations = $query->paginate(20);
        $counts = [
            'all'      => StudentRegistration::count(),
            'pending'  => StudentRegistration::where('status', 'pending')->count(),
            'reviewed' => StudentRegistration::where('status', 'reviewed')->count(),
            'approved' => StudentRegistration::where('status', 'approved')->count(),
            'rejected' => StudentRegistration::where('status', 'rejected')->count(),
        ];

        return view('admin.registrations.index', compact('registrations', 'status', 'counts'));
    }

    public function show(StudentRegistration $registration)
    {
        $registration->load(['financialData', 'vehicleAssets', 'user.siswa', 'reviewer']);
        return view('admin.registrations.show', compact('registration'));
    }

    public function updateStatus(Request $request, StudentRegistration $registration)
    {
        $request->validate([
            'status'                          => 'required|in:pending,reviewed,approved,rejected',
            'catatan_admin'                   => 'nullable|string|max:1000',
            'status_keringanan'               => 'nullable|in:pending,approved,rejected,none',
            'persentase_keringanan_disetujui' => 'nullable|integer|min:0|max:100',
            'catatan_keringanan_admin'        => 'nullable|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($request, $registration) {
                $newStatus = $request->status;
                $userId = $registration->user_id;

                // Update data keringanan jika diajukan
                $statusKeringanan = $request->input('status_keringanan', $registration->status_keringanan);
                $persentaseDisetujui = $request->input('persentase_keringanan_disetujui');
                $catatanKeringanan = $request->input('catatan_keringanan_admin');

                if ($newStatus === 'approved' && !$userId) {
                    $email = $registration->email;
                    if (!$email) {
                        throw new \Exception('Email pendaftar tidak ditemukan.');
                    }
                    if (\App\Models\User::where('email', $email)->exists()) {
                        throw new \Exception('Email pendaftar (' . $email . ') sudah terdaftar di sistem sebagai user.');
                    }

                    // Gunakan password hash yang dibuat calon siswa di Slide 4, atau fallback ke default
                    $passwordHash = $registration->password_hash ?: \Illuminate\Support\Facades\Hash::make('password123');

                    // 1. Buat akun user siswa
                    $user = \App\Models\User::create([
                        'name'     => $registration->nama_lengkap,
                        'email'    => $email,
                        'password' => $passwordHash,
                        'role'     => 'student',
                    ]);
                    $userId = $user->id;

                    // 2. Buat data Siswa
                    \App\Models\Siswa::create([
                        'nama_siswa'        => $registration->nama_lengkap,
                        'nama_orang_tua'    => $registration->nama_ayah ?? $registration->nama_ibu ?? 'Orang Tua',
                        'alamat_tagihan'    => $registration->alamat_rumah ?? '',
                        'user_id'           => $userId,
                        'status'            => 'aktif',
                        'telepon_orang_tua' => $registration->no_telpon_wa,
                        'tanggal_lahir'     => $registration->tanggal_lahir,
                        'jenis_kelamin'     => $registration->jenis_kelamin,
                        'alamat_orang_tua'  => $registration->alamat_rumah,
                    ]);
                }

                $updateData = [
                    'status'        => $newStatus,
                    'user_id'       => $userId,
                    'catatan_admin' => $request->catatan_admin,
                    'reviewed_at'   => now(),
                    'reviewed_by'   => auth()->id(),
                ];

                if ($registration->mengajukan_keringanan) {
                    $updateData['status_keringanan'] = $statusKeringanan;
                    if ($persentaseDisetujui !== null) {
                        $updateData['persentase_keringanan_disetujui'] = (int)$persentaseDisetujui;
                    }
                    if ($catatanKeringanan !== null) {
                        $updateData['catatan_keringanan_admin'] = $catatanKeringanan;
                    }
                }

                $registration->update($updateData);
            });

            return redirect()->back()->with('success', 'Status pendaftaran dan keringanan biaya berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pendaftaran: ' . $e->getMessage());
        }
    }

    public function downloadPdf(StudentRegistration $registration)
    {
        $registration->load(['financialData', 'vehicleAssets']);
        return view('admin.registrations.pdf', compact('registration'));
    }
}
