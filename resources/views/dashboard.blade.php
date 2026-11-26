@extends('layouts.app')

@section('title', 'Dashboard - SIS SEKOLAH')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-8">
        {{-- Greeting Banner --}}
        <div class="bg-gradient-to-r from-[#6d28d9] via-[#5b21b6] to-[#4f46e5] rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-purple-600/20">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-[10px] font-extrabold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                {{ auth()->user()->isSuperadmin() ? 'Superadmin' : 'Guru / Pengajar' }}
            </span>
            <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-1 text-sm text-purple-100">
                Kelola aktivitas akademik, rapor siswa, tagihan SPP, dan ruang belajar dari satu dashboard.
            </p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase">Siswa Aktif</span>
                <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalStudents ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase">Layanan Sekolah</span>
                <p class="text-2xl font-extrabold text-purple-700 mt-1">{{ $totalServices ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase">Total Tagihan</span>
                <p class="text-2xl font-extrabold text-indigo-700 mt-1">{{ $totalInvoices ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase">Tagihan Lunas</span>
                <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $paidInvoices ?? 0 }}</p>
            </div>
        </div>

        {{-- Quick Modules --}}
        <div>
            <h2 class="text-base font-extrabold text-slate-900 mb-4">Menu Modul Utama</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('students.index') }}" class="bg-white hover:border-purple-300 border border-slate-200/80 rounded-2xl p-5 transition flex items-center gap-4 group">
                    <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900">Data Siswa</p>
                        <p class="text-xs text-slate-500">Kelola profil & kelas siswa</p>
                    </div>
                </a>

                <a href="{{ route('teacher.activities.index') }}" class="bg-white hover:border-purple-300 border border-slate-200/80 rounded-2xl p-5 transition flex items-center gap-4 group">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900">Tugas &amp; Ujian</p>
                        <p class="text-xs text-slate-500">Buat &amp; nilai aktivitas siswa</p>
                    </div>
                </a>

                <a href="{{ route('teacher.lessons.index') }}" class="bg-white hover:border-purple-300 border border-slate-200/80 rounded-2xl p-5 transition flex items-center gap-4 group">
                    <div class="w-11 h-11 rounded-2xl bg-violet-50 text-violet-700 flex items-center justify-center group-hover:bg-violet-600 group-hover:text-white transition">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900">Ruang Belajar</p>
                        <p class="text-xs text-slate-500">Kelola kursus &amp; materi</p>
                    </div>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="bg-white hover:border-purple-300 border border-slate-200/80 rounded-2xl p-5 transition flex items-center gap-4 group">
                    <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900">Manajemen Rapor</p>
                        <p class="text-xs text-slate-500">Rapor Akademik, Diniyah &amp; PBL</p>
                    </div>
                </a>

                <a href="{{ route('invoices.index') }}" class="bg-white hover:border-purple-300 border border-slate-200/80 rounded-2xl p-5 transition flex items-center gap-4 group">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900">Tagihan &amp; Kwitansi</p>
                        <p class="text-xs text-slate-500">Kelola SPP &amp; pembayaran</p>
                    </div>
                </a>

                <a href="{{ route('teacher.video-conferences.index') }}" class="bg-white hover:border-purple-300 border border-slate-200/80 rounded-2xl p-5 transition flex items-center gap-4 group">
                    <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900">Kelas Video WebRTC</p>
                        <p class="text-xs text-slate-500">Jadwalkan &amp; mulai kelas online</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
