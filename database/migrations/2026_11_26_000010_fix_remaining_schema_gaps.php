<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('exam_questions')) {
            Schema::create('exam_questions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('exam_id');
                $table->text('question_text');
                $table->string('question_type')->default('multiple_choice');
                $table->json('options')->nullable();
                $table->text('correct_answer')->nullable();
                $table->integer('points')->default(10);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('exam_drafts')) {
            Schema::create('exam_drafts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('teacher_id');
                $table->unsignedBigInteger('exam_id')->nullable();
                $table->json('draft_data')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('exam_attempts')) {
            Schema::table('exam_attempts', function (Blueprint $table) {
                if (!Schema::hasColumn('exam_attempts', 'siswa_id')) {
                    $table->unsignedBigInteger('siswa_id')->nullable();
                }
                if (!Schema::hasColumn('exam_attempts', 'started_at')) {
                    $table->timestamp('started_at')->nullable();
                }
                if (!Schema::hasColumn('exam_attempts', 'completed_at')) {
                    $table->timestamp('completed_at')->nullable();
                }
                if (!Schema::hasColumn('exam_attempts', 'answers')) {
                    $table->json('answers')->nullable();
                }
            });
        }

        if (Schema::hasTable('assignment_submissions')) {
            Schema::table('assignment_submissions', function (Blueprint $table) {
                if (!Schema::hasColumn('assignment_submissions', 'siswa_id')) {
                    $table->unsignedBigInteger('siswa_id')->nullable();
                }
            });
        }

        if (Schema::hasTable('layanan')) {
            Schema::table('layanan', function (Blueprint $table) {
                if (!Schema::hasColumn('layanan', 'harga_standar')) {
                    $table->decimal('harga_standar', 15, 2)->default(0);
                }
            });
        }

        if (Schema::hasTable('surat_keterangan_settings')) {
            Schema::table('surat_keterangan_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('surat_keterangan_settings', 'psikolog_nama')) {
                    $table->string('psikolog_nama')->nullable();
                }
                if (!Schema::hasColumn('surat_keterangan_settings', 'psikolog_jabatan')) {
                    $table->string('psikolog_jabatan')->nullable();
                }
                if (!Schema::hasColumn('surat_keterangan_settings', 'psikolog_alamat')) {
                    $table->text('psikolog_alamat')->nullable();
                }
                if (!Schema::hasColumn('surat_keterangan_settings', 'psikolog_no_himpsi')) {
                    $table->string('psikolog_no_himpsi')->nullable();
                }
            });
        }

        if (Schema::hasTable('competency_assessments')) {
            Schema::table('competency_assessments', function (Blueprint $table) {
                if (!Schema::hasColumn('competency_assessments', 'student_report_id')) {
                    $table->unsignedBigInteger('student_report_id')->nullable();
                }
                if (!Schema::hasColumn('competency_assessments', 'competency_name')) {
                    $table->string('competency_name')->nullable();
                }
                if (!Schema::hasColumn('competency_assessments', 'score')) {
                    $table->integer('score')->default(0);
                }
            });
        }
    }

    public function down(): void
    {
        //
    }
};
