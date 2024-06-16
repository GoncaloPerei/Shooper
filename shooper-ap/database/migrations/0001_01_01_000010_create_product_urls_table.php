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
        Schema::create('product_urls', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('name')->unique()->nullable()->default(null);
            $table->double('price')->nullable()->default(null);
            $table->double('scratched_price')->nullable()->default(null);
            $table->foreignId('product_id')->nullable()->constrained('products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('store_id')->nullable()->constrained('stores')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('product_urls', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('product_urls');
    }
};
