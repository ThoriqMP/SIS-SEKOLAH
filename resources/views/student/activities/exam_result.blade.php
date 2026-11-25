@extends('layouts.app')

@section('title', __('messages.exam_score_result'))

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('student.activities.index') }}" class="p-3 bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition active:scale-90 shrink-0">
                ← {{ __('messages.back_to_activities') }}
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ __('messages.exam_score_result') }}</h1>
                <p class="text-[10px] sm:text-xs font-black text-purple-650 dark:text-purple-400 uppercase tracking-widest mt-0.5 font-display">{{ __('messages.test_evaluation') }}</p>
            </div>
        </div>

        <!-- Score counter banner -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-[32px] p-8 border border-gray-150/40 dark:border-gray-800/40 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
            <div class="space-y-2">
                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-650 dark:text-indigo-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-indigo-100/20">
                    {{ $exam->subject->name ?? __('messages.subject') }}
                </span>
                <h2 class="text-xl sm:text-2xl font-black text-gray-950 dark:text-white tracking-tight">{{ $exam->title }}</h2>
                <div class="flex items-center gap-4 text-xs font-bold text-gray-400">
                    <span>{{ __('messages.completed') }}: {{ $attempt->completed_at ? $attempt->completed_at->format('d M Y H:i') : '-' }}</span>
                    <span>{{ __('messages.duration_label') }}: {{ $exam->duration }} {{ __('messages.minutes') }}</span>
                </div>
            </div>

            <div class="text-center bg-indigo-600 text-white px-8 py-5 rounded-[24px] shadow-lg shadow-indigo-500/20 flex flex-col justify-center items-center shrink-0">
                <div class="text-[10px] font-black uppercase tracking-widest text-indigo-200">{{ __('messages.exam_score') }}</div>
                <div class="text-4xl sm:text-5xl font-black mt-1">{{ number_format($attempt->score, 1) }}</div>
            </div>
        </div>

        <!-- Questions Breakdown -->
        <div class="space-y-6">
            <h3 class="text-sm font-black text-gray-950 dark:text-white uppercase tracking-wider">{{ __('messages.questions_review') }}</h3>
            
            @foreach($exam->questions as $index => $q)
                @php
                    $ans = is_array($attempt->answers) ? ($attempt->answers[$q->id] ?? null) : null;
                    $isCorrect = $q->question_type === 'multiple_choice' && strtoupper(trim($ans)) === strtoupper(trim($q->correct_answer));
                @endphp
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 border border-gray-150/40 dark:border-gray-800/40 shadow-md space-y-4">
                    <div class="flex justify-between items-start">
                        <span class="font-extrabold text-sm text-gray-900 dark:text-white">{{ __('messages.question_word') }} #{{ $index + 1 }}</span>
                        @if($q->question_type === 'multiple_choice')
                            @if($isCorrect)
                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-450 text-[9px] font-black uppercase tracking-widest rounded-full border border-emerald-100/20">
                                    {{ __('messages.correct_score', ['points' => $q->points]) }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-955/20 text-rose-600 dark:text-rose-455 text-[9px] font-black uppercase tracking-widest rounded-full border border-rose-100/20">
                                    ✗ {{ __('messages.incorrect_label') }}
                                </span>
                            @endif
                        @else
                            <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-955/20 text-amber-600 dark:text-amber-455 text-[9px] font-black uppercase tracking-widest rounded-full border border-amber-100/20">
                                {{ __('messages.essay_pending_grading') }}
                            </span>
                        @endif
                    </div>

                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 leading-relaxed">{{ $q->question_text }}</p>

                    @if($q->question_type === 'multiple_choice')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2">
                            @foreach(['A', 'B', 'C', 'D'] as $opt)
                                @php
                                    $optionClass = 'border-gray-250 dark:border-gray-800 text-gray-650';
                                    if ($opt === $q->correct_answer) {
                                        $optionClass = 'bg-emerald-50 border-emerald-300 text-emerald-800 dark:bg-emerald-950/20 dark:border-emerald-900/40 dark:text-emerald-400';
                                    } elseif ($ans === $opt && !$isCorrect) {
                                        $optionClass = 'bg-rose-50 border-rose-300 text-rose-800 dark:bg-rose-950/20 dark:border-rose-900/40 dark:text-rose-400';
                                    }
                                @endphp
                                <div class="p-3 rounded-2xl border flex items-center text-xs font-bold {{ $optionClass }}">
                                    <span class="h-6 w-6 rounded-lg bg-black/5 dark:bg-white/5 flex items-center justify-center font-black text-[10px] mr-2" x-text="'{{ $opt }}'"></span>
                                    <span>{{ $q->options[$opt] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-xs text-gray-400 font-bold pt-1">
                            {{ __('messages.your_answer_label') }}: <strong class="text-gray-700 dark:text-white">{{ $ans ?: '-' }}</strong>
                        </div>
                    @else
                        <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-gray-150/40 text-xs font-semibold text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">
                            {{ $ans ?: __('messages.no_answer_provided') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
