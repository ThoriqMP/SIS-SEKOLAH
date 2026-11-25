@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{
    tab: 'summary',
    pblData: {{ json_encode($project->pbl_data) }},
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Nav -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <a href="{{ route('admin.reports.bulk-pbl.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-bold text-sm mb-2 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $project->tema }}</h1>
                <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <span class="inline-block px-2.5 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md">{{ $project->period }}</span>
                    <span>•</span>
                    <i class="fas fa-map-marker-alt"></i> {{ $project->location }}
                    @if($project->kelas)
                        <span>•</span>
                        <span class="text-blue-600 dark:text-blue-400 font-bold">Kelas {{ $project->kelas }}</span>
                    @endif
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                @if($project->status === 'draft')
                    <form action="{{ route('admin.reports.bulk-pbl.finalize', $project->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mem-finalisasi draf ini? Data akan disalurkan ke Rapor PBL masing-masing siswa secara permanen.');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm transition-all active:scale-95 shadow-lg shadow-emerald-500/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Finalisasi ke Rapor
                        </button>
                    </form>
                @else
                    <span class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-2xl font-black text-sm border border-emerald-200 dark:border-emerald-800 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Sudah Difinalisasi
                    </span>
                @endif
            </div>
        </div>

        <!-- Description -->
        @if($project->description)
            <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi Proyek</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $project->description }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Indikator Kegiatan (Read-Only) -->
            <div class="md:col-span-1 space-y-4">
                <h2 class="text-lg font-black text-gray-900 dark:text-white mb-4">Indikator Penilaian</h2>
                <template x-for="(cat, catIdx) in pblData.kegiatan" :key="catIdx">
                    <div class="p-5 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <h3 class="text-sm font-black text-blue-600 dark:text-blue-400 mb-3" x-text="cat.category"></h3>
                        <div class="space-y-3">
                            <template x-for="(ind, indIdx) in cat.indicators" :key="indIdx">
                                <div class="flex items-start gap-3 pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                    <div class="w-5 h-5 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 mt-0.5">
                                        <span class="text-[10px] font-black text-blue-600 dark:text-blue-400" x-text="indIdx + 1"></span>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="ind.name"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- List Siswa dan Skor -->
            <div class="md:col-span-2">
                <h2 class="text-lg font-black text-gray-900 dark:text-white mb-4">Data Siswa yang Dinilai (<span x-text="Object.keys(pblData.scores || {}).length"></span>)</h2>
                
                <div class="space-y-4">
                    <template x-for="studentData in pblData.students_data" :key="studentData.id">
                        <div class="p-6 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col gap-4">
                            <!-- Profil Siswa -->
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-lg">
                                        <span x-text="studentData.nama.substring(0, 1)"></span>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-900 dark:text-white" x-text="studentData.nama"></h3>
                                        <p class="text-xs font-medium text-gray-500 mt-0.5" x-text="'ID: ' + studentData.id"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Catatan Observasi (Kecerdasan, Kepribadian, dsb) -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kepribadian</p>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-if="(pblData.personality[studentData.id] || []).length > 0">
                                            <template x-for="item in pblData.personality[studentData.id]">
                                                <span class="inline-block px-2 py-0.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-md text-[10px] font-bold" x-text="item"></span>
                                            </template>
                                        </template>
                                        <template x-if="(pblData.personality[studentData.id] || []).length === 0">
                                            <span class="text-xs text-gray-400 italic">-</span>
                                        </template>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kecerdasan</p>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-if="(pblData.intelligence[studentData.id] || []).length > 0">
                                            <template x-for="item in pblData.intelligence[studentData.id]">
                                                <span class="inline-block px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-md text-[10px] font-bold" x-text="item"></span>
                                            </template>
                                        </template>
                                        <template x-if="(pblData.intelligence[studentData.id] || []).length === 0">
                                            <span class="text-xs text-gray-400 italic">-</span>
                                        </template>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kekuatan</p>
                                    <div class="flex flex-wrap gap-1">
                                        <template x-if="(pblData.strength[studentData.id] || []).length > 0">
                                            <template x-for="item in pblData.strength[studentData.id]">
                                                <span class="inline-block px-2 py-0.5 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-md text-[10px] font-bold" x-text="item"></span>
                                            </template>
                                        </template>
                                        <template x-if="(pblData.strength[studentData.id] || []).length === 0">
                                            <span class="text-xs text-gray-400 italic">-</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Skor Indikator Grid Mini -->
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Ringkasan Skor Indikator</p>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="(cat, catIdx) in pblData.kegiatan" :key="catIdx">
                                        <template x-for="(ind, indIdx) in cat.indicators" :key="indIdx">
                                            <div class="flex items-center gap-1.5 px-2 py-1 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                                                <span class="text-[10px] font-medium text-gray-500" x-text="'I.' + (indIdx+1)"></span>
                                                <span class="text-xs font-black" :class="{
                                                    'text-red-500': pblData.scores[studentData.id][catIdx][indIdx] <= 2,
                                                    'text-amber-500': pblData.scores[studentData.id][catIdx][indIdx] == 3,
                                                    'text-emerald-500': pblData.scores[studentData.id][catIdx][indIdx] >= 4
                                                }" x-text="pblData.scores[studentData.id][catIdx][indIdx] || 3"></span>
                                            </div>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
