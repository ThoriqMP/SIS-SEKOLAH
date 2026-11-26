<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $classFilter = $request->input('class');

        $classes = Siswa::where('status', 'aktif')
            ->whereNotNull('class')
            ->distinct()
            ->pluck('class')
            ->sort()
            ->values();

        $students = Siswa::where('status', 'aktif')
            ->when($classFilter, fn ($q) => $q->where('class', $classFilter))
            ->orderBy('class')
            ->orderBy('nama_siswa')
            ->get();

        $existing = Attendance::whereDate('date', $date)
            ->get()
            ->keyBy('siswa_id');

        $summary = [
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alpha' => 0,
        ];

        foreach ($students as $student) {
            $status = $existing->get($student->id)?->status;
            if ($status && isset($summary[$status])) {
                $summary[$status]++;
            }
        }

        return view('attendances.index', compact('students', 'existing', 'date', 'classFilter', 'classes', 'summary'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'attendances' => ['required', 'array'],
            'attendances.*.status' => ['required', 'in:hadir,izin,sakit,alpha'],
            'attendances.*.notes' => ['nullable', 'string', 'max:255'],
        ]);

        $date = $request->input('date');
        $userId = Auth::id();

        foreach ($request->input('attendances') as $siswaId => $data) {
            Attendance::updateOrCreate(
                [
                    'siswa_id' => (int) $siswaId,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'notes' => $data['notes'] ?? null,
                    'recorded_by' => $userId,
                ]
            );
        }

        return redirect()
            ->route('attendances.index', ['date' => $date, 'class' => $request->input('class')])
            ->with('success', 'Data absensi tanggal ' . date('d M Y', strtotime($date)) . ' berhasil disimpan.');
    }

    public function studentIndex()
    {
        $user = Auth::user();
        $student = $user->siswa;

        $records = $student
            ? Attendance::where('siswa_id', $student->id)->orderByDesc('date')->take(30)->get()
            : collect();

        $summary = [
            'hadir' => $records->where('status', 'hadir')->count(),
            'izin' => $records->where('status', 'izin')->count(),
            'sakit' => $records->where('status', 'sakit')->count(),
            'alpha' => $records->where('status', 'alpha')->count(),
        ];

        return view('student.attendances.index', compact('student', 'records', 'summary'));
    }
}
