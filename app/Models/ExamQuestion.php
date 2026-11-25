<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'points',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    protected $hidden = [
        'correct_answer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
