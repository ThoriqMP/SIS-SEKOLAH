<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamAttempt;
use App\Helpers\ReportHelper;
use App\Helpers\ActivityGradeSyncHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PortalActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $currentPeriod = ReportHelper::getCurrentPeriod();

        $assignments = collect();
        $exams = collect();
        $submissions = collect();
        $attempts = collect();

        if ($siswa) {
            try {
                $assignments = Assignment::with(['subject'])
                    ->where('class', $siswa->class)
                    ->where('period', $currentPeriod)
                    ->orderBy('due_date', 'asc')
                    ->get();
            } catch (\Throwable $e) {
                $assignments = collect();
            }

            try {
                $exams = Exam::with(['subject'])
                    ->where('class', $siswa->class)
                    ->where('period', $currentPeriod)
                    ->orderBy('due_date', 'asc')
                    ->get();
            } catch (\Throwable $e) {
                $exams = collect();
            }

            try {
                $subCol = \Illuminate\Support\Facades\Schema::hasColumn('assignment_submissions', 'siswa_id') ? 'siswa_id' : 'student_id';
                $submissions = AssignmentSubmission::where($subCol, $siswa->id)
                    ->whereIn('assignment_id', $assignments->pluck('id'))
                    ->get()
                    ->keyBy('assignment_id');
            } catch (\Throwable $e) {
                $submissions = collect();
            }

            try {
                $attCol = \Illuminate\Support\Facades\Schema::hasColumn('exam_attempts', 'siswa_id') ? 'siswa_id' : 'student_id';
                $attempts = ExamAttempt::where($attCol, $siswa->id)
                    ->whereIn('exam_id', $exams->pluck('id'))
                    ->get()
                    ->keyBy('exam_id');
            } catch (\Throwable $e) {
                $attempts = collect();
            }
        }

        return view('student.activities.index', compact('assignments', 'exams', 'submissions', 'attempts', 'currentPeriod'));
    }

    public function showAssignment(Assignment $assignment)
    {
        $siswa = Auth::user()->siswa;
        if ($assignment->class !== $siswa->class) {
            abort(403, 'Anda tidak tergabung di kelas untuk tugas ini.');
        }

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        return view('student.activities.show_assignment', compact('assignment', 'submission'));
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $siswa = Auth::user()->siswa;
        if ($assignment->class !== $siswa->class) {
            abort(403);
        }

        $request->validate([
            'submission_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip,doc,docx|max:10240', // 10MB max
        ]);

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        $filePath = $submission ? $submission->file_path : null;

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($filePath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'siswa_id' => $siswa->id,
            ],
            [
                'submission_text' => $request->submission_text,
                'file_path' => $filePath,
                'submitted_at' => now(),
            ]
        );

        return redirect()->route('student.activities.index')->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function showExam(Exam $exam)
    {
        $siswa = Auth::user()->siswa;
        if ($exam->class !== $siswa->class) {
            abort(403, 'Anda tidak tergabung di kelas untuk ujian ini.');
        }

        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        return view('student.activities.show_exam', compact('exam', 'attempt'));
    }

    public function takeExam(Exam $exam)
    {
        $siswa = Auth::user()->siswa;
        if ($exam->class !== $siswa->class) {
            abort(403);
        }

        $attempt = ExamAttempt::firstOrCreate([
            'exam_id' => $exam->id,
            'siswa_id' => $siswa->id,
        ], [
            'started_at' => now(),
        ]);

        if ($attempt->completed_at) {
            return redirect()->route('student.activities.exam.result', $exam);
        }

        // Check if duration exceeded
        $elapsedMinutes = now()->diffInMinutes($attempt->started_at);
        if ($elapsedMinutes >= $exam->duration) {
            // Auto submit
            $attempt->update([
                'completed_at' => $attempt->started_at->addMinutes($exam->duration),
                'score' => 0,
            ]);
            return redirect()->route('student.activities.exam.result', $exam)->with('error', 'Waktu ujian telah habis.');
        }

        $exam->load(['questions' => function ($query) {
            $query->select(['id', 'exam_id', 'question_text', 'question_type', 'options', 'points']);
        }]);
        $exam->questions->makeHidden('correct_answer');
        return view('student.activities.take_exam', compact('exam', 'attempt'));
    }

    public function submitExam(Request $request, Exam $exam)
    {
        $siswa = Auth::user()->siswa;
        if ($exam->class !== $siswa->class) {
            abort(403);
        }

        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        if ($attempt->completed_at) {
            return redirect()->route('student.activities.exam.result', $exam);
        }

        $request->validate([
            'answers' => 'required|array',
        ]);

        $exam->load('questions');
        
        $totalPoints = 0;
        $earnedPoints = 0;
        $hasEssay = false;

        foreach ($exam->questions as $question) {
            $totalPoints += $question->points;
            $studentAnswer = $request->answers[$question->id] ?? null;

            if ($question->question_type === 'multiple_choice') {
                if (strtoupper(trim($studentAnswer)) === strtoupper(trim($question->correct_answer))) {
                    $earnedPoints += $question->points;
                }
            } else {
                $hasEssay = true;
            }
        }

        // Auto-grade calculation
        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;

        $attempt->update([
            'completed_at' => now(),
            'answers' => $request->answers,
            'score' => $score,
        ]);

        // Sync grade directly to report card!
        ActivityGradeSyncHelper::syncGrade($siswa->id, $exam->report_subject_id, $exam->period);

        return redirect()->route('student.activities.exam.result', $exam)->with('success', 'Jawaban ujian berhasil dikirim!');
    }

    public function examResult(Exam $exam)
    {
        $siswa = Auth::user()->siswa;
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        $exam->load('questions');
        return view('student.activities.exam_result', compact('exam', 'attempt'));
    }
}
