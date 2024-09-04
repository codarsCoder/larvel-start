<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\FileService;
use App\Services\QuestionService;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    protected $questionService;
    protected $fileService;

    public function __construct(QuestionService $questionService, FileService $fileService)
    {
        $this->questionService = $questionService;
        $this->fileService = $fileService;
    }

    public function storeMultiple(Request $request)
    {
        $validated = $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.exam_id' => 'required|exists:exams,id',
            'questions.*.order' => 'required|integer|min:1',
            'questions.*.parent_id' => 'nullable|exists:questions,id',
            'questions.*.option_count' => 'required|integer|min:1',
            'questions.*.true_answer' => 'required|integer|min:1|max:questions.*.option_count',
            'questions.*.answer_count' => 'required|integer|min:0',
            'questions.*.true_answer_count' => 'required|integer|min:0',
            'questions.*.participants_count' => 'required|integer|min:0',
            'questions.*.point' => 'required|integer|min:0',
            'questions.*.question_img' => 'nullable|file|mimes:jpg,png,jpeg|max:2048'
        ]);

        $questionsData = [];

        foreach ($request->questions as $index => $question) {
            // Görsel dosyasını kaydet ve dosya yolunu ayarla
            if ($request->hasFile("questions.$index.question_img")) {
                $file = $request->file("questions.$index.question_img");
                $filePath = $this->fileService->storeImage($file, 'questions');
                $question['question_img'] = $filePath;
            }

            $questionsData[] = $question;
        }

        // Toplu soruları ekle
        $questions = $this->questionService->createMultiple($questionsData);

        return response()->json([
            'status' => 200,
            'messages' => 'Questions created successfully',
        ]);
    }

}
