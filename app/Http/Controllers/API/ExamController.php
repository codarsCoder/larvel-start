<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ExamService;
use App\Services\FileService;
use App\Services\PurchaseService;
use App\Services\QuestionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;
    protected $fileService;
    protected $questionService;

    protected $purchaseService;

    public function __construct(ExamService $examService, FileService $fileService, QuestionService $questionService, PurchaseService $purchaseService)
    {
        $this->examService = $examService;
        $this->fileService = $fileService;
        $this->questionService = $questionService;
        $this->purchaseService = $purchaseService;
    }

    public function store(Request $request)
    {
        try {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type_id' => 'required|exists:exam_types,id',
            'lesson_id' => 'required|integer|min:1',
            'question_count' => 'required|integer|min:1',
            'exam_duration' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'participants_count' => 'nullable|integer|min:0',
            'total_point' => 'nullable|integer|min:0',
        ]);

        $exam = $this->examService->create($validated);
        if ($exam) {
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'exam_id' => $exam->id
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'failed',
            ]);
        }

        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }

    }

    public function update(Request $request, $id)
    {
        try {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'exam_type_id' => 'sometimes|required|exists:exam_types,id',
            'lesson_id' => 'sometimes|required|integer|min:1',
            'question_count' => 'sometimes|required|integer|min:1',
            'exam_duration' => 'sometimes|required|integer|min:1',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'participants_count' => 'nullable|integer|min:0',
            'total_point' => 'nullable|integer|min:0',
        ]);


        $exam = $this->examService->update($id, $validated);

        if ($exam) {
            return response()->json(['status' => 200, 'message' => 'Exam updated successfully']);
        } else {
            return response()->json(['status' => 404, 'message' => 'Exam not found'], 404);
        }

        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function deleteExam($id)
{
    try {
        // Sınavı bul
        $exam = $this->examService->findById($id);

        if (!$exam) {
            return response()->json([
                'status' => 404,
                'message' => 'Exam not found',
            ]);
        }

        // Sınava ait soruları bul
        $questions = $this->questionService->getQuestions($exam->id);

        // Her soruya ait görselleri sil
        foreach ($questions as $question) {
            if (!empty($question->question_img)) {
                // Görsel dosyasını sil
                $this->fileService->deleteFile($question->question_img);
            }
            // Soruyu sil
            $this->questionService->delete($question);
        }

        // Son olarak sınavı sil
        $this->examService->delete($exam);

        return response()->json([
            'status' => 200,
            'message' => 'Exam and related questions deleted successfully',
        ]);
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return response()->json([
            'status' => 500,
            'message' => 'Failed to delete exam',
        ]);
    }
}

    public function getExam($id)
    {
        $exam = $this->examService->findById($id);
        return response()->json([
            'status' => 200,
            'exams' => $exam
        ]);
    }
    public function getAllExams()
    {
        $exams = $this->examService->getExams();
        return response()->json([
            'status' => 200,
            'exams' => $exams
        ]);
    }
    public function getExams()
    {
        $user = Auth::user();
        $purchases = $this->purchaseService->getPurchasedsByUser($user->id);
        // $exams = $this->examService->getExams();
        return response()->json([
            'status' => 200,
            'exams' => $purchases
        ]);
    }


}
