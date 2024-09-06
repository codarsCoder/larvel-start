<?php

namespace App\Services;

use App\Models\Question;

class QuestionService
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function findById($id)
    {
        return Question::findOrFail($id);
    }

    public function update(Question $question)
    {
        return $question->save();
    }
    public function getQuestions($exam_id)
    {
        return Question::where('exam_id', $exam_id)->get()->map(function ($question) {
            $question->question_img = $this->fileService->getImageUrl($question->question_img);
            return $question;
        });
    }

    public function delete(Question $question)
    {
        return $question->delete();
    }

    public function createMultiple(array $questions): array
    {
        $createdQuestions = [];

        foreach ($questions as $questionData) {
            $createdQuestions[] = Question::create($questionData);
        }

        return $createdQuestions;
    }
}
