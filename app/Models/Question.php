<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'question_img',
        'exam_id',
        'order',
        'parent_id',
        'option_count',
        'true_answer',
        'answer_count',
        'true_answer_count',
        'participants_count',
        'point',
        'deleted_at',
    ];
}
