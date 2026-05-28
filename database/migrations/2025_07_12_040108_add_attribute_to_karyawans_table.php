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
        Schema::create('Jabatan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_jabatan')->unique()->comment('Nama Jabatan');
            $table->timestamps();
            $table->softDeletes()->comment('Tanggal Hapus Jabatan (Soft Delete)');
        });


        Schema::table('karyawans', function (Blueprint $table) {
            $table->boolean('jenis_kelamin')->default(true)->comment('Jenis Kelamin Karyawan (true untuk laki-laki, false untuk perempuan)')->after('foto');
            $table->date('tanggal_lahir')->nullable()->comment('Tanggal Lahir Karyawan')->after('jenis_kelamin');
            $table->foreignId('jabatan_id')
                ->nullable()
                ->constrained('Jabatan')
                ->onDelete('set null')
                ->comment('ID Jabatan Karyawan')
                ->after('divisi_id');
            $table->softDeletes()->comment('Tanggal Hapus Karyawan (Soft Delete)')->after('tanggal_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Jabatan');
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn('jenis_kelamin');
            $table->dropColumn('tanggal_lahir');
            $table->dropSoftDeletes();
        });
    }
};
