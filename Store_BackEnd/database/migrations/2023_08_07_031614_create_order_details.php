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
        Schema::create('order_details', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            $table->string('product_id');
            $table->string('order_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->biginteger('quantity')->default(1);
            $table->float('unit_price')->default(1);
            $table->float('total_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropifExists('order_details');
    }
};
