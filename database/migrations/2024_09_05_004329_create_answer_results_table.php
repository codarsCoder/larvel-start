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
        Schema::create('answer_results', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // users tablosu ile ilişki
            $table->unsignedBigInteger('question_id'); // questions tablosu ile ilişki
            $table->string('answer'); // Kullanıcının verdiği cevap
            $table->boolean('answer_statu'); // Cevap durumu (doğru/yanlış gibi)
            $table->timestamps(); // created_at ve updated_at kolonları

            // Foreign key constraints (without onDelete cascade)
            // $table->foreign('user_id')->references('id')->on('users'); // users tablosu ile ilişki
            // $table->foreign('question_id')->references('id')->on('questions'); // questions tablosu ile ilişki
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_results');
    }
};
