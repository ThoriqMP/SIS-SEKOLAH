<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicGradeRange extends Model
{
    use HasFactory;

    protected $fillable = ['letter', 'min_score', 'description'];
}
