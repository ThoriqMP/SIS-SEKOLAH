@extends('layouts.app')

@php
    $isStudentPortal = request()->is('student*');
@endphp

@section('title', ($isStudentPortal ? 'Portal Siswa' : 'Portal Guru & Admin') . ' - SIS SEKOLAH')

@section('content')
<div class="relative min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 overflow-hidden bg-[#fafafc]">
    {{-- Ambient Background Blobs --}}
    <div class="pointer-events-none absolute -top-32 -left-32 w-96 h-96 rounded-full bg-sky-400/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -right-32 w-[28rem] h-[28rem] rounded-full bg-sky-400/20 blur-3xl"></div>
    <div class="pointer-events-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[36rem] h-[36rem] rounded-full bg-amber-300/10 blur-3xl"></div>

    <div class="relative z-10 w-full max-w-5xl grid grid-cols-1 lg:grid-cols-12 bg-white/90 backdrop-blur-xl rounded-3xl shadow-[0_24px_70px_-15px_rgba(79,70,229,0.16)] border border-slate-200/70 overflow-hidden">

        {{-- Left Brand Showcase Panel --}}
        <div class="hidden lg:flex lg:col-span-5 flex-col justify-between p-10 bg-gradient-to-br from-[#0369a1] via-[#0369a1] to-[#0c4a6e] text-white relative overflow-hidden">
            <div class="pointer-events-none absolute -top-20 -right-20 w-64 h-64 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-16 w-72 h-72 rounded-full bg-amber-400/15 blur-2xl"></div>

            <div class="relative z-10">
                <a href="{{ route('compro.index') }}" class="inline-flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-white p-1.5 shadow-lg shadow-black/15 flex items-center justify-center transition-transform group-hover:scale-105">
                        <img src="{{ asset('logos/sis-sekolah-logo.png') }}" alt="SIS SEKOLAH" class="w-full h-full object-contain rounded-xl">
                    </div>
                    <div>
                        <span class="block font-extrabold text-lg tracking-tight leading-none">SIS SEKOLAH</span>
                        <span class="block text-[11px] font-semibold tracking-widest uppercase text-sky-200 mt-1">Digital Academy & LMS</span>
                    </div>
                </a>

                <div class="mt-12">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-semibold text-sky-100 backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        {{ $isStudentPortal ? 'Ruang Belajar & Rapor Siswa' : 'Manajemen Akademik & Pengajar' }}
                    </span>

                    <h2 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight">
                        Pendidikan Fleksibel, <span class="text-amber-300">Tumbuh Bersama</span> Potensi Anak.
                    </h2>
                    <p class="mt-3 text-sm text-sky-100/85 leading-relaxed">
                        Akses materi pembelajaran interaktif, kelas video 1-on-1, tugas & ujian, serta pantau perkembangan rapor secara real-time.
                    </p>
                </div>
            </div>

            <div class="relative z-10 space-y-3 pt-8 border-t border-white/15">
                <div class="flex items-center gap-3 text-xs text-sky-100">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-graduation-cap text-amber-300"></i>
                    </div>
                    <span>Kurikulum Personal & Project Based Learning</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-sky-100">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-video text-emerald-300"></i>
                    </div>
                    <span>Kelas Video Interaktif & Forum Diskusi</span>
                </div>
            </div>
        </div>

        {{-- Right Login Form Panel --}}
        <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center">
            {{-- Mobile Brand Header --}}
            <div class="flex lg:hidden items-center justify-between mb-6">
                <a href="{{ route('compro.index') }}" class="inline-flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 border border-sky-100 p-1 flex items-center justify-center">
                        <img src="{{ asset('logos/sis-sekolah-logo.png') }}" alt="SIS SEKOLAH" class="w-full h-full object-contain rounded-lg">
                    </div>
                    <div>
                        <span class="block font-extrabold text-sm text-slate-900 leading-none">SIS SEKOLAH</span>
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-sky-600 mt-0.5">Digital Academy</span>
                    </div>
                </a>
                <a href="{{ route('compro.index') }}" class="text-xs font-semibold text-slate-500 hover:text-sky-700">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Beranda
                </a>
            </div>

            {{-- Portal Switcher Tabs --}}
            <div class="flex items-center justify-between gap-2 mb-6">
                <div class="inline-flex p-1 rounded-2xl bg-slate-100 border border-slate-200/70">
                    <a href="{{ route('student.login') }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $isStudentPortal ? 'bg-white text-sky-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        <i class="fa-solid fa-user-graduate"></i>
                        Portal Siswa
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ !$isStudentPortal ? 'bg-white text-sky-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        Guru & Admin
                    </a>
                </div>

                <a href="{{ route('compro.index') }}" class="hidden lg:inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-sky-700 transition">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>
            </div>

            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $isStudentPortal ? 'Selamat Datang, Siswa!' : 'Masuk ke Portal Pengajar' }}
                </h1>
                <p class="text-sm text-slate-500 mt-1.5">
                    {{ $isStudentPortal ? 'Silakan masuk dengan akun siswa untuk mengakses materi, tugas, dan rapor.' : 'Gunakan akun staf, guru, atau administrator sekolah Anda.' }}
                </p>
            </div>

            @if (session('success'))
                <div class="mt-5 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST"
                action="{{ $isStudentPortal ? route('student.login.submit') : route('login.submit') }}"
                class="mt-6 space-y-5"
                x-data="{ showPassword: false, loading: false }"
                @submit="loading = true">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 pointer-events-none">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="nama@email.com"
                            class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 focus:border-sky-600 outline-none transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 pointer-events-none">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required
                            placeholder="Masukkan password Anda"
                            class="w-full pl-11 pr-12 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 focus:border-sky-600 outline-none transition">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-700 transition"
                            aria-label="Tampilkan password">
                            <i class="fa-regular" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center gap-2 text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        <span class="text-xs font-semibold">Ingat sesi saya</span>
                    </label>

                    @if ($isStudentPortal)
                        <a href="{{ route('student.activate-form') }}" class="text-xs font-bold text-sky-600 hover:text-sky-800 transition">
                            Aktivasi Akun Baru <i class="fa-solid fa-arrow-right ml-0.5 text-[10px]"></i>
                        </a>
                    @endif
                </div>

                <button type="submit" :disabled="loading"
                    class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#0284c7] to-[#0369a1] hover:from-[#0369a1] hover:to-[#075985] text-white font-bold text-sm shadow-lg shadow-sky-600/25 hover:shadow-sky-600/35 active:scale-[0.99] transition-all flex items-center justify-center gap-2 disabled:opacity-70">
                    <span x-show="!loading">Masuk ke {{ $isStudentPortal ? 'Portal Siswa' : 'Dashboard' }}</span>
                    <span x-show="loading" x-cloak class="inline-flex items-center gap-2">
                        <i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...
                    </span>
                    <i x-show="!loading" class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            @if ($isStudentPortal)
                <p class="mt-6 text-center text-xs text-slate-500">
                    Belum terdaftar sebagai siswa?
                    <a href="{{ route('registration.create') }}" class="font-bold text-sky-600 hover:underline">Daftar PPDB Online</a>
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
