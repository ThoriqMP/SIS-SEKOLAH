@extends('layouts.app')

@section('title', __('messages.assignment_details'))

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-sky-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-sky-950/20 min-h-screen">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('student.activities.index') }}" class="p-3 bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition active:scale-90 shrink-0">
                ← {{ __('messages.back') }}
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ __('messages.assignment_details') }}</h1>
                <p class="text-[10px] sm:text-xs font-black text-sky-600 dark:text-sky-400 uppercase tracking-widest mt-0.5 font-display">{{ __('messages.class_assignments_projects') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Assignment Detail Card -->
            <div class="lg:col-span-2 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-gray-150/40 dark:border-gray-800/40 shadow-xl space-y-6">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-sky-100/20">
                            {{ $assignment->subject->name ?? __('messages.subject') }}
                        </span>
                        <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-955/20 text-rose-600 dark:text-rose-455 text-[9px] font-black uppercase tracking-widest rounded-md border border-rose-100/20">
                            {{ __('messages.due_date_label') }}: {{ $assignment->due_date->format('d M H:i') }}
                        </span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-black text-gray-950 dark:text-white tracking-tight">{{ $assignment->title }}</h2>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ __('messages.published_by', ['name' => $assignment->teacher->name ?? __('messages.teacher')]) }}</p>
                </div>

                <div class="prose dark:prose-invert max-w-none text-sm leading-relaxed font-semibold text-gray-700 dark:text-gray-300 whitespace-pre-wrap p-4 bg-slate-50 dark:bg-slate-955 rounded-2xl border border-gray-150/40">
                    {{ $assignment->description }}
                </div>
            </div>

            <!-- Submission Panel -->
            <div class="space-y-6">
                <div class="bg-white/85 dark:bg-gray-900/85 backdrop-blur-md rounded-3xl p-6 border border-gray-150/40 dark:border-gray-800/40 shadow-xl space-y-6">
                    <h3 class="text-sm font-black text-gray-950 dark:text-white uppercase tracking-wider border-b border-gray-100 dark:border-gray-800 pb-3">{{ __('messages.submission_status') }}</h3>
                    
                    @if($submission)
                        <div class="space-y-4">
                            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl text-xs font-bold text-center">
                                ✓ {{ __('messages.assignment_submitted_success') }}
                                <div class="text-[10px] text-gray-400 mt-1">{{ __('messages.date') }}: {{ $submission->submitted_at->format('d M Y H:i') }}</div>
                            </div>

                            @if($submission->score !== null)
                                <div class="p-4 bg-sky-500/10 border border-sky-500/20 text-sky-700 dark:text-sky-400 rounded-2xl text-center">
                                    <div class="text-xs font-black uppercase tracking-wider text-gray-400">{{ __('messages.score_earned') }}</div>
                                    <div class="text-4xl font-black mt-2">{{ number_format($submission->score, 0) }}</div>
                                    @if($submission->feedback)
                                        <div class="mt-4 p-3 bg-white/50 dark:bg-gray-950/50 rounded-xl text-left text-xs font-semibold text-gray-600 dark:text-gray-300">
                                            <div class="font-bold text-[10px] text-sky-600 uppercase tracking-widest mb-1">{{ __('messages.teacher_feedback') }}</div>
                                            "{{ $submission->feedback }}"
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="p-4 bg-slate-50 dark:bg-slate-950 text-gray-500 text-xs font-bold text-center rounded-2xl border border-gray-200/50">
                                    {{ __('messages.waiting_grading') }}
                                </div>
                            @endif
                        </div>
                    @else
                        @if(now()->gt($assignment->due_date))
                            <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-2xl text-xs font-bold text-center">
                                ⚠ {{ __('messages.time_limit_exceeded') }}<br/>
                                <span class="text-[10px] text-gray-400">{{ __('messages.time_limit_exceeded_desc') }}</span>
                            </div>
                        @else
                            <!-- Submit Form -->
                            <form action="{{ route('student.activities.assignment.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div class="space-y-1">
                                    <label class="text-xs font-black text-gray-600 dark:text-gray-450 uppercase tracking-wider">{{ __('messages.submission_text_optional') }}</label>
                                    <textarea name="submission_text" rows="4" placeholder="{{ __('messages.type_answer_placeholder') }}" class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-xs focus:ring-sky-500 shadow-inner"></textarea>
                                </div>

                                <div class="space-y-1">
                                    <label class="text-xs font-black text-gray-600 dark:text-gray-450 uppercase tracking-wider">{{ __('messages.upload_file_optional') }}</label>
                                    <div class="relative flex items-center justify-center p-6 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl hover:border-sky-500 transition-colors">
                                        <input type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                                        <div class="text-center">
                                            <span class="text-xs font-black text-sky-600 dark:text-sky-400 hover:underline">{{ __('messages.choose_file') }}</span>
                                            <p class="text-[9px] text-gray-400 mt-1">{{ __('messages.file_format_limit') }}</p>
                                        </div>
                                    </div>
                                    @error('file')
                                        <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white rounded-2xl font-black text-xs transition duration-200 active:scale-95 shadow-lg shadow-sky-500/20">
                                    {{ __('messages.submit_assignment_btn') }}
                                </button>
                            </form>
                        @endif
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
