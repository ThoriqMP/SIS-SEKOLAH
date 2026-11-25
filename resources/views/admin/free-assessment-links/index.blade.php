@extends('mbi.layouts.app')

@section('title', 'Link Free Assessment')

@section('content')
<div class="py-10 bg-gradient-to-br from-gray-50 via-slate-50 to-purple-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-700 dark:from-purple-950 dark:via-indigo-950 dark:to-blue-900 text-white shadow-2xl p-6 sm:p-8">
            <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-md border border-white/30 mb-3">
                        <i class="fa-solid fa-bolt text-amber-300"></i> Fitur Link Free Asesmen (Unlimited Tanpa Kode Akses)
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Manajemen Tautan Free Assessment</h1>
                    <p class="text-purple-100 text-sm mt-1">Kelola tautan publik asesmen gratis untuk lead marketing, skrining cepat, dan pendaftaran siswa baru.</p>
                </div>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    <a href="{{ route('admin.free-links.create') }}" 
                       class="inline-flex items-center justify-center gap-2 bg-white text-purple-900 hover:bg-purple-50 px-5 py-3 rounded-2xl font-bold transition shadow-lg text-sm">
                        <i class="fa-solid fa-plus"></i>
                        <span>Buat Tautan Baru</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/80 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-bold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Search Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.free-links.index') }}" class="w-full sm:w-96">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau slug tautan..."
                           class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs font-semibold text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-500 shadow-sm">
                </div>
            </form>
            <div class="text-xs font-bold text-slate-500">
                Menampilkan {{ $links->total() }} tautan free assessment
            </div>
        </div>

        <!-- Links Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($links as $item)
                @php
                    $appUrl = config('app.url');
                    $rootHost = parse_url($appUrl, PHP_URL_HOST);
                    if (empty($rootHost) || $rootHost === 'localhost' || $rootHost === '127.0.0.1') {
                        $currentHost = preg_replace('/^(teacher|student)\./', '', request()->getHost());
                        $scheme = request()->getScheme();
                        $port = request()->getPort();
                        $portStr = ($port && !in_array($port, [80, 443])) ? ':' . $port : '';
                        $publicUrl = $scheme . '://' . $currentHost . $portStr . '/free-assessment/' . $item->slug;
                    } else {
                        $publicUrl = rtrim($appUrl, '/') . '/free-assessment/' . $item->slug;
                    }
                @endphp
                <div class="rounded-3xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 p-6 flex flex-col justify-between space-y-5 shadow-lg hover:shadow-xl transition-all relative overflow-hidden group">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-black {{ $item->is_active ? 'bg-emerald-50 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $item->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                {{ $item->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </span>
                            <span class="text-[11px] text-slate-400 font-semibold">
                                {{ $item->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        <div>
                            <h3 class="font-extrabold text-slate-900 dark:text-white text-base group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors line-clamp-1">
                                <a href="{{ route('admin.free-links.show', $item->id) }}">{{ $item->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                                {{ $item->description ?: 'Tanpa deskripsi tambahan.' }}
                            </p>
                        </div>

                        <!-- URL Box -->
                        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-between gap-2">
                            <span class="text-[11px] font-mono font-semibold text-purple-600 dark:text-purple-300 truncate">
                                /free-assessment/{{ $item->slug }}
                            </span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $publicUrl }}'); alert('Tautan berhasil disalin!');"
                                    class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-400 hover:text-purple-500 transition shrink-0" title="Salin Tautan">
                                <i class="fa-solid fa-copy text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                        <!-- Stats Counter -->
                        <div class="flex items-center gap-3 text-xs">
                            <div>
                                <span class="font-black text-slate-900 dark:text-white text-sm">{{ $item->respondents_count }}</span>
                                <span class="text-slate-400 text-[10px] block">Responden</span>
                            </div>
                            <span class="text-slate-300 dark:text-slate-700">&bull;</span>
                            <div>
                                <span class="font-black text-emerald-500 text-sm">{{ $item->completed_sessions_count }}</span>
                                <span class="text-slate-400 text-[10px] block">Selesai</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-1.5">
                            <form method="POST" action="{{ route('admin.free-links.toggle', $item->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-2 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-400 hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition" title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fa-solid {{ $item->is_active ? 'fa-toggle-on text-emerald-500' : 'fa-toggle-off' }} text-base"></i>
                                </button>
                            </form>

                            <a href="{{ route('admin.free-links.show', $item->id) }}" class="px-3 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-md shadow-purple-900/30 transition flex items-center gap-1.5">
                                <span>Lihat Responden</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-12 text-center rounded-3xl bg-white dark:bg-slate-900/50 border border-dashed border-slate-300 dark:border-slate-800 text-slate-400 space-y-3">
                    <i class="fa-solid fa-link text-4xl text-slate-500"></i>
                    <p class="text-sm font-semibold">Belum ada tautan Free Assessment yang dibuat.</p>
                    <a href="{{ route('admin.free-links.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-bold shadow">
                        <i class="fa-solid fa-plus"></i> Buat Tautan Pertama
                    </a>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $links->links() }}
        </div>

    </div>
</div>
@endsection
