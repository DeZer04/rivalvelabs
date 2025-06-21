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
        Schema::create('detail_pesanan_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_penjualan_id')
                ->constrained('pesanan_penjualans')
                ->onDelete('cascade')
                ->comment('Foreign key to pesanan_penjualans table');
            $table->foreignId('item_id')
                ->constrained('items')
                ->onDelete('cascade')
                ->comment('Foreign key to items table');
            $table->foreignId('item_variant_id')
                ->nullable()
                ->constrained('item_variants')
                ->onDelete('set null')
                ->comment('Foreign key to item_variants table, nullable if no variant is selected');
            $table->integer('jumlah_item')
                ->comment('Jumlah item dalam pesanan');
            $table->integer('jumlah_item_dikirim')
                ->default(0)
                ->comment('Jumlah item yang telah dikirim, default 0');
            $table->decimal('harga_satuan', 10, 2)
                ->comment('Harga satuan item dalam pesanan');
            $table->decimal('diskon', 10, 2)
                ->default(0.00)
                ->comment('Diskon yang diberikan untuk item dalam pesanan, default 0.00');
            $table->decimal('total_harga', 10, 2)
                ->comment('Total harga untuk jumlah item dalam pesanan');
            $table->string('keterangan')
                ->nullable()
                ->comment('Keterangan tambahan untuk detail pesanan, bisa null jika tidak ada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan_penjualans');
    }
};
