<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaportSetting extends Model
{
    protected $fillable = ['academic_year_id', 'report_date', 'place'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
