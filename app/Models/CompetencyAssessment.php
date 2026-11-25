<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyAssessment extends Model
{
    protected $fillable = [
        'student_report_id',
        'competency_name',
        'score',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    public function studentReport()
    {
        return $this->belongsTo(StudentReport::class);
    }
}
