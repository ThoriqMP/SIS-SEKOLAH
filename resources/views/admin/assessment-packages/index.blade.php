@extends('mbi.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex text-xs text-gray-500 dark:text-gray-400 mb-1 space-x-2">
                <a href="{{ route('assessments.index') }}" class="hover:text-purple-600 transition">Asesmen</a>
                <span>/</span>
                <span class="text-gray-800 dark:text-gray-200 font-semibold">Manajemen Paket Asesmen</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-600/10 border border-purple-500/30 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                Manajemen Paket Asesmen
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kelola hierarki Paket Asesmen, Instrumen, dan Item Pernyataan dalam sistem.</p>
        </div>

        <div>
            <a href="{{ route('admin.assessment-packages.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-purple-500/20 transition transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah Paket Asesmen
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Package Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages as $pkg)
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-5 relative">
                <div class="space-y-3">
                    <div class="flex justify-between items-start">
                        <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60 text-[10px] font-bold rounded-lg font-mono tracking-wide uppercase">
                            {{ $pkg->code }}
                        </span>

                        <form action="{{ route('admin.assessment-packages.toggle', $pkg) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold rounded-full border transition {{ $pkg->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800' : 'bg-gray-100 text-gray-500 border-gray-300 dark:bg-slate-700 dark:text-gray-400 dark:border-slate-600' }}">
                                {{ $pkg->is_active ? '● Aktif' : '○ Non-Aktif' }}
                            </button>
                        </form>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-snug">
                        {{ $pkg->name }}
                    </h3>

                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-3 leading-relaxed">
                        {{ $pkg->description ?: 'Tidak ada deskripsi paket.' }}
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-slate-700/60 space-y-4">
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1.5 font-semibold text-purple-600 dark:text-purple-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            {{ $pkg->instruments_count }} Instrumen
                        </span>
                        <span class="text-[11px]">Urutan: #{{ $pkg->order_number }}</span>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <a href="{{ route('admin.assessment-packages.show', $pkg) }}" class="flex-1 text-center py-2 px-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                            Kelola Instrumen →
                        </a>
                        <a href="{{ route('admin.assessment-packages.edit', $pkg) }}" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-slate-700 rounded-xl transition border border-gray-200 dark:border-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="{{ route('admin.assessment-packages.destroy', $pkg) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket asesmen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-xl transition border border-gray-200 dark:border-slate-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 space-y-3">
                <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Belum Ada Paket Asesmen</p>
                <a href="{{ route('admin.assessment-packages.create') }}" class="inline-block text-xs text-purple-600 dark:text-purple-400 font-bold hover:underline">
                    + Buat Paket Asesmen Pertama
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
