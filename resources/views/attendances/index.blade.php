@extends('layouts.app')

@section('title', 'Absensi Kehadiran - SIS SEKOLAH')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Absensi Kehadiran Siswa</h1>
            <p class="text-sm text-slate-500 mt-0.5">Catat dan pantau kehadiran harian siswa per kelas.</p>
        </div>

        <form method="GET" action="{{ route('attendances.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="date" name="date" value="{{ $date }}"
                class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 focus:ring-2 focus:ring-purple-500 outline-none">
            <select name="class"
                class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 focus:ring-2 focus:ring-purple-500 outline-none">
                <option value="">Semua Kelas</option>
                @foreach ($classes as $cls)
                    <option value="{{ $cls }}" {{ $classFilter === $cls ? 'selected' : '' }}>{{ $cls }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition">
                Filter
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80">
            <span class="text-[11px] font-bold uppercase text-emerald-600">Hadir</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $summary['hadir'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80">
            <span class="text-[11px] font-bold uppercase text-sky-600">Izin</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $summary['izin'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80">
            <span class="text-[11px] font-bold uppercase text-amber-600">Sakit</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $summary['sakit'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80">
            <span class="text-[11px] font-bold uppercase text-rose-600">Alpha</span>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $summary['alpha'] }}</p>
        </div>
    </div>

    {{-- Attendance Table Form --}}
    <form method="POST" action="{{ route('attendances.store') }}" class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="class" value="{{ $classFilter }}">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-sm font-extrabold text-slate-900">
                Daftar Siswa ({{ $students->count() }}) &bull; {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            </span>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#6d28d9] to-[#4f46e5] text-white text-xs font-bold shadow-md shadow-purple-600/20 hover:opacity-95 transition">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Absensi
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-extrabold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Siswa</th>
                        <th class="px-6 py-3.5">Kelas</th>
                        <th class="px-6 py-3.5">Status Kehadiran</th>
                        <th class="px-6 py-3.5">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        @php
                            $record = $existing->get($student->id);
                            $currentStatus = $record->status ?? 'hadir';
                        @endphp
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-3.5">
                                <p class="font-extrabold text-slate-900">{{ $student->nama_siswa }}</p>
                                <p class="text-[11px] text-slate-400">NIS: {{ $student->nis ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5 font-bold text-purple-700">{{ $student->class ?: '-' }}</td>
                            <td class="px-6 py-3.5">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach (['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpha' => 'Alpha'] as $val => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="attendances[{{ $student->id }}][status]" value="{{ $val }}"
                                                {{ $currentStatus === $val ? 'checked' : '' }} class="peer sr-only">
                                            <span class="inline-block px-3 py-1.5 rounded-lg border text-[11px] font-bold transition
                                                {{ $val === 'hadir' ? 'peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 border-slate-200 text-slate-600' : '' }}
                                                {{ $val === 'izin' ? 'peer-checked:bg-sky-600 peer-checked:text-white peer-checked:border-sky-600 border-slate-200 text-slate-600' : '' }}
                                                {{ $val === 'sakit' ? 'peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 border-slate-200 text-slate-600' : '' }}
                                                {{ $val === 'alpha' ? 'peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-600 border-slate-200 text-slate-600' : '' }}">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <input type="text" name="attendances[{{ $student->id }}][notes]"
                                    value="{{ $record->notes ?? '' }}"
                                    placeholder="Keterangan opsional..."
                                    class="w-full min-w-[180px] px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-500 outline-none">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400 font-semibold">
                                Belum ada data siswa aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>
@endsection
