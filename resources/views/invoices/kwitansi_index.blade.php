@extends('layouts.app')

@section('title', 'Kwitansi Pembayaran')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/80">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-purple-600"></i>
                    <span>Kwitansi Pembayaran</span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola kwitansi resmi, cetak tanda terima lunas, dan terbitkan kwitansi pembayaran siswa.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('invoices.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-xl transition">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Invoice Siswa</span>
                </a>
                <a href="{{ route('invoices.kwitansi-create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-purple-600/25 transition active:scale-95">
                    <i class="fa-solid fa-plus"></i>
                    <span>Terbitkan Kwitansi Baru</span>
                </a>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/80">
            <form action="{{ route('invoices.kwitansi-index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Cari Kwitansi / Siswa / Ortu</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nomor invoice, nama siswa, nama orang tua..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Status Pembayaran</label>
                    <select name="status" class="w-full py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Semua Terbayar (Lunas & Setengah)</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        <option value="partially_paid" {{ request('status') === 'partially_paid' ? 'selected' : '' }}>Dibayar Setengah</option>
                    </select>
                </div>

                <!-- Date Start -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_start" value="{{ request('tanggal_start') }}" 
                           class="w-full py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5 shadow-md shadow-purple-600/20">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('invoices.kwitansi-index') }}" class="py-2 px-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-xs rounded-xl transition flex items-center justify-center" title="Reset">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase font-bold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">No. Kwitansi</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Diterima Dari</th>
                            <th class="px-6 py-4">Untuk Pembayaran</th>
                            <th class="px-6 py-4">Jumlah Uang</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi Kwitansi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($invoices as $inv)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 font-mono font-bold text-purple-600 dark:text-purple-400">
                                    KWT-{{ $inv->no_invoice }}
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ $inv->tanggal_invoice ? $inv->tanggal_invoice->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white">
                                        {{ $inv->siswa->nama_orang_tua ? $inv->siswa->nama_orang_tua : $inv->siswa->nama_siswa }}
                                    </div>
                                    <div class="text-xs text-gray-400">Siswa: {{ $inv->siswa->nama_siswa ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <div class="truncate font-medium text-gray-800 dark:text-gray-200">
                                        @php
                                            $firstDetail = $inv->invoiceDetails->first();
                                        @endphp
                                        {{ $firstDetail ? ($firstDetail->deskripsi_tambahan ?? $firstDetail->layanan->nama_layanan ?? 'Pembayaran SPP / Sekolah') : 'Pembayaran Sekolah' }}
                                    </div>
                                    @if($inv->invoiceDetails->count() > 1)
                                        <div class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold mt-0.5">+ {{ $inv->invoiceDetails->count() - 1 }} item lainnya</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($inv->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($inv->status === 'paid')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> LUNAS
                                        </span>
                                    @elseif($inv->status === 'partially_paid')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            <i class="fa-solid fa-clock text-[10px]"></i> SETENGAH
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            {{ strtoupper($inv->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('invoices.kwitansi-preview', $inv) }}" target="_blank" 
                                           class="p-2 rounded-xl bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white dark:bg-purple-950/50 dark:text-purple-300 dark:hover:bg-purple-600 dark:hover:text-white transition"
                                           title="Preview Kwitansi">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('invoices.kwitansi', $inv) }}" 
                                           class="p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white dark:bg-emerald-950/50 dark:text-emerald-300 dark:hover:bg-emerald-600 dark:hover:text-white transition"
                                           title="Download PDF Kwitansi">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                        <a href="{{ route('invoices.show', $inv) }}" 
                                           class="p-2 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-700 hover:text-white dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition"
                                           title="Detail Invoice">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fa-solid fa-receipt text-4xl mb-3 block text-gray-300 dark:text-gray-600"></i>
                                    <div>Belum ada kwitansi pembayaran yang diterbitkan.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($invoices->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
