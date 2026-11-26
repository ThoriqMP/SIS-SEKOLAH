@extends('layouts.app')

@section('content')
<div class="pt-2.5 sm:pt-6 pb-28 sm:pb-12 px-3 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-sky-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-sky-950/20 min-h-screen">
    <!-- Welcome Header -->
    <div class="mb-3 sm:mb-8" id="tour-welcome">
        <!-- Mobile Compact Hero Card (Above-The-Fold / Zero Scroll) -->
        <div class="sm:hidden bg-gradient-to-r from-sky-700 via-sky-800 to-sky-800 rounded-2xl p-3.5 text-white shadow-lg shadow-sky-950/20 relative overflow-hidden">
            <div class="relative z-10 flex items-center justify-between gap-2 mb-2">
                <span class="px-2.5 py-0.5 bg-white/15 backdrop-blur-md border border-white/20 rounded-full text-[9px] font-black uppercase tracking-wider text-sky-200">
                    SIS SEKOLAH Portal
                </span>
                <div class="flex items-center gap-1.5">
                    <span class="bg-white/10 px-2 py-0.5 rounded-md text-[9px] font-black uppercase text-sky-200 border border-white/10">
                        {{ __('messages.semester_even') }}
                    </span>
                    <span class="bg-sky-500/25 px-2 py-0.5 rounded-md text-[9px] font-black uppercase text-sky-200 border border-sky-400/25 flex items-center gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        {{ __('messages.active') }}
                    </span>
                </div>
            </div>
            <div class="relative z-10 flex items-center justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <h1 class="text-base font-black text-white tracking-tight truncate leading-tight">
                        {{ __('messages.welcome') }}, {{ $student->nama_siswa }}! 👋
                    </h1>
                    <p class="text-[11px] text-sky-100 font-semibold opacity-90 truncate mt-0.5">
                        NIS: {{ $student->nis ?: '-' }} &bull; {{ __('messages.class') }}: {{ $student->class }}
                    </p>
                </div>
                <button onclick="window.startDashboardTour()" class="bg-white/95 text-sky-950 hover:bg-white font-extrabold px-2.5 py-1.5 rounded-xl text-[10px] transition-all shadow-xs active:scale-95 flex items-center gap-1 shrink-0 border border-white/20">
                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Tour</span>
                </button>
            </div>
            <!-- Decorative accents -->
            <div class="absolute -top-16 -right-16 w-36 h-36 bg-sky-500/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-sky-500/25 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <!-- Desktop Spacious Hero Card -->
        <div class="hidden sm:block bg-gradient-to-r from-sky-700 via-sky-800 to-sky-800 rounded-[32px] sm:rounded-[40px] p-6 sm:p-10 text-white shadow-xl shadow-sky-950/25 relative overflow-hidden group">
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <span class="px-3.5 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[10px] sm:text-xs font-black uppercase tracking-widest text-sky-200">
                            SIS SEKOLAH Portal
                        </span>
                        <h1 class="text-2xl sm:text-5xl font-black mt-4 mb-2 tracking-tight leading-tight">
                            {{ __('messages.welcome') }}, {{ $student->nama_siswa }}! 👋
                        </h1>
                        <p class="text-sky-100 text-sm sm:text-lg opacity-90 font-medium font-display">
                            NIS: {{ $student->nis }} &bull; {{ __('messages.class') }}: {{ $student->class }}
                        </p>
                    </div>
                    <button onclick="window.startDashboardTour()" class="bg-white text-sky-900 hover:bg-sky-50 font-extrabold px-5 py-3 rounded-2xl text-xs sm:text-sm transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2 border border-white/10 shrink-0 self-start md:self-center">
                        <svg class="w-4 h-4 text-sky-600 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('messages.dashboard_tour') }}
                    </button>
                </div>
                <div class="mt-6 flex flex-wrap gap-2.5">
                    <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl text-xs font-black border border-white/10 tracking-wider uppercase text-sky-200">
                        {{ __('messages.semester_even') }}
                    </div>
                    <div class="bg-sky-500/20 backdrop-blur-md px-4 py-2 rounded-xl text-xs font-black border border-sky-400/20 tracking-wider uppercase text-sky-300 flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-sky-400 animate-ping"></span>
                        {{ __('messages.status') }}: {{ __('messages.active') }}
                    </div>
                </div>
            </div>
            <!-- Decorative flowing background bubbles -->
            <div class="absolute -top-32 -right-32 w-80 h-80 bg-sky-500/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-sky-500/25 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
        </div>
    </div>

    <!-- Trait Summary Section (Peta Potensi & Karakter) -->
    @php
        $allPersonalities = [];
        $allIntelligences = [];
        $allStrengths = [];
        // Only consider Project Based Learning reports
        foreach($allReports->filter(fn($r) => str_contains(strtolower($r->category->name ?? ''), 'project based') || str_contains(strtolower($r->category->name ?? ''), 'probing') || strtolower($r->category->name ?? '') === 'pbl') as $pr) {
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

        // Academic and stats helpers
        $latestAcademic = $academicReports->sortByDesc('created_at')->first();
        $avgLatest = $latestAcademic ? $latestAcademic->grades->avg('score') : null;
        $predicate = $avgLatest !== null ? \App\Helpers\ReportHelper::calculatePredicate($avgLatest) : '-';
        try {
            $totalSurahs = $latestReports->isNotEmpty()
                ? \App\Models\DiniyahReport::whereIn('student_report_id', $latestReports->pluck('id'))->count()
                : 0;
        } catch (\Throwable $e) {
            $totalSurahs = 0;
        }
    @endphp

    <div class="mb-3 sm:mb-10" id="tour-potensi">
        <div class="flex items-center justify-between gap-2 mb-2 sm:mb-6">
            <div class="flex items-center gap-2 sm:gap-3.5">
                <div class="h-7 w-7 sm:h-12 sm:w-12 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-white shadow-md shadow-sky-500/20 shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                <div>
                    <h3 class="text-xs sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">{{ __('messages.potential_character_map') }}</h3>
                    <p class="text-[9px] sm:text-xs font-black text-sky-600 dark:text-sky-400 uppercase tracking-widest mt-0.5">{{ __('messages.pbl_analysis_desc') }}</p>
                </div>
            </div>
            <!-- Mobile swipe hint -->
            @if($topPersonalities->count() > 0 || $topIntelligences->count() > 0 || $topStrengths->count() > 0)
                <span class="sm:hidden text-[9px] font-extrabold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/50 px-2 py-0.5 rounded-full border border-sky-200/50 flex items-center gap-1">
                    <span>Geser</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </span>
            @endif
        </div>

        @if($topPersonalities->count() > 0 || $topIntelligences->count() > 0 || $topStrengths->count() > 0)
        <!-- Mobile Horizontal Swipeable Snap Cards (Tinggi ringkas ~95px, semua aspek dalam 1 baris geser) -->
        <div class="sm:hidden flex items-stretch gap-2.5 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-1 -mx-3 px-3">
            @if($topPersonalities->count() > 0)
            <div class="w-[78vw] max-w-[280px] shrink-0 snap-center bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-3 text-white shadow-md shadow-amber-500/20 relative overflow-hidden flex flex-col justify-between border border-white/20">
                <div class="relative z-10">
                    <h4 class="text-[10px] font-black uppercase tracking-wider mb-2 text-amber-100 flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 bg-amber-200 rounded-full animate-ping"></span>
                        {{ __('messages.dominant_personality') }}
                    </h4>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($topPersonalities as $trait)
                            <span class="px-2.5 py-1 bg-white/95 text-amber-950 rounded-xl text-[10px] font-black shadow-xs tracking-wide border border-white/40">{{ $trait }}</span>
                        @endforeach
                    </div>
                </div>
                <svg class="absolute -bottom-4 -right-4 w-20 h-20 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            @endif

            @if($topIntelligences->count() > 0)
            <div class="w-[78vw] max-w-[280px] shrink-0 snap-center bg-gradient-to-br from-sky-500 to-sky-600 rounded-2xl p-3 text-white shadow-md shadow-sky-500/20 relative overflow-hidden flex flex-col justify-between border border-white/20">
                <div class="relative z-10">
                    <h4 class="text-[10px] font-black uppercase tracking-wider mb-2 text-sky-100 flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 bg-sky-200 rounded-full animate-ping"></span>
                        {{ __('messages.multiple_intelligence') }}
                    </h4>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($topIntelligences as $trait)
                            <span class="px-2.5 py-1 bg-white/95 text-sky-950 rounded-xl text-[10px] font-black shadow-xs tracking-wide border border-white/40">{{ $trait }}</span>
                        @endforeach
                    </div>
                </div>
                <svg class="absolute -bottom-4 -right-4 w-20 h-20 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
            </div>
            @endif

            @if($topStrengths->count() > 0)
            <div class="w-[78vw] max-w-[280px] shrink-0 snap-center bg-gradient-to-br from-sky-500 to-fuchsia-600 rounded-2xl p-3 text-white shadow-md shadow-sky-500/20 relative overflow-hidden flex flex-col justify-between border border-white/20">
                <div class="relative z-10">
                    <h4 class="text-[10px] font-black uppercase tracking-wider mb-2 text-sky-100 flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 bg-sky-200 rounded-full animate-ping"></span>
                        {{ __('messages.potential_strength') }}
                    </h4>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($topStrengths as $trait)
                            <span class="px-2.5 py-1 bg-white/95 text-fuchsia-950 rounded-xl text-[10px] font-black shadow-xs tracking-wide border border-white/40">{{ $trait }}</span>
                        @endforeach
                    </div>
                </div>
                <svg class="absolute -bottom-4 -right-4 w-20 h-20 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            @endif
        </div>

        <!-- Desktop Grid View (3 Columns) -->
        <div class="hidden sm:grid sm:grid-cols-3 gap-6 sm:gap-8">
            @if($topPersonalities->count() > 0)
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-[32px] p-6 sm:p-8 text-white shadow-xl shadow-amber-500/25 relative overflow-hidden group hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 border border-white/20">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <h4 class="text-[11px] font-black uppercase tracking-widest mb-6 text-amber-100 flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 bg-amber-200 rounded-full animate-ping"></span>
                            {{ __('messages.dominant_personality') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($topPersonalities as $trait)
                                <span class="px-3 py-2 bg-white/95 text-amber-950 rounded-2xl text-[11px] font-black shadow-sm tracking-wide border border-white/40">{{ $trait }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Background decoration icon -->
                <svg class="absolute -bottom-6 -right-6 w-36 h-36 text-white/10 transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
            </div>
            @endif

            @if($topIntelligences->count() > 0)
            <div class="bg-gradient-to-br from-sky-500 to-sky-600 rounded-[32px] p-6 sm:p-8 text-white shadow-xl shadow-sky-500/25 relative overflow-hidden group hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 border border-white/20">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <h4 class="text-[11px] font-black uppercase tracking-widest mb-6 text-sky-100 flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 bg-sky-200 rounded-full animate-ping"></span>
                            {{ __('messages.multiple_intelligence') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($topIntelligences as $trait)
                                <span class="px-3 py-2 bg-white/95 text-sky-950 rounded-2xl text-[11px] font-black shadow-sm tracking-wide border border-white/40">{{ $trait }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Background decoration icon -->
                <svg class="absolute -bottom-6 -right-6 w-36 h-36 text-white/10 transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
            </div>
            @endif

            @if($topStrengths->count() > 0)
            <div class="bg-gradient-to-br from-sky-500 to-fuchsia-600 rounded-[32px] p-6 sm:p-8 text-white shadow-xl shadow-sky-500/25 relative overflow-hidden group hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 border border-white/20">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <h4 class="text-[11px] font-black uppercase tracking-widest mb-6 text-sky-100 flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 bg-sky-200 rounded-full animate-ping"></span>
                            {{ __('messages.potential_strength') }}
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($topStrengths as $trait)
                                <span class="px-3 py-2 bg-white/95 text-fuchsia-950 rounded-2xl text-[11px] font-black shadow-sm tracking-wide border border-white/40">{{ $trait }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Background decoration icon -->
                <svg class="absolute -bottom-6 -right-6 w-36 h-36 text-white/10 transform group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
            </div>
            @endif
        </div>
        @else
        <!-- Beautiful Premium Empty State for Potentials -->
        <div class="clay-card rounded-2xl sm:rounded-[32px] p-4 sm:p-10 border border-white/40 dark:border-gray-800/40 shadow-md sm:shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center gap-4 sm:gap-8 group">
            <div class="h-10 w-10 sm:h-20 sm:w-20 bg-gradient-to-br from-sky-100 to-sky-100 dark:from-sky-900/30 dark:to-sky-900/30 rounded-xl sm:rounded-[24px] flex items-center justify-center text-sky-600 dark:text-sky-400 shadow-inner shrink-0 relative group-hover:scale-105 transition-transform duration-500">
                <svg class="w-5 h-5 sm:w-10 sm:h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <div class="text-center md:text-left space-y-1 sm:space-y-2 max-w-xl">
                <h4 class="text-xs sm:text-lg font-black text-gray-800 dark:text-white uppercase tracking-tight">{{ __('messages.potential_not_available') }}</h4>
                <p class="text-gray-500 dark:text-gray-400 text-[10px] sm:text-sm leading-relaxed font-semibold">
                    {{ __('messages.potential_not_available_desc') }}
                </p>
            </div>
        </div>
        @endif
    </div>

    <!-- Stats Overview Counters -->
    <div id="tour-stats" class="mb-3 sm:mb-12">
        <!-- Mobile 4-Column Micro Stats Strip (Above-The-Fold / Zero Scroll) -->
        <div class="sm:hidden grid grid-cols-4 gap-1.5">
            <!-- 1. Nilai Rata-rata -->
            <div class="p-2 rounded-xl bg-white dark:bg-gray-800 border border-slate-200/60 dark:border-gray-700/60 shadow-2xs text-center flex flex-col items-center justify-center">
                <span class="text-[8px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tight truncate max-w-full leading-none mb-1">
                    {{ __('messages.average_score') }}
                </span>
                <p class="text-sm font-black text-sky-700 dark:text-sky-300 tracking-tight leading-none">
                    {{ $averageScore !== null ? round($averageScore) : '-' }}
                </p>
            </div>

            <!-- 2. Predikat -->
            <div class="p-2 rounded-xl bg-white dark:bg-gray-800 border border-slate-200/60 dark:border-gray-700/60 shadow-2xs text-center flex flex-col items-center justify-center">
                <span class="text-[8px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tight truncate max-w-full leading-none mb-1">
                    {{ __('messages.current_predicate') }}
                </span>
                <p class="text-sm font-black text-amber-600 dark:text-amber-400 tracking-tight leading-none">
                    {{ $predicate }}
                </p>
            </div>

            <!-- 3. SPP Status -->
            <div class="p-2 rounded-xl bg-white dark:bg-gray-800 border border-slate-200/60 dark:border-gray-700/60 shadow-2xs text-center flex flex-col items-center justify-center">
                <span class="text-[8px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tight truncate max-w-full leading-none mb-1">
                    {{ __('messages.spp_fees') }}
                </span>
                @if($latestInvoice)
                    <p class="text-[10px] font-black {{ $latestInvoice->status === 'paid' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} tracking-tight leading-none truncate max-w-full">
                        {{ $latestInvoice->status === 'paid' ? __('messages.paid') : 'Rp '.number_format($latestInvoice->grand_total, 0, ',', '.') }}
                    </p>
                @else
                    <p class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 leading-none">Lunas</p>
                @endif
            </div>

            <!-- 4. Tahfidz Quran -->
            <div class="p-2 rounded-xl bg-white dark:bg-gray-800 border border-slate-200/60 dark:border-gray-700/60 shadow-2xs text-center flex flex-col items-center justify-center">
                <span class="text-[8px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tight truncate max-w-full leading-none mb-1">
                    Tahfidz
                </span>
                <p class="text-sm font-black text-sky-700 dark:text-sky-300 tracking-tight leading-none">
                    {{ $totalSurahs }} <span class="text-[8px] font-semibold text-gray-400">Surah</span>
                </p>
            </div>
        </div>

        <!-- Desktop Spacious 4-Card Grid -->
        <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="clay-card p-5 sm:p-6 hover:shadow-2xl hover:scale-[1.02] hover:border-sky-500/30 transition-all duration-300 group flex items-center gap-4">
                <div class="h-12 w-12 bg-sky-50 dark:bg-sky-950/40 rounded-2xl flex items-center justify-center text-sky-600 dark:text-sky-400 flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[9px] sm:text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest truncate">{{ __('messages.average_score') }}</p>
                    <p class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ $averageScore !== null ? round($averageScore) : '-' }}</p>
                </div>
            </div>
            <div class="clay-card p-5 sm:p-6 hover:shadow-2xl hover:scale-[1.02] hover:border-sky-500/30 transition-all duration-300 group flex items-center gap-4">
                <div class="h-12 w-12 bg-sky-50 dark:bg-sky-950/40 rounded-2xl flex items-center justify-center text-sky-650 dark:text-sky-400 flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[9px] sm:text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest truncate">{{ __('messages.spp_fees') }}</p>
                    <p class="text-lg sm:text-xl font-black text-gray-900 dark:text-white tracking-tight truncate">Rp {{ number_format($latestInvoice->grand_total ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="clay-card p-5 sm:p-6 hover:shadow-2xl hover:scale-[1.02] hover:border-sky-500/30 transition-all duration-300 group flex items-center gap-4">
                <div class="h-12 w-12 bg-sky-50 dark:bg-sky-950/40 rounded-2xl flex items-center justify-center text-sky-600 dark:text-sky-400 flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[9px] sm:text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest truncate">Tahfidz Quran</p>
                    <p class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ $totalSurahs }} {{ __('messages.surah') }}</p>
                </div>
            </div>
            <div class="clay-card p-5 sm:p-6 hover:shadow-2xl hover:scale-[1.02] hover:border-sky-500/30 transition-all duration-300 group flex items-center gap-4">
                <div class="h-12 w-12 bg-sky-55 text-sky-600 dark:bg-sky-950/40 dark:text-sky-400 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform text-lg font-black">
                    🌟
                </div>
                <div class="min-w-0">
                    <p class="text-[9px] sm:text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest truncate">{{ __('messages.current_predicate') }}</p>
                    <p class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ $predicate }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Quick Action Tiles (Raport Siswa & Tagihan SPP di Atas Lipatan) -->
    <div class="sm:hidden grid grid-cols-2 gap-2 mb-4">
        <!-- Tile 1: Raport Siswa -->
        <div class="p-3 bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/70 dark:border-gray-700/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[9px] font-black uppercase tracking-wider text-sky-600 dark:text-sky-400 flex items-center gap-1">
                        <i class="fa-solid fa-graduation-cap text-[8px]"></i>
                        {{ __('messages.academic_progress') }}
                    </span>
                    <span class="text-[8px] text-gray-400 font-semibold">{{ $latestReports->first()?->created_at?->format('d/m') ?? '' }}</span>
                </div>
                <h5 class="text-xs font-black text-gray-900 dark:text-white truncate">{{ $latestReports->first()?->category->name ?? 'Belum ada raport' }}</h5>
                <p class="text-[9.5px] text-gray-400 font-semibold truncate">{{ $latestReports->first()?->period ?? 'Semester Aktif' }}</p>
            </div>
            <div class="mt-2.5 flex items-center gap-1.5">
                @if($latestReports->first())
                    <a href="{{ route('student.reports.show', $latestReports->first()) }}" class="flex-1 py-1.5 text-center bg-slate-900 dark:bg-slate-700 text-white rounded-xl text-[10px] font-extrabold hover:bg-sky-950 transition active:scale-95 shadow-2xs">
                        Lihat Raport
                    </a>
                    <a href="{{ route('student.reports.pdf', $latestReports->first()) }}" class="p-1.5 bg-sky-50 dark:bg-sky-950/40 text-sky-600 rounded-xl text-[10px] font-bold hover:bg-sky-100 transition active:scale-95" title="PDF">
                        <i class="fa-solid fa-file-pdf"></i>
                    </a>
                @else
                    <a href="{{ route('student.reports') }}" class="w-full py-1.5 text-center bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-200 rounded-xl text-[10px] font-extrabold">Semua Raport</a>
                @endif
            </div>
        </div>

        <!-- Tile 2: Status SPP & Pembayaran -->
        <div class="p-3 bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/70 dark:border-gray-700/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[9px] font-black uppercase tracking-wider text-sky-600 dark:text-sky-400 flex items-center gap-1">
                        <i class="fa-solid fa-receipt text-[8px]"></i>
                        {{ __('messages.spp_fees') }}
                    </span>
                    @if($latestInvoice)
                        <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase {{ $latestInvoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/50 dark:text-rose-300' }}">
                            {{ $latestInvoice->status === 'paid' ? __('messages.paid') : __('messages.unpaid') }}
                        </span>
                    @endif
                </div>
                @if($latestInvoice)
                    <p class="text-xs font-black text-gray-900 dark:text-white truncate">Rp {{ number_format($latestInvoice->grand_total, 0, ',', '.') }}</p>
                    <p class="text-[9.5px] text-gray-400 font-semibold truncate">{{ __('messages.due_date') }}: {{ $latestInvoice->jatuh_tempo ? $latestInvoice->jatuh_tempo->format('d M') : '-' }}</p>
                @else
                    <p class="text-xs font-black text-emerald-600 dark:text-emerald-400">Semua Lunas</p>
                    <p class="text-[9.5px] text-gray-400 font-semibold">Tidak ada tagihan</p>
                @endif
            </div>
            <div class="mt-2.5">
                @if($latestInvoice)
                    <a href="{{ route('student.invoices.show', $latestInvoice) }}" class="block w-full py-1.5 text-center {{ $latestInvoice->status === 'paid' ? 'bg-slate-900 dark:bg-slate-700' : 'bg-sky-950 dark:bg-sky-800' }} text-white rounded-xl text-[10px] font-extrabold hover:opacity-90 transition active:scale-95 shadow-2xs">
                        {{ $latestInvoice->status === 'paid' ? __('messages.view_details') : 'Bayar Sekarang' }}
                    </a>
                @else
                    <a href="{{ route('student.invoices') }}" class="block w-full py-1.5 text-center bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-200 rounded-xl text-[10px] font-extrabold">Riwayat SPP</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Dashboard Layout Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content (Left Column) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Raport Section -->
            <div id="tour-raport">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ __('messages.academic_progress') }}</h3>
                        <p class="text-xs font-semibold text-gray-400 mt-0.5">{{ __('messages.unread_academic_report') }}</p>
                    </div>
                    <a href="{{ route('student.reports') }}" class="text-sm font-black text-sky-600 hover:text-sky-700 flex items-center gap-1 hover:underline">
                        {{ __('messages.all_reports') }} &rarr;
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @forelse($latestReports as $report)
                        <div class="clay-card p-6 shadow-md hover:shadow-xl hover:border-sky-500/20 hover:-translate-y-1 duration-300 group flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <div class="p-3 bg-sky-50 dark:bg-sky-950/40 rounded-2xl text-sky-600 dark:text-sky-400 group-hover:bg-sky-600 group-hover:text-white transition-colors duration-300">
                                        @if($report->category->name === 'Akademik')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        @elseif($report->category->name === 'Diniyah')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        @endif
                                    </div>
                                    <span class="text-[9px] font-black text-gray-400 bg-gray-50 dark:bg-gray-800/80 px-3 py-1 rounded-full uppercase tracking-widest border border-slate-200/20 shadow-sm">{{ $report->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="mb-6">
                                    <h4 class="text-lg font-black text-gray-900 dark:text-white line-clamp-1 tracking-tight">{{ $report->category->name }}</h4>
                                    <p class="text-xs text-gray-400 font-bold mb-3">{{ $report->period }}</p>
                                    <div class="inline-flex items-center gap-2 p-2 bg-slate-100/50 dark:bg-slate-900/50 rounded-xl border border-slate-200/20">
                                        <div class="h-6 w-6 rounded-lg bg-sky-600 text-white flex items-center justify-center font-black text-[9px] uppercase shadow-sm">
                                            {{ substr($report->teacher->name ?? 'A', 0, 1) }}
                                        </div>
                                        <p class="text-[9px] font-black text-gray-500 dark:text-gray-400 tracking-wider truncate max-w-[120px]">{{ $report->teacher->name ?? __('messages.homeroom_teacher') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-auto">
                                <a href="{{ route('student.reports.show', $report) }}" class="flex-1 text-center py-3 bg-slate-900 dark:bg-slate-800 text-white rounded-xl font-black text-xs hover:bg-sky-600 hover:text-white transition duration-200">{{ __('messages.action_detail') }}</a>
                                <a href="{{ route('student.reports.pdf', $report) }}" class="p-3 bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 rounded-xl hover:bg-sky-600 hover:text-white transition duration-200 flex items-center justify-center shadow-inner" title="Download PDF">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center clay-card border-2 border-dashed border-sky-200/50 dark:border-sky-800/40 text-gray-400 p-8 shadow-sm">
                            <svg class="w-12 h-12 text-sky-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="text-sm font-bold text-gray-500">{{ __('messages.unread_academic_report_empty') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Content (Right Column) -->
        <div class="space-y-8">
            <!-- Tagihan Widget -->
            <div class="clay-card p-6 sm:p-8 relative overflow-hidden group border border-white/40 dark:border-gray-800/40 shadow-md" id="tour-payment">
                <h3 class="text-lg sm:text-xl font-black text-gray-900 dark:text-white tracking-tight mb-6">{{ __('messages.financial_history') }}</h3>
                @if($latestInvoice)
                    <div class="space-y-6">
                        <div class="p-6 bg-slate-100/50 dark:bg-slate-900/50 rounded-2xl border border-slate-200/30 relative overflow-hidden">
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">{{ __('messages.invoice_amount') }}</p>
                            <p class="text-3xl font-black text-gray-950 dark:text-white tracking-tighter">Rp {{ number_format($latestInvoice->grand_total, 0, ',', '.') }}</p>
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $latestInvoice->status === 'paid' ? 'bg-sky-100 text-sky-700 dark:bg-sky-950 dark:text-sky-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-400' }}">
                                    {{ $latestInvoice->status === 'paid' ? __('messages.paid') : __('messages.unpaid') }}
                                </span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold">{{ __('messages.due_date') }}: {{ $latestInvoice->jatuh_tempo ? $latestInvoice->jatuh_tempo->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('student.invoices.show', $latestInvoice) }}" class="flex-1 py-3.5 bg-sky-600 text-white rounded-2xl font-black text-xs sm:text-sm text-center shadow-lg shadow-sky-600/20 hover:bg-sky-700 transition duration-200 active:scale-95">
                                {{ __('messages.view_details') }}
                            </a>
                            <a href="{{ route('student.invoices.pdf', $latestInvoice) }}" class="px-5 py-3.5 bg-slate-150 dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl font-black text-center hover:bg-slate-200 dark:hover:bg-gray-650 transition duration-200 active:scale-95 flex items-center justify-center shadow-inner" title="Download PDF">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                        </div>
                    </div>
                @else
                    <div class="py-10 text-center">
                        <div class="h-16 w-16 bg-sky-50 dark:bg-sky-950/40 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-8 h-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-sm font-black text-sky-600 dark:text-sky-400">{{ __('messages.awesome_payment') }}</p>
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 mt-1">{{ __('messages.spp_fees_all_paid') }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Diskusi Terbaru Widget -->
            <div class="clay-card p-6 sm:p-8 relative overflow-hidden group border border-white/40 dark:border-gray-800/40 shadow-md">
                <h3 class="text-lg sm:text-xl font-black text-gray-900 dark:text-white tracking-tight mb-6">{{ __('messages.recent_discussions') }}</h3>
                @if(isset($latestDiscussions) && !$latestDiscussions->isEmpty())
                    <div class="space-y-4">
                        @foreach($latestDiscussions as $comment)
                            @php
                                $isStudent = $comment->user->role === 'student';
                                $initials = strtoupper(substr($comment->user->name, 0, 2));
                                $avatarGrad = $isStudent ? 'from-amber-400 to-orange-500' : 'from-sky-500 to-sky-600';
                                
                                $badgeClass = 'bg-slate-100 text-slate-700';
                                if ($comment->user->role === 'guru') {
                                    $badgeClass = 'bg-sky-100 text-sky-800 border border-sky-200/50';
                                } elseif ($comment->user->role === 'admin') {
                                    $badgeClass = 'bg-rose-100 text-rose-800 border border-rose-200/50';
                                } elseif ($comment->user->role === 'pimpinan') {
                                    $badgeClass = 'bg-sky-100 text-sky-800 border border-sky-200/50';
                                }
                            @endphp
                            <a href="{{ route('student.lessons.show', $comment->lesson_id) }}?tab=diskusi" class="block p-3.5 bg-slate-50/70 hover:bg-slate-100/70 dark:bg-slate-900/40 dark:hover:bg-slate-800/50 rounded-2xl border border-slate-150/50 dark:border-slate-800/60 transition group/item">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-black text-white shrink-0 bg-gradient-to-br {{ $avatarGrad }}">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-1.5">
                                            <span class="text-xs font-extrabold text-gray-800 dark:text-gray-200 truncate">{{ $comment->user->name }}</span>
                                            <span class="text-[8px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded shrink-0 {{ $badgeClass }} scale-90">{{ $comment->user->role }}</span>
                                        </div>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium truncate mt-1">
                                            {{ $comment->content }}
                                        </p>
                                        <div class="flex items-center justify-between gap-2 mt-2 pt-1.5 border-t border-slate-150/40 dark:border-slate-850/40">
                                            <span class="text-[9px] font-bold text-sky-600 dark:text-sky-400 truncate max-w-[120px] group-hover/item:underline">📚 {{ $comment->lesson->title }}</span>
                                            <span class="text-[9px] text-gray-400 dark:text-gray-500 font-semibold shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-gray-400">
                        <span class="text-3xl">💬</span>
                        <p class="text-xs font-bold text-gray-400 dark:text-gray-500 mt-2">{{ __('messages.no_discussions') }}</p>
                    </div>
                @endif
            </div>

            <!-- Quick Links / Help -->
            <div class="bg-gradient-to-br from-sky-800 via-sky-900 to-sky-950 rounded-[32px] p-6 sm:p-8 text-white shadow-xl relative overflow-hidden group" id="tour-contact">
                <div class="relative z-10">
                    <h3 class="text-lg sm:text-xl font-black mb-2 text-white">{{ __('messages.contact_homeroom_teacher') }}</h3>
                    <p class="text-sky-200 text-xs leading-relaxed mb-6 font-semibold">{{ __('messages.contact_homeroom_desc') }}</p>
                    <button @click="$dispatch('toggle-admin-chat')" class="w-full py-4 bg-white text-sky-950 rounded-2xl font-black text-xs sm:text-sm hover:bg-sky-50 transition duration-200 active:scale-95 shadow-lg">
                        {{ __('messages.contact_admin') }}
                    </button>
                </div>
                <!-- Neon accents background -->
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-sky-500/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
                <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-sky-500/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
            </div></div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
<style>
    .driver-popover {
        border-radius: 28px !important;
        padding: 24px !important;
        font-family: 'Outfit', 'Inter', sans-serif !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
    }
    .driver-popover-title {
        font-size: 20px !important;
        font-weight: 900 !important;
        color: #6d28d9 !important; /* purple */
        margin-bottom: 8px !important;
        letter-spacing: -0.025em !important;
    }
    .dark .driver-popover {
        background-color: #111827 !important; /* deep black-gray */
        color: #f3f4f6 !important;
    }
    .dark .driver-popover-title {
        color: #a78bfa !important;
    }
    .dark .driver-popover-description {
        color: #9ca3af !important;
    }
    .driver-popover-next-btn, .driver-popover-prev-btn {
        border-radius: 12px !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        font-size: 10px !important;
        letter-spacing: 0.05em !important;
        padding: 8px 16px !important;
        background-color: #7c3aed !important;
        color: #ffffff !important;
        border: none !important;
        text-shadow: none !important;
    }
    .driver-popover-prev-btn {
        background-color: #f3f4f6 !important;
        color: #1f2937 !important;
    }
    .dark .driver-popover-prev-btn {
        background-color: #374151 !important;
        color: #f3f4f6 !important;
    }
    .driver-popover-progress-text {
        font-weight: 900 !important;
        font-size: 11px !important;
        color: #9ca3af !important;
    }
    .driver-active-element {
        pointer-events: none !important;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const driver = window.driver.js.driver;
        const navTarget = window.innerWidth >= 1024 ? '#tour-desktop-nav' : '#tour-mobile-nav';

        const tourDriver = driver({
            showProgress: true,
            doneBtnText: '{{ __('messages.done') }}',
            closeBtnText: '{{ __('messages.skip') }}',
            nextBtnText: '{{ __('messages.next_arrow') }}',
            prevBtnText: '{{ __('messages.back_arrow') }}',
            allowClose: false,
            steps: [
                {
                    element: navTarget,
                    popover: {
                        title: '{{ __('messages.tour_nav_title') }}',
                        description: '{{ __('messages.tour_nav_desc') }}',
                        side: window.innerWidth >= 1024 ? "right" : "top", align: 'start'
                    }
                },
                {
                    element: '#tour-welcome',
                    popover: {
                        title: '{{ __('messages.tour_welcome_title') }}',
                        description: '{{ __('messages.tour_welcome_desc') }}',
                        side: "bottom", align: 'start'
                    }
                },
                {
                    element: '#tour-potensi',
                    popover: {
                        title: '{{ __('messages.tour_potensi_title') }}',
                        description: '{{ __('messages.tour_potensi_desc') }}',
                        side: "bottom", align: 'start'
                    }
                },
                {
                    element: '#tour-stats',
                    popover: {
                        title: '{{ __('messages.tour_stats_title') }}',
                        description: '{{ __('messages.tour_stats_desc') }}',
                        side: "bottom", align: 'start'
                    }
                },
                {
                    element: '#tour-raport',
                    popover: {
                        title: '{{ __('messages.tour_raport_title') }}',
                        description: `{!! __('messages.tour_raport_desc') !!}`,
                        side: "top", align: 'start'
                    }
                },
                {
                    element: '#tour-payment',
                    popover: {
                        title: '{{ __('messages.tour_payment_title') }}',
                        description: '{{ __('messages.tour_payment_desc') }}',
                        side: "left", align: 'start'
                    }
                },
                {
                    element: '#tour-contact',
                    popover: {
                        title: '{{ __('messages.tour_help_title') }}',
                        description: `{!! __('messages.tour_help_desc') !!}`,
                        side: "top", align: 'center'
                    }
                }
            ]
        });

        window.startDashboardTour = function() {
            tourDriver.drive();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    });
</script>
@endpush
@endsection
