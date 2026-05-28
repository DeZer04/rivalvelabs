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
        Schema::create('group_kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('nama_group_kriteria')->unique()->comment('Nama Group Kriteria');
            $table->boolean('is_calculated')->default(false)->comment('Apakah Group Kriteria Ini Dihitung?');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_kriterias');
    }
};
