<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurriculumLoad extends Model
{
    protected $table = 'curriculum_loads';
    protected $fillable = ['kode', 'nama', 'persentase', 'deskripsi', 'warna', 'icon', 'urutan'];

    public function activities()
    {
        return $this->hasMany(CurriculumActivity::class, 'curriculum_load_id')->orderBy('urutan');
    }
}
