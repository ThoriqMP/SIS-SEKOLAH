-- Jalankan di Supabase Dashboard -> SQL Editor -> New Query
-- Membuat tabel users (jika belum ada), menambahkan kolom role, dan mengisi akun contoh

CREATE TABLE IF NOT EXISTS public.users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role VARCHAR(255) NOT NULL DEFAULT 'student',
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

ALTER TABLE public.users ADD COLUMN IF NOT EXISTS role VARCHAR(255) NOT NULL DEFAULT 'student';

-- Akun Contoh (Password untuk semua akun: password123)
INSERT INTO public.users (name, email, role, email_verified_at, password, created_at, updated_at)
VALUES
    ('Super Admin SIS', 'superadmin@sissekolah.test', 'superadmin', NOW(), '$2y$12$nG33P7swabGtVHk4Thyi8OH3B484NpyAhUYaLqT9FZchY6o8FTCAK', NOW(), NOW()),
    ('Guru Contoh', 'guru@sissekolah.test', 'teacher', NOW(), '$2y$12$nG33P7swabGtVHk4Thyi8OH3B484NpyAhUYaLqT9FZchY6o8FTCAK', NOW(), NOW()),
    ('Siswa Contoh', 'siswa@sissekolah.test', 'student', NOW(), '$2y$12$nG33P7swabGtVHk4Thyi8OH3B484NpyAhUYaLqT9FZchY6o8FTCAK', NOW(), NOW())
ON CONFLICT (email) DO UPDATE SET
    name = EXCLUDED.name,
    role = EXCLUDED.role,
    password = EXCLUDED.password,
    updated_at = NOW();
