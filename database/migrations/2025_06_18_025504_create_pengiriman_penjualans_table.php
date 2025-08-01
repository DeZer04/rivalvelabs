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
        Schema::create('pengiriman_penjualans', function (Blueprint $table) {
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
            $table->foreignId('pesanan_penjualan_id')
                ->nullable()
                ->constrained('pesanan_penjualans')
                ->onDelete('cascade')
                ->comment('Optional foreign key to pesanan_penjualans table, can be null if not associated with a pesanan');
            $table->string('nomor_pengiriman')
                ->unique()
                ->comment('Nomor pengiriman yang unik untuk setiap pengiriman');
            $table->date('tanggal_pengiriman')
                ->comment('Tanggal pengiriman dibuat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_penjualans');
    }
};
