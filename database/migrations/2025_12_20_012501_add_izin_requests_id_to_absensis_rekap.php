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
        Schema::table('absensi_rekaps', function (Blueprint $table) {
            $table->foreignId('izin_request_id')
                ->nullable()
                ->constrained('izin_requests')
                ->cascadeOnDelete()
                ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_rekaps', function (Blueprint $table) {
            $table->dropForeign(['izin_request_id']);
            $table->dropColumn('izin_request_id');
        });
    }
};
