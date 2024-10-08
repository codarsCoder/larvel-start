<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Sınav başlığı
            $table->unsignedBigInteger('exam_type_id');
            $table->unsignedBigInteger('lesson_id');
            $table->integer('question_count'); // Soru sayısı
            $table->integer('exam_duration'); // Sınav süresi (dakika cinsinden)
            $table->dateTime('start_time'); // Başlangıç zamanı
            $table->dateTime('end_time'); // Bitiş zamanı
            $table->integer('participants_count')->nullable(); // Katılımcı sayısı (boş bırakılabilir)
            $table->integer('total_point')->nullable(); // Katılımcı sayısı (boş bırakılabilir)
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->softDeletes();
            $table->timestamps();

            // $table->foreign('exam_type_id')->references('id')->on('exam_types');
            // $table->foreign('lesson_id')->references('id')->on('lessons');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
