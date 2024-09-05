<?php

namespace App\Services;

use App\Models\Question;

class QuestionService
{
    public function multipleStore(array $questions): array
    {
        $createdQuestions = [];

        foreach ($questions as $questionData) {
            $createdQuestions[] = Question::create($questionData);
        }

        return $createdQuestions;
    }
}
