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
        Schema::create('penawaran_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')
                ->constrained('buyers')
                ->onDelete('cascade');
            $table->string('nomor_penawaran')
                ->unique()
                ->comment('Nomor penawaran yang unik untuk setiap penawaran');
            $table->date('tanggal_penawaran')
                ->comment('Tanggal penawaran dibuat');
            $table->enum('status_penawaran', ['draft','pending', 'accepted', 'rejected'])
                ->default('pending')
                ->comment('Status penawaran: draft, pending, accepted, atau rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penawaran_penjualans');
    }
};
