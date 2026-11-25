<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamDraft extends Model
{
    protected $fillable = [
        'teacher_id',
        'exam_id',
        'draft_data',
    ];

    protected $casts = [
        'draft_data' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
