<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('student_reports') && !Schema::hasColumn('student_reports', 'period')) {
            Schema::table('student_reports', function (Blueprint $table) {
                $table->string('period')->nullable()->after('academic_year');
            });
        }

        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->integer('kkm')->default(75);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('education_level')->default('SD');
                $table->string('grade')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('course_badges')) {
            Schema::create('course_badges', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id');
                $table->string('name');
                $table->string('icon')->nullable();
                $table->string('color')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('lesson_progress')) {
            Schema::create('lesson_progress', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lesson_id');
                $table->unsignedBigInteger('user_id');
                $table->boolean('is_completed')->default(false);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('billing_settings')) {
            Schema::create('billing_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('student_services')) {
            Schema::create('student_services', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_siswa');
                $table->unsignedBigInteger('id_layanan');
                $table->decimal('custom_price', 15, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('academic_years')) {
            Schema::create('academic_years', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('raport_settings')) {
            Schema::create('raport_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('academic_year_id')->nullable();
                $table->string('semester')->nullable();
                $table->string('headmaster_name')->nullable();
                $table->string('headmaster_nip')->nullable();
                $table->string('city')->nullable();
                $table->date('report_date')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('semester_projects')) {
            Schema::create('semester_projects', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('academic_year_id')->nullable();
                $table->string('semester')->nullable();
                $table->string('month')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('bulk_pbl_projects')) {
            Schema::create('bulk_pbl_projects', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('period')->nullable();
                $table->string('status')->default('draft');
                $table->json('data')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('project_templates')) {
            Schema::create('project_templates', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('structure')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('report_subjects')) {
            Schema::create('report_subjects', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('report_category_id')->nullable();
                $table->string('name');
                $table->integer('kkm')->default(75);
                $table->integer('order')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('academic_grade_ranges')) {
            Schema::create('academic_grade_ranges', function (Blueprint $table) {
                $table->id();
                $table->string('grade_letter');
                $table->integer('min_score')->default(0);
                $table->integer('max_score')->default(100);
                $table->string('predicate')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('non_academic_mutu_labels')) {
            Schema::create('non_academic_mutu_labels', function (Blueprint $table) {
                $table->id();
                $table->string('label');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('teacher_note_suggestions')) {
            Schema::create('teacher_note_suggestions', function (Blueprint $table) {
                $table->id();
                $table->string('category')->nullable();
                $table->text('note');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('surat_keterangan')) {
            Schema::create('surat_keterangan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('siswa_id')->nullable();
                $table->string('nomor_surat')->nullable();
                $table->string('jenis')->default('cibi');
                $table->date('tanggal_surat')->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('surat_keterangan_settings')) {
            Schema::create('surat_keterangan_settings', function (Blueprint $table) {
                $table->id();
                $table->string('kepala_sekolah')->nullable();
                $table->string('nip_kepala_sekolah')->nullable();
                $table->string('kota')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('school_hero_settings')) {
            Schema::create('school_hero_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('school_pillars')) {
            Schema::create('school_pillars', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('ikon')->nullable();
                $table->integer('urutan')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('school_programs')) {
            Schema::create('school_programs', function (Blueprint $table) {
                $table->id();
                $table->string('jenjang')->nullable();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('catatan_kaki')->nullable();
                $table->integer('urutan')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('curriculum_loads')) {
            Schema::create('curriculum_loads', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->nullable();
                $table->string('nama');
                $table->text('deskripsi')->nullable();
                $table->integer('urutan')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('curriculum_activities')) {
            Schema::create('curriculum_activities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('curriculum_load_id')->nullable();
                $table->string('nama');
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('investment_items')) {
            Schema::create('investment_items', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->decimal('nominal', 15, 2)->default(0);
                $table->text('keterangan')->nullable();
                $table->boolean('aktif')->default(true);
                $table->integer('urutan')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('investment_promos')) {
            Schema::create('investment_promos', function (Blueprint $table) {
                $table->id();
                $table->string('label');
                $table->text('deskripsi')->nullable();
                $table->boolean('aktif')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('investment_promo_discounts')) {
            Schema::create('investment_promo_discounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('investment_promo_id');
                $table->unsignedBigInteger('investment_item_id')->nullable();
                $table->decimal('diskon_nominal', 15, 2)->default(0);
                $table->string('keterangan')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gallery_albums')) {
            Schema::create('gallery_albums', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->boolean('aktif')->default(true);
                $table->integer('urutan')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gallery_photos')) {
            Schema::create('gallery_photos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('gallery_album_id');
                $table->string('path');
                $table->string('caption')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('learning_methods')) {
            Schema::create('learning_methods', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->integer('urutan')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('learning_highlights')) {
            Schema::create('learning_highlights', function (Blueprint $table) {
                $table->id();
                $table->string('teks');
                $table->integer('urutan')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        //
    }
};
