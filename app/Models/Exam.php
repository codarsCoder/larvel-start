<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'deleted_at',
    ];
}
