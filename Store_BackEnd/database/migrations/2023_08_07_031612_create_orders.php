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
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            $table->string('title');
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->float('total_price')->default(0);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        //
    }
};
