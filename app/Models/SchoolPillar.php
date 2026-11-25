<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolPillar extends Model
{
    protected $table = 'school_pillars';
    protected $fillable = ['icon', 'judul', 'deskripsi', 'urutan'];
}
