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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('purchase_id');
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3);
            $table->timestamps();

            // Foreign key constraints, but without cascading on delete
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('exam_id')->references('id')->on('exams');
            // $table->foreign('purchase_id')->references('id')->on('purchases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
