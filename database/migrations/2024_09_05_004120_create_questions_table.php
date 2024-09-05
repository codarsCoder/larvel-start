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
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('question_img')->nullable(); // Soru resmi (nullable yapılabilir, bazı sorular görsel içermeyebilir)
            $table->unsignedBigInteger('exam_id'); // exams tablosuyla ilişki için
            $table->integer('order'); // Soru sırası
            $table->unsignedBigInteger('parent_id')->default(0); // Ana soru ID (varsayılan olarak 0)
            $table->integer('option_count'); // Şık sayısı
            $table->integer('true_answer'); // Doğru cevap (şık numarası)
            $table->integer('answer_count')->nullable(); // Verilen cevap sayısı
            $table->integer('true_answer_count')->nullable(); // Doğru cevap sayısı
            $table->integer('participants_count')->nullable(); // Katılımcı sayısı
            $table->integer('point')->nullable(); // Soru puanı
            $table->timestamps();

            // Foreign key constraints (without onDelete)
            $table->foreign('exam_id')->references('id')->on('exams'); // exams tablosuyla ilişki
            $table->foreign('parent_id')->references('id')->on('questions'); // Ana soruyla ilişki
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
