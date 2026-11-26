<?php

namespace Database\Seeders;

use App\Helpers\ReportHelper;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Layanan;
use App\Models\ProfilSekolah;
use App\Models\ReportCategory;
use App\Models\ReportGrade;
use App\Models\ReportSubject;
use App\Models\Siswa;
use App\Models\StudentReport;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Contoh (Superadmin, Guru, Siswa)
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@sissekolah.test'],
            [
                'name' => 'Super Admin SIS',
                'role' => 'superadmin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        $teacher = User::updateOrCreate(
            ['email' => 'guru@sissekolah.test'],
            [
                'name' => 'Guru Contoh',
                'role' => 'teacher',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        $studentUser = User::updateOrCreate(
            ['email' => 'siswa@sissekolah.test'],
            [
                'name' => 'Siswa Contoh',
                'role' => 'student',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Profil Sekolah
        if (ProfilSekolah::count() === 0) {
            ProfilSekolah::create([
                'nama_sekolah' => 'SIS SEKOLAH',
                'alamat' => 'Jl. Pendidikan Modern No. 1, Jakarta',
                'telepon' => '021-5550123',
                'email' => 'admin@sissekolah.test',
            ]);
        }

        // 3. Tahun Ajaran & Kategori Rapor
        $academicYear = AcademicYear::firstOrCreate(
            ['name' => '2025/2026'],
            ['is_active' => true]
        );

        $akademikCat = ReportCategory::firstOrCreate(['name' => 'Akademik']);
        foreach (['Diniyah', 'Ibadah', 'Project Based Learning'] as $cat) {
            ReportCategory::firstOrCreate(['name' => $cat]);
        }

        // 4. Mata Pelajaran
        $subjectNames = [
            ['name' => 'Matematika', 'code' => 'MTK', 'kkm' => 75],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND', 'kkm' => 75],
            ['name' => 'IPA Terpadu', 'code' => 'IPA', 'kkm' => 75],
            ['name' => 'Bahasa Inggris', 'code' => 'BING', 'kkm' => 75],
        ];
        $reportSubjects = [];
        foreach ($subjectNames as $idx => $s) {
            Subject::firstOrCreate(['name' => $s['name']], $s);
            $reportSubjects[] = ReportSubject::firstOrCreate(
                ['name' => $s['name'], 'report_category_id' => $akademikCat->id],
                ['kkm' => $s['kkm'], 'order' => $idx + 1]
            );
        }

        // 5. Layanan Sekolah
        $sppService = Layanan::firstOrCreate(
            ['nama_layanan' => 'SPP Bulanan'],
            ['harga' => 1250000, 'deskripsi' => 'Biaya pendidikan bulanan siswa']
        );
        $activityService = Layanan::firstOrCreate(
            ['nama_layanan' => 'Uang Kegiatan Semester'],
            ['harga' => 500000, 'deskripsi' => 'Biaya kegiatan proyek & praktikum semester']
        );

        // 6. Data Dummy Siswa
        $dummyStudents = [
            [
                'user_id' => $studentUser->id,
                'nama_siswa' => 'Siswa Contoh',
                'nis' => '20260101',
                'class' => '5 SD',
                'nama_orang_tua' => 'Bapak Hendra Wijaya',
                'telepon_orang_tua' => '081234567890',
                'alamat_tagihan' => 'Jl. Pendidikan No. 1, Jakarta',
                'status' => 'aktif',
            ],
            [
                'user_id' => null,
                'nama_siswa' => 'Aisyah Putri Ramadhani',
                'nis' => '20260102',
                'class' => '5 SD',
                'nama_orang_tua' => 'Ibu Siti Aminah',
                'telepon_orang_tua' => '081234567891',
                'alamat_tagihan' => 'Jl. Melati No. 12, Bandung',
                'status' => 'aktif',
            ],
            [
                'user_id' => null,
                'nama_siswa' => 'Zaidan Pratama',
                'nis' => '20260103',
                'class' => '7 SMP',
                'nama_orang_tua' => 'Bapak Ahmad Fauzi',
                'telepon_orang_tua' => '081234567892',
                'alamat_tagihan' => 'Jl. Anggrek No. 8, Depok',
                'status' => 'aktif',
            ],
            [
                'user_id' => null,
                'nama_siswa' => 'Nabila Azzahra',
                'nis' => '20260104',
                'class' => '8 SMP',
                'nama_orang_tua' => 'Ibu Dewi Lestari',
                'telepon_orang_tua' => '081234567893',
                'alamat_tagihan' => 'Jl. Kenanga No. 24, Bekasi',
                'status' => 'aktif',
            ],
            [
                'user_id' => null,
                'nama_siswa' => 'Raka Mahendra',
                'nis' => '20260105',
                'class' => '10 SMA',
                'nama_orang_tua' => 'Bapak Budi Santoso',
                'telepon_orang_tua' => '081234567894',
                'alamat_tagihan' => 'Jl. Sudirman No. 45, Tangerang',
                'status' => 'aktif',
            ],
        ];

        $students = [];
        foreach ($dummyStudents as $ds) {
            $students[] = Siswa::updateOrCreate(
                ['nis' => $ds['nis']],
                $ds
            );
        }

        // 7. Data Dummy Rapor & Nilai
        $period = ReportHelper::getCurrentPeriod();
        foreach ($students as $idx => $s) {
            $report = StudentReport::firstOrCreate(
                [
                    'siswa_id' => $s->id,
                    'report_category_id' => $akademikCat->id,
                    'period' => $period,
                ],
                [
                    'teacher_id' => $teacher->id,
                    'academic_year_id' => $academicYear->id,
                    'summary_notes' => 'Menunjukkan perkembangan belajar yang sangat baik dan aktif dalam proyek kelas.',
                    'is_draft' => false,
                ]
            );

            if ($report->grades()->count() === 0) {
                $baseScores = [94, 91, 89, 93];
                foreach ($reportSubjects as $subIdx => $rSub) {
                    $score = $baseScores[$subIdx] - ($idx % 3);
                    ReportGrade::create([
                        'student_report_id' => $report->id,
                        'report_subject_id' => $rSub->id,
                        'score' => $score,
                        'predicate' => $score >= 90 ? 'A' : 'B',
                        'description' => 'Sangat baik dalam memahami kompetensi ' . $rSub->name . '.',
                    ]);
                }
            }
        }

        // 8. Data Dummy Invoice (Tagihan SPP)
        $statuses = ['paid', 'sent', 'overdue', 'paid', 'sent'];
        foreach ($students as $idx => $s) {
            $noInv = 'INV-2026-' . str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT);
            $status = $statuses[$idx] ?? 'sent';

            $invoice = Invoice::firstOrCreate(
                ['no_invoice' => $noInv],
                [
                    'tanggal_invoice' => now()->startOfMonth()->toDateString(),
                    'jatuh_tempo' => now()->startOfMonth()->addDays(9)->toDateString(),
                    'id_siswa' => $s->id,
                    'status' => $status,
                    'is_auto_generated' => true,
                    'grand_total' => $idx === 0 ? 1750000 : 1250000,
                    'potongan' => 0,
                    'payment_date' => $status === 'paid' ? now()->subDays(2) : null,
                ]
            );

            if ($invoice->invoiceDetails()->count() === 0) {
                InvoiceDetail::create([
                    'id_invoice' => $invoice->id,
                    'id_layanan' => $sppService->id,
                    'qty' => 1,
                    'harga_satuan' => $sppService->harga,
                    'subtotal' => $sppService->harga,
                    'deskripsi_tambahan' => 'SPP Bulan Berjalan',
                ]);
                if ($idx === 0) {
                    InvoiceDetail::create([
                        'id_invoice' => $invoice->id,
                        'id_layanan' => $activityService->id,
                        'qty' => 1,
                        'harga_satuan' => $activityService->harga,
                        'subtotal' => $activityService->harga,
                        'deskripsi_tambahan' => 'Kegiatan Proyek Semester',
                    ]);
                }
            }
        }

        // 9. Data Dummy Absensi Kehadiran (Hari ini & Kemarin)
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $attStatuses = ['hadir', 'hadir', 'izin', 'hadir', 'sakit'];

        foreach ($students as $idx => $s) {
            Attendance::firstOrCreate(
                ['siswa_id' => $s->id, 'date' => $today],
                [
                    'status' => $attStatuses[$idx] ?? 'hadir',
                    'notes' => ($attStatuses[$idx] ?? 'hadir') === 'izin' ? 'Izin acara keluarga' : (($attStatuses[$idx] ?? 'hadir') === 'sakit' ? 'Demam ringan' : null),
                    'recorded_by' => $teacher->id,
                ]
            );
            Attendance::firstOrCreate(
                ['siswa_id' => $s->id, 'date' => $yesterday],
                [
                    'status' => 'hadir',
                    'notes' => null,
                    'recorded_by' => $teacher->id,
                ]
            );
        }
    }
}
