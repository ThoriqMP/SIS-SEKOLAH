<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('user')->latest();

        $status = $request->input('status', 'aktif');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nama_orang_tua', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(12)->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function show(Siswa $student)
    {
        $student->load(['user', 'reports.category', 'reports.grades', 'reports.probingActivities']);
        $categories = \App\Models\ReportCategory::all();
        return view('students.show', compact('student', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_siswa' => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:255',
            'alamat_tagihan' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|min:6',
            'nis' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:aktif,lulus,pindah',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $educationLevel = null;
            if ($request->filled('class')) {
                $classUpper = strtoupper($request->class);
                if (str_contains($classUpper, 'SD')) {
                    $educationLevel = 'SD';
                } elseif (str_contains($classUpper, 'SMP')) {
                    $educationLevel = 'SMP';
                } elseif (str_contains($classUpper, 'SMA') || str_contains($classUpper, 'SMK') || str_contains($classUpper, 'MA')) {
                    $educationLevel = 'SMA';
                }
            }

            $userId = null;
            if ($request->filled('email')) {
                $user = User::create([
                    'name' => $request->nama_siswa,
                    'email' => $request->email,
                    'password' => bcrypt($request->password ?? 'password123'),
                    'role' => 'student',
                    'education_level' => $educationLevel,
                ]);
                $userId = $user->id;
                Log::info('User created: ' . $user->id);
            }

            $status = $request->input('status', 'aktif');
            $accountExpiresAt = in_array($status, ['lulus', 'pindah']) ? now()->addMonths(6) : null;

            $siswa = Siswa::create([
                'nama_siswa' => $request->nama_siswa,
                'nama_orang_tua' => $request->nama_orang_tua,
                'alamat_tagihan' => $request->alamat_tagihan,
                'user_id' => $userId,
                'nis' => $request->nis,
                'class' => $request->class,
                'status' => $status,
                'account_expires_at' => $accountExpiresAt,
            ]);
            Log::info('Siswa created: ' . $siswa->id);

            DB::commit();
            return redirect()->route('students.show', $siswa->id)
                ->with('success', 'Siswa dan akun login berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menambahkan siswa: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Siswa $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Siswa $student)
    {
        $validator = Validator::make($request->all(), [
            'nama_siswa' => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:255',
            'alamat_tagihan' => 'required|string',
            'nis' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . ($student->user_id ?? 'NULL'),
            'password' => 'nullable|min:6',
            'status' => 'nullable|string|in:aktif,lulus,pindah',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $userId = $student->user_id;

            $educationLevel = null;
            if ($request->filled('class')) {
                $classUpper = strtoupper($request->class);
                if (str_contains($classUpper, 'SD')) {
                    $educationLevel = 'SD';
                } elseif (str_contains($classUpper, 'SMP')) {
                    $educationLevel = 'SMP';
                } elseif (str_contains($classUpper, 'SMA') || str_contains($classUpper, 'SMK') || str_contains($classUpper, 'MA')) {
                    $educationLevel = 'SMA';
                }
            }

            if ($request->filled('email')) {
                if ($student->user) {
                    $userData = [
                        'name' => $request->nama_siswa,
                        'email' => $request->email,
                        'education_level' => $educationLevel,
                    ];
                    if ($request->filled('password')) {
                        $userData['password'] = bcrypt($request->password);
                    }
                    $student->user->update($userData);
                } else {
                    $user = User::create([
                        'name' => $request->nama_siswa,
                        'email' => $request->email,
                        'password' => bcrypt($request->password ?? 'password123'),
                        'role' => 'student',
                        'is_profile_completed' => false,
                        'education_level' => $educationLevel,
                    ]);
                    $userId = $user->id;
                }
            } else {
                if ($student->user) {
                    // Keep name and education_level in sync even if email is not changed/passed
                    $student->user->update([
                        'name' => $request->nama_siswa,
                        'education_level' => $educationLevel,
                    ]);
                }
            }

            $status = $request->input('status', $student->status ?? 'aktif');
            $accountExpiresAt = $student->account_expires_at;

            if (in_array($status, ['lulus', 'pindah'])) {
                // Set expiration if not already set, or if status changed from aktif
                if ($student->status === 'aktif' || !$accountExpiresAt) {
                    $accountExpiresAt = now()->addMonths(6);
                }
            } else {
                // Re-activated: clear expiration
                $accountExpiresAt = null;
            }

            $student->update([
                'nama_siswa' => $request->nama_siswa,
                'nama_orang_tua' => $request->nama_orang_tua,
                'alamat_tagihan' => $request->alamat_tagihan,
                'nis' => $request->nis,
                'class' => $request->class,
                'user_id' => $userId,
                'status' => $status,
                'account_expires_at' => $accountExpiresAt,
            ]);

            DB::commit();
            return redirect()->route('students.show', $student->id)
                ->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui siswa: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui siswa: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Siswa $student)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isPimpinan()) {
            abort(403, 'Hanya pimpinan yang dapat menghapus siswa.');
        }

        if ($student->invoices()->count() > 0) {
            return redirect()->route('students.index')
                ->with('error', 'Siswa tidak dapat dihapus karena memiliki data invoice.');
        }

        if ($student->reports()->count() > 0) {
            return redirect()->route('students.index')
                ->with('error', 'Siswa tidak dapat dihapus karena memiliki data raport.');
        }

        DB::beginTransaction();
        try {
            $userToDelete = $student->user;
            
            // Delete student first to avoid foreign key issues
            $student->delete();
            
            // Then delete associated user if exists
            if ($userToDelete) {
                $userToDelete->delete();
            }
            
            DB::commit();
            return redirect()->route('students.index')
                ->with('success', 'Data siswa dan akun akses berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus siswa: ' . $e->getMessage());
            return redirect()->route('students.index')
                ->with('error', 'Gagal menghapus siswa.');
        }
    }
}
