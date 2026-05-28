<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pengiriman_penjualans', function (Blueprint $table) {
            // Ubah kolom item_id jadi nullable
            $table->unsignedBigInteger('item_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('detail_pengiriman_penjualans', function (Blueprint $table) {
            // Balik lagi jadi not nullable
            $table->unsignedBigInteger('item_id')->nullable(false)->change();
        });
    }
};