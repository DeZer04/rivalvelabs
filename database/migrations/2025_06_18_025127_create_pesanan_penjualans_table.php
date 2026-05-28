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
        Schema::create('pesanan_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')
                ->constrained('buyers')
                ->onDelete('cascade')
                ->comment('Foreign key to buyers table');
            $table->foreignId('penawaran_penjualan_id')
                ->nullable()
                ->constrained('penawaran_penjualans')
                ->onDelete('cascade')
                ->comment('Optional foreign key to penawaran_penjualans table, can be null if not associated with a penawaran');
            $table->string('nomor_pesanan')
                ->unique()
                ->comment('Nomor pesanan yang unik untuk setiap pesanan');
            $table->date('tanggal_pesanan')
                ->comment('Tanggal pesanan dibuat');
            $table->enum('status_pesanan', ['menunggu diproses', 'sebagian terproses', 'terproses'])
                ->default('menunggu diproses')
                ->comment('Status pesanan: menunggu diproses, sebagian terproses, atau terproses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_penjualans');
    }
};
