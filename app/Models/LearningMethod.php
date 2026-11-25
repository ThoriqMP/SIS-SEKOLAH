<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningMethod extends Model
{
    protected $table = 'learning_methods';
    protected $fillable = ['icon', 'judul', 'deskripsi', 'urutan'];
}
