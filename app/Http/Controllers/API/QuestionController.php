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

        // $validated = $request->validate([
        //     'questions' => 'required|array|min:1',
        //     'questions.*.exam_id' => 'required|exists:exams,id',
        //     'questions.*.order' => 'required|integer|min:1',
        //     'questions.*.parent_id' => 'nullable|exists:questions,id',
        //     'questions.*.option_count' => 'required|integer|min:1',
        //     'questions.*.true_answer' => 'required|integer|min:1|max:questions.*.option_count',
        //     'questions.*.answer_count' => 'required|integer|min:0',
        //     'questions.*.true_answer_count' => 'required|integer|min:0',
        //     'questions.*.participants_count' => 'required|integer|min:0',
        //     'questions.*.point' => 'required|integer|min:0',
        //     'questions.*.question_img' => 'nullable|file|mimes:jpg,png,jpeg|max:2048'
        // ]);

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

    public function update(Request $request, $id)
    {
        // Soruyu bul
        $question = $this->questionService->findById($id);
        if(!$question) {
            return response()->json([
                'status' => 404,
                'message' => 'Question not found',
            ]);
        }
        // Eğer soru görseli varsa eski dosyayı sil ve yenisini kaydet
        if ($request->hasFile('question_img')) {
            // Eski görsel dosyasını sil
            if (!empty($question->question_img)) {
                $this->fileService->deleteFile($question->question_img);
            }

            // Yeni dosyayı kaydet ve dosya yolunu soruya ata
            $file = $request->file('question_img');
            $filePath = $this->fileService->storeImage($file, 'questions');
            $question->question_img = $filePath;
        }

        // Diğer bilgileri güncelle
        $question->exam_id = $request->input('exam_id', $question->exam_id);
        $question->order = $request->input('order', $question->order);
        $question->parent_id = $request->input('parent_id', $question->parent_id);
        $question->option_count = $request->input('option_count', $question->option_count);
        $question->true_answer = $request->input('true_answer', $question->true_answer);
        $question->answer_count = $request->input('answer_count', $question->answer_count);
        $question->true_answer_count = $request->input('true_answer_count', $question->true_answer_count);
        $question->participants_count = $request->input('participants_count', $question->participants_count);
        $question->point = $request->input('point', $question->point);

        // Soruyu kaydet
        $this->questionService->update($question);

        return response()->json([
            'status' => 200,
            'message' => 'Question updated successfully',
        ]);
    }

    public function delete($id)
    {
        try {
        // Soruyu bul
        $question = $this->questionService->findById($id);
        if(!$question) {
            return response()->json([
                'status' => 404,
                'message' => 'Question not found',
            ]);
        }
        // Eğer soru görseli varsa, eski dosyayı sil
        if (!empty($question->question_img)) {
            $this->fileService->deleteFile($question->question_img);
        }

        // Soruyu sil
        $this->questionService->delete($question);

        return response()->json([
            'status' => 200,
            'message' => 'Question deleted successfully',
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
