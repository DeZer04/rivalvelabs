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
        Schema::create('warna_anyams', function (Blueprint $table) {
            $table->id();
            $table->string('nama_warna_anyam')->unique();
            $table->foreignId('jenis_anyam_id')
                  ->constrained('jenis_anyams')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warna_anyams');
    }
};
