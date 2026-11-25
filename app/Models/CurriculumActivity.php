<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumActivity extends Model
{
    protected $table = 'curriculum_activities';
    protected $fillable = ['curriculum_load_id', 'icon', 'nama', 'deskripsi', 'urutan'];

    public function curriculumLoad()
    {
        return $this->belongsTo(CurriculumLoad::class, 'curriculum_load_id');
    }
}
