<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('exams')) {
            Schema::table('exams', function (Blueprint $table) {
                if (!Schema::hasColumn('exams', 'report_subject_id')) {
                    $table->unsignedBigInteger('report_subject_id')->nullable();
                }
                if (!Schema::hasColumn('exams', 'period')) {
                    $table->string('period')->nullable();
                }
                if (!Schema::hasColumn('exams', 'type')) {
                    $table->string('type')->default('harian');
                }
                if (!Schema::hasColumn('exams', 'target_period')) {
                    $table->string('target_period')->nullable();
                }
                if (!Schema::hasColumn('exams', 'duration')) {
                    $table->integer('duration')->default(60);
                }
                if (!Schema::hasColumn('exams', 'due_date')) {
                    $table->timestamp('due_date')->nullable();
                }
            });
        }

        if (Schema::hasTable('assignments')) {
            Schema::table('assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('assignments', 'report_subject_id')) {
                    $table->unsignedBigInteger('report_subject_id')->nullable();
                }
                if (!Schema::hasColumn('assignments', 'period')) {
                    $table->string('period')->nullable();
                }
            });
        }

        if (!Schema::hasTable('lesson_discussion_reads')) {
            Schema::create('lesson_discussion_reads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lesson_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamp('last_read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        //
    }
};
