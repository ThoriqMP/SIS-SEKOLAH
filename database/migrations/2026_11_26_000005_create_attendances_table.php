<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('siswa_id');
                $table->date('date');
                $table->string('status')->default('hadir'); // hadir, izin, sakit, alpha
                $table->string('notes')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->timestamps();

                $table->unique(['siswa_id', 'date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
