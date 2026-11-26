<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseBadge;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonManagementController extends Controller
{
    /**
     * Tampilkan semua kursus yang dikelompokkan berdasarkan jenjang pendidikan.
     */
    public function index()
    {
        $coursesGrouped = Course::with(['lessons', 'badges'])
            ->get()
            ->groupBy('education_level');

        // Ambil pelajaran yang memiliki diskusi untuk tab Forum Diskusi
        $lessonsWithDiscussions = Lesson::whereHas('comments')
            ->with(['course', 'comments' => function ($q) {
                $q->latest()->with('user');
            }])
            ->withCount('comments')
            ->get()
            ->sortByDesc(function ($lesson) {
                return $lesson->comments->first()?->created_at;
            });

        return view('teacher.lessons.index', compact('coursesGrouped', 'lessonsWithDiscussions'));
    }

    /**
     * Tampilkan formulir pembuatan kursus baru.
     */
    public function createCourse()
    {
        return view('teacher.lessons.course_form');
    }

    /**
     * Simpan kursus baru beserta data lencananya.
     */
    public function storeCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'education_level' => 'required|in:SD,SMP,SMA',
            'grade' => 'required|integer|min:1|max:12',
            'badge_name' => 'required|string|max:255',
            'badge_image_url' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $course = Course::create([
                'title' => $request->title,
                'education_level' => $request->education_level,
                'grade' => $request->grade,
            ]);

            CourseBadge::create([
                'course_id' => $course->id,
                'badge_name' => $request->badge_name,
                'badge_image_url' => $request->badge_image_url ?: '🏆', // Default emoji jika kosong
            ]);
        });

        return redirect()
            ->route('teacher.lessons.index')
            ->with('success', 'Kursus dan Lencana berhasil dibuat!');
    }

    /**
     * Tampilkan formulir penyuntingan kursus.
     */
    public function editCourse(Course $course)
    {
        $course->load('badges');
        $badge = $course->badges->first();

        return view('teacher.lessons.course_form', compact('course', 'badge'));
    }

    /**
     * Perbarui data kursus beserta lencananya.
     */
    public function updateCourse(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'education_level' => 'required|in:SD,SMP,SMA',
            'grade' => 'required|integer|min:1|max:12',
            'badge_name' => 'required|string|max:255',
            'badge_image_url' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $course) {
            $course->update([
                'title' => $request->title,
                'education_level' => $request->education_level,
                'grade' => $request->grade,
            ]);

            CourseBadge::updateOrCreate(
                ['course_id' => $course->id],
                [
                    'badge_name' => $request->badge_name,
                    'badge_image_url' => $request->badge_image_url ?: '🏆',
                ]
            );
        });

        return redirect()
            ->route('teacher.lessons.index')
            ->with('success', 'Data Kursus dan Lencana berhasil diperbarui!');
    }

    /**
     * Hapus kursus beserta semua materi dan lencana terkait (karena cascade constraints).
     */
    public function destroyCourse(Course $course)
    {
        $course->delete();

        return redirect()
            ->route('teacher.lessons.index')
            ->with('success', 'Kursus berhasil dihapus beserta materi dan lencananya!');
    }

    /**
     * Tampilkan formulir pembuatan materi (lesson) baru di bawah kursus tertentu.
     */
    public function createLesson(Course $course)
    {
        // Tentukan posisi default untuk materi baru
        $defaultOrder = $course->lessons()->count() + 1;

        return view('teacher.lessons.lesson_form', compact('course', 'defaultOrder'));
    }

    /**
     * Simpan materi baru ke database.
     */
    public function storeLesson(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:interactive,video,pdf,docx',
            'gdrive_url' => 'nullable|string',
            'order_position' => 'required|integer|min:0',
            'module_data_json' => 'nullable|string',
        ]);

        $moduleData = null;
        if ($request->filled('module_data_json')) {
            $decoded = json_decode($request->input('module_data_json'), true);
            if (is_array($decoded)) {
                $moduleData = $decoded;
            }
        }

        $course->lessons()->create([
            'title' => $request->title,
            'type' => $request->type,
            'gdrive_url' => $request->gdrive_url,
            'order_position' => $request->order_position,
            'module_data' => $moduleData,
        ]);

        return redirect()
            ->route('teacher.lessons.index')
            ->with('success', 'Materi pembelajaran berhasil ditambahkan!');
    }

    /**
     * Tampilkan formulir penyuntingan materi.
     */
    public function editLesson(Course $course, Lesson $lesson)
    {
        return view('teacher.lessons.lesson_form', compact('course', 'lesson'));
    }

    /**
     * Perbarui data materi pembelajaran.
     */
    public function updateLesson(Request $request, Course $course, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:interactive,video,pdf,docx',
            'gdrive_url' => 'nullable|string',
            'order_position' => 'required|integer|min:0',
            'module_data_json' => 'nullable|string',
        ]);

        $moduleData = $lesson->module_data;
        if ($request->filled('module_data_json')) {
            $decoded = json_decode($request->input('module_data_json'), true);
            if (is_array($decoded)) {
                $moduleData = $decoded;
            }
        }

        $lesson->update([
            'title' => $request->title,
            'type' => $request->type,
            'gdrive_url' => $request->gdrive_url,
            'order_position' => $request->order_position,
            'module_data' => $moduleData,
        ]);

        return redirect()
            ->route('teacher.lessons.index')
            ->with('success', 'Materi pembelajaran berhasil diperbarui!');
    }

    /**
     * Hapus materi pembelajaran.
     */
    public function destroyLesson(Course $course, Lesson $lesson)
    {
        $lesson->delete();

        return redirect()
            ->route('teacher.lessons.index')
            ->with('success', 'Materi pembelajaran berhasil dihapus!');
    }
}
