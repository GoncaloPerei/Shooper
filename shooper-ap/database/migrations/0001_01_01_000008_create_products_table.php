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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('filename')->nullable()->default(null);
            $table->foreignId('product_brand_id')->constrained('product_brands')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_category_id')->constrained('product_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('status_id')->nullable()->constrained('statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('products');
    }
};
