@extends('layouts.app')

@section('title', 'Daftar Invoice')

@section('content')
<div class="py-12" x-data="{ 
    showPreviewModal: false, 
    previewUrl: '', 
    viewLayout: localStorage.getItem('invoiceViewLayout') || 'grid',
    showPaymentModal: false,
    paymentInvoice: null,
    paymentItems: [],
    checkedItems: [],
    updating: false,

    // Dynamic Delete Modal State
    showDeleteModal: false,
    deleteInvoiceId: null,
    deleteInvoiceNo: '',
    deleteSiswaName: '',
    deleteCardRef: null,

    triggerDelete(invoice, cardContext) {
        this.deleteInvoiceId = invoice.id;
        this.deleteInvoiceNo = invoice.no_invoice;
        this.deleteSiswaName = invoice.nama_siswa;
        this.deleteCardRef = cardContext;
        this.showDeleteModal = true;
    },

    confirmDelete() {
        this.updating = true;
        this.showDeleteModal = false;
        
        fetch(`/invoices/${this.deleteInvoiceId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.deleteCardRef.deleted = true;
            } else {
                alert(data.message || 'Gagal menghapus invoice.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan.');
        })
        .finally(() => {
            this.updating = false;
        });
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
}" x-init="$watch('viewLayout', val => localStorage.setItem('invoiceViewLayout', val))">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col gap-4 mb-6">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Daftar Invoice</h2>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('invoices.automation') }}" 
                               class="border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg transition flex items-center justify-center whitespace-nowrap font-medium text-sm">
                                <svg class="h-5 w-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                Otomatisasi & Mapping SPP
                            </a>
                            <a href="{{ route('invoices.create') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center whitespace-nowrap font-medium text-sm">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Buat Invoice Baru
                            </a>
                        </div>
                    </div>

                    <!-- Filter & Search Section -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                        <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                            <!-- Search -->
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-lg text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder:text-gray-400" 
                                       placeholder="Cari invoice (No, Siswa, Ortu)...">
                            </div>

                            <!-- Month Filter -->
                            <div class="w-full lg:w-44">
                                <select name="month" onchange="this.form.submit()" class="w-full py-3 pl-3 pr-8 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-lg text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Semua Bulan</option>
                                    @php
                                        $bulanIndo = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                    @endphp
                                    @foreach($bulanIndo as $num => $name)
                                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Year Filter -->
                            <div class="w-full lg:w-36">
                                <select name="year" onchange="this.form.submit()" class="w-full py-3 pl-3 pr-8 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-lg text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Semua Tahun</option>
                                    @php
                                        $currentYear = (int) date('Y');
                                        $years = range($currentYear + 1, $currentYear - 3);
                                    @endphp
                                    @foreach($years as $yr)
                                        <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                                            {{ $yr }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="w-full lg:w-44">
                                <select name="status" onchange="this.form.submit()" class="w-full py-3 pl-3 pr-8 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-lg text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                                    <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Dibayar Setengah</option>
                                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Jatuh Tempo</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('invoices.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg text-sm hover:bg-gray-300 dark:hover:bg-gray-500 transition flex items-center justify-center" title="Reset Filter">
                                   Reset
                                </a>
                                
                                <button type="button" 
                                        @click="
                                            const f = $el.closest('form'); 
                                            const old = f.action; 
                                            f.action = '{{ route('invoices.export') }}'; 
                                            f.submit(); 
                                            f.action = old;
                                        "
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition flex items-center justify-center whitespace-nowrap">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Export CSV
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Layout Toggle -->
                <div class="flex justify-end mb-4">
                    <div class="bg-gray-100 dark:bg-gray-700 p-1 rounded-lg flex gap-1">
                        <button @click="viewLayout = 'grid'" :class="viewLayout === 'grid' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="p-2 rounded-md transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </button>
                        <button @click="viewLayout = 'list'" :class="viewLayout === 'list' ? 'bg-white dark:bg-gray-600 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="p-2 rounded-md transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Cards Container -->
                <div :class="viewLayout === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6' : 'flex flex-col gap-4'">
                    @forelse($invoices as $invoice)
                        <div x-data="{ open: false, status: '{{ $invoice->status }}', updating: false, deleted: false }" 
                             x-show="!deleted"
                             x-transition:leave="transition ease-in duration-300 transform scale-95 opacity-0"
                             :class="[
                                viewLayout === 'grid' ? 'flex flex-col' : 'flex flex-col md:flex-row md:items-center',
                                open ? 'z-50' : 'z-0'
                             ]" 
                             class="bg-white dark:bg-gray-800 rounded-[24px] border border-gray-100 dark:border-gray-700 p-5 sm:p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group">
                            <!-- Status Indicator -->
                            <div class="absolute inset-0 overflow-hidden rounded-[24px] pointer-events-none">
                                <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 opacity-10 group-hover:opacity-20 transition-opacity rotate-45"
                                    :class="{
                                        'bg-green-500': '{{ $invoice->status }}' == 'paid',
                                        'bg-blue-500': '{{ $invoice->status }}' == 'sent',
                                        'bg-red-500': '{{ $invoice->status }}' == 'overdue' || '{{ $invoice->status }}' == 'unpaid',
                                        'bg-gray-500': '{{ $invoice->status }}' == 'draft'
                                    }">
                                </div>
                            </div>

                            <div class="relative z-10 flex flex-1 h-full gap-4 md:gap-5" :class="viewLayout === 'grid' ? 'flex-col' : 'flex-col lg:flex-row lg:items-center'">
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <!-- Top Meta: Invoice #, Badges & Status Dropdown -->
                                    <div class="flex items-center justify-between gap-2 flex-wrap mb-2">
                                        <div class="flex items-center gap-2 flex-wrap min-w-0">
                                            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] truncate">
                                                {{ $invoice->no_invoice }}
                                            </span>
                                            @if($invoice->is_auto_generated)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200/40 dark:border-purple-800/40 shadow-sm" title="Invoice ini dibuat secara otomatis oleh sistem">
                                                    <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                    Otomatis
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-400 border border-gray-200/40 dark:border-gray-600/40">
                                                    Manual
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Status Dropdown -->
                                        <div class="relative flex-shrink-0">
                                            <button @click="open = !open" class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border flex items-center justify-between gap-1.5 transition-all w-auto min-w-[110px] max-w-[160px]"
                                                :class="{
                                                    'bg-green-50 text-green-700 border-green-200 dark:bg-green-950/40 dark:text-green-300 dark:border-green-800/40': status === 'paid',
                                                    'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/40': status === 'partially_paid',
                                                    'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800/40': status === 'sent',
                                                    'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/40 dark:text-red-300 dark:border-red-800/40': status === 'overdue' || status === 'unpaid',
                                                    'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700': !['paid','partially_paid','sent','overdue','unpaid'].includes(status),
                                                    'opacity-50 cursor-not-allowed': updating
                                                }" :disabled="updating" :title="status">
                                                <span class="truncate text-left flex-1" x-text="status === 'partially_paid' ? 'dibayar 1/2' : status"></span>
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false" style="display: none;" class="absolute z-50 right-0 mt-2 w-36 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden py-1">
                                                <template x-for="s in ['draft', 'sent', 'paid', 'partially_paid', 'unpaid', 'overdue']">
                                                    <button @click="
                                                        if (s === 'paid' || s === 'partially_paid') {
                                                            paymentInvoice = {
                                                                id: {{ $invoice->id }},
                                                                no_invoice: '{{ $invoice->no_invoice }}',
                                                                nama_siswa: '{{ addslashes($invoice->siswa->nama_siswa ?? 'Tanpa Nama') }}'
                                                            };
                                                            paymentItems = {{ json_encode($invoice->invoiceDetails->map(function($d) {
                                                                return [
                                                                    'id' => $d->id,
                                                                    'nama_layanan' => $d->layanan->nama_layanan,
                                                                    'deskripsi' => $d->deskripsi_tambahan ?? '-',
                                                                    'total' => $d->total_biaya,
                                                                ];
                                                            })) }};
                                                            checkedItems = paymentItems.map(item => item.id);
                                                            showPaymentModal = true;
                                                            open = false;
                                                        } else {
                                                            updating = true; open = false;
                                                            fetch('{{ route('invoices.update-status', $invoice) }}', {
                                                                method: 'PATCH',
                                                                headers: {
                                                                    'Content-Type': 'application/json',
                                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                    'Accept': 'application/json'
                                                                },
                                                                body: JSON.stringify({ status: s })
                                                            }).then(res => res.json()).then(data => {
                                                                if(data.success) { status = s; }
                                                                updating = false;
                                                            }).catch(() => { updating = false; });
                                                        }
                                                    " class="block w-full text-left px-4 py-2 text-xs uppercase font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition" x-text="s"></button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Student Name & Parent Details: 100% Full Width of the Card -->
                                    <div class="min-w-0" :class="viewLayout === 'grid' ? 'mb-4' : 'mb-2 lg:mb-0'">
                                        <h3 class="text-base sm:text-lg font-black text-gray-900 dark:text-white leading-snug break-words mb-1" title="{{ $invoice->siswa->nama_siswa }}">
                                            {{ $invoice->siswa->nama_siswa }}
                                        </h3>
                                        <div class="flex items-center gap-1.5 text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">
                                            <svg class="w-4 h-4 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span class="truncate" title="{{ $invoice->siswa->nama_orang_tua }}">{{ $invoice->siswa->nama_orang_tua }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 sm:gap-4 flex-shrink-0" :class="viewLayout === 'grid' ? 'mb-6' : 'lg:w-64 mb-0'">
                                    <div class="p-3.5 sm:p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800 flex flex-col justify-center">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal</p>
                                        <p class="text-xs sm:text-sm font-black text-gray-900 dark:text-white truncate">{{ $invoice->tanggal_invoice->format('d M Y') }}</p>
                                    </div>
                                    <div class="p-3.5 sm:p-4 bg-blue-50/50 dark:bg-blue-900/20 rounded-2xl border border-blue-100/50 dark:border-blue-800/50 flex flex-col justify-center">
                                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Tagihan</p>
                                        <p class="text-xs sm:text-sm font-black text-blue-600 dark:text-blue-400 truncate">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="mt-auto flex items-center justify-between gap-2 flex-wrap pt-4 sm:pt-5 border-t border-gray-100 dark:border-gray-700"
                                     :class="viewLayout === 'list' ? 'lg:pt-0 lg:border-t-0 lg:ml-4' : ''">
                                    <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                        <a href="{{ route('invoices.show', $invoice) }}" 
                                           class="p-2.5 sm:p-3 bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-blue-600 hover:text-white rounded-xl transition-all active:scale-90" title="Detail">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <button type="button" 
                                           @click="showPreviewModal = true; previewUrl = '{{ route('invoices.preview', $invoice) }}'"
                                           class="p-2.5 sm:p-3 bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-indigo-600 hover:text-white rounded-xl transition-all active:scale-90" title="Preview PDF">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </button>
                                        <a href="{{ route('invoices.pdf', $invoice) }}" 
                                           class="p-2.5 sm:p-3 bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-green-600 hover:text-white rounded-xl transition-all active:scale-90" title="Download PDF Invoice">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        </a>
                                        <a href="{{ route('invoices.kwitansi', $invoice) }}" 
                                           class="p-2.5 sm:p-3 bg-gray-100 dark:bg-gray-700 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white dark:hover:text-white rounded-xl transition-all active:scale-90" title="Download Kwitansi">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                        <button type="button" 
                                                @click="triggerDelete({
                                                    id: {{ $invoice->id }},
                                                    no_invoice: '{{ $invoice->no_invoice }}',
                                                    nama_siswa: '{{ addslashes($invoice->siswa->nama_siswa ?? 'Tanpa Nama') }}'
                                                }, $data)"
                                                class="p-2.5 sm:p-3 bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-red-600 hover:text-white rounded-xl transition-all active:scale-90" 
                                                :disabled="updating"
                                                title="Hapus Invoice">
                                            <svg x-show="!updating" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <svg x-show="updating" style="display: none;" class="animate-spin h-4 w-4 sm:h-5 sm:w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <a href="{{ route('invoices.edit', $invoice) }}" 
                                       class="flex items-center gap-1.5 sm:gap-2 px-4 py-2.5 sm:px-6 sm:py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 dark:hover:bg-blue-600 hover:text-white dark:hover:text-white transition-all active:scale-95 shadow-md shadow-black/10">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 00 2-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12 px-4 text-center">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-full p-4 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum ada invoice</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                                Buat invoice baru untuk menagih pembayaran kepada siswa.
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $invoices->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="showPreviewModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showPreviewModal = false"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-6xl h-[90vh] flex flex-col">
                <!-- Header -->
                <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Preview Invoice</h3>
                    <button @click="showPreviewModal = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Body (Iframe) -->
                <div class="flex-1 bg-gray-100 dark:bg-gray-900 p-0 overflow-hidden relative">
                    <div x-show="!previewUrl" class="absolute inset-0 flex items-center justify-center">
                        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <iframe :src="previewUrl" class="w-full h-full border-0" x-show="previewUrl"></iframe>
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

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <!-- Backdrop with modern blur -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showDeleteModal = false"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-[28px] border border-gray-100 dark:border-gray-700 shadow-2xl w-full max-w-md overflow-hidden flex flex-col transform transition-all duration-300">
                <!-- Body -->
                <div class="p-6 flex flex-col items-center text-center space-y-4">
                    <!-- Warning Icon -->
                    <div class="h-16 w-16 bg-red-50 dark:bg-red-950/30 rounded-full flex items-center justify-center text-red-600 dark:text-red-400">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wider">
                            Konfirmasi Hapus
                        </h3>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                            Apakah Anda yakin ingin menghapus invoice <span class="text-red-500 font-extrabold" x-text="deleteInvoiceNo"></span> untuk siswa <span class="text-gray-900 dark:text-white font-extrabold" x-text="deleteSiswaName"></span>?
                        </p>
                        <p class="text-[10px] font-black text-amber-500 dark:text-amber-400 uppercase tracking-widest mt-3">
                            ⚠️ Semua nomor urut untuk periode ini akan di-reset secara berurutan.
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-end gap-3">
                    <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-black uppercase tracking-wider transition">
                        Batal
                    </button>
                    <button type="button" @click="confirmDelete" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-lg shadow-red-600/20 flex items-center gap-2">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
