<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonComment extends Model
{
    protected $fillable = ['lesson_id', 'user_id', 'parent_id', 'content'];

    /**
     * Relasi ke materi pelajaran (lesson)
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Relasi ke pengguna (user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke balasan komentar (replies)
     */
    public function replies()
    {
        return $this->hasMany(LessonComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Relasi ke komentar induk (parent)
     */
    public function parent()
    {
        return $this->belongsTo(LessonComment::class, 'parent_id');
    }

    /**
     * Cek apakah ada diskusi baru yang belum dibaca oleh user tertentu
     */
    public static function hasUnreadForUser($user)
    {
        if (!$user) {
            return false;
        }

        // Jika student, hanya cek lesson yang ditargetkan untuk kelas/jenjangnya
        if ($user->role === 'student') {
            $level = $user->educationLevel() ?? 'SMA';
            $grade = $user->studentGrade();

            $lessonIds = \App\Models\Lesson::whereHas('course', function ($query) use ($level, $grade) {
                $query->where('education_level', $level)
                      ->when($grade !== null, function ($q) use ($grade) {
                          $q->where('grade', $grade);
                      });
            })->pluck('id');
        } else {
            // Guru/admin/pimpinan melihat semua lesson
            $lessonIds = \App\Models\Lesson::pluck('id');
        }

        if ($lessonIds->isEmpty()) {
            return false;
        }

        return self::leftJoin('lesson_discussion_reads', function ($join) use ($user) {
                $join->on('lesson_comments.lesson_id', '=', 'lesson_discussion_reads.lesson_id')
                     ->where('lesson_discussion_reads.user_id', '=', $user->id);
            })
            ->whereIn('lesson_comments.lesson_id', $lessonIds)
            ->where('lesson_comments.user_id', '!=', $user->id)
            ->where(function ($q) {
                $q->whereNull('lesson_discussion_reads.last_read_at')
                  ->orWhereColumn('lesson_comments.created_at', '>', 'lesson_discussion_reads.last_read_at');
            })
            ->exists();
    }
}
