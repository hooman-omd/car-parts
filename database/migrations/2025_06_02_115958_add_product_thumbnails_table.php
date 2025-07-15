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
        Schema::table('product_thumbnails', function (Blueprint $table) {
            $table->enum('thumbnail_input',['thumbnail_1','thumbnail_2','thumbnail_3','thumbnail_4'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_thumbnails', function (Blueprint $table) {
            $table->dropColumn('product_thumbnails');
        });
    }
};
