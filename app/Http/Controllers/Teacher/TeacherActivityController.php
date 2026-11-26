<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamAttempt;
use App\Models\ReportSubject;
use App\Models\Siswa;
use App\Models\ExamDraft;
use App\Models\User;
use App\Helpers\ReportHelper;
use App\Helpers\ActivityGradeSyncHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherActivityController extends Controller
{
    public function index(Request $request)
    {
        $currentPeriod = ReportHelper::getCurrentPeriod();

        // Fetch distinct classes
        $classes = Siswa::whereNotNull('class')->distinct()->pluck('class')->filter()->toArray();
        if (empty($classes)) {
            $classes = ['1 SD', '2 SD', '3 SD', '4 SD', '5 SD', '6 SD'];
        }
        sort($classes);

        $selectedClass = $request->query('class');

        // Load assignments with owner or collaborator check (admin/superadmin sees all)
        $assignmentsQuery = Assignment::with(['subject', 'teacher', 'collaborators'])
            ->when(!Auth::user()->isAdmin(), function ($q) {
                $q->where(function ($query) {
                    $query->where('teacher_id', Auth::id())
                          ->orWhereHas('collaborators', function ($cq) {
                              $cq->where('users.id', Auth::id());
                          });
                });
            })
            ->orderBy('due_date', 'asc');
            
        // Load exams with owner or collaborator check (admin/superadmin sees all)
        $examsQuery = Exam::with(['subject', 'teacher', 'collaborators'])
            ->when(!Auth::user()->isAdmin(), function ($q) {
                $q->where(function ($query) {
                    $query->where('teacher_id', Auth::id())
                          ->orWhereHas('collaborators', function ($cq) {
                              $cq->where('users.id', Auth::id());
                          });
                });
            })
            ->orderBy('due_date', 'asc');

        if ($selectedClass) {
            $assignmentsQuery->where('class', $selectedClass);
            $examsQuery->where('class', $selectedClass);
        }

        $assignments = $assignmentsQuery->get();
        $exams = $examsQuery->get();

        return view('teacher.activities.index', compact('assignments', 'exams', 'currentPeriod', 'classes', 'selectedClass'));
    }


    public function createAssignment()
    {
        $subjects = ReportSubject::select('report_subjects.*')
            ->join('report_categories', 'report_subjects.report_category_id', '=', 'report_categories.id')
            ->orderByRaw("CASE WHEN report_categories.name = 'Akademik' THEN 0 ELSE 1 END")
            ->orderBy('report_subjects.name')
            ->get();
        $classes = Siswa::whereNotNull('class')->distinct()->pluck('class')->filter()->toArray();
        if (empty($classes)) {
            $classes = ['1 SD', '2 SD', '3 SD', '4 SD', '5 SD', '6 SD'];
        }
        $currentPeriod = ReportHelper::getCurrentPeriod();

        // Get other teachers for collaboration selection
        $teachers = User::where('role', 'guru')->where('id', '!=', Auth::id())->orderBy('name')->get();

        return view('teacher.activities.create_assignment', compact('subjects', 'classes', 'currentPeriod', 'teachers'));
    }

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'report_subject_id' => 'required|exists:report_subjects,id',
            'class' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
        ]);

        $currentPeriod = ReportHelper::getCurrentPeriod();

        DB::transaction(function () use ($request, $currentPeriod) {
            $assignment = Assignment::create([
                'teacher_id' => Auth::id(),
                'report_subject_id' => $request->report_subject_id,
                'class' => $request->class,
                'period' => $currentPeriod,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
            ]);

            if ($request->filled('collaborators')) {
                $assignment->collaborators()->sync($request->collaborators);
            }
        });

        return redirect()->route('teacher.activities.index', ['tab' => 'assignments'])->with('success', 'Penugasan berhasil dibuat!');
    }

    public function editAssignment(Assignment $assignment)
    {
        $this->authorizeActivity($assignment);

        $subjects = ReportSubject::select('report_subjects.*')
            ->join('report_categories', 'report_subjects.report_category_id', '=', 'report_categories.id')
            ->orderByRaw("CASE WHEN report_categories.name = 'Akademik' THEN 0 ELSE 1 END")
            ->orderBy('report_subjects.name')
            ->get();
        $classes = Siswa::whereNotNull('class')->distinct()->pluck('class')->filter()->toArray();
        if (empty($classes)) {
            $classes = ['1 SD', '2 SD', '3 SD', '4 SD', '5 SD', '6 SD'];
        }

        // Get other teachers for collaboration selection
        $teachers = User::where('role', 'guru')->where('id', '!=', Auth::id())->orderBy('name')->get();

        return view('teacher.activities.edit_assignment', compact('assignment', 'subjects', 'classes', 'teachers'));
    }

    public function updateAssignment(Request $request, Assignment $assignment)
    {
        $this->authorizeActivity($assignment);

        $request->validate([
            'report_subject_id' => 'required|exists:report_subjects,id',
            'class' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($request, $assignment) {
            $assignment->update([
                'report_subject_id' => $request->report_subject_id,
                'class' => $request->class,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
            ]);

            $assignment->collaborators()->sync($request->input('collaborators', []));
        });

        return redirect()->route('teacher.activities.index', ['tab' => 'assignments'])->with('success', 'Penugasan berhasil diperbarui!');
    }

    public function destroyAssignment(Assignment $assignment)
    {
        $this->authorizeDelete($assignment);
        $assignment->delete();
        return redirect()->route('teacher.activities.index', ['tab' => 'assignments'])->with('success', 'Penugasan berhasil dihapus!');
    }

    public function listSubmissions(Assignment $assignment)
    {
        $this->authorizeActivity($assignment);
        $assignment->load(['subject']);
        
        // Get all students in the assigned class
        $students = Siswa::where('class', $assignment->class)->get();

        // Get submissions mapped by student ID
        $submissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->get()
            ->keyBy('siswa_id');

        return view('teacher.activities.submissions', compact('assignment', 'students', 'submissions'));
    }

    public function gradeSubmission(Request $request, AssignmentSubmission $submission)
    {
        $this->authorizeActivity($submission->assignment);

        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        // Sync grade directly to report card!
        $assignment = $submission->assignment;
        ActivityGradeSyncHelper::syncGrade($submission->siswa_id, $assignment->report_subject_id, $assignment->period);

        return back()->with('success', 'Nilai penugasan berhasil disimpan dan disinkronisasikan ke raport!');
    }

    public function createExam()
    {
        $subjects = ReportSubject::select('report_subjects.*')
            ->join('report_categories', 'report_subjects.report_category_id', '=', 'report_categories.id')
            ->orderByRaw("CASE WHEN report_categories.name = 'Akademik' THEN 0 ELSE 1 END")
            ->orderBy('report_subjects.name')
            ->get();
        $classes = Siswa::whereNotNull('class')->distinct()->pluck('class')->filter()->toArray();
        if (empty($classes)) {
            $classes = ['1 SD', '2 SD', '3 SD', '4 SD', '5 SD', '6 SD'];
        }
        $currentPeriod = ReportHelper::getCurrentPeriod();

        $draft = ExamDraft::where('teacher_id', Auth::id())->whereNull('exam_id')->first();

        // Get other teachers for collaboration selection
        $teachers = User::where('role', 'guru')->where('id', '!=', Auth::id())->orderBy('name')->get();

        return view('teacher.activities.create_exam', compact('subjects', 'classes', 'currentPeriod', 'draft', 'teachers'));
    }

    public function storeExam(Request $request)
    {
        $request->validate([
            'report_subject_id' => 'required|exists:report_subjects,id',
            'class' => 'required|string',
            'period' => 'required|string|max:255',
            'target_period' => 'nullable|string|in:PTS,PAS',
            'title' => 'required|string|max:255',
            'type' => 'required|in:harian,tengah_semester,akhir_semester,remedial',
            'duration' => 'required|integer|min:1',
            'due_date' => 'required|date',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:multiple_choice,essay',
            'questions.*.points' => 'required|integer|min:1',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($request) {
            $exam = Exam::create([
                'teacher_id' => Auth::id(),
                'report_subject_id' => $request->report_subject_id,
                'class' => $request->class,
                'period' => $request->period,
                'target_period' => $request->target_period,
                'title' => $request->title,
                'type' => $request->type,
                'duration' => $request->duration,
                'due_date' => $request->due_date,
            ]);

            foreach ($request->questions as $q) {
                $options = null;
                $correctAnswer = $q['correct_answer'] ?? null;
                
                if ($q['question_type'] === 'multiple_choice') {
                    $options = [
                        'A' => $q['options']['A'] ?? '',
                        'B' => $q['options']['B'] ?? '',
                        'C' => $q['options']['C'] ?? '',
                        'D' => $q['options']['D'] ?? '',
                    ];
                }

                ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_text' => $q['question_text'],
                    'question_type' => $q['question_type'],
                    'options' => $options,
                    'correct_answer' => $correctAnswer,
                    'points' => $q['points'],
                ]);
            }

            if ($request->filled('collaborators')) {
                $exam->collaborators()->sync($request->collaborators);
            }

            // Clear draft
            ExamDraft::where('teacher_id', Auth::id())->whereNull('exam_id')->delete();
        });

        return redirect()->route('teacher.activities.index', ['tab' => 'exams'])->with('success', 'Ujian berhasil dibuat!');
    }

    public function editExam(Exam $exam)
    {
        $this->authorizeActivity($exam);

        $exam->load('questions');
        $subjects = ReportSubject::select('report_subjects.*')
            ->join('report_categories', 'report_subjects.report_category_id', '=', 'report_categories.id')
            ->orderByRaw("CASE WHEN report_categories.name = 'Akademik' THEN 0 ELSE 1 END")
            ->orderBy('report_subjects.name')
            ->get();
        $classes = Siswa::whereNotNull('class')->distinct()->pluck('class')->filter()->toArray();
        if (empty($classes)) {
            $classes = ['1 SD', '2 SD', '3 SD', '4 SD', '5 SD', '6 SD'];
        }

        $draft = ExamDraft::where('teacher_id', Auth::id())->where('exam_id', $exam->id)->first();

        // Get other teachers for collaboration selection
        $teachers = User::where('role', 'guru')->where('id', '!=', Auth::id())->orderBy('name')->get();

        return view('teacher.activities.edit_exam', compact('exam', 'subjects', 'classes', 'draft', 'teachers'));
    }

    public function updateExam(Request $request, Exam $exam)
    {
        $this->authorizeActivity($exam);

        $request->validate([
            'report_subject_id' => 'required|exists:report_subjects,id',
            'class' => 'required|string',
            'period' => 'required|string|max:255',
            'target_period' => 'nullable|string|in:PTS,PAS',
            'title' => 'required|string|max:255',
            'type' => 'required|in:harian,tengah_semester,akhir_semester,remedial',
            'duration' => 'required|integer|min:1',
            'due_date' => 'required|date',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:multiple_choice,essay',
            'questions.*.points' => 'required|integer|min:1',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
        ]);

        DB::transaction(function () use ($request, $exam) {
            $exam->update([
                'report_subject_id' => $request->report_subject_id,
                'class' => $request->class,
                'period' => $request->period,
                'target_period' => $request->target_period,
                'title' => $request->title,
                'type' => $request->type,
                'duration' => $request->duration,
                'due_date' => $request->due_date,
            ]);

            // Delete old questions and re-insert
            $exam->questions()->delete();

            foreach ($request->questions as $q) {
                $options = null;
                $correctAnswer = $q['correct_answer'] ?? null;
                
                if ($q['question_type'] === 'multiple_choice') {
                    $options = [
                        'A' => $q['options']['A'] ?? '',
                        'B' => $q['options']['B'] ?? '',
                        'C' => $q['options']['C'] ?? '',
                        'D' => $q['options']['D'] ?? '',
                    ];
                }

                ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_text' => $q['question_text'],
                    'question_type' => $q['question_type'],
                    'options' => $options,
                    'correct_answer' => $correctAnswer,
                    'points' => $q['points'],
                ]);
            }

            $exam->collaborators()->sync($request->input('collaborators', []));

            // Clear draft
            ExamDraft::where('teacher_id', Auth::id())->where('exam_id', $exam->id)->delete();
        });

        return redirect()->route('teacher.activities.index', ['tab' => 'exams'])->with('success', 'Ujian berhasil diperbarui!');
    }

    public function destroyExam(Exam $exam)
    {
        $this->authorizeDelete($exam);
        $exam->delete();
        return redirect()->route('teacher.activities.index', ['tab' => 'exams'])->with('success', 'Ujian berhasil dihapus!');
    }

    public function listAttempts(Exam $exam)
    {
        $this->authorizeActivity($exam);
        $exam->load(['subject', 'questions']);
        $students = Siswa::where('class', $exam->class)->get();
        $attempts = ExamAttempt::where('exam_id', $exam->id)->get()->keyBy('siswa_id');

        return view('teacher.activities.attempts', compact('exam', 'students', 'attempts'));
    }

    public function gradeAttemptEssay(Request $request, ExamAttempt $attempt)
    {
        $this->authorizeActivity($attempt->exam);

        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $attempt->update([
            'score' => $request->score,
        ]);

        // Sync directly to student report card!
        $exam = $attempt->exam;
        ActivityGradeSyncHelper::syncGrade($attempt->siswa_id, $exam->report_subject_id, $exam->period);

        return back()->with('success', 'Nilai ujian berhasil diperbarui dan disinkronisasikan ke raport!');
    }

    public function saveExamDraft(Request $request)
    {
        $request->validate([
            'exam_id' => 'nullable|integer',
            'draft_data' => 'required|array',
        ]);

        if ($request->filled('exam_id')) {
            $exam = Exam::find($request->exam_id);
            if ($exam) {
                $this->authorizeActivity($exam);
            }
        }

        ExamDraft::updateOrCreate(
            [
                'teacher_id' => Auth::id(),
                'exam_id' => $request->exam_id,
            ],
            [
                'draft_data' => $request->draft_data,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function discardExamDraft(Request $request)
    {
        $request->validate([
            'exam_id' => 'nullable|integer',
        ]);

        if ($request->filled('exam_id')) {
            $exam = Exam::find($request->exam_id);
            if ($exam) {
                $this->authorizeActivity($exam);
            }
        }

        ExamDraft::where('teacher_id', Auth::id())
            ->where('exam_id', $request->exam_id)
            ->delete();

        return response()->json(['success' => true]);
    }

    private function authorizeActivity($activity)
    {
        $isOwner = $activity->teacher_id == Auth::id();
        $isCollaborator = $activity->collaborators()->where('users.id', Auth::id())->exists();

        if (!$isOwner && !$isCollaborator) {
            abort(403, 'Anda tidak memiliki akses ke aktivitas ini.');
        }
    }

    private function authorizeDelete($activity)
    {
        if ($activity->teacher_id != Auth::id()) {
            abort(403, 'Hanya pembuat/pemilik utama yang dapat menghapus aktivitas ini.');
        }
    }
}
