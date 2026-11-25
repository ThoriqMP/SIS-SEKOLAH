<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningHighlight extends Model
{
    protected $table = 'learning_highlights';
    protected $fillable = ['teks', 'urutan'];
}
