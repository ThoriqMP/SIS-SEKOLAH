@extends('layouts.app')

@section('title', 'Invoice #' . $invoice->no_invoice)

@section('content')
<div class="py-12" x-data="{ 
    showPaymentModal: false, 
    paymentInvoice: {{ json_encode(['id' => $invoice->id, 'no_invoice' => $invoice->no_invoice, 'nama_siswa' => $invoice->siswa->nama_siswa]) }},
    paymentItems: {{ json_encode($invoice->invoiceDetails->map(function($d) {
        return [
            'id' => $d->id,
            'nama_layanan' => $d->layanan->nama_layanan,
            'deskripsi' => $d->deskripsi_tambahan ?? '-',
            'total' => $d->total_biaya,
        ];
    })) }},
    checkedItems: [],
    updating: false,
    init() {
        this.checkedItems = this.paymentItems.map(item => item.id);
    },
    submitPayment() {
        this.updating = true;
        fetch(`/invoices/${this.paymentInvoice.id}/pay-partial`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ checked_items: this.checkedItems })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Gagal memproses pembayaran.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan.');
        })
        .finally(() => {
            this.updating = false;
            this.showPaymentModal = false;
        });
    }
}">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xs rounded-2xl border border-slate-200/80 dark:border-gray-700">
            <div class="p-4 sm:p-7 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 mb-6 border-b border-slate-100 dark:border-gray-700/60">
                    <!-- Left: Breadcrumb / Back Link + Title + Status Pill -->
                    <div class="min-w-0">
                        <a href="{{ route('invoices.index') }}" 
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition mb-1.5 group">
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar Invoice
                        </a>
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-gray-100 tracking-tight">
                                Invoice #{{ $invoice->no_invoice }}
                            </h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold tracking-wide
                                @if($invoice->status == 'paid') bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/40
                                @elseif($invoice->status == 'partially_paid') bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/40
                                @elseif($invoice->status == 'sent') bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40
                                @elseif($invoice->status == 'overdue') bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800/40
                                @else bg-slate-100 text-slate-700 border border-slate-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600
                                @endif">
                                {{ $invoice->status == 'partially_paid' ? 'Dibayar Sebagian' : ($invoice->status == 'paid' ? 'Lunas' : ($invoice->status == 'sent' ? 'Terkirim' : ($invoice->status == 'overdue' ? 'Jatuh Tempo' : 'Draft'))) }}
                            </span>
                        </div>
                    </div>

                    <!-- Right: Seamless Action Toolbar -->
                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        <!-- Segmented Document Actions -->
                        <div class="inline-flex rounded-xl border border-slate-200 dark:border-gray-700 bg-slate-50/70 dark:bg-gray-800/80 p-1 shadow-2xs">
                            <a href="{{ route('invoices.preview', $invoice) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-gray-200 hover:text-purple-700 dark:hover:text-white hover:bg-white dark:hover:bg-gray-700 rounded-lg transition"
                               title="Buka Pratinjau PDF di Tab Baru">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Preview</span>
                            </a>
                            <div class="w-px h-4 bg-slate-200 dark:bg-gray-700 self-center mx-0.5"></div>
                            <a href="{{ route('invoices.pdf', $invoice) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-gray-200 hover:text-purple-700 dark:hover:text-white hover:bg-white dark:hover:bg-gray-700 rounded-lg transition"
                               title="Unduh Berkas PDF Invoice">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>PDF</span>
                            </a>
                            <div class="w-px h-4 bg-slate-200 dark:bg-gray-700 self-center mx-0.5"></div>
                            <a href="{{ route('invoices.kwitansi', $invoice) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-gray-200 hover:text-purple-700 dark:hover:text-white hover:bg-white dark:hover:bg-gray-700 rounded-lg transition" 
                               title="Unduh Bukti Kwitansi Pembayaran">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Kwitansi</span>
                            </a>
                        </div>

                        <!-- Edit Button -->
                        <a href="{{ route('invoices.edit', $invoice) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 hover:bg-slate-50 dark:hover:bg-gray-700 rounded-xl shadow-2xs transition"
                           title="Edit Data Invoice">
                            <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span>Edit</span>
                        </a>

                        <!-- Primary Action: Mark as Paid -->
                        @if($invoice->status !== 'paid')
                            <button type="button" 
                                    @click="showPaymentModal = true"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 rounded-xl shadow-xs shadow-emerald-600/25 transition cursor-pointer">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Tandai Lunas</span>
                            </button>
                        @endif

                        <!-- Delete Action -->
                        <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice ini? Semua nomor urut untuk bulan ini akan di-reset berurutan secara otomatis.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center justify-center w-8.5 h-8.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl border border-slate-200 dark:border-gray-700 hover:border-rose-200 dark:hover:border-rose-900 transition cursor-pointer"
                                    title="Hapus Invoice">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 px-4 py-3 bg-emerald-50/80 dark:bg-emerald-950/30 border border-emerald-200/80 dark:border-emerald-800/40 rounded-xl flex items-center gap-2.5 text-xs sm:text-sm font-medium text-emerald-800 dark:text-emerald-300">
                        <svg class="h-4.5 w-4.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Header Invoice -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Info Sekolah -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ $invoice->profilSekolah->nama_sekolah }}</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <p>{{ $invoice->profilSekolah->alamat }}</p>
                            @if($invoice->profilSekolah->bank_nama)
                                <p class="mt-2">
                                    <strong>Bank:</strong> {{ $invoice->profilSekolah->bank_nama }}<br>
                                    <strong>No. Rek:</strong> {{ $invoice->profilSekolah->no_rekening }}<br>
                                    <strong>Atas Nama:</strong> {{ $invoice->profilSekolah->atas_nama }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Info Invoice -->
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <div>
                                <strong>No. Invoice:</strong> {{ $invoice->no_invoice }}
                            </div>
                            <div>
                                <strong>Tanggal:</strong> {{ $invoice->tanggal_invoice->format('d/m/Y') }}
                            </div>
                            <div>
                                <strong>Jatuh Tempo:</strong> {{ $invoice->jatuh_tempo->format('d/m/Y') }}
                            </div>
                            <div>
                                <strong>Pembuatan:</strong> 
                                @if($invoice->is_auto_generated)
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300">
                                        ⚡ Otomatis (Sistem)
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                        ✏️ Manual
                                    </span>
                                @endif
                            </div>
                            <div>
                                <strong>Status:</strong> 
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($invoice->status == 'paid') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                    @elseif($invoice->status == 'partially_paid') bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300
                                    @elseif($invoice->status == 'sent') bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300
                                    @elseif($invoice->status == 'overdue') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                    @endif">
                                    {{ $invoice->status == 'partially_paid' ? 'Dibayar Setengah' : ucfirst($invoice->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Siswa -->
                <div class="mb-8">
                    <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200 mb-2">Kepada:</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p><strong>Siswa:</strong> {{ $invoice->siswa->nama_siswa }}</p>
                        <p><strong>Orang Tua:</strong> {{ $invoice->siswa->nama_orang_tua }}</p>
                        <p><strong>Alamat:</strong> {{ $invoice->siswa->alamat_tagihan }}</p>
                    </div>
                </div>

                <!-- Tabel Item -->
                <div class="mb-8">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Layanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kuantitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Satuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($invoice->invoiceDetails as $index => $detail)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $detail->layanan->nama_layanan }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $detail->deskripsi_tambahan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $detail->kuantitas }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @if($detail->harga_satuan < 0)
                                            <span class="text-amber-600 dark:text-amber-400 font-semibold">- Rp {{ number_format(abs($detail->harga_satuan), 0, ',', '.') }}</span>
                                        @else
                                            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        @if($detail->total_biaya < 0)
                                            <span class="text-amber-600 dark:text-amber-400 font-semibold">- Rp {{ number_format(abs($detail->total_biaya), 0, ',', '.') }}</span>
                                        @else
                                            Rp {{ number_format($detail->total_biaya, 0, ',', '.') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-100">Grand Total:</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Terbilang -->
                <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Terbilang:</strong> {{ $terbilang }}
                    </p>
                </div>

                <!-- Footer -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <strong>Catatan:</strong><br>
                            Pembayaran dapat dilakukan melalui transfer bank ke rekening yang tercantum di atas.
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="mb-8">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $invoice->profilSekolah->nama_sekolah }}
                            </p>
                            @if($invoice->profilSekolah->pimpinan_nama)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-12">
                                    <strong>{{ $invoice->profilSekolah->pimpinan_nama }}</strong><br>
                                    Pimpinan
                                </p>
                                @if($invoice->profilSekolah->signature_path)
                                    <div class="mt-4">
                                        <img src="{{ Storage::url($invoice->profilSekolah->signature_path) }}" alt="Tanda Tangan" class="h-16 object-contain inline-block">
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Partial Payment Pop-up Modal -->
    <div x-show="showPaymentModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <!-- Backdrop with modern blur -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showPaymentModal = false"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-[28px] border border-gray-100 dark:border-gray-700 shadow-2xl w-full max-w-lg overflow-hidden flex flex-col transform transition-all duration-300">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wider">
                            Rincian Pembayaran
                        </h3>
                        <p class="text-xs font-bold text-gray-400 mt-1" x-text="'Invoice: ' + paymentInvoice?.no_invoice"></p>
                    </div>
                    <button @click="showPaymentModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6 overflow-y-auto space-y-4 max-h-[50vh]">
                    <p class="text-sm font-bold text-gray-600 dark:text-gray-300" x-text="'Siswa: ' + paymentInvoice?.nama_siswa"></p>
                    <div class="space-y-3">
                        <template x-for="item in paymentItems" :key="item.id">
                            <label class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-800 hover:border-blue-400 dark:hover:border-blue-400/50 cursor-pointer transition-all">
                                <input type="checkbox" :value="item.id" x-model="checkedItems" 
                                       class="mt-1 rounded text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 w-5 h-5 transition">
                                <div class="flex-1">
                                    <div class="font-black text-sm text-gray-900 dark:text-white" x-text="item.nama_layanan"></div>
                                    <div class="text-xs font-bold text-gray-400 mt-0.5" x-text="item.deskripsi"></div>
                                    <div class="text-xs font-black text-blue-600 dark:text-blue-400 mt-2" x-text="'Rp ' + (parseFloat(item.total) || 0).toLocaleString('id-ID')"></div>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-end gap-3">
                    <button type="button" @click="showPaymentModal = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-black uppercase tracking-wider transition">
                        Batal
                    </button>
                    <button type="button" @click="submitPayment" :disabled="updating || checkedItems.length === 0" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-lg shadow-blue-600/20 flex items-center gap-2">
                        <span x-show="updating" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                        Simpan Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
