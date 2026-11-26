<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('video_conferences') && !Schema::hasColumn('video_conferences', 'is_active')) {
            Schema::table('video_conferences', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }

        if (!Schema::hasTable('competency_assessments')) {
            Schema::create('competency_assessments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('siswa_id')->nullable();
                $table->string('title')->nullable();
                $table->decimal('score', 5, 2)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('video_conferences') && Schema::hasColumn('video_conferences', 'is_active')) {
            Schema::table('video_conferences', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
