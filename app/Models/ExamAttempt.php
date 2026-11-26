<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id',
        'siswa_id',
        'started_at',
        'completed_at',
        'score',
        'answers',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'decimal:2',
        'answers' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (ExamAttempt $attempt) {
            if (empty($attempt->student_id) && !empty($attempt->siswa_id)) {
                $attempt->student_id = $attempt->siswa_id;
            }
            if (empty($attempt->siswa_id) && !empty($attempt->student_id)) {
                $attempt->siswa_id = $attempt->student_id;
            }
        });
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
