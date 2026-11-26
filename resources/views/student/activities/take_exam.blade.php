@extends('layouts.app')

@section('title', __('messages.taking_exam'))

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-sky-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-sky-950/20 min-h-screen" x-data="quizTaker()">
    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Left Side: Quiz Questions -->
        <div class="lg:col-span-3 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-gray-150/40 dark:border-gray-800/40 shadow-xl flex flex-col justify-between min-h-[500px]">
            <form id="quizForm" action="{{ route('student.activities.exam.submit', $exam) }}" method="POST">
                @csrf
                
                <!-- Questions Area -->
                <template x-for="(q, index) in questions" :key="q.id">
                    <div x-show="activeQuestion === index" x-transition:enter="transition ease-out duration-300" class="space-y-6">
                        <!-- Question Header -->
                        <div class="flex justify-between items-center pb-4 border-b border-gray-100 dark:border-gray-800">
                            <h3 class="text-sm font-black text-sky-600 dark:text-sky-400 uppercase tracking-widest" x-text="`{{ __('messages.question_word') }} ${index + 1} {{ __('messages.of_word') }} ${questions.length}`"></h3>
                            <span class="px-2.5 py-1 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-md" x-text="`${q.points} {{ __('messages.points_label') }}`"></span>
                        </div>

                        <!-- Question Text -->
                        <h2 class="text-lg font-black text-gray-900 dark:text-white leading-relaxed" x-text="q.question_text"></h2>

                        <!-- Answer Inputs -->
                        <!-- Multiple Choice -->
                        <div class="space-y-3 pt-2" x-show="q.question_type === 'multiple_choice'">
                            <template x-for="opt in ['A', 'B', 'C', 'D']">
                                <label :class="answers[q.id] === opt ? 'bg-sky-600 border-sky-600 text-white shadow-lg shadow-sky-500/10' : 'bg-slate-50/50 dark:bg-slate-950 border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-300 hover:bg-slate-100'" class="flex items-center p-4 rounded-2xl border cursor-pointer transition-all duration-200">
                                    <input type="radio" :name="`answers[${q.id}]`" :value="opt" x-model="answers[q.id]" class="hidden" />
                                    <span class="h-6 w-6 rounded-lg bg-black/5 dark:bg-white/5 flex items-center justify-center font-black text-xs mr-3" x-text="opt"></span>
                                    <span class="text-sm font-bold" x-text="q.options[opt]"></span>
                                </label>
                            </template>
                        </div>

                        <!-- Essay -->
                        <div class="space-y-2 pt-2" x-show="q.question_type === 'essay'">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-wider">{{ __('messages.write_your_answer') }}</label>
                            <textarea :name="`answers[${q.id}]`" x-model="answers[q.id]" rows="6" placeholder="{{ __('messages.type_essay_answer_placeholder') }}" class="w-full rounded-2xl border-gray-250 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-4 text-sm focus:ring-sky-500 shadow-inner"></textarea>
                        </div>
                    </div>
                </template>

                <!-- Next/Prev Buttons -->
                <div class="flex justify-between items-center pt-8 border-t border-gray-100 dark:border-gray-800 mt-8">
                    <button type="button" @click="prevQuestion()" :disabled="activeQuestion === 0" :class="activeQuestion === 0 ? 'opacity-40 cursor-not-allowed' : ''" class="px-5 py-3 bg-slate-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-extrabold rounded-xl text-xs active:scale-95 transition">
                        ← {{ __('messages.previous') }}
                    </button>

                    <button type="button" x-show="activeQuestion < questions.length - 1" @click="nextQuestion()" class="px-5 py-3 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-xl text-xs active:scale-95 transition">
                        {{ __('messages.next') }} →
                    </button>

                    <button type="button" x-show="activeQuestion === questions.length - 1" @click="confirmSubmit()" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs active:scale-95 transition shadow-lg shadow-emerald-500/10">
                        {{ __('messages.submit_exam') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side: Timer & Navigation Map -->
        <div class="space-y-6">
            <!-- Timer Card -->
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 border border-gray-150/40 dark:border-gray-800/40 shadow-xl text-center space-y-3">
                <div class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">{{ __('messages.remaining_exam_time') }}</div>
                <div class="text-3xl sm:text-4xl font-black text-rose-500 tracking-tight font-mono" x-text="formatTime(timer)"></div>
            </div>

            <!-- Questions Grid Map -->
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 border border-gray-150/40 dark:border-gray-800/40 shadow-xl space-y-4">
                <h4 class="text-xs font-black text-gray-950 dark:text-white uppercase tracking-wider border-b border-gray-100 dark:border-gray-800 pb-2">{{ __('messages.question_navigation') }}</h4>
                <div class="grid grid-cols-5 gap-2.5">
                    <template x-for="(q, index) in questions" :key="q.id">
                        <button type="button" @click="activeQuestion = index" :class="activeQuestion === index ? 'bg-sky-600 text-white border-sky-600 scale-105' : (answers[q.id] ? 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/40 dark:text-sky-400 dark:border-sky-900/40' : 'bg-slate-50 dark:bg-slate-950 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-800')" class="h-10 rounded-xl border font-black text-xs transition duration-200 flex items-center justify-center" x-text="index + 1"></button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Beautiful Confirmation Modal -->
        <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/55 backdrop-blur-sm p-4" style="display: none;" @keydown.escape.window="showConfirmModal = false">
            <div class="bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-3xl max-w-md w-full p-6 text-center shadow-2xl space-y-6" @click.away="showConfirmModal = false">
                <div class="w-16 h-16 bg-sky-50 dark:bg-sky-950/30 rounded-full flex items-center justify-center mx-auto text-sky-600 dark:text-sky-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ __('messages.submit_exam_question') }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed font-semibold">{{ __('messages.submit_exam_warning_desc') }}</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="submitQuiz()" class="flex-1 py-3 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm active:scale-95 shadow-lg shadow-sky-500/10">
                        {{ __('messages.yes_submit') }}
                    </button>
                    <button type="button" @click="showConfirmModal = false" class="flex-1 py-3 bg-slate-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-bold rounded-2xl text-xs sm:text-sm hover:bg-slate-200">
                        {{ __('messages.cancel') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Beautiful Timeout Modal -->
        <div x-show="showTimeoutModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/55 backdrop-blur-sm p-4" style="display: none;">
            <div class="bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-3xl max-w-md w-full p-6 text-center shadow-2xl space-y-6">
                <div class="w-16 h-16 bg-rose-50 dark:bg-rose-955/30 rounded-full flex items-center justify-center mx-auto text-rose-500 dark:text-rose-400 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-lg font-black text-rose-500">{{ __('messages.time_is_up') }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed font-semibold">{{ __('messages.time_is_up_desc') }}</p>
                </div>
                <div class="pt-2">
                    <button type="button" @click="submitQuiz()" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm active:scale-95 shadow-lg shadow-rose-500/10">
                        {{ __('messages.send_answers') }}
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function quizTaker() {
    return {
        activeQuestion: 0,
        questions: @json($exam->questions),
        answers: {},
        timer: 0,
        intervalId: null,
        showConfirmModal: false,
        showTimeoutModal: false,
        
        init() {
            // Setup initial answers state
            this.questions.forEach(q => {
                this.answers[q.id] = '';
            });

            // Calculate remaining seconds
            const limit = {{ $exam->duration * 60 }};
            const startedAt = new Date('{{ $attempt->started_at->toIso8601String() }}').getTime();
            const elapsed = Math.floor((Date.now() - startedAt) / 1000);
            this.timer = Math.max(0, limit - elapsed);

            // Tick countdown
            this.intervalId = setInterval(() => {
                if (this.timer > 0) {
                    this.timer--;
                } else {
                    this.autoSubmit();
                }
            }, 1000);
        },

        nextQuestion() {
            if (this.activeQuestion < this.questions.length - 1) {
                this.activeQuestion++;
            }
        },

        prevQuestion() {
            if (this.activeQuestion > 0) {
                this.activeQuestion--;
            }
        },

        formatTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            return [
                h > 0 ? String(h).padStart(2, '0') : null,
                String(m).padStart(2, '0'),
                String(s).padStart(2, '0')
            ].filter(Boolean).join(':');
        },

        confirmSubmit() {
            this.showConfirmModal = true;
        },

        submitQuiz() {
            clearInterval(this.intervalId);
            document.getElementById('quizForm').submit();
        },

        autoSubmit() {
            clearInterval(this.intervalId);
            this.showTimeoutModal = true;
            setTimeout(() => {
                this.submitQuiz();
            }, 3000);
        }
    }
}
</script>
@endsection
