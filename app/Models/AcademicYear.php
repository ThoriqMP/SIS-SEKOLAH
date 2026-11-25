<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['year_name', 'semester', 'is_active'];

    public function raportSetting()
    {
        return $this->hasOne(RaportSetting::class);
    }

    public function semesterProjects()
    {
        return $this->hasMany(SemesterProject::class);
    }

    public function studentReports()
    {
        return $this->hasMany(StudentReport::class);
    }
}
