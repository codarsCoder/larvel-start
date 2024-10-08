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

    public function delete(Exam $exam)
{
    return $exam->delete();
}

    public function findById(int $id): ?Exam
    {
        return Exam::find($id);
    }

    public function getExams()
    {
        return Exam::all();
    }

    public function getExamByExamIds(array $examIds)
    {
        return Exam::whereIn('id', $examIds)->get();
    }

}
