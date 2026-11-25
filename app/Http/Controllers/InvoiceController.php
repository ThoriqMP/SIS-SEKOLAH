<?php

namespace App\Http\Controllers;

use App\Helpers\TerbilangHelper;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Layanan;
use App\Models\ProfilSekolah;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_invoice', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function($subQ) use ($search) {
                      $subQ->where('nama_siswa', 'like', "%{$search}%")
                           ->orWhere('nama_orang_tua', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $month = $request->month;
            $query->where(function ($q) use ($month) {
                $q->whereMonth('tanggal_invoice', $month)
                  ->orWhereMonth('jatuh_tempo', $month);
            });
        }

        if ($request->filled('year')) {
            $year = $request->year;
            $query->where(function ($q) use ($year) {
                $q->whereYear('tanggal_invoice', $year)
                  ->orWhereYear('jatuh_tempo', $year);
            });
        }

        // Sort data terbaru lebih dulu: tanggal invoice terbaru, dibuat terbaru, ID terbesar
        $query->orderByDesc('tanggal_invoice')
              ->orderByDesc('created_at')
              ->orderByDesc('id');

        $invoices = $query->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Export invoice recap to CSV
     */
    public function export(Request $request)
    {
        $query = Invoice::with(['siswa', 'invoiceDetails.layanan']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_invoice', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function($subQ) use ($search) {
                      $subQ->where('nama_siswa', 'like', "%{$search}%")
                           ->orWhere('nama_orang_tua', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $month = $request->month;
            $query->where(function ($q) use ($month) {
                $q->whereMonth('tanggal_invoice', $month)
                  ->orWhereMonth('jatuh_tempo', $month);
            });
        }

        if ($request->filled('year')) {
            $year = $request->year;
            $query->where(function ($q) use ($year) {
                $q->whereYear('tanggal_invoice', $year)
                  ->orWhereYear('jatuh_tempo', $year);
            });
        }

        $query->orderByDesc('tanggal_invoice')
              ->orderByDesc('created_at')
              ->orderByDesc('id');

        $filename = 'rekap_invoice_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($handle, [
                'Tanggal Invoice',
                'No Invoice',
                'Nama Siswa',
                'Item Tagihan',
                'Harga Per Unit',
                'Total Tagihan',
                'Status'
            ]);

            // Chunking to handle large datasets memory-efficiently
            $query->chunk(100, function ($invoices) use ($handle) {
                foreach ($invoices as $invoice) {
                    foreach ($invoice->invoiceDetails as $detail) {
                        $itemName = $detail->layanan->nama_layanan ?? '-';
                        if ($detail->deskripsi_tambahan) {
                            $itemName .= ' (' . $detail->deskripsi_tambahan . ')';
                        }

                        fputcsv($handle, [
                            $invoice->tanggal_invoice->format('Y-m-d'),
                            $invoice->no_invoice,
                            $invoice->siswa->nama_siswa ?? 'Tanpa Nama',
                            $itemName,
                            $detail->harga_satuan,
                            $invoice->grand_total,
                            ucfirst($invoice->status)
                        ]);
                    }
                }
            });

            fclose($handle);
        }, $filename);
    }

    /**
     * Search students for autocomplete
     */
    public function searchStudents(Request $request)
    {
        $query = $request->get('query');
        
        $students = Siswa::where('status', 'aktif')
            ->where(function($q) use ($query) {
                $q->where('nama_siswa', 'like', "%{$query}%")
                  ->orWhere('nama_orang_tua', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get(['id', 'nama_siswa', 'nama_orang_tua']);

        return response()->json($students);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Layanan::firstOrCreate(
            ['nama_layanan' => 'Potongan / Kelebihan Transfer'],
            ['harga_standar' => 0]
        );

        $students = Siswa::where('status', 'aktif')->get();
        $services = Layanan::all();
        $schoolProfile = ProfilSekolah::first();

        return view('invoices.create', compact('students', 'services', 'schoolProfile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('potongan') && $request->input('potongan') !== null) {
            $request->merge(['potongan' => str_replace(['.', ','], ['', '.'], $request->input('potongan'))]);
        }
        if ($request->has('items') && is_array($request->input('items'))) {
            $items = $request->input('items');
            foreach ($items as &$it) {
                if (isset($it['harga_satuan'])) {
                    $it['harga_satuan'] = str_replace(['.', ','], ['', '.'], $it['harga_satuan']);
                }
                if (isset($it['total_biaya'])) {
                    $it['total_biaya'] = str_replace(['.', ','], ['', '.'], $it['total_biaya']);
                }
            }
            $request->merge(['items' => $items]);
        }

        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id',
            'tanggal_invoice' => 'required|date',
            'jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_invoice',
            'potongan' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.id_layanan' => 'required|exists:layanan,id',
            'items.*.kuantitas' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric',
            'items.*.total_biaya' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $schoolProfile = ProfilSekolah::first();
            if (!$schoolProfile) {
                $schoolProfile = ProfilSekolah::create([
                    'nama_sekolah' => 'Sekolah',
                    'alamat' => '-',
                ]);
            }

            $invoiceDate = Carbon::parse($request->tanggal_invoice);

            if ($request->filled('jatuh_tempo')) {
                $dueDate = Carbon::parse($request->jatuh_tempo);
            } else {
                $dueDate = $invoiceDate->copy()->addMonth();
                $dueDate->day = 10;
            }

            $dueMonthName = $this->getIndonesianMonthName($dueDate->month);
            $dueYear = $dueDate->year;

            $itemsData = $request->items;
            $serviceIds = collect($itemsData)->pluck('id_layanan')->all();
            $services = Layanan::whereIn('id', $serviceIds)->get()->keyBy('id');

            $potongan = (float) ($request->input('potongan', 0));

            $invoice = Invoice::create([
                'tanggal_invoice' => $request->tanggal_invoice,
                'jatuh_tempo' => $dueDate->toDateString(),
                'id_siswa' => $request->id_siswa,
                'id_sekolah' => $schoolProfile->id,
                'status' => 'draft',
                'potongan' => $potongan,
                'grand_total' => 0,
            ]);

            $subtotal = 0;

            foreach ($itemsData as $item) {
                $service = $services->get($item['id_layanan']);
                $deskripsiTambahan = $item['deskripsi_tambahan'] ?? null;
                $hargaSatuan = (float) $item['harga_satuan'];

                // Auto-convert to negative if service is a Potongan / Kelebihan Transfer item
                if ($service && (stripos($service->nama_layanan, 'potongan') !== false || stripos($service->nama_layanan, 'kelebihan') !== false)) {
                    if ($hargaSatuan > 0) {
                        $hargaSatuan = -$hargaSatuan;
                    }
                }

                if (isset($item['total_biaya']) && $item['total_biaya'] !== '') {
                    $totalBiaya = (float) $item['total_biaya'];
                    if ($service && (stripos($service->nama_layanan, 'potongan') !== false || stripos($service->nama_layanan, 'kelebihan') !== false)) {
                        if ($totalBiaya > 0) {
                            $totalBiaya = -$totalBiaya;
                        }
                    }
                } else {
                    $totalBiaya = $item['kuantitas'] * $hargaSatuan;
                }
                $subtotal += $totalBiaya;

                // Hanya isi default SPP jika deskripsi tambahan kosong
                if (empty($deskripsiTambahan) && $service && stripos($service->nama_layanan, 'SPP') !== false) {
                    $deskripsiTambahan = 'SPP ('.$dueMonthName.' '.$dueYear.')';
                }

                InvoiceDetail::create([
                    'id_invoice' => $invoice->id,
                    'id_layanan' => $item['id_layanan'],
                    'deskripsi_tambahan' => $deskripsiTambahan,
                    'kuantitas' => $item['kuantitas'],
                    'harga_satuan' => $hargaSatuan,
                    'total_biaya' => $totalBiaya,
                ]);
            }

            $grandTotal = max(0, $subtotal - $potongan);
            $invoice->update(['potongan' => $potongan, 'grand_total' => $grandTotal]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('Error storing invoice: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan invoice: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = TerbilangHelper::rupiah($invoice->grand_total);

        return view('invoices.show', compact('invoice', 'terbilang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        Layanan::firstOrCreate(
            ['nama_layanan' => 'Potongan / Kelebihan Transfer'],
            ['harga_standar' => 0]
        );

        $services = Layanan::all();
        $schoolProfile = ProfilSekolah::first();
        $invoice->load(['invoiceDetails.layanan', 'siswa']);

        return view('invoices.edit', compact('invoice', 'services', 'schoolProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($request->has('potongan') && $request->input('potongan') !== null) {
            $request->merge(['potongan' => str_replace(['.', ','], ['', '.'], $request->input('potongan'))]);
        }
        if ($request->has('items') && is_array($request->input('items'))) {
            $items = $request->input('items');
            foreach ($items as &$it) {
                if (isset($it['harga_satuan'])) {
                    $it['harga_satuan'] = str_replace(['.', ','], ['', '.'], $it['harga_satuan']);
                }
                if (isset($it['total_biaya'])) {
                    $it['total_biaya'] = str_replace(['.', ','], ['', '.'], $it['total_biaya']);
                }
            }
            $request->merge(['items' => $items]);
        }

        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id',
            'tanggal_invoice' => 'required|date',
            'jatuh_tempo' => 'required|date|after_or_equal:tanggal_invoice',
            'status' => 'required|string',
            'potongan' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.id_layanan' => 'required|exists:layanan,id',
            'items.*.kuantitas' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric',
            'items.*.total_biaya' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $potongan = (float) ($request->input('potongan', 0));

            $updateData = [
                'id_siswa' => $request->id_siswa,
                'tanggal_invoice' => $request->tanggal_invoice,
                'jatuh_tempo' => $request->jatuh_tempo,
                'status' => $request->status,
                'potongan' => $potongan,
            ];

            // Cek jika bulan atau tahun berubah, generate nomor baru agar reset sesuai bulan
            $oldDate = $invoice->tanggal_invoice;
            $newDate = Carbon::parse($request->tanggal_invoice);

            if ($oldDate->format('Y-m') !== $newDate->format('Y-m')) {
                $updateData['no_invoice'] = Invoice::generateInvoiceNumber($newDate, $invoice->id);
            }

            $invoice->update($updateData);

            $dueDate = Carbon::parse($request->jatuh_tempo);
            $dueMonthName = $this->getIndonesianMonthName($dueDate->month);
            $dueYear = $dueDate->year;

            $itemsData = $request->items;
            $serviceIds = collect($itemsData)->pluck('id_layanan')->all();
            $services = Layanan::whereIn('id', $serviceIds)->get()->keyBy('id');

            // Hapus detail lama
            $invoice->invoiceDetails()->delete();

            $subtotal = 0;

            foreach ($itemsData as $item) {
                $service = $services->get($item['id_layanan']);
                $deskripsiTambahan = $item['deskripsi_tambahan'] ?? null;
                $hargaSatuan = (float) $item['harga_satuan'];

                // Auto-convert to negative if service is a Potongan / Kelebihan Transfer item
                if ($service && (stripos($service->nama_layanan, 'potongan') !== false || stripos($service->nama_layanan, 'kelebihan') !== false)) {
                    if ($hargaSatuan > 0) {
                        $hargaSatuan = -$hargaSatuan;
                    }
                }

                if (isset($item['total_biaya']) && $item['total_biaya'] !== '') {
                    $totalBiaya = (float) $item['total_biaya'];
                    if ($service && (stripos($service->nama_layanan, 'potongan') !== false || stripos($service->nama_layanan, 'kelebihan') !== false)) {
                        if ($totalBiaya > 0) {
                            $totalBiaya = -$totalBiaya;
                        }
                    }
                } else {
                    $totalBiaya = $item['kuantitas'] * $hargaSatuan;
                }
                $subtotal += $totalBiaya;

                // Hanya isi default SPP jika deskripsi tambahan kosong
                if (empty($deskripsiTambahan) && $service && stripos($service->nama_layanan, 'SPP') !== false) {
                    $deskripsiTambahan = 'SPP ('.$dueMonthName.' '.$dueYear.')';
                }

                InvoiceDetail::create([
                    'id_invoice' => $invoice->id,
                    'id_layanan' => $item['id_layanan'],
                    'deskripsi_tambahan' => $deskripsiTambahan,
                    'kuantitas' => $item['kuantitas'],
                    'harga_satuan' => $hargaSatuan,
                    'total_biaya' => $totalBiaya,
                ]);
            }

            $grandTotal = max(0, $subtotal - $potongan);
            $invoice->update(['potongan' => $potongan, 'grand_total' => $grandTotal]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('Error updating invoice: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui invoice: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        DB::beginTransaction();

        try {
            $noInvoice = $invoice->no_invoice;

            $invoice->invoiceDetails()->delete();
            $invoice->delete();

            // Check if no_invoice matches the format INV/YYYY/MM/XXXX
            if (preg_match('/^INV\/(\d{4})\/(\d{2})\/(\d{4})$/', $noInvoice, $matches)) {
                $year = $matches[1];
                $month = $matches[2];

                // Fetch all remaining invoices for this year and month, ordered by ID
                $remainingInvoices = Invoice::where('no_invoice', 'like', "INV/{$year}/{$month}/%")
                    ->orderBy('id', 'asc')
                    ->get();

                $counter = 1;
                foreach ($remainingInvoices as $remInvoice) {
                    $newNum = str_pad($counter, 4, '0', STR_PAD_LEFT);
                    $newNoInvoice = "INV/{$year}/{$month}/{$newNum}";
                    
                    if ($remInvoice->no_invoice !== $newNoInvoice) {
                        $remInvoice->update(['no_invoice' => $newNoInvoice]);
                    }
                    $counter++;
                }
            }

            DB::commit();

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Invoice berhasil dihapus dan nomor urut invoice untuk periode tersebut telah di-reset secara berurutan.'
                ]);
            }

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice berhasil dihapus dan nomor urut invoice untuk periode tersebut telah di-reset secara berurutan.');

        } catch (\Exception $e) {
            DB::rollback();

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Terjadi kesalahan saat menghapus invoice: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus invoice: ' . $e->getMessage()]);
        }
    }

    /**
     * Preview PDF invoice di browser
     */
    public function previewPdf(Invoice $invoice)
    {
        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = TerbilangHelper::rupiah($invoice->grand_total);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'terbilang'));

        // Set paper size to A4 Landscape
        $pdf->setPaper('a4', 'landscape');

        $filename = $this->buildInvoiceFilename($invoice);

        return $pdf->stream($filename);
    }

    /**
     * Download PDF invoice
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = TerbilangHelper::rupiah($invoice->grand_total);

        $invoice->update([
            'status' => 'Telah dicetak pada ' . now()->format('d/m/Y'),
        ]);

        $filename = $this->buildInvoiceFilename($invoice);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'terbilang'));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * Download Kwitansi PDF
     */
    public function downloadKwitansi(Invoice $invoice)
    {
        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = TerbilangHelper::rupiah($invoice->grand_total);
        $studentName = Str::slug($invoice->siswa->nama_siswa ?? 'TanpaNama', '_');
        $filename = 'Kwitansi-' . $invoice->id . '_' . $studentName . '.pdf';

        $stampColorHex = match ($invoice->status) {
            'paid' => '16a34a',
            'partially_paid' => 'd97706',
            default => '2563eb',
        };

        $stampLogoPath = \App\Helpers\ImageHelper::getTintedLogoPath($invoice->profilSekolah->logo_path ?? null, $stampColorHex);
        $transparentSignaturePath = \App\Helpers\ImageHelper::getTransparentSignaturePath($invoice->profilSekolah->signature_path ?? null);

        $pdf = Pdf::loadView('invoices.kwitansi', compact('invoice', 'terbilang', 'stampLogoPath', 'transparentSignaturePath'));
        $pdf->setPaper('a5', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * Preview Kwitansi PDF in browser/modal
     */
    public function previewKwitansi(Invoice $invoice)
    {
        $invoice->load(['siswa', 'profilSekolah', 'invoiceDetails.layanan']);
        $terbilang = TerbilangHelper::rupiah($invoice->grand_total);
        $studentName = Str::slug($invoice->siswa->nama_siswa ?? 'TanpaNama', '_');
        $filename = 'Kwitansi-' . $invoice->id . '_' . $studentName . '.pdf';

        $stampColorHex = match ($invoice->status) {
            'paid' => '16a34a',
            'partially_paid' => 'd97706',
            default => '2563eb',
        };

        $stampLogoPath = \App\Helpers\ImageHelper::getTintedLogoPath($invoice->profilSekolah->logo_path ?? null, $stampColorHex);
        $transparentSignaturePath = \App\Helpers\ImageHelper::getTransparentSignaturePath($invoice->profilSekolah->signature_path ?? null);

        $pdf = Pdf::loadView('invoices.kwitansi', compact('invoice', 'terbilang', 'stampLogoPath', 'transparentSignaturePath'));
        $pdf->setPaper('a5', 'landscape');

        return $pdf->stream($filename);
    }

    protected function buildInvoiceFilename(Invoice $invoice): string
    {
        $dueDate = $invoice->jatuh_tempo ?? now();
        $monthAbbr = $dueDate->format('M');
        $studentName = Str::slug($invoice->siswa->nama_siswa ?? 'TanpaNama', '_');

        return 'Invoice-'.$monthAbbr.'_'.$studentName.'.pdf';
    }

    protected function getIndonesianMonthName(int $month): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[$month] ?? '';
    }

    /**
     * Update payment status quickly via AJAX
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 422);
        }

        try {
            $invoice->update(['status' => $request->status]);
            return response()->json(['success' => true, 'message' => 'Status berhasil diubah.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status.'], 500);
        }
    }

    /**
     * Handle partial or full payments of an invoice.
     */
    public function payPartial(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'checked_items' => 'required|array|min:1',
            'checked_items.*' => 'required|exists:invoice_details,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Item pembayaran tidak valid.'], 422);
        }

        $checkedItems = $request->checked_items;
        $allDetails = $invoice->invoiceDetails;
        $totalDetailsCount = $allDetails->count();
        $checkedDetailsCount = count($checkedItems);

        DB::beginTransaction();

        try {
            foreach ($allDetails as $detail) {
                $isPaid = in_array($detail->id, $checkedItems);
                $detail->update(['is_paid' => $isPaid]);
            }

            $paidCount = $invoice->invoiceDetails()->where('is_paid', true)->count();

            if ($paidCount === $totalDetailsCount) {
                $invoice->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                ]);
            } else {
                $invoice->update([
                    'status' => 'partially_paid',
                    'payment_date' => now(),
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil disimpan.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the automation control index page.
     */
    public function automationIndex()
    {
        $students = Siswa::where('status', 'aktif')->orderBy('nama_siswa', 'asc')->get();
        $services = Layanan::orderBy('nama_layanan', 'asc')->get();
        $billingSetting = \App\Models\BillingSetting::firstOrCreate(['id' => 1], [
            'invoice_generation_day' => 25,
            'invoice_due_day' => 10,
        ]);
        
        $mappings = \App\Models\StudentService::with(['siswa', 'layanan'])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('invoices.automation', compact('students', 'services', 'mappings', 'billingSetting'));
    }

    /**
     * Trigger the monthly invoice generation console command programmatically.
     */
    public function automationRun(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
        ]);

        try {
            $billingSetting = \App\Models\BillingSetting::first();
            $genDay = $billingSetting->invoice_generation_day ?? 25;
            $parsedDate = Carbon::parse($request->month . '-' . $genDay);
            
            // Programmatically call Artisan command
            \Illuminate\Support\Facades\Artisan::call('invoice:generate-monthly', [
                'date' => $parsedDate->toDateString()
            ]);

            $output = \Illuminate\Support\Facades\Artisan::output();

            return redirect()->back()->with('success', 'Auto-Generate berhasil dijalankan: ' . trim($output));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menjalankan auto-generate: ' . $e->getMessage());
        }
    }

    /**
     * Store a custom nominal mapping for a student (supporting any service component, and both add and edit).
     */
    public function storeMapping(Request $request)
    {
        $request->validate([
            'mapping_id' => 'nullable|exists:student_services,id',
            'id_siswa' => 'required|exists:siswa,id',
            'id_layanan' => 'required|exists:layanan,id',
            'nominal' => 'required|numeric|min:0',
            'month' => 'nullable|string',
        ]);

        $bulan = null;
        $tahun = null;

        if ($request->filled('month')) {
            $date = Carbon::parse($request->month . '-01');
            $bulan = $date->month;
            $tahun = $date->year;
        }

        try {
            if ($request->filled('mapping_id')) {
                $mapping = \App\Models\StudentService::find($request->mapping_id);
                $mapping->update([
                    'id_siswa' => $request->id_siswa,
                    'id_layanan' => $request->id_layanan,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nominal' => $request->nominal,
                ]);
            } else {
                \App\Models\StudentService::updateOrCreate([
                    'id_siswa' => $request->id_siswa,
                    'id_layanan' => $request->id_layanan,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ], [
                    'nominal' => $request->nominal,
                ]);
            }

            return redirect()->back()->with('success', 'Mapping nominal khusus berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan mapping: ' . $e->getMessage());
        }
    }

    /**
     * Delete a custom student service nominal mapping.
     */
    public function deleteMapping(\App\Models\StudentService $studentService)
    {
        try {
            $studentService->delete();
            return redirect()->back()->with('success', 'Mapping nominal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus mapping: ' . $e->getMessage());
        }
    }

    /**
     * Save dynamic billing schedule and due day parameters.
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'invoice_generation_day' => 'required|integer|min:1|max:28',
            'invoice_due_day' => 'required|integer|min:1|max:28',
        ]);

        try {
            $setting = \App\Models\BillingSetting::firstOrCreate(['id' => 1], [
                'invoice_generation_day' => 25,
                'invoice_due_day' => 10,
            ]);

            $setting->update([
                'invoice_generation_day' => $request->invoice_generation_day,
                'invoice_due_day' => $request->invoice_due_day,
            ]);

            return redirect()->back()->with('success', 'Pengaturan tanggal tagihan bulanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan pengaturan tanggal: ' . $e->getMessage());
        }
    }

    /**
     * Display listing of Kwitansi Pembayaran
     */
    public function kwitansiIndex(Request $request)
    {
        $query = Invoice::with(['siswa', 'invoiceDetails.layanan', 'profilSekolah']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            if (!$request->has('all')) {
                $query->whereIn('status', ['paid', 'partially_paid']);
            }
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('no_invoice', 'like', $search)
                  ->orWhereHas('siswa', function ($sq) use ($search) {
                      $sq->where('nama_siswa', 'like', $search)
                        ->orWhere('nama_orang_tua', 'like', $search);
                  });
            });
        }

        if ($request->filled('tanggal_start')) {
            $query->whereDate('tanggal_invoice', '>=', $request->tanggal_start);
        }
        if ($request->filled('tanggal_end')) {
            $query->whereDate('tanggal_invoice', '<=', $request->tanggal_end);
        }

        $invoices = $query->latest()->paginate(15)->withQueryString();

        return view('invoices.kwitansi_index', compact('invoices'));
    }

    /**
     * Form to create a new Kwitansi
     */
    public function kwitansiCreate()
    {
        $students = Siswa::where('status', 'aktif')->orderBy('nama_siswa', 'asc')->get();
        $services = Layanan::orderBy('nama_layanan', 'asc')->get();
        $schoolProfile = ProfilSekolah::first();
        $nextNoInvoice = Invoice::generateInvoiceNumber(now());

        return view('invoices.kwitansi_create', compact('students', 'services', 'schoolProfile', 'nextNoInvoice'));
    }

    /**
     * Store a newly created Kwitansi
     */
    public function kwitansiStore(Request $request)
    {
        // Sanitize numeric amount
        if ($request->has('jumlah_uang')) {
            $cleanAmount = preg_replace('/[^0-9.]/', '', (string)$request->jumlah_uang);
            $request->merge(['jumlah_uang' => $cleanAmount]);
        }

        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswa,id',
            'tanggal_invoice' => 'required|date',
            'diterima_dari' => 'nullable|string|max:255',
            'untuk_pembayaran' => 'required|string|max:500',
            'jumlah_uang' => 'required|numeric|min:1',
            'status' => 'required|in:paid,partially_paid',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $schoolProfile = ProfilSekolah::first() ?? ProfilSekolah::create(['nama_sekolah' => 'Sekolah', 'alamat' => '-']);

            $invoiceDate = Carbon::parse($request->tanggal_invoice);
            $dueDate = $invoiceDate->copy()->addMonth();

            $service = Layanan::firstOrCreate(
                ['nama_layanan' => 'Kwitansi Pembayaran'],
                ['harga_standar' => $request->jumlah_uang]
            );

            $invoice = Invoice::create([
                'tanggal_invoice' => $invoiceDate->toDateString(),
                'jatuh_tempo' => $dueDate->toDateString(),
                'id_siswa' => $request->id_siswa,
                'id_sekolah' => $schoolProfile->id,
                'status' => $request->status,
                'potongan' => 0,
                'grand_total' => $request->jumlah_uang,
                'payment_date' => $invoiceDate->toDateString(),
                'payment_notes' => $request->diterima_dari ? 'Diterima Dari: ' . $request->diterima_dari : null,
            ]);

            InvoiceDetail::create([
                'id_invoice' => $invoice->id,
                'id_layanan' => $service->id,
                'deskripsi_tambahan' => $request->untuk_pembayaran,
                'kuantitas' => 1,
                'harga_satuan' => $request->jumlah_uang,
                'total_biaya' => $request->jumlah_uang,
                'is_paid' => ($request->status === 'paid'),
            ]);

            DB::commit();

            return redirect()->route('invoices.kwitansi-index')
                ->with('success', "Kwitansi Pembayaran No. KWT-{$invoice->no_invoice} berhasil diterbitkan!");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing kwitansi: ' . $e->getMessage(), ['exception' => $e]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menerbitkan kwitansi: ' . $e->getMessage()])->withInput();
        }
    }
}
