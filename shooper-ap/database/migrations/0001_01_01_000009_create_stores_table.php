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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('website_url')->unique();
            $table->string('title_tag')->nullable()->default(null);
            $table->string('price_tag')->nullable()->default(null);
            $table->string('scratched_price_tag')->nullable()->default(null);
            $table->string('filename')->nullable()->default(null);
            $table->foreignId('status_id')->nullable()->constrained('statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('stores');
    }
};
