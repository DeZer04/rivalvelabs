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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_category_id')
                  ->constrained('item_categories')
                  ->onDelete('cascade');
            $table->foreignId('sub_category_id')
                  ->constrained('sub_categories')
                  ->onDelete('cascade');
            $table->string('master_gambar_item')->nullable();
            $table->string('nama_item')->unique();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('depth')->nullable();
            $table->float('seat_height')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
