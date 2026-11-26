<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'education_level',
        'grade',
        'subject_id',
        'teacher_id',
        'is_published',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function badge()
    {
        return $this->hasOne(CourseBadge::class);
    }

    public function badges()
    {
        return $this->hasMany(CourseBadge::class);
    }

    /**
     * Check if any lesson in this course has comments unread by the given user.
     */
    public function hasUnreadCommentsForUser($userId): bool
    {
        try {
            $lessonIds = $this->lessons()->pluck('id');
            if ($lessonIds->isEmpty()) {
                return false;
            }
            $latestComment = \App\Models\LessonComment::whereIn('lesson_id', $lessonIds)
                ->where('user_id', '!=', $userId)
                ->latest()
                ->first();

            if (!$latestComment) {
                return false;
            }

            $read = \Illuminate\Support\Facades\DB::table('lesson_discussion_reads')
                ->where('lesson_id', $latestComment->lesson_id)
                ->where('user_id', $userId)
                ->first();

            return !$read || \Carbon\Carbon::parse($read->last_read_at)->lt($latestComment->created_at);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
