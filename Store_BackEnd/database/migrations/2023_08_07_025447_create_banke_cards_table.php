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
        Schema::create('banke_cards', function (Blueprint $table) {
            $table->string('id')->unique()->primary();
            $table->string('cardNumber')->unique();
            $table->string('expiryDate');
            $table->string('cardHolderName');
            $table->string('CVV');
            $table->string('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banke_cards');
    }
};
