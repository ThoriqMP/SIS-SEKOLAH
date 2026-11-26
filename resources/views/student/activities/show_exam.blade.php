@extends('layouts.app')

@section('title', __('messages.exam_instructions'))

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-sky-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-sky-950/20 min-h-screen">
    <div class="max-w-xl mx-auto space-y-6">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('student.activities.index') }}" class="p-3 bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition active:scale-90 shrink-0">
                ← {{ __('messages.back') }}
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ __('messages.exam_instructions') }}</h1>
                <p class="text-[10px] sm:text-xs font-black text-sky-600 dark:text-sky-400 uppercase tracking-widest mt-0.5 font-display">{{ __('messages.exam_evaluation') }}</p>
            </div>
        </div>

        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-gray-150/40 dark:border-gray-800/40 shadow-xl space-y-6">
            <div class="text-center space-y-3">
                <span class="px-3.5 py-1 bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-sky-150/20">
                    {{ $exam->subject->name ?? __('messages.subject') }}
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ $exam->title }}</h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ __('messages.semester_label') }}: {{ $exam->period }}</p>
            </div>

            <!-- Details list -->
            <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-gray-200/50 space-y-3.5 text-sm font-semibold text-gray-700 dark:text-gray-300">
                <div class="flex justify-between items-center pb-2 border-b border-gray-200/40 dark:border-gray-800/40">
                    <span>⏱ {{ __('messages.exam_duration_label') }}:</span>
                    <span class="font-extrabold text-gray-950 dark:text-white">{{ $exam->duration }} {{ __('messages.minutes') }}</span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-200/40 dark:border-gray-800/40">
                    <span>📑 {{ __('messages.questions_count_label') }}:</span>
                    <span class="font-extrabold text-gray-950 dark:text-white">{{ __('messages.questions_suffix', ['count' => $exam->questions()->count()]) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>📅 {{ __('messages.access_due_date_label') }}:</span>
                    <span class="font-extrabold text-gray-950 dark:text-white">{{ $exam->due_date->format('d M Y H:i') }}</span>
                </div>
            </div>

            <!-- Important guidelines -->
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 text-amber-700 dark:text-amber-400 rounded-2xl text-xs font-bold space-y-2 leading-relaxed">
                <div class="uppercase tracking-widest text-[9px] font-black">{{ __('messages.important_before_starting') }}</div>
                <ul class="list-disc pl-4 space-y-1">
                    <li>{{ __('messages.guideline_realtime_timer') }}</li>
                    <li>{{ __('messages.guideline_close_page') }}</li>
                    <li>{{ __('messages.guideline_stable_internet') }}</li>
                </ul>
            </div>

            <!-- Action buttons -->
            <div class="pt-2">
                @if($attempt && $attempt->completed_at)
                    <a href="{{ route('student.activities.exam.result', $exam) }}" class="block w-full text-center py-4 bg-slate-900 dark:bg-slate-800 text-white font-extrabold rounded-2xl text-xs sm:text-sm shadow-md transition duration-200 active:scale-95">
                        {{ __('messages.view_exam_score_result') }}
                    </a>
                @elseif(now()->gt($exam->due_date))
                    <button disabled class="w-full py-4 bg-gray-150 text-gray-400 dark:bg-gray-800 dark:text-gray-600 rounded-2xl font-black text-xs sm:text-sm cursor-not-allowed">
                        {{ __('messages.exam_ended') }}
                    </button>
                @else
                    <a href="{{ route('student.activities.exam.take', $exam) }}" class="block w-full text-center py-4 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm shadow-lg shadow-sky-500/20 active:scale-95 transition-all">
                        {{ __('messages.start_exam_now') }}
                    </a>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
