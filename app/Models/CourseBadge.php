<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseBadge extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'icon',
        'color',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
