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
        Schema::create('jam_kerja_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jam_kerja_group_id')
                ->constrained('jam_kerja_groups')
                ->onDelete('cascade');

            $table->enum('hari', [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu',
                'minggu'
            ]);

            $table->boolean('libur')->default(false);

            $table->unique(['jam_kerja_group_id', 'hari']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_kerja_schedules');
    }
};
