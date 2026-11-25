<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProbingActivity extends Model
{
    protected $fillable = [
        'student_report_id',
        'semester_project_id',
        'activity_name',
        'activity_title',
        'location',
        'description',
        'image_path',
        'pbl_data',      // kept for backward compatibility (deprecated, will be null after migration)
        'personality',
        'intelligence',
        'strength',
    ];

    protected $casts = [
        'pbl_data'     => 'array',
        'personality'  => 'array',
        'intelligence' => 'array',
        'strength'     => 'array',
    ];

    public function report()
    {
        return $this->belongsTo(StudentReport::class, 'student_report_id');
    }

    public function semesterProject()
    {
        return $this->belongsTo(SemesterProject::class);
    }

    public function activityGrades()
    {
        return $this->hasMany(ActivityGrade::class)->orderBy('sort_order');
    }

    public function activityDocumentations()
    {
        return $this->hasMany(ActivityDocumentation::class)->orderBy('sort_order');
    }

    /**
     * Kembalikan kegiatan yang dikelompokkan per phase_name.
     * Digunakan oleh PDF template.
     */
    public function getGradesByPhaseAttribute(): array
    {
        return $this->activityGrades
            ->groupBy('phase_name')
            ->map(fn($items) => $items->values())
            ->toArray();
    }
}
