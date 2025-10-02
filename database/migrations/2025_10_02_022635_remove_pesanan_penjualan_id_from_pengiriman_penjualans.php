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
        Schema::table('pengiriman_penjualans', function (Blueprint $table) {
            $table->dropForeign(['pesanan_penjualan_id']);
            $table->dropColumn('pesanan_penjualan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman_penjualans', function (Blueprint $table) {
            $table->foreignId('pesanan_penjualan_id')
                ->nullable()
                ->constrained('pesanan_penjualans')
                ->onDelete('cascade')
                ->comment('Optional foreign key to pesanan_penjualans table, can be null if not associated with a pesanan');
        });
    }
};
