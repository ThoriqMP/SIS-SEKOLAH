<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityGrade extends Model
{
    protected $fillable = [
        'probing_activity_id',
        'phase_name',
        'indicator_name',
        'grade',
        'sort_order',
    ];

    public function probingActivity()
    {
        return $this->belongsTo(ProbingActivity::class);
    }
}
