@extends('mbi.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex text-xs text-gray-500 dark:text-gray-400 mb-1 space-x-2">
                <a href="{{ route('admin.assessment-packages.index') }}" class="hover:text-purple-600 transition">Paket Asesmen</a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ $package->name }}</span>
            </nav>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $package->name }}</h1>
                <span class="px-2.5 py-0.5 bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 font-mono text-xs font-bold rounded-lg border border-purple-200 dark:border-purple-800">
                    {{ $package->code }}
                </span>
                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $package->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 border-gray-300 dark:bg-slate-700 dark:text-gray-400' }}">
                    {{ $package->is_active ? 'Aktif' : 'Non-Aktif' }}
                </span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-3xl">{{ $package->description ?: 'Tidak ada deskripsi paket.' }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.assessment-packages.edit', $package) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition border border-gray-200 dark:border-slate-600">
                Edit Paket
            </a>
            <a href="{{ route('admin.assessment-packages.instruments.create', $package) }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-purple-500/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah Instrumen
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Instruments Table / List -->
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-slate-700/60 flex justify-between items-center">
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Daftar Instrumen dalam Paket Ini</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Instrumen-instrumen di bawah ini akan dimuat secara berurutan pada asesmen.</p>
            </div>
            <span class="px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-xl">
                Total {{ $package->instruments->count() }} Instrumen
            </span>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-slate-700/60">
            @forelse($package->instruments as $inst)
                <div class="p-6 hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-2 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-purple-600/10 text-purple-600 dark:text-purple-400 font-bold text-xs flex items-center justify-center">
                                #{{ $inst->order_number }}
                            </span>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                {{ $inst->name }}
                            </h3>
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 font-mono text-[10px] rounded border border-slate-200 dark:border-slate-700">
                                {{ $inst->section_key }}
                            </span>
                            <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full border {{ $inst->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 border-gray-300' }}">
                                {{ $inst->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>

                        @if($inst->instruction)
                            <p class="text-xs text-gray-600 dark:text-gray-400 italic bg-gray-50 dark:bg-slate-900/60 p-2.5 rounded-xl border border-gray-200/60 dark:border-slate-700/60">
                                " {{ $inst->instruction }} "
                            </p>
                        @endif

                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400 pt-1">
                            <span class="font-medium text-purple-600 dark:text-purple-400">Jenis Input: <strong>{{ strtoupper($inst->input_type) }}</strong></span>
                            @if($inst->min_select || $inst->max_select)
                                <span>Pilihan: {{ $inst->min_select ?: 0 }} - {{ $inst->max_select ?: 'Tak Terbatas' }} item</span>
                            @endif
                            <span class="font-semibold text-gray-700 dark:text-gray-300">● {{ $inst->items_count }} Item Pernyataan</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-start md:self-center">
                        <a href="{{ route('admin.assessment-instruments.items.index', $inst) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            Kelola Item ({{ $inst->items_count }}) →
                        </a>
                        <a href="{{ route('admin.assessment-instruments.edit', $inst) }}" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-slate-700 rounded-xl transition border border-gray-200 dark:border-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.assessment-instruments.destroy', $inst) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus instrumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-xl transition border border-gray-200 dark:border-slate-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center space-y-3">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Belum ada instrumen dalam paket ini.</p>
                    <a href="{{ route('admin.assessment-packages.instruments.create', $package) }}" class="inline-block text-xs text-purple-600 dark:text-purple-400 font-bold hover:underline">
                        + Tambah Instrumen Pertama
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
