@extends('layouts.app')

@section('content')
<style>
    .compact-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .compact-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .dark .compact-scrollbar::-webkit-scrollbar-thumb {
        background: #4b5563;
    }
</style>

<!-- Eye-Level Vertically Centered Executive Report Console -->
<div class="w-full min-h-[calc(100vh-2rem)] flex flex-col justify-center py-3 sm:py-5 px-3 sm:px-6 lg:px-8 pb-28 lg:pb-6" 
     x-data="{ showGuideline: false, rightTab: 'notes' }">
    
    <div class="max-w-7xl mx-auto w-full my-auto space-y-3 sm:space-y-3.5">
        
        <!-- COMPACT HEADER BAR: Identitas Siswa, Periode & Tombol Cepat (1 Baris Ringkas Eye-Level) -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-3 sm:p-3.5 shadow-sm shrink-0">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                
                <!-- Left: Back Navigation, Avatar, Student Name, Badges & Meta -->
                <div class="flex items-center gap-3 min-w-0">
                    <a href="{{ auth()->user()->role === 'student' ? route('student.reports') : route('admin.reports.student-list', [$report->siswa_id, 'tahun_ajaran' => request('tahun_ajaran')]) }}" 
                       class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-gray-750 dark:hover:bg-gray-700 text-slate-500 hover:text-purple-600 transition flex items-center justify-center shrink-0" 
                       title="Kembali ke Daftar Raport">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </a>

                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 flex items-center justify-center font-black text-base shrink-0 border border-purple-200/70 dark:border-purple-800/60">
                        {{ strtoupper(substr($report->student->nama_siswa ?? 'S', 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-base sm:text-lg font-black text-slate-900 dark:text-white truncate leading-tight">
                                {{ $report->student->nama_siswa }}
                            </h1>

                            <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 rounded-md">
                                {{ $report->category->name }}
                            </span>

                            @if($report->academic_type)
                                <span class="px-2 py-0.5 text-[10px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-gray-750 rounded-md uppercase">
                                    {{ $report->academic_type }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-x-2 text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $report->period }}</span>
                            <span>•</span>
                            <span>NIS: <strong class="font-mono text-slate-700 dark:text-slate-300">{{ $report->student->nis ?: '-' }}</strong></span>
                            @if($report->student->class)
                                <span>•</span>
                                <span>Kelas: <strong>{{ $report->student->class }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Action Buttons -->
                <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
                    <button @click="showGuideline = true" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/60 text-purple-700 dark:text-purple-300 rounded-xl font-bold transition text-xs border border-purple-200/60 dark:border-purple-800/60 min-h-[36px]">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ __('messages.how_to_read') }}</span>
                    </button>

                    <a href="{{ route('student.reports.pdf', $report) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-900 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition shadow-xs min-h-[36px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>{{ __('messages.save_pdf') }}</span>
                    </a>
                </div>
            </div>
        </div>

        @if(str_contains(strtolower($report->category->name ?? ''), 'project based') || str_contains(strtolower($report->category->name ?? ''), 'probing') || strtolower($report->category->name ?? '') === 'pbl')
            <!-- 1. PBL Probing Immersive View -->
            <div class="space-y-6">
                @foreach($report->probingActivities as $activity)
                    @php $pbl = $activity->pbl_data ?? []; @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-5 shadow-sm overflow-hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                            <div class="lg:col-span-7 space-y-3">
                                <span class="px-3 py-1 bg-purple-50 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 rounded-full text-[10px] font-bold uppercase tracking-wider border border-purple-200">
                                    Project Based Learning
                                </span>
                                <h4 class="text-xl font-black text-slate-900 dark:text-white leading-tight">
                                    {{ $activity->activity_name }}
                                </h4>
                                <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed italic">
                                    "{{ $pbl['description'] ?? $activity->description }}"
                                </p>
                            </div>
                            <div class="lg:col-span-5 relative bg-slate-100 dark:bg-gray-900 rounded-xl overflow-hidden min-h-[220px]">
                                @php $images = $pbl['images'] ?? ($activity->image_path ? [$activity->image_path] : []); @endphp
                                @if(count($images) > 0)
                                    <img src="{{ Storage::url($images[0]) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="h-full w-full flex flex-col items-center justify-center text-slate-400 p-6">
                                        <p class="text-xs font-bold uppercase tracking-wider">Dokumentasi Foto Belum Tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @elseif(strtolower($report->category->name) === 'diniyah')
            <!-- 2. Diniyah Quranic Tahfidz Board -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php $surahs = \App\Helpers\ReportHelper::getSurahs(); @endphp
                @foreach($report->diniyahReports as $diniyah)
                    @php $surah = collect($surahs)->where('id', $diniyah->surah_id)->first(); @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-4 shadow-sm flex items-center justify-between">
                        <div>
                            <h5 class="text-base font-black text-slate-900 dark:text-white">{{ $surah['name'] ?? 'Surat' }}</h5>
                            <span class="text-xs text-purple-600 font-bold">Ayat {{ $diniyah->start_ayah }} - {{ $diniyah->end_ayah }}</span>
                            <p class="text-xs text-slate-500 italic mt-1">"{{ $diniyah->description }}"</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-600 text-white rounded-xl flex items-center justify-center font-black text-lg shrink-0">
                            {{ $diniyah->mutu_code }}
                        </div>
                    </div>
                @endforeach
            </div>

        @elseif(strtolower($report->category->name) === 'praktek ibadah')
            <!-- 3. Ibadah Verified Checklist Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @php
                    $ibadahItems = \App\Helpers\ReportHelper::getIbadahItems();
                    if ($report->ibadahReport && isset($report->ibadahReport->additional_grades['_ordered_indicators'])) {
                        $ibadahItems = $report->ibadahReport->additional_grades['_ordered_indicators'];
                    }
                @endphp
                @foreach($ibadahItems as $item)
                    @php
                        $score = '-';
                        if ($report->ibadahReport) {
                            if (isset($report->ibadahReport->{$item['id']})) {
                                $score = $report->ibadahReport->{$item['id']};
                            } elseif (isset($report->ibadahReport->additional_grades[$item['id']])) {
                                $score = $report->ibadahReport->additional_grades[$item['id']];
                            }
                        }
                        $score = $score ?: '-';
                    @endphp
                    <div class="bg-white dark:bg-gray-800 p-3.5 rounded-xl border border-slate-200/80 dark:border-gray-700 flex items-center gap-3 shadow-sm">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center font-black text-sm {{ $score === 'A' ? 'bg-purple-600 text-white' : ($score === 'B' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-400') }}">
                            {{ $score }}
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white text-xs block leading-tight">{{ $item['label'] }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $score === 'A' ? 'Sangat Baik' : ($score === 'B' ? 'Baik' : 'Belum Terisi') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <!-- 4. ACADEMIC COMPACT EYE-LEVEL CONSOLE (Semua Nilai Ditampilkan 1 Halaman Tanpa Scroll) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-stretch">
                
                <!-- LEFT 8 COLUMNS: MATRIKS NILAI AKADEMIK TERPADU (Semua 8+ Mapel Terlihat Sekaligus) -->
                <div class="lg:col-span-8 bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-4 shadow-sm flex flex-col justify-between">
                    <div>
                        <!-- Header Tabel -->
                        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-100 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">
                                    Rincian Nilai Mata Pelajaran
                                </h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10.5px] font-bold text-slate-500 dark:text-slate-400">
                                    {{ $report->grades->count() }} Mata Pelajaran Terdaftar
                                </span>
                                <span class="px-2 py-0.5 text-[9.5px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 rounded border border-emerald-200/60">
                                    Active Report
                                </span>
                            </div>
                        </div>

                        <!-- Matriks Tabel Nilai Lengkap (Tanpa Scroll, Eye-Level) -->
                        <div class="overflow-x-auto rounded-xl border border-slate-200/80 dark:border-gray-700">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-50/90 dark:bg-gray-750 text-slate-500 font-bold uppercase tracking-wider text-[9.5px] border-b border-slate-200/80 dark:border-gray-700">
                                    <tr>
                                        <th class="py-2.5 px-3">Mata Pelajaran</th>
                                        <th class="py-2.5 px-2 text-center">KKM</th>
                                        <th class="py-2.5 px-2 text-center">Harian</th>
                                        <th class="py-2.5 px-2 text-center">PTS</th>
                                        <th class="py-2.5 px-2 text-center">PAS</th>
                                        <th class="py-2.5 px-2 text-center">Remedial</th>
                                        <th class="py-2.5 px-3 text-center bg-purple-50/50 dark:bg-purple-950/20">Nilai Akhir</th>
                                        <th class="py-2.5 px-3 text-center">Predikat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-gray-700/60 font-medium">
                                    @foreach($report->grades as $grade)
                                        <tr class="hover:bg-purple-50/30 dark:hover:bg-gray-750/40 transition">
                                            <!-- Nama Mapel + Keterangan Guru jika ada -->
                                            <td class="py-2 px-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-lg bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 font-black text-[11px] flex items-center justify-center shrink-0">
                                                        {{ strtoupper(substr($grade->subject->name, 0, 1)) }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <span class="font-bold text-slate-900 dark:text-white block truncate text-[11.5px]">{{ $grade->subject->name }}</span>
                                                        @if($grade->description)
                                                            <span class="text-[9.5px] text-slate-400 italic block truncate max-w-xs" title="{{ $grade->description }}">
                                                                "{{ $grade->description }}"
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- KKM -->
                                            <td class="py-2 px-2 text-center font-mono text-slate-400 text-[10.5px]">
                                                {{ $grade->subject->kkm ?? 75 }}
                                            </td>

                                            <!-- Harian -->
                                            <td class="py-2 px-2 text-center font-mono text-slate-700 dark:text-slate-300 text-xs">
                                                {{ $grade->score_harian ? round($grade->score_harian) : '-' }}
                                            </td>

                                            <!-- PTS -->
                                            <td class="py-2 px-2 text-center font-mono text-slate-700 dark:text-slate-300 text-xs">
                                                {{ $grade->score_pts ? round($grade->score_pts) : '-' }}
                                            </td>

                                            <!-- PAS -->
                                            <td class="py-2 px-2 text-center font-mono text-slate-700 dark:text-slate-300 text-xs">
                                                {{ $grade->score_pas ? round($grade->score_pas) : '-' }}
                                            </td>

                                            <!-- Remedial -->
                                            <td class="py-2 px-2 text-center font-mono text-slate-700 dark:text-slate-300 text-xs">
                                                {{ $grade->score_remedial ? round($grade->score_remedial) : '-' }}
                                            </td>

                                            <!-- Nilai Akhir (Highlight) -->
                                            <td class="py-2 px-3 text-center bg-purple-50/50 dark:bg-purple-950/20">
                                                <span class="font-mono font-black text-sm text-slate-900 dark:text-white">
                                                    {{ is_numeric($grade->score) ? round($grade->score) : '-' }}
                                                </span>
                                            </td>

                                            <!-- Predikat -->
                                            <td class="py-2 px-3 text-center">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider {{ ($grade->predicate === 'A' || $grade->score >= 85) ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300' : 'bg-purple-50 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300' }}">
                                                    Predikat {{ $grade->predicate ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bottom Summary Bar Tabel -->
                    <div class="pt-2.5 mt-2 border-t border-slate-100 dark:border-gray-700 flex items-center justify-between text-xs text-slate-500">
                        <div class="flex items-center gap-3">
                            <span>Standar KKM Kelulusan: <strong class="text-slate-800 dark:text-slate-200">75</strong></span>
                            <span>•</span>
                            <span class="text-emerald-600 font-bold">Status: Seluruh Nilai Memenuhi KKM</span>
                        </div>
                        <span class="font-mono text-[11px] text-slate-400">Total: {{ $report->grades->count() }} Mata Pelajaran</span>
                    </div>
                </div>

                <!-- RIGHT 4 COLUMNS: KONSOL RERATA, EVALUASI & RADAR KEKUATAN -->
                <div class="lg:col-span-4 flex flex-col justify-between gap-3">
                    
                    <!-- Card 1: Rerata Nilai Akademik (Gradient Hero Card Ringkas) -->
                    <div class="bg-gradient-to-br from-purple-800 via-purple-900 to-indigo-950 rounded-2xl p-4 text-white shadow-sm border border-purple-700/50 relative overflow-hidden shrink-0">
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <span class="text-purple-300 text-[10px] font-black uppercase tracking-wider block">Rerata Akademik</span>
                                <div class="text-4xl font-black text-white font-mono tracking-tight mt-0.5">
                                    {{ round($report->grades->avg('score'), 1) }}
                                </div>
                                <span class="text-[10px] text-purple-300 block mt-0.5">
                                    {{ $report->grades->count() }} Mata Pelajaran Terverifikasi KKM
                                </span>
                            </div>

                            <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex flex-col items-center justify-center border border-white/20 shadow-xs shrink-0">
                                <span class="text-2xl font-black text-purple-100 leading-none">
                                    {{ $report->grades->avg('score') >= 90 ? 'A' : ($report->grades->avg('score') >= 80 ? 'B' : 'C') }}
                                </span>
                                <span class="text-[8.5px] uppercase font-bold text-purple-300 tracking-wider">Predikat</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Evaluasi Guru & Analisis Radar (Dengan Tab Switcher Cerdas) -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-4 shadow-sm flex-1 flex flex-col justify-between overflow-hidden">
                        
                        <div>
                            <!-- Tab Switcher: Catatan Evaluasi vs Radar Kekuatan -->
                            <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-100 dark:border-gray-700">
                                <div class="flex p-0.5 bg-slate-100 dark:bg-gray-750 rounded-xl text-[10.5px] font-bold">
                                    <button type="button" 
                                            @click="rightTab = 'notes'" 
                                            :class="rightTab === 'notes' ? 'bg-white dark:bg-gray-700 text-purple-700 dark:text-purple-300 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                            class="px-2.5 py-1 rounded-lg transition min-h-[26px]">
                                        Catatan Evaluasi
                                    </button>
                                    <button type="button" 
                                            @click="rightTab = 'radar'; $nextTick(() => { window.reportRadarChart && window.reportRadarChart.resize(); })" 
                                            :class="rightTab === 'radar' ? 'bg-white dark:bg-gray-700 text-purple-700 dark:text-purple-300 shadow-xs' : 'text-slate-500 hover:text-slate-800'"
                                            class="px-2.5 py-1 rounded-lg transition min-h-[26px]">
                                        Radar Kekuatan
                                    </button>
                                </div>

                                <span class="text-[10px] text-slate-400 font-bold uppercase" x-text="rightTab === 'notes' ? 'Evaluasi' : 'Peta Grafis'"></span>
                            </div>

                            <!-- TAB CONTENT 1: CATATAN & EVALUASI GURU -->
                            <div x-show="rightTab === 'notes'" class="space-y-2 text-xs">
                                <!-- Penilaian PTS / PAS -->
                                @if($report->pts_pas_summary)
                                    <div class="p-2.5 bg-purple-50/60 dark:bg-purple-950/30 rounded-xl border border-purple-100 dark:border-purple-800/40">
                                        <span class="text-[9.5px] font-bold text-purple-900 dark:text-purple-300 uppercase tracking-wider block mb-0.5">
                                            Penilaian {{ $report->academic_type === 'PAS' ? 'Akhir' : 'Tengah' }} Semester
                                        </span>
                                        <p class="text-[11px] text-slate-700 dark:text-slate-300 italic leading-snug line-clamp-3">
                                            "{{ $report->pts_pas_summary }}"
                                        </p>
                                    </div>
                                @endif

                                <!-- Laporan Remedial -->
                                @if($report->remedial_summary)
                                    <div class="p-2 bg-slate-50 dark:bg-gray-750/50 rounded-xl border border-slate-200/60 dark:border-gray-700">
                                        <span class="text-[9.5px] font-bold text-slate-400 uppercase tracking-wider block">Laporan Remedial</span>
                                        <p class="text-[10.5px] text-slate-600 dark:text-slate-300 italic truncate">
                                            "{{ $report->remedial_summary }}"
                                        </p>
                                    </div>
                                @endif

                                <!-- Catatan Wali Kelas / Guru Pendidik -->
                                <div class="p-2.5 bg-slate-50 dark:bg-gray-750/50 rounded-xl border border-slate-200/60 dark:border-gray-700 space-y-1">
                                    <span class="text-[9.5px] font-bold text-slate-500 uppercase tracking-wider block">Catatan Guru Pendidik</span>
                                    <p class="text-[11px] text-slate-800 dark:text-slate-200 font-semibold italic leading-snug line-clamp-3">
                                        "{{ $report->summary_notes ?? 'Alhamdulillah, ananda menunjukkan perkembangan yang sangat positif. Teruslah istiqomah dalam belajar dan berbuat baik.' }}"
                                    </p>
                                </div>
                            </div>

                            <!-- TAB CONTENT 2: ANALISIS RADAR CHART -->
                            <div x-show="rightTab === 'radar'" x-cloak class="flex flex-col items-center justify-center p-2">
                                <div class="relative w-full aspect-square max-h-[220px]">
                                    <canvas id="reportChart"></canvas>
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block">Visualisasi sebaran penguasaan mata pelajaran</span>
                            </div>
                        </div>

                        <!-- Footer Wali Kelas & Tanda Tangan -->
                        <div class="pt-2.5 mt-2 border-t border-slate-100 dark:border-gray-700 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-slate-900 text-white font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($report->teacher->name ?? 'W', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 dark:text-white block text-[11px] leading-none">{{ $report->teacher->name ?? 'Wali Kelas' }}</span>
                                    <span class="text-[9.5px] text-slate-400 block">{{ $report->teacher->role ?? 'PENDIDIK' }}</span>
                                </div>
                            </div>

                            @if($report->teacher && $report->teacher->signature_path)
                                <div class="h-8 w-20 bg-slate-50 dark:bg-gray-750 rounded p-1 flex items-center justify-center border border-slate-200 dark:border-gray-700">
                                    <img src="{{ Storage::url($report->teacher->signature_path) }}" alt="TTD Guru" class="h-full w-auto object-contain">
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

            </div>
        @endif

    </div>

    <!-- Guideline Modal (Fixed Glassmorphic Layout) -->
    <div x-show="showGuideline" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" @click="showGuideline = false" x-transition.opacity></div>
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden relative z-10 transform transition-all max-h-[85vh] flex flex-col border border-slate-200 dark:border-gray-700"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <div class="p-6 flex-shrink-0 border-b border-purple-100 dark:border-purple-900/40">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center shrink-0 border border-purple-100/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight">Panduan Membaca Raport</h3>
                            <p class="text-[9px] font-black text-purple-600 uppercase tracking-widest mt-0.5">Kategori: {{ $report->category->name }}</p>
                        </div>
                    </div>
                    <button @click="showGuideline = false" class="text-slate-400 hover:text-rose-500 transition-colors bg-slate-100 dark:bg-gray-700/60 p-1.5 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <div class="p-6 overflow-y-auto text-xs text-slate-600 dark:text-slate-300 leading-relaxed space-y-3">
                <p>Halaman ini menampilkan <strong>Capaian Hasil Belajar Akademik</strong> untuk periode terpilih:</p>
                <ul class="list-disc pl-5 space-y-1.5">
                    <li><strong>Rincian Nilai:</strong> Menampilkan nilai akhir tiap mata pelajaran, KKM, beserta detail Harian, PTS, PAS, dan Remedial.</li>
                    <li><strong>Rerata Akademik:</strong> Nilai rata-rata dari seluruh mata pelajaran terverifikasi KKM beserta konversi predikat (A/B/C).</li>
                    <li><strong>Radar Kekuatan:</strong> Peta grafik laba-laba untuk melihat sebaran kekuatan mata pelajaran yang dikuasai siswa secara visual.</li>
                </ul>

                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-gray-700 flex justify-end">
                    <button @click="showGuideline = false" class="px-6 py-2.5 bg-purple-600 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-purple-700 transition">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@if(strtolower($report->category->name) === 'akademik')
<!-- Radar Chart.js Configuration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('reportChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const subjects = {!! json_encode($report->grades->pluck('subject.name')) !!};
        const scores = {!! json_encode($report->grades->pluck('score')) !!};

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.04)';
        const labelColor = isDark ? 'rgba(255, 255, 255, 0.6)' : 'rgba(0, 0, 0, 0.6)';

        window.reportRadarChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: subjects,
                datasets: [{
                    label: 'Skor Capaian',
                    data: scores,
                    fill: true,
                    backgroundColor: 'rgba(139, 92, 246, 0.2)',
                    borderColor: 'rgb(139, 92, 246)',
                    pointBackgroundColor: 'rgb(139, 92, 246)',
                    pointBorderColor: '#ffffff',
                    borderWidth: 3,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: gridColor },
                        grid: { color: gridColor },
                        pointLabels: {
                            color: labelColor,
                            font: { size: 9, weight: '700' }
                        },
                        ticks: { display: false, max: 100, min: 0 }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endif
@endsection
