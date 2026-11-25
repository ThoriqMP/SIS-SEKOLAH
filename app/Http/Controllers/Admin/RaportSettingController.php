<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RaportSettingController extends Controller
{
    public function index()
    {
        $academicYears = \App\Models\AcademicYear::orderBy('id', 'desc')->get();
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->with('raportSetting')->first();
        
        return view('admin.setting-rapor.index', compact('academicYears', 'activeYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year_name' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'report_date' => 'required|date',
            'place' => 'required|string',
        ]);

        \DB::beginTransaction();
        try {
            // Nonaktifkan semua tahun ajaran lainnya
            \App\Models\AcademicYear::query()->update(['is_active' => false]);

            // Cari atau buat tahun ajaran baru
            $academicYear = \App\Models\AcademicYear::firstOrCreate(
                ['year_name' => $request->year_name, 'semester' => $request->semester],
                ['is_active' => true]
            );

            // Pastikan tahun ajaran yang dicari diaktifkan (jika sudah ada sebelumnya)
            if (!$academicYear->wasRecentlyCreated) {
                $academicYear->update(['is_active' => true]);
            }

            // Update atau buat setting raport
            \App\Models\RaportSetting::updateOrCreate(
                ['academic_year_id' => $academicYear->id],
                ['report_date' => $request->report_date, 'place' => $request->place]
            );

            \DB::commit();

            return redirect()->back()->with('success', 'Pengaturan tanggal rapor berhasil disimpan.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage())->withInput();
        }
    }
}
