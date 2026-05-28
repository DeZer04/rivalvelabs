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
        Schema::create('izin_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('izin_type_id')
                ->constrained('izin_types')
                ->onDelete('cascade');

            $table->string('kode');

            $table->string('nama');

            $table->boolean('perlu_jam')->default(false);
            $table->enum('override_event', [
                'masuk',
                'pulang',
                'istirahat_mulai',
                'istirahat_selesai',
                'lembur_mulai',
                'lembur_selesai',
                'none'
            ])->default('none');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_options');
    }
};
