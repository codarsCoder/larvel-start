<?php

namespace App\Services;

use App\Models\Question;

class QuestionService
{
    public function findById($id)
    {
        return Question::findOrFail($id);
    }

    public function update(Question $question)
    {
        return $question->save();
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
