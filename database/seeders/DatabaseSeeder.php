<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\ProfilSekolah;
use App\Models\ReportCategory;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin SIS',
                'email' => 'superadmin@sissekolah.test',
                'role' => 'superadmin',
            ],
            [
                'name' => 'Guru Contoh',
                'email' => 'guru@sissekolah.test',
                'role' => 'teacher',
            ],
            [
                'name' => 'Siswa Contoh',
                'email' => 'siswa@sissekolah.test',
                'role' => 'student',
            ],
        ];

        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'role' => $u['role'],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]
            );

            if ($u['role'] === 'student') {
                Siswa::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_siswa' => $u['name'],
                        'nis' => '20260101',
                        'class' => '5 SD',
                        'nama_orang_tua' => 'Wali Siswa Contoh',
                        'alamat_tagihan' => 'Jl. Pendidikan No. 1',
                        'status' => 'aktif',
                    ]
                );
            }
        }

        if (ProfilSekolah::count() === 0) {
            ProfilSekolah::create([
                'nama_sekolah' => 'SIS SEKOLAH',
                'email' => 'admin@sissekolah.test',
            ]);
        }

        foreach (['Akademik', 'Diniyah', 'Ibadah', 'Project Based Learning'] as $cat) {
            ReportCategory::firstOrCreate(['name' => $cat]);
        }

        if (Layanan::count() === 0) {
            Layanan::create([
                'nama_layanan' => 'SPP Bulanan',
                'harga' => 1250000,
                'deskripsi' => 'Biaya pendidikan bulanan siswa',
            ]);
        }
    }
}
