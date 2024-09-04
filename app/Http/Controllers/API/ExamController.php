<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
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
            return response()->json($exam, 200);
        } else {
            return response()->json(['message' => 'Exam not found'], 404);
        }

        } catch (\Exception $e) {
            \Log::info($e);
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
