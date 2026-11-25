@extends('layouts.app')

@section('title', 'Surat Keterangan')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            {{-- Search --}}
            <form action="{{ route('surat-keterangan.index') }}" method="GET" class="w-full md:max-w-xl relative">
                @if(request('jenis'))
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                @endif
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full pl-11 pr-4 py-3 border border-gray-300 dark:border-gray-700 rounded-full text-base focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder:text-gray-400 dark:placeholder:text-gray-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition shadow-sm" 
                       placeholder="Cari nama anak / orang tua...">
            </form>

            {{-- Actions --}}
            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <a href="{{ route('surat-keterangan.settings') }}" 
                   class="whitespace-nowrap px-5 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition shadow-sm flex items-center gap-2 text-sm font-semibold">
                    <i class="fa-solid fa-gear text-purple-650 dark:text-purple-400"></i>
                    Pengaturan Logo & TTD
                </a>
                <a href="{{ route('surat-keterangan.create') }}" 
                   class="whitespace-nowrap px-6 py-2.5 bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 text-white text-sm font-medium rounded-full transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Surat Baru
                </a>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex gap-2 mb-6">
            <a href="{{ route('surat-keterangan.index', array_merge(request()->except('jenis', 'page'))) }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition {{ !request('jenis') ? 'bg-purple-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Semua
            </a>
            <a href="{{ route('surat-keterangan.index', array_merge(request()->except('page'), ['jenis' => 'cibi'])) }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition {{ request('jenis') === 'cibi' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fa-solid fa-star mr-1"></i> SK CIBI
            </a>
            <a href="{{ route('surat-keterangan.index', array_merge(request()->except('page'), ['jenis' => 'abk'])) }}" 
               class="px-4 py-2 rounded-full text-sm font-medium transition {{ request('jenis') === 'abk' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fa-solid fa-heart mr-1"></i> SK ABK
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Grid Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @forelse($suratList as $surat)
                <div class="bg-white dark:bg-gray-800 rounded-[16px] md:rounded-[20px] border border-gray-200 dark:border-gray-700 p-4 md:p-6 shadow-sm hover:shadow-lg transition duration-200 flex flex-col group">
                    
                    {{-- Header: Badge & Date --}}
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $surat->jenis_surat === 'cibi' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300' }}">
                            <i class="fa-solid {{ $surat->jenis_surat === 'cibi' ? 'fa-star' : 'fa-heart' }} mr-1.5 text-[10px]"></i>
                            {{ $surat->jenis_label }}
                        </span>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $surat->tanggal_surat->format('d/m/Y') }}
                        </div>
                    </div>

                    {{-- Nama Anak --}}
                    <h3 class="text-base md:text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight mb-1 truncate" title="{{ $surat->nama_anak }}">
                        {{ $surat->nama_anak }}
                    </h3>

                    {{-- Orang Tua --}}
                    <div class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mb-3">
                        <i class="fa-solid fa-user text-[10px] mr-1"></i> Orang Tua: {{ $surat->nama_orang_tua }}
                    </div>

                    {{-- IQ Score if CIBI --}}
                    @if($surat->jenis_surat === 'cibi' && $surat->iq_score)
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">IQ:</span>
                            <span class="px-2 py-0.5 rounded bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 text-xs font-bold">
                                {{ $surat->iq_score }}
                            </span>
                        </div>
                    @endif

                    {{-- Psikolog --}}
                    <div class="border-t border-gray-100 dark:border-gray-700 pt-3 mt-auto">
                        <div class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Psikolog</div>
                        <div class="text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-300 truncate">
                            {{ $surat->psikolog_nama }}
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <a href="{{ route('surat-keterangan.show', $surat) }}" 
                           class="flex-1 text-center py-2 px-3 text-xs font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            <i class="fa-solid fa-eye mr-1"></i> Lihat
                        </a>
                        <a href="{{ route('surat-keterangan.pdf', $surat) }}?preview=1" target="_blank"
                           class="flex-1 text-center py-2 px-3 text-xs font-medium rounded-lg bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800/60 transition">
                            <i class="fa-solid fa-file-pdf mr-1"></i> PDF
                        </a>
                        <a href="{{ route('surat-keterangan.edit', $surat) }}" 
                           class="py-2 px-3 text-xs font-medium rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 hover:bg-amber-200 dark:hover:bg-amber-800/60 transition">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('surat-keterangan.destroy', $surat) }}" method="POST" 
                              onsubmit="return confirm('Hapus surat keterangan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="py-2 px-3 text-xs font-medium rounded-lg bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800/60 transition">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <i class="fa-solid fa-file-lines text-2xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Belum Ada Surat Keterangan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Buat surat keterangan pertama Anda.</p>
                        <a href="{{ route('surat-keterangan.create') }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-full transition shadow-md">
                            <i class="fa-solid fa-plus mr-2"></i> Buat Surat Baru
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($suratList->hasPages())
            <div class="mt-8">
                {{ $suratList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
