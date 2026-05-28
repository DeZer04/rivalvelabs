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
        Schema::create('detail_pengiriman_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengiriman_penjualan_id')
                ->constrained('pengiriman_penjualans')
                ->onDelete('cascade')
                ->comment('Foreign key to pengiriman_penjualans table');
            $table->foreignId('detail_pesanan_penjualan_id')
                ->nullable()
                ->constrained('detail_pesanan_penjualans')
                ->onDelete('set null')
                ->comment('Referensi ke detail item dalam pesanan penjualan (opsional jika pengiriman langsung)');
            $table->foreignId('item_id')
                ->constrained('items')
                ->onDelete('cascade')
                ->comment('Foreign key to items table');
            $table->foreignId('item_variant_id')
                ->nullable()
                ->constrained('item_variants')
                ->onDelete('cascade')
                ->comment('Referensi ke varian item (opsional jika item tidak memiliki varian)');
            $table->integer('jumlah_item')
                ->comment('Jumlah item dalam pengiriman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengiriman_penjualans');
    }
};
