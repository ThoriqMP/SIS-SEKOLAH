<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiniyahReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_report_id',
        'surah_id',
        'start_ayah',
        'end_ayah',
        'mutu_code',
        'description'
    ];

    public function studentReport()
    {
        return $this->belongsTo(StudentReport::class);
    }
}
