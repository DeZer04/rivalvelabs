<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->foreignId('jam_kerja_group_id')
                ->nullable()
                ->after('id')
                ->constrained('jam_kerja_groups')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropForeign(['jam_kerja_group_id']);
            $table->dropColumn('jam_kerja_group_id');
        });
    }
};
