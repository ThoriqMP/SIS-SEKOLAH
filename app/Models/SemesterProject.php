<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterProject extends Model
{
    protected $fillable = [
        'academic_year_id',
        'project_template_id',
        'title',
        'description',
        'kegiatan',
        'sort_order',
    ];

    protected $casts = [
        'kegiatan' => 'array',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function projectTemplate()
    {
        return $this->belongsTo(ProjectTemplate::class);
    }

    public function probingActivities()
    {
        return $this->hasMany(ProbingActivity::class);
    }

    public function bulkPblProjects()
    {
        return $this->hasMany(BulkPblProject::class);
    }
}
