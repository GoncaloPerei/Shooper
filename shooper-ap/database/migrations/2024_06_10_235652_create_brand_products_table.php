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
        Schema::create('brand_products', function (Blueprint $table) {
            $table->foreignId('brand_id')->constrained('product_brands')->primary()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('product_id')->constrained('products')->primary()->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_products');
    }
};
