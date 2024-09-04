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
            $table->string('lesson'); // Ders adı
            $table->integer('question_count'); // Soru sayısı
            $table->integer('exam_duration'); // Sınav süresi (dakika cinsinden)
            $table->dateTime('start_time'); // Başlangıç zamanı
            $table->dateTime('end_time'); // Bitiş zamanı
            $table->integer('participants_count')->nullable(); // Katılımcı sayısı (boş bırakılabilir)
            $table->integer('total_point')->nullable(); // Katılımcı sayısı (boş bırakılabilir)
            $table->timestamps();

            $table->foreign('exam_type_id')->references('id')->on('exam_types');

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
