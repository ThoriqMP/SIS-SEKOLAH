<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin SIS',
                'email' => 'superadmin@sissekolah.test',
                'role' => 'superadmin',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Guru Contoh',
                'email' => 'guru@sissekolah.test',
                'role' => 'teacher',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Siswa Contoh',
                'email' => 'siswa@sissekolah.test',
                'role' => 'student',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => $user['password'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
