@extends('layouts.app')

@section('title', 'Dashboard - SIS SEKOLAH')

@section('content')
@php
    $user = auth()->user();
    $isAdmin = $user && ($user->isSuperadmin() || $user->isPimpinan() || $user->isAdmin());
    $isGuruOnly = $user && $user->isGuru() && !$isAdmin;

    $greeting = match(true) {
        now()->hour < 11  => 'Selamat Pagi',
        now()->hour < 15  => 'Selamat Siang',
        now()->hour < 18  => 'Selamat Sore',
        default           => 'Selamat Malam',
    };

    $roleLabel = match($user?->role) {
        'superadmin' => 'Super Admin',
        'pimpinan'   => 'Pimpinan',
        'admin'      => 'Admin',
        'guru', 'teacher' => 'Guru / Pengajar',
        default      => 'Staf',
    };
@endphp

<div class="p-4 sm:p-6 lg:p-8 max-w-[1400px] mx-auto space-y-5">

    {{-- ═══════════════════════════════════════════════════════════
         TOP BENTO: Welcome Banner + Today's Date
    ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Welcome Banner (spans 2 cols) --}}
        <div class="lg:col-span-2 relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 p-6 sm:p-7 text-white shadow-lg">
            {{-- Decorative circles --}}
            <div class="absolute -top-10 -right-10 w-52 h-52 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-16 right-24 w-64 h-64 bg-purple-600/10 rounded-full blur-2xl pointer-events-none"></div>

            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-[10px] font-extrabold uppercase tracking-widest text-teal-300">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                {{ $roleLabel }}
            </span>

            <h1 class="mt-3 text-2xl sm:text-3xl font-black tracking-tight">
                {{ $greeting }}, {{ $user?->name }}!
            </h1>
            <p class="mt-1 text-sm text-slate-300 max-w-md">
                Kelola aktivitas akademik, rapor siswa, tagihan SPP, dan ruang belajar dari satu dashboard terpadu.
            </p>

            {{-- Quick nav pills --}}
            <div class="mt-5 flex flex-wrap gap-2">
                <a href="{{ route('teacher.activities.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-teal-500/30 border border-white/10 text-xs font-semibold text-white transition">
                    📝 Tugas & Ujian
                </a>
                <a href="{{ route('teacher.lessons.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-teal-500/30 border border-white/10 text-xs font-semibold text-white transition">
                    📚 Ruang Belajar
                </a>
                <a href="{{ route('attendances.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-teal-500/30 border border-white/10 text-xs font-semibold text-white transition">
                    ✅ Absensi
                </a>
                @if ($canViewFinance ?? false)
                <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-teal-500/30 border border-white/10 text-xs font-semibold text-white transition">
                    💰 Tagihan SPP
                </a>
                @endif
            </div>
        </div>

        {{-- Date / Clock Card --}}
        <div class="rounded-2xl bg-white border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Hari Ini</p>
                <p class="mt-1 text-3xl font-black text-slate-900">{{ now()->translatedFormat('d') }}</p>
                <p class="text-sm font-semibold text-slate-700">{{ now()->translatedFormat('l, F Y') }}</p>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-500">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Semester Aktif: <span class="font-bold text-slate-700">{{ $activeSemester ?? 'Ganjil 2026/2027' }}</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         STAT BENTO CARDS ROW
    ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">

        {{-- Siswa Aktif --}}
        <div class="sm:col-span-1 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-4 text-white shadow-md shadow-teal-500/20">
            <div class="flex items-start justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest opacity-80">Siswa Aktif</span>
                <span class="text-lg">🎓</span>
            </div>
            <p class="mt-2 text-3xl font-black">{{ $totalStudents ?? 0 }}</p>
            <p class="text-[10px] opacity-70 mt-1">Total terdaftar</p>
        </div>

        {{-- Siswa Berprestasi --}}
        <div class="sm:col-span-1 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-4 text-white shadow-md shadow-amber-400/20">
            <div class="flex items-start justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest opacity-80">Berprestasi</span>
                <span class="text-lg">🏆</span>
            </div>
            <p class="mt-2 text-3xl font-black">{{ $studentsWithHighScore ?? 0 }}</p>
            <p class="text-[10px] opacity-70 mt-1">Rata-rata ≥ 90</p>
        </div>

        {{-- Kehadiran Hari Ini --}}
        <div class="sm:col-span-1 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-4 text-white shadow-md shadow-emerald-500/20">
            <div class="flex items-start justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest opacity-80">Hadir Hari Ini</span>
                <span class="text-lg">📋</span>
            </div>
            <p class="mt-2 text-3xl font-black">{{ $hadirToday ?? 0 }}<span class="text-base font-semibold opacity-70">/{{ $totalExpectedToday ?? 0 }}</span></p>
            <p class="text-[10px] opacity-70 mt-1">Kehadiran siswa</p>
        </div>

        {{-- Layanan Aktif --}}
        <div class="sm:col-span-1 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-4 text-white shadow-md shadow-indigo-500/20">
            <div class="flex items-start justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest opacity-80">Layanan</span>
                <span class="text-lg">⚙️</span>
            </div>
            <p class="mt-2 text-3xl font-black">{{ $totalServices ?? 0 }}</p>
            <p class="text-[10px] opacity-70 mt-1">Paket aktif</p>
        </div>

        {{-- Tagihan Lunas --}}
        <div class="sm:col-span-1 bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Lunas Bulan Ini</span>
                <span class="text-lg">✅</span>
            </div>
            <p class="mt-2 text-3xl font-black text-emerald-600">{{ $paidInvoices ?? 0 }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Tagihan terbayar</p>
        </div>

        {{-- Tagihan Menunggak --}}
        <div class="sm:col-span-1 bg-white border border-rose-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <span class="text-[10px] font-bold uppercase tracking-widest text-rose-400">Menunggak</span>
                <span class="text-lg">⚠️</span>
            </div>
            <p class="mt-2 text-3xl font-black text-rose-600">{{ $overdueInvoices ?? 0 }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Perlu tindakan</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         MAIN BENTO GRID: Shortcuts + Recent Activity
    ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- LEFT: Feature Shortcuts (2 cols) --}}
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-sm font-extrabold text-slate-500 uppercase tracking-widest">Akses Cepat</h2>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3">

                {{-- Tugas & Ujian --}}
                <a href="{{ route('teacher.activities.index') }}"
                   class="group relative overflow-hidden bg-white hover:bg-purple-600 border border-slate-200/80 rounded-2xl p-4 flex flex-col gap-3 transition-all duration-200 shadow-sm hover:shadow-purple-500/25 hover:shadow-lg hover:border-transparent">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 group-hover:bg-white/20 text-purple-600 group-hover:text-white flex items-center justify-center transition text-lg">📝</div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900 group-hover:text-white">Tugas & Ujian</p>
                        <p class="text-[11px] text-slate-400 group-hover:text-purple-200 leading-tight mt-0.5">Kelola penugasan siswa</p>
                    </div>
                    @if(($pendingGradingCount ?? 0) > 0)
                    <span class="absolute top-3 right-3 w-5 h-5 bg-rose-500 text-white text-[9px] font-black rounded-full flex items-center justify-center">{{ $pendingGradingCount }}</span>
                    @endif
                </a>

                {{-- Ruang Belajar --}}
                <a href="{{ route('teacher.lessons.index') }}"
                   class="group relative overflow-hidden bg-white hover:bg-teal-600 border border-slate-200/80 rounded-2xl p-4 flex flex-col gap-3 transition-all duration-200 shadow-sm hover:shadow-teal-500/25 hover:shadow-lg hover:border-transparent">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 group-hover:bg-white/20 text-teal-600 group-hover:text-white flex items-center justify-center transition text-lg">📚</div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900 group-hover:text-white">Ruang Belajar</p>
                        <p class="text-[11px] text-slate-400 group-hover:text-teal-200 leading-tight mt-0.5">Kursus & materi interaktif</p>
                    </div>
                </a>

                {{-- Absensi --}}
                <a href="{{ route('attendances.index') }}"
                   class="group overflow-hidden bg-white hover:bg-emerald-600 border border-slate-200/80 rounded-2xl p-4 flex flex-col gap-3 transition-all duration-200 shadow-sm hover:shadow-emerald-500/25 hover:shadow-lg hover:border-transparent">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 group-hover:bg-white/20 text-emerald-600 group-hover:text-white flex items-center justify-center transition text-lg">✅</div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900 group-hover:text-white">Absensi</p>
                        <p class="text-[11px] text-slate-400 group-hover:text-emerald-200 leading-tight mt-0.5">Rekap kehadiran harian</p>
                    </div>
                </a>

                {{-- Data Siswa --}}
                <a href="{{ route('students.index') }}"
                   class="group overflow-hidden bg-white hover:bg-indigo-600 border border-slate-200/80 rounded-2xl p-4 flex flex-col gap-3 transition-all duration-200 shadow-sm hover:shadow-indigo-500/25 hover:shadow-lg hover:border-transparent">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 group-hover:bg-white/20 text-indigo-600 group-hover:text-white flex items-center justify-center transition text-lg">👨‍🎓</div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900 group-hover:text-white">Data Siswa</p>
                        <p class="text-[11px] text-slate-400 group-hover:text-indigo-200 leading-tight mt-0.5">Profil & kelas siswa</p>
                    </div>
                </a>

                {{-- Manajemen Rapor --}}
                <a href="{{ route('admin.reports.index') }}"
                   class="group overflow-hidden bg-white hover:bg-amber-500 border border-slate-200/80 rounded-2xl p-4 flex flex-col gap-3 transition-all duration-200 shadow-sm hover:shadow-amber-400/25 hover:shadow-lg hover:border-transparent">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 group-hover:bg-white/20 text-amber-600 group-hover:text-white flex items-center justify-center transition text-lg">📊</div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900 group-hover:text-white">Manajemen Rapor</p>
                        <p class="text-[11px] text-slate-400 group-hover:text-amber-100 leading-tight mt-0.5">Rapor akademik & diniyah</p>
                    </div>
                </a>

                {{-- Tagihan SPP --}}
                @if ($canViewFinance ?? false)
                <a href="{{ route('invoices.index') }}"
                   class="group overflow-hidden bg-white hover:bg-green-600 border border-slate-200/80 rounded-2xl p-4 flex flex-col gap-3 transition-all duration-200 shadow-sm hover:shadow-green-500/25 hover:shadow-lg hover:border-transparent">
                    <div class="w-10 h-10 rounded-xl bg-green-100 group-hover:bg-white/20 text-green-600 group-hover:text-white flex items-center justify-center transition text-lg">💰</div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900 group-hover:text-white">Tagihan SPP</p>
                        <p class="text-[11px] text-slate-400 group-hover:text-green-200 leading-tight mt-0.5">Kelola SPP & pembayaran</p>
                    </div>
                </a>
                @endif

                {{-- Kelas Video WebRTC --}}
                <a href="{{ route('teacher.video-conferences.index') }}"
                   class="group overflow-hidden bg-white hover:bg-rose-600 border border-slate-200/80 rounded-2xl p-4 flex flex-col gap-3 transition-all duration-200 shadow-sm hover:shadow-rose-500/25 hover:shadow-lg hover:border-transparent">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 group-hover:bg-white/20 text-rose-600 group-hover:text-white flex items-center justify-center transition text-lg">🎥</div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900 group-hover:text-white">Kelas Video</p>
                        <p class="text-[11px] text-slate-400 group-hover:text-rose-200 leading-tight mt-0.5">Ruang WebRTC online</p>
                    </div>
                </a>

                {{-- Pendaftaran PPDB --}}
                <a href="{{ route('admin.registrations.index') }}"
                   class="group overflow-hidden bg-white hover:bg-slate-800 border border-slate-200/80 rounded-2xl p-4 flex flex-col gap-3 transition-all duration-200 shadow-sm hover:shadow-slate-700/25 hover:shadow-lg hover:border-transparent">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-white/20 text-slate-600 group-hover:text-white flex items-center justify-center transition text-lg">📋</div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900 group-hover:text-white">PPDB</p>
                        <p class="text-[11px] text-slate-400 group-hover:text-slate-300 leading-tight mt-0.5">Pendaftaran siswa baru</p>
                    </div>
                </a>
            </div>

            {{-- Guru-specific: Assignments --}}
            @if ($isGuru && ($myAssignments ?? collect())->isNotEmpty())
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-3">Tugas Terbaru Saya</h3>
                <div class="space-y-2">
                    @foreach ($myAssignments as $asgn)
                    <div class="flex items-center justify-between gap-3 p-2.5 rounded-xl bg-slate-50 hover:bg-purple-50 transition group">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $asgn->title }}</p>
                            <p class="text-[11px] text-slate-400">{{ $asgn->subject?->name ?? '-' }} · Deadline: {{ $asgn->deadline ? \Carbon\Carbon::parse($asgn->deadline)->format('d M') : '-' }}</p>
                        </div>
                        <span class="shrink-0 text-xs font-bold px-2 py-0.5 rounded-full {{ $asgn->submissions_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }}">
                            {{ $asgn->submissions_count }} pengumpul
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Activity Feed --}}
        <div class="space-y-4">
            <h2 class="text-sm font-extrabold text-slate-500 uppercase tracking-widest">Aktivitas Terbaru</h2>

            {{-- Attendance Today --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest">Absensi Hari Ini</h3>
                    <a href="{{ route('attendances.index') }}" class="text-[11px] text-teal-600 font-semibold hover:underline">Lihat semua →</a>
                </div>

                @if(($recentAttendances ?? collect())->isEmpty())
                <div class="text-center py-4">
                    <span class="text-3xl">📋</span>
                    <p class="text-xs text-slate-400 mt-2">Belum ada absensi hari ini</p>
                </div>
                @else
                <div class="space-y-2">
                    @foreach ($recentAttendances ?? collect() as $att)
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-xs font-black text-slate-600 shrink-0">
                            {{ substr($att->siswa?->nama_siswa ?? '?', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-800 truncate">{{ $att->siswa?->nama_siswa ?? '-' }}</p>
                        </div>
                        <span class="shrink-0 text-[10px] font-bold px-1.5 py-0.5 rounded-md {{ match($att->status) { 'hadir' => 'bg-emerald-100 text-emerald-700', 'sakit' => 'bg-amber-100 text-amber-700', 'izin' => 'bg-blue-100 text-blue-700', default => 'bg-rose-100 text-rose-700' } }}">
                            {{ ucfirst($att->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Today's Conferences (Guru) --}}
            @if ($isGuru)
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest">Kelas Video Hari Ini</h3>
                    <a href="{{ route('teacher.video-conferences.index') }}" class="text-[11px] text-rose-600 font-semibold hover:underline">Semua →</a>
                </div>
                @if(($todayConferences ?? collect())->isEmpty())
                <div class="text-center py-4">
                    <span class="text-3xl">🎥</span>
                    <p class="text-xs text-slate-400 mt-2">Tidak ada kelas terjadwal</p>
                </div>
                @else
                <div class="space-y-2">
                    @foreach ($todayConferences as $conf)
                    <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-100">
                        <p class="text-xs font-bold text-slate-900">{{ $conf->title ?? 'Kelas Online' }}</p>
                        <p class="text-[11px] text-slate-400">{{ $conf->scheduled_at ? \Carbon\Carbon::parse($conf->scheduled_at)->format('H:i') : '-' }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            {{-- Financial Snapshot (Admin only) --}}
            @if ($canViewFinance ?? false)
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
                <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-widest mb-3">Keuangan Tahun Ini</h3>
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Pendapatan</span>
                        <span class="text-xs font-black text-emerald-600">Rp {{ number_format($revenue ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Pengeluaran</span>
                        <span class="text-xs font-black text-rose-600">Rp {{ number_format($totalExpenses ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-700">Net Cashflow</span>
                        <span class="text-xs font-black {{ ($netCashflow ?? 0) >= 0 ? 'text-teal-600' : 'text-rose-600' }}">
                            Rp {{ number_format($netCashflow ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            {{-- System Info Card --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 text-white shadow-md">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Sistem</p>
                <p class="mt-2 text-sm font-black">SIS SEKOLAH v2.0</p>
                <p class="text-[11px] text-slate-400 mt-1">Ruang Belajar · Rapor Digital · WebRTC</p>
                <div class="mt-4 flex items-center gap-1.5">
                    <span class="w-2 h-2 bg-teal-400 rounded-full animate-pulse"></span>
                    <span class="text-[11px] text-teal-400 font-semibold">Sistem Berjalan Normal</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
