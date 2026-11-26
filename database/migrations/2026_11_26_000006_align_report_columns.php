<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('student_reports')) {
            Schema::table('student_reports', function (Blueprint $table) {
                if (!Schema::hasColumn('student_reports', 'report_category_id')) {
                    $table->unsignedBigInteger('report_category_id')->nullable();
                }
                if (!Schema::hasColumn('student_reports', 'teacher_id')) {
                    $table->unsignedBigInteger('teacher_id')->nullable();
                }
                if (!Schema::hasColumn('student_reports', 'academic_year_id')) {
                    $table->unsignedBigInteger('academic_year_id')->nullable();
                }
                if (!Schema::hasColumn('student_reports', 'summary_notes')) {
                    $table->text('summary_notes')->nullable();
                }
            });
        }

        if (Schema::hasTable('report_grades')) {
            Schema::table('report_grades', function (Blueprint $table) {
                if (!Schema::hasColumn('report_grades', 'report_subject_id')) {
                    $table->unsignedBigInteger('report_subject_id')->nullable();
                }
                if (!Schema::hasColumn('report_grades', 'predicate')) {
                    $table->string('predicate')->nullable();
                }
                if (!Schema::hasColumn('report_grades', 'description')) {
                    $table->text('description')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        //
    }
};
