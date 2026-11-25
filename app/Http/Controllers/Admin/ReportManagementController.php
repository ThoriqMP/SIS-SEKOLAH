<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ReportHelper;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CompetencyAssessment;
use App\Models\ProfilSekolah;
use App\Models\ProbingActivity;
use App\Models\DiniyahReport;
use App\Models\IbadahReport;
use App\Models\ReportCategory;
use App\Models\ReportGrade;
use App\Models\ReportSubject;
use App\Models\Siswa;
use App\Models\StudentReport;
use App\Models\TeacherNoteSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportManagementController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period');

        if (!$period) {
            // Folder List View
            // Get unique periods from StudentReport
            $dbPeriods = StudentReport::select('period')
                ->distinct()
                ->orderByRaw("SUBSTRING_INDEX(period, ' ', -1) DESC") // Sort by year desc
                ->orderByRaw("FIELD(SUBSTRING_INDEX(period, ' ', 2), 'Semester Genap', 'Semester Ganjil') DESC") // Sort by semester desc
                ->get()
                ->pluck('period');

            $currentPeriod = ReportHelper::getCurrentPeriod();
            if ($dbPeriods->isEmpty()) {
                $dbPeriods = collect([$currentPeriod]);
            } else if (!$dbPeriods->contains($currentPeriod)) {
                $dbPeriods = collect([$currentPeriod])->merge($dbPeriods);
            }

            // Calculate stats for each period and only keep those with reports
            $periodsData = $dbPeriods->map(function($p) {
                $total = StudentReport::where('period', $p)->count();
                $final = StudentReport::where('period', $p)->where('is_draft', false)->count();
                $draft = StudentReport::where('period', $p)->where('is_draft', true)->count();
                return [
                    'name' => $p,
                    'total' => $total,
                    'final' => $final,
                    'draft' => $draft
                ];
            })->filter(function($period) {
                return $period['total'] > 0;
            })->values();

            $categories = ReportCategory::orderBy('name')->get();
            $allStudents = Siswa::orderBy('nama_siswa')->get(['id', 'nama_siswa', 'nis', 'class']);
            $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('id')->get();

            return view('admin.reports.index', [
                'periods' => $periodsData,
                'isFolderView' => true,
                'categories' => $categories,
                'allStudents' => $allStudents,
                'academicYears' => $academicYears
            ]);
        }

        // Inside a Specific Period Folder
        $categoryId = $request->query('category_id');
        $query = Siswa::whereHas('reports', function($q) use ($period, $categoryId) {
            $q->where('period', $period);
            if ($categoryId) {
                $q->where('report_category_id', $categoryId);
            }
        })
        ->withCount(['reports' => function($q) use ($period, $categoryId) {
            $q->where('period', $period);
            if ($categoryId) {
                $q->where('report_category_id', $categoryId);
            }
        }])
        ->orderBy('nama_siswa');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(12)->withQueryString();
        $categories = ReportCategory::orderBy('name')->get();
        
        // Fetch all students for the modal list
        $allStudents = Siswa::orderBy('nama_siswa')->get(['id', 'nama_siswa', 'nis', 'class']);
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('id')->get();

        return view('admin.reports.index', [
            'students' => $students,
            'categories' => $categories,
            'allStudents' => $allStudents,
            'isFolderView' => false,
            'selectedPeriod' => $period,
            'academicYears' => $academicYears
        ]);
    }

    public function studentReports(Request $request, Siswa $siswa)
    {
        $period = $request->query('tahun_ajaran');

        $query = StudentReport::where('siswa_id', $siswa->id)
            ->with(['category', 'teacher'])
            ->latest();

        if ($period && $period !== 'Semua') {
            $query->where('period', $period);
        }

        $reports = $query->get();
        
        $categories = ReportCategory::orderBy('name')->get();
        $allStudents = Siswa::orderBy('nama_siswa')->get(['id', 'nama_siswa', 'nis', 'class']);

        $tahunAjarans = collect();

        return view('admin.reports.student_list', compact('siswa', 'reports', 'categories', 'allStudents', 'tahunAjarans'));
    }

    public function create(Request $request)
    {
        $selectedStudentId = $request->student_id;
        $selectedCategoryId = $request->category_id;
        $students = Siswa::orderBy('nama_siswa')->get();
        $categories = ReportCategory::with('subjects')->get();

        // If we don't have student or category yet, we just show the index or handle via modal
        if (!$selectedStudentId || !$selectedCategoryId) {
            return redirect()->route('admin.reports.index')->with('error', 'Silakan pilih siswa dan kategori raport terlebih dahulu.');
        }

        $selectedCategory = ReportCategory::with('subjects')->findOrFail($selectedCategoryId);
        $surahs = ReportHelper::getSurahs();
        $suggestions = TeacherNoteSuggestion::all();
        $defaultPeriod = $request->query('period', ReportHelper::getCurrentPeriod());
        $ibadahItems = ReportHelper::getIbadahItems();
        $academicType = $request->academic_type;
        $intelligences = ReportHelper::getMultipleIntelligences();
        $strengths = ReportHelper::getST30Strengths();
        $mutuLabels = \App\Models\NonAcademicMutuLabel::all();
        $projectTemplates = \App\Models\ProjectTemplate::get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('id')->get();
        $activeYear = $academicYears->firstWhere('is_active', true);
        $semesterProjects = \App\Models\SemesterProject::when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))->orderBy('sort_order')->get();
        $semesterProjectTemplates = collect();
        $templateIds = [];
        if ($semesterProjects->isNotEmpty()) {
            $templateIds = $semesterProjects->flatMap(function($sp) {
                return is_array($sp->kegiatan) ? $sp->kegiatan : [];
            })->unique()->filter()->toArray();
            $semesterProjectTemplates = \App\Models\ProjectTemplate::whereIn('id', $templateIds)->orderBy('title')->get();
        }
        $otherProjectTemplates = \App\Models\ProjectTemplate::whereNotIn('id', $templateIds)->orderBy('title')->get();
        $probingCompetencies = ReportHelper::getFoundationCompetencies();

        return view('admin.reports.create', compact(
            'students', 'categories', 'selectedStudentId', 'selectedCategoryId', 'selectedCategory',
            'suggestions', 'surahs', 'ibadahItems', 'defaultPeriod', 'academicType',
            'intelligences', 'strengths', 'mutuLabels', 'projectTemplates',
            'academicYears', 'activeYear', 'probingCompetencies', 'semesterProjects', 
            'semesterProjectTemplates', 'otherProjectTemplates'
        ));
    }

    public function searchStudents(Request $request)
    {
        $search = $request->get('query');
        $students = Siswa::where('nama_siswa', 'like', "%{$search}%")
            ->orWhere('nis', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nama_siswa', 'nis', 'class']);
            
        return response()->json($students);
    }

    public function show(StudentReport $report)
    {
        $report->load(['student', 'category', 'grades.subject', 'probingActivities.activityGrades', 'probingActivities.activityDocumentations', 'competencyAssessments', 'teacher', 'diniyahReports', 'ibadahReport']);
        // Use the same view as the student portal
        return view('student.reports.show', compact('report'));
    }

    public function downloadPdf(StudentReport $report)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(600);

        $report->load(['student', 'category', 'grades.subject', 'probingActivities.activityGrades', 'probingActivities.activityDocumentations', 'competencyAssessments', 'teacher', 'diniyahReports', 'ibadahReport']);
        $school = ProfilSekolah::first();
        
        // Filename: Raport_Jenis rapor_nama_siswa_semester_PTS/PAS
        $safeName = str_replace(['/', '\\', ' '], '_', $report->student->nama_siswa);
        $safeCategory = str_replace(['/', '\\', ' '], '_', $report->category->name);
        $safePeriod = str_replace(['/', '\\', ' '], '-', $report->period);
        $typeSuffix = $report->academic_type ? "_{$report->academic_type}" : "";
        
        $filename = "Raport_{$safeCategory}_{$safeName}_{$safePeriod}{$typeSuffix}.pdf";
        
        $catName = strtolower(trim($report->category->name));
        $viewPath = 'student.reports.pdf.akademik'; // Default
        if (str_contains($catName, 'project based') || str_contains($catName, 'probing') || $catName === 'pbl') $viewPath = 'student.reports.pdf.probing';
        elseif ($catName === 'diniyah') $viewPath = 'student.reports.pdf.diniyah';
        elseif (str_contains($catName, 'ibadah')) $viewPath = 'student.reports.pdf.ibadah';
        
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->with('raportSetting')->first();
        $printPlace = optional($activeYear?->raportSetting)->place ?? 'Bogor';
        $printDate = optional($activeYear?->raportSetting)->report_date ? \Carbon\Carbon::parse($activeYear->raportSetting->report_date)->locale('id')->translatedFormat('d F Y') : now()->locale('id')->translatedFormat('d F Y');
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewPath, compact('report', 'school', 'printPlace', 'printDate'));
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        $pdf->getDomPDF()->set_option('defaultPaperSize', 'a4');
        $pdf->getDomPDF()->set_option('isFontSubsettingEnabled', true);

        return $pdf->download($filename);
    }

    public function previewPdf(StudentReport $report)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(600);

        $report->load(['student', 'category', 'grades.subject', 'probingActivities', 'teacher', 'diniyahReports', 'ibadahReport']);
        $school = ProfilSekolah::first();
        
        $catName = strtolower(trim($report->category->name));
        $viewPath = 'student.reports.pdf.akademik';
        if (str_contains($catName, 'project based') || str_contains($catName, 'probing') || $catName === 'pbl') $viewPath = 'student.reports.pdf.probing';
        elseif ($catName === 'diniyah') $viewPath = 'student.reports.pdf.diniyah';
        elseif (str_contains($catName, 'ibadah')) $viewPath = 'student.reports.pdf.ibadah';

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->with('raportSetting')->first();
        $printPlace = optional($activeYear?->raportSetting)->place ?? 'Bogor';
        $printDate = optional($activeYear?->raportSetting)->report_date ? \Carbon\Carbon::parse($activeYear->raportSetting->report_date)->locale('id')->translatedFormat('d F Y') : now()->locale('id')->translatedFormat('d F Y');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewPath, compact('report', 'school', 'printPlace', 'printDate'));
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        $pdf->getDomPDF()->set_option('defaultPaperSize', 'a4');
        $pdf->getDomPDF()->set_option('isFontSubsettingEnabled', true);
        
        $safeName = str_replace(['/', '\\', ' '], '_', $report->student->nama_siswa);
        $filename = "Pratinjau_Raport_{$safeName}.pdf";
        
        return $pdf->stream($filename);
    }

    public function edit(StudentReport $report)
    {
        $report->load(['grades', 'probingActivities.activityGrades', 'probingActivities.activityDocumentations', 'competencyAssessments']);
        $students = Siswa::orderBy('nama_siswa')->get();
        $categories = ReportCategory::with('subjects')->get();
        $suggestions = TeacherNoteSuggestion::all();
        $surahs = ReportHelper::getSurahs();
        $ibadahItems = ReportHelper::getIbadahItems();
        $intelligences = ReportHelper::getMultipleIntelligences();
        $strengths = ReportHelper::getST30Strengths();
        $mutuLabels = \App\Models\NonAcademicMutuLabel::all();
        $projectTemplates = \App\Models\ProjectTemplate::get();
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('id')->get();
        $activeYear = $academicYears->firstWhere('is_active', true);
        $semesterProjects = \App\Models\SemesterProject::when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))->orderBy('sort_order')->get();
        $semesterProjectTemplates = collect();
        $templateIds = [];
        if ($semesterProjects->isNotEmpty()) {
            $templateIds = $semesterProjects->flatMap(function($sp) {
                return is_array($sp->kegiatan) ? $sp->kegiatan : [];
            })->unique()->filter()->toArray();
            $semesterProjectTemplates = \App\Models\ProjectTemplate::whereIn('id', $templateIds)->orderBy('title')->get();
        }
        $otherProjectTemplates = \App\Models\ProjectTemplate::whereNotIn('id', $templateIds)->orderBy('title')->get();
        $probingCompetencies = ReportHelper::getFoundationCompetencies();
        $teachers = \App\Models\User::whereIn('role', ['admin', 'guru', 'pimpinan'])->orderBy('name')->get();
        return view('admin.reports.edit', compact(
            'report', 'students', 'categories', 'suggestions', 'surahs', 'ibadahItems',
            'intelligences', 'strengths', 'mutuLabels', 'projectTemplates',
            'academicYears', 'activeYear', 'probingCompetencies', 'semesterProjects', 
            'semesterProjectTemplates', 'otherProjectTemplates', 'teachers'
        ));
    }

    public function update(Request $request, StudentReport $report)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'report_category_id' => 'required|exists:report_categories,id',
            'period' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
            'summary_notes' => 'nullable|string',
            'pts_pas_summary' => 'nullable|string',
            'remedial_summary' => 'nullable|string',
            'harian_summary' => 'nullable|string',
            'grades' => 'array',
            'grades.*.score' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_pts' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_pas' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_remedial' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_harian' => 'nullable|numeric|min:0|max:100',
            'grades.*.predicate' => 'nullable|string|max:10',
            'grades.*.ayat_range' => 'nullable|string|max:255',
            'grades.*.description' => 'nullable|string|max:255',
            'new_subjects' => 'array',
            'probing' => 'array',
            'diniyah' => 'array',
            'ibadah' => 'array',
            'new_ibadah_indicators' => 'array',
            'is_first_pbl' => 'boolean',
            'is_final_pbl' => 'boolean',
            'is_draft' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $teacherId = $report->teacher_id;
            if (Auth::user()->isPimpinan() && $request->has('teacher_id')) {
                $teacherId = $request->teacher_id;
            }

            $report->update([
                'siswa_id'              => $request->siswa_id,
                'report_category_id'    => $request->report_category_id,
                'teacher_id'            => $teacherId,
                'period'                => $request->period,
                'summary_notes'         => $request->summary_notes,
                'notes_developed'       => $request->notes_developed,
                'notes_need_development'=> $request->notes_need_development,
                'pts_pas_summary'       => $request->pts_pas_summary,
                'remedial_summary'      => $request->remedial_summary,
                'harian_summary'        => $request->harian_summary,
                'academic_type'         => $request->academic_type,
                'is_first_pbl'          => $request->boolean('is_first_pbl'),
                'is_final_pbl'          => $request->boolean('is_final_pbl'),
                'is_draft'              => $request->boolean('is_draft'),
            ]);

            // Handle CompetencyAssessments (probing only)
            if ($request->has('competencies') && is_array($request->competencies)) {
                $report->competencyAssessments()->delete();
                foreach ($request->competencies as $name => $score) {
                    if (!empty($name)) {
                        CompetencyAssessment::create([
                            'student_report_id' => $report->id,
                            'competency_name'   => $name,
                            'score'             => max(0, min(10, (int)$score)),
                        ]);
                    }
                }
            }

            // Handle Existing Grades
            if ($request->has('grades')) {
                // Delete grades for subjects that are not in the submitted list (i.e. deleted/excluded in UI)
                $submittedSubjectIds = array_keys($request->grades);
                ReportGrade::where('student_report_id', $report->id)
                    ->whereNotIn('report_subject_id', $submittedSubjectIds)
                    ->delete();

                foreach ($request->grades as $subjectId => $data) {
                    $score = $data['score'] ?? null;
                    $subject = ReportSubject::find($subjectId);
                    $predicate = $data['predicate'] ?? ReportHelper::calculatePredicate($score, $subject->kkm ?? 75);

                    ReportGrade::updateOrCreate(
                        ['student_report_id' => $report->id, 'report_subject_id' => $subjectId],
                        [
                            'score' => $score,
                            'score_pts' => $data['score_pts'] ?? null,
                            'score_pas' => $data['score_pas'] ?? null,
                            'score_remedial' => $data['score_remedial'] ?? null,
                            'score_harian' => $data['score_harian'] ?? null,
                            'predicate' => $predicate,
                            'ayat_range' => $data['ayat_range'] ?? null,
                            'description' => $data['description'] ?? null
                        ]
                    );
                }
            } else {
                // If grades array is completely missing, delete all grades for this report
                ReportGrade::where('student_report_id', $report->id)->delete();
            }

            // Handle New Subjects
            if ($request->has('new_subjects')) {
                foreach ($request->new_subjects as $newSubject) {
                    if (empty($newSubject['name'])) continue;

                    $subject = ReportSubject::create([
                        'report_category_id' => $request->report_category_id,
                        'name' => $newSubject['name'],
                    ]);

                    $score = $newSubject['score'] ?? null;
                    $predicate = $newSubject['predicate'] ?? ReportHelper::calculatePredicate($score, 75);

                    ReportGrade::create([
                        'student_report_id' => $report->id,
                        'report_subject_id' => $subject->id,
                        'score' => $score,
                        'score_pts' => $newSubject['score_pts'] ?? null,
                        'score_pas' => $newSubject['score_pas'] ?? null,
                        'score_remedial' => $newSubject['score_remedial'] ?? null,
                        'score_harian' => $newSubject['score_harian'] ?? null,
                        'predicate' => $predicate,
                        'ayat_range' => $newSubject['ayat_range'] ?? null,
                        'description' => $newSubject['description'] ?? null,
                    ]);
                }
            }

            // Handle Diniyah
            $report->diniyahReports()->delete();
            if ($request->has('diniyah')) {
                foreach ($request->diniyah as $item) {
                    if (empty($item['surah_id'])) continue;
                    $report->diniyahReports()->create([
                        'surah_id' => $item['surah_id'],
                        'start_ayah' => $item['start_ayah'] ?? 1,
                        'end_ayah' => $item['end_ayah'] ?? 1,
                        'mutu_code' => $item['mutu_code'] ?? 'A',
                        'description' => ReportHelper::getMutuDescription($item['mutu_code'] ?? 'A', 'Diniyah')
                    ]);
                }
            }

            // Handle Ibadah
            if ($request->has('new_ibadah_indicators')) {
                foreach ($request->new_ibadah_indicators as $code => $name) {
                    if (!empty($name)) {
                        \App\Models\IbadahIndicator::firstOrCreate(
                            ['code' => $code],
                            ['name' => $name, 'is_default' => false]
                        );
                    }
                }
            }

            if ($request->has('ibadah')) {
                $ibadahData = $request->ibadah;
                $defaultColumns = [
                    'niat_subuh', 'niat_dzuhur', 'niat_ashar', 'niat_maghrib', 'niat_isya',
                    'iftitah', 'alfatihah', 'surat_pendek', 'rukuk', 'itidal', 'sujud',
                    'duduk_antara_sujud', 'tasyahud_awal', 'tasyahud_akhir'
                ];

                $standardData = [];
                $additionalGrades = [];

                // Parse custom order and exclusions
                $orderedIndicators = null;
                if ($request->filled('ibadah_order')) {
                    $orderedIndicators = json_decode($request->ibadah_order, true);
                }

                $activeIds = $orderedIndicators ? collect($orderedIndicators)->pluck('id')->toArray() : null;

                foreach ($ibadahData as $key => $val) {
                    if (in_array($key, $defaultColumns)) {
                        if ($activeIds === null || in_array($key, $activeIds)) {
                            $standardData[$key] = $val;
                        }
                    } else {
                        if ($activeIds === null || in_array($key, $activeIds)) {
                            $additionalGrades[$key] = $val;
                        }
                    }
                }

                // Explicitly set columns that were deleted to null
                if ($activeIds !== null) {
                    foreach ($defaultColumns as $col) {
                        if (!in_array($col, $activeIds)) {
                            $standardData[$col] = null;
                        }
                    }
                }

                if ($orderedIndicators) {
                    $additionalGrades['_ordered_indicators'] = $orderedIndicators;
                }

                $standardData['additional_grades'] = $additionalGrades;

                if ($report->ibadahReport) {
                    $report->ibadahReport->update($standardData);
                } else {
                    $report->ibadahReport()->create($standardData);
                }
            }

            // Handle Probing (Complex PBL structure)
            if ($request->has('probing')) {
                // 1. Identify images to keep and images to delete
                $currentImages = [];
                foreach ($report->probingActivities as $activity) {
                    if ($activity->activityDocumentations) {
                        foreach ($activity->activityDocumentations as $doc) {
                            $currentImages[] = $doc->image_path;
                        }
                    }
                    // Fallback to legacy
                    $images = $activity->pbl_data['images'] ?? [];
                    $currentImages = array_merge($currentImages, $images);
                }
                $currentImages = array_unique(array_filter($currentImages));

                $imagesToKeep = [];
                foreach ($request->probing as $activityData) {
                    if (isset($activityData['existing_images'])) {
                        $imagesToKeep = array_merge($imagesToKeep, $activityData['existing_images']);
                    }
                }
                $imagesToKeep = array_unique($imagesToKeep);

                $imagesToDelete = array_diff($currentImages, $imagesToKeep);
                foreach ($imagesToDelete as $img) {
                    Storage::disk('public')->delete($img);
                }

                // 2. Delete old records
                $report->probingActivities()->delete();

                foreach ($request->probing as $activityData) {
                    if (empty($activityData['tema'])) continue;

                    $pblData = [
                        'tema' => $activityData['tema'],
                        'description' => $activityData['description'] ?? '',
                        'location' => $activityData['location'] ?? '',
                        'kegiatan' => $activityData['kegiatan'] ?? [],
                        'personality' => $activityData['personality'] ?? [],
                        'intelligence' => $activityData['intelligence'] ?? [],
                        'strength' => $activityData['strength'] ?? [],
                        'images' => [],
                    ];

                    $probingActivity = ProbingActivity::create([
                        'student_report_id' => $report->id,
                        'semester_project_id' => !empty($activityData['semester_project_id']) ? $activityData['semester_project_id'] : null,
                        'activity_name' => $activityData['tema'],
                        'location' => $activityData['location'] ?? '',
                        'description' => $activityData['description'] ?? '',
                        'pbl_data' => $pblData,
                    ]);

                    // Store grades in 3NF table
                    if (isset($activityData['kegiatan']) && is_array($activityData['kegiatan'])) {
                        $sortOrder = 0;
                        foreach ($activityData['kegiatan'] as $catIdx => $catData) {
                            $categoryName = $catData['category'] ?? '';
                            if (isset($catData['indicators']) && is_array($catData['indicators'])) {
                                foreach ($catData['indicators'] as $indData) {
                                    $indName = $indData['name'] ?? '';
                                    $indScore = $indData['score'] ?? 'Baik';
                                    if (!empty($indName)) {
                                        \App\Models\ActivityGrade::create([
                                            'probing_activity_id' => $probingActivity->id,
                                            'phase_name'          => $categoryName,
                                            'indicator_name'      => $indName,
                                            'grade'               => $indScore,
                                            'sort_order'          => $sortOrder++,
                                        ]);
                                    }
                                }
                            }
                        }
                    }

                    // Store existing documentations
                    $savedPaths = [];
                    if (isset($activityData['existing_images']) && is_array($activityData['existing_images'])) {
                        foreach ($activityData['existing_images'] as $imgIdx => $existingImg) {
                            $caption = $activityData['existing_photo_captions'][$imgIdx] ?? '';
                            \App\Models\ActivityDocumentation::create([
                                'probing_activity_id' => $probingActivity->id,
                                'image_path' => $existingImg,
                                'caption' => $caption,
                            ]);
                            $savedPaths[] = $existingImg;
                        }
                    }

                    // Store new uploaded images
                    if (isset($activityData['images']) && is_array($activityData['images'])) {
                        foreach ($activityData['images'] as $imgIdx => $img) {
                            if ($img instanceof \Illuminate\Http\UploadedFile) {
                                $path = $img->store('probing_activities', 'public');
                                $this->cropTo34($path);
                                $caption = $activityData['photo_captions'][$imgIdx] ?? '';
                                \App\Models\ActivityDocumentation::create([
                                    'probing_activity_id' => $probingActivity->id,
                                    'image_path' => $path,
                                    'caption' => $caption,
                                ]);
                                $savedPaths[] = $path;
                            }
                        }
                    }

                    // Update local images list in JSON blob for compatibility
                    $pblData['images'] = $savedPaths;
                    $probingActivity->update(['pbl_data' => $pblData]);
                }
            }

            DB::commit();
            return redirect()->route('admin.reports.student-list', [
                $report->siswa_id,
                'tahun_ajaran' => $request->query('tahun_ajaran', 'Semua')
            ])->with('success', 'Raport siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui raport: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui raport: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Request $request, StudentReport $report)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$report->is_draft && !$user->isPimpinan()) {
            abort(403, 'Hanya Pimpinan yang dapat menghapus raport.');
        }

        $siswaId = $report->siswa_id;
        DB::beginTransaction();
        try {
            foreach($report->probingActivities as $old) {
                if (isset($old->pbl_data['images'])) {
                    foreach($old->pbl_data['images'] as $img) Storage::disk('public')->delete($img);
                }
            }
            $report->delete();
            DB::commit();
            return redirect()->route('admin.reports.student-list', [
                $siswaId,
                'tahun_ajaran' => $request->query('tahun_ajaran', 'Semua')
            ])->with('success', 'Raport berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus raport.');
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'report_category_id' => 'required|exists:report_categories,id',
            'period' => 'required|string|max:255',
            'summary_notes' => 'nullable|string',
            'pts_pas_summary' => 'nullable|string',
            'remedial_summary' => 'nullable|string',
            'harian_summary' => 'nullable|string',
            'grades' => 'array',
            'grades.*.score' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_pts' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_pas' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_remedial' => 'nullable|numeric|min:0|max:100',
            'grades.*.score_harian' => 'nullable|numeric|min:0|max:100',
            'grades.*.predicate' => 'nullable|string|max:10',
            'grades.*.ayat_range' => 'nullable|string|max:255',
            'grades.*.description' => 'nullable|string|max:255',
            'new_subjects' => 'array',
            'probing' => 'array',
            'diniyah' => 'array',
            'ibadah' => 'array',
            'new_ibadah_indicators' => 'array',
            'is_first_pbl' => 'boolean',
            'is_final_pbl' => 'boolean',
            'is_draft' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $report = StudentReport::create([
                'siswa_id'              => $request->siswa_id,
                'report_category_id'    => $request->report_category_id,
                'teacher_id'            => Auth::id(),
                'period'                => $request->period,
                'summary_notes'         => $request->summary_notes,
                'notes_developed'       => $request->notes_developed,
                'notes_need_development'=> $request->notes_need_development,
                'pts_pas_summary'       => $request->pts_pas_summary,
                'remedial_summary'      => $request->remedial_summary,
                'harian_summary'        => $request->harian_summary,
                'academic_type'         => $request->academic_type,
                'is_first_pbl'          => $request->boolean('is_first_pbl'),
                'is_final_pbl'          => $request->boolean('is_final_pbl'),
                'is_draft'              => $request->boolean('is_draft'),
            ]);
            Log::info('Report created: ' . $report->id);

            // Handle CompetencyAssessments (probing only)
            if ($request->has('competencies') && is_array($request->competencies)) {
                foreach ($request->competencies as $name => $score) {
                    if (!empty($name)) {
                        CompetencyAssessment::create([
                            'student_report_id' => $report->id,
                            'competency_name'   => $name,
                            'score'             => max(0, min(10, (int)$score)),
                        ]);
                    }
                }
            }

            // Handle Existing Grades
            if ($request->has('grades')) {
                foreach ($request->grades as $subjectId => $data) {
                    $score = $data['score'] ?? null;
                    $subject = ReportSubject::find($subjectId);
                    $predicate = $data['predicate'] ?? ReportHelper::calculatePredicate($score, $subject->kkm ?? 75);

                    ReportGrade::create([
                        'student_report_id' => $report->id,
                        'report_subject_id' => $subjectId,
                        'score' => $score,
                        'score_pts' => $data['score_pts'] ?? null,
                        'score_pas' => $data['score_pas'] ?? null,
                        'score_remedial' => $data['score_remedial'] ?? null,
                        'score_harian' => $data['score_harian'] ?? null,
                        'predicate' => $predicate,
                        'ayat_range' => $data['ayat_range'] ?? null,
                        'description' => $data['description'] ?? null,
                    ]);
                }
            }

            // Handle New Subjects
            if ($request->has('new_subjects')) {
                foreach ($request->new_subjects as $newSubject) {
                    if (empty($newSubject['name'])) continue;

                    $subject = ReportSubject::create([
                        'report_category_id' => $request->report_category_id,
                        'name' => $newSubject['name'],
                    ]);

                    $score = $newSubject['score'] ?? null;
                    $predicate = $newSubject['predicate'] ?? ReportHelper::calculatePredicate($score, 75);

                    ReportGrade::create([
                        'student_report_id' => $report->id,
                        'report_subject_id' => $subject->id,
                        'score' => $score,
                        'score_pts' => $newSubject['score_pts'] ?? null,
                        'score_pas' => $newSubject['score_pas'] ?? null,
                        'score_remedial' => $newSubject['score_remedial'] ?? null,
                        'score_harian' => $newSubject['score_harian'] ?? null,
                        'predicate' => $predicate,
                        'ayat_range' => $newSubject['ayat_range'] ?? null,
                        'description' => $newSubject['description'] ?? null,
                    ]);
                }
            }

            // Handle Diniyah
            if ($request->has('diniyah')) {
                foreach ($request->diniyah as $item) {
                    if (empty($item['surah_id'])) continue;
                    $report->diniyahReports()->create([
                        'surah_id' => $item['surah_id'],
                        'start_ayah' => $item['start_ayah'] ?? 1,
                        'end_ayah' => $item['end_ayah'] ?? 1,
                        'mutu_code' => $item['mutu_code'] ?? 'A',
                        'description' => ReportHelper::getMutuDescription($item['mutu_code'] ?? 'A', 'Diniyah')
                    ]);
                }
            }

            // Handle Ibadah
            if ($request->has('new_ibadah_indicators')) {
                foreach ($request->new_ibadah_indicators as $code => $name) {
                    if (!empty($name)) {
                        \App\Models\IbadahIndicator::firstOrCreate(
                            ['code' => $code],
                            ['name' => $name, 'is_default' => false]
                        );
                    }
                }
            }

            if ($request->has('ibadah')) {
                $ibadahData = $request->ibadah;
                $defaultColumns = [
                    'niat_subuh', 'niat_dzuhur', 'niat_ashar', 'niat_maghrib', 'niat_isya',
                    'iftitah', 'alfatihah', 'surat_pendek', 'rukuk', 'itidal', 'sujud',
                    'duduk_antara_sujud', 'tasyahud_awal', 'tasyahud_akhir'
                ];

                $standardData = [];
                $additionalGrades = [];

                // Parse custom order and exclusions
                $orderedIndicators = null;
                if ($request->filled('ibadah_order')) {
                    $orderedIndicators = json_decode($request->ibadah_order, true);
                }

                $activeIds = $orderedIndicators ? collect($orderedIndicators)->pluck('id')->toArray() : null;

                foreach ($ibadahData as $key => $val) {
                    if (in_array($key, $defaultColumns)) {
                        if ($activeIds === null || in_array($key, $activeIds)) {
                            $standardData[$key] = $val;
                        }
                    } else {
                        if ($activeIds === null || in_array($key, $activeIds)) {
                            $additionalGrades[$key] = $val;
                        }
                    }
                }

                // Explicitly set columns that were deleted to null
                if ($activeIds !== null) {
                    foreach ($defaultColumns as $col) {
                        if (!in_array($col, $activeIds)) {
                            $standardData[$col] = null;
                        }
                    }
                }

                if ($orderedIndicators) {
                    $additionalGrades['_ordered_indicators'] = $orderedIndicators;
                }

                $standardData['additional_grades'] = $additionalGrades;

                $report->ibadahReport()->create($standardData);
            }

            // Handle Probing Activities (PBL)
            if ($request->has('probing')) {
                foreach ($request->probing as $activityData) {
                    if (empty($activityData['tema'])) continue;

                    $pblData = [
                        'tema' => $activityData['tema'],
                        'description' => $activityData['description'] ?? '',
                        'location' => $activityData['location'] ?? '',
                        'kegiatan' => $activityData['kegiatan'] ?? [],
                        'personality' => $activityData['personality'] ?? [],
                        'intelligence' => $activityData['intelligence'] ?? [],
                        'strength' => $activityData['strength'] ?? [],
                        'images' => [],
                    ];

                    $probingActivity = ProbingActivity::create([
                        'student_report_id' => $report->id,
                        'semester_project_id' => !empty($activityData['semester_project_id']) ? $activityData['semester_project_id'] : null,
                        'activity_name' => $activityData['tema'],
                        'location' => $activityData['location'] ?? '',
                        'description' => $activityData['description'] ?? '',
                        'pbl_data' => $pblData,
                    ]);

                    // Store grades in 3NF table
                    if (isset($activityData['kegiatan']) && is_array($activityData['kegiatan'])) {
                        $sortOrder = 0;
                        foreach ($activityData['kegiatan'] as $catIdx => $catData) {
                            $categoryName = $catData['category'] ?? '';
                            if (isset($catData['indicators']) && is_array($catData['indicators'])) {
                                foreach ($catData['indicators'] as $indData) {
                                    $indName = $indData['name'] ?? '';
                                    $indScore = $indData['score'] ?? 'Baik';
                                    if (!empty($indName)) {
                                        \App\Models\ActivityGrade::create([
                                            'probing_activity_id' => $probingActivity->id,
                                            'phase_name'          => $categoryName,
                                            'indicator_name'      => $indName,
                                            'grade'               => $indScore,
                                            'sort_order'          => $sortOrder++,
                                        ]);
                                    }
                                }
                            }
                        }
                    }

                    // Store uploaded images and captions
                    $savedPaths = [];
                    if (isset($activityData['images']) && is_array($activityData['images'])) {
                        foreach ($activityData['images'] as $imgIdx => $img) {
                            if ($img instanceof \Illuminate\Http\UploadedFile) {
                                $path = $img->store('probing_activities', 'public');
                                $this->cropTo34($path);
                                $caption = $activityData['photo_captions'][$imgIdx] ?? '';
                                \App\Models\ActivityDocumentation::create([
                                    'probing_activity_id' => $probingActivity->id,
                                    'image_path' => $path,
                                    'caption' => $caption,
                                ]);
                                $savedPaths[] = $path;
                            }
                        }
                    }

                    // Update JSON compatibility
                    $pblData['images'] = $savedPaths;
                    $probingActivity->update(['pbl_data' => $pblData]);
                }
            }

            DB::commit();
            return redirect()->route('admin.reports.student-list', [
                $request->siswa_id,
                'tahun_ajaran' => $request->query('tahun_ajaran', 'Semua')
            ])->with('success', 'Raport siswa berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan raport: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan raport: ' . $e->getMessage())->withInput();
        }
    }

    public function importGrades(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'period' => 'required|string',
            'academic_type' => 'nullable|string|in:PTS,PAS',
        ]);

        $siswaId = $request->siswa_id;
        $period = $request->period;
        $academicType = $request->academic_type; // 'PTS' or 'PAS' or null

        $student = Siswa::findOrFail($siswaId);

        // Check if student has completed any exams for this period
        $hasAttempts = \App\Models\ExamAttempt::join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
            ->where('exam_attempts.siswa_id', $siswaId)
            ->where('exams.period', $period)
            ->whereNotNull('exam_attempts.score')
            ->exists();

        if (!$hasAttempts) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa belum mengerjakan/memiliki nilai ujian pada periode ini.',
            ]);
        }

        // Get Akademik Category
        $category = ReportCategory::where('name', 'Akademik')->first();
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Kategori Akademik tidak ditemukan.']);
        }
        $subjects = ReportSubject::where('report_category_id', $category->id)->get();

        $grades = [];

        foreach ($subjects as $subject) {
            $subjectId = $subject->id;

            // 1. Fetch Daily Exam scores (type = harian)
            $dailyQuery = \App\Models\ExamAttempt::join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                ->where('exam_attempts.siswa_id', $siswaId)
                ->where('exams.report_subject_id', $subjectId)
                ->where('exams.period', $period)
                ->where('exams.type', 'harian')
                ->whereNotNull('exam_attempts.score');

            if ($academicType === 'PTS') {
                $dailyQuery->where('exams.target_period', 'PTS');
            } elseif ($academicType === 'PAS') {
                $dailyQuery->where('exams.target_period', 'PAS');
            }

            $dailyScores = $dailyQuery->pluck('exam_attempts.score')->map(fn($s) => (float)$s)->toArray();
            $scoreHarian = count($dailyScores) > 0 ? round(array_sum($dailyScores) / count($dailyScores), 2) : null;

            // 2. Fetch Remedial Exam scores (type = remedial)
            $remedialQuery = \App\Models\ExamAttempt::join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                ->where('exam_attempts.siswa_id', $siswaId)
                ->where('exams.report_subject_id', $subjectId)
                ->where('exams.period', $period)
                ->where('exams.type', 'remedial')
                ->whereNotNull('exam_attempts.score');

            if ($academicType === 'PTS') {
                $remedialQuery->where('exams.target_period', 'PTS');
            } elseif ($academicType === 'PAS') {
                $remedialQuery->where('exams.target_period', 'PAS');
            }

            $remedialScores = $remedialQuery->pluck('exam_attempts.score')->map(fn($s) => (float)$s)->toArray();
            $scoreRemedial = count($remedialScores) > 0 ? round(array_sum($remedialScores) / count($remedialScores), 2) : null;

            // 3. Fetch Mid-Term Exam (type = tengah_semester)
            $ptsScore = \App\Models\ExamAttempt::join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                ->where('exam_attempts.siswa_id', $siswaId)
                ->where('exams.report_subject_id', $subjectId)
                ->where('exams.period', $period)
                ->where('exams.type', 'tengah_semester')
                ->whereNotNull('exam_attempts.score')
                ->orderBy('exam_attempts.completed_at', 'desc')
                ->value('exam_attempts.score');

            // 4. Fetch Final Exam (type = akhir_semester)
            $pasScore = \App\Models\ExamAttempt::join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                ->where('exam_attempts.siswa_id', $siswaId)
                ->where('exams.report_subject_id', $subjectId)
                ->where('exams.period', $period)
                ->where('exams.type', 'akhir_semester')
                ->whereNotNull('exam_attempts.score')
                ->orderBy('exam_attempts.completed_at', 'desc')
                ->value('exam_attempts.score');

            $grades[$subject->name] = [
                'score_harian' => $scoreHarian,
                'score_remedial' => $scoreRemedial,
                'score_pts' => $ptsScore !== null ? (float)$ptsScore : null,
                'score_pas' => $pasScore !== null ? (float)$pasScore : null,
            ];
        }

        return response()->json([
            'success' => true,
            'grades' => $grades,
        ]);
    }

    public function importPbl(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'period' => 'required|string',
        ]);

        $siswaId = $request->siswa_id;
        $period = $request->period;

        $activities = \App\Models\ProbingActivity::whereHas('studentReport', function($q) use ($siswaId, $period) {
            $q->where('siswa_id', $siswaId)->where('period', $period);
        })->with(['activityGrades', 'activityDocumentations'])->get();

        if ($activities->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ditemukan data nilai proyek bulanan (PBL) yang sudah difinalisasi untuk siswa ini di semester/periode tersebut.',
            ]);
        }

        $formatted = $activities->map(function($p) {
            $kegiatan = [];
            $tempGrades = [];
            foreach ($p->activityGrades as $g) {
                $catName = trim($g->phase_name ?? '');
                if (empty($catName)) {
                    $indNameLower = strtolower($g->indicator_name ?? '');
                    if (str_contains($indNameLower, 'menyimak') || str_contains($indNameLower, 'keaktifan') || str_contains($indNameLower, 'tim') || str_contains($indNameLower, 'pembekalan')) {
                        $catName = 'Pembekalan';
                    } elseif (str_contains($indNameLower, 'kedisiplinan') || str_contains($indNameLower, 'realisasi') || str_contains($indNameLower, 'bahan') || str_contains($indNameLower, 'materi') || str_contains($indNameLower, 'tanggung jawab') || str_contains($indNameLower, 'tanggung')) {
                        $catName = 'Realisasi';
                    } elseif (str_contains($indNameLower, 'kreativitas') || str_contains($indNameLower, 'deadline') || str_contains($indNameLower, 'persiapan') || str_contains($indNameLower, 'visual')) {
                        $catName = 'Persiapan Presentasi';
                    } elseif (str_contains($indNameLower, 'presentasi') || str_contains($indNameLower, 'penguasaan') || str_contains($indNameLower, 'tanya') || str_contains($indNameLower, 'jawab') || str_contains($indNameLower, 'penyampaian')) {
                        $catName = 'Presentasi';
                    } else {
                        $catName = 'Pembekalan';
                    }
                }
                $tempGrades[$catName][] = [
                    'name' => $g->indicator_name,
                    'score' => $g->grade ?? 'Baik'
                ];
            }
            // Fetch template kegiatan order if available
            $templateKegiatan = null;
            if ($p->semesterProject && $p->semesterProject->projectTemplate) {
                $templateKegiatan = $p->semesterProject->projectTemplate->kegiatan;
            }

            // Fallback: search template by title matching the activity theme name
            if (empty($templateKegiatan) && !empty($p->activity_name)) {
                $tpl = \App\Models\ProjectTemplate::where('title', 'like', '%' . trim($p->activity_name) . '%')->first();
                if ($tpl) {
                    $templateKegiatan = $tpl->kegiatan;
                }
            }

            if (is_string($templateKegiatan)) {
                $templateKegiatan = json_decode($templateKegiatan, true);
            }

            // Fallback to default categories if template is not found
            if (!is_array($templateKegiatan) || empty($templateKegiatan)) {
                $templateKegiatan = [
                    ['category' => 'Pembekalan', 'indicators' => []],
                    ['category' => 'Realisasi', 'indicators' => []],
                    ['category' => 'Persiapan Presentasi', 'indicators' => []],
                    ['category' => 'Presentasi', 'indicators' => []],
                ];
            }

            if (is_array($templateKegiatan) && !empty($templateKegiatan)) {
                $sortedKegiatan = [];
                foreach ($templateKegiatan as $tplCat) {
                    $tplCatName = trim($tplCat['category'] ?? '');
                    if (empty($tplCatName)) continue;

                    $matchedCatName = null;
                    foreach (array_keys($tempGrades) as $savedCatName) {
                        if (strcasecmp($savedCatName, $tplCatName) === 0) {
                            $matchedCatName = $savedCatName;
                            break;
                        }
                    }

                    if ($matchedCatName !== null) {
                        $savedIndicators = $tempGrades[$matchedCatName];
                        unset($tempGrades[$matchedCatName]);

                        $sortedIndicators = [];
                        $tplInds = $tplCat['indicators'] ?? [];
                        foreach ($tplInds as $tplInd) {
                            $tplIndName = is_array($tplInd) ? ($tplInd['name'] ?? '') : $tplInd;
                            $tplIndName = trim($tplIndName);
                            if (empty($tplIndName)) continue;

                            foreach ($savedIndicators as $savedIdx => $savedInd) {
                                if (strcasecmp(trim($savedInd['name']), $tplIndName) === 0) {
                                    $sortedIndicators[] = $savedInd;
                                    unset($savedIndicators[$savedIdx]);
                                }
                            }
                        }
                        foreach ($savedIndicators as $savedInd) {
                            $sortedIndicators[] = $savedInd;
                        }

                        $sortedKegiatan[] = [
                            'category' => $matchedCatName,
                            'indicators' => $sortedIndicators
                        ];
                    }
                }
                foreach ($tempGrades as $remainingCatName => $remainingInds) {
                    $sortedKegiatan[] = [
                        'category' => $remainingCatName,
                        'indicators' => $remainingInds
                    ];
                }
                $kegiatan = $sortedKegiatan;
            } else {
                foreach ($tempGrades as $catName => $indicators) {
                    $kegiatan[] = [
                        'category' => $catName,
                        'indicators' => $indicators
                    ];
                }
            }

            // Map all kegiatan to have stable unique category and indicator IDs for Alpine.js rendering keys
            $kegiatan = array_map(function($catIdx, $cat) {
                $inds = $cat['indicators'] ?? [];
                $indicators = array_map(function($indIdx, $ind) use ($catIdx) {
                    return [
                        'id' => $ind['id'] ?? ('ind_' . $catIdx . '_' . $indIdx . '_' . uniqid()),
                        'name' => $ind['name'] ?? '',
                        'score' => $ind['score'] ?? 'Baik'
                    ];
                }, array_keys($inds), $inds);

                return [
                    'id' => $cat['id'] ?? ('cat_' . $catIdx . '_' . uniqid()),
                    'category' => $cat['category'] ?? '',
                    'indicators' => $indicators
                ];
            }, array_keys($kegiatan), $kegiatan);

            $images = [];
            $captions = [];
            foreach ($p->activityDocumentations as $doc) {
                $images[] = $doc->image_path;
                $captions[] = $doc->caption ?? '';
            }

            return [
                'id' => $p->id,
                'semester_project_id' => $p->semester_project_id ?? '',
                'tema' => $p->activity_name,
                'location' => $p->location ?? '',
                'description' => $p->description ?? '',
                'kegiatan' => $kegiatan,
                'personality' => $p->personality ?? $p->pbl_data['personality'] ?? [],
                'intelligence' => $p->intelligence ?? $p->pbl_data['intelligence'] ?? [],
                'strength' => $p->strength ?? $p->pbl_data['strength'] ?? [],
                'existing_images' => $images,
                'existing_photo_captions' => $captions,
                'previews' => collect($images)->map(fn($path) => asset('storage/'.$path))->toArray(),
                'new_slots' => []
            ];
        });

        return response()->json([
            'success' => true,
            'activities' => $formatted,
        ]);
    }

    public function probingGuide()
    {
        return view('admin.reports.probing_guide');
    }

    public function finalize(\App\Models\StudentReport $report)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        $report->update(['is_draft' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Status raport berhasil diubah menjadi Final dan dipublikasikan ke Student Portal!'
        ]);
    }

    public function finalizeAll(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user->isPimpinan()) {
            abort(403, 'Hanya Pimpinan yang dapat melakukan finalisasi semua raport.');
        }

        $request->validate([
            'period' => 'required|string',
        ]);

        $period = $request->input('period');

        $updatedCount = \App\Models\StudentReport::where('period', $period)
            ->where('is_draft', true)
            ->update(['is_draft' => false]);

        return redirect()->back()->with('success', "Berhasil memfinalisasikan {$updatedCount} raport untuk periode {$period}.");
    }

    private function cropTo34($path)
    {
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) return;

        $info = getimagesize($fullPath);
        if (!$info) return;

        $mime = $info['mime'];
        $width = $info[0];
        $height = $info[1];

        $targetRatio = 3 / 4;
        $currentRatio = $width / $height;

        $cropX = 0;
        $cropY = 0;
        $cropWidth = $width;
        $cropHeight = $height;

        if ($currentRatio > $targetRatio) {
            $cropWidth = (int)($height * $targetRatio);
            $cropX = (int)(($width - $cropWidth) / 2);
        } else {
            $cropHeight = (int)($width / $targetRatio);
            $cropY = (int)(($height - $cropHeight) / 2);
        }

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $src = @imagecreatefromjpeg($fullPath);
                break;
            case 'image/png':
                $src = @imagecreatefrompng($fullPath);
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $src = @imagecreatefromwebp($fullPath);
                } else {
                    return;
                }
                break;
            default:
                return;
        }

        if (!$src) return;

        $cropped = imagecrop($src, [
            'x' => $cropX,
            'y' => $cropY,
            'width' => $cropWidth,
            'height' => $cropHeight
        ]);

        if ($cropped) {
            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':
                    imagejpeg($cropped, $fullPath, 90);
                    break;
                case 'image/png':
                    imagepng($cropped, $fullPath);
                    break;
                case 'image/webp':
                    imagewebp($cropped, $fullPath, 90);
                    break;
            }
            imagedestroy($cropped);
        }
        imagedestroy($src);
    }
}
