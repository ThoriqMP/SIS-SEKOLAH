@extends('layouts.app')

@section('content')
<div class="py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen" 
     x-data="{ 
        activeSection: localStorage.getItem('student_reports_active_tab') || 'academic', 
        selectedPeriod: '' 
     }"
     x-init="$watch('activeSection', val => localStorage.setItem('student_reports_active_tab', val))">
    <!-- Immersive Header -->
    <div class="mb-8 sm:mb-12">
        <span class="px-3 py-1 bg-purple-100 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400 rounded-full text-[9px] font-black uppercase tracking-widest border border-purple-200/20 shadow-sm">
            Portal Akademik
        </span>
        <h2 class="text-3xl md:text-5xl font-black text-gray-955 dark:text-white mt-3 mb-4 tracking-tight leading-tight font-display">Dashboard Perkembangan</h2>
        <p class="text-xs sm:text-base text-gray-500 dark:text-gray-400 font-bold max-w-2xl leading-relaxed">Pantau grafik kenaikan nilai akademik, progres hafalan Diniyah, dan catatan aktivitas harian secara real-time.</p>
    </div>

    <!-- Dashboard Navigation Tabs -->
    <div class="flex gap-3 mb-8 sm:mb-10 overflow-x-auto pb-2 scrollbar-hide border-b border-purple-100/50 dark:border-purple-900/40">
        <button @click="activeSection = 'academic'" 
                :class="activeSection === 'academic' ? 'bg-purple-650 text-white shadow-lg shadow-purple-500/20' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white border border-gray-100 dark:border-gray-700/60'" 
                class="px-6 py-3 rounded-full font-black transition-all duration-300 whitespace-nowrap uppercase tracking-widest text-[9px] sm:text-xs">
            Tren Akademik
        </button>
        <button @click="activeSection = 'spiritual'" 
                :class="activeSection === 'spiritual' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white border border-gray-100 dark:border-gray-700/60'" 
                class="px-6 py-3 rounded-full font-black transition-all duration-300 whitespace-nowrap uppercase tracking-widest text-[9px] sm:text-xs">
            Diniyah & Ibadah
        </button>
        <button @click="activeSection = 'activities'" 
                :class="activeSection === 'activities' ? 'bg-violet-600 text-white shadow-lg shadow-violet-500/20' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white border border-gray-100 dark:border-gray-700/60'" 
                class="px-6 py-3 rounded-full font-black transition-all duration-300 whitespace-nowrap uppercase tracking-widest text-[9px] sm:text-xs">
            Project Based Learning (PBL)
        </button>
        <button @click="activeSection = 'all'" 
                :class="activeSection === 'all' ? 'bg-gray-900 dark:bg-white dark:text-gray-900 text-white shadow-lg' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white border border-gray-100 dark:border-gray-700/60'" 
                class="px-6 py-3 rounded-full font-black transition-all duration-300 whitespace-nowrap uppercase tracking-widest text-[9px] sm:text-xs">
            Semua Laporan
        </button>
    </div>

    <!-- Content Sections -->
    <div class="space-y-12">
        
        <!-- 1. Academic Trend Section -->
        <div x-show="activeSection === 'academic'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Trend Chart -->
                <div class="lg:col-span-2 clay-card p-6 sm:p-8 border border-white/40 dark:border-gray-800/40 shadow-xl relative overflow-hidden group">
                    <div class="flex justify-between items-center mb-6 sm:mb-8">
                        <div>
                            <h3 class="text-base sm:text-xl font-black text-gray-955 dark:text-white uppercase tracking-wider">Grafik Perkembangan Nilai</h3>
                            <p class="text-gray-450 dark:text-gray-500 font-bold text-xs mt-1">Visualisasi kenaikan nilai rata-rata per periode akademik.</p>
                        </div>
                        <div class="hidden sm:flex h-12 w-12 bg-purple-50 dark:bg-purple-950/40 rounded-2xl items-center justify-center text-purple-600 dark:text-purple-400 shadow-inner group-hover:scale-105 transition-transform border border-purple-250/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                    <div class="h-80 relative z-10">
                        <canvas id="academicTrendChart"></canvas>
                    </div>
                </div>

                <!-- Academic Summary Cards -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-purple-650 via-purple-750 to-indigo-800 p-6 sm:p-8 rounded-[32px] sm:rounded-[40px] text-white shadow-xl shadow-purple-900/10 relative overflow-hidden group border border-white/20">
                        <p class="text-purple-200 font-black uppercase tracking-widest text-[9px] mb-2 font-display">Pencapaian Tertinggi</p>
                        @php
                            $maxScore = $academicTrend->max('average') ?: 0;
                            $latestScore = $academicTrend->last()['average'] ?? 0;
                            $growth = $academicTrend->count() > 1 ? ($latestScore - $academicTrend->get($academicTrend->count()-2)['average']) : 0;
                        @endphp
                        <h4 class="text-4xl sm:text-5xl font-black mb-6 tracking-tighter">{{ $maxScore }}</h4>
                        <div class="flex items-center gap-2 text-[10px] sm:text-xs font-bold bg-white/20 backdrop-blur-md px-3.5 py-2 rounded-2xl w-fit border border-white/10 shadow-sm">
                            @if($growth >= 0)
                                <svg class="w-4 h-4 text-emerald-300 animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                                <span class="text-emerald-300 font-extrabold">+{{ $growth }}</span>
                            @else
                                <svg class="w-4 h-4 text-rose-300 animate-bounce" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1v-5a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd"></path></svg>
                                <span class="text-rose-300 font-extrabold">{{ $growth }}</span>
                            @endif
                            Naik dari periode lalu
                        </div>
                        <div class="absolute -bottom-16 -right-16 w-36 h-36 bg-purple-500/20 rounded-full blur-2xl group-hover:scale-110 transition-transform"></div>
                    </div>

                    <div class="clay-card p-6 sm:p-8 border border-white/40 dark:border-gray-800/40 shadow-xl">
                        <h4 class="text-xs sm:text-sm font-black text-gray-900 dark:text-white mb-6 uppercase tracking-wider">Mata Pelajaran Unggulan</h4>
                        <div class="space-y-4">
                            @php
                                $bestSubject = \App\Models\ReportGrade::whereIn('student_report_id', $academicReports->pluck('id'))
                                    ->with('subject')
                                    ->orderBy('score', 'desc')
                                    ->first();
                            @endphp
                            @if($bestSubject)
                                <div class="flex items-center gap-4 p-4.5 bg-slate-100/50 dark:bg-gray-900/50 rounded-2xl border border-slate-200/20 shadow-inner group">
                                    <div class="h-12 w-12 bg-purple-100 text-purple-650 dark:bg-purple-950/40 dark:text-purple-400 rounded-xl flex items-center justify-center font-black shadow-inner group-hover:scale-105 transition-transform border border-purple-200/10">
                                        {{ substr($bestSubject->subject->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-black text-gray-955 dark:text-gray-200">{{ $bestSubject->subject->name }}</p>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-0.5">Skor Tertinggi: {{ $bestSubject->score }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Spiritual (Diniyah & Ibadah) Section -->
        <div x-show="activeSection === 'spiritual'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Latest Diniyah Summary -->
                <div class="clay-card p-6 sm:p-8 border border-white/40 dark:border-gray-800/40 shadow-xl overflow-hidden relative group">
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="h-12 w-12 bg-purple-50 dark:bg-purple-950/40 text-purple-650 dark:text-purple-450 rounded-xl flex items-center justify-center shrink-0 shadow-inner group-hover:scale-105 transition-transform border border-purple-250/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-xl font-black text-gray-955 dark:text-white uppercase tracking-wider">Progres Hafalan Diniyah</h3>
                                <p class="text-gray-400 dark:text-gray-500 font-bold text-xs mt-0.5">Rangkuman setoran hafalan surat terbaru.</p>
                            </div>
                        </div>

                        @if($latestDiniyah && $latestDiniyah->diniyahReports->count() > 0)
                            <div class="space-y-4">
                                @foreach($latestDiniyah->diniyahReports as $dr)
                                    <div class="flex items-center justify-between p-5 bg-purple-50/20 dark:bg-purple-950/20 rounded-2xl border border-purple-100/50 dark:border-purple-900/30">
                                        <div>
                                            <p class="text-sm sm:text-base font-black text-gray-805 dark:text-gray-200">Surat {{ \App\Helpers\ReportHelper::getSurahs()[$dr->surah_id-1]['name'] }}</p>
                                            <p class="text-[10px] font-black text-purple-650 dark:text-purple-400 mt-0.5 uppercase">Ayat {{ $dr->start_ayah }} - {{ $dr->end_ayah }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xl sm:text-2xl font-black text-purple-605 dark:text-purple-400">{{ $dr->mutu_code }}</div>
                                            <div class="text-[8px] font-black uppercase text-gray-400 dark:text-gray-500 tracking-widest mt-0.5">{{ \App\Helpers\ReportHelper::getMutuDescription($dr->mutu_code) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400 dark:text-gray-500 font-bold text-center py-12 italic text-sm">Belum ada data hafalan terbaru.</p>
                        @endif
                    </div>
                    <div class="absolute -bottom-16 -right-16 w-36 h-36 bg-purple-500/5 rounded-full blur-2xl group-hover:scale-110 transition-transform"></div>
                </div>

                <!-- Latest Ibadah Summary -->
                <div class="clay-card p-6 sm:p-8 border border-white/40 dark:border-gray-800/40 shadow-xl overflow-hidden relative group">
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="h-12 w-12 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-650 dark:text-indigo-400 rounded-xl flex items-center justify-center shrink-0 shadow-inner group-hover:scale-105 transition-transform border border-indigo-100/10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-xl font-black text-gray-955 dark:text-white uppercase tracking-wider">Checklist Praktek Ibadah</h3>
                                <p class="text-gray-400 dark:text-gray-500 font-bold text-xs mt-0.5">Status amalan ibadah harian ananda.</p>
                            </div>
                        </div>

                        @if($latestIbadah && $latestIbadah->ibadahReport)
                            <div class="grid grid-cols-2 gap-4">
                                @foreach(['alfatihah' => 'Al-Fatihah', 'rukuk' => 'Rukuk', 'sujud' => 'Sujud', 'tasyahud_akhir' => 'Tasyahud'] as $key => $label)
                                    <div class="p-4 bg-purple-50/20 dark:bg-purple-950/20 rounded-2xl border border-purple-100/30 dark:border-purple-900/30 text-center">
                                        <p class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">{{ $label }}</p>
                                        @php
                                            $score = '-';
                                            if ($latestIbadah->ibadahReport) {
                                                if (isset($latestIbadah->ibadahReport->{$key})) {
                                                    $score = $latestIbadah->ibadahReport->{$key};
                                                } elseif (isset($latestIbadah->ibadahReport->additional_grades[$key])) {
                                                    $score = $latestIbadah->ibadahReport->additional_grades[$key];
                                                }
                                            }
                                            $score = $score ?: '-';
                                        @endphp
                                        <div class="text-xl sm:text-2xl font-black text-purple-650 dark:text-purple-400 shadow-sm">{{ $score }}</div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-8 text-center">
                                <a href="{{ route('student.reports.show', $latestIbadah) }}" class="text-xs font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest hover:underline flex items-center justify-center gap-1.5">
                                    Lihat Selengkapnya &rarr;
                                </a>
                            </div>
                        @else
                            <p class="text-gray-400 dark:text-gray-500 font-bold text-center py-12 italic text-sm">Belum ada data praktek ibadah terbaru.</p>
                        @endif
                    </div>
                    <div class="absolute -bottom-16 -right-16 w-36 h-36 bg-purple-500/5 rounded-full blur-2xl group-hover:scale-110 transition-transform"></div>
                </div>
            </div>
        </div>

        <!-- 3. Activity Log (PBL) Section -->
        <div x-show="activeSection === 'activities'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
            <div class="clay-card border border-white/40 dark:border-gray-800/40 shadow-xl overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-purple-100/50 dark:border-purple-900/30 bg-slate-100/30 dark:bg-gray-900/20">
                    <h3 class="text-base sm:text-xl font-black text-gray-955 dark:text-white uppercase tracking-wider">Kecerdasan & Karakter PBL</h3>
                    <p class="text-gray-400 dark:text-gray-500 font-bold text-xs mt-1">Metrik karakter tertinggi yang terakumulasi dari penilaian proyek harian kelas.</p>
                    
                    @php
                        $allPersonalities = [];
                        $allIntelligences = [];
                        $allStrengths = [];
                        foreach($probingReports as $pr) {
                            foreach($pr->probingActivities as $activity) {
                                $pbl = is_array($activity->pbl_data) ? $activity->pbl_data : json_decode($activity->pbl_data, true) ?? [];
                                if(!empty($pbl['personality'])) $allPersonalities = array_merge($allPersonalities, $pbl['personality']);
                                if(!empty($pbl['intelligence'])) $allIntelligences = array_merge($allIntelligences, $pbl['intelligence']);
                                if(!empty($pbl['strength'])) $allStrengths = array_merge($allStrengths, $pbl['strength']);
                            }
                        }
                        $topPersonalities = collect($allPersonalities)->countBy()->sortDesc()->keys()->take(4);
                        $topIntelligences = collect($allIntelligences)->countBy()->sortDesc()->keys()->take(5);
                        $topStrengths = collect($allStrengths)->countBy()->sortDesc()->keys()->take(6);
                    @endphp

                    @if($topPersonalities->count() > 0 || $topIntelligences->count() > 0 || $topStrengths->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            @if($topPersonalities->count() > 0)
                            <div class="bg-white/80 dark:bg-gray-800/80 p-5 rounded-2xl border border-amber-100 dark:border-amber-900/30 shadow-inner">
                                <h4 class="text-[9px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-3">Dominan Personality</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($topPersonalities as $trait)
                                        <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 rounded-full text-[9px] font-bold">{{ $trait }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($topIntelligences->count() > 0)
                            <div class="bg-white/80 dark:bg-gray-800/80 p-5 rounded-2xl border border-purple-100 dark:border-purple-900/30 shadow-inner">
                                <h4 class="text-[9px] font-black text-purple-600 dark:text-purple-500 uppercase tracking-widest mb-3">Multiple Intelligence</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($topIntelligences as $trait)
                                        <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 rounded-full text-[9px] font-bold">{{ $trait }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($topStrengths->count() > 0)
                            <div class="bg-white/80 dark:bg-gray-800/80 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-900/30 shadow-inner">
                                <h4 class="text-[9px] font-black text-indigo-600 dark:text-indigo-500 uppercase tracking-widest mb-3">Potential Strength</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($topStrengths as $trait)
                                        <span class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-300 rounded-full text-[9px] font-bold">{{ $trait }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Probing Timeline Feed -->
                <div class="divide-y divide-purple-100/50 dark:divide-purple-900/40">
                    @forelse($probingReports as $pr)
                        @foreach($pr->probingActivities as $activity)
                            <div class="p-6 sm:p-8 hover:bg-slate-50/40 dark:hover:bg-slate-900/10 transition-all flex flex-col sm:flex-row gap-5 sm:gap-8">
                                <div class="flex-shrink-0 text-left sm:text-center flex sm:flex-col items-center sm:items-center gap-2 sm:gap-0 bg-purple-50/30 dark:bg-purple-950/20 p-4 rounded-2xl border border-purple-100/30 shadow-inner self-start">
                                    <div class="text-2xl sm:text-3xl font-black text-gray-955 dark:text-white leading-none">{{ $activity->created_at->format('d') }}</div>
                                    <div class="text-[8px] sm:text-[10px] font-black text-purple-500 dark:text-purple-400 uppercase tracking-wider sm:mt-1.5">{{ $activity->created_at->format('M') }}</div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-3">
                                        <h4 class="text-base sm:text-lg font-black text-gray-900 dark:text-white leading-tight font-display">{{ $activity->activity_name }}</h4>
                                        <span class="px-3 py-1 bg-purple-50 dark:bg-purple-950 text-purple-650 dark:text-purple-450 text-[8px] font-black uppercase tracking-widest rounded-full self-start border border-purple-105/10">
                                            {{ $pr->period }}
                                        </span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-semibold italic">"{{ $activity->description }}"</p>

                                    @php $pbl = is_array($activity->pbl_data) ? $activity->pbl_data : json_decode($activity->pbl_data, true) ?? []; @endphp
                                    <div class="flex flex-wrap gap-1.5 mt-4">
                                        @foreach(['personality', 'intelligence', 'strength'] as $traitKey)
                                            @foreach($pbl[$traitKey] ?? [] as $val)
                                                <span class="px-2.5 py-1 bg-slate-100/50 dark:bg-gray-900 text-slate-650 dark:text-gray-400 rounded-xl text-[9px] font-black uppercase tracking-wider border border-slate-200/30 shadow-sm">
                                                    {{ $val }}
                                                </span>
                                            @endforeach
                                        @endforeach
                                    </div>
                                    <div class="mt-5">
                                        <a href="{{ route('student.reports.show', $pr) }}" class="inline-flex items-center gap-1.5 px-4.5 py-2 bg-purple-50 dark:bg-purple-950/40 text-purple-650 dark:text-purple-400 font-black text-[9px] uppercase tracking-widest rounded-xl hover:bg-purple-600 hover:text-white transition duration-200 shadow-sm">
                                            Detail Proyek
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @empty
                        <div class="p-16 text-center text-gray-400 italic">
                            <svg class="w-12 h-12 text-purple-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm font-bold text-gray-500">Belum ada catatan aktivitas pembelajaran berbasis proyek (PBL) diterbitkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 4. All Reports Section -->
        <div x-show="activeSection === 'all'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
            @php
                $allReportsOrdered = collect($academicReports)->merge($diniyahReports)->merge($ibadahReports)->merge($probingReports)->sortByDesc('created_at');
                $uniquePeriods = $allReportsOrdered->pluck('period')->unique()->filter()->values();
            @endphp

            <!-- Dropdown Filter Bar -->
            <div class="mb-8 flex justify-end">
                <div class="relative w-full sm:w-64">
                    <select x-model="selectedPeriod" class="w-full appearance-none bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700/60 text-gray-700 dark:text-gray-300 py-3 px-5 pr-10 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-black text-xs cursor-pointer">
                        <option value="">Semua Periode Akademik</option>
                        @foreach($uniquePeriods as $period)
                            <option value="{{ $period }}">{{ $period }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400">
                        <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Reports Grid Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($allReportsOrdered as $report)
                    <div x-show="selectedPeriod === '' || selectedPeriod === '{{ $report->period }}'" 
                          class="clay-card hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 group overflow-hidden flex flex-col justify-between" 
                          x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div class="p-3.5 bg-slate-50 dark:bg-gray-700 rounded-2xl text-slate-500 dark:text-slate-400 group-hover:bg-purple-650 group-hover:text-white transition-colors shadow-inner border border-slate-200/10">
                                    @if($report->category->name === 'Akademik')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    @elseif($report->category->name === 'Diniyah')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    @endif
                                </div>
                                <span class="px-3 py-1 bg-slate-50 dark:bg-gray-700 text-slate-555 dark:text-slate-400 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">{{ $report->created_at->format('M Y') }}</span>
                            </div>
                            
                            <h3 class="text-lg font-black text-gray-905 dark:text-white mb-1.5 line-clamp-1 group-hover:text-purple-650 transition-colors tracking-tight font-display">{{ $report->category->name }}</h3>
                            <p class="text-gray-400 dark:text-gray-500 font-bold text-xs mb-6 line-clamp-1">{{ $report->period }}</p>

                            <div class="flex items-center gap-3 p-3.5 bg-slate-100/50 dark:bg-gray-900/50 rounded-2xl shadow-inner border border-slate-200/20">
                                <div class="h-9 w-9 rounded-xl bg-purple-600 text-white flex items-center justify-center font-black text-xs uppercase shadow-sm">
                                    {{ substr($report->teacher->name ?? 'A', 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[8px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none mb-1">Guru Pendidik</p>
                                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300 truncate">{{ $report->teacher->name ?? 'Admin Sekolah' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 pb-6">
                            <a href="{{ route('student.reports.show', $report) }}" class="flex items-center justify-center w-full py-3.5 bg-slate-900 dark:bg-slate-800 text-white rounded-2xl font-black text-xs text-center hover:bg-purple-600 hover:text-white transition duration-200 active:scale-95 shadow-lg">
                                Detail Laporan
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('academicTrendChart').getContext('2d');
        const data = @json($academicTrend);
        
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.03)';
        const tickColor = isDark ? '#9ca3af' : '#4b5563';

        // Custom gradient for line fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(139, 92, 246, 0.25)');
        gradient.addColorStop(1, 'rgba(139, 92, 246, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.period),
                datasets: [{
                    label: 'Rata-Rata Nilai',
                    data: data.map(item => item.average),
                    borderColor: '#8b5cf6',
                    backgroundColor: gradient,
                    borderWidth: 5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#8b5cf6',
                    pointBorderWidth: 4,
                    pointRadius: 6,
                    pointHoverRadius: 9,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#ffffff',
                        titleFont: { size: 12, weight: 'bold', family: "'Outfit', 'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: '900', family: "'Outfit', 'Inter', sans-serif" },
                        padding: 14,
                        cornerRadius: 16,
                        displayColors: false,
                        borderWidth: 1,
                        borderColor: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 60,
                        max: 100,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { 
                            color: tickColor,
                            font: { weight: 'bold', size: 10, family: "'Outfit', 'Inter', sans-serif" }, 
                            stepSize: 10 
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: tickColor,
                            font: { weight: 'bold', size: 10, family: "'Outfit', 'Inter', sans-serif" } 
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
