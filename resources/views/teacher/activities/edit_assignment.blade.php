@extends('layouts.app')

@section('title', 'Edit Penugasan')

@section('content')
<div class="py-6 sm:py-12 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.activities.index') }}" class="p-3 bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition active:scale-90 shrink-0">
                ← Kembali
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Edit Penugasan</h1>
                <p class="text-[10px] sm:text-xs font-black text-purple-650 dark:text-purple-400 uppercase tracking-widest mt-0.5">Penugasan & Latihan Siswa</p>
            </div>
        </div>

        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-gray-150/40 dark:border-gray-800/40 shadow-xl">
            <form action="{{ route('teacher.assignments.update', $assignment) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Period Info -->
                <div class="p-4 bg-purple-500/10 border border-purple-500/20 rounded-2xl flex justify-between items-center text-xs font-bold text-purple-750 dark:text-purple-450">
                    <span>Periode Semester:</span>
                    <span>{{ $assignment->period }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Subject Selector -->
                    <div class="space-y-2">
                        <label for="report_subject_id" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Mata Pelajaran</label>
                        <select name="report_subject_id" id="report_subject_id" required class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ (old('report_subject_id', $assignment->report_subject_id) == $subject->id) ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->category->name ?? 'Category' }})
                                </option>
                            @endforeach
                        </select>
                        @error('report_subject_id')
                            <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class Selector -->
                    <div class="space-y-2">
                        <label for="class" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kelas</label>
                        <select name="class" id="class" required class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c }}" {{ (old('class', $assignment->class) == $c) ? 'selected' : '' }}>
                                    Kelas {{ $c }}
                                </option>
                            @endforeach
                        </select>
                        @error('class')
                            <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Assignment Title -->
                <div class="space-y-2">
                    <label for="title" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Judul Penugasan</label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $assignment->title) }}" placeholder="Contoh: Tugas Mandiri Pekan 1" class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner" />
                    @error('title')
                        <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instructions / Description -->
                <div class="space-y-2">
                    <label for="description" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Instruksi / Detail Tugas</label>
                    <textarea name="description" id="description" rows="6" required placeholder="Tuliskan instruksi penugasan di sini secara rinci..." class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3.5 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner">{{ old('description', $assignment->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div class="space-y-2">
                    <label for="due_date" class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Batas Waktu Pengumpulan (Deadline)</label>
                    <input type="datetime-local" name="due_date" id="due_date" required value="{{ old('due_date', $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '') }}" class="w-full rounded-2xl border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white p-3 text-sm focus:ring-purple-500 focus:border-purple-500 shadow-inner" />
                    @error('due_date')
                        <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Collaborators Section -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kolaborator (Guru Lain)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 bg-slate-50 dark:bg-gray-950 rounded-2xl border border-gray-200 dark:border-gray-800 max-h-48 overflow-y-auto shadow-inner">
                        @forelse($teachers as $teacher)
                            <label class="flex items-center gap-3 p-2 bg-white dark:bg-gray-900 border border-gray-150/40 dark:border-gray-800/40 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition">
                                <input type="checkbox" name="collaborators[]" value="{{ $teacher->id }}" 
                                    class="rounded text-purple-650 focus:ring-purple-500 border-gray-300 dark:border-gray-850"
                                    {{ (is_array(old('collaborators')) && in_array($teacher->id, old('collaborators'))) || (!is_array(old('collaborators')) && $assignment->collaborators->contains($teacher->id)) ? 'checked' : '' }} />
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-250">{{ $teacher->name }}</span>
                            </label>
                        @empty
                            <span class="text-xs text-gray-400 dark:text-gray-500 col-span-full py-4 text-center">Tidak ada guru lain yang tersedia.</span>
                        @endforelse
                    </div>
                    @error('collaborators')
                        <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="pt-4 flex gap-3">
                    <button type="submit" class="flex-1 py-4 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm shadow-lg shadow-purple-500/20 active:scale-95 transition-all text-center">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('teacher.activities.index') }}" class="flex-1 py-4 bg-slate-100 dark:bg-gray-800 text-gray-855 dark:text-gray-200 font-bold rounded-2xl text-xs sm:text-sm text-center hover:bg-slate-200 dark:hover:bg-gray-750 transition active:scale-95">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
