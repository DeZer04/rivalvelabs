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
        Schema::create('grade_kayus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_grade_kayu')->unique();
            $table->foreignId('jenis_kayu_id')
                  ->constrained('jenis_kayus')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_kayus');
    }
};
