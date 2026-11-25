# Modul Sekolah (Hasil Salinan Terpisah dari Says-Apps)

Folder ini berisi salinan terpisah modul **Sekolah** dari `Says-Apps` agar dapat dikelola/dikembangkan secara independen tanpa mengganggu aplikasi `Says-Apps` yang sedang aktif berjalan di server.

> **Catatan:** Folder ini sudah dimasukkan ke `.gitignore` pada repository utama `Says-Apps`, dan tidak ada file asli di `Says-Apps` yang dihapus atau diubah.

## Struktur Isi
- `app/Http/Controllers/Student/` — Portal Siswa (dashboard, tagihan/invoice, rapor, tugas, ujian, video conference)
- `app/Http/Controllers/Teacher/` — Portal Guru / Pengajar
- `app/Http/Controllers/Admin/` — Controller Admin Sekolah (akademik, rapor, PBL, registrasi siswa, surat keterangan, compro)
- `app/Http/Controllers/*.php` — Controller utama sekolah (`StudentController`, `RegistrationController`, `InvoiceController`, `SchoolProfileController`, `SubjectController`, `Expense*`, `ComproController`, `DashboardController`, `ServiceController`, `ChatController`)
- `app/Models/` — Model domain sekolah (akademik, rapor, siswa, ujian, tugas, invoice, keuangan sekolah, profil sekolah)
- `resources/views/` — View Blade modul sekolah (`student`, `students`, `teacher`, `school-profile`, `surat-keterangan`, `invoices`, `lessons`, `subjects`, `compro`, `admin`, `services`, `expense_categories`)
- `routes/web.php` — Route khusus modul sekolah berbasis path prefix (tanpa subdomain)
- `routes/web_sekolah.php` — Alias pemanggil langsung ke `routes/web.php`
