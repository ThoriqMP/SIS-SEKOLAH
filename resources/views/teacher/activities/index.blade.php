@extends('layouts.app')

@section('title', 'Kelola Penugasan & Ujian')

@section('content')
<div class="py-6 sm:py-12 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ activeTab: '{{ request('tab', 'assignments') }}' }">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white/40 dark:bg-gray-900/40 backdrop-blur-md p-6 rounded-3xl border border-white/20 dark:border-gray-800/40 shadow-sm">
            <div>
                <h1 class="text-2xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">Kelola Penugasan & Ujian</h1>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1">
                    Periode Semester Aktif: <span class="text-purple-600 dark:text-purple-400 font-extrabold">{{ $currentPeriod }}</span>
                </p>
            </div>
            
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('teacher.assignments.create') }}" class="flex-1 sm:flex-none px-5 py-3 bg-purple-600 hover:bg-purple-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm shadow-lg shadow-purple-500/20 active:scale-95 transition-all text-center">
                    + Buat Penugasan
                </a>
                <a href="{{ route('teacher.exams.create') }}" class="flex-1 sm:flex-none px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-2xl text-xs sm:text-sm shadow-lg shadow-indigo-500/20 active:scale-95 transition-all text-center">
                    + Buat Ujian
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Navigation Tabs -->
        <div class="flex border-b border-gray-200 dark:border-gray-800">
            <button @click="activeTab = 'assignments'; const url = new URL(window.location.href); url.searchParams.set('tab', 'assignments'); window.history.replaceState(null, '', url.toString());" :class="activeTab === 'assignments' ? 'border-purple-600 text-purple-600 dark:border-purple-400 dark:text-purple-400 font-black' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-bold'" class="py-4 px-6 border-b-2 text-sm uppercase tracking-wider transition-colors duration-200">
                Penugasan (Tugas)
            </button>
            <button @click="activeTab = 'exams'; const url = new URL(window.location.href); url.searchParams.set('tab', 'exams'); window.history.replaceState(null, '', url.toString());" :class="activeTab === 'exams' ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400 font-black' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-bold'" class="py-4 px-6 border-b-2 text-sm uppercase tracking-wider transition-colors duration-200">
                Ujian / Tes
            </button>
        </div>

        <!-- Class Filter Pills -->
        <div class="flex flex-wrap items-center gap-2 pb-2">
            <span class="text-xs font-black uppercase tracking-wider text-gray-400 dark:text-gray-500 mr-2">Filter Kelas:</span>
            
            <a :href="'?tab=' + activeTab"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 active:scale-95 border"
               :class="activeTab === 'assignments' 
                   ? '{{ is_null($selectedClass) ? "bg-purple-600 text-white border-purple-600 shadow-md shadow-purple-500/25" : "bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-250 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/60" }}' 
                   : '{{ is_null($selectedClass) ? "bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-500/25" : "bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-250 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/60" }}'">
                Semua Kelas
            </a>

            @foreach($classes as $c)
                <a :href="'?class=' + encodeURIComponent('{{ $c }}') + '&tab=' + activeTab"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 active:scale-95 border"
                   :class="activeTab === 'assignments'
                       ? '{{ $selectedClass === $c ? "bg-purple-600 text-white border-purple-600 shadow-md shadow-purple-500/25" : "bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-250 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/60" }}'
                       : '{{ $selectedClass === $c ? "bg-indigo-600 text-white border-indigo-600 shadow-md shadow-indigo-500/25" : "bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-250 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/60" }}'">
                    Kelas {{ $c }}
                </a>
            @endforeach
        </div>

        <!-- Assignments Tab Content -->
        <div x-show="activeTab === 'assignments'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($assignments as $assignment)
                    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 border border-gray-150/40 dark:border-gray-800/40 shadow-md flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-purple-150/20">
                                    {{ $assignment->subject->name ?? 'Mata Pelajaran' }}
                                </span>
                                <div class="flex gap-1.5 items-center">
                                    @if($assignment->teacher_id == Auth::id())
                                        <span class="px-2 py-1 bg-purple-500/10 border border-purple-500/20 text-purple-600 dark:text-purple-400 text-[9px] font-black uppercase tracking-widest rounded-md">
                                            Pembuat
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest rounded-md">
                                            Kolaborator
                                        </span>
                                    @endif
                                    <span class="px-2.5 py-1 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-slate-200/20">
                                        Kelas {{ $assignment->class }}
                                    </span>
                                </div>
                            </div>

                            <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight line-clamp-1 mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                {{ $assignment->title }}
                            </h3>
                            <div class="flex flex-wrap gap-x-2 gap-y-1 mb-4 text-[10px] font-bold text-gray-400 dark:text-gray-500">
                                <span>Semester: {{ $assignment->period }}</span>
                                <span>•</span>
                                <span>Oleh: {{ $assignment->teacher->name ?? 'Guru' }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-3 leading-relaxed mb-6 font-semibold">
                                {{ strip_tags($assignment->description) }}
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-[10px] font-black text-rose-500 uppercase tracking-widest">
                                📅 Batas: {{ $assignment->due_date->format('d M Y H:i') }}
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-100 dark:border-gray-800/60">
                                <a href="{{ route('teacher.assignments.submissions', $assignment) }}" class="col-span-2 text-center py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-extrabold text-xs shadow-md transition duration-200">
                                    Lihat Pengumpulan
                                </a>
                                <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="text-center py-2.5 bg-slate-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-xl font-bold text-xs hover:bg-slate-200 dark:hover:bg-gray-700 transition duration-200 {{ $assignment->teacher_id == Auth::id() ? '' : 'col-span-2' }}">
                                    Edit
                                </a>
                                @if($assignment->teacher_id == Auth::id())
                                    <form action="{{ route('teacher.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-center py-2.5 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 rounded-xl font-bold text-xs hover:bg-rose-100 dark:hover:bg-rose-900/20 transition duration-200">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white/40 dark:bg-gray-900/40 rounded-3xl border-2 border-dashed border-purple-200/50 dark:border-purple-800/40 text-gray-400 dark:text-gray-500 p-8 shadow-sm">
                        <p class="text-sm font-bold">Belum ada tugas yang dibuat untuk semester ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Exams Tab Content -->
        <div x-show="activeTab === 'exams'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($exams as $exam)
                    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-3xl p-6 border border-gray-150/40 dark:border-gray-800/40 shadow-md flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-indigo-150/20">
                                    {{ $exam->subject->name ?? 'Mata Pelajaran' }}
                                </span>
                                <div class="flex gap-1.5 items-center">
                                    @if($exam->teacher_id == Auth::id())
                                        <span class="px-2 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-[9px] font-black uppercase tracking-widest rounded-md">
                                            Pembuat
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest rounded-md">
                                            Kolaborator
                                        </span>
                                    @endif
                                    <span class="px-2.5 py-1 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[9px] font-black uppercase tracking-widest rounded-md border border-slate-200/20">
                                        Kelas {{ $exam->class }}
                                    </span>
                                </div>
                            </div>

                            <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight line-clamp-1 mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                {{ $exam->title }}
                            </h3>
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                <span class="px-2 py-0.5 bg-indigo-100/50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 text-[8px] font-black uppercase tracking-widest rounded">
                                    Type: {{ str_replace('_', ' ', $exam->type) }} {{ $exam->target_period ? "({$exam->target_period})" : "" }}
                                </span>
                                <span class="px-2 py-0.5 bg-slate-100/60 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 text-[8px] font-black uppercase tracking-widest rounded">
                                    ⏱ {{ $exam->duration }} Menit
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-x-2 gap-y-1 mb-6 text-[10px] font-bold text-gray-400 dark:text-gray-500">
                                <span>Semester: {{ $exam->period }}</span>
                                <span>•</span>
                                <span>Oleh: {{ $exam->teacher->name ?? 'Guru' }}</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-[10px] font-black text-rose-500 uppercase tracking-widest">
                                📅 Batas: {{ $exam->due_date->format('d M Y H:i') }}
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-100 dark:border-gray-800/60">
                                <a href="{{ route('teacher.exams.attempts', $exam) }}" class="col-span-2 text-center py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-extrabold text-xs shadow-md transition duration-200">
                                    Lihat Hasil Ujian
                                </a>
                                <a href="{{ route('teacher.exams.edit', $exam) }}" class="text-center py-2.5 bg-slate-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-xl font-bold text-xs hover:bg-slate-200 dark:hover:bg-gray-700 transition duration-200 {{ $exam->teacher_id == Auth::id() ? '' : 'col-span-2' }}">
                                    Edit
                                </a>
                                @if($exam->teacher_id == Auth::id())
                                    <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ujian ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-center py-2.5 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 rounded-xl font-bold text-xs hover:bg-rose-100 dark:hover:bg-rose-900/20 transition duration-200">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-white/40 dark:bg-gray-900/40 rounded-3xl border-2 border-dashed border-indigo-200/50 dark:border-indigo-800/40 text-gray-400 dark:text-gray-500 p-8 shadow-sm">
                        <p class="text-sm font-bold">Belum ada ujian yang dibuat untuk semester ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
