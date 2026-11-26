<?php

namespace Database\Seeders;

use App\Helpers\ReportHelper;
use App\Models\AcademicYear;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Layanan;
use App\Models\Lesson;
use App\Models\ProfilSekolah;
use App\Models\ReportCategory;
use App\Models\ReportGrade;
use App\Models\ReportSubject;
use App\Models\Siswa;
use App\Models\StudentReport;
use App\Models\Subject;
use App\Models\User;
use App\Models\VideoConference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            ['is_active' => DB::raw('true')]
        );

        $akademikCat = ReportCategory::firstOrCreate(['name' => 'Akademik']);
        foreach (['Diniyah', 'Ibadah', 'Project Based Learning'] as $cat) {
            ReportCategory::firstOrCreate(['name' => $cat]);
        }

        // 4. Mata Pelajaran Lengkap (Umum / Akademi Negeri & Madrasah Aliyah / MA)
        $subjectNames = [
            // Umum / Akademi Negeri
            ['name' => 'Matematika', 'code' => 'MTK', 'kkm' => 75],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND', 'kkm' => 75],
            ['name' => 'Bahasa Inggris', 'code' => 'BING', 'kkm' => 75],
            ['name' => 'IPA Terpadu', 'code' => 'IPA', 'kkm' => 75],
            ['name' => 'IPS Terpadu', 'code' => 'IPS', 'kkm' => 75],
            ['name' => 'Pendidikan Pancasila (PPKn)', 'code' => 'PPKN', 'kkm' => 75],
            ['name' => 'Fisika', 'code' => 'FIS', 'kkm' => 75],
            ['name' => 'Kimia', 'code' => 'KIM', 'kkm' => 75],
            ['name' => 'Biologi', 'code' => 'BIO', 'kkm' => 75],
            ['name' => 'Ekonomi', 'code' => 'EKO', 'kkm' => 75],
            ['name' => 'Sosiologi', 'code' => 'SOS', 'kkm' => 75],
            ['name' => 'Geografi', 'code' => 'GEO', 'kkm' => 75],
            ['name' => 'Sejarah Indonesia', 'code' => 'SEJ', 'kkm' => 75],
            ['name' => 'Informatika / TIK', 'code' => 'TIK', 'kkm' => 75],
            ['name' => 'Seni Budaya', 'code' => 'SBD', 'kkm' => 75],
            ['name' => 'Pendidikan Jasmani (PJOK)', 'code' => 'PJOK', 'kkm' => 75],
            // Madrasah Aliyah (MA) & Keagamaan
            ['name' => "Al-Qur'an Hadits", 'code' => 'QH', 'kkm' => 75],
            ['name' => 'Akidah Akhlak', 'code' => 'AA', 'kkm' => 75],
            ['name' => 'Fikih', 'code' => 'FIK', 'kkm' => 75],
            ['name' => 'Sejarah Kebudayaan Islam (SKI)', 'code' => 'SKI', 'kkm' => 75],
            ['name' => 'Bahasa Arab', 'code' => 'BAR', 'kkm' => 75],
            ['name' => "Tahfidz Al-Qur'an", 'code' => 'THZ', 'kkm' => 80],
        ];
        $reportSubjects = [];
        foreach ($subjectNames as $idx => $s) {
            Subject::firstOrCreate(['name' => $s['name']], $s);
            $rSub = ReportSubject::firstOrCreate(
                ['name' => $s['name'], 'report_category_id' => $akademikCat->id],
                ['kkm' => $s['kkm'], 'order' => $idx + 1]
            );
            if ($idx < 4) {
                $reportSubjects[] = $rSub;
            }
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
                    'is_draft' => DB::raw('false'),
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
                    'is_auto_generated' => DB::raw('true'),
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

        // 10. Data Dummy Ruangan WebRTC (Video Conference)
        $rooms = [
            [
                'room_name' => 'room-matematika-interaktif',
                'title' => 'Kelas Matematika Interaktif (Akademi Negeri)',
                'scheduled_at' => now()->addMinutes(30),
            ],
            [
                'room_name' => 'room-tahfidz-fikih-ma',
                'title' => 'Bimbingan Tahfidz & Fikih (MA)',
                'scheduled_at' => now()->addHours(2),
            ],
            [
                'room_name' => 'room-english-speaking-1on1',
                'title' => 'English Speaking Club 1-on-1',
                'scheduled_at' => now()->addDay(),
            ],
        ];

        foreach ($rooms as $room) {
            VideoConference::firstOrCreate(
                ['room_name' => $room['room_name']],
                [
                    'teacher_id' => $teacher->id,
                    'title' => $room['title'],
                    'class' => [(int) $studentUser->id],
                    'scheduled_at' => $room['scheduled_at'],
                    'is_active' => DB::raw('true'),
                ]
            );
        }

        // 11. Data Dummy Ruang Belajar (Kursus & Materi untuk SD, SMP, SMA)
        $coursesData = [
            ['title' => 'Matematika Kreatif Kelas 5', 'education_level' => 'SD', 'grade' => '5', 'lesson' => 'Konsep Pecahan & Desimal'],
            ['title' => 'IPA & Sains Terapan', 'education_level' => 'SMP', 'grade' => '7', 'lesson' => 'Ekosistem & Energi Terbarukan'],
            ['title' => "Fikih & Al-Qur'an Hadits MA", 'education_level' => 'SMA', 'grade' => '10', 'lesson' => 'Kaidah Dasar Muamalah'],
        ];

        foreach ($coursesData as $cData) {
            $course = Course::firstOrCreate(
                ['title' => $cData['title']],
                [
                    'description' => 'Modul pembelajaran interaktif untuk jenjang ' . $cData['education_level'],
                    'education_level' => $cData['education_level'],
                    'grade' => $cData['grade'],
                    'teacher_id' => $teacher->id,
                    'is_published' => DB::raw('true'),
                ]
            );

            Lesson::firstOrCreate(
                ['course_id' => $course->id, 'title' => $cData['lesson']],
                [
                    'content' => 'Materi pengantar dan latihan soal interaktif untuk topik ' . $cData['lesson'] . '.',
                    'order' => 1,
                ]
            );
        }

        // 12. Data Dummy Tugas & Ujian Online
        if (!empty($reportSubjects)) {
            $dummyAssignments = [
                [
                    'title' => 'Proyek Sains Terapan: Energi Hijau',
                    'subject' => $reportSubjects[2]->id ?? $reportSubjects[0]->id,
                    'class' => '5 SD',
                    'desc' => 'Buat ringkasan dan foto eksperimen sederhana tentang energi terbarukan.',
                    'days' => 5,
                ],
                [
                    'title' => 'Latihan Soal Pecahan & Persentase',
                    'subject' => $reportSubjects[0]->id,
                    'class' => '5 SD',
                    'desc' => 'Kerjakan 10 soal cerita pecahan pada modul Ruang Belajar.',
                    'days' => 3,
                ],
                [
                    'title' => 'Esai Bahasa Indonesia: Literasi Digital',
                    'subject' => $reportSubjects[1]->id ?? $reportSubjects[0]->id,
                    'class' => '7 SMP',
                    'desc' => 'Tulis esai 300 kata tentang etika berinternet di lingkungan sekolah.',
                    'days' => 6,
                ],
            ];

            foreach ($dummyAssignments as $da) {
                Assignment::firstOrCreate(
                    ['title' => $da['title']],
                    [
                        'teacher_id' => $teacher->id,
                        'report_subject_id' => $da['subject'],
                        'class' => $da['class'],
                        'period' => $period,
                        'description' => $da['desc'],
                        'due_date' => now()->addDays($da['days']),
                    ]
                );
            }

            $dummyExams = [
                [
                    'title' => 'Evaluasi Tengah Semester Matematika',
                    'subject' => $reportSubjects[0]->id,
                    'class' => '5 SD',
                    'type' => 'tengah_semester',
                    'duration' => 60,
                    'days' => 7,
                ],
                [
                    'title' => 'Kuis Harian IPA Terpadu',
                    'subject' => $reportSubjects[2]->id ?? $reportSubjects[0]->id,
                    'class' => '7 SMP',
                    'type' => 'harian',
                    'duration' => 30,
                    'days' => 4,
                ],
                [
                    'title' => 'Penilaian Akhir Semester Bahasa Inggris',
                    'subject' => $reportSubjects[3]->id ?? $reportSubjects[0]->id,
                    'class' => '10 SMA',
                    'type' => 'akhir_semester',
                    'duration' => 90,
                    'days' => 10,
                ],
            ];

            foreach ($dummyExams as $de) {
                Exam::firstOrCreate(
                    ['title' => $de['title']],
                    [
                        'teacher_id' => $teacher->id,
                        'report_subject_id' => $de['subject'],
                        'class' => $de['class'],
                        'period' => $period,
                        'type' => $de['type'],
                        'duration' => $de['duration'],
                        'due_date' => now()->addDays($de['days']),
                    ]
                );
            }
        }
    }
}
