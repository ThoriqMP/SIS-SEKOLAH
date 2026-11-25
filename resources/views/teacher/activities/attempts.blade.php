@extends('layouts.app')

@section('title', 'Hasil Ujian Siswa')

@section('content')
<div class="py-6 sm:py-12 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen" x-data="{ activeAttemptId: null }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.activities.index') }}" class="p-3 bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition active:scale-90 shrink-0">
                ← Kembali
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Hasil Ujian Siswa</h1>
                <p class="text-[10px] sm:text-xs font-black text-purple-650 dark:text-purple-400 uppercase tracking-widest mt-0.5 font-display">
                    {{ $exam->title }} ({{ $exam->subject->name }}) - Kelas {{ $exam->class }}
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl border border-gray-150/40 dark:border-gray-800/40 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-slate-100/50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800 text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="p-4 pl-6">Nama Siswa</th>
                            <th class="p-4">Mulai Ujian</th>
                            <th class="p-4">Selesai Ujian</th>
                            <th class="p-4">Nilai Terhitung (MC)</th>
                            <th class="p-4 pr-6 text-center">Aksi / Penilaian Esai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        @forelse($students as $student)
                            @php
                                $att = $attempts->get($student->id);
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="p-4 pl-6">
                                    <div class="font-extrabold text-gray-950 dark:text-white">{{ $student->nama_siswa }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">NIS: {{ $student->nis ?? '-' }}</div>
                                </td>
                                <td class="p-4">
                                    @if($att)
                                        <span class="text-xs">{{ $att->started_at->format('d M Y H:i') }}</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 text-[10px] font-black rounded-full uppercase tracking-wider">Belum Mengikuti</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($att && $att->completed_at)
                                        <span class="text-xs">{{ $att->completed_at->format('d M Y H:i') }}</span>
                                    @elseif($att)
                                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded">Sedang Mengerjakan</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($att && $att->score !== null)
                                        <span class="text-base font-black text-purple-650 dark:text-purple-400">{{ number_format($att->score, 1) }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4 pr-6 text-center">
                                    @if($att && $att->completed_at)
                                        <button @click="activeAttemptId = {{ $student->id }}" class="px-4.5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-black shadow-md shadow-purple-500/10 active:scale-95 transition-all">
                                            Koreksi Jawaban
                                        </button>
                                    @else
                                        <button disabled class="px-4.5 py-2 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-650 rounded-xl text-xs font-black cursor-not-allowed">
                                            Koreksi
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">Tidak ada siswa yang terdaftar di kelas {{ $exam->class }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

<!-- Modals Section -->
@foreach($students as $student)
    @php
        $att = $attempts->get($student->id);
    @endphp
    @if($att && $att->completed_at)
        <!-- Detail and Grading Modal Overlay -->
        <div x-show="activeAttemptId === {{ $student->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/55 backdrop-blur-sm p-4" style="display: none;" @keydown.escape.window="activeAttemptId = null">
            <div class="bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-3xl max-w-2xl w-full p-6 text-left shadow-2xl space-y-6 flex flex-col max-h-[90vh]" @click.away="activeAttemptId = null">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-800 shrink-0">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">Jawaban: {{ $student->nama_siswa }}</h3>
                    <button @click="activeAttemptId = null" class="text-gray-400 hover:text-gray-650 dark:hover:text-gray-200 font-extrabold text-sm transition">✕</button>
                </div>

                <!-- Scrollable answers review -->
                <div class="flex-1 overflow-y-auto space-y-6 pr-2">
                    @foreach($exam->questions as $qIndex => $q)
                        @php
                            $ans = $att && is_array($att->answers) ? ($att->answers[$q->id] ?? null) : null;
                            $isCorrect = $q->question_type === 'multiple_choice' && strtoupper(trim($ans)) === strtoupper(trim($q->correct_answer));
                        @endphp
                        <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-gray-200 dark:border-gray-800/80 rounded-2xl space-y-3">
                            <div class="flex justify-between items-start">
                                <div class="font-extrabold text-sm text-gray-900 dark:text-white">Pertanyaan #{{ $qIndex + 1 }}</div>
                                <div class="text-[10px] font-black uppercase px-2 py-0.5 rounded {{ $q->question_type === 'multiple_choice' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $q->question_type === 'multiple_choice' ? 'Pilihan Ganda' : 'Esai' }}
                                </div>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 font-semibold">{{ $q->question_text }}</p>

                            @if($q->question_type === 'multiple_choice')
                                <div class="grid grid-cols-2 gap-2 text-xs font-semibold text-gray-600 dark:text-gray-400">
                                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                                        <div class="p-2 rounded-lg border {{ $ans === $opt ? ($isCorrect ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-rose-50 border-rose-300 text-rose-800') : 'border-gray-200 dark:border-gray-800' }}">
                                            {{ $opt }}. {{ $q->options[$opt] ?? '' }}
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex items-center justify-between text-xs font-bold mt-2">
                                    <span class="text-gray-400">Jawaban siswa: <strong class="text-gray-700 dark:text-white">{{ $ans ?: '-' }}</strong></span>
                                    @if($isCorrect)
                                        <span class="text-emerald-600">✓ Benar (+{{ $q->points }} Poin)</span>
                                    @else
                                        <span class="text-rose-500">✗ Salah (Kunci: {{ $q->correct_answer }})</span>
                                    @endif
                                </div>
                            @else
                                <div class="p-3 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 text-xs text-gray-850 dark:text-gray-200 font-medium whitespace-pre-wrap min-h-12 leading-relaxed">
                                    {{ $ans ?: '(Tidak menjawab)' }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Final Grading Input -->
                <form action="{{ route('teacher.attempts.grade', $att ? $att->id : 0) }}" method="POST" class="border-t border-gray-100 dark:border-gray-800 pt-4 shrink-0 space-y-4">
                    @csrf
                    <div class="flex items-center gap-4">
                        <div class="flex-1 space-y-1">
                            <label class="text-xs font-black text-gray-600 dark:text-gray-450 uppercase tracking-wider">Nilai Akhir Ujian (0-100)</label>
                            <input type="number" name="score" min="0" max="100" required value="{{ ($att && $att->score !== null) ? number_format($att->score, 0) : '' }}" class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 shadow-inner" />
                        </div>
                        <div class="flex-1 flex gap-2.5 pt-5">
                            <button type="submit" class="flex-1 py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm shadow-lg shadow-purple-500/10 active:scale-95 transition-all">
                                Simpan & Sync
                            </button>
                            <button type="button" @click="activeAttemptId = null" class="flex-1 py-3.5 bg-slate-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-bold rounded-2xl text-xs sm:text-sm hover:bg-slate-200">
                                Tutup
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach

</div>
@endsection
