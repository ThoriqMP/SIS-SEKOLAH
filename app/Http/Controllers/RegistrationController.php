<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentRegistration;
use App\Models\RegistrationFinancialData;
use App\Models\RegistrationVehicleAsset;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran online (Multi-Step Wizard).
     */
    public function create()
    {
        $feeSchemes = config('registration.schemes', []);
        return view('compro.registration-form', compact('feeSchemes'));
    }

    /**
     * Proses simpan formulir gabungan wizard + buat pengajuan keringanan & akun sementara.
     */
    public function store(Request $request)
    {
        $rules = [
            // Modal Awal & Slide 1
            'jenjang'                 => 'required|in:SD,SMP,SMA',
            'jenis_pendaftar'        => 'required|in:baru,pindahan',
            'nama_sekolah_sebelumnya' => 'required_if:jenis_pendaftar,pindahan|nullable|string|max:255',

            'nama_lengkap'  => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            
            // Slide 3 — Skema Belajar & Keringanan
            'skema_belajar' => 'required|in:offline,online,hybrid',
            'mengajukan_keringanan' => 'nullable|boolean',
            'persentase_keringanan_diajukan' => 'required_if:mengajukan_keringanan,1,true|nullable|in:15,25,35',

            // Slide 4 — Pembuatan Akun & Kontak
            'no_telpon_wa'  => 'required|string|max:20',
            'email'         => 'required|email|unique:users,email|unique:student_registrations,email',
            'password'      => 'required|string|min:8|confirmed',
        ];

        // Validasi Kondisional Slide 3a jika mengajukan keringanan
        $mengajukanKeringanan = filter_var($request->input('mengajukan_keringanan'), FILTER_VALIDATE_BOOLEAN);

        if ($mengajukanKeringanan) {
            $rules['ayah_pekerjaan']     = 'required|string|max:255';
            $rules['ibu_pekerjaan']      = 'required|string|max:255';
            $rules['jumlah_tanggungan']  = 'required|integer|min:1';
            $rules['daya_listrik_watt']  = 'required|integer|min:100';
            $rules['vehicles']           = 'nullable|array';
            $rules['vehicles.*.jenis']   = 'required_with:vehicles|string|max:50';
            $rules['vehicles.*.merk']    = 'nullable|string|max:100';
            $rules['vehicles.*.tahun']   = 'nullable|integer|min:1900|max:' . (date('Y') + 1);
        }

        $messages = [
            'jenjang.required'                        => 'Jenjang pendidikan wajib dipilih.',
            'jenis_pendaftar.required'                => 'Jenis pendaftar (Murid Baru / Pindahan) wajib dipilih.',
            'nama_sekolah_sebelumnya.required_if'     => 'Nama sekolah sebelumnya wajib diisi untuk murid pindahan.',
            'nama_lengkap.required'                   => 'Nama lengkap calon siswa wajib diisi.',
            'jenis_kelamin.required'                  => 'Jenis kelamin wajib dipilih.',
            'skema_belajar.required'                  => 'Silakan pilih skema belajar.',
            'persentase_keringanan_diajukan.required_if' => 'Silakan pilih skema persentase keringanan (15%, 20%, atau 30%).',
            'no_telpon_wa.required'                   => 'Nomor telepon/WhatsApp wajib diisi.',
            'email.required'                          => 'Email wajib diisi.',
            'email.unique'                            => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.required'                       => 'Password akun wajib diisi.',
            'password.min'                            => 'Password minimal harus 8 karakter.',
            'password.confirmed'                      => 'Konfirmasi password tidak cocok.',
            'ayah_pekerjaan.required'                 => 'Pekerjaan/Jabatan Ayah wajib diisi untuk pengajuan keringanan.',
            'ibu_pekerjaan.required'                  => 'Pekerjaan/Jabatan Ibu wajib diisi untuk pengajuan keringanan.',
            'jumlah_tanggungan.required'              => 'Jumlah tanggungan keluarga wajib diisi.',
            'daya_listrik_watt.required'              => 'Daya listrik rumah wajib diisi.',
        ];

        $request->validate($rules, $messages);

        // Ambil snapshot biaya dasar dari config berdasarkan jenjang & skema_belajar
        $schemesConfig = config('registration.schemes');
        $jenjang = $request->jenjang ?? 'SMP';
        $selectedScheme = $request->skema_belajar ?? 'offline';

        $schemeData = $schemesConfig[$jenjang][$selectedScheme] 
            ?? $schemesConfig['SMP'][$selectedScheme] 
            ?? ['uang_gedung' => 13800000, 'spp_bulanan' => 1500000, 'kegiatan_semester' => 6525000];

        $nomor = null;
        $registration = null;

        DB::transaction(function () use ($request, $schemeData, $mengajukanKeringanan, &$nomor, &$registration) {
            $nomor = StudentRegistration::generateNomor();

            $registration = StudentRegistration::create([
                'nomor_pendaftaran' => $nomor,
                'email'             => $request->email,
                'tahun_pelajaran'   => date('Y') . '/' . (date('Y') + 1),
                'status'            => 'pending',
                'user_id'           => null,
                'password_hash'     => Hash::make($request->password),

                // Modal Awal
                'jenjang'                 => $request->jenjang,
                'jenis_pendaftar'        => $request->jenis_pendaftar,
                'nama_sekolah_sebelumnya' => $request->jenis_pendaftar === 'pindahan' ? $request->nama_sekolah_sebelumnya : null,

                // Skema & Biaya
                'skema_belajar'           => $request->skema_belajar,
                'biaya_uang_gedung'       => $schemeData['uang_gedung'],
                'biaya_spp'               => $schemeData['spp_bulanan'],
                'biaya_kegiatan_semester' => $schemeData['kegiatan_semester'],

                // Keringanan
                'mengajukan_keringanan'          => $mengajukanKeringanan,
                'status_keringanan'             => $mengajukanKeringanan ? 'pending' : 'none',
                'persentase_keringanan_diajukan' => $mengajukanKeringanan ? (int) $request->persentase_keringanan_diajukan : null,

                // Slide 1 — A. Data Anak
                'nama_lengkap'   => $request->nama_lengkap,
                'nama_panggilan' => $request->nama_panggilan,
                'tempat_lahir'   => $request->tempat_lahir,
                'tanggal_lahir'  => $request->tanggal_lahir,
                'jenis_kelamin'  => $request->jenis_kelamin,
                'anak_ke'        => $request->anak_ke,
                'agama'          => $request->agama,
                'golongan_darah' => $request->golongan_darah,
                'berat_badan'    => $request->berat_badan,
                'tinggi_badan'   => $request->tinggi_badan,
                'no_telpon_wa'   => $request->no_telpon_wa,
                'alamat_rumah'   => $request->alamat_rumah,
                'tinggal_dengan' => $request->tinggal_dengan,

                // Slide 1 — B. Sekolah Asal
                'asal_sekolah'        => $request->jenis_pendaftar === 'pindahan' ? $request->nama_sekolah_sebelumnya : null,
                'alamat_sekolah'      => $request->alamat_sekolah,
                'nama_kepala_sekolah' => $request->nama_kepala_sekolah,
                'nisn'                => $request->nisn,
                'status_sekolah'      => $request->status_sekolah,
                'sistem_pendidikan'   => $request->sistem_pendidikan,
                'lama_belajar'        => $request->lama_belajar,

                // Slide 2 — C. Ayah & Ibu
                'nama_ayah'       => $request->nama_ayah,
                'ttl_ayah'        => $request->ttl_ayah,
                'pekerjaan_ayah'  => $request->pekerjaan_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'agama_ayah'      => $request->agama_ayah,
                'hp_ayah'         => $request->hp_ayah,
                'alamat_ayah'     => $request->alamat_ayah,

                'nama_ibu'        => $request->nama_ibu,
                'ttl_ibu'         => $request->ttl_ibu,
                'pekerjaan_ibu'   => $request->pekerjaan_ibu,
                'pendidikan_ibu'  => $request->pendidikan_ibu,
                'agama_ibu'       => $request->agama_ibu,
                'hp_ibu'          => $request->hp_ibu,
                'alamat_ibu'      => $request->alamat_ibu,

                'submitted_at' => now(),
            ]);

            // Save Slide 3a — Financial Data & Vehicles if applicable
            if ($mengajukanKeringanan) {
                RegistrationFinancialData::create([
                    'student_registration_id' => $registration->id,
                    'ayah_tempat_kerja'       => $request->ayah_tempat_kerja,
                    'ayah_pekerjaan'          => $request->ayah_pekerjaan,
                    'ibu_tempat_kerja'        => $request->ibu_tempat_kerja,
                    'ibu_pekerjaan'           => $request->ibu_pekerjaan,
                    'penghasilan_gabungan'    => $request->penghasilan_gabungan,
                    'jumlah_tanggungan'       => $request->jumlah_tanggungan ?? 1,
                    'daya_listrik_watt'       => $request->daya_listrik_watt ?? 900,
                ]);

                if ($request->has('vehicles') && is_array($request->vehicles)) {
                    foreach ($request->vehicles as $veh) {
                        if (!empty($veh['jenis'])) {
                            RegistrationVehicleAsset::create([
                                'student_registration_id' => $registration->id,
                                'jenis_kendaraan'         => $veh['jenis'],
                                'merk_tipe'               => $veh['merk'] ?? null,
                                'tahun'                   => !empty($veh['tahun']) ? (int)$veh['tahun'] : null,
                            ]);
                        }
                    }
                }
            }
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'           => true,
                'nomor_pendaftaran' => $nomor,
                'redirect_url'      => route('registration.success', ['nomor' => $nomor]),
            ]);
        }

        return redirect()->route('registration.success', ['nomor' => $nomor]);
    }

    /**
     * Tampilkan halaman sukses pendaftaran beserta nomor registrasi.
     */
    public function success($nomor)
    {
        $registration = StudentRegistration::with(['financialData', 'vehicleAssets'])
            ->where('nomor_pendaftaran', $nomor)
            ->firstOrFail();

        return view('compro.registration-success', compact('registration'));
    }

    /**
     * API JSON untuk melacak status pendaftaran + status keringanan.
     */
    public function track($nomor)
    {
        $registration = StudentRegistration::where('nomor_pendaftaran', $nomor)->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor pendaftaran tidak ditemukan. Pastikan penulisan benar (contoh: REG-2026-0001).'
            ], 404);
        }

        $activateUrl = url('/student/aktivasi-akun?nomor=' . $registration->nomor_pendaftaran . '&email=' . urlencode($registration->email));

        $keringananInfo = null;
        if ($registration->mengajukan_keringanan) {
            $statusKeringananLabel = match($registration->status_keringanan) {
                'pending'  => 'Pengajuan Keringanan Biaya sedang ditinjau',
                'approved' => 'Pengajuan Keringanan Disetujui (' . ($registration->persentase_keringanan_disetujui ?? $registration->persentase_keringanan_diajukan) . '%)',
                'rejected' => 'Pengajuan Keringanan Ditolak',
                default    => 'Tidak ada pengajuan',
            };

            $keringananInfo = [
                'mengajukan'            => true,
                'status'                => $registration->status_keringanan,
                'status_label'          => $statusKeringananLabel,
                'persentase_diajukan'  => $registration->persentase_keringanan_diajukan,
                'persentase_disetujui' => $registration->persentase_keringanan_disetujui,
                'catatan_admin'         => $registration->catatan_keringanan_admin,
            ];
        }

        return response()->json([
            'success'           => true,
            'nomor_pendaftaran' => $registration->nomor_pendaftaran,
            'nama_lengkap'      => $registration->nama_lengkap,
            'skema_belajar'     => ucfirst($registration->skema_belajar),
            'status'            => $registration->status,
            'status_label'      => match($registration->status) {
                'pending'  => 'Menunggu Review',
                'reviewed' => 'Sedang Direview',
                'approved' => 'Diterima',
                'rejected' => 'Ditolak',
                default    => 'Tidak Diketahui'
            },
            'catatan_admin'    => $registration->catatan_admin,
            'keringanan'       => $keringananInfo,
            'activate_url'     => $activateUrl,
        ]);
    }

    /**
     * Form aktivasi akun siswa (di subdomain student).
     */
    public function showActivationForm(Request $request)
    {
        $nomor = $request->query('nomor');
        $email = $request->query('email');

        $registration = StudentRegistration::where('nomor_pendaftaran', $nomor)
            ->where('email', $email)
            ->first();

        if (!$registration || $registration->status !== 'approved') {
            return redirect()->route('student.index')->with('error', 'Tautan aktivasi tidak valid atau pendaftaran belum disetujui.');
        }

        return view('student.auth.activate', compact('registration'));
    }

    /**
     * Proses aktivasi akun (di subdomain student).
     */
    public function activate(Request $request)
    {
        $request->validate([
            'nomor_pendaftaran' => 'required|string',
            'email'             => 'required|email',
            'password'          => 'required|string|min:8|confirmed',
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $registration = StudentRegistration::where('nomor_pendaftaran', $request->nomor_pendaftaran)
            ->where('email', $request->email)
            ->first();

        if (!$registration || $registration->status !== 'approved') {
            return redirect()->back()->with('error', 'Aktivasi gagal. Status pendaftaran tidak valid.');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Akun user untuk email ini tidak ditemukan. Silakan hubungi admin.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('student.complete-profile')
            ->with('success', 'Akun belajar Anda berhasil diaktifkan! Silakan lengkapi profil pribadi.');
    }
}
