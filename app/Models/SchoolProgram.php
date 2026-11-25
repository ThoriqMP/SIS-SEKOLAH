<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProgram extends Model
{
    protected $table = 'school_programs';
    protected $fillable = ['jenjang', 'judul', 'deskripsi', 'catatan_kaki', 'urutan'];
}
