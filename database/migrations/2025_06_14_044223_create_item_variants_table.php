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
        Schema::create('item_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('nama_variant')->unique();
            $table->string('gambar_item')->nullable();
            $table->text('deskripsi_item')->nullable();
            $table->foreignId('jenis_kayu_id')
                  ->constrained('jenis_kayus')
                  ->onDelete('cascade');
            $table->foreignId('grade_kayu_id')
                  ->constrained('grade_kayus')
                  ->onDelete('cascade');
            $table->foreignId('finishing_kayu_id')
                  ->constrained('finishing_kayus')
                  ->onDelete('cascade');
            $table->foreignId('jenis_anyam_id')
                  ->constrained('jenis_anyams')
                  ->onDelete('cascade');
            $table->foreignId('warna_anyam_id')
                  ->constrained('warna_anyams')
                  ->onDelete('cascade');
            $table->float('harga')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_variants');
    }
};
