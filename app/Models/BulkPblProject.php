<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkPblProject extends Model
{
    protected $fillable = [
        'teacher_id',
        'semester_project_id',
        'period',
        'kelas',
        'tema',
        'location',
        'description',
        'pbl_data',
        'status'
    ];

    protected $casts = [
        'pbl_data' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function semesterProject()
    {
        return $this->belongsTo(SemesterProject::class);
    }
}
