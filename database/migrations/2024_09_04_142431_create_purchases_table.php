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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('transaction_id')->unique();
            $table->string('transaction_token')->nullable();
            $table->string('payment_method');
            $table->string('payment_type');
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3);
            $table->string('status');
            $table->text('description')->nullable();
            $table->timestamps();

            // Foreign key constraints, but without cascading on delete
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
