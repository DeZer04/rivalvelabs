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
        Schema::create('izin_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('izin_type_id')->constrained();
            $table->foreignId('izin_option_id')->nullable()->constrained();
            $table->foreignId('izin_category_id')->nullable()->constrained();

            // Tanggal
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            // Jam opsional
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();

            $table->text('catatan')->nullable();

            $table->enum('status', [
                'draft',
                'diajukan',
                'disetujui',
                'ditolak'
            ])->default('draft');

            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()
                ->references('id')->on('users');

            $table->timestamps();

            $table->index(['karyawan_id', 'tanggal_mulai', 'tanggal_selesai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_requests');
    }
};
