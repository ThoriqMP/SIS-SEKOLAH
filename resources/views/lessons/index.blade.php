@extends('layouts.app')

@section('title', __('messages.immersive_study_room'))

@section('content')
@php
    $isSD = $educationLevel === 'SD';
    $isSMP = $educationLevel === 'SMP';
    $isSMA = $educationLevel === 'SMA';

    // Theme Mapping for Student Level Tones
    $theme = [
        'bg_gradient' => $isSD 
            ? 'from-amber-50 via-slate-50 to-teal-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-teal-950/20'
            : ($isSMP 
                ? 'from-slate-50 via-slate-50 to-indigo-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-indigo-950/20'
                : 'from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20'),
        'banner_gradient' => $isSD
            ? 'from-amber-500 via-amber-600 to-teal-600 shadow-amber-500/10'
            : ($isSMP 
                ? 'from-indigo-600 via-indigo-700 to-blue-700 shadow-indigo-600/10'
                : 'from-purple-700 via-purple-800 to-indigo-800 shadow-purple-950/25'),
        'accent_badge' => $isSD
            ? 'bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200/50'
            : ($isSMP
                ? 'bg-indigo-100 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 border border-indigo-200/50'
                : 'bg-purple-100 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 border border-purple-200/50'),
        'progress_bar' => $isSD ? 'bg-teal-500' : ($isSMP ? 'bg-indigo-500' : 'bg-purple-650'),
        'progress_bg' => $isSD ? 'bg-amber-200/50 border-amber-300' : ($isSMP ? 'bg-indigo-900/20 border-indigo-900/30' : 'bg-purple-950/20 border-purple-900/30'),
        'card_badge_earned' => $isSD
            ? 'bg-teal-50 border-teal-200 text-teal-800 dark:bg-teal-950/40 dark:border-teal-900/50 dark:text-teal-400'
            : ($isSMP
                ? 'bg-indigo-50 border-indigo-200 text-indigo-800 dark:bg-indigo-950/40 dark:border-indigo-900/50 dark:text-indigo-400'
                : 'bg-purple-50 border-purple-200 text-purple-800 dark:bg-purple-950/40 dark:border-purple-900/50 dark:text-purple-400'),
        'btn_primary' => $isSD
            ? 'bg-teal-500 hover:bg-teal-600 shadow-teal-500/20'
            : ($isSMP
                ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/20'
                : 'bg-purple-650 hover:bg-purple-700 shadow-purple-600/20')
    ];
@endphp

<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br {{ $theme['bg_gradient'] }} min-h-screen">
    
    <!-- Welcome Header Banner -->
    <div class="mb-8 sm:mb-10">
        <div class="bg-gradient-to-r {{ $theme['banner_gradient'] }} rounded-[32px] sm:rounded-[40px] p-6 sm:p-10 text-white shadow-xl relative overflow-hidden group">
            <div class="relative z-10">
                <span class="px-3.5 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[10px] sm:text-xs font-black uppercase tracking-widest text-purple-100">
                    {{ __('messages.student_study_room') }}
                </span>
                <h1 class="text-2xl sm:text-5xl font-black mt-4 mb-2 tracking-tight leading-tight">
                    {{ __('messages.immersive_study_room') }}
                </h1>
                <p class="text-white/80 text-sm sm:text-lg max-w-xl font-medium leading-relaxed mt-2">
                    {{ __('messages.explore_modules_desc', ['level' => $educationLevel]) }}
                </p>
            </div>
            
            <!-- Ambient backgrounds -->
            <div class="absolute -top-32 -right-32 w-80 h-80 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
        </div>
    </div>

    <!-- Courses Dashboard Grid -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ __('messages.your_courses') }}</h2>
                <p class="text-xs text-gray-500 font-semibold mt-0.5">{{ __('messages.courses_subtitle') }}</p>
            </div>
            <span class="px-3 py-1 text-xs font-black rounded-xl {{ $theme['accent_badge'] }}">
                {{ __('messages.total_courses', ['count' => $courses->count()]) }}
            </span>
        </div>

        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($courses as $course)
                    @php
                        $hasLessons = $course->lessons->count() > 0;
                        $isCompleted = $course->progress_percentage >= 100;
                    @endphp
                    
                    <div class="clay-card p-6 sm:p-8 bg-white dark:bg-gray-800 rounded-[32px] border border-gray-150 dark:border-gray-700 shadow-md hover:shadow-2xl hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 flex flex-col group relative overflow-hidden">
                        <!-- Decorative background light -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <!-- Top Row: Grade Badge and Completion Tick -->
                        <div class="flex justify-between items-start mb-6">
                            <div class="relative">
                                <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider {{ $theme['accent_badge'] }}">
                                    {{ __('messages.courses_grade_badge', ['grade' => $course->grade, 'level' => $course->education_level]) }}
                                </span>
                                @if($course->hasUnreadCommentsForUser(Auth::user()))
                                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full animate-pulse shadow-md border border-white dark:border-gray-800 z-10"></span>
                                @endif
                            </div>
                            @if($isCompleted)
                                <span class="flex items-center gap-1 px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider {{ $theme['card_badge_earned'] }}">
                                    {{ __('messages.badge_earned') }}
                                </span>
                            @endif
                        </div>

                        <!-- Course Title -->
                        <div class="mb-6 flex-grow">
                            <h3 class="text-lg sm:text-xl font-black text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200 line-clamp-2 leading-tight">
                                {{ $course->title }}
                            </h3>
                            <p class="text-xs text-gray-400 font-semibold mt-2">
                                {{ $course->lessons->count() }} {{ __('messages.learning_modules') }}
                            </p>
                        </div>

                        <!-- Badge Rewards Info -->
                        @if($course->badge)
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-850 flex items-center gap-3.5 mb-6">
                                <span class="text-4xl filter drop-shadow shrink-0">
                                    {{ $course->badge->badge_image_url ?? '🏆' }}
                                </span>
                                <div class="min-w-0">
                                    <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest block">{{ __('messages.reward_badge_label') }}</span>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 truncate block">{{ $course->badge->badge_name }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Progress Bar Section -->
                        <div class="space-y-2 mb-6">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-black text-gray-400 uppercase tracking-wider">{{ __('messages.learning_progress') }}:</span>
                                <span class="font-black text-gray-900 dark:text-white">{{ round($course->progress_percentage) }}%</span>
                            </div>
                            <div class="w-full rounded-full h-2.5 overflow-hidden p-0.5 border {{ $theme['progress_bg'] }}">
                                <div class="h-full rounded-full transition-all duration-700 {{ $theme['progress_bar'] }}" style="width: {{ $course->progress_percentage }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold">
                                {{ __('messages.completed_modules_count', ['completed' => $course->completed_count, 'total' => $course->lessons->count()]) }}
                            </p>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-750 flex items-center justify-between gap-4">
                            @if($hasLessons)
                                @php
                                    $targetLesson = $course->first_lesson_to_play;
                                @endphp
                                <a href="{{ route('student.lessons.show', $targetLesson->id) }}" 
                                   class="flex-1 py-3 text-center text-white rounded-2xl font-black text-xs uppercase tracking-widest active:scale-95 transition-all {{ $theme['btn_primary'] }}">
                                    @if($course->progress_percentage == 0)
                                        {{ __('messages.start_learning') }}
                                    @elseif($isCompleted)
                                        {{ __('messages.retake_learning') }}
                                    @else
                                        {{ __('messages.continue_learning') }}
                                    @endif
                                </a>
                            @else
                                <button disabled 
                                        class="flex-1 py-3 text-center bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-2xl font-black text-xs uppercase tracking-widest cursor-not-allowed">
                                    {{ __('messages.no_lessons') }}
                                </button>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <!-- Beautiful Premium Empty State -->
            <div class="clay-card rounded-[32px] p-8 sm:p-16 border border-white/40 dark:border-gray-800/40 shadow-xl relative overflow-hidden flex flex-col items-center justify-center text-center max-w-2xl mx-auto group">
                <div class="h-20 w-20 bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-[28px] flex items-center justify-center text-purple-650 dark:text-purple-400 shadow-inner shrink-0 mb-6 group-hover:scale-105 transition-transform duration-500 relative">
                    <span class="text-5xl filter drop-shadow">📚</span>
                    <div class="absolute inset-0 bg-purple-500/10 rounded-[28px] blur-xl scale-120 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                </div>
                
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight mb-2">{{ __('messages.no_courses_published') }}</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base leading-relaxed font-semibold max-w-md">
                    {{ __('messages.no_courses_published_desc') }}
                </p>
                
                <a href="{{ route('student.dashboard') }}" 
                   class="mt-8 px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-purple-650 dark:hover:bg-purple-650 hover:text-white dark:hover:text-white transition-all active:scale-95 shadow-md shadow-black/10">
                    {{ __('messages.back_to_home') }}
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
