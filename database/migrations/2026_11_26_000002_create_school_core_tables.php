<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('profil_sekolah')) {
            Schema::create('profil_sekolah', function (Blueprint $table) {
                $table->id();
                $table->string('nama_sekolah')->default('SIS SEKOLAH');
                $table->string('alamat')->nullable();
                $table->string('telepon')->nullable();
                $table->string('email')->nullable();
                $table->string('logo_path')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('siswa')) {
            Schema::create('siswa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('nama_siswa');
                $table->string('nis')->nullable();
                $table->string('class')->nullable();
                $table->string('nama_orang_tua')->nullable();
                $table->text('alamat_tagihan')->nullable();
                $table->string('telepon_orang_tua')->nullable();
                $table->date('tanggal_lahir')->nullable();
                $table->string('jenis_kelamin')->nullable();
                $table->string('jenis_kelamin_orang_tua')->nullable();
                $table->date('tanggal_lahir_orang_tua')->nullable();
                $table->text('alamat_orang_tua')->nullable();
                $table->string('status')->default('aktif');
                $table->timestamp('account_expires_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('layanan')) {
            Schema::create('layanan', function (Blueprint $table) {
                $table->id();
                $table->string('nama_layanan');
                $table->decimal('harga', 15, 2)->default(0);
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('no_invoice')->unique();
                $table->date('tanggal_invoice');
                $table->date('jatuh_tempo');
                $table->unsignedBigInteger('id_siswa');
                $table->unsignedBigInteger('id_sekolah')->nullable();
                $table->string('status')->default('draft');
                $table->boolean('is_auto_generated')->default(false);
                $table->decimal('grand_total', 15, 2)->default(0);
                $table->decimal('potongan', 15, 2)->default(0);
                $table->string('payment_proof')->nullable();
                $table->timestamp('payment_date')->nullable();
                $table->text('payment_notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('invoice_details')) {
            Schema::create('invoice_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_invoice');
                $table->unsignedBigInteger('id_layanan')->nullable();
                $table->integer('qty')->default(1);
                $table->decimal('harga_satuan', 15, 2)->default(0);
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->string('deskripsi_tambahan')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('student_registrations')) {
            Schema::create('student_registrations', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_pendaftaran')->unique();
                $table->string('nama_lengkap');
                $table->string('email');
                $table->string('jenjang')->nullable();
                $table->string('status')->default('pending');
                $table->boolean('mengajukan_keringanan')->default(false);
                $table->string('status_keringanan')->nullable();
                $table->integer('persentase_keringanan_diajukan')->nullable();
                $table->integer('persentase_keringanan_disetujui')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('report_categories')) {
            Schema::create('report_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('student_reports')) {
            Schema::create('student_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('siswa_id');
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('semester')->nullable();
                $table->string('academic_year')->nullable();
                $table->boolean('is_draft')->default(true);
                $table->text('teacher_notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('report_grades')) {
            Schema::create('report_grades', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_report_id');
                $table->string('subject_name')->nullable();
                $table->decimal('score', 5, 2)->default(0);
                $table->string('grade_letter')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('diniyah_reports')) {
            Schema::create('diniyah_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_report_id');
                $table->string('surah')->nullable();
                $table->string('predicate')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ibadah_reports')) {
            Schema::create('ibadah_reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_report_id');
                $table->string('indicator')->nullable();
                $table->string('predicate')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('probing_activities')) {
            Schema::create('probing_activities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_report_id');
                $table->string('title')->nullable();
                $table->json('pbl_data')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('assignments')) {
            Schema::create('assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('class')->nullable();
                $table->timestamp('due_date')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('assignment_submissions')) {
            Schema::create('assignment_submissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('assignment_id');
                $table->unsignedBigInteger('student_id');
                $table->text('submission_text')->nullable();
                $table->string('file_path')->nullable();
                $table->decimal('score', 5, 2)->nullable();
                $table->text('feedback')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('exams')) {
            Schema::create('exams', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->string('title');
                $table->integer('duration_minutes')->default(60);
                $table->string('class')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('exam_attempts')) {
            Schema::create('exam_attempts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('exam_id');
                $table->unsignedBigInteger('student_id');
                $table->decimal('score', 5, 2)->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lessons')) {
            Schema::create('lessons', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id')->nullable();
                $table->string('title');
                $table->longText('content')->nullable();
                $table->integer('order')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lesson_comments')) {
            Schema::create('lesson_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lesson_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->text('comment');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('video_conferences')) {
            Schema::create('video_conferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->string('title');
                $table->string('room_name')->unique();
                $table->json('class')->nullable();
                $table->timestamp('scheduled_at')->nullable();
                $table->string('status')->default('scheduled');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('expense_categories')) {
            Schema::create('expense_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('expense_category_id');
                $table->string('description')->nullable();
                $table->decimal('amount', 15, 2)->default(0);
                $table->date('expense_date')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'expenses', 'expense_categories', 'video_conferences', 'lesson_comments', 'lessons',
            'exam_attempts', 'exams', 'assignment_submissions', 'assignments', 'probing_activities',
            'ibadah_reports', 'diniyah_reports', 'report_grades', 'student_reports', 'report_categories',
            'student_registrations', 'invoice_details', 'invoices', 'layanan', 'siswa', 'profil_sekolah',
        ];
        foreach ($tables as $t) {
            Schema::dropIfExists($t);
        }
    }
};
