<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman_pesanan_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengiriman_penjualan_id')
                ->constrained('pengiriman_penjualans')
                ->onDelete('cascade');
            $table->foreignId('pesanan_penjualan_id')
                ->constrained('pesanan_penjualans')
                ->onDelete('cascade');
            $table->timestamps();

            $table->unique(
                ['pengiriman_penjualan_id', 'pesanan_penjualan_id'],
                'uniq_pengiriman_pesanan'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman_pesanan_penjualans');
    }
};
