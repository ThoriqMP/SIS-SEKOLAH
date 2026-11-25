<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTemplate extends Model
{
    protected $table = 'project_templates';

    protected $fillable = ['title', 'description', 'kegiatan'];

    protected $casts = [
        'kegiatan' => 'array'
    ];

    public function semesterProjects()
    {
        return $this->hasMany(SemesterProject::class);
    }
}
