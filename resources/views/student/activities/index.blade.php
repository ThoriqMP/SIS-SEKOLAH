@extends('layouts.app')

@section('title', __('messages.assignments_exams_title'))

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-sky-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-sky-950/20 min-h-screen" x-data="{ activeTab: 'assignments' }">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header banner -->
        <div class="bg-gradient-to-r from-sky-700 via-sky-800 to-sky-800 rounded-[32px] p-6 sm:p-10 text-white shadow-xl shadow-sky-950/25 relative overflow-hidden group">
            <div class="relative z-10">
                <span class="px-3.5 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[10px] sm:text-xs font-black uppercase tracking-widest text-sky-200">
                    {{ __('messages.academic_activity') }}
                </span>
                <h1 class="text-2xl sm:text-4xl font-black mt-4 mb-2 tracking-tight">
                    {{ __('messages.assignments_exams_title') }}
                </h1>
                <p class="text-sky-100 text-xs sm:text-sm opacity-90 font-medium leading-relaxed">
                    {{ __('messages.assignments_exams_desc') }}
                </p>
                <div class="mt-6 flex flex-wrap gap-2.5">
                    <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl text-xs font-black border border-white/10 tracking-wider uppercase text-sky-200">
                        {{ $currentPeriod }}
                    </div>
                </div>
            </div>
            <div class="absolute -top-32 -right-32 w-80 h-80 bg-sky-500/20 rounded-full blur-3xl group-hover:scale-110 duration-1000"></div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-sky-500/25 rounded-full blur-3xl group-hover:scale-110 duration-1000"></div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-2xl text-sm font-bold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="flex border-b border-gray-250 dark:border-gray-800">
            <button @click="activeTab = 'assignments'" :class="activeTab === 'assignments' ? 'border-sky-600 text-sky-600 dark:border-sky-400 dark:text-sky-400 font-black' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-bold'" class="py-4 px-6 border-b-2 text-sm uppercase tracking-wider transition-colors duration-200">
                {{ __('messages.homework_project') }}
            </button>
            <button @click="activeTab = 'exams'" :class="activeTab === 'exams' ? 'border-sky-650 text-sky-650 dark:border-sky-400 dark:text-sky-400 font-black' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-bold'" class="py-4 px-6 border-b-2 text-sm uppercase tracking-wider transition-colors duration-200">
                {{ __('messages.exams_list') }}
            </button>
        </div>

        <!-- Assignments Tab Content -->
        <div x-show="activeTab === 'assignments'" x-transition:enter="transition ease-out duration-200" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($assignments as $assignment)
                    @php
                        $sub = $submissions->get($assignment->id);
                        $isOverdue = now()->gt($assignment->due_date) && !$sub;
                    @endphp
                    <div class="clay-card rounded-[32px] p-6 border border-white/40 dark:border-gray-800/40 shadow-md flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-sky-100/20">
                                    {{ $assignment->subject->name ?? __('messages.subject') }}
                                </span>
                                @if($sub)
                                    @if($sub->score !== null)
                                        <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-emerald-100/20">
                                            {{ __('messages.graded') }}: {{ number_format($sub->score, 0) }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-blue-100/20">
                                            {{ __('messages.submitted') }}
                                        </span>
                                    @endif
                                @elseif($isOverdue)
                                    <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-955/30 text-rose-600 dark:text-rose-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-rose-100/20">
                                        {{ __('messages.overdue') }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-955/30 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-amber-100/20">
                                        {{ __('messages.incomplete') }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight line-clamp-1 mb-3 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
                                {{ $assignment->title }}
                            </h3>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 line-clamp-3 leading-relaxed mb-6 font-medium">
                                {{ strip_tags($assignment->description) }}
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                📅 {{ __('messages.due_date_label') }}: {{ $assignment->due_date->format('d M Y H:i') }}
                            </div>
                            
                            <a href="{{ route('student.activities.assignment.show', $assignment) }}" class="block w-full text-center py-3.5 bg-slate-900 dark:bg-slate-800 text-white hover:bg-sky-600 rounded-2xl font-black text-xs transition duration-200 active:scale-95 shadow-sm">
                                {{ $sub ? __('messages.view_detail_score') : __('messages.start_working') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center clay-card border-2 border-dashed border-sky-200/50 dark:border-sky-800/40 text-gray-400 dark:text-gray-500 p-8 shadow-sm">
                        <p class="text-sm font-bold">{{ __('messages.no_active_assignments') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Exams Tab Content -->
        <div x-show="activeTab === 'exams'" x-transition:enter="transition ease-out duration-200" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($exams as $exam)
                    @php
                        $att = $attempts->get($exam->id);
                        $isExamOverdue = now()->gt($exam->due_date) && !$att;
                    @endphp
                    <div class="clay-card rounded-[32px] p-6 border border-white/40 dark:border-gray-800/40 shadow-md flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-sky-50 dark:bg-sky-950/40 text-sky-650 dark:text-sky-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-sky-100/20">
                                    {{ $exam->subject->name ?? __('messages.subject') }}
                                </span>
                                @if($att)
                                    @if($att->completed_at)
                                        <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-emerald-100/20">
                                            {{ __('messages.completed') }}: {{ number_format($att->score, 1) }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-yellow-50 dark:bg-yellow-950/30 text-yellow-600 dark:text-yellow-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-yellow-100/20">
                                            {{ __('messages.active') }}
                                        </span>
                                    @endif
                                @elseif($isExamOverdue)
                                    <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-955/30 text-rose-600 dark:text-rose-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-rose-100/20">
                                        {{ __('messages.missed') }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-955/30 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-amber-100/20">
                                        {{ __('messages.not_joined') }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight line-clamp-1 mb-2 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
                                {{ $exam->title }}
                            </h3>
                            <div class="flex flex-wrap gap-1.5 mb-6">
                                <span class="px-2 py-0.5 bg-sky-100/50 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 text-[8px] font-black uppercase tracking-widest rounded">
                                    {{ __('messages.type_label') }}: {{ str_replace('_', ' ', $exam->type) }} {{ $exam->target_period ? "({$exam->target_period})" : "" }}
                                </span>
                                <span class="px-2 py-0.5 bg-slate-100/60 dark:bg-slate-800/60 text-slate-600 dark:text-slate-305 text-[8px] font-black uppercase tracking-widest rounded">
                                    ⏱ {{ $exam->duration }} {{ __('messages.minutes') }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                📅 {{ __('messages.due_date_label') }}: {{ $exam->due_date->format('d M Y H:i') }}
                            </div>
                            
                            @if($att && $att->completed_at)
                                <a href="{{ route('student.activities.exam.result', $exam) }}" class="block w-full text-center py-3.5 bg-slate-900 dark:bg-slate-800 text-white hover:bg-sky-650 rounded-2xl font-black text-xs transition duration-200 active:scale-95 shadow-sm">
                                    {{ __('messages.view_score_result') }}
                                </a>
                            @elseif($isExamOverdue)
                                <button disabled class="w-full text-center py-3.5 bg-gray-150 text-gray-400 dark:bg-gray-800 dark:text-gray-600 rounded-2xl font-black text-xs cursor-not-allowed">
                                    {{ __('messages.time_limit_expired') }}
                                </button>
                            @else
                                <a href="{{ route('student.activities.exam.show', $exam) }}" class="block w-full text-center py-3.5 bg-sky-600 hover:bg-sky-700 text-white rounded-2xl font-black text-xs transition duration-200 active:scale-95 shadow-lg shadow-sky-500/20">
                                    {{ __('messages.start_exam') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center clay-card border-2 border-dashed border-sky-200/50 dark:border-sky-800/40 text-gray-400 dark:text-gray-500 p-8 shadow-sm">
                        <p class="text-sm font-bold">{{ __('messages.no_active_exams') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
