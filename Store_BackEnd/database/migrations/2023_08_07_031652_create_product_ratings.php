<?php

use illuminate\Database\Migrations\Migration;
use illuminate\Database\Schema\Blueprint;
use illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            $table->string('user_id');
            $table->string('product_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropifExists('product_ratings');
    }
};
