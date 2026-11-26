<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth scroll-pt-24">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <title>SIS SEKOLAH — Smart School Management & Digital LMS</title>
    <meta name="description" content="SIS SEKOLAH — Platform Sistem Informasi Sekolah terintegrasi dengan Portal Siswa, Portal Guru, Rapor Digital & PBL, Tagihan SPP Otomatis, serta Kelas Video WebRTC.">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SIS SEKOLAH">
    <meta property="og:title" content="SIS SEKOLAH — Smart School Management & Digital LMS">
    <meta property="og:description" content="Platform Sistem Informasi Sekolah terintegrasi dengan Portal Siswa, Portal Guru, Rapor Digital & PBL, dan Tagihan SPP Otomatis.">
    <meta property="og:image" content="{{ asset('logos/sis-sekolah-logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #ffffff;
            color: #0f172a;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Realistic iPhone 15/16 Pro Titanium Frame */
        .iphone-frame {
            background: linear-gradient(145deg, #0b0d12 0%, #020305 100%);
            border-radius: 54px;
            padding: 9px;
            border: 2.5px solid #475569;
            box-shadow:
                0 35px 70px -15px rgba(15, 23, 42, 0.42),
                0 0 0 1px rgba(255, 255, 255, 0.25) inset,
                0 0 0 1px #1e293b;
            position: relative;
        }
        /* Physical Side Buttons */
        .iphone-frame::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 84px;
            width: 3px;
            height: 22px;
            background: linear-gradient(180deg, #64748b, #334155);
            border-radius: 3px 0 0 3px;
            box-shadow:
                0 34px 0 0 #475569,
                0 34px 0 0 #475569,
                0 68px 0 0 #475569;
        }
        .iphone-frame::after {
            content: '';
            position: absolute;
            right: -5px;
            top: 112px;
            width: 3px;
            height: 52px;
            background: linear-gradient(180deg, #64748b, #334155);
            border-radius: 0 3px 3px 0;
        }
        .iphone-screen {
            background: #f8fafc;
            border-radius: 44px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.08);
        }
        .iphone-statusbar {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 16px 7px;
            font-size: 9.5px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
        }
        .iphone-island {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 72px;
            height: 19px;
            background: #040507;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 6px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
        }
        .iphone-island::after {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 9999px;
            background: radial-gradient(circle at 35% 35%, #3b82f6 0%, #1e1b4b 45%, #020617 100%);
            border: 1px solid #1e293b;
        }
        .iphone-home-bar {
            width: 88px;
            height: 3.5px;
            background: rgba(15, 23, 42, 0.78);
            border-radius: 9999px;
            margin: 6px auto 2px;
        }

        /* Realistic MacBook Pro Frame */
        .macbook-lid {
            background: linear-gradient(180deg, #1e293b 0%, #090d16 100%);
            border-radius: 20px 20px 0 0;
            padding: 12px 12px 0;
            box-shadow:
                0 25px 50px -12px rgba(15, 23, 42, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.12) inset;
            position: relative;
        }
        .macbook-notch {
            width: 72px;
            height: 14px;
            background: #090d16;
            border-radius: 0 0 8px 8px;
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
        }
        .macbook-screen {
            background: #f8fafc;
            border-radius: 10px 10px 0 0;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .macbook-base {
            height: 14px;
            background: linear-gradient(180deg, #cbd5e1 0%, #94a3b8 65%, #64748b 100%);
            border-radius: 2px 2px 14px 14px;
            position: relative;
            margin: 0 -22px;
            box-shadow: 0 12px 24px -8px rgba(15, 23, 42, 0.3);
        }
        .macbook-base::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 78px;
            height: 5px;
            background: #64748b;
            border-radius: 0 0 6px 6px;
        }
    </style>
</head>
<body x-data="{ mobileMenu: false }">

    {{-- ===================================================================== --}}
    {{-- NAVBAR                                                                --}}
    {{-- ===================================================================== --}}
    <header class="sticky top-0 z-50 w-full px-4 sm:px-6 py-3.5 bg-[#f3efff]/85 backdrop-blur-md">
        <nav class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            {{-- Brand --}}
            <a href="#" class="flex items-center gap-2.5 shrink-0 group">
                <img src="{{ asset('logos/sis-sekolah-logo.png') }}" alt="SIS SEKOLAH" class="h-9 w-9 object-contain rounded-xl shadow-xs group-hover:scale-105 transition-transform bg-white p-0.5">
                <div class="leading-none">
                    <span class="block text-sm sm:text-base font-extrabold tracking-tight text-slate-900">SIS SEKOLAH</span>
                    <span class="block text-[9px] font-bold uppercase tracking-widest text-purple-600 mt-0.5">Smart School</span>
                </div>
            </a>

            {{-- Desktop Centered Pill Menu --}}
            <div class="hidden md:flex items-center gap-6 bg-white/90 border border-purple-100/90 rounded-full px-6 py-2.5 shadow-[0_6px_24px_rgb(109,40,217,0.06)] text-xs font-bold text-slate-600">
                <a href="#fitur" class="hover:text-purple-700 transition">Fitur</a>
                <a href="#hasil-belajar" class="hover:text-purple-700 transition">Rapor &amp; Nilai</a>
                <a href="#dashboard-macbook" class="hover:text-purple-700 transition">Admin &amp; Guru</a>
                <a href="#testimoni" class="hover:text-purple-700 transition">Testimoni</a>
                <a href="{{ route('registration.create') }}" class="hover:text-purple-700 transition">PPDB</a>
            </div>

            {{-- Right Actions --}}
            <div class="hidden md:flex items-center gap-2 shrink-0">
                <a href="{{ route('student.login') }}" class="px-4 py-2.5 rounded-full text-xs font-bold text-purple-700 bg-white/90 hover:bg-white border border-purple-200/80 shadow-xs transition">
                    <i class="fa-solid fa-graduation-cap mr-1"></i> Portal Siswa
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2.5 rounded-full text-xs font-bold text-white bg-gradient-to-r from-[#7c3aed] to-[#4f46e5] hover:opacity-95 shadow-md shadow-purple-600/20 transition">
                    <i class="fa-solid fa-chalkboard-user mr-1"></i> Portal Guru
                </a>
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2.5 rounded-xl bg-white/80 border border-purple-100 text-slate-700 hover:bg-white" aria-label="Menu">
                <i class="fa-solid" :class="mobileMenu ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </nav>

        {{-- Mobile Dropdown --}}
        <div x-show="mobileMenu" x-cloak @click.away="mobileMenu = false"
            class="md:hidden mt-2 max-w-7xl mx-auto bg-white rounded-3xl border border-purple-100 shadow-xl p-5 space-y-3">
            <a href="#fitur" @click="mobileMenu = false" class="block py-2 text-sm font-bold text-slate-700">Fitur</a>
            <a href="#hasil-belajar" @click="mobileMenu = false" class="block py-2 text-sm font-bold text-slate-700">Rapor &amp; Nilai</a>
            <a href="#dashboard-macbook" @click="mobileMenu = false" class="block py-2 text-sm font-bold text-slate-700">Admin &amp; Guru</a>
            <a href="#testimoni" @click="mobileMenu = false" class="block py-2 text-sm font-bold text-slate-700">Testimoni</a>
            <a href="{{ route('registration.create') }}" @click="mobileMenu = false" class="block py-2 text-sm font-bold text-slate-700">PPDB</a>
            <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-2">
                <a href="{{ route('student.login') }}" class="text-center py-2.5 rounded-xl text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200">Portal Siswa</a>
                <a href="{{ route('login') }}" class="text-center py-2.5 rounded-xl text-xs font-bold text-white bg-purple-700">Portal Guru</a>
            </div>
        </div>
    </header>

    {{-- ===================================================================== --}}
    {{-- 1. HERO SECTION (Soft Violet Glow + Triple iPhone Authentic Blade UI)  --}}
    {{-- ===================================================================== --}}
    <section class="relative pt-10 sm:pt-14 pb-0 overflow-hidden bg-gradient-to-b from-[#f3efff] via-[#ede6ff] to-[#e4d9ff]">
        {{-- Decorative Orbit Rings --}}
        <div class="pointer-events-none absolute -left-24 top-20 w-72 h-72 rounded-full border-[18px] border-white/40 blur-[1px]"></div>
        <div class="pointer-events-none absolute -right-24 top-20 w-72 h-72 rounded-full border-[18px] border-white/40 blur-[1px]"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/80 border border-purple-200 text-[11px] font-extrabold uppercase tracking-wider text-purple-700 shadow-xs mb-5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Sistem Informasi Sekolah Terpadu
            </span>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-[1.12]">
                Manajemen Sekolah Pintar<br>
                untuk <span class="bg-gradient-to-r from-[#7c3aed] to-[#4f46e5] bg-clip-text text-transparent">Setiap Siswa</span>
            </h1>

            <p class="mt-4 max-w-2xl mx-auto text-sm sm:text-base text-slate-600 font-medium leading-relaxed">
                Satu platform untuk Portal Siswa, Rapor Digital & PBL, Ujian Online, Tagihan SPP Otomatis, hingga Kelas Video WebRTC — siap pakai di desktop dan mobile.
            </p>

            {{-- Hero CTAs --}}
            <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('student.login') }}" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold shadow-lg shadow-slate-900/20 transition active:scale-95">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    Buka Portal Siswa
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-full bg-white hover:bg-purple-50 text-slate-800 border border-purple-200 text-xs sm:text-sm font-bold shadow-sm transition active:scale-95">
                    <i class="fa-solid fa-laptop"></i>
                    Portal Guru & Admin
                </a>
            </div>
        </div>

        {{-- Hero Multi-iPhone Showcase (Authentic Student Blade UIs) --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-12 relative z-10">
            <div class="flex items-end justify-center">

                {{-- Left iPhone: Authentic student/activities/index.blade.php --}}
                <div class="hidden md:block w-[245px] lg:w-[265px] -mb-14 -mr-6 z-10 transform -rotate-3 translate-y-3 opacity-95">
                    <div class="iphone-frame">
                        <div class="iphone-screen h-[360px] bg-slate-50 p-3 flex flex-col">
                            <div class="iphone-statusbar mb-1">
                                <span>9:41</span>
                                <div class="iphone-island"></div>
                                <div class="flex items-center gap-1 text-[8.5px]">
                                    <i class="fa-solid fa-signal"></i>
                                    <i class="fa-solid fa-wifi"></i>
                                    <i class="fa-solid fa-battery-full text-[10px]"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-indigo-700 to-purple-700 rounded-2xl p-3 text-white mb-2.5">
                                <span class="text-[8px] font-extrabold uppercase tracking-wider text-indigo-200">Aktivitas Belajar</span>
                                <p class="text-xs font-extrabold mt-0.5">Tugas & Ujian Online</p>
                            </div>
                            <div class="space-y-2 text-left">
                                <div class="bg-white rounded-xl p-2.5 border border-slate-100 shadow-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="px-1.5 py-0.5 rounded bg-purple-100 text-purple-700 text-[8px] font-bold">TUGAS</span>
                                        <span class="text-[8px] font-bold text-emerald-600">Dikumpulkan</span>
                                    </div>
                                    <p class="text-[11px] font-bold text-slate-800 mt-1">Proyek Sains Terapan</p>
                                    <p class="text-[9px] text-slate-400">Nilai: 95 / 100</p>
                                </div>
                                <div class="bg-white rounded-xl p-2.5 border border-slate-100 shadow-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 text-[8px] font-bold">UJIAN</span>
                                        <span class="text-[8px] font-bold text-purple-600">Aktif</span>
                                    </div>
                                    <p class="text-[11px] font-bold text-slate-800 mt-1">Evaluasi Tengah Semester</p>
                                    <p class="text-[9px] text-slate-400">Durasi: 60 Menit</p>
                                </div>
                                <div class="bg-white rounded-xl p-2.5 border border-slate-100 shadow-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[8px] font-bold">VIDEO</span>
                                        <span class="text-[8px] font-bold text-rose-500">Live</span>
                                    </div>
                                    <p class="text-[11px] font-bold text-slate-800 mt-1">Kelas WebRTC 1-on-1</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Center iPhone: Authentic student/dashboard.blade.php --}}
                <div class="w-[290px] sm:w-[320px] -mb-10 z-20">
                    <div class="iphone-frame">
                        <div class="iphone-screen h-[430px] bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/30 p-3.5 flex flex-col">
                            <div class="iphone-statusbar mb-1">
                                <span>9:41</span>
                                <div class="iphone-island"></div>
                                <div class="flex items-center gap-1 text-[8.5px]">
                                    <i class="fa-solid fa-signal"></i>
                                    <i class="fa-solid fa-wifi"></i>
                                    <i class="fa-solid fa-battery-full text-[10px]"></i>
                                </div>
                            </div>

                            {{-- Authentic Mobile Hero Card from student/dashboard.blade.php --}}
                            <div class="bg-gradient-to-r from-purple-700 via-purple-800 to-indigo-800 rounded-3xl p-3.5 text-white shadow-lg shadow-purple-950/20 relative overflow-hidden text-left">
                                <div class="flex items-center justify-between gap-1 mb-2">
                                    <span class="px-2 py-0.5 bg-white/15 border border-white/20 rounded-full text-[8px] font-extrabold uppercase tracking-wider text-purple-100">
                                        SIS SEKOLAH Portal
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <span class="bg-white/10 px-1.5 py-0.5 rounded text-[8px] font-bold text-purple-100">Genap</span>
                                        <span class="bg-purple-500/30 px-1.5 py-0.5 rounded text-[8px] font-bold text-emerald-200 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Aktif
                                        </span>
                                    </div>
                                </div>
                                <h3 class="text-sm font-extrabold tracking-tight leading-tight">Selamat Datang, Siswa! 👋</h3>
                                <p class="text-[10px] text-purple-100 opacity-90 mt-0.5">NIS: 20260101 &bull; Kelas: 5 SD</p>
                            </div>

                            {{-- Authentic 4 Metric Cards --}}
                            <div class="grid grid-cols-2 gap-2 mt-3 text-left">
                                <div class="bg-white rounded-xl p-2.5 border border-slate-100 shadow-xs">
                                    <span class="text-[8px] font-bold uppercase text-slate-400">Rata-rata Nilai</span>
                                    <p class="text-lg font-extrabold text-slate-900 mt-0.5">94.8</p>
                                    <span class="text-[9px] font-bold text-emerald-600">Predikat A+</span>
                                </div>
                                <div class="bg-white rounded-xl p-2.5 border border-slate-100 shadow-xs">
                                    <span class="text-[8px] font-bold uppercase text-slate-400">Status SPP</span>
                                    <p class="text-base font-extrabold text-emerald-600 mt-0.5">Lunas</p>
                                    <span class="text-[9px] text-slate-400">Bulan Berjalan</span>
                                </div>
                            </div>

                            {{-- Peta Potensi & Karakter --}}
                            <div class="bg-white rounded-xl p-3 border border-slate-100 shadow-xs mt-2.5 text-left">
                                <span class="text-[8px] font-extrabold uppercase tracking-wider text-purple-600">Peta Potensi & Karakter</span>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 text-[9px] font-bold">Kreatif</span>
                                    <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[9px] font-bold">Logika</span>
                                    <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[9px] font-bold">Komunikatif</span>
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[9px] font-bold">Mandiri</span>
                                </div>
                            </div>

                            {{-- Quick Actions --}}
                            <div class="grid grid-cols-4 gap-1.5 mt-auto pt-2 text-center">
                                <div class="bg-purple-50 rounded-xl py-2 text-purple-700">
                                    <i class="fa-solid fa-book-open text-xs"></i>
                                    <span class="block text-[8px] font-bold mt-0.5">Materi</span>
                                </div>
                                <div class="bg-indigo-50 rounded-xl py-2 text-indigo-700">
                                    <i class="fa-solid fa-file-lines text-xs"></i>
                                    <span class="block text-[8px] font-bold mt-0.5">Rapor</span>
                                </div>
                                <div class="bg-amber-50 rounded-xl py-2 text-amber-700">
                                    <i class="fa-solid fa-receipt text-xs"></i>
                                    <span class="block text-[8px] font-bold mt-0.5">Tagihan</span>
                                </div>
                                <div class="bg-emerald-50 rounded-xl py-2 text-emerald-700">
                                    <i class="fa-solid fa-video text-xs"></i>
                                    <span class="block text-[8px] font-bold mt-0.5">Kelas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right iPhone: Authentic student/invoices/index.blade.php --}}
                <div class="hidden md:block w-[245px] lg:w-[265px] -mb-14 -ml-6 z-10 transform rotate-3 translate-y-3 opacity-95">
                    <div class="iphone-frame">
                        <div class="iphone-screen h-[360px] bg-slate-50 p-3 flex flex-col text-left">
                            <div class="iphone-statusbar mb-1">
                                <span>9:41</span>
                                <div class="iphone-island"></div>
                                <div class="flex items-center gap-1 text-[8.5px]">
                                    <i class="fa-solid fa-signal"></i>
                                    <i class="fa-solid fa-wifi"></i>
                                    <i class="fa-solid fa-battery-full text-[10px]"></i>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mb-2.5">
                                <div>
                                    <span class="text-[8px] font-extrabold uppercase text-purple-600">Keuangan Siswa</span>
                                    <p class="text-xs font-extrabold text-slate-900">Tagihan & Kwitansi</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[8px] font-bold">Auto-Billing</span>
                            </div>
                            <div class="space-y-2">
                                <div class="bg-white rounded-xl p-2.5 border border-slate-100 shadow-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-bold text-slate-400">INV-2026-011</span>
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[8px] font-extrabold">LUNAS</span>
                                    </div>
                                    <p class="text-xs font-extrabold text-slate-900 mt-1">Rp 1.250.000</p>
                                    <div class="mt-2 flex gap-1.5">
                                        <span class="px-2 py-1 rounded-lg bg-purple-50 text-purple-700 text-[8px] font-bold">PDF Invoice</span>
                                        <span class="px-2 py-1 rounded-lg bg-amber-50 text-amber-700 text-[8px] font-bold">Kwitansi</span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-2.5 border border-slate-100 shadow-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-bold text-slate-400">INV-2026-012</span>
                                        <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 text-[8px] font-extrabold">VERIFIKASI</span>
                                    </div>
                                    <p class="text-xs font-extrabold text-slate-900 mt-1">Rp 1.250.000</p>
                                    <p class="text-[8px] text-slate-400 mt-1">Bukti transfer telah diunggah</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 2. BENTO FEATURE GRID + CENTER IPHONE                                 --}}
    {{-- ===================================================================== --}}
    <section id="fitur" class="py-20 sm:py-24 bg-white relative z-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="inline-block px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-[11px] font-extrabold uppercase tracking-wider mb-3">
                    Fitur Lengkap
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Semua yang Dibutuhkan untuk Pembelajaran Modern
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-500">
                    Dirancang khusus untuk memudahkan siswa, guru, orang tua, dan admin sekolah dalam satu ekosistem terpadu.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-stretch">
                {{-- Left Column (2 Cards) --}}
                <div class="flex flex-col gap-5">
                    <div class="flex-1 bg-slate-50/80 hover:bg-purple-50/40 border border-slate-200/70 rounded-3xl p-6 transition">
                        <div class="w-10 h-10 rounded-2xl bg-purple-600 text-white flex items-center justify-center shadow-md shadow-purple-600/20 mb-4">
                            <i class="fa-solid fa-users-gear"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Manajemen Siswa & PPDB</h3>
                        <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Pendaftaran online terintegrasi, verifikasi berkas admin, hingga aktivasi akun portal siswa otomatis.
                        </p>
                    </div>
                    <div class="flex-1 bg-slate-50/80 hover:bg-purple-50/40 border border-slate-200/70 rounded-3xl p-6 transition">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-md shadow-indigo-600/20 mb-4">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Tugas & Ujian Online</h3>
                        <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Buat tugas, kuis, dan ujian pilihan ganda/esai lengkap dengan timer real-time dan penilaian guru.
                        </p>
                    </div>
                </div>

                {{-- Center Column: Purple Card with iPhone Ruang Belajar UI --}}
                <div class="bg-gradient-to-b from-[#a78bfa] to-[#7c3aed] rounded-3xl p-6 pb-0 flex flex-col justify-between overflow-hidden shadow-xl shadow-purple-600/15">
                    <div class="text-center text-white mb-4">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-purple-100">Ruang Belajar LMS</span>
                        <h3 class="text-lg font-extrabold mt-0.5">Materi & Diskusi Interaktif</h3>
                    </div>
                    <div class="w-full max-w-[230px] mx-auto -mb-10">
                        <div class="iphone-frame">
                            <div class="iphone-screen h-[290px] bg-white p-3 text-left">
                                <div class="iphone-statusbar mb-1">
                                    <span>9:41</span>
                                    <div class="iphone-island"></div>
                                    <div class="flex items-center gap-1 text-[8.5px]">
                                        <i class="fa-solid fa-signal"></i>
                                        <i class="fa-solid fa-wifi"></i>
                                        <i class="fa-solid fa-battery-full text-[10px]"></i>
                                    </div>
                                </div>
                                <div class="bg-purple-50 border border-purple-100 rounded-xl p-2.5 mb-2">
                                    <span class="text-[8px] font-extrabold uppercase text-purple-600">Kursus Aktif</span>
                                    <p class="text-[11px] font-extrabold text-slate-900 mt-0.5">Matematika & Logika Terapan</p>
                                    <div class="w-full bg-purple-200 h-1.5 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-purple-600 h-full w-4/5 rounded-full"></div>
                                    </div>
                                    <span class="text-[8px] font-bold text-purple-700 mt-1 block">80% Selesai</span>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100">
                                        <span class="text-[10px] font-bold text-slate-800">1. Konsep Pecahan</span>
                                        <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                                    </div>
                                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100">
                                        <span class="text-[10px] font-bold text-slate-800">2. Forum Diskusi Kelas</span>
                                        <i class="fa-solid fa-comments text-purple-600 text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column (2 Cards) --}}
                <div class="flex flex-col gap-5">
                    <div class="flex-1 bg-slate-50/80 hover:bg-purple-50/40 border border-slate-200/70 rounded-3xl p-6 transition">
                        <div class="w-10 h-10 rounded-2xl bg-violet-600 text-white flex items-center justify-center shadow-md shadow-violet-600/20 mb-4">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Tagihan SPP & Kwitansi PDF</h3>
                        <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Otomatisasi tagihan bulanan, upload bukti transfer siswa, hingga cetak invoice & kwitansi PDF instan.
                        </p>
                    </div>
                    <div class="flex-1 bg-slate-50/80 hover:bg-purple-50/40 border border-slate-200/70 rounded-3xl p-6 transition">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md shadow-emerald-600/20 mb-4">
                            <i class="fa-solid fa-video"></i>
                        </div>
                        <h3 class="text-base font-extrabold text-slate-900">Kelas Video WebRTC & Chat</h3>
                        <p class="mt-2 text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Tatap muka kelas online langsung dari browser tanpa aplikasi tambahan, plus chat guru-siswa.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Bottom Center Feature Card --}}
            <div class="mt-5 max-w-md mx-auto bg-slate-50/80 border border-slate-200/70 rounded-3xl p-6 text-center">
                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center mx-auto shadow-md shadow-amber-500/20 mb-3">
                    <i class="fa-solid fa-award"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Rapor Akademik, Diniyah, Ibadah & PBL</h3>
                <p class="mt-1.5 text-xs sm:text-sm text-slate-500">
                    Mendukung 4 jenis rapor lengkap dengan penilaian KKM, rentang mutu, proyek bulanan, dan ekspor PDF siap cetak.
                </p>
            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 3. SPLIT SPOTLIGHT: MONITOR STUDENT RESULTS (iPhone Rapor UI)         --}}
    {{-- ===================================================================== --}}
    <section id="hasil-belajar" class="py-16 sm:py-24 bg-slate-50/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
            {{-- Left: Purple Box + iPhone Rapor Mockup --}}
            <div class="lg:col-span-6">
                <div class="bg-gradient-to-br from-[#8b5cf6] to-[#6d28d9] rounded-[36px] p-8 pb-0 overflow-hidden shadow-2xl shadow-purple-600/20">
                    <div class="max-w-[270px] mx-auto -mb-10">
                        <div class="iphone-frame">
                            <div class="iphone-screen h-[400px] bg-white p-4 text-left">
                                <div class="iphone-statusbar mb-1">
                                <span>9:41</span>
                                <div class="iphone-island"></div>
                                <div class="flex items-center gap-1 text-[8.5px]">
                                    <i class="fa-solid fa-signal"></i>
                                    <i class="fa-solid fa-wifi"></i>
                                    <i class="fa-solid fa-battery-full text-[10px]"></i>
                                </div>
                            </div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-extrabold text-slate-900">Hasil Rapor Semester</span>
                                    <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[9px] font-bold">Final</span>
                                </div>

                                {{-- Score Ring --}}
                                <div class="bg-purple-50/70 border border-purple-100 rounded-2xl p-4 text-center mb-3">
                                    <div class="w-24 h-24 rounded-full border-[8px] border-purple-600 border-t-purple-200 mx-auto flex flex-col items-center justify-center bg-white shadow-inner">
                                        <span class="text-xl font-extrabold text-slate-900">94.8%</span>
                                        <span class="text-[8px] font-bold text-emerald-600 uppercase">Mumtaz</span>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-600 mt-2">Rata-rata Capaian Kompetensi</p>
                                </div>

                                {{-- Subject List --}}
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-[10px] bg-slate-50 px-3 py-2 rounded-xl">
                                        <span class="font-bold text-slate-700">Matematika Terapan</span>
                                        <span class="font-extrabold text-purple-700">96 (A)</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] bg-slate-50 px-3 py-2 rounded-xl">
                                        <span class="font-bold text-slate-700">Project Based Learning</span>
                                        <span class="font-extrabold text-emerald-600">95 (A)</span>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] bg-slate-50 px-3 py-2 rounded-xl">
                                        <span class="font-bold text-slate-700">Diniyah & Ibadah</span>
                                        <span class="font-extrabold text-indigo-700">94 (A)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Copy & Highlights --}}
            <div class="lg:col-span-6">
                <span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-[11px] font-extrabold uppercase tracking-wider">
                    Evaluasi Akademik
                </span>
                <h2 class="mt-4 text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Pantau Perkembangan &amp; Rapor Siswa dengan Mudah
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                    Orang tua dan siswa dapat melihat nilai tugas, hasil ujian, catatan guru, hingga mengunduh rapor PDF resmi kapan saja secara transparan.
                </p>

                <div class="mt-8 space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-full bg-purple-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md shadow-purple-600/20">
                            01
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-slate-900">Akses Rapor &amp; Nilai Real-time</h3>
                            <p class="mt-1 text-xs sm:text-sm text-slate-500">
                                Nilai ujian dan tugas langsung tersinkronisasi ke buku rapor semester secara otomatis setelah divalidasi guru.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-md shadow-indigo-600/20">
                            02
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-slate-900">Insight Potensi &amp; Project Based Learning</h3>
                            <p class="mt-1 text-xs sm:text-sm text-slate-500">
                                Dilengkapi pemetaan kepribadian, kecerdasan majemuk, dan dokumentasi proyek bulanan setiap siswa.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 4. MACBOOK SHOWCASE: ADMIN & GURU MANAGEMENT                          --}}
    {{-- ===================================================================== --}}
    <section id="dashboard-macbook" class="py-16 sm:py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {{-- Left Copy + Stats --}}
            <div class="lg:col-span-5">
                <span class="inline-block px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-extrabold uppercase tracking-wider">
                    Portal Guru &amp; Admin
                </span>
                <h2 class="mt-4 text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Kelola Sekolah Lebih Cerdas dari Satu Dashboard
                </h2>
                <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
                    Dari pengelolaan data siswa, penjadwalan kelas, manajemen tagihan &amp; kwitansi, hingga penerbitan Surat Keterangan (SK) dalam hitungan klik.
                </p>

                <div class="mt-6">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-gradient-to-r from-[#7c3aed] to-[#4f46e5] text-white text-xs sm:text-sm font-bold shadow-lg shadow-purple-600/25 hover:opacity-95 transition">
                        Masuk Dashboard Admin &amp; Guru <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                {{-- Floating Metric Strip --}}
                <div class="mt-8 grid grid-cols-3 gap-3 bg-slate-50 border border-slate-200/80 rounded-2xl p-4">
                    <div>
                        <p class="text-lg sm:text-xl font-extrabold text-purple-700">100%</p>
                        <p class="text-[11px] font-semibold text-slate-500">Paperless PDF</p>
                    </div>
                    <div>
                        <p class="text-lg sm:text-xl font-extrabold text-indigo-700">4 Jenis</p>
                        <p class="text-[11px] font-semibold text-slate-500">Rapor Digital</p>
                    </div>
                    <div>
                        <p class="text-lg sm:text-xl font-extrabold text-emerald-600">24/7</p>
                        <p class="text-[11px] font-semibold text-slate-500">Akses Cloud</p>
                    </div>
                </div>
            </div>

            {{-- Right: Realistic MacBook Pro Mockup (Authentic Admin/Teacher Blade UI) --}}
            <div class="lg:col-span-7 px-3 sm:px-6">
                <div class="macbook-lid">
                    <div class="macbook-notch"></div>
                    <div class="macbook-screen bg-slate-50 p-3 sm:p-4 text-left">
                        {{-- Top Browser/App Header --}}
                        <div class="flex items-center justify-between border-b border-slate-200 pb-2.5 mb-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('logos/sis-sekolah-logo.png') }}" alt="Logo" class="w-5 h-5 rounded object-contain">
                                <span class="text-[11px] font-extrabold text-slate-800">SIS SEKOLAH — Admin &amp; Teacher Workspace</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-md bg-purple-100 text-purple-700 text-[9px] font-bold">Superadmin</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-3">
                            {{-- Mini Sidebar --}}
                            <div class="hidden sm:block col-span-3 space-y-1 text-[10px] font-bold text-slate-600">
                                <div class="px-2.5 py-1.5 rounded-lg bg-purple-600 text-white flex items-center gap-1.5">
                                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-200/60 flex items-center gap-1.5">
                                    <i class="fa-solid fa-users"></i> Data Siswa
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-200/60 flex items-center gap-1.5">
                                    <i class="fa-solid fa-file-invoice"></i> Tagihan SPP
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-200/60 flex items-center gap-1.5">
                                    <i class="fa-solid fa-award"></i> Manajemen Rapor
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg hover:bg-slate-200/60 flex items-center gap-1.5">
                                    <i class="fa-solid fa-book"></i> Ruang Belajar
                                </div>
                            </div>

                            {{-- Main Content Area --}}
                            <div class="col-span-12 sm:col-span-9 space-y-2.5">
                                {{-- Stats Row --}}
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-[8px] font-bold uppercase text-slate-400">Siswa Aktif</span>
                                        <p class="text-sm font-extrabold text-slate-900">184</p>
                                    </div>
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-[8px] font-bold uppercase text-slate-400">Rapor Final</span>
                                        <p class="text-sm font-extrabold text-purple-700">98%</p>
                                    </div>
                                    <div class="bg-white p-2.5 rounded-xl border border-slate-200/70">
                                        <span class="text-[8px] font-bold uppercase text-slate-400">SPP Lunas</span>
                                        <p class="text-sm font-extrabold text-emerald-600">92%</p>
                                    </div>
                                </div>

                                {{-- Authentic Table Preview (admin/reports & invoices) --}}
                                <div class="bg-white rounded-xl border border-slate-200/70 p-2.5">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[10px] font-extrabold text-slate-800">Daftar Rapor &amp; Tagihan Siswa</span>
                                        <span class="px-2 py-0.5 rounded bg-slate-900 text-white text-[8px] font-bold">+ Tambah</span>
                                    </div>
                                    <div class="space-y-1.5 text-[9px]">
                                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                                            <span class="font-bold text-slate-800">Aisyah Putri (Kelas 5)</span>
                                            <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 font-bold">Rapor Final</span>
                                            <span class="font-bold text-purple-700">Unduh PDF</span>
                                        </div>
                                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                                            <span class="font-bold text-slate-800">Zaidan Pratama (Kelas 8)</span>
                                            <span class="px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 font-bold">SPP Lunas</span>
                                            <span class="font-bold text-purple-700">Kwitansi</span>
                                        </div>
                                        <div class="flex items-center justify-between py-1.5">
                                            <span class="font-bold text-slate-800">Nabila Azzahra (Kelas 10)</span>
                                            <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-700 font-bold">Review PBL</span>
                                            <span class="font-bold text-purple-700">Detail</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="macbook-base"></div>
            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 5. TESTIMONIALS GRID                                                  --}}
    {{-- ===================================================================== --}}
    <section id="testimoni" class="py-16 sm:py-24 bg-slate-50/70 border-y border-slate-200/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Dipercaya Sekolah, Guru, &amp; Orang Tua
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Pengalaman nyata menggunakan ekosistem digital SIS SEKOLAH setiap hari.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @php
                    $testimonials = [
                        ['name' => 'Ustadzah Rahma, S.Pd', 'role' => 'Kepala Sekolah', 'quote' => 'Pengelolaan rapor akademik dan diniyah jauh lebih cepat. Proses finalisasi dan cetak PDF kini selesai dalam hitungan menit.'],
                        ['name' => 'Bapak Hendra Wijaya', 'role' => 'Wali Murid', 'quote' => 'Sangat terbantu! Saya bisa pantau nilai tugas anak, jadwal kelas online, dan download kwitansi SPP langsung dari HP.'],
                        ['name' => 'Ibu Nadia Kusuma', 'role' => 'Guru & Wali Kelas', 'quote' => 'Fitur Ruang Belajar dan forum diskusi membuat interaksi dengan siswa di luar jam tatap muka tetap aktif dan terarah.'],
                        ['name' => 'Aisyah Putri', 'role' => 'Siswa Kelas 5', 'quote' => 'Tampilan portal siswanya rapi dan gampang dipakai. Cek materi baru atau kerjakan ujian online jadi lebih seru.'],
                        ['name' => 'Fajar Pratama', 'role' => 'Admin Keuangan', 'quote' => 'Otomatisasi invoice SPP bulanan dan verifikasi bukti bayar sangat memangkas pekerjaan manual administrasi sekolah.'],
                        ['name' => 'Ibu Dewi Lestari', 'role' => 'Koordinator Kurikulum', 'quote' => 'Pemetaan potensi dan dokumentasi Project Based Learning tercatat rapi dari awal semester hingga kenaikan kelas.'],
                    ];
                @endphp

                @foreach ($testimonials as $t)
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/70 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-extrabold text-slate-900">{{ $t['name'] }}</p>
                                    <p class="text-[11px] font-semibold text-purple-600">{{ $t['role'] }}</p>
                                </div>
                                <span class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-quote-right"></i>
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">"{{ $t['quote'] }}"</p>
                        </div>
                        <div class="mt-4 flex items-center gap-1 text-amber-400 text-xs">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 6. PURPLE CTA BANNER WITH IPHONE MOCKUP                               --}}
    {{-- ===================================================================== --}}
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="bg-gradient-to-r from-[#8b5cf6] via-[#7c3aed] to-[#4f46e5] rounded-[36px] p-8 sm:p-12 pb-0 sm:pb-0 text-white overflow-hidden shadow-2xl shadow-purple-600/25 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <div class="md:col-span-7 pb-8 sm:pb-12">
                    <h2 class="text-2xl sm:text-4xl font-extrabold leading-tight tracking-tight">
                        Sekolah &amp; Siswa Terhubung dalam Satu Tempat
                    </h2>
                    <p class="mt-3 text-sm text-purple-100 max-w-md leading-relaxed">
                        Mulai gunakan SIS SEKOLAH sekarang. Akses cepat untuk siswa, guru, dan administrator sekolah tanpa ribet.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('student.login') }}" class="px-5 py-3 rounded-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition shadow-md">
                            Masuk Portal Siswa
                        </a>
                        <a href="{{ route('registration.create') }}" class="px-5 py-3 rounded-full bg-white hover:bg-purple-50 text-purple-800 text-xs font-bold transition shadow-md">
                            Daftar PPDB Online
                        </a>
                    </div>
                </div>

                <div class="md:col-span-5 flex justify-center self-end">
                    <div class="w-[230px] -mb-14">
                        <div class="iphone-frame">
                            <div class="iphone-screen h-[260px] bg-white p-3 text-left">
                                <div class="iphone-statusbar mb-1">
                                    <span>9:41</span>
                                    <div class="iphone-island"></div>
                                    <div class="flex items-center gap-1 text-[8.5px]">
                                        <i class="fa-solid fa-signal"></i>
                                        <i class="fa-solid fa-wifi"></i>
                                        <i class="fa-solid fa-battery-full text-[10px]"></i>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-r from-purple-700 to-indigo-700 rounded-xl p-3 text-white mb-2">
                                    <span class="text-[8px] font-bold uppercase text-purple-200">SIS SEKOLAH</span>
                                    <p class="text-xs font-extrabold mt-0.5">Portal Akademik</p>
                                </div>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <div class="p-2 rounded-xl bg-purple-50 text-purple-800">
                                        <span class="text-[8px] font-bold block">Rata-rata</span>
                                        <span class="text-sm font-extrabold">94.8</span>
                                    </div>
                                    <div class="p-2 rounded-xl bg-emerald-50 text-emerald-800">
                                        <span class="text-[8px] font-bold block">Tagihan</span>
                                        <span class="text-sm font-extrabold">Lunas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================================================================== --}}
    {{-- 7. DARK FOOTER WITH GIANT WATERMARK                                   --}}
    {{-- ===================================================================== --}}
    <footer class="bg-[#0b0f19] text-slate-300 pt-16 pb-6 overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 pb-12 border-b border-slate-800/80">
                <div class="md:col-span-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('logos/sis-sekolah-logo.png') }}" alt="SIS SEKOLAH" class="w-10 h-10 rounded-xl bg-white p-1 object-contain">
                        <span class="text-lg font-extrabold text-white tracking-tight">SIS SEKOLAH</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-400 max-w-sm leading-relaxed">
                        Sistem Informasi Sekolah &amp; Learning Management System modern untuk mendukung pembelajaran digital, manajemen akademik, dan keuangan sekolah.
                    </p>
                </div>

                <div class="md:col-span-3 space-y-2.5 text-xs">
                    <p class="font-extrabold text-white uppercase tracking-wider mb-3">Navigasi</p>
                    <a href="#fitur" class="block hover:text-white transition">Fitur Unggulan</a>
                    <a href="#hasil-belajar" class="block hover:text-white transition">Rapor &amp; Nilai</a>
                    <a href="#dashboard-macbook" class="block hover:text-white transition">Dashboard Guru &amp; Admin</a>
                    <a href="#testimoni" class="block hover:text-white transition">Testimoni</a>
                </div>

                <div class="md:col-span-4 space-y-2.5 text-xs">
                    <p class="font-extrabold text-white uppercase tracking-wider mb-3">Akses Cepat Portal</p>
                    <a href="{{ route('student.login') }}" class="block hover:text-white transition">Login Portal Siswa</a>
                    <a href="{{ route('login') }}" class="block hover:text-white transition">Login Portal Guru &amp; Admin</a>
                    <a href="{{ route('registration.create') }}" class="block hover:text-white transition">Pendaftaran Siswa Baru (PPDB)</a>
                    <a href="{{ route('student.activate-form') }}" class="block hover:text-white transition">Aktivasi Akun Siswa</a>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <p>&copy; {{ date('Y') }} SIS SEKOLAH. All rights reserved.</p>
                <p>Powered by Laravel &amp; Vercel</p>
            </div>

            {{-- Giant Brand Display Typography --}}
            <div class="mt-8 select-none text-center">
                <span class="block text-[13vw] sm:text-[11vw] lg:text-[9.5rem] font-extrabold tracking-tighter leading-none text-white/[0.92]">
                    SIS SEKOLAH
                </span>
            </div>
        </div>
    </footer>

</body>
</html>
