<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ReportHelper;
use App\Http\Controllers\Controller;
use App\Models\ActivityDocumentation;
use App\Models\ActivityGrade;
use App\Models\AcademicYear;
use App\Models\BulkPblProject;
use App\Models\ProbingActivity;
use App\Models\ReportCategory;
use App\Models\SemesterProject;
use App\Models\Siswa;
use App\Models\StudentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BulkPblController extends Controller
{
    public function index()
    {
        $query = BulkPblProject::with('semesterProject.academicYear')->orderBy('created_at', 'desc');

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isPimpinan()) {
            $query->where('teacher_id', $user->id);
        }

        $projects = $query->get();

        return view('admin.reports.bulk_pbl.index', compact('projects'));
    }

    public function create(Request $request)
    {
        $defaultPeriod   = ReportHelper::getCurrentPeriod();
        $intelligences   = ReportHelper::getMultipleIntelligences();
        $strengths       = ReportHelper::getST30Strengths();
        $students        = Siswa::orderBy('nama_siswa')->get(['id', 'nama_siswa', 'nis', 'class']);
        $academicYears   = AcademicYear::orderByDesc('is_active')->orderByDesc('id')->get();
        $activeYear      = $academicYears->firstWhere('is_active', true);

        // Load semester projects for active year (default)
        $semesterProjects = SemesterProject::when(
            $activeYear,
            fn($q) => $q->where('academic_year_id', $activeYear->id)
        )->orderBy('sort_order')->get();

        return view('admin.reports.bulk_pbl.create', compact(
            'defaultPeriod',
            'intelligences',
            'strengths',
            'students',
            'academicYears',
            'activeYear',
            'semesterProjects'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'period'             => 'required|string',
            'semester_project_id'=> 'required|exists:semester_projects,id',
            'class'              => 'nullable|string',
            'students'           => 'required|array|min:1',
            'scores'             => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    if ($img->isValid()) {
                        $path = $img->store('probing_activities', 'public');
                        $this->cropTo34($path);
                        $imagePaths[] = $path;
                    }
                }
            }

            $studentsData = Siswa::whereIn('id', $request->students)
                ->get(['id', 'nama_siswa'])
                ->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama_siswa]);

            $semesterProject = SemesterProject::findOrFail($request->semester_project_id);

            // Simpan scores, personality, intelligence, strength, images ke pbl_data (draft)
            // Format baru: scores[siswa_id][phase_idx][indicator_idx] = grade_string
            $pblData = [
                'kegiatan'       => $semesterProject->kegiatan ?? [],
                'students'       => $request->students,
                'students_data'  => $studentsData,
                'scores'         => $request->scores,
                'personality'    => $request->personality ?? [],
                'intelligence'   => $request->intelligence ?? [],
                'strength'       => $request->strength ?? [],
                'notes_developed'=> $request->notes_developed ?? [],
                'notes_need_development'=> $request->notes_need_development ?? [],
                'images'         => $imagePaths,
                'photo_captions' => $request->photo_captions ?? [],
            ];

            BulkPblProject::create([
                'teacher_id'          => Auth::id() ?: 1,
                'semester_project_id' => $request->semester_project_id,
                'period'              => $request->period,
                'kelas'               => $request->class ?? '',
                'tema'                => $semesterProject->title,
                'location'            => $request->location ?? '',
                'description'         => $request->description ?? $semesterProject->description ?? '',
                'pbl_data'            => $pblData,
                'status'              => 'draft',
            ]);

            DB::commit();

            return redirect()->route('admin.reports.bulk-pbl.index')
                ->with('success', 'Draf PBL kolektif berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan draf rapor PBL kolektif: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function show(BulkPblProject $project)
    {
        $project->load('semesterProject.academicYear');
        return view('admin.reports.bulk_pbl.show', compact('project'));
    }

    public function finalize(BulkPblProject $project)
    {
        if ($project->status === 'finalized') {
            return back()->with('error', 'Proyek ini sudah difinalisasi.');
        }

        try {
            DB::beginTransaction();

            // Cari report category probing
            $pblCategory = ReportCategory::where(function ($q) {
                $q->where('name', 'like', '%project based%')
                  ->orWhere('name', 'like', '%probing%')
                  ->orWhere('name', 'pbl');
            })->first();

            if (!$pblCategory) {
                return back()->with('error', 'Kategori Raport Probing tidak ditemukan di database.');
            }

            $pblData         = $project->pbl_data;
            $students        = $pblData['students'] ?? [];
            $kegiatanInput   = $pblData['kegiatan'] ?? [];
            $scoresInput     = $pblData['scores'] ?? [];
            $semesterProject = $project->semesterProject;

            $warnings = [];

            foreach ($students as $studentId) {
                // KRITIS: Cari StudentReport probing yang sudah ada untuk semester ini.
                // Jika tidak ada, guru harus membuat segmen raport probing terlebih dahulu.
                $report = StudentReport::where('siswa_id', $studentId)
                    ->where('report_category_id', $pblCategory->id)
                    ->where('period', $project->period)
                    ->first();

                if (!$report) {
                    $siswa = Siswa::find($studentId);
                    $namaS = $siswa ? $siswa->nama_siswa : "ID#{$studentId}";
                    $warnings[] = "Siswa {$namaS} belum memiliki segmen raport probing untuk periode '{$project->period}'. Buat segmen raportnya terlebih dahulu.";
                    continue; // Lewati siswa ini, jangan buat raport baru otomatis
                }

                // Save student-specific notes (if any)
                $updateData = [];
                if (!empty($pblData['notes_developed'][$studentId])) {
                    $updateData['notes_developed'] = $pblData['notes_developed'][$studentId];
                }
                if (!empty($pblData['notes_need_development'][$studentId])) {
                    $updateData['notes_need_development'] = $pblData['notes_need_development'][$studentId];
                }
                if (!empty($updateData)) {
                    $report->update($updateData);
                }

                // Map kegiatan dengan scores siswa ini
                $studentScores = $scoresInput[$studentId] ?? [];

                // Upload images per siswa (shared images dari bulk)
                $imagePaths = $pblData['images'] ?? [];
                $captions   = $pblData['photo_captions'] ?? [];

                // Potensi per siswa
                $personality  = $pblData['personality'][$studentId] ?? [];
                $intelligence = $pblData['intelligence'][$studentId] ?? [];
                $strength     = $pblData['strength'][$studentId] ?? [];

                // Buat ProbingActivity
                $probingActivity = ProbingActivity::create([
                    'student_report_id'   => $report->id,
                    'semester_project_id' => $project->semester_project_id,
                    'activity_name'       => $semesterProject ? $semesterProject->title : $project->tema,
                    'activity_title'      => $semesterProject ? $semesterProject->title : $project->tema,
                    'location'            => $project->location,
                    'description'         => $project->description ?? '',
                    'personality'         => $personality,
                    'intelligence'        => $intelligence,
                    'strength'            => $strength,
                ]);

                // Buat ActivityGrade records dari kegiatan + scores
                $sortOrder = 0;
                foreach ($kegiatanInput as $phaseIdx => $catData) {
                    $phaseName = $catData['category'] ?? 'Kegiatan';
                    foreach ($catData['indicators'] ?? [] as $indIdx => $indData) {
                        $indName   = $indData['name'] ?? '';
                        $rawScore  = $studentScores[$phaseIdx][$indIdx] ?? null;
                        $grade     = $this->resolveGradeLabel($rawScore);

                        ActivityGrade::create([
                            'probing_activity_id' => $probingActivity->id,
                            'phase_name'          => $phaseName,
                            'indicator_name'      => $indName,
                            'grade'               => $grade,
                            'sort_order'          => $sortOrder++,
                        ]);
                    }
                }

                // Buat ActivityDocumentation records
                foreach ($imagePaths as $idx => $imgPath) {
                    if (empty($imgPath)) continue;
                    ActivityDocumentation::create([
                        'probing_activity_id' => $probingActivity->id,
                        'image_path'          => $imgPath,
                        'caption'             => $captions[$idx] ?? null,
                        'sort_order'          => $idx,
                    ]);
                }
            }

            $project->update(['status' => 'finalized']);

            DB::commit();

            $message = 'Finalisasi berhasil. Laporan PBL telah ditambahkan ke masing-masing siswa.';
            if (!empty($warnings)) {
                $message .= ' Namun ada ' . count($warnings) . ' siswa yang dilewati karena belum memiliki segmen raport probing.';
                return redirect()->route('admin.reports.bulk-pbl.index')
                    ->with('success', $message)
                    ->with('warnings', $warnings);
            }

            return redirect()->route('admin.reports.bulk-pbl.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Finalisasi gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal mem-finalisasi data: ' . $e->getMessage());
        }
    }

    public function destroy(BulkPblProject $project)
    {
        // Hapus file gambar jika ada
        $images = $project->pbl_data['images'] ?? [];
        foreach ($images as $img) {
            Storage::disk('public')->delete($img);
        }
        $project->delete();
        return redirect()->route('admin.reports.bulk-pbl.index')
            ->with('success', 'Draf proyek kolektif berhasil dihapus.');
    }

    private function resolveGradeLabel(mixed $raw): ?string
    {
        if ($raw === null || $raw === '' || $raw === '-') return null;

        $map = [5 => 'Sangat Baik', 4 => 'Baik', 3 => 'Cukup', 2 => 'Kurang', 1 => 'Buruk'];

        if (is_numeric($raw)) {
            return $map[(int)$raw] ?? (string)$raw;
        }

        // Already a string label
        return (string)$raw;
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
