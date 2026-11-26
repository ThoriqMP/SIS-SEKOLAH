<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('billing_settings') && Schema::hasColumn('billing_settings', 'key')) {
            Schema::drop('billing_settings');
        }

        if (!Schema::hasTable('billing_settings')) {
            Schema::create('billing_settings', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_generation_day')->default(1);
                $table->integer('invoice_due_day')->default(10);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('activity_collaborators')) {
            Schema::create('activity_collaborators', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('collaboratorable_id')->nullable();
                $table->string('collaboratorable_type')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('teacher_id')->nullable();
                $table->timestamps();
            });
        } else {
            if (!Schema::hasColumn('activity_collaborators', 'collaboratorable_id')) {
                Schema::table('activity_collaborators', function (Blueprint $table) {
                    $table->unsignedBigInteger('collaboratorable_id')->nullable();
                    $table->string('collaboratorable_type')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_collaborators');
    }
};
