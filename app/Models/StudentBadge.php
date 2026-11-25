<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBadge extends Model
{
    protected $table = 'student_badges';

    protected $fillable = ['student_id', 'badge_id', 'earned_at'];

    protected $casts = [
        'earned_at' => 'datetime',
    ];

    /**
     * Relasi ke siswa (user)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relasi ke lencana kursus (badge)
     */
    public function badge()
    {
        return $this->belongsTo(CourseBadge::class, 'badge_id');
    }
}
