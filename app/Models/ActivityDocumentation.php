<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityDocumentation extends Model
{
    protected $fillable = [
        'probing_activity_id',
        'image_path',
        'caption',
        'sort_order',
    ];

    public function probingActivity()
    {
        return $this->belongsTo(ProbingActivity::class);
    }
}
