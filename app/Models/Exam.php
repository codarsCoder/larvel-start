<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'exam_type_id',
        'lesson_id',
        'question_count',
        'exam_duration',
        'start_time',
        'end_time',
        'participants_count',
        'total_point',
    ];
}
