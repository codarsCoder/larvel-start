<?php

namespace App\Services;

use App\Models\Exam;

class ExamService
{
    public function create(array $data): Exam
    {
        return Exam::create($data);
    }

    public function update(int $id, array $data): ?Exam
    {
        $exam = Exam::find($id);

        if ($exam) {
            $exam->update($data);
            return $exam;
        }

        return null;
    }
}
