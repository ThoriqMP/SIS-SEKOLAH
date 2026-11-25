<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_id', 'title', 'type', 'gdrive_url', 'order_position'];

    /**
     * Relasi ke kursus (course)
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relasi ke komentar/diskusi materi (comments)
     */
    public function comments()
    {
        return $this->hasMany(LessonComment::class)->orderBy('created_at', 'asc');
    }

    /**
     * Relasi ke komentar utama (root comments) yang tidak memiliki parent_id
     */
    public function rootComments()
    {
        return $this->hasMany(LessonComment::class)->whereNull('parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Cek apakah ada komentar unread untuk user di lesson ini
     */
    public function hasUnreadCommentsForUser($user)
    {
        if (!$user) {
            return false;
        }

        $lastRead = \Illuminate\Support\Facades\DB::table('lesson_discussion_reads')
            ->where('user_id', $user->id)
            ->where('lesson_id', $this->id)
            ->value('last_read_at');

        $query = $this->comments()
            ->where('user_id', '!=', $user->id);

        if ($lastRead) {
            $query->where('created_at', '>', $lastRead);
        }

        return $query->exists();
    }

    /**
     * Relasi ke progres materi (progress)
     */
    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Mendapatkan status progres untuk siswa tertentu
     */
    public function progressForStudent($studentId)
    {
        return $this->progress()->where('student_id', $studentId)->first();
    }

    /**
     * Accessor untuk memutasi gdrive_url menjadi embed / preview link
     */
    public function getEmbedUrlAttribute()
    {
        $url = $this->gdrive_url;
        if (empty($url)) {
            return '';
        }

        // Hilangkan parameter kueri pencarian (seperti ?usp=sharing)
        $url = preg_replace('/\?.*/', '', $url);

        // Ekstraksi File ID Google Drive (/file/d/{id})
        if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $fileId = $matches[1];
            return "https://drive.google.com/file/d/{$fileId}/preview";
        }

        return $url;
    }
}
