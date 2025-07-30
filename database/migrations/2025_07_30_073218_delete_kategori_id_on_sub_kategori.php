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
        Schema::table('sub_categories', function (Blueprint $table) {
            // Remove item_category_id column if it exists
            if (Schema::hasColumn('sub_categories', 'item_category_id')) {
                $table->dropForeign(['item_category_id']);
                $table->dropColumn('item_category_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            // Re-add kategori_id column if needed
        });
    }
};
