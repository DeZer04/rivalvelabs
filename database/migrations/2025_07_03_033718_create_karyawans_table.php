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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_karyawan')->unique()->comment('Nama Karyawan');
            $table->string('nik')->unique()->comment('Nomor Induk Karyawan');
            $table->string('email')->unique()->nullable()->comment('Email Karyawan');
            $table->string('telepon')->nullable()->comment('Nomor Telepon Karyawan');
            $table->string('alamat')->nullable()->comment('Alamat Karyawan');
            $table->foreignId('divisi_id')
                ->constrained('divisis')
                ->onDelete('cascade')
                ->comment('ID Divisi Karyawan');
            $table->date('tanggal_masuk')->nullable()->comment('Tanggal Masuk Karyawan');
            $table->date('tanggal_keluar')->nullable()->comment('Tanggal Keluar Karyawan');
            $table->string('status')->default('aktif')->comment('Status Karyawan (aktif, tidak aktif)');
            $table->string('foto')->nullable()->comment('Foto Karyawan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
