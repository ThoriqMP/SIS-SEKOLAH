@extends('layouts.app')

@section('title', 'Buat Ujian Baru')

@section('content')
<div class="py-6 sm:py-12 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="examBuilder({{ $draft ? json_encode($draft->draft_data) : 'null' }})">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.activities.index') }}" class="p-3 bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition active:scale-90 shrink-0">
                ← Kembali
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Buat Ujian Baru</h1>
                <p class="text-[10px] sm:text-xs font-black text-purple-650 dark:text-purple-400 uppercase tracking-widest mt-0.5">Tes & Evaluasi Evaluatif Siswa</p>
            </div>
        </div>

        <!-- Draft Alert Banner -->
        <div x-show="hasDraft" x-cloak class="p-4 bg-amber-500/10 border border-amber-500/20 text-amber-800 dark:text-amber-400 rounded-2xl text-xs font-bold flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3" style="display: none;">
            <div>
                <span>💡 Draf pembuatan ujian sebelumnya ditemukan.</span>
            </div>
            <div class="flex gap-2 shrink-0">
                <button type="button" @click="restoreDraft()" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition active:scale-95 text-[10px] font-black uppercase tracking-wider">
                    Pulihkan Draf
                </button>
                <button type="button" @click="discardDraft()" class="px-3 py-1.5 bg-slate-150 hover:bg-slate-200 text-gray-800 dark:text-gray-300 rounded-lg transition active:scale-95 text-[10px] font-bold uppercase tracking-wider">
                    Abaikan
                </button>
            </div>
        </div>

        <form action="{{ route('teacher.exams.store') }}" method="POST" @input.debounce.500ms="autoSave()" @submit="clearDraft()" class="space-y-8">
            @csrf

            <!-- Section 1: General Info -->
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-gray-150/40 dark:border-gray-800/40 shadow-xl space-y-6">
                <h3 class="text-base font-black text-gray-950 dark:text-white uppercase tracking-wider border-b border-gray-100 dark:border-gray-800 pb-3">Informasi Ujian</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Subject Selector -->
                    <div class="space-y-2">
                        <label for="report_subject_id" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Mata Pelajaran</label>
                        <select name="report_subject_id" id="report_subject_id" x-model="report_subject_id" required class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }} ({{ $subject->category->name ?? 'Category' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Class Selector -->
                    <div class="space-y-2">
                        <label for="class" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kelas</label>
                        <select name="class" id="class" x-model="class" required class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c }}">
                                    Kelas {{ $c }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Period Selection -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Periode / Semester</label>
                        <div class="grid grid-cols-2 gap-2">
                            <select x-model="selectedSemester" @change="updatePeriod()" class="w-full rounded-2xl border-gray-250 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">
                                <option value="Ganjil">Semester Ganjil</option>
                                <option value="Genap">Semester Genap</option>
                            </select>
                            <select x-model="selectedAcademicYear" @change="updatePeriod()" class="w-full rounded-2xl border-gray-250 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">
                                <template x-for="year in getAcademicYears()" :key="year">
                                    <option :value="year" x-text="year" :selected="year === selectedAcademicYear"></option>
                                </template>
                            </select>
                        </div>
                        <input type="hidden" name="period" :value="period">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-1 space-y-2">
                        <label for="title" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Judul Ujian</label>
                        <input type="text" name="title" id="title" x-model="title" required placeholder="Contoh: UTS Matematika" class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner" />
                    </div>

                    <!-- Exam Type -->
                    <div class="space-y-2">
                        <label for="type" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kategori Ujian</label>
                        <select name="type" id="type" x-model="type" required class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">
                            <option value="harian">Ujian Harian / Tugas</option>
                            <option value="remedial">Ujian Remedial</option>
                            <option value="tengah_semester">Ujian Tengah Semester (PTS)</option>
                            <option value="akhir_semester">Ujian Akhir Semester (PAS)</option>
                        </select>
                    </div>

                    <!-- Target Period (for Harian / Remedial) -->
                    <div class="space-y-2" x-show="type === 'harian' || type === 'remedial'" x-transition>
                        <label for="target_period" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Target Periode Raport</label>
                        <select name="target_period" id="target_period" x-model="target_period" :required="type === 'harian' || type === 'remedial'" class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">
                            <option value="">-- Pilih Target Periode --</option>
                            <option value="PTS">Penilaian Tengah Semester (PTS)</option>
                            <option value="PAS">Penilaian Akhir Semester (PAS)</option>
                        </select>
                    </div>

                    <!-- Duration -->
                    <div class="space-y-2">
                        <label for="duration" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Durasi (Menit)</label>
                        <input type="number" name="duration" id="duration" x-model="duration" required min="1" class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner" />
                    </div>
                </div>

                <!-- Due Date -->
                <div class="space-y-2">
                    <label for="due_date" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Batas Waktu Akses (Deadline)</label>
                    <input type="datetime-local" name="due_date" id="due_date" x-model="due_date" required class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner" />
                </div>

                <!-- Collaborators Section -->
                <div class="space-y-2 col-span-full pt-4 border-t border-dashed border-gray-150 dark:border-gray-850">
                    <label class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kolaborator (Guru Lain)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-slate-50 dark:bg-gray-950 rounded-2xl border border-gray-200 dark:border-gray-800 max-h-48 overflow-y-auto shadow-inner">
                        @forelse($teachers as $teacher)
                            <label class="flex items-center gap-3 p-2 bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition">
                                <input type="checkbox" name="collaborators[]" value="{{ $teacher->id }}" x-model="collaborators"
                                    class="rounded text-purple-650 focus:ring-purple-500 border-gray-300 dark:border-gray-850" />
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-250">{{ $teacher->name }}</span>
                            </label>
                        @empty
                            <span class="text-xs text-gray-400 dark:text-gray-500 col-span-full py-4 text-center">Tidak ada guru lain yang tersedia.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Section 2: Question Builder -->
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-gray-150/40 dark:border-gray-800/40 shadow-xl space-y-6">
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-3">
                    <h3 class="text-base font-black text-gray-950 dark:text-white uppercase tracking-wider">Daftar Pertanyaan Ujian</h3>
                </div>

                <!-- List of Questions -->
                <div class="space-y-6">
                    <template x-for="(q, idx) in questions" :key="q.temp_id">
                        <div class="p-5 bg-slate-50 dark:bg-slate-950 rounded-3xl border border-gray-150/40 dark:border-gray-800/40 relative space-y-4">
                            <!-- Question Header -->
                            <div class="flex justify-between items-center">
                                <h4 class="text-sm font-black text-purple-650 dark:text-purple-400 uppercase tracking-wider" x-text="`Soal #${idx + 1}`"></h4>
                                <button type="button" @click="removeQuestion(idx)" class="p-2 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-100 transition duration-200 text-xs font-black">
                                    Hapus
                                </button>
                            </div>

                            <!-- Question Text -->
                            <div class="space-y-2">
                                <label class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pertanyaan</label>
                                <textarea :name="`questions[${idx}][question_text]`" required x-model="q.question_text" rows="3" placeholder="Tuliskan pertanyaan soal di sini..." class="w-full rounded-2xl border-gray-250 dark:border-gray-800 dark:bg-gray-900 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Type Selector -->
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tipe Soal</label>
                                    <select :name="`questions[${idx}][question_type]`" x-model="q.question_type" required class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white p-3 text-sm focus:ring-purple-500 shadow-inner">
                                        <option value="multiple_choice">Pilihan Ganda (A, B, C, D)</option>
                                        <option value="essay">Essay / Jawaban Teks</option>
                                    </select>
                                </div>

                                <!-- Points -->
                                <div class="space-y-2">
                                    <label class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider font-display">Bobot Poin</label>
                                    <input type="number" :name="`questions[${idx}][points]`" x-model.number="q.points" required min="1" class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white p-3 text-sm focus:ring-purple-500 shadow-inner" />
                                </div>
                            </div>

                            <!-- Multiple Choice Options -->
                            <div class="space-y-4 pt-2 border-t border-dashed border-gray-200 dark:border-gray-800" x-show="q.question_type === 'multiple_choice'">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Pilihan A</label>
                                        <input type="text" :name="`questions[${idx}][options][A]`" x-model="q.options.A" :required="q.question_type === 'multiple_choice'" placeholder="Jawaban A" class="w-full rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white p-2.5 text-xs shadow-inner" />
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider font-display">Pilihan B</label>
                                        <input type="text" :name="`questions[${idx}][options][B]`" x-model="q.options.B" :required="q.question_type === 'multiple_choice'" placeholder="Jawaban B" class="w-full rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white p-2.5 text-xs shadow-inner" />
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider font-display">Pilihan C</label>
                                        <input type="text" :name="`questions[${idx}][options][C]`" x-model="q.options.C" :required="q.question_type === 'multiple_choice'" placeholder="Jawaban C" class="w-full rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white p-2.5 text-xs shadow-inner" />
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider font-display">Pilihan D</label>
                                        <input type="text" :name="`questions[${idx}][options][D]`" x-model="q.options.D" :required="q.question_type === 'multiple_choice'" placeholder="Jawaban D" class="w-full rounded-xl border-gray-200 dark:border-gray-800 dark:bg-gray-900 dark:text-white p-2.5 text-xs shadow-inner" />
                                    </div>
                                </div>

                                <!-- Correct Answer -->
                                <div class="space-y-2 max-w-xs">
                                    <label class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kunci Jawaban Benar</label>
                                    <select :name="`questions[${idx}][correct_answer]`" x-model="q.correct_answer" :required="q.question_type === 'multiple_choice'" class="w-full rounded-2xl border-gray-200 dark:border-gray-900 dark:text-white p-3 text-sm focus:ring-purple-500 shadow-inner">
                                        <option value="A">Pilihan A</option>
                                        <option value="B">Pilihan B</option>
                                        <option value="C">Pilihan C</option>
                                        <option value="D">Pilihan D</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </template>
                </div>

                <!-- Fallback Empty State -->
                <div x-show="questions.length === 0" class="py-12 text-center text-gray-400">
                    <p class="text-sm font-semibold">Ujian ini belum memiliki soal. Klik "+ Tambah Soal Baru" di bawah untuk menambahkan.</p>
                </div>

                <!-- Add Question Button at the bottom -->
                <div class="pt-4 flex justify-end border-t border-gray-100 dark:border-gray-800">
                    <button type="button" @click="addQuestion()" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black shadow-md transition duration-200 active:scale-95 flex items-center gap-1.5">
                        <span>+ Tambah Soal Baru</span>
                    </button>
                </div>
            </div>

            <!-- Submit Button Actions -->
            <div class="pt-2 flex gap-4">
                <button type="submit" class="flex-1 py-4 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm shadow-lg shadow-purple-500/20 active:scale-95 transition-all text-center">
                    Simpan Ujian & Soal
                </button>
                <a href="{{ route('teacher.activities.index') }}" @click="clearDraft()" class="flex-1 py-4 bg-slate-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-bold rounded-2xl text-xs sm:text-sm text-center hover:bg-slate-200 dark:hover:bg-gray-750 transition active:scale-95">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<script>
function examBuilder(initialDraft) {
    return {
        examId: null,
        hasDraft: initialDraft !== null,
        draftData: initialDraft,
        report_subject_id: '{{ old('report_subject_id') }}',
        class: '{{ old('class') }}',
        title: {!! json_encode(old('title', '')) !!},
        type: '{{ old('type', 'harian') }}',
        duration: '{{ old('duration', 60) }}',
        due_date: '{{ old('due_date') }}',
        period: '{{ old("period", $currentPeriod) }}',
        selectedSemester: 'Ganjil',
        selectedAcademicYear: '',
        target_period: '{{ old("target_period", "") }}',
        collaborators: {!! json_encode(old('collaborators', [])) !!},
        questions: [
            {
                temp_id: Date.now() + Math.random(),
                question_text: '',
                question_type: 'multiple_choice',
                points: 10,
                correct_answer: 'A',
                options: { A: '', B: '', C: '', D: '' }
            }
        ],
        init() {
            this.initPeriod();
        },
        initPeriod() {
            const match = this.period.match(/Semester\s+(Ganjil|Genap)\s+(\d{4}\/\d{4})/i);
            if (match) {
                this.selectedSemester = match[1];
                this.selectedAcademicYear = match[2];
            } else {
                this.selectedSemester = this.period.toLowerCase().includes('genap') ? 'Genap' : 'Ganjil';
                const yearMatch = this.period.match(/(\d{4}\/\d{4})/);
                if (yearMatch) {
                    this.selectedAcademicYear = yearMatch[1];
                } else {
                    const currentYear = new Date().getFullYear();
                    this.selectedAcademicYear = `${currentYear}/${currentYear + 1}`;
                }
            }
            this.updatePeriod();
        },
        updatePeriod() {
            this.period = `Semester ${this.selectedSemester} ${this.selectedAcademicYear}`;
        },
        getAcademicYears() {
            const currentYear = new Date().getFullYear();
            const years = [];
            for (let i = -3; i <= 3; i++) {
                const y = currentYear + i;
                years.push(`${y}/${y + 1}`);
            }
            return years;
        },
        restoreDraft() {
            if (this.draftData) {
                this.report_subject_id = this.draftData.report_subject_id || '';
                this.class = this.draftData.class || '';
                this.title = this.draftData.title || '';
                this.type = this.draftData.type || 'harian';
                this.duration = this.draftData.duration || 60;
                this.due_date = this.draftData.due_date || '';
                this.questions = this.draftData.questions || [];
                this.period = this.draftData.period || '{{ $currentPeriod }}';
                this.target_period = this.draftData.target_period || '';
                this.collaborators = this.draftData.collaborators || [];
                this.initPeriod();
                this.hasDraft = false;
            }
        },
        discardDraft() {
            this.clearDraftBackend();
            this.hasDraft = false;
            this.draftData = null;
        },
        clearDraft() {
            this.clearDraftBackend();
        },
        clearDraftBackend() {
            fetch('{{ route('teacher.exams.draft.discard') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    exam_id: this.examId
                })
            }).catch(e => console.error("Failed to discard draft:", e));
        },
        autoSave() {
            const data = {
                report_subject_id: this.report_subject_id,
                class: this.class,
                title: this.title,
                type: this.type,
                duration: this.duration,
                due_date: this.due_date,
                questions: this.questions,
                period: this.period,
                target_period: this.target_period,
                collaborators: this.collaborators
            };
            
            fetch('{{ route('teacher.exams.draft.save') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    exam_id: this.examId,
                    draft_data: data
                })
            }).catch(e => console.error("Failed to save draft:", e));
        },
        addQuestion() {
            this.questions.push({
                temp_id: Date.now() + Math.random(),
                question_text: '',
                question_type: 'multiple_choice',
                points: 10,
                correct_answer: 'A',
                options: { A: '', B: '', C: '', D: '' }
            });
            this.autoSave();
        },
        removeQuestion(idx) {
            this.questions.splice(idx, 1);
            this.autoSave();
        }
    }
}
</script>
@endsection
