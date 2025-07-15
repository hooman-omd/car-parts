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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code',100);
            $table->integer('discount_value');
            $table->integer('max_uses')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot table for discount_code_product relationship
        Schema::create('discount_code_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_code_id')->constrained('discount_codes')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot table for discount_code_category relationship
        Schema::create('discount_code_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_code_id')->constrained('discount_codes')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_code_category');
        Schema::dropIfExists('discount_code_product');
        Schema::dropIfExists('discount_codes');
    }
};
