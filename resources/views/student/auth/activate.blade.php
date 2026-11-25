@extends('layouts.app')

@section('title', 'Aktivasi Akun Portal Siswa - Sayyidah School')

@section('content')
@php
    $portalLabel = 'Portal Siswa & Wali';
    $portalTitle = 'Aktivasi Akun Belajar';
    $portalSubtitle = 'Silakan tentukan kata sandi baru untuk akun portal belajar Anda.';
    $gradientFrom = 'from-violet-600';
    $gradientTo = 'to-indigo-600';
    $accentText = 'text-indigo-600 dark:text-indigo-400 hover:text-indigo-500';
    $focusRing = 'focus:ring-indigo-500 focus:border-indigo-500';
    $accentColorHex = '#4f46e5';
    $bgGradient = 'from-violet-50/50 via-slate-50 to-indigo-50/50 dark:from-violet-950/20 dark:via-gray-950 dark:to-indigo-950/20';
    $buttonClass = 'bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 focus:ring-indigo-500 shadow-indigo-500/20';
    $badgeClass = 'bg-violet-100 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 border border-violet-200/50 dark:border-violet-800/30';
    $iconClass = 'text-violet-600 dark:text-violet-400';
    
    // Illustration representing activation / secure keys
    $illustration = '
    <div class="relative w-20 h-20 sm:w-28 sm:h-28 mx-auto flex items-center justify-center">
        <!-- Animated rings -->
        <div class="absolute inset-0 rounded-full bg-violet-500/10 dark:bg-violet-500/5 animate-ping duration-1000"></div>
        <div class="absolute inset-2 rounded-full bg-indigo-500/10 dark:bg-indigo-500/5 animate-pulse"></div>
        
        <!-- Key Icon Container -->
        <div class="relative w-14 h-14 sm:w-20 sm:h-20 bg-gradient-to-tr from-violet-500/10 to-indigo-500/10 border border-indigo-200/30 dark:border-indigo-800/30 rounded-2xl flex items-center justify-center shadow-lg backdrop-blur-md">
            <i class="fa-solid fa-key text-2xl sm:text-4xl text-indigo-600 dark:text-indigo-400"></i>
        </div>
    </div>';
@endphp

<style>
    @keyframes float-slow {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-16px) scale(1.04); }
    }
    @keyframes float-delayed {
        0%, 100% { transform: translateY(0) scale(1.04); }
        50% { transform: translateY(16px) scale(0.96); }
    }
    .animate-float-1 { animation: float-slow 8s ease-in-out infinite; }
    .animate-float-2 { animation: float-delayed 12s ease-in-out infinite; }
</style>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br {{ $bgGradient }} py-4 sm:py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:24px_24px] dark:bg-[radial-gradient(#1e293b_1.5px,transparent_1.5px)]">
    <!-- Floating background premium aesthetic blobs -->
    <div class="absolute top-1/4 -left-36 w-80 h-80 bg-gradient-to-tr {{ $gradientFrom }} {{ $gradientTo }} rounded-full blur-3xl opacity-15 dark:opacity-25 animate-float-1"></div>
    <div class="absolute bottom-1/4 -right-36 w-96 h-96 bg-gradient-to-bl {{ $gradientTo }} {{ $gradientFrom }} rounded-full blur-3xl opacity-15 dark:opacity-25 animate-float-2"></div>

    <div class="max-w-md w-full space-y-4 sm:space-y-6 relative z-10">
        <!-- Main Form Glassmorphic Panel -->
        <div class="bg-white/70 dark:bg-slate-900/75 backdrop-blur-xl border border-white/30 dark:border-slate-800/40 shadow-[0_24px_50px_-12px_rgba(0,0,0,0.12)] rounded-[28px] sm:rounded-[32px] p-5 sm:p-10 space-y-4 sm:space-y-6 transition-all duration-300 hover:shadow-[0_32px_64px_-16px_rgba(0,0,0,0.16)]">
            
            <!-- Top Branding Section -->
            <div class="text-center space-y-2.5 sm:space-y-4">
                {!! $illustration !!}
                
                <div class="space-y-1.5">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $badgeClass }}">
                        {{ $portalLabel }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-950 dark:text-white tracking-tight font-display">
                        {{ $portalTitle }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium max-w-sm mx-auto leading-relaxed">
                        {{ $portalSubtitle }}
                    </p>
                </div>
            </div>

            <!-- Validation Errors and Alerts -->
            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 text-xs font-semibold space-y-1 animate-pulse">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900/50 text-rose-700 dark:text-rose-400 text-xs font-semibold flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Activation Form -->
            <form class="space-y-4 sm:space-y-5" action="{{ route('student.activate') }}" method="POST">
                @csrf
                <input type="hidden" name="nomor_pendaftaran" value="{{ $registration->nomor_pendaftaran }}">
                <input type="hidden" name="email" value="{{ $registration->email }}">

                <div class="space-y-4">
                    <!-- Read-only Confirmation Details -->
                    <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-200/50 dark:border-slate-800/60 rounded-2xl p-4 space-y-2.5">
                        <div class="flex justify-between items-center text-xs font-semibold">
                            <span class="text-slate-400">Nomor Registrasi:</span>
                            <span class="text-slate-900 dark:text-white font-mono">{{ $registration->nomor_pendaftaran }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-semibold">
                            <span class="text-slate-400">Nama Calon Siswa:</span>
                            <span class="text-slate-900 dark:text-white font-bold">{{ $registration->nama_lengkap }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs font-semibold">
                            <span class="text-slate-400">Alamat Email:</span>
                            <span class="text-slate-900 dark:text-white">{{ $registration->email }}</span>
                        </div>
                    </div>

                    <!-- New Password Field -->
                    <div class="space-y-1.5 relative">
                        <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider pl-1">
                            Password Baru
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </div>
                            <input id="password" name="password" type="password" required 
                                   class="block w-full pl-11 pr-4 py-3 sm:py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800/80 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 font-medium text-sm focus:outline-none focus:ring-2 {{ $focusRing }} dark:focus:ring-offset-slate-900 transition-all" 
                                   placeholder="Min. 8 karakter">
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="space-y-1.5 relative">
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider pl-1">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                <i class="fa-solid fa-shield-halved text-sm"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                   class="block w-full pl-11 pr-4 py-3 sm:py-3.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800/80 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 font-medium text-sm focus:outline-none focus:ring-2 {{ $focusRing }} dark:focus:ring-offset-slate-900 transition-all" 
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center items-center gap-2 px-6 py-3.5 sm:py-4 border border-transparent text-sm font-bold uppercase tracking-wider rounded-2xl text-white shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.98] transition-all {{ $buttonClass }}">
                        <i class="fa-solid fa-circle-check text-base"></i>
                        <span>Aktifkan Akun & Masuk</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
