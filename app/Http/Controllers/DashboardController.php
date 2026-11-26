<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Layanan;
use App\Models\Siswa;
use App\Models\VideoConference;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Lesson;
use App\Models\LessonComment;
use App\Models\Attendance;
use App\Models\StudentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isGuru = $user && $user->isGuru();
        $canViewFinance = $user && ($user->isSuperadmin() || $user->isPimpinan() || $user->hasPermission('invoices.index'));

        // Cache ringkasan metrik global sekolah selama 60 detik untuk memotong overhead DB
        try {
            $totalStudents = Cache::remember('dashboard_total_students_aktif', 60, fn() => Siswa::where('status', 'aktif')->count());
            $totalServices = Cache::remember('dashboard_total_services', 120, fn() => Layanan::count());
        } catch (\Throwable $e) {
            $totalStudents = 0;
            $totalServices = 0;
        }

        // ── New School KPIs ──────────────────────────────────────────
        $studentsWithHighScore = 0;
        $hadirToday = 0;
        $totalExpectedToday = $totalStudents;
        $recentAttendances = collect();
        $activeSemester = 'Ganjil 2026/2027';

        try {
            // Siswa berprestasi: laporan dengan rata-rata nilai >= 90
            $studentsWithHighScore = Cache::remember('dashboard_high_score', 120, function () {
                return StudentReport::with('grades')
                    ->where('is_draft', false)
                    ->get()
                    ->filter(fn($r) => $r->grades->avg('score') >= 90)
                    ->unique('siswa_id')
                    ->count();
            });
        } catch (\Throwable $e) {
            $studentsWithHighScore = 0;
        }

        try {
            $attendanceToday = Attendance::whereDate('date', today())->get();
            $hadirToday = $attendanceToday->where('status', 'hadir')->count();

            $recentAttendances = Attendance::with('siswa')
                ->whereDate('date', today())
                ->latest()
                ->take(5)
                ->get();
        } catch (\Throwable $e) {
            $hadirToday = 0;
            $recentAttendances = collect();
        }

        // Data for Teacher Academic Dashboard
        $todayConferences = collect();
        $pendingGradingCount = 0;
        $myAssignments = collect();
        $totalLessons = 0;
        $recentComments = collect();

        if ($isGuru) {
            try {
                // Live or scheduled classes today
                $todayConferences = VideoConference::where(function($q) use ($user) {
                        $q->where('teacher_id', $user->id)
                          ->orWhereJsonContains('class', (int) $user->id)
                          ->orWhereJsonContains('class', (string) $user->id);
                    })
                    ->where('scheduled_at', '>=', now()->startOfDay())
                    ->where('scheduled_at', '<=', now()->endOfDay()->addHours(2))
                    ->orderBy('scheduled_at', 'asc')
                    ->get();

                // Assignments by this teacher
                $myAssignments = Assignment::with(['subject'])
                    ->where('teacher_id', $user->id)
                    ->withCount('submissions')
                    ->orderByDesc('created_at')
                    ->take(5)
                    ->get();

                // Count pending submissions (waiting for grading)
                $pendingGradingCount = AssignmentSubmission::whereHas('assignment', function ($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                })->whereNull('score')->count();

                // Count lessons & recent comments
                $totalLessons = Lesson::count();
                $recentComments = LessonComment::with(['user', 'lesson'])
                    ->latest()
                    ->take(5)
                    ->get();
            } catch (\Throwable $e) {
                $todayConferences = collect();
                $myAssignments = collect();
                $pendingGradingCount = 0;
                $totalLessons = 0;
                $recentComments = collect();
            }
        }

        // Financial Data (Only calculated if authorized)
        $totalInvoices = 0;
        $pendingInvoices = 0;
        $paidInvoices = 0;
        $overdueInvoices = 0;
        $draftInvoices = 0;
        $revenue = 0;
        $potentialRevenue = 0;
        $totalExpenses = 0;
        $netCashflow = 0;
        $revenueChart = [];
        $expenseChart = [];
        $statusChart = [];
        $expensesByCategory = collect();
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        if ($canViewFinance) {
            try {
                $totalInvoices = Invoice::count();

            // Filtered Stats Query: Agregasi 1x query untuk semua status invoice (menggantikan 6 query terpisah)
            $invoiceQuery = Invoice::query()->whereYear('jatuh_tempo', $year);
            if ($month) {
                $invoiceQuery->whereMonth('jatuh_tempo', $month);
            }

            $stats = (clone $invoiceQuery)
                ->selectRaw("
                    COUNT(CASE WHEN status = 'sent' THEN 1 END) as pending_count,
                    COUNT(CASE WHEN status = 'paid' THEN 1 END) as paid_count,
                    COUNT(CASE WHEN status = 'overdue' THEN 1 END) as overdue_count,
                    COUNT(CASE WHEN status = 'draft' THEN 1 END) as draft_count,
                    COALESCE(SUM(CASE WHEN status = 'paid' THEN grand_total ELSE 0 END), 0) as paid_revenue,
                    COALESCE(SUM(CASE WHEN status IN ('sent', 'overdue') THEN grand_total ELSE 0 END), 0) as potential_revenue
                ")
                ->first();

            $pendingInvoices = (int) ($stats->pending_count ?? 0);
            $paidInvoices = (int) ($stats->paid_count ?? 0);
            $overdueInvoices = (int) ($stats->overdue_count ?? 0);
            $draftInvoices = (int) ($stats->draft_count ?? 0);
            $revenue = (float) ($stats->paid_revenue ?? 0);
            $potentialRevenue = (float) ($stats->potential_revenue ?? 0);

            $expenseQuery = \App\Models\Expense::query()->whereYear('date', $year);
            if ($month) {
                $expenseQuery->whereMonth('date', $month);
            }
            $totalExpenses = (clone $expenseQuery)->sum('amount');
            $netCashflow = $revenue - $totalExpenses;

            $isSqlite = DB::connection()->getDriverName() === 'sqlite';
            $monthExpr = $isSqlite ? "CAST(strftime('%m', jatuh_tempo) AS INTEGER)" : "MONTH(jatuh_tempo)";
            $expenseMonthExpr = $isSqlite ? "CAST(strftime('%m', date) AS INTEGER)" : "MONTH(date)";

            $monthlyRevenueData = Invoice::whereYear('jatuh_tempo', $year)
                ->where('status', 'paid')
                ->select(
                    DB::raw("$monthExpr as month"),
                    DB::raw('SUM(grand_total) as total')
                )
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $revenueChart[] = $monthlyRevenueData[$i] ?? 0;
            }

            $monthlyExpenseData = \App\Models\Expense::whereYear('date', $year)
                ->select(
                    DB::raw("$expenseMonthExpr as month"),
                    DB::raw('SUM(amount) as total')
                )
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $expenseChart[] = $monthlyExpenseData[$i] ?? 0;
            }

            $statusDistribution = (clone $invoiceQuery)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $statusChart = [
                'paid' => $statusDistribution['paid'] ?? 0,
                'sent' => $statusDistribution['sent'] ?? 0,
                'overdue' => $statusDistribution['overdue'] ?? 0,
                'draft' => $statusDistribution['draft'] ?? 0,
            ];

            $expensesByCategory = (clone $expenseQuery)
                ->with('category')
                ->select('expense_category_id', DB::raw('SUM(amount) as total'))
                ->groupBy('expense_category_id')
                ->orderByDesc('total')
                ->take(5)
                ->get();
            } catch (\Throwable $e) {
                // Fallback ke nilai default 0 / empty collection jika tabel belum dibuat
            }
        }

        return view('dashboard', compact(
            'totalInvoices', 'totalStudents', 'totalServices',
            'pendingInvoices', 'paidInvoices', 'overdueInvoices', 'draftInvoices',
            'revenue', 'potentialRevenue', 'totalExpenses', 'netCashflow',
            'revenueChart', 'expenseChart', 'statusChart', 'expensesByCategory',
            'year', 'month', 'canViewFinance', 'isGuru',
            'todayConferences', 'myAssignments', 'pendingGradingCount', 'totalLessons', 'recentComments',
            'studentsWithHighScore', 'hadirToday', 'totalExpectedToday', 'recentAttendances', 'activeSemester'
        ));
    }

    public function export(Request $request)
    {
        $user = Auth::user() ?? auth('mbi')->user();
        if (!$user || (!$user->isSuperadmin() && !$user->isPimpinan() && !$user->hasPermission('invoices.index'))) {
            abort(403, 'Aksi ini hanya diperbolehkan untuk pengguna yang memiliki hak akses keuangan.');
        }
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        $query = Invoice::with(['siswa', 'invoiceDetails.layanan'])
            ->whereYear('jatuh_tempo', $year);

        if ($month) {
            $query->whereMonth('jatuh_tempo', $month);
        }
        
        // Sort by Date
        $query->orderBy('jatuh_tempo', 'desc');

        $filename = 'laporan_pendapatan_' . $year . ($month ? '_bulan_'.$month : '') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM

            fputcsv($handle, [
                'Tanggal Jatuh Tempo',
                'No Invoice',
                'Siswa',
                'Item',
                'Harga',
                'Status',
                'Total Invoice'
            ]);

            $query->chunk(100, function ($invoices) use ($handle) {
                foreach ($invoices as $invoice) {
                    foreach ($invoice->invoiceDetails as $detail) {
                        $itemName = $detail->layanan->nama_layanan ?? '-';
                        if ($detail->deskripsi_tambahan) $itemName .= ' (' . $detail->deskripsi_tambahan . ')';

                        fputcsv($handle, [
                            $invoice->jatuh_tempo->format('Y-m-d'),
                            $invoice->no_invoice,
                            $invoice->siswa->nama_siswa ?? 'Unknown',
                            $itemName,
                            $detail->harga_satuan,
                            ucfirst($invoice->status),
                            $invoice->grand_total
                        ]);
                    }
                }
            });
            fclose($handle);
        }, $filename);
    }

    /**
     * Wipe all operational, assessment, and transaction data (Superadmin only)
     */
    public function wipeAllData(Request $request, \App\Services\SystemDataWipeService $wipeService)
    {
        $user = auth()->user() ?? auth('mbi')->user();
        if (!$user || $user->role !== 'superadmin') {
            abort(403, 'Aksi ini hanya diperuntukkan bagi pengguna dengan role Superadmin.');
        }

        $request->validate([
            'confirm_keyword' => 'required|string',
        ], [
            'confirm_keyword.required' => 'Frasa konfirmasi wajib diisi.',
        ]);

        if (trim((string) $request->input('confirm_keyword')) !== 'HAPUS-SEMUA-DATA') {
            return back()->with('error', 'Frasa konfirmasi tidak sesuai! Ketik "HAPUS-SEMUA-DATA" secara tepat untuk melanjutkan.');
        }

        try {
            $result = $wipeService->wipeAllData();
            return redirect()->route('dashboard')->with('success', "BERHASIL: Seluruh data operasional ({$result['tables_count']} tabel) telah dibersihkan secara total.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membersihkan data: ' . $e->getMessage());
        }
    }
}
