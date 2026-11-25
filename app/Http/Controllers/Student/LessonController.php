<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseBadge;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\StudentBadge;
use App\Models\LessonComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Tampilkan atau redirect ke materi pertama di Ruang Belajar.
     */
    public function index()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['student', 'guru', 'admin', 'pimpinan'])) {
            abort(403, 'Akses ditolak.');
        }

        if ($user->role === 'student') {
            $level = $user->educationLevel() ?? 'SMA';
            $grade = $user->studentGrade();
            
            // Get all courses matching level and grade
            $courses = Course::where('education_level', $level)
                ->when($grade !== null, function($q) use ($grade) {
                    $q->where('grade', $grade);
                })
                ->with(['lessons', 'badge'])
                ->get();
            
            // Fallback to level-only if no courses found for specific grade
            if ($courses->isEmpty()) {
                $courses = Course::where('education_level', $level)
                    ->with(['lessons', 'badge'])
                    ->get();
            }
            
            // Calculate progress percentage and get first uncompleted lesson for each course
            foreach ($courses as $course) {
                $lessonsCount = $course->lessons->count();
                if ($lessonsCount > 0) {
                    $completedCount = LessonProgress::where('student_id', $user->id)
                        ->whereIn('lesson_id', $course->lessons->pluck('id'))
                        ->where('is_completed', true)
                        ->count();
                    $course->progress_percentage = ($completedCount / $lessonsCount) * 100;
                    $course->completed_count = $completedCount;
                    
                    // Identify first uncompleted lesson
                    $firstUncompletedLesson = null;
                    $completedLessonIds = LessonProgress::where('student_id', $user->id)
                        ->whereIn('lesson_id', $course->lessons->pluck('id'))
                        ->where('is_completed', true)
                        ->pluck('lesson_id')
                        ->toArray();
                        
                    foreach ($course->lessons as $lesson) {
                        if (!in_array($lesson->id, $completedLessonIds)) {
                            $firstUncompletedLesson = $lesson;
                            break;
                        }
                    }
                    
                    // Fallback to first lesson
                    if (!$firstUncompletedLesson) {
                        $firstUncompletedLesson = $course->lessons->first();
                    }
                    
                    $course->first_lesson_to_play = $firstUncompletedLesson;
                } else {
                    $course->progress_percentage = 0;
                    $course->completed_count = 0;
                    $course->first_lesson_to_play = null;
                }
            }

            return view('lessons.index', [
                'courses' => $courses,
                'educationLevel' => $level
            ]);
        }

        // For teachers/admins, redirect to the management dashboard
        return redirect()->route('teacher.lessons.index');
    }

    /**
     * Tampilkan Halaman Ruang Belajar untuk materi tertentu.
     */
    public function show(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        // Izinkan student, guru, admin, dan pimpinan
        if (!in_array($user->role, ['student', 'guru', 'admin', 'pimpinan'])) {
            abort(403, 'Akses ditolak.');
        }

        $course = $lesson->course;
        $lessons = $course->lessons; // Diurutkan berdasarkan order_position dan id

        if ($user->role === 'student') {
            // Ambil daftar materi yang sudah diselesaikan siswa dalam kursus ini
            $progressList = LessonProgress::where('student_id', $user->id)
                ->whereIn('lesson_id', $lessons->pluck('id'))
                ->where('is_completed', true)
                ->pluck('lesson_id')
                ->toArray();

            // Hitung persentase progres
            $totalLessons = $lessons->count();
            $completedCount = count($progressList);
            $progressPercentage = $totalLessons > 0 ? ($completedCount / $totalLessons) * 100 : 0;

            // Ambil info lencana kursus
            $badge = CourseBadge::where('course_id', $course->id)->first();
            $hasEarnedBadge = $badge ? StudentBadge::where('student_id', $user->id)->where('badge_id', $badge->id)->exists() : false;

            // Tentukan jenjang pendidikan siswa untuk tema adaptif
            $educationLevel = $user->educationLevel() ?? 'SMA';
        } else {
            // Untuk preview guru / admin / pimpinan
            $progressList = [];
            $progressPercentage = 0;
            $badge = CourseBadge::where('course_id', $course->id)->first();
            $hasEarnedBadge = false;
            $educationLevel = $course->education_level; // Tema visual adaptif sesuai modul kursus yang sedang ditinjau
        }

        // Cari index materi saat ini untuk menentukan tombol navigasi
        $currentIndex = $lessons->search(fn($item) => $item->id === $lesson->id);
        $prevLesson = $currentIndex > 0 ? $lessons->get($currentIndex - 1) : null;
        $nextLesson = $currentIndex !== false && $currentIndex < $lessons->count() - 1 ? $lessons->get($currentIndex + 1) : null;

        // Cek unread status untuk lesson ini
        $hasUnreadCommentsForThisLesson = $lesson->hasUnreadCommentsForUser($user);

        // Jika ada request tab=diskusi, tandai dibaca seketika
        if ($request->get('tab') === 'diskusi') {
            \Illuminate\Support\Facades\DB::table('lesson_discussion_reads')->updateOrInsert(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                ['last_read_at' => now(), 'updated_at' => now()]
            );
            $hasUnreadCommentsForThisLesson = false;
        }

        // Ambil diskusi/komentar utama untuk materi ini beserta reply-nya
        $comments = $lesson->rootComments()->with(['user', 'replies.user'])->get();

        return view('lessons.show', compact(
            'lesson',
            'course',
            'lessons',
            'progressList',
            'progressPercentage',
            'prevLesson',
            'nextLesson',
            'badge',
            'hasEarnedBadge',
            'educationLevel',
            'comments',
            'hasUnreadCommentsForThisLesson'
        ));
    }

    /**
     * Mengubah status progres membaca/menonton materi via AJAX.
     */
    public function toggleProgress(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['student', 'guru', 'admin', 'pimpinan'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $isCompleted = (bool) $request->is_completed;

        // Jika bukan student (guru/admin/pimpinan), berikan respons simulasi (Mock) untuk preview UX
        if ($user->role !== 'student') {
            $badge = CourseBadge::where('course_id', $lesson->course_id)->first();
            return response()->json([
                'success' => true,
                'is_completed' => $isCompleted,
                'progress_percentage' => $isCompleted ? 100 : 0,
                'earned_badge' => $isCompleted && $badge,
                'badge_name' => $badge ? $badge->badge_name : 'Lencana Simulasi',
                'badge_image' => $badge ? $badge->badge_image_url : '🏆',
            ]);
        }

        // Update atau buat progress materi
        $progress = LessonProgress::updateOrCreate(
            [
                'student_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => $isCompleted,
                'completed_at' => $isCompleted ? now() : null,
            ]
        );

        // Hitung ulang persentase penyelesaian kursus
        $course = $lesson->course;
        $lessons = $course->lessons;
        $totalLessons = $lessons->count();
        
        $completedLessonsCount = LessonProgress::where('student_id', $user->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->where('is_completed', true)
            ->count();

        $progressPercentage = $totalLessons > 0 ? ($completedLessonsCount / $totalLessons) * 100 : 0;

        $earnedBadgeTriggered = false;
        $badge = null;

        // Jika progres mencapai tepat 100%, berikan lencana secara otomatis
        if ($progressPercentage >= 100) {
            $badge = CourseBadge::where('course_id', $course->id)->first();
            if ($badge) {
                $alreadyEarned = StudentBadge::where('student_id', $user->id)
                    ->where('badge_id', $badge->id)
                    ->exists();

                if (!$alreadyEarned) {
                    StudentBadge::create([
                        'student_id' => $user->id,
                        'badge_id' => $badge->id,
                        'earned_at' => now(),
                    ]);
                    $earnedBadgeTriggered = true;
                }
            }
        }

        return response()->json([
            'success' => true,
            'is_completed' => $progress->is_completed,
            'progress_percentage' => $progressPercentage,
            'earned_badge' => $earnedBadgeTriggered,
            'badge_name' => ($earnedBadgeTriggered && $badge) ? $badge->badge_name : null,
            'badge_image' => ($earnedBadgeTriggered && $badge) ? $badge->badge_image_url : null,
        ]);
    }

    /**
     * Menyimpan komentar/diskusi baru untuk materi tertentu via AJAX.
     */
    public function storeComment(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['student', 'guru', 'admin', 'pimpinan'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:lesson_comments,id',
        ]);

        $comment = $lesson->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        // Load the user relation for response
        $comment->load('user');

        // Otomatis tandai dibaca oleh pengirim
        \Illuminate\Support\Facades\DB::table('lesson_discussion_reads')->updateOrInsert(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['last_read_at' => now(), 'updated_at' => now()]
        );

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'user_role' => $comment->user->role,
                'content' => e($comment->content),
                'created_at_formatted' => $comment->created_at->diffForHumans(),
                'parent_id' => $comment->parent_id,
                'is_owner_or_staff' => true,
            ]
        ]);
    }

    /**
     * Menghapus komentar/diskusi materi via AJAX.
     */
    public function destroyComment(Request $request, $commentId)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['student', 'guru', 'admin', 'pimpinan'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $comment = LessonComment::findOrFail($commentId);

        // Authorization check: Only comment owner or staff (guru/admin/pimpinan) can delete
        if ($comment->user_id !== $user->id && !in_array($user->role, ['guru', 'admin', 'pimpinan'])) {
            return response()->json(['error' => 'Anda tidak berwenang menghapus komentar ini.'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Mendapatkan daftar komentar terbaru (untuk polling).
     */
    public function getComments(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['student', 'guru', 'admin', 'pimpinan'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $comments = $lesson->rootComments()->with(['user', 'replies.user'])->get();

        $formattedComments = $comments->map(function ($comment) use ($user) {
            $isOwnerOrStaff = $comment->user_id === $user->id || in_array($user->role, ['guru', 'admin', 'pimpinan']);
            return [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'user_role' => $comment->user->role,
                'content' => e($comment->content),
                'created_at_formatted' => $comment->created_at->diffForHumans(),
                'parent_id' => $comment->parent_id,
                'is_owner_or_staff' => $isOwnerOrStaff,
                'replies' => $comment->replies->map(function ($reply) use ($user) {
                    $replyIsOwnerOrStaff = $reply->user_id === $user->id || in_array($user->role, ['guru', 'admin', 'pimpinan']);
                    return [
                        'id' => $reply->id,
                        'user_name' => $reply->user->name,
                        'user_role' => $reply->user->role,
                        'content' => e($reply->content),
                        'created_at_formatted' => $reply->created_at->diffForHumans(),
                        'parent_id' => $reply->parent_id,
                        'is_owner_or_staff' => $replyIsOwnerOrStaff,
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'comments' => $formattedComments
        ]);
    }

    /**
     * Menandai forum diskusi materi telah dibaca oleh pengguna.
     */
    public function markAsRead(Request $request, Lesson $lesson)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['student', 'guru', 'admin', 'pimpinan'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        \Illuminate\Support\Facades\DB::table('lesson_discussion_reads')->updateOrInsert(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['last_read_at' => now(), 'updated_at' => now()]
        );

        return response()->json([
            'success' => true
        ]);
    }
}
