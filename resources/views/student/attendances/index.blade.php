@extends('layouts.app')

@section('title', 'Riwayat Kehadiran - Portal Siswa')

@section('content')
<div class="min-h-screen bg-[#fafafc] p-4 sm:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('student.dashboard') }}" class="text-xs font-bold text-purple-600 hover:underline">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-1">Riwayat Kehadiran</h1>
                <p class="text-xs text-slate-500">{{ $student->nama_siswa ?? auth()->user()->name }} &bull; Kelas {{ $student->class ?? '-' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase text-emerald-600">Hadir</span>
                <p class="text-xl font-extrabold text-slate-900 mt-1">{{ $summary['hadir'] }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase text-sky-600">Izin</span>
                <p class="text-xl font-extrabold text-slate-900 mt-1">{{ $summary['izin'] }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase text-amber-600">Sakit</span>
                <p class="text-xl font-extrabold text-slate-900 mt-1">{{ $summary['sakit'] }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80">
                <span class="text-[10px] font-bold uppercase text-rose-600">Alpha</span>
                <p class="text-xl font-extrabold text-slate-900 mt-1">{{ $summary['alpha'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-extrabold text-slate-900">Catatan Kehadiran Terbaru</h2>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($records as $row)
                    <div class="px-6 py-3.5 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-extrabold text-slate-900">{{ $row->date->translatedFormat('l, d F Y') }}</p>
                            @if ($row->notes)
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $row->notes }}</p>
                            @endif
                        </div>
                        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase
                            {{ $row->status === 'hadir' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $row->status === 'izin' ? 'bg-sky-100 text-sky-700' : '' }}
                            {{ $row->status === 'sakit' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $row->status === 'alpha' ? 'bg-rose-100 text-rose-700' : '' }}">
                            {{ $row->status }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-xs text-slate-400 font-semibold">
                        Belum ada catatan kehadiran.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
