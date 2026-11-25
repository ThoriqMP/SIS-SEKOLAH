<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoConference extends Model
{
    protected $fillable = [
        'teacher_id',
        'class',
        'title',
        'room_name',
        'scheduled_at',
        'is_active',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_active' => 'boolean',
        'class' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function getTargetStudentsAttribute()
    {
        if (!is_array($this->class) || empty($this->class)) {
            return [];
        }

        $studentNames = \App\Models\Siswa::whereIn('user_id', $this->class)
            ->pluck('nama_siswa')
            ->toArray();

        $teacherNames = \App\Models\User::whereIn('id', $this->class)
            ->whereIn('role', ['guru', 'staff', 'pimpinan'])
            ->pluck('name')
            ->toArray();

        return array_merge($studentNames, $teacherNames);
    }
}
