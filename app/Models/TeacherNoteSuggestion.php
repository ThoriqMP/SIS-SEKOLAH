<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherNoteSuggestion extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'category'];
}
