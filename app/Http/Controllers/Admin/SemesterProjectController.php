<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ProjectTemplate;
use App\Models\SemesterProject;
use Illuminate\Http\Request;

class SemesterProjectController extends Controller
{
    public function index(Request $request)
    {
        $academicYears    = AcademicYear::orderByDesc('is_active')->orderByDesc('id')->get();
        
        $selectedYearId = $request->query('year');
        if ($selectedYearId) {
            $activeYear = $academicYears->firstWhere('id', $selectedYearId);
        } else {
            $activeYear = $academicYears->firstWhere('is_active', true);
        }

        $semesterProjects = SemesterProject::with(['academicYear', 'projectTemplate'])
            ->when($activeYear, fn($q) => $q->where('academic_year_id', $activeYear->id))
            ->orderBy('sort_order')
            ->get();

        return view('admin.semester_projects.index', compact('semesterProjects', 'academicYears', 'activeYear'));
    }

    public function create()
    {
        $academicYears     = AcademicYear::orderByDesc('is_active')->orderByDesc('id')->get();
        $activeYear        = $academicYears->firstWhere('is_active', true);
        $projectTemplates  = ProjectTemplate::orderBy('title')->get();

        return view('admin.semester_projects.create', compact('academicYears', 'projectTemplates', 'activeYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year_name'             => 'required|string',
            'semester'              => 'required|in:Ganjil,Genap',
            'project_template_ids'  => 'nullable|array',
            'project_template_ids.*'=> 'exists:project_templates,id',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'sort_order'            => 'nullable|integer|min:0',
        ]);

        $academicYear = AcademicYear::firstOrCreate([
            'year_name' => $request->year_name,
            'semester'  => $request->semester,
        ]);

        SemesterProject::create([
            'academic_year_id'    => $academicYear->id,
            'project_template_id' => $request->project_template_ids[0] ?? null, // Fallback ke item pertama untuk backward compatibility DB FK
            'title'               => $request->title,
            'description'         => $request->description,
            'kegiatan'            => $request->project_template_ids ?? [], // Simpan array ID-ID proyek bulanan di kegiatan
            'sort_order'          => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.semester-projects.index')
            ->with('success', 'Proyek semester berhasil ditambahkan.');
    }

    public function edit(SemesterProject $semesterProject)
    {
        $academicYears    = AcademicYear::orderByDesc('is_active')->orderByDesc('id')->get();
        $activeYear       = $academicYears->firstWhere('is_active', true);
        $projectTemplates = ProjectTemplate::orderBy('title')->get();

        return view('admin.semester_projects.edit', compact('semesterProject', 'academicYears', 'projectTemplates', 'activeYear'));
    }

    public function update(Request $request, SemesterProject $semesterProject)
    {
        $request->validate([
            'year_name'             => 'required|string',
            'semester'              => 'required|in:Ganjil,Genap',
            'project_template_ids'  => 'nullable|array',
            'project_template_ids.*'=> 'exists:project_templates,id',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'sort_order'            => 'nullable|integer|min:0',
        ]);

        $academicYear = AcademicYear::firstOrCreate([
            'year_name' => $request->year_name,
            'semester'  => $request->semester,
        ]);

        $semesterProject->update([
            'academic_year_id'    => $academicYear->id,
            'project_template_id' => $request->project_template_ids[0] ?? null, // Fallback ke item pertama untuk backward compatibility DB FK
            'title'               => $request->title,
            'description'         => $request->description,
            'kegiatan'            => $request->project_template_ids ?? [], // Simpan array ID-ID proyek bulanan di kegiatan
            'sort_order'          => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.semester-projects.index')
            ->with('success', 'Proyek semester berhasil diperbarui.');
    }

    public function destroy(SemesterProject $semesterProject)
    {
        $semesterProject->delete();

        return redirect()->route('admin.semester-projects.index')
            ->with('success', 'Proyek semester berhasil dihapus.');
    }

    /**
     * API endpoint: return semester projects for a given academic_year_id (for JS/AJAX in BulkPBL).
     */
    public function byAcademicYear(Request $request)
    {
        $projects = SemesterProject::where('academic_year_id', $request->academic_year_id)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'description', 'kegiatan']);

        return response()->json($projects);
    }
}
