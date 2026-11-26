<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logos/sis-sekolah-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logos/sis-sekolah-logo.png') }}">
    <title>@yield('title', config('app.name', 'SIS SEKOLAH'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#fafafc] text-slate-900 antialiased">

@if (auth()->check() && !auth()->user()->isStudent() && !request()->is('login', 'teacher/login', 'pendaftaran*'))
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex bg-[#fafafc]">

        {{-- Mobile Sidebar Overlay --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden"></div>

        {{-- Persistent Left Sidebar (Teacher & Admin Portal) --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:sticky top-0 left-0 z-50 w-64 h-screen bg-white border-r border-slate-200/80 flex flex-col transition-transform duration-200 ease-in-out shrink-0">

            {{-- Brand --}}
            <div class="h-16 px-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('logos/sis-sekolah-logo.png') }}" alt="SIS SEKOLAH" class="w-9 h-9 rounded-xl object-contain bg-sky-50 p-1">
                    <div>
                        <span class="block text-sm font-extrabold text-slate-900 leading-none">SIS SEKOLAH</span>
                        <span class="block text-[9px] font-bold uppercase tracking-wider text-sky-600 mt-0.5">
                            {{ auth()->user()->isSuperadmin() ? 'Superadmin' : 'Portal Guru' }}
                        </span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Navigation Links --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5 text-xs font-semibold">
                <div>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-sky-600 text-white shadow-md shadow-sky-600/20' : 'text-slate-600 hover:bg-sky-50 hover:text-sky-700' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center"></i> Dashboard
                    </a>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Pengajaran (Guru)</p>
                    <div class="space-y-1">
                        <a href="{{ route('teacher.activities.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('teacher.activities.*', 'teacher.assignments.*', 'teacher.exams.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-pen-to-square w-4 text-center"></i> Tugas &amp; Ujian
                        </a>
                        <a href="{{ route('teacher.lessons.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('teacher.lessons.*', 'teacher.courses.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-book-open w-4 text-center"></i> Ruang Belajar
                        </a>
                        <a href="{{ route('teacher.video-conferences.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('teacher.video-conference*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-video w-4 text-center"></i> Kelas Video WebRTC
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Akademik &amp; Rapor</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.reports.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.reports.index', 'admin.reports.create', 'admin.reports.show', 'admin.reports.edit') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-award w-4 text-center"></i> Manajemen Rapor
                        </a>
                        <a href="{{ route('admin.reports.bulk-pbl.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.reports.bulk-pbl.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-layer-group w-4 text-center"></i> Bulk PBL
                        </a>
                        <a href="{{ route('admin.reports.settings.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.reports.settings.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-sliders w-4 text-center"></i> Setting Rapor
                        </a>
                        <a href="{{ route('admin.semester-projects.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.semester-projects.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-diagram-project w-4 text-center"></i> Proyek Semester
                        </a>
                        <a href="{{ route('project-templates.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('project-templates.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-copy w-4 text-center"></i> Template Proyek
                        </a>
                        <a href="{{ route('admin.academic-settings.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.academic-settings.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-graduation-cap w-4 text-center"></i> KKM &amp; Mutu
                        </a>
                        <a href="{{ route('admin.report-categories.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.report-categories.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-tags w-4 text-center"></i> Kategori Rapor
                        </a>
                        <a href="{{ route('subjects.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('subjects.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-book w-4 text-center"></i> Mata Pelajaran
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Kesiswaan &amp; Surat</p>
                    <div class="space-y-1">
                        <a href="{{ route('students.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('students.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-users w-4 text-center"></i> Data Siswa
                        </a>
                        <a href="{{ route('attendances.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('attendances.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-clipboard-user w-4 text-center"></i> Absensi Kehadiran
                        </a>
                        <a href="{{ route('admin.registrations.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.registrations.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-user-plus w-4 text-center"></i> Pendaftaran PPDB
                        </a>
                        <a href="{{ route('surat-keterangan.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('surat-keterangan.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-envelope-open-text w-4 text-center"></i> Surat Keterangan
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Keuangan &amp; Sekolah</p>
                    <div class="space-y-1">
                        <a href="{{ route('invoices.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('invoices.index', 'invoices.create', 'invoices.show', 'invoices.edit') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i> Tagihan SPP
                        </a>
                        <a href="{{ route('invoices.automation') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('invoices.automation*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-robot w-4 text-center"></i> Otomatisasi SPP
                        </a>
                        <a href="{{ route('invoices.kwitansi-index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('invoices.kwitansi*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-receipt w-4 text-center"></i> Kwitansi
                        </a>
                        <a href="{{ route('services.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('services.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-cubes w-4 text-center"></i> Layanan Sekolah
                        </a>
                        <a href="{{ route('expense-categories.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('expense-categories.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-wallet w-4 text-center"></i> Pengeluaran
                        </a>
                        <a href="{{ route('school-profile.edit') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('school-profile.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-school w-4 text-center"></i> Profil Sekolah
                        </a>
                        <a href="{{ route('admin.compro-cms.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.compro-cms.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-globe w-4 text-center"></i> CMS Compro
                        </a>
                    </div>
                </div>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-3 border-t border-slate-100 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold transition">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content Column --}}
        <div class="flex-1 min-w-0 flex flex-col">
            <header class="h-16 bg-white/85 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-sky-50">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <a href="{{ route('compro.index') }}" class="text-xs font-bold text-slate-500 hover:text-sky-700 transition">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Lihat Landing Page
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-extrabold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold uppercase text-sky-600 mt-0.5">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </div>
@else
    @yield('content')
@endif

@include('components.demo-notice-modal')
@stack('scripts')
</body>
</html>
