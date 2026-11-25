<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportCategory;
use App\Models\AcademicGradeRange;
use App\Models\NonAcademicMutuLabel;
use Illuminate\Http\Request;

class AcademicSettingController extends Controller
{
    public function index()
    {
        $gradeRanges = AcademicGradeRange::orderBy('min_score', 'desc')->get();
        $mutuLabels = NonAcademicMutuLabel::all();
        return view('admin.academic-settings.index', compact('gradeRanges', 'mutuLabels'));
    }

    public function updateGradeRanges(Request $request)
    {
        $request->validate([
            'ranges' => 'required|array',
            'ranges.*.letter' => 'required|string',
            'ranges.*.min_score' => 'required|numeric|min:0|max:100',
            'ranges.*.description' => 'nullable|string',
        ]);

        AcademicGradeRange::truncate();

        foreach ($request->ranges as $range) {
            AcademicGradeRange::create($range);
        }

        return redirect()->back()->with('success', 'Pengaturan Rentang Nilai Predikat berhasil diperbarui.');
    }

    public function updateMutuLabels(Request $request)
    {
        $request->validate([
            'labels' => 'required|array',
            'labels.*.type' => 'required|string',
            'labels.*.code' => 'required|string',
            'labels.*.label' => 'required|string',
        ]);

        NonAcademicMutuLabel::truncate();

        foreach ($request->labels as $label) {
            NonAcademicMutuLabel::create($label);
        }

        return redirect()->back()->with('success', 'Keterangan Huruf Mutu berhasil diperbarui.');
    }
}
