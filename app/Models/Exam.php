<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'teacher_id',
        'report_subject_id',
        'class',
        'period',
        'title',
        'type', // harian, tengah_semester, akhir_semester, remedial
        'target_period',
        'duration',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(ReportSubject::class, 'report_subject_id');
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function collaborators()
    {
        return $this->morphToMany(User::class, 'collaboratorable', 'activity_collaborators', 'collaboratorable_id', 'teacher_id');
    }
}
