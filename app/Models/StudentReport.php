<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentReport extends Model
{
    protected $fillable = [
        'siswa_id',
        'report_category_id',
        'teacher_id',
        'academic_year_id',
        'period',
        'summary_notes',
        'notes_developed',
        'notes_need_development',
        'pts_pas_summary',
        'remedial_summary',
        'harian_summary',
        'academic_type',
        'is_first_pbl',
        'is_final_pbl',
        'is_draft',
    ];

    public function student()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'report_category_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function grades()
    {
        return $this->hasMany(ReportGrade::class);
    }

    public function probingActivities()
    {
        return $this->hasMany(ProbingActivity::class)->orderBy('created_at');
    }

    public function competencyAssessments()
    {
        return $this->hasMany(CompetencyAssessment::class);
    }

    public function diniyahReports()
    {
        return $this->hasMany(DiniyahReport::class);
    }

    public function ibadahReport()
    {
        return $this->hasOne(IbadahReport::class);
    }
}
