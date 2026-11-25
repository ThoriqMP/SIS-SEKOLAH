@extends('layouts.app')
@section('title', 'Proyek Bulanan per Semester')

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">Proyek Bulanan per Semester</h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                    Jadwalkan proyek bulanan dari template ke semester aktif. Proyek ini digunakan saat Input Kolektif PBL.
                </p>
            </div>
            <a href="{{ route('admin.semester-projects.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs sm:text-sm uppercase tracking-wider rounded-xl shadow-md transition-all active:scale-95 duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Proyek ke Semester
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 rounded-2xl text-xs font-bold flex items-center gap-2">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        {{-- Academic Year Filter --}}
        @if($academicYears->isNotEmpty())
        <div class="flex items-center gap-3 flex-wrap">
            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Filter Tahun Ajaran:</span>
            @foreach($academicYears as $year)
                <a href="{{ route('admin.semester-projects.index', ['year' => $year->id]) }}"
                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition border
                          {{ ($activeYear && $activeYear->id === $year->id) ? 'bg-purple-600 text-white border-purple-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-purple-400' }}">
                    {{ $year->year_name }} {{ $year->semester }}
                    @if($year->is_active) <span class="ml-1 text-[9px] uppercase">● Aktif</span> @endif
                </a>
            @endforeach
        </div>
        @endif

        {{-- Projects Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-150 dark:border-gray-700 overflow-hidden shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-150 dark:border-gray-700">
                            <th class="px-4 py-4 text-xs font-black text-gray-400 uppercase tracking-widest w-8">#</th>
                            <th class="px-4 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Judul Proyek</th>
                            <th class="px-4 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Semester</th>
                            <th class="px-4 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Template Dasar</th>
                            <th class="px-4 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Fase Kegiatan</th>
                            <th class="px-4 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($semesterProjects as $sp)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition-colors">
                                <td class="px-4 py-4 text-xs font-black text-gray-400">{{ $sp->sort_order + 1 }}</td>
                                <td class="px-4 py-4">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">{{ $sp->title }}</div>
                                    @if($sp->description)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">{{ $sp->description }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-block px-2 py-1 rounded-lg text-[10px] font-black bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                                        {{ $sp->academicYear?->year_name }} {{ $sp->academicYear?->semester }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-xs text-gray-600 dark:text-gray-400">
                                    {{ $sp->projectTemplate?->title ?? '—' }}
                                </td>
                                <td class="px-4 py-4">
                                    @php $phases = collect($sp->kegiatan ?? [])->pluck('category')->filter()->unique(); @endphp
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($phases as $phase)
                                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-md text-[10px] font-semibold">{{ $phase }}</span>
                                        @empty
                                            <span class="text-gray-400 text-xs italic">Belum ada</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <a href="{{ route('admin.semester-projects.edit', $sp) }}"
                                           class="p-2 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700/50 dark:hover:bg-gray-700 text-gray-650 dark:text-gray-300 rounded-lg text-xs font-bold transition">Edit</a>
                                        <form action="{{ route('admin.semester-projects.destroy', $sp) }}" method="POST"
                                              onsubmit="return confirm('Hapus proyek {{ addslashes($sp->title) }} dari semester ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-lg text-xs font-bold transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <div class="text-3xl mb-2">📅</div>
                                    <div class="text-sm font-bold text-gray-500">Belum ada proyek yang dijadwalkan untuk semester ini.</div>
                                    <div class="text-xs text-gray-400 mt-1">Klik "Tambah Proyek ke Semester" untuk memulai.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
