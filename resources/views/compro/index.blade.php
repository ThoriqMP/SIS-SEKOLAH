<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth scroll-pt-28 sm:scroll-pt-32">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-adsense-account" content="ca-pub-1189692810742391">
    
    <!-- Standard Favicons & Touch Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link class="brand-logo-fav" rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <title>Says Homeschooling - Mandiri, Kreatif, & Berkarakter Berbasis Pemetaan Potensi & Fitrah</title>
    <meta name="description" content="Says Homeschooling - Institusi pendidikan fleksibel berbasis karakter, kurikulum personal, dan pemetaan potensi & fitrah anak dengan dukungan LMS & kelas online interaktif.">

    <!-- Open Graph / Google Search Rich Metadata -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Says Homeschooling - Sayyidah School">
    <meta property="og:title" content="Says Homeschooling - Mandiri, Kreatif, & Berkarakter Berbasis Pemetaan Potensi & Fitrah">
    <meta property="og:description" content="Says Homeschooling - Institusi pendidikan fleksibel berbasis karakter, kurikulum personal, dan pemetaan potensi & fitrah anak dengan dukungan LMS & kelas online interaktif.">
    <meta property="og:image" content="{{ asset('logos/sayyidah-school-logo.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('logos/sayyidah-school-logo.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="640">
    <meta property="og:image:height" content="640">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Says Homeschooling - Mandiri, Kreatif, & Berkarakter">
    <meta name="twitter:description" content="Institusi pendidikan fleksibel berbasis karakter, kurikulum personal, dan pemetaan potensi & fitrah anak.">
    <meta name="twitter:image" content="{{ asset('logos/sayyidah-school-logo.png') }}">

    <!-- Official Google Fonts CDN & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400;1,9..40,500&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (via Vite) & Alpine.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-violet: #6d28d9;
            --primary-indigo: #4f46e5;
            --accent-amber: #f59e0b;
            --accent-emerald: #10b981;
            --bg-canvas: #fafafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background-color: var(--bg-canvas) !important;
            color: #334155;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            transition: opacity 0.2s ease-in-out;
        }

        body.page-fade-out {
            opacity: 0 !important;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
            letter-spacing: -0.02em;
        }

        .hero-headline {
            line-height: 1.14 !important;
        }

        /* ══════════════════════════════════════════════════════════════ */
        /* PREMIUM BENTO & GLASS CARDS                                    */
        /* ══════════════════════════════════════════════════════════════ */
        .glass-card-pro {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.85);
            border-radius: 28px;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03), 0 4px 12px -2px rgba(15, 23, 42, 0.02);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .glass-card-pro:hover {
            border-color: rgba(192, 132, 252, 0.5);
            box-shadow: 0 20px 35px -10px rgba(109, 40, 217, 0.08), 0 6px 12px -4px rgba(15, 23, 42, 0.04);
            transform: translateY(-3px);
        }

        .icon-squircle {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #6366f1;
            transition: all 0.25s ease;
        }
        .group:hover .icon-squircle {
            transform: translateY(-2px) scale(1.04);
        }

        /* Gradient Text Accents */
        .text-gradient-brand {
            background: linear-gradient(135deg, #6d28d9 0%, #9333ea 50%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-amber {
            background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Hero Clearance for Floating Navbar */
        .hero-section-wrapper {
            margin-top: 60px !important;
            padding-top: 10px !important;
        }
        @media (min-width: 640px) {
            .hero-section-wrapper {
                margin-top: 104px !important;
                padding-top: 36px !important;
            }
        }
        @media (min-width: 1024px) {
            .hero-section-wrapper {
                margin-top: 116px !important;
                padding-top: 44px !important;
            }
        }

        /* Scroll Reveal (Subtle & Smooth) */
        .reveal-init {
            opacity: 0;
            transform: translateY(22px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-init.revealed {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-scale-init {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.6s ease, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-scale-init.revealed {
            opacity: 1;
            transform: scale(1);
        }

        /* Modern Tactile Buttons */
        .btn-primary-action {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            border-radius: 16px;
            font-weight: 700;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.15);
        }
        .btn-primary-action:hover {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.22);
        }

        .btn-secondary-action {
            background: #ffffff;
            color: #334155;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            font-weight: 700;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        }
        .btn-secondary-action:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
        }

        /* Sticky WhatsApp Floating Hub */
        .floating-wa-hub {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 60;
        }

        /* Floating Trust Card Glow */
        .floating-badge-glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 14px 30px -6px rgba(109, 40, 217, 0.12), 0 4px 12px -2px rgba(15, 23, 42, 0.06);
            border-radius: 18px;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="relative">

    <!-- Modern Dynamic Background (Fine Dot Grid + Multi-Layer Radiant Glow - Light Theme) -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10 bg-[#FAF9FD]">
        <!-- Tech Dot Grid Matrix -->
        <div class="absolute inset-0 bg-[radial-gradient(#cbd5e1_1.25px,transparent_1.25px)] [background-size:24px_24px] opacity-60"></div>
        
        <!-- Multi-Layer Warm & Radiant Glows -->
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[1200px] h-[650px] bg-gradient-to-b from-purple-200/45 via-indigo-100/35 to-transparent blur-3xl pointer-events-none"></div>
        <div class="absolute -top-32 -right-32 w-[650px] h-[650px] bg-amber-100/50 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-10 -left-32 w-[600px] h-[600px] bg-purple-200/35 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-[38%] -left-40 w-[600px] h-[600px] bg-indigo-100/40 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-[62%] -right-40 w-[650px] h-[650px] bg-purple-100/40 rounded-full blur-3xl pointer-events-none"></div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- FLOATING CAPSULE HEADER & NAVIGATION                           -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <header class="fixed top-2.5 sm:top-5 left-0 right-0 z-50 px-2.5 sm:px-6 pointer-events-none" 
            x-data="{ mobileMenuOpen: false, scrolled: false }"
            @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="w-full pointer-events-auto max-w-7xl mx-auto rounded-2xl sm:rounded-full bg-white/95 backdrop-blur-xl border border-white/90 shadow-xl shadow-purple-900/5 ring-1 ring-purple-100/60 px-3.5 sm:px-7 h-14 sm:h-20 flex items-center justify-between transition-all duration-300 relative"
             :class="{ 'shadow-2xl shadow-purple-950/10 bg-white/98 border-purple-200/80 !h-12 sm:!h-16': scrolled }">
            
            <!-- Logo Brand -->
            <a href="#" class="flex items-center gap-2 sm:gap-3 group shrink-0">
                <img src="{{ ($schoolProfile && $schoolProfile->logo_path) ? Storage::url($schoolProfile->logo_path) : asset('storage/logos/sayyidah-school-logo.png') }}" 
                     alt="Sayyidah School" 
                     class="h-8 sm:h-11 md:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                <div class="flex flex-col leading-none">
                    <span class="text-xs sm:text-base md:text-lg font-heading font-black tracking-tight text-slate-900 group-hover:text-purple-700 transition-colors">
                        Sayyidah School
                    </span>
                    <span class="text-[8px] sm:text-[10px] md:text-[11px] font-extrabold text-purple-600 uppercase tracking-widest mt-0.5 sm:mt-1">
                        Homeschooling
                    </span>
                </div>
            </a>

            <!-- Navigation Links Pill (Desktop) -->
            <nav class="hidden md:flex items-center gap-1 px-3 py-1.5 bg-slate-100/80 rounded-full border border-slate-200/60 text-xs font-bold text-slate-600 shadow-inner">
                <a href="#tentang" class="px-3.5 py-1.5 rounded-full hover:bg-white hover:text-purple-700 hover:shadow-xs transition-all">{{ __('messages.about_us') }}</a>
                <a href="#program" class="px-3.5 py-1.5 rounded-full hover:bg-white hover:text-purple-700 hover:shadow-xs transition-all">{{ __('messages.programs') }}</a>
                <a href="#lms-features" class="px-3.5 py-1.5 rounded-full hover:bg-white hover:text-purple-700 hover:shadow-xs transition-all">LMS &amp; WebRTC</a>
                <a href="#biaya" class="px-3.5 py-1.5 rounded-full hover:bg-white hover:text-purple-700 hover:shadow-xs transition-all">{{ __('messages.fees') }}</a>
                <a href="#metode" class="px-3.5 py-1.5 rounded-full hover:bg-white hover:text-purple-700 hover:shadow-xs transition-all">Metode Belajar</a>
                <a href="#pendaftaran" class="px-3.5 py-1.5 rounded-full hover:bg-white hover:text-purple-700 hover:shadow-xs transition-all">{{ __('messages.registration') }}</a>
            </nav>

            <!-- Action Gateways & Language Switcher (Desktop) -->
            <div class="hidden md:flex items-center gap-3">
                <!-- Language Switcher -->
                <div class="flex items-center gap-1 bg-slate-100/80 p-1 rounded-full text-xs font-bold border border-slate-200/80">
                    <a href="/lang/id" class="lang-switch-btn px-2.5 py-1 rounded-full transition-all {{ app()->getLocale() === 'id' ? 'bg-purple-600 text-white font-black shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">ID</a>
                    <a href="/lang/en" class="lang-switch-btn px-2.5 py-1 rounded-full transition-all {{ app()->getLocale() === 'en' ? 'bg-purple-600 text-white font-black shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">EN</a>
                </div>

                <!-- Portal Access Buttons -->
                <a href="{{ route('student.index') }}" 
                   class="px-4 py-2 rounded-full bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200/80 text-xs font-bold transition-all flex items-center gap-1.5 hover:scale-105">
                    <i class="fa-solid fa-graduation-cap text-purple-600"></i>
                    <span>{{ $settings['header_cta_student_text'] ?? __('messages.student_portal') }}</span>
                </a>
                
                <a href="{{ route('teacher.index') }}" 
                   class="px-4 py-2 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-all shadow-md shadow-indigo-600/20 flex items-center gap-1.5 hover:scale-105">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>{{ $settings['header_cta_teacher_text'] ?? __('messages.teacher_portal') }}</span>
                </a>
            </div>

            <!-- Mobile Controls (Language Toggle + Menu Button) -->
            <div class="flex items-center gap-2 md:hidden">
                <!-- Mini Language Toggle on Mobile -->
                <div class="flex items-center bg-slate-100 p-0.5 rounded-full text-[10px] font-bold border border-slate-200">
                    <a href="/lang/id" class="lang-switch-btn px-2 py-0.5 rounded-full {{ app()->getLocale() === 'id' ? 'bg-purple-600 text-white font-black shadow-xs' : 'text-slate-500' }}">ID</a>
                    <a href="/lang/en" class="lang-switch-btn px-2 py-0.5 rounded-full {{ app()->getLocale() === 'en' ? 'bg-purple-600 text-white font-black shadow-xs' : 'text-slate-500' }}">EN</a>
                </div>

                <!-- Hamburger Button with Clear Touch Target -->
                <button @click.stop="mobileMenuOpen = !mobileMenuOpen" 
                        class="w-10 h-10 min-h-[44px] min-w-[44px] rounded-xl text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 flex items-center justify-center text-sm transition active:scale-95 shadow-xs cursor-pointer"
                        aria-label="Menu Utama">
                    <i class="fa-solid fa-bars text-sm" x-show="!mobileMenuOpen"></i>
                    <i class="fa-solid fa-xmark text-sm text-purple-700" x-show="mobileMenuOpen" x-cloak></i>
                </button>
            </div>

            <!-- Mobile Floating Drawer -->
            <div x-show="mobileMenuOpen" 
                 x-cloak
                 @click.outside="mobileMenuOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="absolute top-full left-0 right-0 mt-2.5 p-4 bg-white/98 rounded-2xl border border-purple-100 shadow-2xl backdrop-blur-xl md:hidden">
                <nav class="flex flex-col gap-1.5 font-bold text-slate-700 text-xs">
                    <a href="#tentang" @click="mobileMenuOpen = false" class="py-2.5 px-3 hover:bg-purple-50 rounded-xl flex items-center justify-between">
                        <span>{{ __('messages.about_us') }}</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="#program" @click="mobileMenuOpen = false" class="py-2.5 px-3 hover:bg-purple-50 rounded-xl flex items-center justify-between">
                        <span>{{ __('messages.programs') }}</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="#lms-features" @click="mobileMenuOpen = false" class="py-2.5 px-3 hover:bg-purple-50 rounded-xl flex items-center justify-between">
                        <span>LMS &amp; WebRTC</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="#biaya" @click="mobileMenuOpen = false" class="py-2.5 px-3 hover:bg-purple-50 rounded-xl flex items-center justify-between">
                        <span>{{ __('messages.fees') }}</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="#metode" @click="mobileMenuOpen = false" class="py-2.5 px-3 hover:bg-purple-50 rounded-xl flex items-center justify-between">
                        <span>Metode Belajar</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    <a href="#pendaftaran" @click="mobileMenuOpen = false" class="py-2.5 px-3 hover:bg-purple-50 rounded-xl flex items-center justify-between">
                        <span>{{ __('messages.registration') }}</span>
                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    
                    <div class="grid grid-cols-2 gap-2 pt-3 mt-1 border-t border-slate-100">
                        <a href="{{ route('student.index') }}" class="text-center py-2.5 rounded-xl bg-purple-50 text-purple-700 text-xs font-bold border border-purple-200/80">
                            {{ $settings['header_cta_student_text'] ?? __('messages.student_portal') }}
                        </a>
                        <a href="{{ route('teacher.index') }}" class="text-center py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-600/20">
                            {{ $settings['header_cta_teacher_text'] ?? __('messages.teacher_portal') }}
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </header>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- SECTION 1: HERO (HIGH-IMPACT VALUE PROPOSITION & LIVE MOCKUP)  -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 hero-section-wrapper pb-12 sm:pb-24 overflow-x-clip">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-14 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-6 space-y-4 sm:space-y-5 text-center lg:text-left reveal-init">
                
                <!-- Badge Pill -->
                <div class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 text-[10px] sm:text-xs font-black tracking-wide text-purple-800 bg-white/90 rounded-full border border-purple-200/80 shadow-sm shadow-purple-500/5 backdrop-blur-md max-w-full">
                    <span class="relative flex h-2 w-2 sm:h-2.5 sm:w-2.5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 sm:h-2.5 sm:w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="uppercase tracking-wider font-extrabold truncate">{{ $settings['hero_badge'] ?? 'PPDB TAHUN AJARAN BARU TELAH DIBUKA' }}</span>
                    <i class="fa-solid fa-arrow-right text-[9px] text-purple-400 shrink-0"></i>
                </div>

                <!-- Main Dynamic Headline -->
                <h1 class="hero-headline text-3xl sm:text-4xl lg:text-[3.25rem] xl:text-[3.5rem] font-heading font-extrabold text-slate-900 tracking-tight" style="line-height: 1.14 !important;">
                    Pendidikan Fleksibel,<br class="hidden sm:inline">
                    Tumbuh Bersama<br class="hidden sm:inline">
                    <span class="text-gradient-brand">Karakter &amp; Potensi Anak.</span>
                </h1>

                <!-- Subtitle Description -->
                <p class="text-xs sm:text-base lg:text-lg text-slate-600 max-w-xl mx-auto lg:mx-0 font-normal pt-0.5" style="line-height: 1.65;">
                    {{ $settings['hero_subtitle'] ?? 'Kurikulum personal berbasis pemetaan potensi dan fitrah yang didukung LMS modern, kelas video WebRTC 1-on-1, serta pendampingan psikologi berlisensi.' }}
                </p>

                <!-- Action CTAs -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-2.5 sm:gap-3.5 pt-1">
                    <a href="{{ route('registration.create') }}" 
                       class="btn-primary-action w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 text-xs sm:text-sm tracking-wide flex items-center justify-center gap-2.5 group">
                        <i class="fa-solid fa-pen-nib text-xs text-purple-300 group-hover:rotate-12 transition-transform"></i>
                        <span>Daftar PPDB Online</span>
                        <i class="fa-solid fa-arrow-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    
                    <a href="#biaya" 
                       class="btn-secondary-action w-full sm:w-auto px-6 sm:px-7 py-3 sm:py-4 text-xs sm:text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-calculator text-amber-500 text-xs"></i>
                        <span>Hitung Estimasi Biaya</span>
                    </a>
                </div>

                <!-- Trust Stats Checklist -->
                <div class="pt-4 sm:pt-6 grid grid-cols-2 sm:flex sm:flex-wrap items-center justify-center lg:justify-start gap-2 sm:gap-6 text-[11px] sm:text-xs text-slate-600 font-semibold border-t border-slate-200/70">
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-[9px] sm:text-[10px] shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="truncate">Legalitas PKBM Resmi</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-purple-50 text-purple-600 border border-purple-200 flex items-center justify-center text-[9px] sm:text-[10px] shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="truncate">Project Based Learning</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-200 flex items-center justify-center text-[9px] sm:text-[10px] shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="truncate">Ijazah Terakreditasi</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-[9px] sm:text-[10px] shrink-0">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="truncate">Akses Belajar Global</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Card Mockup (Authentic Student Dashboard Canvas Showcase with Floating Cards & Vibrant Ambient Backdrop) -->
            <div class="lg:col-span-6 flex flex-col items-center lg:items-end justify-center reveal-scale-init relative w-full max-w-full"
                 x-data="{
                      cursorX: 4.5,
                      cursorY: 19,
                      clicking: false,
                      activeHighlight: 0,
                      actionIndex: 0,
                      tourTimer: null,

                      tourActions: [
                          /* 0: BERANDA / DASHBOARD */
                          { 
                              page: 0, 
                              sidebarY: 19, 
                              targetX: 50, 
                              targetY: 48, 
                              url: 'portal.sayyidahschool.id/dashboard', 
                              label: '👋 Beranda Siswa: Peta Potensi, Karakter & Progres' 
                          },

                          /* 1: RUANG BELAJAR */
                          { 
                              page: 1, 
                              sidebarY: 31, 
                              targetX: 52, 
                              targetY: 38, 
                              url: 'portal.sayyidahschool.id/ruang-belajar', 
                              label: '📚 Ruang Belajar: Modul IPA, Matematika & Inggris' 
                          },

                          /* 2: MUTABA'AH TAHFIDZ */
                          { 
                              page: 2, 
                              sidebarY: 43, 
                              targetX: 50, 
                              targetY: 52, 
                              url: 'portal.sayyidahschool.id/mutabaah-tahfidz', 
                              label: '📊 Mutaba\'ah Tahfidz: Setoran Quran & Uji Tasmi\'' 
                          },

                          /* 3: PERKEMBANGAN AKADEMIK */
                          { 
                              page: 3, 
                              sidebarY: 55, 
                              targetX: 52, 
                              targetY: 42, 
                              url: 'portal.sayyidahschool.id/perkembangan-akademik', 
                              label: '📑 Perkembangan Akademik: Rapor & Nilai Portofolio' 
                          },

                          /* 4: KELAS VIRTUAL WEBRTC */
                          { 
                              page: 4, 
                              sidebarY: 67, 
                              targetX: 50, 
                              targetY: 50, 
                              url: 'portal.sayyidahschool.id/kelas-virtual-webrtc', 
                              label: '🎥 Kelas Online: Sesi Live WebRTC HD Bersama Guru' 
                          }
                      ],

                      init() {
                          this.startTourLoop();
                      },

                      startTourLoop() {
                          if (this.tourTimer) clearInterval(this.tourTimer);
                          this.tourTimer = setInterval(() => {
                              this.nextAction();
                          }, 3400);
                      },

                      nextAction() {
                          this.actionIndex = (this.actionIndex + 1) % this.tourActions.length;
                          this.executeAction(this.actionIndex);
                      },

                      goToStep(pageIndex) {
                          this.actionIndex = pageIndex;
                          this.activeHighlight = pageIndex;
                          const action = this.tourActions[pageIndex];
                          this.cursorX = action.targetX;
                          this.cursorY = action.targetY;
                          this.startTourLoop();
                      },

                      executeAction(idx) {
                          const action = this.tourActions[idx];

                          // Phase 1: Move cursor to sidebar menu icon
                          this.cursorX = 4.5;
                          this.cursorY = action.sidebarY;

                          // Phase 2: Click sidebar icon -> Canvas page switches immediately
                          setTimeout(() => {
                              this.clicking = true;
                              this.activeHighlight = action.page;

                              // Phase 3: Glide smoothly into the active page content
                              setTimeout(() => {
                                  this.clicking = false;
                                  this.cursorX = action.targetX;
                                  this.cursorY = action.targetY;
                              }, 300);
                          }, 400);
                      }
                  }">

                <!-- Mobile Top Status Bar (Only on mobile screens) -->
                <div class="flex sm:hidden items-center justify-between gap-2 mb-2.5 w-full max-w-xl text-[10px] font-bold text-slate-700 px-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-slate-200 shadow-xs">
                        <i class="fa-solid fa-diagram-project text-purple-600 text-[10px]"></i>
                        <span>Project Based Learning</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-slate-200 shadow-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Live WebRTC Class</span>
                    </span>
                </div>

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- RADIANT AMBIENT BACKDROP (FILLS EMPTY BACKGROUND)      -->
                <!-- ══════════════════════════════════════════════════════ -->
                <div class="absolute -top-16 -left-12 -right-12 -bottom-16 bg-gradient-to-tr from-purple-200/55 via-indigo-100/40 to-amber-100/50 rounded-[64px] blur-3xl -z-10 pointer-events-none"></div>
                <div class="absolute -top-24 -right-12 w-80 h-80 bg-purple-300/35 rounded-full blur-3xl -z-10 pointer-events-none animate-pulse" style="animation-duration: 7s"></div>
                <div class="absolute -bottom-20 -left-12 w-80 h-80 bg-indigo-300/30 rounded-full blur-3xl -z-10 pointer-events-none"></div>
                <div class="absolute top-1/3 -right-20 w-64 h-64 bg-amber-200/40 rounded-full blur-2xl -z-10 pointer-events-none"></div>

                <!-- Subtle Decorative Orbit Line Rings -->
                <div class="absolute -inset-8 border border-purple-200/50 rounded-[44px] -z-10 pointer-events-none hidden sm:block"></div>
                <div class="absolute -inset-16 border border-dashed border-indigo-200/40 rounded-[60px] -z-10 pointer-events-none hidden sm:block"></div>

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- STRATEGIC FLOATING EDUTECH BADGES (DESKTOP)            -->
                <!-- ══════════════════════════════════════════════════════ -->
                <!-- Badge 1: Top-Left (Project Based Learning System) -->
                <div class="hidden sm:flex floating-badge-glass px-3.5 py-2 absolute -top-7 -left-7 z-30 items-center gap-2.5 shadow-xl border border-white/90 bg-white/95 backdrop-blur-md">
                    <div class="w-7 h-7 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-black shadow-xs">
                        <i class="fa-solid fa-diagram-project"></i>
                    </div>
                    <div class="leading-tight">
                        <div class="text-[10px] font-black text-slate-900">Project Based Learning System</div>
                        <div class="text-[9px] text-purple-600 font-bold">Portofolio &amp; Riset Mandiri</div>
                    </div>
                </div>

                <!-- Badge 2: Top-Right (WebRTC HD Indicator) -->
                <div class="hidden sm:flex floating-badge-glass px-3.5 py-2 absolute -top-4 -right-3 z-30 items-center gap-2.5 shadow-xl border border-white/90 bg-white/95 backdrop-blur-md">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-800">Live WebRTC Class</span>
                    <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-purple-100 text-purple-700">HD 1-on-1</span>
                </div>

                <!-- Badge 3: Bottom-Left (Parent Satisfaction) -->
                <div class="hidden sm:flex floating-badge-glass px-4 py-2.5 absolute -bottom-5 -left-4 z-30 items-center gap-3 shadow-xl border border-white/90 bg-white/95 backdrop-blur-md">
                    <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center font-bold text-xs shadow-xs">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="leading-tight">
                        <div class="flex items-center gap-1">
                            <span class="text-xs font-black text-slate-900">4.9 / 5.0</span>
                            <span class="text-[10px] text-amber-500">★★★★★</span>
                        </div>
                        <span class="text-[10px] text-slate-500 font-medium">Kepuasan 500+ Keluarga</span>
                    </div>
                </div>

                <!-- ══════════════════════════════════════════════════════ -->
                <!-- MAIN WHITE MACOS LAPTOP WINDOW FRAME                   -->
                <!-- ══════════════════════════════════════════════════════ -->
                <div class="w-full max-w-xl bg-white p-2 sm:p-3 rounded-2xl sm:rounded-[32px] shadow-2xl shadow-purple-900/10 border-2 border-slate-200/80 ring-1 ring-purple-100/60 relative overflow-hidden select-none">

                    <div class="w-full bg-white rounded-xl sm:rounded-[28px] p-1.5 sm:p-3 border border-slate-200/70 shadow-sm relative overflow-hidden">
                        
                        <!-- Window Titlebar -->
                        <div class="flex items-center justify-between px-1.5 sm:px-2 pb-1.5 sm:pb-2.5 pt-0.5 border-b border-slate-100 text-slate-500 text-xs">
                            <!-- Mac Traffic Light Buttons -->
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 sm:w-2.5 h-2 sm:h-2.5 rounded-full bg-rose-400 inline-block shadow-xs"></span>
                                <span class="w-2 sm:w-2.5 h-2 sm:h-2.5 rounded-full bg-amber-400 inline-block shadow-xs"></span>
                                <span class="w-2 sm:w-2.5 h-2 sm:h-2.5 rounded-full bg-emerald-400 inline-block shadow-xs"></span>
                            </div>

                            <!-- Mini URL Bar -->
                            <div class="hidden sm:flex items-center gap-1.5 bg-slate-50 px-3 py-0.5 rounded-lg border border-slate-200 text-[10px] text-slate-600 font-mono shadow-xs">
                                <i class="fa-solid fa-lock text-[8px] text-emerald-500"></i>
                                <span x-text="tourActions[actionIndex] ? tourActions[actionIndex].url : 'portal.sayyidahschool.id/dashboard'"></span>
                            </div>

                            <!-- Live Session Dot -->
                            <div class="flex items-center gap-1 text-[9px] font-bold text-emerald-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                <span class="hidden sm:inline">Online</span>
                            </div>
                        </div>

                        <!-- ══════════════════════════════════════════════════ -->
                        <!-- MAIN SCREEN CONTENT CANVAS (PURE WHITE STUDIO LMS) -->
                        <!-- ══════════════════════════════════════════════════ -->
                        <div class="bg-white rounded-lg sm:rounded-[20px] mt-1.5 sm:mt-2.5 border border-slate-200/90 shadow-sm flex overflow-hidden relative text-slate-800 h-[315px] sm:h-[355px]">
                            
                            <!-- Virtual Animated Cursor & Tour Tooltip -->
                            <div class="absolute z-50 pointer-events-none transition-all duration-700 ease-out"
                                 :style="`left: ${cursorX}%; top: ${cursorY}%; transform: translate(-2px, -2px);`">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-900 drop-shadow-md transform transition-transform duration-150"
                                     :class="clicking ? 'scale-75 -rotate-6' : 'scale-100 -rotate-12'"
                                     viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 2l16 11-7.5 1.5 4.5 7.5-3.5 2-4.5-7.5-5 5V2z" stroke="white" stroke-width="1.5" stroke-linejoin="round" />
                                </svg>
                                <div class="absolute -top-1.5 -left-1.5 w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-purple-500/40 border border-purple-600 transition-all duration-300"
                                     :class="clicking ? 'scale-150 opacity-100 animate-ping' : 'scale-0 opacity-0'"></div>
                                <div class="absolute left-3.5 sm:left-4 -top-3 bg-slate-950/95 text-white text-[7.5px] sm:text-[8px] font-bold px-2.5 py-0.5 rounded-full shadow-lg border border-purple-400/40 whitespace-nowrap flex items-center gap-1 transition-all duration-300">
                                    <span x-text="tourActions[actionIndex].label"></span>
                                </div>
                            </div>

                            <!-- Mini LMS Navigation Sidebar (Auto-Animated with Tour + Interactive Click) -->
                            <!-- stepIndex: 0=home, 1=pbl, 2=stats, 3=raport, 4=webrtc -->
                            <div class="w-8 sm:w-11 bg-white border-r border-slate-200/80 py-2 sm:py-3 flex flex-col items-center justify-between shrink-0 select-none z-20">
                                <div class="flex flex-col items-center gap-1.5 sm:gap-2 w-full px-0.5 sm:px-1">
                                    <!-- User Mini Avatar -->
                                    <div class="relative mb-0.5 sm:mb-1 cursor-pointer" @click="goToStep(0)" title="Profil Siswa">
                                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg sm:rounded-xl bg-gradient-to-tr from-purple-600 to-indigo-600 text-white flex items-center justify-center text-[8px] sm:text-[10px] font-bold shadow-sm transition-all duration-300"
                                             :class="activeHighlight === 0 ? 'ring-2 ring-purple-400 scale-110' : 'ring-1 ring-purple-100'">
                                            SA
                                        </div>
                                        <span class="absolute -bottom-0.5 -right-0.5 w-1.5 h-1.5 sm:w-2 sm:h-2 bg-emerald-500 rounded-full border border-white"></span>
                                    </div>

                                    <!-- Divider -->
                                    <div class="w-4 sm:w-6 h-px bg-slate-200/80 rounded-full"></div>

                                    <!-- 0: Home / Dashboard -->
                                    <button type="button" @click="goToStep(0)"
                                            class="relative w-6 h-6 sm:w-7 sm:h-7 rounded-lg flex items-center justify-center text-[9px] sm:text-[11px] transition-all duration-300"
                                            :class="activeHighlight === 0 ? 'bg-purple-600 text-white scale-110 shadow-md shadow-purple-600/30' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'">
                                        <i class="fa-solid fa-house"></i>
                                        <span class="absolute -left-0.5 top-1/2 -translate-y-1/2 w-0.5 sm:w-1 h-3 sm:h-4 rounded-r-full transition-all duration-300"
                                              :class="activeHighlight === 0 ? 'bg-purple-600 opacity-100' : 'opacity-0'"></span>
                                    </button>

                                    <!-- 1: Ruang Belajar & PBL -->
                                    <button type="button" @click="goToStep(1)"
                                            class="relative w-6 h-6 sm:w-7 sm:h-7 rounded-lg flex items-center justify-center text-[9px] sm:text-[11px] transition-all duration-300"
                                            :class="activeHighlight === 1 ? 'bg-sky-500 text-white scale-110 shadow-md shadow-sky-500/30' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'">
                                        <i class="fa-solid fa-book-open"></i>
                                        <span class="absolute -left-0.5 top-1/2 -translate-y-1/2 w-0.5 sm:w-1 h-3 sm:h-4 rounded-r-full transition-all duration-300"
                                              :class="activeHighlight === 1 ? 'bg-sky-500 opacity-100' : 'opacity-0'"></span>
                                    </button>

                                    <!-- 2: Mutaba'ah Tahfidz & Statistik -->
                                    <button type="button" @click="goToStep(2)"
                                            class="relative w-6 h-6 sm:w-7 sm:h-7 rounded-lg flex items-center justify-center text-[9px] sm:text-[11px] transition-all duration-300"
                                            :class="activeHighlight === 2 ? 'bg-emerald-500 text-white scale-110 shadow-md shadow-emerald-500/30' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'">
                                        <i class="fa-solid fa-chart-pie"></i>
                                        <span class="absolute -left-0.5 top-1/2 -translate-y-1/2 w-0.5 sm:w-1 h-3 sm:h-4 rounded-r-full transition-all duration-300"
                                              :class="activeHighlight === 2 ? 'bg-emerald-500 opacity-100' : 'opacity-0'"></span>
                                    </button>

                                    <!-- 3: Perkembangan Akademik Siswa -->
                                    <button type="button" @click="goToStep(3)"
                                            class="relative w-6 h-6 sm:w-7 sm:h-7 rounded-lg flex items-center justify-center text-[9px] sm:text-[11px] transition-all duration-300"
                                            :class="activeHighlight === 3 ? 'bg-emerald-500 text-white scale-110 shadow-md shadow-emerald-500/30' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'">
                                        <i class="fa-solid fa-chart-line"></i>
                                        <span class="absolute -left-0.5 top-1/2 -translate-y-1/2 w-0.5 sm:w-1 h-3 sm:h-4 rounded-r-full transition-all duration-300"
                                              :class="activeHighlight === 3 ? 'bg-emerald-500 opacity-100' : 'opacity-0'"></span>
                                    </button>

                                    <!-- 4: Kelas Virtual WebRTC -->
                                    <button type="button" @click="goToStep(4)"
                                            class="relative w-6 h-6 sm:w-7 sm:h-7 rounded-lg flex items-center justify-center text-[9px] sm:text-[11px] transition-all duration-300"
                                            :class="activeHighlight === 4 ? 'bg-rose-500 text-white scale-110 shadow-md shadow-rose-500/30' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'">
                                        <i class="fa-solid fa-video"></i>
                                        <span class="absolute top-0.5 right-0.5 w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full border border-white"
                                              :class="activeHighlight === 4 ? 'bg-rose-400' : 'bg-rose-500 animate-ping'"></span>
                                        <span class="absolute -left-0.5 top-1/2 -translate-y-1/2 w-0.5 sm:w-1 h-3 sm:h-4 rounded-r-full transition-all duration-300"
                                              :class="activeHighlight === 4 ? 'bg-rose-500 opacity-100' : 'opacity-0'"></span>
                                    </button>
                                </div>
                                <!-- Bottom: Settings Cog -->
                                <div class="text-slate-400 text-[9px] sm:text-[10px] pb-1">
                                    <i class="fa-solid fa-gear"></i>
                                </div>
                            </div>

                            <!-- Right Main Workspace Content Area (White Background, Fixed Height) -->
                            <div class="flex-1 p-2 sm:p-2.5 flex flex-col justify-between relative overflow-hidden bg-white h-full">

                                <!-- Top Mini Header Bar -->
                                <div class="flex items-center justify-between pb-1 text-[7.5px] sm:text-[8px] text-slate-400 border-b border-slate-100 shrink-0">
                                    <div class="flex items-center gap-1 bg-slate-50 px-1.5 sm:px-2 py-0.5 rounded-md border border-slate-200 w-28 sm:w-44 text-slate-500 shadow-2xs">
                                        <i class="fa-solid fa-magnifying-glass text-[7px] text-purple-600"></i>
                                        <span class="truncate">Cari materi, modul, tugas...</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 sm:gap-2">
                                        <span class="text-slate-400 text-[7px] sm:text-[7.5px] font-medium hidden sm:inline">Jumat, 18 Sep 2026</span>
                                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-md bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 text-[8px] relative shadow-2xs">
                                            <i class="fa-regular fa-bell"></i>
                                            <span class="absolute top-0.5 right-0.5 w-1 h-1 bg-rose-500 rounded-full"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Smooth Page Container with Absolute Leaving Transition (NO HEIGHT JUMP) -->
                                <div class="relative w-full flex-1 overflow-hidden pt-1">

                                    <!-- ═══════════════════════════════════════════ -->
                                    <!-- PAGE 0: DASHBOARD SISWA (ORIGINAL RICH LMS) -->
                                    <!-- ═══════════════════════════════════════════ -->
                                    <div x-show="activeHighlight === 0"
                                         x-transition:enter="transition ease-out duration-250"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150 absolute inset-0"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         class="w-full h-full flex flex-col justify-between space-y-1 sm:space-y-1.5">
                                        
                                        <!-- 1. Welcome Header -->
                                        <div class="bg-gradient-to-r from-purple-700 via-purple-800 to-indigo-800 rounded-xl sm:rounded-2xl p-2 sm:p-2.5 text-white shadow-md relative overflow-hidden shrink-0">
                                            <div class="relative z-10 flex items-center justify-between gap-1.5 sm:gap-2">
                                                <div>
                                                    <span class="px-1.5 sm:px-2 py-0.5 bg-white/15 border border-white/25 rounded-full text-[6.5px] sm:text-[7px] font-black uppercase tracking-widest text-purple-200">
                                                        Sayyidah School Portal
                                                    </span>
                                                    <h3 class="text-[10.5px] sm:text-sm font-black mt-0.5 tracking-tight text-white leading-tight">
                                                        Selamat Datang, Sayyidah Athazya! 👋
                                                    </h3>
                                                    <p class="text-purple-100 text-[7px] sm:text-[7.5px] opacity-95 font-medium">
                                                        NIS: 20240901 &bull; Kelas: 5 SD Tahfidz &amp; Science
                                                    </p>
                                                </div>
                                                <div class="bg-white text-purple-950 font-black px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-lg text-[7px] sm:text-[7.5px] shadow-sm flex items-center gap-1 shrink-0 border border-white/20">
                                                    <i class="fa-solid fa-compass text-purple-600 animate-spin" style="animation-duration: 8s"></i>
                                                    <span class="hidden sm:inline">Tour</span> Aktif
                                                </div>
                                            </div>
                                            <div class="mt-1 sm:mt-1.5 flex items-center gap-1 sm:gap-1.5">
                                                <span class="bg-white/15 px-1.5 sm:px-2 py-0.5 rounded-md text-[6.5px] sm:text-[7px] font-black tracking-wider uppercase text-purple-200 border border-white/20">
                                                    Semester Genap
                                                </span>
                                                <span class="bg-purple-500/30 px-1.5 sm:px-2 py-0.5 rounded-md text-[6.5px] sm:text-[7px] font-black tracking-wider uppercase text-purple-200 border border-purple-400/30 flex items-center gap-1">
                                                    <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                                                    Status: Aktif
                                                </span>
                                            </div>
                                            <div class="absolute -top-8 -right-8 w-24 h-24 bg-purple-500/20 rounded-full blur-xl"></div>
                                        </div>

                                        <!-- 2. Trait Summary Section (Peta Potensi & Karakter Siswa) -->
                                        <div class="space-y-0.5 sm:space-y-1 shrink-0">
                                            <div class="flex items-center gap-1 sm:gap-1.5">
                                                <div class="w-3.5 h-3.5 sm:w-4 sm:h-4 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-md flex items-center justify-center text-white text-[7px] sm:text-[8px] shadow-xs">
                                                    <i class="fa-solid fa-lightbulb"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-[8px] sm:text-[8.5px] font-black text-slate-900 leading-none">Peta Potensi &amp; Karakter Siswa</h4>
                                                    <p class="text-[5.5px] sm:text-[6px] font-bold text-purple-700 uppercase tracking-widest mt-0.5">Analisis Asesmen &amp; Observasi Berbasis PBL</p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-3 gap-1 sm:gap-1.5">
                                                <!-- Card 1: Dominant Personality -->
                                                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg p-1 sm:p-1.5 text-white shadow-xs">
                                                    <span class="text-[5.5px] sm:text-[6px] font-black uppercase tracking-widest text-amber-100 block mb-0.5 flex items-center gap-0.5">
                                                        <span class="w-1 h-1 bg-amber-200 rounded-full animate-ping"></span>
                                                        Kepribadian Dominan
                                                    </span>
                                                    <div class="flex flex-wrap gap-0.5">
                                                        <span class="px-1 py-0.2 bg-white/95 rounded text-[5.5px] sm:text-[6px] font-black text-amber-950">Kreatif</span>
                                                        <span class="px-1 py-0.2 bg-white/95 rounded text-[5.5px] sm:text-[6px] font-black text-amber-950">Kolaboratif</span>
                                                        <span class="px-1 py-0.2 bg-white/95 rounded text-[5.5px] sm:text-[6px] font-black text-amber-950">Teliti</span>
                                                    </div>
                                                </div>

                                                <!-- Card 2: Multiple Intelligence -->
                                                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg p-1 sm:p-1.5 text-white shadow-xs">
                                                    <span class="text-[5.5px] sm:text-[6px] font-black uppercase tracking-widest text-purple-100 block mb-0.5 flex items-center gap-0.5">
                                                        <span class="w-1 h-1 bg-purple-200 rounded-full animate-ping"></span>
                                                        Kecerdasan Majemuk
                                                    </span>
                                                    <div class="flex flex-wrap gap-0.5">
                                                        <span class="px-1 py-0.2 bg-white/95 rounded text-[5.5px] sm:text-[6px] font-black text-purple-950">Logika</span>
                                                        <span class="px-1 py-0.2 bg-white/95 rounded text-[5.5px] sm:text-[6px] font-black text-purple-950">Visual</span>
                                                        <span class="px-1 py-0.2 bg-white/95 rounded text-[5.5px] sm:text-[6px] font-black text-purple-950">Naturalis</span>
                                                    </div>
                                                </div>

                                                <!-- Card 3: Potential Strength -->
                                                <div class="bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-lg p-1 sm:p-1.5 text-white shadow-xs">
                                                    <span class="text-[5.5px] sm:text-[6px] font-black uppercase tracking-widest text-violet-100 block mb-0.5 flex items-center gap-0.5">
                                                        <span class="w-1 h-1 bg-violet-200 rounded-full animate-ping"></span>
                                                        Potensi Kekuatan
                                                    </span>
                                                    <div class="flex flex-wrap gap-0.5">
                                                        <span class="px-1 py-0.2 bg-white/95 rounded text-[5.5px] sm:text-[6px] font-black text-fuchsia-950">Analitis</span>
                                                        <span class="px-1 py-0.2 bg-white/95 rounded text-[5.5px] sm:text-[6px] font-black text-fuchsia-950">Komunikasi</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 3. Stats Overview Counters (Matching Student Dashboard clay-cards) -->
                                        <div class="grid grid-cols-4 gap-1 sm:gap-1.5 shrink-0">
                                            <div class="bg-white border border-slate-200/80 p-1 sm:p-1.5 rounded-lg flex items-center gap-1 shadow-2xs">
                                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md bg-purple-50 text-purple-600 flex items-center justify-center text-[7px] sm:text-[8px] shrink-0">
                                                    <i class="fa-solid fa-chart-line"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="text-[5px] sm:text-[5.5px] font-bold text-slate-400 block uppercase tracking-wider truncate">Rata-rata</span>
                                                    <span class="text-[8.5px] sm:text-[9.5px] font-black text-slate-900 leading-none">94.8</span>
                                                </div>
                                            </div>
                                            <div class="bg-white border border-slate-200/80 p-1 sm:p-1.5 rounded-lg flex items-center gap-1 shadow-2xs">
                                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md bg-indigo-50 text-indigo-600 flex items-center justify-center text-[7px] sm:text-[8px] shrink-0">
                                                    <i class="fa-solid fa-wallet"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="text-[5px] sm:text-[5.5px] font-bold text-slate-400 block uppercase tracking-wider truncate">Biaya SPP</span>
                                                    <span class="text-[8px] sm:text-[9px] font-black text-emerald-600 leading-none">Lunas</span>
                                                </div>
                                            </div>
                                            <div class="bg-white border border-slate-200/80 p-1 sm:p-1.5 rounded-lg flex items-center gap-1 shadow-2xs">
                                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md bg-violet-50 text-violet-600 flex items-center justify-center text-[7px] sm:text-[8px] shrink-0">
                                                    <i class="fa-solid fa-book-open"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="text-[5px] sm:text-[5.5px] font-bold text-slate-400 block uppercase tracking-wider truncate">Tahfidz</span>
                                                    <span class="text-[8px] sm:text-[9px] font-black text-slate-900 leading-none truncate">18 Surah</span>
                                                </div>
                                            </div>
                                            <div class="bg-white border border-slate-200/80 p-1 sm:p-1.5 rounded-lg flex items-center gap-1 shadow-2xs">
                                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-md bg-amber-50 text-amber-500 flex items-center justify-center text-[7px] sm:text-[8px] shrink-0">
                                                    🌟
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="text-[5px] sm:text-[5.5px] font-bold text-slate-400 block uppercase tracking-wider truncate">Predikat</span>
                                                    <span class="text-[7.5px] sm:text-[8.5px] font-black text-amber-600 leading-none truncate">A+ Mumtaz</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 4. Bottom Split: Perkembangan Akademik & Riwayat Tagihan (Matching Student Dashboard) -->
                                        <div class="grid grid-cols-2 gap-1.5 sm:gap-2 pt-0.5 shrink-0">
                                            <!-- Left: Academic Progress Preview -->
                                            <div class="bg-white border border-slate-200/80 p-1 sm:p-1.5 rounded-lg shadow-xs space-y-0.5">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[7px] sm:text-[7.5px] font-black text-slate-900 truncate">Perkembangan Akademik</span>
                                                    <span class="text-[6px] bg-purple-100 text-purple-700 px-1 py-0.2 rounded font-black">Semester Genap</span>
                                                </div>
                                                <p class="text-[6px] sm:text-[6.5px] text-slate-500 leading-tight truncate">
                                                    Raport Akademik &amp; Portofolio Siswa Terverifikasi
                                                </p>
                                                <div class="flex items-center justify-between pt-0.5 border-t border-slate-100 text-[6px] sm:text-[6.5px]">
                                                    <span class="text-slate-400 truncate">Ust. Fauzi, S.Pd</span>
                                                    <span class="text-purple-600 font-bold shrink-0 flex items-center gap-0.5">Detail &amp; PDF &rarr;</span>
                                                </div>
                                            </div>

                                            <!-- Right: Riwayat Tagihan / Keuangan -->
                                            <div class="bg-slate-50 border border-slate-200/80 p-1 sm:p-1.5 rounded-lg shadow-xs space-y-0.5">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[7px] sm:text-[7.5px] font-black text-slate-900 truncate">Riwayat Tagihan &amp; SPP</span>
                                                    <span class="text-[6px] bg-emerald-100 text-emerald-700 px-1 py-0.2 rounded font-black flex items-center gap-0.5">
                                                        <i class="fa-solid fa-check text-[5px]"></i> Lunas
                                                    </span>
                                                </div>
                                                <div class="flex items-baseline justify-between pt-0.5">
                                                    <span class="text-[8px] sm:text-[9px] font-black text-slate-900">Rp 1.250.000</span>
                                                    <span class="text-[6px] text-slate-400 font-semibold">Bebas Tunggakan</span>
                                                </div>
                                                <div class="flex items-center justify-between pt-0.5 border-t border-slate-200/60 text-[6px] sm:text-[6.5px]">
                                                    <span class="text-slate-400 truncate">Jatuh Tempo: 10 Okt</span>
                                                    <span class="text-indigo-600 font-bold shrink-0">Rincian &rarr;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ═══════════════════════════════════════════ -->
                                    <!-- PAGE 1: RUANG BELAJAR & PROYEK PBL          -->
                                    <!-- ═══════════════════════════════════════════ -->
                                    <div x-show="activeHighlight === 1"
                                         x-transition:enter="transition ease-out duration-250"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150 absolute inset-0"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         class="w-full h-full flex flex-col justify-between space-y-1 sm:space-y-1.5">
                                        <!-- Header -->
                                        <div class="flex items-center justify-between pb-1 border-b border-slate-100 shrink-0">
                                            <div>
                                                <h4 class="text-[9px] sm:text-[10px] font-bold text-slate-900 flex items-center gap-1.5">
                                                    <span class="w-4 h-4 rounded-md bg-sky-500 text-white flex items-center justify-center text-[8px]">
                                                        <i class="fa-solid fa-book-open"></i>
                                                    </span>
                                                    Ruang Belajar &amp; Proyek PBL
                                                </h4>
                                                <p class="text-[6.5px] sm:text-[7px] text-slate-500 font-medium">Kurikulum Riset Mandiri Berbasis Portofolio</p>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <span class="px-1.5 py-0.5 bg-sky-50 text-sky-700 border border-sky-200 rounded text-[6.5px] font-bold">Semua (3)</span>
                                                <span class="px-1.5 py-0.5 bg-slate-50 text-slate-600 rounded text-[6.5px] font-medium">Aktif</span>
                                            </div>
                                        </div>

                                        <!-- 3 Subject Cards: IPA, Matematika, Bahasa Inggris -->
                                        <div class="space-y-1 flex-1 flex flex-col justify-around">
                                            <!-- Subject 1: IPA -->
                                            <div class="bg-gradient-to-br from-white to-slate-50 border border-sky-200/80 rounded-lg p-1.5 sm:p-2 shadow-2xs">
                                                <div class="flex items-center justify-between mb-0.5">
                                                    <div class="flex items-center gap-1">
                                                        <span class="px-1.5 py-0.2 bg-sky-100 text-sky-800 rounded text-[6px] font-bold">IPA (Sains)</span>
                                                        <span class="text-[6.5px] text-slate-400">Modul 4/4</span>
                                                    </div>
                                                    <span class="text-[6.5px] font-bold text-emerald-600 flex items-center gap-0.5">
                                                        <span class="w-1 h-1 bg-emerald-500 rounded-full"></span> 95% Selesai
                                                    </span>
                                                </div>
                                                <h5 class="text-[8px] sm:text-[9px] font-bold text-slate-900 leading-snug">Modul IPA: Ekosistem Alam &amp; Daur Hidup</h5>
                                                <p class="text-[6.5px] text-slate-500 leading-tight line-clamp-1">Observasi rantai makanan, keanekaragaman hayati, dan sains ramah lingkungan.</p>
                                                <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden mt-1">
                                                    <div class="bg-gradient-to-r from-sky-500 to-indigo-600 h-full rounded-full" style="width: 95%"></div>
                                                </div>
                                                <div class="flex items-center justify-between mt-1 pt-1 border-t border-slate-100 text-[6.5px] text-slate-400">
                                                    <span>Pengampu: <strong class="text-slate-700">Ust. Fauzi, S.Pd</strong></span>
                                                    <span class="text-sky-600 font-bold bg-sky-50 px-1 py-0.2 rounded">Siap Presentasi &rarr;</span>
                                                </div>
                                            </div>

                                            <!-- Subject 2: Matematika -->
                                            <div class="bg-white border border-slate-200/80 rounded-lg p-1.5 sm:p-2 shadow-2xs">
                                                <div class="flex items-center justify-between mb-0.5">
                                                    <div class="flex items-center gap-1">
                                                        <span class="px-1.5 py-0.2 bg-purple-100 text-purple-800 rounded text-[6px] font-bold">Matematika</span>
                                                        <span class="text-[6.5px] text-slate-400">Modul 3/4</span>
                                                    </div>
                                                    <span class="text-[6.5px] font-bold text-purple-600">80% Progres</span>
                                                </div>
                                                <h5 class="text-[8px] sm:text-[9px] font-bold text-slate-900 leading-snug">Modul Matematika: Geometri &amp; Pecahan Kontekstual</h5>
                                                <div class="w-full bg-slate-100 h-1 rounded-full overflow-hidden mt-1">
                                                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-full rounded-full" style="width: 80%"></div>
                                                </div>
                                            </div>

                                            <!-- Subject 3: Bahasa Inggris -->
                                            <div class="bg-white border border-slate-200/80 rounded-lg p-1.5 sm:p-2 shadow-2xs flex items-center justify-between">
                                                <div>
                                                    <span class="px-1.5 py-0.2 bg-amber-100 text-amber-800 rounded text-[6px] font-bold">Bahasa Inggris</span>
                                                    <h5 class="text-[8px] sm:text-[9px] font-bold text-slate-900 mt-0.5">Modul English: Conversation &amp; Daily Storytelling</h5>
                                                </div>
                                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[6.5px] font-bold">
                                                    100% (A+)
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ═══════════════════════════════════════════ -->
                                    <!-- PAGE 2: MUTABA'AH TAHFIDZ & STATISTIK      -->
                                    <!-- ═══════════════════════════════════════════ -->
                                    <div x-show="activeHighlight === 2"
                                         x-transition:enter="transition ease-out duration-250"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150 absolute inset-0"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         class="w-full h-full flex flex-col justify-between space-y-1 sm:space-y-1.5">
                                        <!-- Header -->
                                        <div class="flex items-center justify-between pb-1 border-b border-slate-100 shrink-0">
                                            <div>
                                                <h4 class="text-[9px] sm:text-[10px] font-bold text-slate-900 flex items-center gap-1.5">
                                                    <span class="w-4 h-4 rounded-md bg-emerald-500 text-white flex items-center justify-center text-[8px]">
                                                        <i class="fa-solid fa-chart-pie"></i>
                                                    </span>
                                                    Mutaba'ah Tahfidz &amp; Progres Nilai
                                                </h4>
                                                <p class="text-[6.5px] sm:text-[7px] text-slate-500 font-medium">Rekap Hafalan Quran &amp; Performa Belajar</p>
                                            </div>
                                            <span class="text-[7px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded-full border border-emerald-200">
                                                Juz 30: 92%
                                            </span>
                                        </div>

                                        <!-- Stats 3 boxes -->
                                        <div class="grid grid-cols-3 gap-1 shrink-0">
                                            <div class="bg-emerald-50/60 border border-emerald-100 p-1 sm:p-1.5 rounded-lg text-center">
                                                <span class="text-[5.5px] font-bold text-emerald-700 uppercase block">Rata-rata Nilai</span>
                                                <span class="text-[9.5px] font-bold text-emerald-800">96.8</span>
                                            </div>
                                            <div class="bg-purple-50/60 border border-purple-100 p-1 sm:p-1.5 rounded-lg text-center">
                                                <span class="text-[5.5px] font-bold text-purple-700 uppercase block">Surah Teruji</span>
                                                <span class="text-[9.5px] font-bold text-purple-800">18 Surah</span>
                                            </div>
                                            <div class="bg-amber-50/60 border border-amber-100 p-1 sm:p-1.5 rounded-lg text-center">
                                                <span class="text-[5.5px] font-bold text-amber-700 uppercase block">Predikat Tajwid</span>
                                                <span class="text-[9px] font-bold text-amber-800 truncate">Mumtaz ★★★★★</span>
                                            </div>
                                        </div>

                                        <!-- Surah Mutaba'ah List -->
                                        <div class="space-y-1 flex-1 flex flex-col justify-around">
                                            <div class="bg-white border border-emerald-200/90 rounded-lg p-1.5 shadow-2xs flex items-center justify-between">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[7.5px] font-bold">78</span>
                                                    <div>
                                                        <h5 class="text-[8px] sm:text-[8.5px] font-bold text-slate-900 leading-tight">QS. An-Naba' (40 Ayat)</h5>
                                                        <p class="text-[6px] text-slate-400">Penguji: Ust. Fauzi &bull; 16 Sep</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-[6.5px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200 block">Mutqin (98)</span>
                                                    <span class="text-[5.5px] text-slate-400">Lulus Uji Tasmi'</span>
                                                </div>
                                            </div>

                                            <div class="bg-white border border-slate-200/80 rounded-lg p-1.5 shadow-2xs flex items-center justify-between">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[7.5px] font-bold">79</span>
                                                    <div>
                                                        <h5 class="text-[8px] sm:text-[8.5px] font-bold text-slate-900 leading-tight">QS. An-Nazi'at (46 Ayat)</h5>
                                                        <p class="text-[6px] text-slate-400">Penguji: Ust. Fauzi &bull; 12 Sep</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-[6.5px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200 block">Mutqin (96)</span>
                                                    <span class="text-[5.5px] text-slate-400">Lulus Uji Tasmi'</span>
                                                </div>
                                            </div>

                                            <div class="bg-white border border-slate-200/80 rounded-lg p-1.5 shadow-2xs flex items-center justify-between">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-5 h-5 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center text-[7.5px] font-bold">80</span>
                                                    <div>
                                                        <h5 class="text-[8px] sm:text-[8.5px] font-bold text-slate-900 leading-tight">QS. 'Abasa (42 Ayat)</h5>
                                                        <p class="text-[6px] text-slate-400">Progres Hafalan: 85%</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-[6.5px] font-bold text-sky-700 bg-sky-50 px-1.5 py-0.2 rounded border border-sky-200 block">Muraja'ah</span>
                                                    <span class="text-[5.5px] text-slate-400">Siap Uji Tasmi'</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ═══════════════════════════════════════════ -->
                                    <!-- PAGE 3: RAPOR AKADEMIK SISWA                -->
                                    <!-- ═══════════════════════════════════════════ -->
                                    <div x-show="activeHighlight === 3"
                                         x-transition:enter="transition ease-out duration-250"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150 absolute inset-0"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         class="w-full h-full flex flex-col justify-between space-y-1 sm:space-y-1.5">
                                        <!-- Header -->
                                        <div class="flex items-center justify-between pb-1 border-b border-slate-100 shrink-0">
                                            <div>
                                                <h4 class="text-[9px] sm:text-[10px] font-bold text-slate-900 flex items-center gap-1.5">
                                                    <span class="w-4 h-4 rounded-md bg-emerald-500 text-white flex items-center justify-center text-[8px]">
                                                        <i class="fa-solid fa-chart-line"></i>
                                                    </span>
                                                    Perkembangan Akademik Siswa
                                                </h4>
                                                <p class="text-[6.5px] sm:text-[7px] text-slate-500 font-medium">Laporan Capaian Belajar Siswa — Semester Genap</p>
                                            </div>
                                            <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-300 rounded-lg text-[6.5px] sm:text-[7px] font-bold shadow-2xs">
                                                🌟 Predikat: MUMTAZ
                                            </span>
                                        </div>

                                        <!-- Subject Grade Table/Cards: IPA, Matematika, Bahasa Inggris -->
                                        <div class="space-y-1 flex-1 flex flex-col justify-around">
                                            <!-- Item 1: IPA -->
                                            <div class="bg-gradient-to-r from-purple-50/40 via-white to-white border border-purple-200/80 rounded-lg p-1.5 sm:p-2 shadow-2xs">
                                                <div class="flex items-center justify-between mb-0.5">
                                                    <span class="text-[7.5px] sm:text-[8px] font-bold text-slate-900">Ilmu Pengetahuan Alam (IPA)</span>
                                                    <span class="px-1.5 py-0.2 bg-purple-100 text-purple-800 font-bold rounded text-[6.5px]">98 (A+)</span>
                                                </div>
                                                <p class="text-[6.5px] text-slate-600 leading-tight">
                                                    Sangat mahir memahami konsep sains ekosistem dan mempresentasikan hasil observasi dengan percaya diri.
                                                </p>
                                            </div>

                                            <!-- Item 2: Matematika -->
                                            <div class="bg-white border border-slate-200/80 rounded-lg p-1.5 sm:p-2 shadow-2xs">
                                                <div class="flex items-center justify-between mb-0.5">
                                                    <span class="text-[7.5px] sm:text-[8px] font-bold text-slate-900">Matematika Terapan</span>
                                                    <span class="px-1.5 py-0.2 bg-emerald-100 text-emerald-800 font-bold rounded text-[6.5px]">97 (A+)</span>
                                                </div>
                                                <p class="text-[6.5px] text-slate-600 leading-tight">
                                                    Logika berpikir numerik, pemecahan kalkulasi pecahan, dan penalaran geometri bangun datar sangat baik.
                                                </p>
                                            </div>

                                            <!-- Item 3: Bahasa Inggris -->
                                            <div class="bg-white border border-slate-200/80 rounded-lg p-1.5 sm:p-2 shadow-2xs">
                                                <div class="flex items-center justify-between mb-0.5">
                                                    <span class="text-[7.5px] sm:text-[8px] font-bold text-slate-900">Bahasa Inggris (English Communication)</span>
                                                    <span class="px-1.5 py-0.2 bg-sky-100 text-sky-800 font-bold rounded text-[6.5px]">95 (A)</span>
                                                </div>
                                                <p class="text-[6.5px] text-slate-600 leading-tight">
                                                    Percakapan aktif mandiri (speaking) lancar dan penguasaan kosa kata cerita tematik sangat berkembang.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Official Seal Footer -->
                                        <div class="flex items-center justify-between pt-1 border-t border-slate-100 text-[6.5px] text-slate-400 shrink-0">
                                            <span class="flex items-center gap-1 text-emerald-600 font-medium">
                                                <i class="fa-solid fa-circle-check text-[7px]"></i>
                                                Telah Diverifikasi Resmi Digital
                                            </span>
                                            <span class="font-mono text-purple-700 font-bold">QR-VERIFIED #2024-8901</span>
                                        </div>
                                    </div>

                                    <!-- ═══════════════════════════════════════════ -->
                                    <!-- PAGE 4: KELAS VIRTUAL WEBRTC                -->
                                    <!-- ═══════════════════════════════════════════ -->
                                    <div x-show="activeHighlight === 4"
                                         x-transition:enter="transition ease-out duration-250"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150 absolute inset-0"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         class="w-full h-full flex flex-col justify-between space-y-1 sm:space-y-1.5">
                                        <!-- Header -->
                                        <div class="flex items-center justify-between pb-1 border-b border-slate-100 shrink-0">
                                            <div>
                                                <h4 class="text-[9px] sm:text-[10px] font-bold text-slate-900 flex items-center gap-1.5">
                                                    <span class="w-4 h-4 rounded-md bg-rose-500 text-white flex items-center justify-center text-[8px]">
                                                        <i class="fa-solid fa-video"></i>
                                                    </span>
                                                    WebRTC Live Virtual Class
                                                </h4>
                                                <p class="text-[6.5px] sm:text-[7px] text-slate-500 font-medium">Tatap Muka Interaktif 1-on-1 dengan Guru Pembimbing</p>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <span class="flex items-center gap-1 bg-rose-50 text-rose-700 px-1.5 py-0.5 rounded text-[6.5px] font-bold border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                                    Ruang 5A HD
                                                </span>
                                                <span class="bg-slate-100 text-slate-600 px-1 py-0.5 rounded text-[6px] font-mono">24ms P2P</span>
                                            </div>
                                        </div>

                                        <!-- Video Conference Stage -->
                                        <div class="relative rounded-xl overflow-hidden bg-slate-900 text-white aspect-[16/10] sm:aspect-[16/9] shadow-inner flex flex-col justify-between p-2 flex-1">
                                            <!-- Live Teacher Screen Preview -->
                                            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 flex items-center justify-center">
                                                <div class="text-center p-2">
                                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-purple-500 to-indigo-600 text-white flex items-center justify-center text-xs font-bold mx-auto mb-1 ring-2 ring-purple-400/40">
                                                        UF
                                                    </div>
                                                    <span class="text-[8px] font-bold block text-white">Ust. Fauzi, S.Pd (Mentor STEM)</span>
                                                    <span class="text-[6.5px] text-purple-300 font-mono block">Presenting: Materi IPA: Ekosistem & Rantai Makanan</span>
                                                </div>
                                            </div>

                                            <!-- Floating PiP (Sayyidah) -->
                                            <div class="absolute top-2 right-2 w-16 h-12 bg-slate-800 rounded-lg border border-white/30 shadow-md overflow-hidden flex flex-col justify-between p-1 z-10">
                                                <div class="flex justify-end">
                                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                                </div>
                                                <div class="text-[5.5px] font-bold text-white bg-black/60 px-1 rounded truncate">
                                                    Sayyidah (Murid)
                                                </div>
                                            </div>

                                            <!-- Top status badge -->
                                            <div class="relative z-10 flex items-center justify-between">
                                                <span class="bg-black/50 backdrop-blur-md px-1.5 py-0.5 rounded text-[6px] font-bold flex items-center gap-1 text-emerald-400">
                                                    <span class="w-1 h-1 bg-emerald-400 rounded-full"></span>
                                                    WebRTC Terenkripsi
                                                </span>
                                            </div>

                                            <!-- In-call Controls Dock -->
                                            <div class="relative z-10 self-center bg-black/60 backdrop-blur-md px-2 py-1 rounded-full border border-white/20 flex items-center gap-1.5">
                                                <div class="w-4 h-4 rounded-full bg-white/20 text-white flex items-center justify-center text-[7px]">
                                                    <i class="fa-solid fa-microphone"></i>
                                                </div>
                                                <div class="w-4 h-4 rounded-full bg-white/20 text-white flex items-center justify-center text-[7px]">
                                                    <i class="fa-solid fa-video"></i>
                                                </div>
                                                <div class="w-4 h-4 rounded-full bg-purple-600 text-white flex items-center justify-center text-[7px]">
                                                    <i class="fa-solid fa-desktop"></i>
                                                </div>
                                                <div class="w-4 h-4 rounded-full bg-rose-600 text-white flex items-center justify-center text-[7px]">
                                                    <i class="fa-solid fa-phone-slash"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Device Caption -->
                    <div class="mt-2 sm:mt-3 flex items-center justify-between px-1 sm:px-2 text-slate-600 text-[9px] sm:text-[11px] font-medium">
                        <span class="flex items-center gap-1 sm:gap-1.5">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="text-[9px] sm:text-[11px] text-slate-700 font-bold truncate">Dashboard Siswa Says</span>
                        </span>
                        <span class="text-[8px] sm:text-[9px] font-mono text-purple-700 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-full font-bold">LMS v2.4</span>
                    </div>

                </div>
            </div>

        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- SECTION 2: TENTANG KAMI & 4 PILAR BENTO GRID                   -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <section id="tentang" class="py-20 sm:py-28 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <!-- Main Bento Card Container -->
            <div class="glass-card-pro p-7 sm:p-12 lg:p-16 rounded-[36px] reveal-init">
                <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                    
                    <!-- Left: Why Says Homeschooling & Key Metrics -->
                    <div class="lg:col-span-5 space-y-6 sm:space-y-8">
                        <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-purple-700 bg-purple-50 px-3.5 py-1.5 rounded-full border border-purple-200/80 shadow-xs">
                            <i class="fa-solid fa-landmark text-purple-600 text-xs"></i>
                            <span>{{ __('messages.school_profile_label') }}</span>
                        </div>
                        
                        <h2 class="text-3xl sm:text-4xl lg:text-[2.6rem] font-heading font-black text-slate-950 tracking-tight leading-[1.18]">
                            {{ $settings['tentang_title'] ?? 'Mengapa Memilih Says Homeschooling?' }}
                        </h2>
                        
                        <p class="text-slate-600 leading-relaxed font-medium text-sm sm:text-base">
                            {{ $settings['tentang_desc_1'] ?? 'Kami meyakini setiap anak memiliki keunikan fitrah, bakat, dan kecepatan belajar masing-masing yang tidak bisa diseragamkan.' }}
                        </p>
                        <p class="text-slate-600 leading-relaxed text-sm">
                            {{ $settings['tentang_desc_2'] ?? 'Dengan pendekatan holistik yang memadukan kurikulum nasional, pendidikan adab/karakter, serta pendampingan psikologi profesional, anak tumbuh menjadi pribadi mandiri yang siap menghadapi tantangan zaman.' }}
                        </p>

                        <!-- Key Educational Metrics -->
                        <div class="grid grid-cols-3 gap-3 pt-2">
                            <div class="p-3.5 rounded-2xl bg-purple-50/80 border border-purple-100/90 text-center">
                                <span class="text-xl sm:text-2xl font-heading font-black text-purple-700 block">500+</span>
                                <span class="text-[10px] sm:text-[11px] font-bold text-slate-600 leading-tight block mt-0.5">Siswa Dibina</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-indigo-50/80 border border-indigo-100/90 text-center">
                                <span class="text-xl sm:text-2xl font-heading font-black text-indigo-700 block">100%</span>
                                <span class="text-[10px] sm:text-[11px] font-bold text-slate-600 leading-tight block mt-0.5">Terakreditasi</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-100/90 text-center">
                                <span class="text-xl sm:text-2xl font-heading font-black text-emerald-700 block">34</span>
                                <span class="text-[10px] sm:text-[11px] font-bold text-slate-600 leading-tight block mt-0.5">Tema Bakat</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: 4 Pillar Bento Grid -->
                    <div class="lg:col-span-7 grid sm:grid-cols-2 gap-5">
                        @php
                            $pillarConfigs = [
                                0 => [
                                    'icon' => 'fa-solid fa-graduation-cap', 
                                    'bg' => 'bg-purple-50', 
                                    'text' => 'text-purple-600', 
                                    'border' => 'border-purple-200/80',
                                    'badge' => 'Pilar Utama',
                                    'badge_bg' => 'bg-purple-100 text-purple-800'
                                ],
                                1 => [
                                    'icon' => 'fa-solid fa-mosque', 
                                    'bg' => 'bg-emerald-50', 
                                    'text' => 'text-emerald-600', 
                                    'border' => 'border-emerald-200/80',
                                    'badge' => 'Fitrah & Adab',
                                    'badge_bg' => 'bg-emerald-100 text-emerald-800'
                                ],
                                2 => [
                                    'icon' => 'fa-solid fa-lightbulb', 
                                    'bg' => 'bg-amber-50', 
                                    'text' => 'text-amber-600', 
                                    'border' => 'border-amber-200/80',
                                    'badge' => 'Talents Mapping',
                                    'badge_bg' => 'bg-amber-100 text-amber-800'
                                ],
                                3 => [
                                    'icon' => 'fa-solid fa-hand-holding-heart', 
                                    'bg' => 'bg-sky-50', 
                                    'text' => 'text-sky-600', 
                                    'border' => 'border-sky-200/80',
                                    'badge' => 'LMS & WebRTC',
                                    'badge_bg' => 'bg-sky-100 text-sky-800'
                                ],
                            ];
                            $emojiMap = [
                                '📚' => 'fa-solid fa-graduation-cap',
                                '🕌' => 'fa-solid fa-mosque',
                                '🚀' => 'fa-solid fa-lightbulb',
                                '💜' => 'fa-solid fa-hand-holding-heart',
                            ];
                        @endphp
                        @foreach($pillars as $idx => $pillar)
                        @php
                            $cfg = $pillarConfigs[$idx % 4];
                            $rawIcon = trim($pillar->icon ?? '');
                            if (isset($emojiMap[$rawIcon])) {
                                $faClass = $emojiMap[$rawIcon];
                            } elseif (str_contains($rawIcon, 'fa-')) {
                                $faClass = $rawIcon;
                            } else {
                                $faClass = $cfg['icon'];
                            }
                        @endphp
                        <div class="glass-card-pro p-6 rounded-3xl group hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/5">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <!-- Modern Rounded Vector Icon Box -->
                                    <div class="w-12 h-12 rounded-2xl {{ $cfg['bg'] }} {{ $cfg['text'] }} border {{ $cfg['border'] }} flex items-center justify-center text-lg transition-all duration-300 shadow-xs group-hover:scale-110">
                                        @if(str_contains($rawIcon, '<i'))
                                            {!! $rawIcon !!}
                                        @else
                                            <i class="{{ $faClass }}"></i>
                                        @endif
                                    </div>
                                    <span class="text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full {{ $cfg['badge_bg'] }}">
                                        {{ $cfg['badge'] }}
                                    </span>
                                </div>
                                <h4 class="font-heading font-black text-base text-slate-900 tracking-tight group-hover:text-purple-700 transition-colors">
                                    {{ $pillar->judul }}
                                </h4>
                                <p class="text-xs text-slate-600 mt-2.5 leading-relaxed font-medium">
                                    {{ $pillar->deskripsi }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <!-- Visi & Misi Modern Editorial Cards -->
            <div class="grid md:grid-cols-2 gap-6 sm:gap-8 reveal-init">
                <!-- Visi -->
                <div class="glass-card-pro p-8 sm:p-10 rounded-[32px] space-y-4 border border-slate-200/90 bg-white/95 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-lg font-bold shadow-xs">
                            <i class="fa-solid fa-compass"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-purple-600 block">Arah &amp; Landasan</span>
                            <h3 class="text-xl font-heading font-black text-slate-950 tracking-tight">{{ __('messages.our_vision') }}</h3>
                        </div>
                    </div>
                    <div class="pt-2 border-l-2 border-purple-300 pl-4 sm:pl-5">
                        <p class="text-base sm:text-lg text-slate-700 leading-relaxed font-medium italic">
                            "{{ $settings['visi'] ?? 'Menjadi lembaga pendidikan homeschooling terdepan yang melahirkan generasi mandiri, berkarakter mulia, dan berprestasi sesuai fitrah bakatnya.' }}"
                        </p>
                    </div>
                </div>

                <!-- Misi -->
                <div class="glass-card-pro p-8 sm:p-10 rounded-[32px] space-y-4 border border-slate-200/90 bg-white/95 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center text-lg font-bold shadow-xs">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-indigo-600 block">Aksi &amp; Realisasi</span>
                            <h3 class="text-xl font-heading font-black text-slate-950 tracking-tight">{{ __('messages.our_mission') }}</h3>
                        </div>
                    </div>
                    <div class="pt-2 text-sm text-slate-600 space-y-2 leading-relaxed font-medium whitespace-pre-line pl-1">
                        {!! $settings['misi'] ?? '1. Menyelenggarakan pembelajaran berbasis potensi dan fitrah asesi.' !!}
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- SECTION 3: PROGRAM BELAJAR & TAB JENJANG                       -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <section id="program" class="py-20 sm:py-28 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeJenjang: 'all' }">
        <div class="text-center max-w-3xl mx-auto mb-14 space-y-4 reveal-init">
            <span class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-purple-700 bg-purple-50 px-4 py-1.5 rounded-full border border-purple-200/80 shadow-xs">
                <i class="fa-solid fa-graduation-cap text-purple-600"></i>
                <span>Program Pendidikan</span>
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black text-slate-950 tracking-tight">
                {{ $settings['program_title'] ?? 'Program Pendidikan Berkelanjutan' }}
            </h2>
            <p class="text-slate-600 font-medium text-sm sm:text-base leading-relaxed">
                {{ $settings['program_desc'] ?? 'Setiap jenjang dirancang khusus untuk mengoptimalkan potensi intelektual, spiritual, dan emosional anak dengan kurikulum fleksibel.' }}
            </p>
        </div>

        <!-- Programs Grid -->
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($programs as $program)
            @php
                $waProgramMsg = urlencode("Halo Admin Says Homeschooling, saya ingin konsultasi mengenai program Jenjang {$program->jenjang} ({$program->judul}).");
                $waNum = $settings['whatsapp_number'] ?? '628123456789';
            @endphp
            <div class="glass-card-pro rounded-[32px] overflow-hidden flex flex-col justify-between group hover:-translate-y-2.5 transition-all duration-300 reveal-init bg-white/95 border border-slate-200/80 shadow-sm hover:shadow-xl hover:shadow-purple-900/10">
                <div class="p-8 sm:p-9 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black tracking-wider uppercase px-3.5 py-1.5 rounded-xl bg-purple-100/80 text-purple-800 border border-purple-200/70 shadow-xs">
                            Jenjang {{ $program->jenjang }}
                        </span>
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-lg shadow-xs group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                    </div>

                    <h3 class="text-2xl font-heading font-black text-slate-950 tracking-tight group-hover:text-purple-700 transition-colors">
                        {{ $program->judul }}
                    </h3>
                    
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">
                        {{ $program->deskripsi }}
                    </p>

                    <!-- Quick Highlights Pill -->
                    <div class="pt-2 flex flex-wrap gap-2 text-[11px] font-bold text-slate-600">
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100/80 border border-slate-200/60 flex items-center gap-1.5">
                            <i class="fa-solid fa-clock text-purple-600"></i> Fleksibel
                        </span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100/80 border border-slate-200/60 flex items-center gap-1.5">
                            <i class="fa-solid fa-id-card text-indigo-600"></i> Ijazah Resmi
                        </span>
                    </div>
                </div>

                <div class="p-6 sm:p-8 pt-0 space-y-3">
                    @if($program->catatan_kaki)
                    <div class="p-3 rounded-2xl bg-purple-50/70 border border-purple-100 text-xs text-purple-950 font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-purple-600 text-sm"></i>
                        <span>{{ $program->catatan_kaki }}</span>
                    </div>
                    @endif

                    <a href="https://wa.me/{{ $waNum }}?text={{ $waProgramMsg }}" 
                       target="_blank"
                       class="w-full py-3 px-4 rounded-xl bg-slate-100 hover:bg-purple-600 text-slate-800 hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-2 group/btn">
                        <span>Konsultasi Jenjang Ini</span>
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- SECTION 4: LMS & WEBRTC DIGITAL ECOSYSTEM SHOWCASE            -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <section id="lms-features" class="py-20 sm:py-28 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4 reveal-init">
                <span class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-indigo-700 bg-indigo-50 px-4 py-1.5 rounded-full border border-indigo-200/80 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Says Learning Management System</span>
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black text-slate-950 tracking-tight">
                    Ekosistem Belajar Digital Terintegrasi
                </h2>
                <p class="text-slate-600 font-medium text-sm sm:text-base leading-relaxed">
                    Sistem pembelajaran terpadu untuk tatap muka online, ujian otomatis, pemantauan tugas, dan komunikasi real-time tanpa memerlukan aplikasi pihak ketiga.
                </p>
            </div>

            <!-- 4 Modern Bento Tech Cards -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1: WebRTC Class -->
                <div class="glass-card-pro p-7 rounded-[30px] flex flex-col justify-between group reveal-init border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/5">
                    <div>
                        <div class="icon-squircle mb-5 bg-gradient-to-tr from-purple-600 to-indigo-600 text-white shadow-md shadow-purple-600/20">
                            <i class="fa-solid fa-video"></i>
                        </div>
                        <div class="inline-block text-[9px] font-black uppercase tracking-wider text-purple-700 bg-purple-100 px-2 py-0.5 rounded-md mb-2">
                            In-House WebRTC
                        </div>
                        <h3 class="text-lg font-heading font-black text-slate-950">Kelas Video WebRTC</h3>
                        <p class="text-xs text-slate-600 mt-2.5 leading-relaxed font-medium">
                            Tatap muka virtual stabil tanpa Zoom/Meet luar, dilengkapi share screen, raise hand, dan audio jernih.
                        </p>
                    </div>
                    <ul class="mt-6 space-y-2.5 border-t border-slate-100 pt-4 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> P2P Enkripsi Aman</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Fitur Angkat Tangan &amp; Mic</li>
                    </ul>
                </div>

                <!-- Feature 2: Assignments & CBT -->
                <div class="glass-card-pro p-7 rounded-[30px] flex flex-col justify-between group reveal-init border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/5" style="transition-delay: 100ms;">
                    <div>
                        <div class="icon-squircle mb-5 bg-gradient-to-tr from-indigo-600 to-blue-600 text-white shadow-md shadow-indigo-600/20">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                        <div class="inline-block text-[9px] font-black uppercase tracking-wider text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-md mb-2">
                            Smart CBT Engine
                        </div>
                        <h3 class="text-lg font-heading font-black text-slate-950">Tugas &amp; Ujian Online</h3>
                        <p class="text-xs text-slate-600 mt-2.5 leading-relaxed font-medium">
                            Pengumpulan tugas praktis dan ujian berbasis komputer (CBT) dengan penilaian otomatis transparan.
                        </p>
                    </div>
                    <ul class="mt-6 space-y-2.5 border-t border-slate-100 pt-4 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Unggah Berkas Digital</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Koreksi &amp; Ulasan Guru Real-Time</li>
                    </ul>
                </div>

                <!-- Feature 3: Digital E-Rapor -->
                <div class="glass-card-pro p-7 rounded-[30px] flex flex-col justify-between group reveal-init border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/5" style="transition-delay: 200ms;">
                    <div>
                        <div class="icon-squircle mb-5 bg-gradient-to-tr from-amber-500 to-orange-600 text-white shadow-md shadow-amber-500/20">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div class="inline-block text-[9px] font-black uppercase tracking-wider text-amber-700 bg-amber-100 px-2 py-0.5 rounded-md mb-2">
                            Portofolio Siswa
                        </div>
                        <h3 class="text-lg font-heading font-black text-slate-950">E-Rapor &amp; Portofolio</h3>
                        <p class="text-xs text-slate-600 mt-2.5 leading-relaxed font-medium">
                            Grafik capaian akademik, rekap ibadah harian, dan arsip perkembangan bakat anak yang bisa dipantau orang tua.
                        </p>
                    </div>
                    <ul class="mt-6 space-y-2.5 border-t border-slate-100 pt-4 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Rapor Akademik &amp; Diniyah</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Integrasi Talents Mapping</li>
                    </ul>
                </div>

                <!-- Feature 4: Live Direct Chat -->
                <div class="glass-card-pro p-7 rounded-[30px] flex flex-col justify-between group reveal-init border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/5" style="transition-delay: 300ms;">
                    <div>
                        <div class="icon-squircle mb-5 bg-gradient-to-tr from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/20">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <div class="inline-block text-[9px] font-black uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md mb-2">
                            3-Way Chat
                        </div>
                        <h3 class="text-lg font-heading font-black text-slate-950">Obrolan Langsung</h3>
                        <p class="text-xs text-slate-600 mt-2.5 leading-relaxed font-medium">
                            Jalur komunikasi personal antara Guru, Siswa, dan Orang Tua untuk memastikan pendampingan belajar optimal.
                        </p>
                    </div>
                    <ul class="mt-6 space-y-2.5 border-t border-slate-100 pt-4 text-xs font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Notifikasi Instan</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Forum Diskusi Materi Terarah</li>
                    </ul>
                </div>
            </div>

        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- SECTION 5: INVESTASI PENDIDIKAN & KALKULATOR BIAYA              -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <section id="biaya" class="py-20 sm:py-28 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4 reveal-init">
                <span class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-purple-700 bg-purple-50 px-4 py-1.5 rounded-full border border-purple-200/80 shadow-xs">
                    <i class="fa-solid fa-hand-holding-dollar text-purple-600"></i>
                    <span>{{ __('messages.investment_full_online') }}</span>
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black text-slate-950 tracking-tight">
                    {{ __('messages.transparant_flexible_cost') }}
                </h2>
                <p class="text-slate-600 font-medium text-sm sm:text-base leading-relaxed">
                    {{ __('messages.pricing_desc') }}
                </p>
            </div>

            <div class="grid lg:grid-cols-12 gap-8 items-stretch">
                <!-- Left Info Box -->
                <div class="lg:col-span-5 flex flex-col justify-between p-8 sm:p-11 rounded-[36px] bg-gradient-to-tr from-purple-800 via-purple-900 to-indigo-950 text-white shadow-2xl relative overflow-hidden reveal-init">
                    <div class="absolute -top-12 -left-12 w-52 h-52 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -bottom-12 -right-12 w-52 h-52 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="relative space-y-6">
                        <span class="text-[10px] font-black tracking-widest bg-white/15 px-3.5 py-1.5 rounded-full uppercase inline-block border border-white/20">
                            Paket Homeschooling Online &amp; Offline
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-heading font-black tracking-tight leading-snug">
                            {{ __('messages.why_apply_fee_adjustment') }}
                        </h3>
                        <p class="text-xs sm:text-sm text-purple-100/90 leading-relaxed font-normal">
                            {{ __('messages.fee_adjustment_desc') }}
                        </p>
                        
                        <div class="space-y-3.5 pt-2 text-xs font-semibold">
                            <div class="flex items-start gap-3">
                                <span class="h-5 w-5 shrink-0 rounded-full bg-emerald-400 text-slate-950 flex items-center justify-center font-bold text-[10px] shadow-sm">✓</span>
                                <span class="text-purple-50">{{ __('messages.virtual_infra_benefit_1') }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="h-5 w-5 shrink-0 rounded-full bg-emerald-400 text-slate-950 flex items-center justify-center font-bold text-[10px] shadow-sm">✓</span>
                                <span class="text-purple-50">{{ __('messages.virtual_infra_benefit_2') }}</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="h-5 w-5 shrink-0 rounded-full bg-emerald-400 text-slate-950 flex items-center justify-center font-bold text-[10px] shadow-sm">✓</span>
                                <span class="text-purple-50">{{ __('messages.virtual_infra_benefit_3') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-white/15 mt-8 relative">
                        <p class="text-[10px] uppercase font-black tracking-wider text-purple-200">{{ __('messages.want_to_start_learning') }}</p>
                        <div class="flex items-center justify-between mt-2.5">
                            <span class="text-sm font-extrabold text-white">{{ __('messages.contact_admin_consult') }}</span>
                            <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '628123456789' }}?text={{ urlencode($settings['whatsapp_text'] ?? 'Halo Admin, saya tertarik bertanya mengenai rincian biaya.') }}" target="_blank" 
                               class="bg-white text-purple-950 hover:bg-purple-50 px-4 py-2.5 rounded-xl text-xs font-extrabold shadow-md transition-all hover:scale-105 flex items-center gap-1.5">
                                <span>Konsultasi</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Detailed Fee Grid -->
                <div class="lg:col-span-7 grid gap-4 reveal-init">
                    @php
                        $items = $investmentItems;
                        if ($items->isEmpty()) {
                            $items = collect([
                                (object)[
                                    'nama' => 'Formulir Pendaftaran',
                                    'harga' => 675000,
                                    'keterangan' => 'Bebas biaya pendaftaran (termasuk psikotes & konseling) khusus pendaftar baru.'
                                ],
                                (object)[
                                    'nama' => 'Investasi Gedung',
                                    'harga' => 13800000,
                                    'keterangan' => 'Uang gedung fisik disesuaikan menjadi biaya platform virtual & LMS khusus online.'
                                ],
                                (object)[
                                    'nama' => 'Kegiatan Semester',
                                    'harga' => 6525000,
                                    'keterangan' => 'Meliputi lisensi materi digital, modul ajar online, serta ujian semester.'
                                ],
                                (object)[
                                    'nama' => 'SPP Bulanan',
                                    'harga' => 1500000,
                                    'keterangan' => 'SPP bulanan khusus pendampingan virtual interaktif Says Homeschooling.'
                                ]
                            ]);
                        }
                    @endphp

                    @foreach($items as $item)
                    @php
                        $isFormulir = Str::contains(strtolower($item->nama), ['formulir', 'pendaftaran', 'daftar', 'registrasi', 'adm', 'administrasi']);
                        $isGedung = Str::contains(strtolower($item->nama), ['gedung', 'infrastruktur', 'virtual', 'sarana', 'pangkal']);
                        $isSemester = Str::contains(strtolower($item->nama), ['semester', 'kegiatan', 'modul', 'buku', 'seragam']);
                        $isSPP = Str::contains(strtolower($item->nama), ['spp', 'bulanan', 'iuran']);
                        
                        $displayName = $item->nama;
                        if ($isGedung) {
                            $displayName = 'Infrastruktur & Platform Virtual';
                        }
                        
                        $hargaAsli = $item->harga;
                        if ($isFormulir) {
                            $hargaOnline = 0;
                        } elseif ($isGedung || $isSemester || $isSPP) {
                            $hargaOnline = $item->harga * 0.55;
                        } else {
                            $hargaOnline = $item->harga * 0.55;
                        }
                        
                        $hargaAsliFormatted = 'Rp ' . number_format($hargaAsli, 0, ',', '.');
                        $hargaOnlineFormatted = 'Rp ' . number_format($hargaOnline, 0, ',', '.');
                    @endphp
                    <div class="glass-card-pro p-6 sm:p-7 rounded-[28px] flex flex-col gap-4 border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-lg transition-all">
                        <div>
                            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100">
                                <h4 class="font-heading font-black text-base text-slate-900">{{ $displayName }}</h4>
                                @if($isFormulir)
                                    <span class="px-2.5 py-0.5 text-[10px] font-black bg-emerald-100 text-emerald-800 rounded-full">Adm. Fee</span>
                                @else
                                    <span class="px-2.5 py-0.5 text-[10px] font-black bg-purple-100 text-purple-800 rounded-full">Tuition Fee</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 mt-2 font-medium leading-relaxed">
                                @if($isGedung)
                                    Uang gedung fisik disesuaikan menjadi biaya platform virtual &amp; LMS khusus online.
                                @elseif($isSemester)
                                    Meliputi lisensi materi digital, modul ajar online, serta ujian semester.
                                @elseif($isSPP)
                                    SPP bulanan khusus pendampingan virtual interaktif Says Homeschooling.
                                @else
                                    {{ $item->keterangan }}
                                @endif
                            </p>
                        </div>

                        <!-- Price Comparison Grid -->
                        <div class="grid grid-cols-2 gap-3 sm:gap-4 pt-2 border-t border-slate-100">
                            <!-- Offline -->
                            <div class="p-3 sm:p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block mb-1">Kelas Offline (Luring)</span>
                                <span class="text-sm sm:text-base font-black text-slate-800 block">{{ $hargaAsliFormatted }}</span>
                                @if($isSPP)
                                    <span class="text-[9px] text-slate-400 font-semibold block">/ bulan</span>
                                @endif
                            </div>

                            <!-- Online -->
                            <div class="p-3 sm:p-3.5 rounded-2xl bg-purple-50/90 border border-purple-200/90 relative overflow-hidden">
                                <span class="text-[9px] font-black text-purple-700 uppercase tracking-wider block mb-1">Kelas Online (Daring)</span>
                                @if($isFormulir)
                                    <span class="text-sm sm:text-base font-black text-emerald-600 block">Rp 0 (Gratis)</span>
                                    <span class="absolute top-1.5 right-2 text-[8px] font-black text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded-md">Free</span>
                                @else
                                    <span class="text-sm sm:text-base font-black text-purple-900 block">{{ $hargaOnlineFormatted }}</span>
                                    @if($isSPP)
                                        <span class="text-[9px] text-purple-600 font-semibold block">/ bulan</span>
                                    @endif
                                    <span class="absolute top-1.5 right-2 text-[8px] font-black text-purple-700 bg-purple-200/80 px-1.5 py-0.5 rounded-md">Hemat 45%</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- SECTION 6: METODE BELAJAR & JADWAL SESI                        -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <section id="metode" class="py-20 sm:py-28 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Text Left -->
                <div class="lg:col-span-6 space-y-6 reveal-init">
                    <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-purple-700 bg-purple-50 px-4 py-1.5 rounded-full border border-purple-200/80 shadow-xs">
                        <i class="fa-solid fa-sliders text-purple-600 text-xs"></i>
                        <span>{{ __('messages.learning_method_label') }}</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black text-slate-950 tracking-tight leading-[1.18]">
                        {{ $settings['metode_title'] ?? 'Keseimbangan Belajar di Rumah & Pendampingan Guru' }}
                    </h2>
                    <p class="text-slate-600 leading-relaxed font-medium text-sm sm:text-base">
                        {{ $settings['metode_desc'] ?? 'Metode blended learning yang fleksibel menggabungkan pembelajaran mandiri terstruktur dengan interaksi sosial yang sehat dan pendampingan personal.' }}
                    </p>
                    
                    <ul class="space-y-3.5 pt-2">
                        @foreach($learningHighlights as $point)
                        <li class="flex items-center gap-3.5 bg-white border border-slate-200/80 p-4 rounded-2xl text-xs sm:text-sm font-semibold text-slate-800 shadow-xs hover:border-purple-300 hover:shadow-sm transition-all">
                            <div class="h-7 w-7 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center font-bold text-xs shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span>{{ $point->teks }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Cards Right -->
                <div class="lg:col-span-6 grid sm:grid-cols-2 gap-5 reveal-init">
                    @php
                        $methodConfigs = [
                            0 => ['icon' => 'fa-solid fa-calendar-days', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'border' => 'border-indigo-200/80'],
                            1 => ['icon' => 'fa-solid fa-chalkboard-user', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200/80'],
                            2 => ['icon' => 'fa-solid fa-chart-line', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-200/80'],
                            3 => ['icon' => 'fa-solid fa-house-laptop', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200/80'],
                        ];
                        $methodEmojiMap = [
                            '📅' => 'fa-solid fa-calendar-days',
                            '🧑‍🏫' => 'fa-solid fa-chalkboard-user',
                            '📊' => 'fa-solid fa-chart-line',
                            '🏠' => 'fa-solid fa-house-laptop',
                        ];
                    @endphp
                    @foreach($learningMethods as $idx => $card)
                    @php
                        $cfg = $methodConfigs[$idx % 4];
                        $rawIcon = trim($card->icon ?? '');
                        if (isset($methodEmojiMap[$rawIcon])) {
                            $faClass = $methodEmojiMap[$rawIcon];
                        } elseif (str_contains($rawIcon, 'fa-')) {
                            $faClass = $rawIcon;
                        } else {
                            $faClass = $cfg['icon'];
                        }
                    @endphp
                    <div class="glass-card-pro p-6 sm:p-7 rounded-[28px] group hover:-translate-y-2 transition-all duration-300 flex flex-col justify-between border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/5">
                        <div>
                            <div class="w-12 h-12 rounded-2xl {{ $cfg['bg'] }} {{ $cfg['text'] }} border {{ $cfg['border'] }} flex items-center justify-center text-lg mb-4 transition-all duration-300 shadow-xs group-hover:scale-110">
                                @if(str_contains($rawIcon, '<i'))
                                    {!! $rawIcon !!}
                                @else
                                    <i class="{{ $faClass }}"></i>
                                @endif
                            </div>
                            <h4 class="font-heading font-black text-base text-slate-900 tracking-tight mb-2 group-hover:text-purple-700 transition-colors">
                                {{ $card->judul }}
                            </h4>
                            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                                {{ $card->deskripsi }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>

            <!-- Schedule Comparison Cards -->
            <div class="mt-16 grid md:grid-cols-2 gap-8 reveal-init">
                <!-- Online Schedule -->
                <div class="glass-card-pro p-7 sm:p-9 rounded-[32px] space-y-6 border border-slate-200/90 bg-white/95 shadow-sm hover:shadow-lg transition-all relative overflow-hidden">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-lg font-bold shadow-xs">
                            <i class="fa-solid fa-laptop-code"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-heading font-black text-base sm:text-lg text-slate-950">Teknis Kelas Online</h3>
                                <span class="text-[10px] font-black text-purple-700 bg-purple-100 px-2.5 py-0.5 rounded-full">Virtual Learning</span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">Sesi tatap maya interaktif &amp; fleksibel terpandu</p>
                        </div>
                    </div>

                    <div class="space-y-3.5 text-xs text-slate-600">
                        <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-100 space-y-2">
                            <span class="text-xs font-black text-purple-900 uppercase tracking-wider block">Di Bawah Kelas 5 SD:</span>
                            <ul class="list-disc pl-5 space-y-1 font-semibold text-purple-950">
                                <li><strong>Senin:</strong> 08.00 - 11.30 WIB (Akademik)</li>
                                <li><strong>Kamis:</strong> 09.00 - 14.00 WIB (Project Tematik)</li>
                                <li><strong>Jumat:</strong> 08.00 - 11.00 WIB (Akademik &amp; Karakter)</li>
                            </ul>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                            <span class="text-xs font-black text-slate-800 uppercase tracking-wider block">Kelas 5 SD ke Atas:</span>
                            <p class="font-semibold text-slate-700"><strong>Senin, Rabu, &amp; Kamis:</strong> 07.30 - 14.00 WIB</p>
                        </div>
                    </div>
                </div>

                <!-- Offline Schedule -->
                <div class="glass-card-pro p-7 sm:p-9 rounded-[32px] space-y-6 border border-slate-200/90 bg-white/95 shadow-sm hover:shadow-lg transition-all relative overflow-hidden">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg font-bold shadow-xs">
                            <i class="fa-solid fa-school"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-heading font-black text-base sm:text-lg text-slate-950">Teknis Kelas Offline</h3>
                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">Tatap Muka Luring</span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium mt-0.5">Pendampingan langsung &amp; sosialisasi positif</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 space-y-3 text-xs text-slate-600">
                        <span class="text-xs font-black text-emerald-900 uppercase tracking-wider block">Jadwal Kelas Luring (Semua Jenjang):</span>
                        <ul class="list-disc pl-5 space-y-1.5 font-semibold text-emerald-950">
                            <li><strong>Hari Aktif:</strong> Senin, Rabu, &amp; Kamis (07.30 - 14.00 WIB)</li>
                            <li><strong>Selasa &amp; Jumat:</strong> Belajar Mandiri di Rumah (MBDR) Terstruktur</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- SECTION 7: GALERI KEGIATAN & ALBUM FOTO                        -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <section id="galeri" class="py-20 sm:py-28 relative" x-data="{
        activeAlbum: null,
        activePhotoIndex: 0,
        openAlbum(album) {
            this.activeAlbum = album;
            this.activePhotoIndex = 0;
        },
        closeAlbum() {
            this.activeAlbum = null;
        },
        nextPhoto() {
            if (this.activeAlbum && this.activeAlbum.photos.length > 0) {
                this.activePhotoIndex = (this.activePhotoIndex + 1) % this.activeAlbum.photos.length;
            }
        },
        prevPhoto() {
            if (this.activeAlbum && this.activeAlbum.photos.length > 0) {
                this.activePhotoIndex = (this.activePhotoIndex - 1 + this.activeAlbum.photos.length) % this.activeAlbum.photos.length;
            }
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4 reveal-init">
                <span class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-purple-700 bg-purple-50 px-4 py-1.5 rounded-full border border-purple-200/80 shadow-xs">
                    <i class="fa-solid fa-images text-amber-500"></i>
                    <span>{{ __('messages.activity_gallery') }}</span>
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-heading font-black text-slate-950 tracking-tight">{{ __('messages.student_activity_documentation') }}</h2>
                <p class="text-slate-600 font-medium text-sm sm:text-base leading-relaxed">
                    {{ __('messages.gallery_subtitle') }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($galleryAlbums as $album)
                <div class="glass-card-pro rounded-[30px] overflow-hidden group cursor-pointer hover:-translate-y-2.5 transition-all duration-300 reveal-init border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/10"
                     @click="openAlbum({
                         judul: '{{ addslashes($album->judul) }}',
                         deskripsi: '{{ addslashes($album->deskripsi) }}',
                         tanggal: '{{ $album->tanggal ? $album->tanggal->format('d M Y') : '' }}',
                         photos: [
                             @if($album->photos->isEmpty() && $album->cover_path)
                                 {
                                     foto_path: '{{ asset($album->cover_path) }}',
                                     keterangan: 'Cover Album'
                                 }
                             @else
                                 @foreach($album->photos as $photo)
                                 {
                                     foto_path: '{{ asset($photo->foto_path) }}',
                                     keterangan: '{{ addslashes($photo->keterangan ?? "") }}'
                                 },
                                 @endforeach
                             @endif
                         ]
                     })">
                    <div class="aspect-video w-full overflow-hidden relative bg-slate-100">
                        @if($album->cover_path)
                        <img src="{{ asset($album->cover_path) }}" 
                             alt="{{ $album->judul }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <i class="fa-solid fa-image text-4xl"></i>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 text-white">
                            <span class="text-[10px] text-purple-300 font-extrabold uppercase tracking-wider mb-1"><i class="fa-solid fa-camera mr-1"></i> {{ $album->photos->count() }} Foto Dokumentasi</span>
                            <h4 class="font-bold text-base tracking-tight text-white">{{ $album->judul }}</h4>
                        </div>
                    </div>
                    <div class="p-6 flex items-center justify-between">
                        <h4 class="font-heading font-black text-sm text-slate-900 truncate group-hover:text-purple-700 transition-colors">{{ $album->judul }}</h4>
                        @if($album->tanggal)
                        <span class="text-[11px] text-slate-400 font-semibold shrink-0 ml-2">{{ $album->tanggal->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center text-slate-400 font-medium">Belum ada album foto kegiatan diunggah.</div>
                @endforelse
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div x-show="activeAlbum !== null" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md"
             x-transition>
             
             <div class="bg-white rounded-[32px] max-w-4xl w-full shadow-2xl relative flex flex-col md:flex-row overflow-hidden max-h-[90vh]"
                  @click.away="closeAlbum()">
                  
                  <button @click="closeAlbum()" 
                          class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors shadow-xs">
                      <i class="fa-solid fa-xmark text-sm"></i>
                  </button>

                  <div class="flex-1 bg-slate-950 flex flex-col justify-center p-6 min-h-[300px] md:min-h-[450px] relative">
                      <template x-if="activeAlbum && activeAlbum.photos.length > 0">
                          <img :src="activeAlbum.photos[activePhotoIndex].foto_path" 
                               :alt="activeAlbum.judul" 
                               class="w-full h-full object-contain max-h-[50vh]">
                      </template>
                      
                      <template x-if="activeAlbum && activeAlbum.photos.length > 1">
                          <div>
                              <button @click="prevPhoto()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center shadow-md hover:scale-105 transition-all">
                                  <i class="fa-solid fa-chevron-left text-sm"></i>
                              </button>
                              <button @click="nextPhoto()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center shadow-md hover:scale-105 transition-all">
                                  <i class="fa-solid fa-chevron-right text-sm"></i>
                              </button>
                          </div>
                      </template>
                  </div>

                  <div class="w-full md:w-80 p-7 flex flex-col justify-between border-t md:border-t-0 md:border-l border-slate-100 bg-white">
                      <div class="space-y-3.5">
                          <span class="inline-block text-[10px] font-black uppercase text-purple-700 bg-purple-100 px-3 py-1 rounded-full" x-text="activeAlbum ? activeAlbum.tanggal : ''"></span>
                          <h3 class="text-xl font-heading font-black text-slate-950" x-text="activeAlbum ? activeAlbum.judul : ''"></h3>
                          <p class="text-xs text-slate-600 leading-relaxed font-medium whitespace-pre-line max-h-[140px] overflow-y-auto" x-text="activeAlbum ? activeAlbum.deskripsi : ''"></p>
                      </div>

                      <template x-if="activeAlbum && activeAlbum.photos.length > 1">
                          <div class="pt-5 border-t border-slate-100">
                              <span class="text-[10px] font-black uppercase text-slate-400 block mb-2.5">Foto Lainnya:</span>
                              <div class="flex gap-2 overflow-x-auto pb-1">
                                  <template x-for="(photo, index) in activeAlbum.photos" :key="index">
                                      <button @click="activePhotoIndex = index" 
                                              class="w-12 h-12 rounded-xl overflow-hidden border-2 shrink-0 transition-all"
                                              :class="activePhotoIndex === index ? 'border-purple-600 ring-2 ring-purple-200 scale-105' : 'border-slate-200'">
                                          <img :src="photo.foto_path" class="w-full h-full object-cover">
                                      </button>
                                  </template>
                              </div>
                          </div>
                      </template>
                  </div>
             </div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- SECTION 8: ALUR PENDAFTARAN & LACAK STATUS PPDB                -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <section id="pendaftaran" class="py-20 sm:py-28 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4 reveal-init">
                <span class="inline-flex items-center gap-1.5 text-xs font-black uppercase tracking-wider text-purple-700 bg-purple-100 px-3.5 py-1.5 rounded-full border border-purple-200">
                    <i class="fa-solid fa-user-plus text-amber-500"></i>
                    <span>{{ __('messages.new_student_admission') }}</span>
                </span>
                <h2 class="text-3xl sm:text-4xl font-heading font-black text-slate-950 tracking-tight">
                    {{ $settings['pendaftaran_title'] ?? 'Alur Pendaftaran Peserta Didik Baru' }}
                </h2>
                <p class="text-slate-500 font-medium text-sm sm:text-base">
                    {{ $settings['pendaftaran_desc'] ?? 'Bergabunglah bersama keluarga besar Says Homeschooling melalui 3 langkah mudah.' }}
                </p>
            </div>

            <!-- 3 Steps Timeline Grid -->
            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <!-- Step 1 -->
                <div class="glass-card-pro p-8 sm:p-9 rounded-[32px] relative hover:-translate-y-2 transition-all duration-300 reveal-init border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/5">
                    <span class="absolute top-5 right-7 text-4xl sm:text-5xl font-black text-purple-100 font-heading select-none">01</span>
                    <div class="icon-squircle mb-6 bg-purple-50 text-purple-700 border border-purple-200/80 shadow-xs">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-purple-700 bg-purple-100 px-2.5 py-1 rounded-full mb-2 inline-block">Langkah 01</span>
                    <h4 class="font-heading font-black text-lg text-slate-950 mb-2">
                        {{ $settings['pendaftaran_step1'] ?? '1. Isi Formulir Online' }}
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                        {{ $settings['pendaftaran_step1_desc'] ?? 'Lengkapi data diri calon siswa dan orang tua secara online melalui formulir resmi kami.' }}
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="glass-card-pro p-8 sm:p-9 rounded-[32px] relative hover:-translate-y-2 transition-all duration-300 reveal-init border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/5" style="transition-delay: 150ms;">
                    <span class="absolute top-5 right-7 text-4xl sm:text-5xl font-black text-indigo-100 font-heading select-none">02</span>
                    <div class="icon-squircle mb-6 bg-indigo-50 text-indigo-700 border border-indigo-200/80 shadow-xs">
                        <i class="fa-solid fa-brain"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-700 bg-indigo-100 px-2.5 py-1 rounded-full mb-2 inline-block">Langkah 02</span>
                    <h4 class="font-heading font-black text-lg text-slate-950 mb-2">
                        {{ $settings['pendaftaran_step2'] ?? '2. Review & Pemetaan Potensi Fitrah' }}
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                        {{ $settings['pendaftaran_step2_desc'] ?? 'Tim pendidik memverifikasi berkas pendaftaran dan menjadwalkan asesmen pemetaan potensi dan fitrah.' }}
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="glass-card-pro p-8 sm:p-9 rounded-[32px] relative hover:-translate-y-2 transition-all duration-300 reveal-init border border-slate-200/80 bg-white/95 shadow-sm hover:shadow-xl hover:shadow-purple-900/5" style="transition-delay: 300ms;">
                    <span class="absolute top-5 right-7 text-4xl sm:text-5xl font-black text-emerald-100 font-heading select-none">03</span>
                    <div class="icon-squircle mb-6 bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-xs">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full mb-2 inline-block">Langkah 03</span>
                    <h4 class="font-heading font-black text-lg text-slate-950 mb-2">
                        {{ $settings['pendaftaran_step3'] ?? '3. Aktivasi Akun Belajar' }}
                    </h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                        {{ $settings['pendaftaran_step3_desc'] ?? 'Setelah disetujui, gunakan nomor registrasi untuk mengaktifkan akun portal siswa dan mulai belajar.' }}
                    </p>
                </div>
            </div>

            <!-- CTAs Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 reveal-init">
                <a href="{{ route('registration.create') }}" 
                   class="btn-primary-action w-full sm:w-auto px-9 py-4 font-black text-sm tracking-wider uppercase flex items-center justify-center gap-2.5 shadow-xl group">
                    <i class="fa-solid fa-user-plus text-purple-300 group-hover:scale-110 transition-transform"></i>
                    <span>{{ __('messages.fill_online_form') }}</span>
                    <i class="fa-solid fa-arrow-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform"></i>
                </a>

                @php
                    $waNum = $settings['whatsapp_number'] ?? '628123456789';
                    $waTxt = urlencode($settings['whatsapp_text'] ?? 'Halo Admin, saya ingin mendaftar.');
                    $waUrl = "https://wa.me/{$waNum}?text={$waTxt}";
                @endphp
                <a href="{{ $waUrl }}" 
                   target="_blank"
                   class="w-full sm:w-auto px-9 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm tracking-wider uppercase flex items-center justify-center gap-2.5 shadow-lg shadow-emerald-600/20 transition-all hover:scale-105">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>Hubungi Admin via WA</span>
                </a>
            </div>

            <!-- Registration Tracking Widget -->
            <div class="mt-16 max-w-2xl mx-auto reveal-init" x-data="{
                searchNomor: '',
                result: null,
                error: null,
                loading: false,
                track() {
                    if (!this.searchNomor.trim()) return;
                    this.loading = true;
                    this.error = null;
                    this.result = null;
                    fetch('/pendaftaran/lacak/' + encodeURIComponent(this.searchNomor.trim()))
                        .then(res => {
                            if (!res.ok) {
                                return res.json().then(data => { throw new Error(data.message || 'Gagal melacak pendaftaran'); });
                            }
                            return res.json();
                        })
                        .then(data => {
                            this.result = data;
                        })
                        .catch(err => {
                            this.error = err.message;
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            }">
                <div class="glass-card-pro p-7 sm:p-9 rounded-[32px] space-y-6 border border-slate-200/90 bg-white/95 shadow-sm hover:shadow-lg transition-all">
                    <div class="text-center space-y-1">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider text-purple-700 bg-purple-100 px-3 py-1 rounded-full">
                            <i class="fa-solid fa-satellite-dish"></i> Status Real-Time
                        </span>
                        <h3 class="text-lg sm:text-xl font-heading font-black text-slate-950 pt-1">Lacak Status Pendaftaran PPDB</h3>
                        <p class="text-xs text-slate-500 font-medium">Masukkan nomor pendaftaran online Anda untuk melihat progres verifikasi.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" 
                               x-model="searchNomor"
                               @keydown.enter.prevent="track()"
                               placeholder="Contoh: REG-2026-0001" 
                               class="flex-1 px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 placeholder-slate-400 font-semibold text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white transition-all">
                        <button @click="track()" 
                                class="px-6 py-3.5 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl font-black text-xs transition-all shadow-md shadow-purple-600/20 flex items-center justify-center gap-2 hover:scale-105">
                            <span x-show="!loading"><i class="fa-solid fa-magnifying-glass"></i> Lacak Status</span>
                            <span x-show="loading"><i class="fa-solid fa-spinner animate-spin"></i> Memuat</span>
                        </button>
                    </div>

                    <!-- Error Alert -->
                    <div x-show="error" x-transition class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs font-bold flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span x-text="error"></span>
                    </div>

                    <!-- Result Box -->
                    <div x-show="result" x-transition class="border-t border-slate-100 pt-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-bold text-sm text-slate-900" x-text="result.nama_lengkap"></h4>
                                <span class="text-xs text-slate-400 font-mono" x-text="result.nomor_pendaftaran"></span>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider"
                                  :class="{
                                      'bg-amber-100 text-amber-800': result.status === 'pending',
                                      'bg-blue-100 text-blue-800': result.status === 'reviewed',
                                      'bg-emerald-100 text-emerald-800': result.status === 'approved',
                                      'bg-rose-100 text-rose-800': result.status === 'rejected'
                                  }"
                                  x-text="result.status_label"></span>
                        </div>

                        <div x-show="result.status === 'approved'" class="pt-2">
                            <a :href="result.activate_url" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-black flex items-center justify-center gap-2 shadow-md shadow-emerald-600/20 hover:scale-105 transition-all">
                                <i class="fa-solid fa-user-plus"></i>
                                <span>Aktivasi Akun Belajar Siswa</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- FOOTER: MODERN CLEAN STRUCTURE                                 -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <footer class="bg-slate-950 text-slate-300 py-16 sm:py-24 relative overflow-hidden border-t border-slate-800">
        <!-- Soft Ambient Footer Glow -->
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[700px] h-[250px] bg-purple-900/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-12 gap-10 lg:gap-16">
                
                <!-- Brand Info -->
                <div class="md:col-span-5 space-y-5">
                    <div class="flex items-center gap-3.5">
                        <img src="{{ ($schoolProfile && $schoolProfile->logo_path) ? Storage::url($schoolProfile->logo_path) : asset('storage/logos/sayyidah-school-logo.png') }}" 
                             alt="Sayyidah School" 
                             class="h-12 sm:h-14 w-auto object-contain bg-white rounded-2xl p-1.5 shadow-md">
                        <div class="flex flex-col leading-none">
                            <span class="text-xl sm:text-2xl font-heading font-black text-white tracking-tight">{{ $settings['footer_title'] ?? 'SAYYIDAH SCHOOL' }}</span>
                            <span class="text-[10px] sm:text-xs font-bold text-purple-400 uppercase tracking-widest mt-1">Homeschooling</span>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-400 max-w-md leading-relaxed font-normal">
                        {{ $settings['footer_desc'] ?? 'Lembaga pendidikan homeschooling modern yang mendidik generasi berkarakter mandiri, kreatif, dan berakhlak mulia berbasis pemetaan potensi dan fitrah anak.' }}
                    </p>
                    
                    <!-- Official PKBM Credential Badge -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-[11px] text-slate-300">
                        <i class="fa-solid fa-certificate text-emerald-400 text-xs"></i>
                        <span>PKBM Resmi &bull; Terdaftar Kemendikbudristek RI</span>
                    </div>
                </div>

                <!-- Portal Access -->
                <div class="md:col-span-3 space-y-4 text-xs font-semibold">
                    <h4 class="text-white font-heading font-black text-xs uppercase tracking-widest border-l-2 border-purple-500 pl-3">
                        {{ $settings['footer_portal_title'] ?? 'Akses Portal' }}
                    </h4>
                    <nav class="flex flex-col gap-3 pt-1">
                        <a href="{{ route('student.index') }}" class="hover:text-purple-400 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-purple-400"></i>
                            <span>{{ $settings['footer_link_student_text'] ?? 'Masuk Portal Siswa' }}</span>
                        </a>
                        <a href="{{ route('teacher.index') }}" class="hover:text-purple-400 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-chalkboard-user text-indigo-400"></i>
                            <span>{{ $settings['footer_link_teacher_text'] ?? 'Masuk Portal Guru' }}</span>
                        </a>
                        <a href="{{ route('teacher.index') }}" class="hover:text-purple-400 transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-user-shield text-slate-400"></i>
                            <span>{{ $settings['footer_link_admin_text'] ?? 'Portal Administrator' }}</span>
                        </a>
                    </nav>
                </div>

                <!-- Contact & Address -->
                <div class="md:col-span-4 space-y-4 text-xs">
                    <h4 class="text-white font-heading font-black text-xs uppercase tracking-widest border-l-2 border-purple-500 pl-3">
                        Kontak &amp; Kantor
                    </h4>
                    <div class="space-y-2.5 pt-1 text-slate-400 font-medium">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot text-purple-400 mt-1"></i>
                            <p class="leading-relaxed">{{ $settings['footer_office_address'] ?? 'Bogor, Jawa Barat, Indonesia' }}</p>
                        </div>
                        @if(!empty($settings['footer_office_email']))
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-purple-400"></i>
                            <a href="mailto:{{ $settings['footer_office_email'] }}" class="hover:text-white transition-colors">{{ $settings['footer_office_email'] }}</a>
                        </div>
                        @endif
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-purple-400"></i>
                            <a href="tel:{{ $settings['footer_office_phone'] ?? '' }}" class="hover:text-white transition-colors">{{ $settings['footer_office_phone'] ?? '+62 812-3456-789' }}</a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="text-center text-xs text-slate-500 border-t border-slate-900 mt-14 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span>&copy; {{ date('Y') }} {{ $settings['footer_title'] ?? 'Says Homeschooling' }}. All Rights Reserved. {{ $settings['footer_copyright_suffix'] ?? '' }}</span>
                <span class="text-[11px] text-slate-600">Terpadu dengan Talents Mapping &amp; Ekosistem LMS Digital</span>
            </div>
        </div>
    </footer>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- FLOATING WHATSAPP SMART HUB WIDGET                             -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <div class="floating-wa-hub" x-data="{ openWa: false }">
        <!-- Popover Options -->
        <div x-show="openWa" 
             x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-3 scale-95"
             @click.away="openWa = false"
             class="absolute bottom-16 right-0 w-80 bg-white rounded-3xl shadow-2xl border border-purple-100 p-4 space-y-3 text-xs">
            <div class="flex items-center gap-2.5 pb-2.5 border-b border-slate-100">
                <div class="w-9 h-9 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/20">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                </div>
                <div>
                    <h5 class="font-heading font-black text-slate-900 text-sm">Pusat Informasi Siswa</h5>
                    <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Online Siap Membantu
                    </span>
                </div>
            </div>
            <div class="space-y-1.5 font-semibold text-slate-700">
                <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '628123456789' }}?text=Halo%20Admin,%20saya%20ingin%20tanya%20mengenai%20Pendaftaran%20Siswa%20Baru" target="_blank" class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-purple-50 transition-colors">
                    <i class="fa-solid fa-comments text-emerald-600 text-sm"></i>
                    <span>Info PPDB &amp; Pendaftaran</span>
                </a>
                <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '628123456789' }}?text=Halo%20Admin,%20saya%20ingin%20konsultasi%20Program%20&%20Biaya%20Homeschooling" target="_blank" class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-purple-50 transition-colors">
                    <i class="fa-solid fa-calculator text-purple-600 text-sm"></i>
                    <span>Konsultasi Biaya &amp; Paket</span>
                </a>
                <a href="https://wa.me/{{ $settings['whatsapp_number'] ?? '628123456789' }}?text=Halo%20Admin,%20saya%20ingin%20tanya%20Jadwal%20Trial%20Class" target="_blank" class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-purple-50 transition-colors">
                    <i class="fa-solid fa-graduation-cap text-indigo-600 text-sm"></i>
                    <span>Info Trial Class &amp; Kurikulum</span>
                </a>
            </div>
        </div>

        <!-- Floating Button -->
        <button @click="openWa = !openWa" 
                class="w-14 h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center text-2xl shadow-xl shadow-emerald-500/30 hover:scale-105 active:scale-95 transition-all relative">
            <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-rose-500 rounded-full border-2 border-white"></span>
            <i class="fa-brands fa-whatsapp"></i>
        </button>
    </div>


    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- JAVASCRIPT: SCROLL REVEAL, COUNTER & HERO TYPEWRITER           -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Scroll Reveal with IntersectionObserver
            const revealElements = document.querySelectorAll('.reveal-init, .reveal-scale-init');
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });

            revealElements.forEach(el => revealObserver.observe(el));

            // 2. Animated Numbers Counter
            const counterElements = document.querySelectorAll('.counter-val');
            const counterObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const targetVal = parseInt(entry.target.getAttribute('data-target') || '100');
                        let current = 0;
                        const increment = Math.max(1, Math.floor(targetVal / 30));
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= targetVal) {
                                current = targetVal;
                                clearInterval(timer);
                            }
                            entry.target.textContent = current + '%';
                        }, 30);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            counterElements.forEach(el => counterObserver.observe(el));



            // 4. Smooth Language Switcher
            document.body.addEventListener('click', (e) => {
                const switchBtn = e.target.closest('.lang-switch-btn');
                if (switchBtn) {
                    e.preventDefault();
                    const url = switchBtn.getAttribute('href');
                    document.body.classList.add('page-fade-out');
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(() => window.location.reload())
                    .catch(() => window.location.href = url);
                }
            });

            window.addEventListener('pageshow', (event) => {
                if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                    document.body.classList.remove('page-fade-out');
                }
            });
        });
    </script>
</body>
</html>
