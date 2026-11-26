<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'education_level',
        'grade',
        'subject_id',
        'teacher_id',
        'is_published',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function badge()
    {
        return $this->hasOne(CourseBadge::class);
    }

    public function badges()
    {
        return $this->hasMany(CourseBadge::class);
    }
}
