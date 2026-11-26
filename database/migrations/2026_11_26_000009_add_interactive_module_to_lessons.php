<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lessons')) {
            Schema::table('lessons', function (Blueprint $table) {
                if (!Schema::hasColumn('lessons', 'type')) {
                    $table->string('type')->default('interactive');
                }
                if (!Schema::hasColumn('lessons', 'gdrive_url')) {
                    $table->string('gdrive_url')->nullable();
                }
                if (!Schema::hasColumn('lessons', 'order_position')) {
                    $table->integer('order_position')->default(1);
                }
                if (!Schema::hasColumn('lessons', 'module_data')) {
                    $table->json('module_data')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        //
    }
};
