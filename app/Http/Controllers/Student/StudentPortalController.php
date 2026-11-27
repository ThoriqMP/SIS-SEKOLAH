<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\StudentReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPortalController extends Controller
{
    public function showCompleteProfileForm()
    {
        $user = Auth::user();
        $student = $user->siswa;
        
        if (!$student) {
            $student = \App\Models\Siswa::create([
                'nama_siswa' => $user->name,
                'nama_orang_tua' => '-',
                'alamat_tagihan' => '-',
                'user_id' => $user->id,
            ]);
        }

        if ($user->is_profile_completed) {
            return redirect()->route('student.dashboard');
        }

        return view('student.complete_profile', compact('user', 'student'));
    }

    public function storeCompleteProfile(Request $request)
    {
        $user = Auth::user();
        $student = $user->siswa;

        if (!$student) {
            return redirect('/')->with('error', 'Profil siswa tidak ditemukan.');
        }

        $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'nama_orang_tua' => 'required|string|max:255',
            'jenis_kelamin_orang_tua' => 'required|string|in:Laki-laki,Perempuan',
            'tanggal_lahir_orang_tua' => 'required|date',
            'telepon_orang_tua' => 'required|string|max:20',
            'alamat_orang_tua' => 'required|string',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $student->update([
            'nama_siswa' => $request->nama_siswa,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nama_orang_tua' => $request->nama_orang_tua,
            'jenis_kelamin_orang_tua' => $request->jenis_kelamin_orang_tua,
            'tanggal_lahir_orang_tua' => $request->tanggal_lahir_orang_tua,
            'telepon_orang_tua' => $request->telepon_orang_tua,
            'alamat_orang_tua' => $request->alamat_orang_tua,
            'alamat_tagihan' => $request->alamat_orang_tua, // Synchronized for invoice billing address
        ]);

        $user->update([
            'name' => $request->nama_siswa,
            'email' => $request->email,
            'is_profile_completed' => true,
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Data pribadi berhasil dilengkapi! Selamat datang di SIS SEKOLAH.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        try {
            $student = $user->siswa;
        } catch (\Throwable $e) {
            $student = null;
        }

        if (!$student) {
            $student = new \App\Models\Siswa([
                'nama_siswa' => $user->name,
                'nis' => '-',
                'class' => 'Umum',
            ]);
        }

        try {
            $latestInvoice = $student->exists ? Invoice::where('id_siswa', $student->id)
                ->where('status', '!=', 'draft')
                ->latest()
                ->first() : null;

            $allReports = $student->exists ? StudentReport::with(['student', 'category', 'grades', 'probingActivities'])
                ->where('siswa_id', $student->id)
                ->where('is_draft', \Illuminate\Support\Facades\DB::raw('false'))
                ->get() : collect();

            $academicReports = $allReports->filter(fn($r) => ($r->category->name ?? '') === 'Akademik');
            $averageScore = $academicReports->avg(function($r) {
                return $r->grades->avg('score');
            });
            $latestReports = $allReports->sortByDesc('created_at')->take(4);

            $level = $user->educationLevel() ?? 'SMA';
            $grade = $user->studentGrade();

            $latestCommentIds = \App\Models\LessonComment::whereHas('lesson.course', function ($query) use ($level, $grade) {
                    $query->where('education_level', $level)
                          ->when($grade !== null, function ($q) use ($grade) {
                              $q->where('grade', $grade);
                          });
                })
                ->selectRaw('MAX(id) as id')
                ->groupBy('lesson_id')
                ->pluck('id');

            $latestDiscussions = \App\Models\LessonComment::whereIn('id', $latestCommentIds)
                ->with(['user', 'lesson'])
                ->latest()
                ->take(5)
                ->get();
        } catch (\Throwable $e) {
            $latestInvoice = null;
            $allReports = collect();
            $academicReports = collect();
            $averageScore = null;
            $latestReports = collect();
            $latestDiscussions = collect();
        }

        return view('student.dashboard', compact('student', 'latestInvoice', 'latestReports', 'averageScore', 'academicReports', 'allReports', 'latestDiscussions'));
    }

    public function invoices()
    {
        $student = Auth::user()->siswa;
        $invoices = Invoice::where('id_siswa', $student->id)
            ->where('status', '!=', 'draft')
            ->orderByDesc('tanggal_invoice')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();
        return view('student.invoices.index', compact('invoices'));
    }

    public function invoiceDetail(Invoice $invoice)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if student owns this invoice and it's not a draft
        if ($user->role === 'student') {
            if ($invoice->id_siswa != $user->siswa->id || $invoice->status === 'draft') {
                abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
            }
        }

        $invoice->load(['siswa', 'invoiceDetails.layanan']);
        $schoolProfile = \App\Models\ProfilSekolah::first();
        return view('student.invoices.show', compact('invoice', 'schoolProfile'));
    }

    public function uploadPaymentProof(Request $request, Invoice $invoice)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'student' && $invoice->id_siswa !== $user->siswa->id) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'checked_items' => 'nullable|array',
            'checked_items.*' => 'exists:invoice_details,id',
        ]);

        if ($request->hasFile('payment_proof')) {
            // Delete old proof if exists
            if ($invoice->payment_proof) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($invoice->payment_proof);
            }

            $checkedItems = $request->input('checked_items', []);
            $paymentNotes = null;
            if (!empty($checkedItems)) {
                $details = \App\Models\InvoiceDetail::with('layanan')->whereIn('id', $checkedItems)->get();
                $names = $details->map(fn($d) => ($d->layanan->nama_layanan ?? 'Item') . ($d->deskripsi_tambahan ? ' (' . $d->deskripsi_tambahan . ')' : ''))->toArray();
                $paymentNotes = implode(', ', $names);
            }

            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $invoice->update([
                'payment_proof' => $path,
                'payment_date' => now(),
                'payment_notes' => $paymentNotes,
                'status' => 'verifikasi',
            ]);

            return back()->with('success', 'Bukti pembayaran berhasil diupload. Mohon tunggu konfirmasi admin.');
        }

        return back()->with('error', 'Gagal mengupload bukti pembayaran.');
    }

    public function reports()
    {
        $student = Auth::user()->siswa;
        
        $allReports = StudentReport::with(['student', 'category', 'grades.subject', 'diniyahReports', 'ibadahReport', 'probingActivities'])
            ->where('siswa_id', $student->id)
            ->where('is_draft', \Illuminate\Support\Facades\DB::raw('false'))
            ->orderBy('created_at', 'asc') // Order by date for trends
            ->get();

        // Grouping for dashboard view
        $academicReports = $allReports->filter(fn($r) => $r->category->name === 'Akademik');
        $diniyahReports = $allReports->filter(fn($r) => $r->category->name === 'Diniyah');
        $ibadahReports = $allReports->filter(fn($r) => $r->category->name === 'Praktek Ibadah');
        $probingReports = $allReports->filter(fn($r) => str_contains(strtolower($r->category->name ?? ''), 'project based') || str_contains(strtolower($r->category->name ?? ''), 'probing') || strtolower($r->category->name ?? '') === 'pbl');

        // Prepare data for the graph (Trend)
        $academicTrend = $academicReports->map(function($r) {
            return [
                'period' => $r->period,
                'average' => round($r->grades->avg('score'), 2),
            ];
        })->values();

        // Summary of Diniyah (latest surahs)
        $latestDiniyah = $diniyahReports->last();
        
        // Summary of Ibadah
        $latestIbadah = $ibadahReports->last();

        return view('student.reports.index', compact(
            'academicReports', 
            'academicTrend', 
            'latestDiniyah', 
            'latestIbadah', 
            'probingReports',
            'diniyahReports',
            'ibadahReports'
        ));
    }

    public function reportDetail(StudentReport $report)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Policy check: Student can only see their own. Admin/Guru (non-student) can see all.
        if ($user->role === 'student' && ($report->siswa_id != $user->siswa->id || $report->is_draft)) {
            abort(403, 'Anda tidak memiliki akses ke raport ini.');
        }

        $report->load(['student', 'category', 'grades.subject', 'probingActivities', 'teacher']);
        return view('student.reports.show', compact('report'));
    }

    public function downloadInvoicePdf(Invoice $invoice)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'student') {
            if ($invoice->id_siswa != $user->siswa->id || $invoice->status === 'draft') {
                abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
            }
        }

        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = \App\Helpers\TerbilangHelper::rupiah($invoice->grand_total);
        
        $dueDate = $invoice->jatuh_tempo ?? now();
        $monthAbbr = $dueDate->format('M');
        $studentName = \Illuminate\Support\Str::slug($invoice->siswa->nama_siswa ?? 'TanpaNama', '_');
        $filename = 'Invoice-'.$monthAbbr.'_'.$studentName.'.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice', 'terbilang'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function downloadKwitansiPdf(Invoice $invoice)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'student') {
            if ($invoice->id_siswa != $user->siswa->id || $invoice->status === 'draft') {
                abort(403, 'Anda tidak memiliki akses ke kwitansi ini.');
            }
        }

        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = \App\Helpers\TerbilangHelper::rupiah($invoice->grand_total);
        
        $studentName = \Illuminate\Support\Str::slug($invoice->siswa->nama_siswa ?? 'TanpaNama', '_');
        $filename = 'Kwitansi-'.$invoice->id.'_'.$studentName.'.pdf';

        $stampColorHex = match ($invoice->status) {
            'paid' => '16a34a',
            'partially_paid' => 'd97706',
            default => '2563eb',
        };

        $stampLogoPath = \App\Helpers\ImageHelper::getTintedLogoPath($invoice->profilSekolah->logo_path ?? null, $stampColorHex);
        $transparentSignaturePath = \App\Helpers\ImageHelper::getTransparentSignaturePath($invoice->profilSekolah->signature_path ?? null);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.kwitansi', compact('invoice', 'terbilang', 'stampLogoPath', 'transparentSignaturePath'));
        $pdf->setPaper('a5', 'landscape');

        return $pdf->download($filename);
    }

    public function downloadReportPdf(StudentReport $report)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'student' && ($report->siswa_id != $user->siswa->id || $report->is_draft)) {
            abort(403, 'Anda tidak memiliki akses ke raport ini.');
        }

        $report->load(['student', 'category', 'grades.subject', 'probingActivities', 'teacher']);
        
        // Sanitize filename: replace / and \ with -
        $safePeriod = str_replace(['/', '\\'], '-', $report->period);
        $filename = "Raport_{$report->student->nama_siswa}_{$safePeriod}.pdf";
        
        $school = \App\Models\ProfilSekolah::first();
        
        $catName = strtolower(trim($report->category->name));
        $viewPath = 'student.reports.pdf.akademik'; // Default
        if (str_contains($catName, 'project based') || str_contains($catName, 'probing') || $catName === 'pbl') $viewPath = 'student.reports.pdf.probing';
        elseif ($catName === 'diniyah') $viewPath = 'student.reports.pdf.diniyah';
        elseif (str_contains($catName, 'ibadah')) $viewPath = 'student.reports.pdf.ibadah';

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->with('raportSetting')->first();
        $printPlace = optional($activeYear?->raportSetting)->place ?? 'Bogor';
        $printDate = optional($activeYear?->raportSetting)->report_date 
            ? \Carbon\Carbon::parse($activeYear->raportSetting->report_date)->locale('id')->translatedFormat('d F Y') 
            : now()->locale('id')->translatedFormat('d F Y');

        $pdf = Pdf::loadView($viewPath, compact('report', 'school', 'printPlace', 'printDate'));
        return $pdf->download($filename);
    }
}
