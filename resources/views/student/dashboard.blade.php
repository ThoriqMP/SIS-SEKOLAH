@extends('layouts.app')

@section('title', 'Dashboard Siswa - SIS SEKOLAH')

@section('content')
@php
    $allPersonalities = [];
    $allIntelligences = [];
    $allStrengths = [];
    foreach ($allReports->filter(fn($r) => str_contains(strtolower($r->category->name ?? ''), 'project based') || str_contains(strtolower($r->category->name ?? ''), 'probing') || strtolower($r->category->name ?? '') === 'pbl') as $pr) {
        foreach ($pr->probingActivities as $activity) {
            $pbl = is_array($activity->pbl_data) ? $activity->pbl_data : (json_decode($activity->pbl_data, true) ?? []);
            if (!empty($pbl['personality'])) $allPersonalities = array_merge($allPersonalities, $pbl['personality']);
            if (!empty($pbl['intelligence'])) $allIntelligences = array_merge($allIntelligences, $pbl['intelligence']);
            if (!empty($pbl['strength'])) $allStrengths = array_merge($allStrengths, $pbl['strength']);
        }
    }
    $topPersonality = !empty($allPersonalities) ? array_key_first(array_count_values($allPersonalities)) : null;
    $topIntelligence = !empty($allIntelligences) ? array_key_first(array_count_values($allIntelligences)) : null;
    $topStrength = !empty($allStrengths) ? array_key_first(array_count_values($allStrengths)) : null;
    $hasPotensi = $topPersonality || $topIntelligence || $topStrength;

    $predicate = $averageScore !== null
        ? (\App\Helpers\ReportHelper::calculatePredicate((float) $averageScore))
        : '-';
@endphp

<div class="p-4 sm:p-5 lg:p-6 max-w-[1400px] mx-auto space-y-4">

    {{-- ═══════════════════════════════════════════════════════════
         ROW 1: Welcome Banner (2 cols) + Peta Potensi & Karakter (1 col)
    ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Welcome Banner --}}
        <div class="lg:col-span-2 relative overflow-hidden rounded-2xl bg-gradient-to-br from-sky-700 via-sky-600 to-cyan-700 p-5 sm:p-6 text-white shadow-md shadow-sky-600/20 flex flex-col justify-between">
            <div class="absolute -top-10 -right-10 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/15 border border-white/20 text-[10px] font-extrabold uppercase tracking-widest text-sky-100">
                            <i class="fa-solid fa-graduation-cap text-[10px]"></i>
                            SIS SEKOLAH Portal
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full bg-white/10 text-[10px] font-bold uppercase text-sky-100">
                            {{ __('messages.semester_even') }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-[10px] font-bold uppercase text-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
                            {{ __('messages.active') }}
                        </span>
                    </div>

                    <h1 class="mt-2.5 text-xl sm:text-2xl font-black tracking-tight">
                        {{ __('messages.welcome') }}, {{ $student->nama_siswa }}!
                    </h1>
                    <p class="text-xs sm:text-sm text-sky-100 mt-0.5">
                        NIS: <span class="font-bold text-white">{{ $student->nis ?: '-' }}</span>
                        &bull; {{ __('messages.class') }}: <span class="font-bold text-white">{{ $student->class ?: '-' }}</span>
                    </p>
                </div>
            </div>

            {{-- Quick Action Pills --}}
            <div class="relative z-10 mt-4 flex flex-wrap gap-2">
                <a href="{{ route('student.activities.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25 border border-white/15 text-xs font-semibold text-white transition">
                    <i class="fa-solid fa-pen-to-square text-sky-200"></i> Tugas &amp; Ujian
                </a>
                <a href="{{ route('student.lessons.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25 border border-white/15 text-xs font-semibold text-white transition">
                    <i class="fa-solid fa-book-open text-sky-200"></i> Ruang Belajar
                </a>
                <a href="{{ route('student.video-conferences.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25 border border-white/15 text-xs font-semibold text-white transition">
                    <i class="fa-solid fa-video text-sky-200"></i> Kelas Video
                </a>
                <a href="{{ route('student.attendances.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25 border border-white/15 text-xs font-semibold text-white transition">
                    <i class="fa-solid fa-clipboard-check text-sky-200"></i> Absensi
                </a>
            </div>
        </div>

        {{-- Peta Potensi & Karakter Compact Card --}}
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-xs p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-lightbulb text-sm"></i>
                    </span>
                    <div>
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">{{ __('messages.potential_character_map') }}</h2>
                        <p class="text-[10px] text-slate-400">Analisis Project Based Learning</p>
                    </div>
                </div>
            </div>

            @if ($hasPotensi)
                <div class="mt-3 space-y-2 text-xs">
                    @if ($topPersonality)
                        <div class="flex items-center justify-between bg-sky-50/70 rounded-xl px-3 py-1.5">
                            <span class="text-slate-500 font-medium">{{ __('messages.dominant_personality') }}</span>
                            <span class="font-bold text-sky-700">{{ $topPersonality }}</span>
                        </div>
                    @endif
                    @if ($topIntelligence)
                        <div class="flex items-center justify-between bg-slate-50 rounded-xl px-3 py-1.5">
                            <span class="text-slate-500 font-medium">{{ __('messages.multiple_intelligence') }}</span>
                            <span class="font-bold text-slate-800">{{ $topIntelligence }}</span>
                        </div>
                    @endif
                    @if ($topStrength)
                        <div class="flex items-center justify-between bg-emerald-50/70 rounded-xl px-3 py-1.5">
                            <span class="text-slate-500 font-medium">{{ __('messages.potential_strength') }}</span>
                            <span class="font-bold text-emerald-700">{{ $topStrength }}</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="mt-3 bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center gap-3">
                    <i class="fa-solid fa-compass text-lg text-sky-400 shrink-0"></i>
                    <p class="text-[11px] text-slate-500 leading-snug">
                        {{ __('messages.potential_not_available_desc') }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         ROW 2: 4 Stat Bento Cards
    ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('messages.average_score') }}</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">{{ $averageScore !== null ? number_format($averageScore, 1) : '-' }}</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl {{ $latestInvoice && $latestInvoice->status !== 'paid' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center shrink-0">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('messages.spp_fees') }}</p>
                <p class="text-xl font-black {{ $latestInvoice && $latestInvoice->status !== 'paid' ? 'text-rose-600' : 'text-emerald-600' }} mt-0.5 truncate">
                    @if ($latestInvoice && $latestInvoice->status !== 'paid')
                        Rp {{ number_format($latestInvoice->grand_total, 0, ',', '.') }}
                    @else
                        Lunas
                    @endif
                </p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-award"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Rapor Terbit</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">{{ $allReports->count() }} Rapor</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-medal"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('messages.current_predicate') }}</p>
                <p class="text-xl font-black text-sky-700 mt-0.5 truncate">{{ $predicate }}</p>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         ROW 3: Progres Akademik + Riwayat Keuangan + Diskusi & Bantuan
    ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Progres Akademik --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-book-bookmark text-sky-600"></i>
                        {{ __('messages.academic_progress') }}
                    </h2>
                    <a href="{{ route('student.reports') }}" class="text-[11px] font-bold text-sky-600 hover:underline">
                        {{ __('messages.all_reports') }} <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if ($latestReports->isEmpty())
                    <div class="py-5 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <i class="fa-regular fa-file-lines text-xl text-slate-300"></i>
                        <p class="text-xs text-slate-400 mt-1.5">{{ __('messages.unread_academic_report_empty') }}</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach ($latestReports->take(3) as $rep)
                            <a href="{{ route('student.reports.show', $rep) }}"
                               class="flex items-center justify-between gap-3 p-2.5 rounded-xl bg-slate-50 hover:bg-sky-50 border border-slate-100 transition group">
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 group-hover:text-sky-700 truncate">
                                        {{ $rep->category->name ?? 'Rapor' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">{{ $rep->period ?? ($rep->semester ?? '-') }}</p>
                                </div>
                                <span class="shrink-0 text-[10px] font-extrabold px-2 py-1 rounded-lg bg-white border border-slate-200 text-sky-700">
                                    {{ $rep->grades->avg('score') ? number_format($rep->grades->avg('score'), 1) : 'Detail' }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Riwayat Keuangan --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-sky-600"></i>
                        {{ __('messages.financial_history') }}
                    </h2>
                    <a href="{{ route('student.invoices') }}" class="text-[11px] font-bold text-sky-600 hover:underline">
                        Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if ($latestInvoice)
                    <div class="p-3.5 rounded-xl border {{ $latestInvoice->status === 'paid' ? 'bg-emerald-50/50 border-emerald-200/70' : 'bg-rose-50/50 border-rose-200/70' }} space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-extrabold text-slate-800">{{ $latestInvoice->no_invoice }}</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase {{ $latestInvoice->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $latestInvoice->status === 'paid' ? __('messages.paid') : __('messages.unpaid') }}
                            </span>
                        </div>
                        <p class="text-lg font-black text-slate-900">
                            Rp {{ number_format($latestInvoice->grand_total, 0, ',', '.') }}
                        </p>
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-[10px] text-slate-500">
                                {{ __('messages.due_date') }}: {{ $latestInvoice->jatuh_tempo ? \Carbon\Carbon::parse($latestInvoice->jatuh_tempo)->format('d M Y') : '-' }}
                            </span>
                            <a href="{{ route('student.invoices.show', $latestInvoice) }}"
                               class="text-[11px] font-bold text-sky-700 hover:underline">
                                {{ __('messages.view_details') }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="py-5 text-center bg-emerald-50/40 rounded-xl border border-emerald-100">
                        <i class="fa-solid fa-circle-check text-xl text-emerald-500"></i>
                        <p class="text-xs font-bold text-emerald-700 mt-1.5">{{ __('messages.awesome_payment') }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Diskusi Terbaru & Wali Kelas --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between gap-3">
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-comments text-sky-600"></i>
                        {{ __('messages.recent_discussions') }}
                    </h2>
                    <a href="{{ route('student.lessons.index') }}" class="text-[11px] font-bold text-sky-600 hover:underline">
                        Ruang Belajar <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @if ($latestDiscussions->isEmpty())
                    <p class="text-xs text-slate-400 py-2">Belum ada diskusi materi terbaru.</p>
                @else
                    <div class="space-y-1.5">
                        @foreach ($latestDiscussions->take(2) as $comment)
                            <a href="{{ route('student.lessons.show', $comment->lesson_id) }}"
                               class="block p-2 rounded-xl bg-slate-50 hover:bg-sky-50 transition">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[11px] font-bold text-slate-800 truncate">{{ $comment->user->name ?? 'Pengguna' }}</span>
                                    <span class="text-[10px] font-semibold text-sky-600 truncate max-w-[120px] flex items-center gap-1">
                                        <i class="fa-solid fa-book-open text-[9px]"></i> {{ $comment->lesson->title ?? '-' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $comment->content }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Compact Homeroom / Help Bar --}}
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('messages.homeroom_teacher') }}</p>
                    <p class="text-xs font-bold text-slate-700 truncate">{{ __('messages.contact_homeroom_teacher') }}</p>
                </div>
                <a href="https://wa.me/6285155238654?text=Halo%2C%20saya%20siswa%20SIS%20SEKOLAH%20ingin%20bertanya."
                   target="_blank" rel="noopener noreferrer"
                   class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-sky-600 hover:bg-sky-700 text-white text-[11px] font-bold transition">
                    <i class="fa-brands fa-whatsapp"></i> Hubungi
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
