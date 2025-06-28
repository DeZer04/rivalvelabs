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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_supplier')
                ->unique()
                ->comment('Nama unik untuk setiap supplier');
            $table->string('alamat')
                ->nullable()
                ->comment('Alamat supplier, bisa null jika tidak ada');
            $table->string('nomor_telepon')
                ->nullable()
                ->comment('Nomor telepon supplier, bisa null jika tidak ada');
            $table->json('kode_supplier')
                ->nullable()
                ->comment('Kode unik untuk supplier, bisa berupa kode internal atau eksternal, bisa null jika tidak ada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
