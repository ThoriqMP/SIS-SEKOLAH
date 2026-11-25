@extends('layouts.app')

@section('title', 'Daftar Pengumpulan Penugasan')

@section('content')
<div class="py-6 sm:py-12 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen" x-data="{ activeSubmissionId: null }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.activities.index') }}" class="p-3 bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition active:scale-90 shrink-0">
                ← Kembali
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Daftar Pengumpulan</h1>
                <p class="text-[10px] sm:text-xs font-black text-purple-650 dark:text-purple-400 uppercase tracking-widest mt-0.5">
                    {{ $assignment->title }} ({{ $assignment->subject->name }}) - Kelas {{ $assignment->class }}
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
                            <th class="p-4">Tanggal Pengumpulan</th>
                            <th class="p-4">Berkas / Jawaban</th>
                            <th class="p-4">Nilai</th>
                            <th class="p-4 pr-6 text-center">Aksi / Penilaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        @forelse($students as $student)
                            @php
                                $sub = $submissions->get($student->id);
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="p-4 pl-6">
                                    <div class="font-extrabold text-gray-950 dark:text-white">{{ $student->nama_siswa }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">NIS: {{ $student->nis ?? '-' }}</div>
                                </td>
                                <td class="p-4">
                                    @if($sub)
                                        <span class="text-xs">{{ $sub->submitted_at->format('d M Y H:i') }}</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 text-[10px] font-black rounded-full uppercase tracking-wider">Belum Mengumpulkan</span>
                                    @endif
                                </td>
                                <td class="p-4 max-w-xs">
                                    @if($sub)
                                        <div class="space-y-1.5">
                                            @if($sub->submission_text)
                                                <p class="text-xs text-gray-500 line-clamp-2 italic">"{{ $sub->submission_text }}"</p>
                                            @endif
                                            @if($sub->file_path)
                                                <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 font-extrabold hover:underline">
                                                    📎 Unduh Berkas Lampiran
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($sub && $sub->score !== null)
                                        <span class="text-base font-black text-purple-650 dark:text-purple-400">{{ number_format($sub->score, 0) }}</span>
                                    @else
                                        <span class="text-gray-400 font-medium">-</span>
                                    @endif
                                </td>
                                <td class="p-4 pr-6 text-center">
                                    @if($sub)
                                        <button @click="activeSubmissionId = {{ $student->id }}" class="px-4.5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-black shadow-md shadow-purple-500/10 active:scale-95 transition-all">
                                            {{ $sub->score !== null ? 'Re-Grade' : 'Nilai' }}
                                        </button>
                                    @else
                                        <button disabled class="px-4.5 py-2 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-650 rounded-xl text-xs font-black cursor-not-allowed">
                                            Nilai
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">Tidak ada siswa yang terdaftar di kelas {{ $assignment->class }}.</td>
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
        $sub = $submissions->get($student->id);
    @endphp
    @if($sub)
        <!-- Grade Modal Overlay -->
        <div x-show="activeSubmissionId === {{ $student->id }}" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/55 backdrop-blur-sm p-4" style="display: none;" @keydown.escape.window="activeSubmissionId = null">
            <div class="bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-3xl max-w-lg w-full p-6 text-left shadow-2xl space-y-6" @click.away="activeSubmissionId = null">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">Penilaian: {{ $student->nama_siswa }}</h3>
                    <button @click="activeSubmissionId = null" class="text-gray-400 hover:text-gray-650 dark:hover:text-gray-200 font-extrabold text-sm transition">✕</button>
                </div>

                <form action="{{ route('teacher.submissions.grade', $sub->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    @if($sub->submission_text)
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-gray-150/40 text-xs">
                            <div class="font-black text-gray-400 uppercase tracking-wider mb-1">Jawaban Siswa:</div>
                            <p class="text-gray-850 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $sub->submission_text }}</p>
                        </div>
                    @endif

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Skor Nilai (0 - 100)</label>
                        <input type="number" name="score" min="0" max="100" required value="{{ $sub->score }}" placeholder="Contoh: 85" class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Catatan Umpan Balik (Feedback)</label>
                        <textarea name="feedback" rows="3" placeholder="Masukan saran atau koreksi untuk siswa..." class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">{{ $sub->feedback }}</textarea>
                    </div>

                    <div class="flex gap-2.5 pt-3">
                        <button type="submit" class="flex-1 py-3 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm active:scale-95 shadow-lg shadow-purple-500/10">
                            Simpan & Sinkronisasi
                        </button>
                        <button type="button" @click="activeSubmissionId = null" class="flex-1 py-3 bg-slate-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-bold rounded-2xl text-xs sm:text-sm hover:bg-slate-200">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach

</div>
@endsection
