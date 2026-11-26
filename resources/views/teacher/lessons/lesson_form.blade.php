@extends('layouts.app')

@section('title', (isset($lesson) ? 'Edit Materi' : 'Tambah Materi') . ' - SIS SEKOLAH')

@section('content')
@php
    $initialType = old('type', $lesson->type ?? 'interactive');
    $initialModule = isset($lesson) && is_array($lesson->module_data) ? $lesson->module_data : [
        'mindmap' => [
            'center' => $lesson->title ?? 'Topik Utama',
            'branches' => [
                ['title' => 'Pengertian Dasar', 'color' => 'purple', 'items' => ['Definisi utama materi', 'Konsep dasar yang perlu dipahami']],
                ['title' => 'Poin & Kaidah Inti', 'color' => 'indigo', 'items' => ['Aturan / rumus penting', 'Langkah penyelesaian sistematis']],
                ['title' => 'Penerapan Nyata', 'color' => 'emerald', 'items' => ['Contoh di kehidupan sehari-hari', 'Studi kasus sederhana']],
                ['title' => 'Ringkasan & Tips', 'color' => 'amber', 'items' => ['Kesalahan umum yang dihindari', 'Checklist pemahaman siswa']],
            ],
        ],
        'explanation' => [
            'summary' => '',
            'key_points' => [''],
            'formula_or_rule' => '',
        ],
        'examples' => [
            ['question' => '', 'answer' => '', 'explanation' => ''],
        ],
    ];
@endphp

<div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto space-y-6"
     x-data="moduleBuilder({{ json_encode($initialType) }}, {{ json_encode($initialModule) }}, {{ json_encode(old('title', $lesson->title ?? '')) }})">

    <div class="flex items-center justify-between gap-4">
        <div>
            <a href="{{ route('teacher.lessons.index') }}" class="text-xs font-bold text-purple-600 hover:underline">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Ruang Belajar
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 mt-1">
                {{ isset($lesson) ? 'Edit Materi Pembelajaran' : 'Buat Materi Pembelajaran Baru' }}
            </h1>
            <p class="text-xs text-slate-500">Kursus: <strong class="text-slate-700">{{ $course->title }}</strong> ({{ $course->education_level }})</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST"
          action="{{ isset($lesson) ? route('teacher.lessons.update', [$course, $lesson]) : route('teacher.lessons.store', $course) }}"
          class="space-y-6">
        @csrf
        @if (isset($lesson))
            @method('PUT')
        @endif

        <input type="hidden" name="module_data_json" :value="JSON.stringify(moduleData)">

        {{-- Basic Info Card --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-5 shadow-xs">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">Judul Materi</label>
                    <input type="text" name="title" x-model="title" required
                        placeholder="Contoh: Konsep Pecahan & Desimal"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-purple-500 outline-none">
                </div>

                <div class="md:col-span-4">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">Tipe Modul</label>
                    <select name="type" x-model="type"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-purple-500 outline-none bg-white">
                        <option value="interactive">Modul Interaktif (Mind Map, Materi & Soal)</option>
                        <option value="video">Video Eksternal</option>
                        <option value="pdf">Dokumen PDF</option>
                        <option value="docx">Dokumen Word</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">Urutan</label>
                    <input type="number" name="order_position" value="{{ old('order_position', $lesson->order_position ?? ($defaultOrder ?? 1)) }}" min="0" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-purple-500 outline-none">
                </div>
            </div>

            <div x-show="type !== 'interactive'" x-cloak>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-1.5">URL Media Eksternal (Opsional)</label>
                <input type="url" name="gdrive_url" value="{{ old('gdrive_url', $lesson->gdrive_url ?? '') }}"
                    placeholder="https://..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-purple-500 outline-none">
            </div>
        </div>

        {{-- Interactive Module Generator & Editor --}}
        <div x-show="type === 'interactive'" class="space-y-6">

            {{-- Auto-Generator Toolbar --}}
            <div class="bg-gradient-to-r from-[#6d28d9] to-[#4f46e5] rounded-3xl p-5 sm:p-6 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-lg shadow-purple-600/20">
                <div>
                    <span class="px-2.5 py-0.5 rounded-full bg-white/15 text-[10px] font-extrabold uppercase tracking-wider">Module Generator Tool</span>
                    <h2 class="text-lg font-extrabold mt-1">Generator Mind Map, Penjelasan &amp; Contoh Soal</h2>
                    <p class="text-xs text-purple-100 mt-0.5">Klik tombol di samping untuk membuat kerangka modul otomatis dari judul materi, lalu edit sesuai kebutuhan.</p>
                </div>
                <button type="button" @click="autoGenerate()"
                    class="px-5 py-3 rounded-2xl bg-white text-purple-800 hover:bg-purple-50 font-extrabold text-xs shadow-md transition active:scale-95 shrink-0 flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Otomatis
                </button>
            </div>

            {{-- 1. Mind Map Builder --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xs"><i class="fa-solid fa-diagram-project"></i></span>
                        1. Peta Konsep (Mind Map)
                    </h3>
                    <button type="button" @click="addBranch()" class="px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold transition">
                        + Tambah Cabang
                    </button>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Topik Pusat (Center Node)</label>
                    <input type="text" x-model="moduleData.mindmap.center"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-purple-500 outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="(branch, bIdx) in moduleData.mindmap.branches" :key="bIdx">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <input type="text" x-model="branch.title" placeholder="Judul Cabang"
                                    class="flex-1 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-extrabold text-slate-800">
                                <select x-model="branch.color" class="px-2 py-1.5 rounded-lg border border-slate-200 bg-white text-[11px] font-bold">
                                    <option value="purple">Ungu</option>
                                    <option value="indigo">Indigo</option>
                                    <option value="emerald">Hijau</option>
                                    <option value="amber">Kuning</option>
                                </select>
                                <button type="button" @click="moduleData.mindmap.branches.splice(bIdx, 1)" class="text-rose-500 hover:text-rose-700 px-1.5 text-xs">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            <div class="space-y-1.5">
                                <template x-for="(item, iIdx) in branch.items" :key="iIdx">
                                    <div class="flex items-center gap-1.5">
                                        <input type="text" x-model="branch.items[iIdx]" placeholder="Sub-poin..."
                                            class="flex-1 px-2.5 py-1 rounded-lg border border-slate-200 bg-white text-xs">
                                        <button type="button" @click="branch.items.splice(iIdx, 1)" class="text-slate-400 hover:text-rose-600 text-xs px-1">&times;</button>
                                    </div>
                                </template>
                                <button type="button" @click="branch.items.push('')" class="text-[11px] font-bold text-purple-600 hover:underline">
                                    + Sub-poin
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 2. Penjelasan Materi --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-4 shadow-xs">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs"><i class="fa-solid fa-book-open"></i></span>
                    2. Penjelasan Materi Terstruktur
                </h3>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Penjelasan Utama</label>
                    <textarea rows="4" x-model="moduleData.explanation.summary"
                        placeholder="Tuliskan penjelasan materi secara jelas dan mudah dipahami siswa..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-800 focus:ring-2 focus:ring-purple-500 outline-none"></textarea>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-[11px] font-bold uppercase text-slate-500">Poin-Poin Penting</label>
                        <button type="button" @click="moduleData.explanation.key_points.push('')" class="text-[11px] font-bold text-indigo-600 hover:underline">+ Tambah Poin</button>
                    </div>
                    <div class="space-y-2">
                        <template x-for="(pt, pIdx) in moduleData.explanation.key_points" :key="pIdx">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="moduleData.explanation.key_points[pIdx]" placeholder="Poin penting..."
                                    class="flex-1 px-3.5 py-2 rounded-xl border border-slate-200 text-xs">
                                <button type="button" @click="moduleData.explanation.key_points.splice(pIdx, 1)" class="text-rose-500 px-2 text-xs"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-500 mb-1">Rumus / Kaidah / Catatan Kunci</label>
                    <input type="text" x-model="moduleData.explanation.formula_or_rule"
                        placeholder="Contoh: a/b + c/d = (ad + bc) / bd"
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold text-purple-800 bg-purple-50/40">
                </div>
            </div>

            {{-- 3. Contoh Soal & Pembahasan --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs"><i class="fa-solid fa-list-check"></i></span>
                        3. Contoh Soal &amp; Pembahasan
                    </h3>
                    <button type="button" @click="moduleData.examples.push({ question: '', answer: '', explanation: '' })"
                        class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition">
                        + Tambah Soal
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(ex, eIdx) in moduleData.examples" :key="eIdx">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold text-slate-700" x-text="'Contoh Soal #' + (eIdx + 1)"></span>
                                <button type="button" @click="moduleData.examples.splice(eIdx, 1)" class="text-rose-500 hover:text-rose-700 text-xs">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </div>
                            <textarea rows="2" x-model="ex.question" placeholder="Pertanyaan..."
                                class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs"></textarea>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <input type="text" x-model="ex.answer" placeholder="Jawaban Singkat / Kunci"
                                    class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-emerald-700">
                                <input type="text" x-model="ex.explanation" placeholder="Pembahasan langkah demi langkah..."
                                    class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('teacher.lessons.index') }}" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-[#6d28d9] to-[#4f46e5] text-white text-xs font-bold shadow-lg shadow-purple-600/20 hover:opacity-95 transition">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Materi
            </button>
        </div>
    </form>
</div>

<script>
function moduleBuilder(initialType, initialModule, initialTitle) {
    return {
        type: initialType || 'interactive',
        title: initialTitle || '',
        moduleData: initialModule,
        addBranch() {
            this.moduleData.mindmap.branches.push({
                title: 'Cabang Baru',
                color: 'purple',
                items: ['Poin 1']
            });
        },
        autoGenerate() {
            const topic = this.title.trim() || 'Materi Pembelajaran';
            this.moduleData = {
                mindmap: {
                    center: topic,
                    branches: [
                        { title: 'Konsep Dasar', color: 'purple', items: ['Definisi ' + topic, 'Tujuan & manfaat utama'] },
                        { title: 'Aturan & Kaidah', color: 'indigo', items: ['Prinsip kerja / rumus inti', 'Syarat dan ketentuan'] },
                        { title: 'Contoh Penerapan', color: 'emerald', items: ['Studi kasus sederhana', 'Aplikasi kehidupan sehari-hari'] },
                        { title: 'Tips Penguasaan', color: 'amber', items: ['Pahami konsep sebelum menghafal', 'Latihan soal bertahap'] }
                    ]
                },
                explanation: {
                    summary: topic + ' merupakan salah satu kompetensi inti yang membantu siswa memahami konsep secara sistematis dan menerapkannya dalam pemecahan masalah sehari-hari.',
                    key_points: [
                        'Memahami definisi dan ruang lingkup ' + topic + '.',
                        'Menguasai langkah penyelesaian secara runtut dan logis.',
                        'Mampu mengidentifikasi penerapan ' + topic + ' pada soal kontekstual.'
                    ],
                    formula_or_rule: 'Pahami Konsep Inti -> Analisis Masalah -> Terapkan Kaidah -> Evaluasi Hasil'
                },
                examples: [
                    {
                        question: 'Jelaskan konsep utama dari ' + topic + ' beserta satu contoh penerapannya!',
                        answer: 'Konsep inti berfokus pada pemahaman dasar dan penerapan terstruktur.',
                        explanation: 'Identifikasi komponen utama terlebih dahulu, lalu hubungkan dengan kasus nyata di sekitar kita.'
                    },
                    {
                        question: 'Apa langkah pertama yang harus dilakukan saat menyelesaikan persoalan terkait ' + topic + '?',
                        answer: 'Memetakan informasi yang diketahui dan menentukan kaidah yang tepat.',
                        explanation: 'Dengan memetakan data awal secara rapi, kesalahan perhitungan atau penafsiran dapat dihindari.'
                    }
                ]
            };
        }
    }
}
</script>
@endsection
