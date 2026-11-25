<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.online_class_student') }}</title>
    @php
        $globalSchoolProfile = \App\Models\ProfilSekolah::first();
        $faviconUrl = ($globalSchoolProfile && $globalSchoolProfile->logo_path) 
            ? Storage::url($globalSchoolProfile->logo_path) 
            : asset('storage/logos/sayyidah-school-logo.png');
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #0f172a, #090d16);
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        footer.flex-nowrap > button,
        footer.flex-nowrap > a,
        footer.flex-nowrap #btn-more {
            width: 3rem !important;
            height: 3rem !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 9999px !important;
            flex-shrink: 0 !important;
            gap: 0 !important;
        }
        footer.flex-nowrap > button > span,
        footer.flex-nowrap > a > span {
            display: none !important;
        }
        #chat-sidebar {
            background: #0b0f19 !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
    </style>
</head>
<body class="min-h-screen text-slate-100 p-4 sm:p-6 lg:p-8 flex flex-col justify-between">

    <!-- Autoplay Audio Blocked Banner -->
    <div id="audio-autoplay-banner" class="hidden fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black px-5 py-2.5 rounded-2xl shadow-2xl flex items-center gap-3 border border-amber-300 transition-all">
        <span class="text-xs flex items-center gap-1.5 cursor-pointer" onclick="unmuteAllAudio()">
            🔊 Suara lawan bicara diblokir browser.
        </span>
        <button type="button" onclick="unmuteAllAudio()" class="bg-slate-950 hover:bg-slate-900 text-white text-[10px] uppercase font-bold px-3 py-1.5 rounded-xl transition-all active:scale-95">
            Aktifkan Suara
        </button>
        <button type="button" onclick="showAudioTroubleshootModal('Panduan mengatasi suara tidak terdengar')" class="bg-amber-600/30 hover:bg-amber-600/50 text-slate-950 border border-slate-950/20 text-[10px] uppercase font-bold px-2.5 py-1.5 rounded-xl transition-all active:scale-95" title="Petunjuk Lengkap">
            Bantuan
        </button>
    </div>

    <!-- Header Section -->
    <header class="max-w-7xl mx-auto w-full mb-6 hidden sm:flex flex-col md:flex-row justify-between items-center gap-4 glass-panel p-5 rounded-3xl border border-purple-500/20 shadow-2xl shadow-indigo-950/30">
        <div class="flex items-center gap-4">
            {{-- Pulsing Live Badge --}}
            <div class="flex-shrink-0 bg-pink-500/10 border border-pink-500/30 px-3.5 py-1.5 rounded-2xl flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-pink-500 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-wider text-pink-400">Live</span>
            </div>
            
            <div>
                <h1 class="text-lg sm:text-xl font-extrabold tracking-tight text-white flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-purple-400"></i>
                    {{ $conference->title ?? __('messages.online_class') }}
                </h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-xs text-slate-400 font-medium">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-key text-[10px] text-purple-400"></i>
                        {{ __('messages.room_id') }}: <span class="text-slate-200 font-bold select-all">{{ $room }}</span>
                    </span>
                    @if(isset($conference))
                        <span class="border-l border-slate-705 pl-4 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-graduate text-[10px] text-pink-400"></i>
                            {{ __('messages.students_studying') }}: 
                            <span class="text-pink-300 font-extrabold">
                                {{ is_array($conference->class) ? (empty($conference->target_students) ? implode(', ', $conference->class) : implode(', ', $conference->target_students)) : $conference->class }}
                            </span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-4 flex-shrink-0 w-full md:w-auto justify-end">
            <div class="glass-panel px-4 py-2 rounded-2xl flex items-center gap-2.5 border border-slate-700 bg-slate-900/30">
                <span class="px-2 py-0.5 bg-pink-500/20 text-pink-300 border border-pink-500/30 text-[9px] font-black uppercase tracking-widest rounded">{{ __('messages.student') }}</span>
                <span class="text-sm font-extrabold text-slate-200">{{ $userName }}</span>
            </div>
            <a href="{{ route('student.video-conferences.index') }}" onclick="isLeavingIntentionally = true" 
               class="px-5 py-2 bg-rose-600/10 border border-rose-500/30 hover:bg-rose-600 text-rose-400 hover:text-white rounded-2xl text-xs sm:text-sm font-bold shadow-lg shadow-rose-950/20 active:scale-95 transition-all text-center flex items-center gap-1.5">
                <i class="fa-solid fa-right-from-bracket"></i> {{ __('messages.exit_class') }}
            </a>
        </div>
    </header>

    <!-- Main Container supporting Chat Sidebar -->
    <div class="max-w-7xl mx-auto w-full flex-grow flex gap-6 my-4 relative">
        <!-- Main Conference Area -->
        <main class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[10px] sm:gap-6 content-start" id="conference-container">
            <!-- Room-Centric Status Banner: Waiting for teacher -->
            <div id="teacher-waiting-banner" class="col-span-full glass-panel px-4 py-3 rounded-2xl border border-indigo-500/30 bg-indigo-950/40 flex items-center justify-between gap-3 text-indigo-200 text-xs sm:text-sm">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-chalkboard-user text-indigo-400 text-base"></i>
                    <span><strong>Ruang Kelas Siap.</strong> Menunggu Guru bergabung... Anda dapat menguji mikrofon dan kamera Anda.</span>
                </div>
                <span class="px-2.5 py-1 bg-indigo-500/20 text-indigo-300 rounded-full text-[10px] font-bold border border-indigo-500/30 uppercase tracking-wider whitespace-nowrap">Lobby Persiapan</span>
            </div>

            <!-- Student local video card -->
            <div class="glass-panel rounded-3xl overflow-hidden border border-purple-500/30 relative shadow-2xl flex flex-col justify-between aspect-video group" style="order: 4;" id="localVideoContainer">
                <video id="localVideo" class="w-full h-full object-cover rounded-3xl transform -scale-x-100 transition-transform duration-300" autoplay playsinline muted></video>
                <div id="localVideoPlaceholder" class="absolute inset-0 bg-slate-900/90 backdrop-blur-xs flex-col items-center justify-center gap-2 z-10 transition-opacity duration-300 hidden">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-600 to-indigo-700 flex items-center justify-center text-lg font-bold text-white shadow-lg border border-white/10">
                        {{ strtoupper(substr($userName, 0, 2)) }}
                    </div>
                    <span class="text-[11px] font-semibold text-slate-300 flex items-center gap-1.5 bg-slate-950/60 px-2.5 py-1 rounded-full border border-slate-700/50">
                        <i class="fa-solid fa-video-slash text-rose-400 text-[10px]"></i> {{ __('messages.camera_off') ?? 'Kamera Anda Mati' }}
                    </span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex flex-col justify-between p-4 pointer-events-none z-20">
                    <span id="badge-local" class="self-start px-3 py-1 bg-purple-600 text-white text-[9px] font-black uppercase tracking-widest rounded-full shadow-md">
                        {{ __('messages.student_you') }} | {{ $userClass }}
                    </span>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-200">{{ $userName }}</span>
                    </div>
                </div>
            </div>
        </main>

        <!-- Chat Sidebar -->
        <aside id="chat-sidebar" class="absolute right-0 top-0 h-full z-55 w-80 glass-panel border border-slate-700/60 rounded-3xl flex flex-col hidden overflow-hidden transition-all duration-300 shadow-2xl pb-[92px] sm:pb-0">
            <!-- Chat Header -->
            <div class="p-4 border-b border-slate-700/60 flex justify-between items-center bg-slate-900/40">
                <span class="font-bold text-xs text-slate-205 flex items-center gap-2">
                    💬 {{ __('messages.class_chat') }}
                </span>
                <button onclick="toggleChat()" class="text-slate-400 hover:text-slate-200 text-lg">&times;</button>
            </div>
            <!-- Chat Messages (Scrollable) -->
            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto flex flex-col gap-3 text-xs">
                <!-- Messages will go here -->
            </div>
            <!-- Chat Input Form -->
            <form id="chat-form" class="p-3 border-t border-slate-700/60 bg-slate-900/20 flex gap-2">
                <input type="text" id="chat-input" placeholder="{{ __('messages.type_message') }}..." autocomplete="off" required
                       class="flex-1 px-3 py-2 bg-slate-800/80 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-purple-500">
                <button type="submit" class="p-2.5 bg-purple-650 hover:bg-purple-700 text-white rounded-xl active:scale-95 transition-all">
                    <svg class="w-3.5 h-3.5 rotate-90" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                    </svg>
                </button>
            </form>
        </aside>
    </div>

    <!-- Floating Footer Controls -->
    <footer class="fixed bottom-4 left-1/2 -translate-x-1/2 z-40 w-auto max-w-[95%] glass-panel border border-slate-700/60 p-2 sm:p-2.5 rounded-full shadow-2xl flex flex-nowrap justify-center items-center gap-2 sm:gap-4 transition-all duration-300">
        <!-- Mic Button -->
        <button id="btn-mic" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-purple-500/20 active:scale-95 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
            <span>{{ __('messages.mute') }}</span>
        </button>
        <!-- Video Button -->
        <button id="btn-video" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl font-medium text-xs flex items-center gap-2 border border-slate-700 active:scale-95 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            <span>{{ __('messages.video_off') }}</span>
        </button>
        <!-- Screen Sharing Button -->
        <button id="btn-screen" class="px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-cyan-500/20 active:scale-95 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <span>{{ __('messages.presentation') }}</span>
        </button>
        <!-- Raise Hand Button -->
        <button id="btn-raise-hand" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-amber-500/20 active:scale-95 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0V11m0-5.5v-1a1.5 1.5 0 113 0V11m0-6.5V3.5a1.5 1.5 0 113 0V11M17 11V8.5a1.5 1.5 0 113 0V15a7 7 0 01-14 0v-3"></path></svg>
            <span>{{ __('messages.raise_hand') }}</span>
        </button>
        <!-- Low Data / Weak Signal Mode Button -->
        <button id="btn-low-data" onclick="toggleLowDataMode()" type="button" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl font-medium text-xs flex items-center gap-2 border border-slate-700 active:scale-95 transition-all" title="Mode Hemat Bandwidth & Audio-First untuk Sinyal Lemah">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span id="low-data-label">Hemat Sinyal</span>
        </button>
        <!-- Chat Toggle Button -->
        <button id="btn-chat" onclick="toggleChat()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl font-medium text-xs flex items-center gap-2 border border-slate-700 active:scale-95 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <span>{{ __('messages.chat') }}</span>
        </button>

        <!-- More Options Dropdown (Mobile only wrapper) -->
        <div class="relative sm:hidden block">
            <button id="btn-more" onclick="toggleMoreMenu()" type="button" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl font-medium text-xs flex items-center justify-center border border-slate-700 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
            </button>
            <div id="more-menu" class="absolute bottom-full right-0 mb-3 bg-slate-900/95 backdrop-blur-md border border-slate-700/60 rounded-2xl p-2 hidden flex flex-col gap-2 shadow-2xl min-w-[150px] z-50">
                <!-- Screen share and Raise hand buttons will be dynamically moved here on mobile -->
            </div>
        </div>

        <!-- Leave Class Button -->
        <a href="{{ route('student.video-conferences.index') }}" onclick="isLeavingIntentionally = true" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-rose-500/20 active:scale-95 transition-all text-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span>{{ __('messages.leave') }}</span>
        </a>
    </footer>

    <!-- Kicked Out Modal -->
    <div id="kicked-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/95 backdrop-blur-md transition-opacity duration-300">
        <div class="glass-panel max-w-sm w-full rounded-3xl border border-rose-500/30 shadow-2xl shadow-rose-950/50 p-6 transform transition-all duration-300 scale-95 opacity-0" id="kicked-content">
            <div class="w-16 h-16 rounded-full bg-rose-500/20 flex items-center justify-center mx-auto mb-4 border border-rose-500/50 animate-pulse">
                <span class="text-3xl">🚫</span>
            </div>
            <h3 class="text-xl font-extrabold text-center text-white mb-2">{{ __('messages.access_restricted') }}</h3>
            <p class="text-sm text-slate-400 text-center mb-6">{{ __('messages.kicked_by_teacher') }}</p>
            <div class="flex justify-center">
                <button onclick="window.location.href='{{ route('student.video-conferences.index') }}'" 
                        class="w-full px-5 py-3 rounded-xl text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-lg shadow-rose-500/30 transition-all text-center active:scale-95">
                    {{ __('messages.back_to_class_list') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Overlay untuk Kelas Belum Dimulai -->
    <div id="not-started-overlay" class="hidden fixed inset-0 bg-slate-950/95 z-50 flex flex-col justify-center items-center p-6 text-center animate-fade-in">
        <div class="w-24 h-24 rounded-full bg-amber-500/10 border-2 border-amber-500 flex items-center justify-center mb-6 animate-pulse">
            <span class="text-5xl text-amber-500">⏰</span>
        </div>
        <h2 class="text-3xl font-extrabold text-amber-500 tracking-tight mb-2">{{ __('messages.class_not_started') }}</h2>
        <p class="text-slate-400 max-w-md font-medium text-sm sm:text-base leading-relaxed mb-6">
            {{ __('messages.wait_for_teacher') }}
        </p>
        <div class="flex gap-4">
            <button onclick="window.location.reload()" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-2xl font-black text-xs sm:text-sm shadow-lg shadow-amber-500/20 active:scale-95 transition-all">
                {{ __('messages.try_again') }}
            </button>
            <a href="{{ route('student.video-conferences.index') }}" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl font-black text-xs sm:text-sm border border-slate-700 active:scale-95 transition-all">
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <!-- Modal Overlay untuk Kelas Diakhiri -->
    <div id="ended-overlay" class="hidden fixed inset-0 bg-slate-950/95 z-50 flex flex-col justify-center items-center p-6 text-center transition-opacity duration-500 opacity-0">
        <div class="w-24 h-24 rounded-full bg-rose-500/10 border-2 border-rose-500 flex items-center justify-center mb-6 shadow-lg shadow-rose-500/20">
            <svg class="w-12 h-12 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h2 class="text-3xl font-extrabold text-rose-500 tracking-tight mb-2">{{ __('messages.session_ended') }}</h2>
        <p class="text-slate-400 max-w-md font-medium text-sm sm:text-base leading-relaxed mb-6">
            {{ __('messages.teacher_ended_session') }}
        </p>
        <a href="{{ route('student.video-conferences.index') }}" onclick="isLeavingIntentionally = true" class="px-8 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black text-sm shadow-lg shadow-rose-500/20 active:scale-95 transition-all">
            {{ __('messages.back_now') }}
        </a>
    </div>

    <!-- Modal Overlay untuk Isolasi / Deafen -->
    <div id="isolation-overlay" class="hidden fixed top-24 left-1/2 -translate-x-1/2 z-[60] flex flex-col items-center justify-center p-4 transition-opacity duration-300 w-[90%] max-w-lg pointer-events-none">
        <div class="bg-indigo-950/90 backdrop-blur-xl border border-indigo-500/50 p-6 rounded-3xl shadow-2xl shadow-indigo-900/50 text-center relative overflow-hidden pointer-events-auto">
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-600/10 to-transparent"></div>
            <div class="w-16 h-16 rounded-full bg-indigo-500/20 flex items-center justify-center mx-auto mb-4 border border-indigo-500/50">
                <span class="text-3xl">🤫</span>
            </div>
            <h3 class="text-lg font-extrabold text-white mb-2 relative z-10">{{ __('messages.quiet_mode_enabled') }}</h3>
            <p class="text-sm text-indigo-200 leading-relaxed font-medium relative z-10">
                {{ __('messages.quiet_mode_desc') }}
                <br><br>
                <span class="text-amber-300">💡 {{ __('messages.quiet_mode_tip') }}</span>
            </p>
            <div class="mt-5 relative z-10">
                <button onclick="document.getElementById('isolation-overlay').classList.add('hidden')" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg active:scale-95 transition-all">
                    {{ __('messages.i_understand') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Bantuan & Penyelesaian Masalah Audio Browser -->
    <div id="audio-troubleshoot-modal" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md transition-opacity duration-300">
        <div class="glass-panel max-w-md w-full rounded-3xl border border-amber-500/50 shadow-2xl shadow-amber-950/50 p-6 relative bg-slate-900/95">
            <button type="button" onclick="closeAudioTroubleshootModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white p-2 text-xl leading-none">&times;</button>
            
            <div class="flex items-center gap-3.5 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center flex-shrink-0 text-amber-400 text-2xl animate-pulse">
                    🔊
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-white">Suara Lawan Bicara Belum Terdengar?</h3>
                    <p class="text-[11px] text-amber-400 font-semibold" id="audio-troubleshoot-reason">Browser membatasi pemutaran audio otomatis.</p>
                </div>
            </div>

            <div class="bg-slate-950/60 rounded-2xl p-4 border border-slate-700/60 space-y-3 mb-5 text-xs text-slate-300">
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-300 font-bold flex items-center justify-center text-[11px] flex-shrink-0 mt-0.5">1</span>
                    <p><strong class="text-white">Buka Blokir Browser:</strong> Klik tombol <span class="text-amber-400 font-bold">"Aktifkan Suara Sekarang"</span> di bawah agar browser mengizinkan audio berjalan.</p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-300 font-bold flex items-center justify-center text-[11px] flex-shrink-0 mt-0.5">2</span>
                    <p><strong class="text-white">Periksa Tab Browser:</strong> Pastikan tab browser tidak di-mute (ikon speaker tersilang di atas tab browser).</p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-amber-500/20 text-amber-300 font-bold flex items-center justify-center text-[11px] flex-shrink-0 mt-0.5">3</span>
                    <p><strong class="text-white">Cek Volume & Speaker:</strong> Pastikan volume HP/laptop tidak dalam posisi mute dan speaker/headset aktif.</p>
                </div>
            </div>

            <div id="audio-troubleshoot-feedback" class="hidden mb-4 p-3 bg-emerald-500/20 border border-emerald-500/40 rounded-xl text-emerald-300 text-xs font-bold text-center">
                ✅ Suara berhasil diaktifkan!
            </div>

            <div class="flex flex-col sm:flex-row gap-2.5">
                <button type="button" id="btn-resolve-audio" onclick="handleAudioTroubleshootResolve()" class="flex-1 py-3 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs rounded-2xl shadow-lg shadow-amber-500/30 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <span>🔊 Aktifkan Suara Sekarang</span>
                </button>
                <button type="button" onclick="closeAudioTroubleshootModal()" class="py-3 px-5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-2xl border border-slate-700 active:scale-95 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- WebRTC Mesh Script -->
    <script>
        const roomId = '{{ $room }}';
        const userId = '{{ $userId }}';
        const userName = '{{ $userName }}';
        const role = '{{ $role }}';
        const userClass = '{{ $userClass }}';

        // Connect to the signaling server (configured via env SIGNALING_SERVER_URL, fallback to port 3000 for local dev or current origin for htaccess proxy)
        const configSocketUrl = '{{ config('app.signaling_server_url') }}';
        let socketUrl = configSocketUrl;
        if (!socketUrl) {
            if (window.location.port === '8000' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' || window.location.hostname.endsWith('.test')) {
                socketUrl = `${window.location.protocol}//${window.location.hostname}:3000`;
            } else {
                socketUrl = window.location.origin;
            }
        }
            
        console.log("[Signaling] Connecting to signaling server at:", socketUrl);
        const socket = io(socketUrl, {
            transports: ['websocket']
        });

        let rtcConfig = {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' },
                { urls: 'stun:stun1.l.google.com:19302' },
                { urls: 'stun:stun2.l.google.com:19302' },
                { urls: 'stun:stun.cloudflare.com:3478' }
            ],
            iceCandidatePoolSize: 10,
            iceTransportPolicy: 'all',
            bundlePolicy: 'max-bundle'
        };

        let windowLocalStream = null;
        const peerConnections = {}; // targetSocketId -> RTCPeerConnection
        const retryCounters = {}; // targetSocketId -> number of retries
        const maxRetryAttempts = 2;
        const participantMap = new Map(); // socketId -> participant info
        const pendingCandidates = {}; // socketId -> [RTCIceCandidate] (buffer sebelum remote desc diset)
        const remoteStreams = {}; // socketId -> MediaStream (dedicated per peer)
        const reconnectTimers = {}; // socketId -> timerId (grace period 4 detik)
        let localMicEnabled = true;
        let localVideoEnabled = true;
        let isScreenSharing = false;
        let screenStream = null;
        let screenSocket = null;
        let screenPcMap = {};
        let isLowDataMode = false;
        
        // Flag state isolasi lokal
        let isSelfMutedByTeacher = false;
        let isSelfDeafenedByTeacher = false;
        
        let isHandRaised = false;

        // Memuat Server ICE dinamis sebelum koneksi dimulai
        async function loadIceConfig() {
            try {
                console.log("[WebRTC] Fetching dynamic ICE servers from backend...");
                const res = await fetch('/api/room/ice-servers');
                const data = await res.json();
                if (data.success && data.iceServers) {
                    rtcConfig.iceServers = data.iceServers;
                    console.log("[WebRTC] Dynamic ICE servers loaded: " + rtcConfig.iceServers.length + " server entries.");
                }
            } catch (err) {
                console.warn("[WebRTC] Failed to load dynamic ICE servers, using fallback Google STUN.", err);
            }
        }

        // Terapkan batas bitrate (Audio-First QoS & Low Data Mode)
        async function applySenderBitrates(pc) {
            if (!pc) return;
            try {
                const senders = pc.getSenders();
                for (const sender of senders) {
                    if (sender.track && sender.track.kind === 'video') {
                        const params = sender.getParameters();
                        if (!params.encodings || params.encodings.length === 0) {
                            params.encodings = [{}];
                        }
                        // Mode Normal: max 250kbps (360p 18fps). Mode Hemat: max 80kbps (10fps)
                        params.encodings[0].maxBitrate = isLowDataMode ? 80000 : 250000;
                        params.encodings[0].maxFramerate = isLowDataMode ? 10 : 18;
                        params.degradationPreference = 'maintain-framerate';
                        await sender.setParameters(params);
                    } else if (sender.track && sender.track.kind === 'audio') {
                        const params = sender.getParameters();
                        if (!params.encodings || params.encodings.length === 0) {
                            params.encodings = [{}];
                        }
                        // Alokasikan audio prioritas tinggi (28 kbps Opus crystal clear)
                        params.encodings[0].maxBitrate = 28000;
                        params.encodings[0].priority = 'high';
                        params.encodings[0].networkPriority = 'high';
                        await sender.setParameters(params);
                    }
                }
            } catch (e) {
                console.warn("[WebRTC] Could not set sender parameters:", e);
            }
        }

        // Toggle Mode Hemat Kuota & Sinyal Lemah
        function toggleLowDataMode() {
            isLowDataMode = !isLowDataMode;
            const btn = document.getElementById('btn-low-data');
            const label = document.getElementById('low-data-label');
            
            if (isLowDataMode) {
                btn.className = "px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-slate-950 font-extrabold rounded-2xl text-xs flex items-center gap-2 shadow-lg shadow-amber-500/30 active:scale-95 transition-all border border-amber-400";
                label.innerText = "Hemat Sinyal: ON";
                console.log("[WebRTC] Mode Hemat Sinyal diaktifkan. Memprioritaskan Audio.");
            } else {
                btn.className = "px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl font-medium text-xs flex items-center gap-2 border border-slate-700 active:scale-95 transition-all";
                label.innerText = "Hemat Sinyal";
                console.log("[WebRTC] Mode Hemat Sinyal dinonaktifkan.");
            }

            // Terapkan ke seluruh peer connection aktif
            Object.values(peerConnections).forEach(pc => applySenderBitrates(pc));
        }

        // Initialize camera and mic (Audio-First & SD Video Constraints)
        async function init() {
            // Sembunyikan tombol share screen jika di mobile atau tidak didukung
            const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (isMobileDevice || !navigator.mediaDevices || !navigator.mediaDevices.getDisplayMedia) {
                const btnScreen = document.getElementById('btn-screen');
                if (btnScreen) btnScreen.style.display = 'none';
            }

            // Muat konfigurasi ICE dinamis
            await loadIceConfig();

            try {
                console.log("[init] Requesting local camera & microphone streams (Optimized QoS)...");
                windowLocalStream = await navigator.mediaDevices.getUserMedia({
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true,
                        channelCount: 1,
                        sampleRate: 48000
                    },
                    video: {
                        width: { ideal: 640, max: 854 },
                        height: { ideal: 360, max: 480 },
                        frameRate: { ideal: 15, max: 20 },
                        facingMode: 'user'
                    }
                });
                document.getElementById('localVideo').srcObject = windowLocalStream;
                console.log("[init] Local camera and microphone stream successfully loaded.");

                console.log("[init] Emitting join-room to signaling server...");
                socket.emit('join-room', { roomId, userId, role, userName, userClass });
            } catch (err) {
                console.error("[init] Error accessing camera/microphone:", err);
                alert("{{ __('messages.camera_mic_error') }}");
            }
        }

        // Socket connection error handlers & auto re-join on reconnect
        socket.on('connect_error', (error) => {
            console.error('[Socket] Connection error:', error);
            // Optional: show server down UI here
        });

        socket.on('connect', () => {
            console.log('[Socket] Connected / Reconnected to signaling server');
            // Otomatis bergabung kembali ke room saat socket reconnect tanpa refresh halaman
            if (windowLocalStream) {
                console.log('[Socket] Auto re-joining room after reconnect...');
                socket.emit('join-room', { roomId, userId, role, userName, userClass });
            }
        });

        // Kontrol tampilan overlay pemulihan jaringan
        function updateConnectivityUI() {
            // Reconnecting overlays are handled per-peer video frame (setPeerReconnectingState)
        }

        // Laporkan error ke backend Laravel
        async function logWebRTCErrorToBackend(targetSocketId, errorDetail) {
            try {
                await fetch('/api/room/log-error', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        room: roomId,
                        error_message: `Peer connection error with ${targetSocketId}: ${errorDetail}`,
                        connection_state: peerConnections[targetSocketId]?.iceConnectionState || 'unknown'
                    })
                });
                console.log("[WebRTC] Log error sent to backend.");
            } catch (e) {
                console.error("[WebRTC] Failed to send log to backend:", e);
            }
        }

        async function createPeerConnection(targetSocketId, isOfferCreator) {
            console.log(`[RTCPeerConnection] Creating peer connection for ${targetSocketId}. isOfferCreator: ${isOfferCreator}`);
            
            // Clean up existing peer connection if any
            if (peerConnections[targetSocketId]) {
                try { peerConnections[targetSocketId].close(); } catch(e) {}
            }

            const pc = new RTCPeerConnection(rtcConfig);
            peerConnections[targetSocketId] = pc;
            if (retryCounters[targetSocketId] === undefined) {
                retryCounters[targetSocketId] = 0;
            }

            // 1. ADD TRACKS BEFORE OFFER/ANSWER CREATION
            if (windowLocalStream) {
                windowLocalStream.getTracks().forEach(track => {
                    // CRITICAL MUTE PERSISTENCE CHECK:
                    if (track.kind === 'audio' && isSelfMutedByTeacher) {
                        track.enabled = false;
                        console.log(`[RTCPeerConnection] Local audio track disabled during creation for peer ${targetSocketId} due to active isolation.`);
                    }
                    pc.addTrack(track, windowLocalStream);
                    console.log(`[RTCPeerConnection] Attached local track: ${track.kind} to peer: ${targetSocketId}`);
                });
            } else {
                console.warn("[RTCPeerConnection] localStream is not loaded yet when creating peer connection.");
            }

            // 2. ICE CANDIDATE HANDLER
            pc.onicecandidate = (event) => {
                if (event.candidate) {
                    console.log(`[RTCPeerConnection] Generated ICE candidate for target ${targetSocketId}`);
                    socket.emit('send-ice-candidate', { targetSocketId, candidate: event.candidate });
                }
            };

            // 3. TRACK HANDLER
            pc.ontrack = (event) => {
                console.log(`[RTCPeerConnection] ontrack triggered from target ${targetSocketId}. Kind: ${event.track ? event.track.kind : 'unknown'}`);
                let remoteStream = remoteStreams[targetSocketId];
                if (!remoteStream) {
                    remoteStream = new MediaStream();
                    remoteStreams[targetSocketId] = remoteStream;
                }
                if (event.track) {
                    if (!remoteStream.getTracks().some(t => t.id === event.track.id)) {
                        remoteStream.addTrack(event.track);
                    }
                    event.track.onmute = () => {
                        console.log(`[RTCPeerConnection] Track ${event.track.kind} muted from ${targetSocketId}`);
                        updatePeerPlaceholder(targetSocketId);
                        updatePeerAudioIndicator(targetSocketId);
                    };
                    event.track.onunmute = () => {
                        console.log(`[RTCPeerConnection] Track ${event.track.kind} unmuted from ${targetSocketId}`);
                        updatePeerPlaceholder(targetSocketId);
                        updatePeerAudioIndicator(targetSocketId);
                    };
                }
                renderRemoteStream(targetSocketId, remoteStream);

                // CRITICAL DEAFEN PERSISTENCE CHECK:
                if (isSelfDeafenedByTeacher) {
                    pc.getReceivers().forEach(receiver => {
                        if (receiver.track && receiver.track.kind === 'audio') {
                            receiver.track.enabled = false;
                            console.log(`[RTCPeerConnection] Receiver audio track from ${targetSocketId} disabled due to active isolation.`);
                        }
                    });
                }
            };

            // 4. CONNECTION STATE CHANGE WITH ICE RESTART LOGIC
            pc.oniceconnectionstatechange = async () => {
                const state = pc.iceConnectionState;
                console.log(`[RTCPeerConnection] ICE connection state with ${targetSocketId} -> ${state}`);
                
                updateConnectivityUI();

                if (state === 'disconnected') {
                    console.warn(`[RTCPeerConnection] Soft disconnect detected with ${targetSocketId}. Waiting for auto recovery...`);
                    setPeerReconnectingState(targetSocketId, true);
                }

                if (state === 'connected' || state === 'completed') {
                    setPeerReconnectingState(targetSocketId, false);
                    applySenderBitrates(pc);
                }

                if (state === 'failed') {
                    console.error(`[RTCPeerConnection] ICE connection failed with ${targetSocketId}.`);
                    
                    if (retryCounters[targetSocketId] < maxRetryAttempts) {
                        retryCounters[targetSocketId]++;
                        console.log(`[RTCPeerConnection] Attempting iceRestart (${retryCounters[targetSocketId]}/${maxRetryAttempts}) with ${targetSocketId}...`);
                        
                        try {
                            if (isOfferCreator) {
                                const offer = await pc.createOffer({ iceRestart: true });
                                await pc.setLocalDescription(offer);
                                socket.emit('send-offer', { targetSocketId, sdp: offer });
                            } else {
                                console.log(`[RTCPeerConnection] Requesting peer ${targetSocketId} to initiate ICE restart...`);
                                socket.emit('request-ice-restart', { targetSocketId });
                            }
                        } catch (err) {
                            console.error("[RTCPeerConnection] Failed to trigger iceRestart:", err);
                        }
                    } else {
                        console.error(`[RTCPeerConnection] Connection failed permanently with ${targetSocketId} after max retries.`);
                        await logWebRTCErrorToBackend(targetSocketId, "ICE_RESTART_LIMIT_REACHED");
                        updateConnectivityUI();
                        removeRemotePeer(targetSocketId);
                    }
                }
            };

            pc.onconnectionstatechange = () => {
                console.log(`[RTCPeerConnection] Overall connection state with ${targetSocketId} -> ${pc.connectionState}`);
                if (pc.connectionState === 'disconnected') {
                    setPeerReconnectingState(targetSocketId, true);
                } else if (pc.connectionState === 'failed' || pc.connectionState === 'closed') {
                    setPeerReconnectingState(targetSocketId, true);
                    if (retryCounters[targetSocketId] >= maxRetryAttempts) {
                        removeRemotePeer(targetSocketId);
                    }
                } else if (pc.connectionState === 'connected') {
                    setPeerReconnectingState(targetSocketId, false);
                    applySenderBitrates(pc);
                }
            };

            // 5. NEGOTIATION: IF INITIATOR (OFFER CREATOR)
            if (isOfferCreator) {
                try {
                    console.log(`[RTCPeerConnection] Creating Offer for target: ${targetSocketId}`);
                    const offer = await pc.createOffer();
                    await pc.setLocalDescription(offer);
                    console.log(`[RTCPeerConnection] Local description set for target: ${targetSocketId}`);
                    socket.emit('send-offer', { targetSocketId, sdp: offer });
                } catch (err) {
                    console.error(`[RTCPeerConnection] Failed to negotiate with target ${targetSocketId}:`, err);
                }
            }

            return pc;
        }

        // Helper function to freeze frame and show reconnecting overlay with debounce
        function setPeerReconnectingState(socketId, isReconnecting) {
            if (reconnectTimers[socketId]) {
                clearTimeout(reconnectTimers[socketId]);
                delete reconnectTimers[socketId];
            }

            const card = document.getElementById(`wrapper-${socketId}`);
            const video = document.getElementById(`video-${socketId}`);
            let reconnectOverlay = document.getElementById(`reconnect-overlay-${socketId}`);

            if (isReconnecting) {
                // Beri waktu toleransi 4 detik sebelum membekukan video dan menampilkan overlay
                reconnectTimers[socketId] = setTimeout(() => {
                    const currentCard = document.getElementById(`wrapper-${socketId}`);
                    const currentVideo = document.getElementById(`video-${socketId}`);
                    if (!currentCard) return;

                    if (currentVideo && !currentVideo.paused) {
                        try { currentVideo.pause(); } catch(e) {}
                    }

                    let overlay = document.getElementById(`reconnect-overlay-${socketId}`);
                    if (!overlay) {
                        overlay = document.createElement('div');
                        overlay.id = `reconnect-overlay-${socketId}`;
                        overlay.className = "absolute inset-0 bg-slate-950/75 backdrop-blur-sm flex flex-col items-center justify-center gap-3 z-30 transition-all duration-300 pointer-events-auto";
                        overlay.innerHTML = `
                            <div class="flex flex-col items-center justify-center gap-3 text-center p-4">
                                <div class="w-8 h-8 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                                <span class="text-sm font-extrabold text-slate-200 tracking-wider">{{ __('messages.reconnecting') }}</span>
                                <span class="text-[10px] text-slate-400">{{ __('messages.connection_lost') }}</span>
                            </div>
                        `;
                        currentCard.appendChild(overlay);
                    }
                }, 4000);
            } else {
                if (reconnectOverlay) {
                    reconnectOverlay.remove();
                }
                if (video && video.paused) {
                    video.play().catch(e => {
                        if (e.name !== 'AbortError') console.warn("Failed to resume remote video:", e);
                    });
                }
            }
        }

        let hasShownAudioTroubleshoot = false;

        function showAudioAutoplayBanner() {
            const banner = document.getElementById('audio-autoplay-banner');
            if (banner) banner.classList.remove('hidden');
        }

        function showAudioTroubleshootModal(reason = '') {
            const modal = document.getElementById('audio-troubleshoot-modal');
            const reasonEl = document.getElementById('audio-troubleshoot-reason');
            if (reason && reasonEl) {
                reasonEl.innerText = reason;
            }
            if (modal) {
                modal.classList.remove('hidden');
                hasShownAudioTroubleshoot = true;
            }
        }

        function closeAudioTroubleshootModal() {
            const modal = document.getElementById('audio-troubleshoot-modal');
            if (modal) modal.classList.add('hidden');
        }

        function handleAudioTroubleshootResolve() {
            unmuteAllAudio();
            let stillBlocked = false;
            document.querySelectorAll('#conference-container audio').forEach(a => {
                if (a.paused && !isSelfDeafenedByTeacher) stillBlocked = true;
            });

            const feedback = document.getElementById('audio-troubleshoot-feedback');
            if (!stillBlocked) {
                if (feedback) {
                    feedback.className = "mb-4 p-3 bg-emerald-500/20 border border-emerald-500/40 rounded-xl text-emerald-300 text-xs font-bold text-center";
                    feedback.innerText = "✅ Suara berhasil diaktifkan!";
                    feedback.classList.remove('hidden');
                }
                setTimeout(() => {
                    closeAudioTroubleshootModal();
                    if (feedback) feedback.classList.add('hidden');
                }, 1000);
            } else {
                if (feedback) {
                    feedback.className = "mb-4 p-3 bg-amber-500/20 border border-amber-500/40 rounded-xl text-amber-300 text-xs font-bold text-center";
                    feedback.innerText = "⚠️ Audio masih tertahan oleh browser. Silakan klik sekali lagi atau periksa izin audio browser.";
                    feedback.classList.remove('hidden');
                }
            }
        }

        function unmuteAllAudio() {
            let hasBlocked = false;
            document.querySelectorAll('#conference-container audio').forEach(a => {
                if (!isSelfDeafenedByTeacher) {
                    a.muted = false;
                }
                if (a.paused && !isSelfDeafenedByTeacher) {
                    a.play().catch(e => {
                        hasBlocked = true;
                    });
                }
            });

            // Remote video selalu muted untuk mencegah konflik autoplay ganda
            document.querySelectorAll('#conference-container video').forEach(v => {
                if (v.id !== 'localVideo') {
                    v.muted = true;
                }
                if (v.paused) {
                    v.play().catch(e => {});
                }
            });

            const banner = document.getElementById('audio-autoplay-banner');
            if (banner) {
                if (hasBlocked) {
                    banner.classList.remove('hidden');
                } else {
                    banner.classList.add('hidden');
                }
            }
        }

        // Global user interaction listener to unlock audio autoplay persisten (tanpa { once: true })
        ['click', 'touchstart', 'keydown'].forEach(evt => {
            window.addEventListener(evt, unmuteAllAudio, { passive: true });
        });

        function removeStaleUserWrapper(pUserId, pRole, currentSocketId) {
            participantMap.forEach((p, sId) => {
                if (p.userId === pUserId && p.role === pRole && sId !== currentSocketId) {
                    console.log(`[DOM/WebRTC] Cleaning up stale participant for userId ${pUserId} (old socket: ${sId})`);
                    removeRemotePeer(sId);
                }
            });
        }

        // Render remote video stream (dari Guru atau Murid lain)
        function renderRemoteStream(socketId, stream) {
            console.log(`[DOM] renderRemoteStream called for socketId: ${socketId}`);

            const existingWrapper = document.getElementById(`wrapper-${socketId}`);
            if (existingWrapper) {
                console.log(`[DOM] Updating existing stream card for socketId: ${socketId}`);
                const existingVideo = document.getElementById(`video-${socketId}`);
                const existingAudio = document.getElementById(`audio-${socketId}`);
                
                const hasVideoTrack = stream && stream.getVideoTracks().length > 0;
                const hasAudioTrack = stream && stream.getAudioTracks().length > 0;
                const videoNeedsRebind = !existingVideo.srcObject || (existingVideo.srcObject.getVideoTracks().length === 0 && hasVideoTrack) || existingVideo.srcObject !== stream;
                const audioNeedsRebind = !existingAudio.srcObject || (existingAudio.srcObject.getAudioTracks().length === 0 && hasAudioTrack) || existingAudio.srcObject !== stream;

                if (existingVideo && videoNeedsRebind) {
                    existingVideo.srcObject = null;
                    existingVideo.srcObject = stream;
                }
                if (existingAudio && audioNeedsRebind) {
                    existingAudio.srcObject = null;
                    existingAudio.srcObject = stream;
                }

                if (existingVideo) {
                    existingVideo.muted = true;
                    existingVideo.play().catch(e => {
                        if (e.name !== 'AbortError') console.warn("Failed to play existing video:", e);
                    });
                }
                if (existingAudio) {
                    if (!isSelfDeafenedByTeacher) existingAudio.muted = false;
                    existingAudio.play().catch((err) => {
                        console.warn(`[Audio Autoplay Blocked on stream update] for ${socketId}:`, err);
                        if (!hasShownAudioTroubleshoot) {
                            showAudioTroubleshootModal("Browser memblokir pembaruan stream suara.");
                        } else {
                            showAudioAutoplayBanner();
                        }
                    });
                }
                updatePeerPlaceholder(socketId);
                updatePeerAudioIndicator(socketId);
                return;
            }

            const participant = participantMap.get(socketId) || {};
            const pUserId = participant.userId || socketId;
            const pRole = participant.role || 'student';

            // Clean up any stale wrapper for the same userId and role
            removeStaleUserWrapper(pUserId, pRole, socketId);

            const container = document.getElementById('conference-container');
            
            const card = document.createElement('div');
            card.id = `wrapper-${socketId}`;
            card.className = "glass-panel rounded-3xl overflow-hidden border border-slate-700/60 relative shadow-2xl flex flex-col justify-between aspect-video group";

            const pName = participant.userName || `User (${socketId})`;
            const pClass = participant.userClass || '';

            // Video Element
            const video = document.createElement('video');
            video.id = `video-${socketId}`;
            if (pRole === 'teacher_screen' || pRole === 'student_screen') {
                video.className = "w-full h-full object-cover rounded-3xl";
            } else {
                video.className = "w-full h-full object-cover rounded-3xl transform -scale-x-100";
            }
            video.srcObject = stream;
            video.autoplay = true;
            video.playsInline = true;
            // Remote video selalu muted karena output suara ditangani khusus oleh elemen <audio>
            video.muted = true;

            // Dedicated Audio Element to guarantee uninterrupted voice communication
            const audio = document.createElement('audio');
            audio.id = `audio-${socketId}`;
            audio.srcObject = stream;
            audio.autoplay = true;
            audio.playsInline = true;
            if (isSelfDeafenedByTeacher) {
                audio.muted = true;
            }
            card.appendChild(audio);

            // Attempt explicit play & catch Autoplay Policy block
            audio.play().catch(err => {
                console.warn(`[Audio Autoplay Blocked] for ${socketId}:`, err);
                const reasonText = err.name === 'NotAllowedError'
                    ? "Browser memblokir suara otomatis karena memerlukan interaksi klik."
                    : `Kendala audio browser: ${err.message || err.name}`;
                if (!hasShownAudioTroubleshoot) {
                    showAudioTroubleshootModal(reasonText);
                } else {
                    showAudioAutoplayBanner();
                }
            });
            video.play().catch(err => {
                if (err.name !== 'AbortError') {
                    console.warn(`[Video Play Prevented] for ${socketId}:`, err);
                }
            });

            // Off-Cam Placeholder (Avatar initials + badge jika kamera dimatikan/belum aktif)
            const placeholder = document.createElement('div');
            placeholder.id = `placeholder-${socketId}`;
            const isVideoInitOff = participant.isVideoOff === true || (stream && stream.getVideoTracks().length === 0);
            placeholder.className = `absolute inset-0 bg-slate-900/90 backdrop-blur-xs flex flex-col items-center justify-center gap-2 z-10 transition-opacity duration-300 ${isVideoInitOff ? 'flex' : 'hidden'}`;
            
            const initials = (pName || 'U').split(' ').filter(Boolean).map(n => n[0]).slice(0, 2).join('').toUpperCase();
            placeholder.innerHTML = `
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center text-lg font-bold text-white shadow-lg border border-white/10">
                    ${initials}
                </div>
                <span class="text-[11px] font-semibold text-slate-300 flex items-center gap-1.5 bg-slate-950/60 px-2.5 py-1 rounded-full border border-slate-700/50">
                    <i class="fa-solid fa-video-slash text-rose-400 text-[10px]"></i> {{ __('messages.camera_off') ?? 'Kamera Dinonaktifkan' }}
                </span>
            `;

            const overlay = document.createElement('div');
            overlay.className = "absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex flex-col justify-between p-4 pointer-events-none z-20";

            // Terapkan urutan (Order)
            if (pRole === 'teacher') {
                card.style.order = 1;
            } else if (pRole === 'pimpinan') {
                card.style.order = 2;
            } else if (pRole === 'teacher_screen') {
                card.style.order = 3;
            } else {
                card.style.order = 4;
            }

            const badgeContainer = document.createElement('div');
            badgeContainer.className = "flex justify-between items-center w-full pointer-events-auto";

            const badge = document.createElement('span');
            badge.id = `badge-${socketId}`;

            let badgeClass = "self-start px-3 py-1 bg-slate-800 text-slate-300 text-[9px] font-black uppercase tracking-widest rounded-full border border-slate-700/30";
            let badgeText = "{{ __('messages.student') }} (" + pClass + ")";

            if (pRole === 'pimpinan') {
                badgeClass = "self-start px-3 py-1 bg-amber-500/20 text-amber-300 text-[9px] font-black uppercase tracking-widest rounded-full border border-amber-500/40 shadow-sm shadow-amber-500/20";
                badgeText = "👑 Pimpinan Sekolah";
            } else if (pRole === 'teacher') {
                badgeClass = "self-start px-3 py-1 bg-indigo-500/20 text-indigo-300 text-[9px] font-black uppercase tracking-widest rounded-full border border-indigo-500/30";
                badgeText = "{{ __('messages.teacher') }}";
            } else if (pRole === 'teacher_screen') {
                badgeClass = "self-start px-3 py-1 bg-indigo-500/20 text-indigo-300 text-[9px] font-black uppercase tracking-widest rounded-full border border-indigo-500/30";
                badgeText = "{{ __('messages.teacher_presentation') }}";
            } else if (pRole === 'student_screen') {
                badgeText = "{{ __('messages.student_presentation') }} (" + pClass + ")";
            }

            badge.className = badgeClass;
            badge.innerText = badgeText;
            badgeContainer.appendChild(badge);

            // Signal quality badge
            const signalBadge = document.createElement('span');
            signalBadge.id = `signal-${socketId}`;
            signalBadge.className = "text-[9px] font-bold px-2 py-0.5 rounded-full bg-slate-900/60 border border-slate-700/50 flex items-center gap-1 text-emerald-400";
            signalBadge.innerHTML = '<i class="fa-solid fa-wifi text-[8px]"></i> <span>Stabil</span>';
            badgeContainer.appendChild(signalBadge);

            const footer = document.createElement('div');
            footer.className = "flex justify-between items-center w-full mt-auto pointer-events-auto";

            const nameLabel = document.createElement('span');
            nameLabel.className = "text-xs font-bold text-slate-200";
            nameLabel.innerText = pName;

            const micIcon = document.createElement('span');
            micIcon.id = `mic-icon-${socketId}`;
            const isMutedInit = participant.isAudioMuted || participant.isMuted;
            micIcon.className = isMutedInit
                ? "text-[10px] text-rose-400 flex items-center gap-1 bg-rose-950/70 px-2 py-0.5 rounded-full border border-rose-600/40"
                : "text-[10px] text-emerald-400 flex items-center gap-1 bg-emerald-950/70 px-2 py-0.5 rounded-full border border-emerald-600/40";
            micIcon.innerHTML = isMutedInit
                ? '<i class="fa-solid fa-microphone-slash"></i>'
                : '<i class="fa-solid fa-microphone"></i>';

            footer.appendChild(nameLabel);
            footer.appendChild(micIcon);
            overlay.appendChild(badgeContainer);
            overlay.appendChild(footer);

            card.appendChild(video);
            card.appendChild(placeholder);
            card.appendChild(overlay);
            
            // Re-render hand badge if user already raised hand
            if (participant.isHandRaised) {
                const handBadge = document.createElement('div');
                handBadge.id = `hand-badge-${socketId}`;
                handBadge.className = "absolute top-4 right-4 text-4xl animate-bounce z-10 drop-shadow-2xl";
                handBadge.innerText = "✋";
                card.appendChild(handBadge);
            }

            container.appendChild(card);
            updateHostBadges();
            updatePeerPlaceholder(socketId);
            updatePeerAudioIndicator(socketId);
        }

        function removeRemotePeer(socketId) {
            console.log(`[DOM] Removing remote peer: ${socketId}`);
            const el = document.getElementById(`wrapper-${socketId}`);
            if (el) el.remove();
            
            const pc = peerConnections[socketId];
            if (pc) {
                try {
                    pc.close();
                } catch(e) {}
                delete peerConnections[socketId];
            }
            delete retryCounters[socketId];
            delete pendingCandidates[socketId];
            delete remoteStreams[socketId];
            if (reconnectTimers[socketId]) {
                clearTimeout(reconnectTimers[socketId]);
                delete reconnectTimers[socketId];
            }
            participantMap.delete(socketId);
        }

        function updatePeerPlaceholder(socketId) {
            const participant = participantMap.get(socketId) || {};
            const placeholder = document.getElementById(`placeholder-${socketId}`);
            const stream = remoteStreams[socketId];
            
            let isOff = false;
            if (participant.isVideoOff === true) {
                isOff = true;
            } else if (stream) {
                const videoTracks = stream.getVideoTracks();
                if (videoTracks.length === 0 || videoTracks.every(t => !t.enabled || t.muted)) {
                    isOff = true;
                }
            }
            
            if (placeholder) {
                placeholder.classList.toggle('hidden', !isOff);
                placeholder.classList.toggle('flex', isOff);
            }
        }

        function updatePeerAudioIndicator(socketId) {
            const participant = participantMap.get(socketId) || {};
            const micIcon = document.getElementById(`mic-icon-${socketId}`);
            const stream = remoteStreams[socketId];
            
            let isMuted = false;
            if (participant.isAudioMuted === true || participant.isMuted === true) {
                isMuted = true;
            } else if (stream) {
                const audioTracks = stream.getAudioTracks();
                if (audioTracks.length === 0 || audioTracks.every(t => !t.enabled || t.muted)) {
                    isMuted = true;
                }
            }

            if (micIcon) {
                micIcon.className = isMuted
                    ? "text-[10px] text-rose-400 flex items-center gap-1 bg-rose-950/70 px-2 py-0.5 rounded-full border border-rose-600/40"
                    : "text-[10px] text-emerald-400 flex items-center gap-1 bg-emerald-950/70 px-2 py-0.5 rounded-full border border-emerald-600/40";
                micIcon.innerHTML = isMuted
                    ? '<i class="fa-solid fa-microphone-slash"></i>'
                    : '<i class="fa-solid fa-microphone"></i>';
            }
        }

        // Realtime Network Quality Monitor (Stats monitoring every 4s)
        setInterval(async () => {
            for (const [targetSocketId, pc] of Object.entries(peerConnections)) {
                if (!pc || pc.connectionState !== 'connected') continue;
                try {
                    const stats = await pc.getStats();
                    stats.forEach(report => {
                        if (report.type === 'inbound-rtp' && report.kind === 'video') {
                            const packetsLost = report.packetsLost || 0;
                            const packetsReceived = report.packetsReceived || 1;
                            const lossRate = (packetsLost / (packetsLost + packetsReceived)) * 100;
                            
                            const signalBadge = document.getElementById(`signal-${targetSocketId}`);
                            if (signalBadge) {
                                if (lossRate > 12) {
                                    signalBadge.className = "text-[9px] font-extrabold px-2 py-0.5 rounded-full bg-rose-950/80 border border-rose-600/50 flex items-center gap-1 text-rose-300 animate-pulse";
                                    signalBadge.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-[8px]"></i> <span>Sinyal Lemah</span>';
                                } else if (lossRate > 4) {
                                    signalBadge.className = "text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-950/80 border border-amber-600/50 flex items-center gap-1 text-amber-300";
                                    signalBadge.innerHTML = '<i class="fa-solid fa-wifi text-[8px]"></i> <span>Cukup</span>';
                                } else {
                                    signalBadge.className = "text-[9px] font-bold px-2 py-0.5 rounded-full bg-slate-900/60 border border-slate-700/50 flex items-center gap-1 text-emerald-400";
                                    signalBadge.innerHTML = '<i class="fa-solid fa-wifi text-[8px]"></i> <span>Stabil</span>';
                                }
                            }
                        }
                    });
                } catch (e) {}
            }
        }, 4000);

        // =========================================================================
        // SIGNALING & WEBRTC LISTENERS (MATCHING USER FLOW)
        // =========================================================================

        function updateTeacherStatusBanner() {
            const banner = document.getElementById('teacher-waiting-banner');
            if (!banner) return;
            const hasTeacher = Array.from(participantMap.values()).some(p => p.role === 'teacher' || p.role === 'pimpinan');
            if (hasTeacher) {
                banner.classList.add('hidden');
            } else {
                banner.classList.remove('hidden');
            }
        }

        socket.on('room-participants', (participants) => {
            console.log(`[Socket] Received existing room-participants list. Count: ${participants.length}`);
            participants.forEach(p => {
                if (p.socketId === socket.id || (p.userId === userId && p.role === role)) {
                    console.log(`[Socket] Skipping self in room-participants: ${p.userId}`);
                    return;
                }
                removeStaleUserWrapper(p.userId, p.role, p.socketId);
                participantMap.set(p.socketId, p);
                console.log(`[Socket] Cached existing participant: ${p.userId} (${p.userName}). Initiating Offer as newcomer...`);
                createPeerConnection(p.socketId, true);
            });
            updateTeacherStatusBanner();
        });

        socket.on('user-joined', (participant) => {
            console.log(`[Socket] A new user has joined the room: ${participant.userId} (${participant.userName})`);
            if (participant.socketId === socket.id || (participant.userId === userId && participant.role === role)) {
                console.log(`[Socket] Skipping self in user-joined: ${participant.userId}`);
                return;
            }
            // Clean up any stale peer connection associated with this userId
            removeStaleUserWrapper(participant.userId, participant.role, participant.socketId);
            participantMap.set(participant.socketId, participant);
            updateTeacherStatusBanner();
        });

        socket.on('receive-offer', async ({ senderSocketId, sdp }) => {
            console.log(`[Socket] Received WebRTC Offer from sender: ${senderSocketId}`);
            try {
                let pc = peerConnections[senderSocketId];
                if (!pc) {
                    pc = await createPeerConnection(senderSocketId, false);
                }

                // Handle Glare Condition
                if (pc.signalingState === 'have-local-offer') {
                    const mySocketId = socket.id;
                    if (mySocketId > senderSocketId) {
                        console.warn(`[Glare] Glare detected with ${senderSocketId}. Rolling back our local offer.`);
                        await pc.setLocalDescription({ type: 'rollback' });
                    } else {
                        console.warn(`[Glare] Glare detected with ${senderSocketId}. Ignoring their offer, we keep ours.`);
                        return;
                    }
                }

                await pc.setRemoteDescription(new RTCSessionDescription(sdp));
                console.log(`[RTCPeerConnection] Remote description set for sender: ${senderSocketId}`);

                // Flush buffered ICE candidates
                if (pendingCandidates[senderSocketId]) {
                    console.log(`[RTCPeerConnection] Flushing ${pendingCandidates[senderSocketId].length} buffered ICE candidates for ${senderSocketId}`);
                    for (const c of pendingCandidates[senderSocketId]) {
                        await pc.addIceCandidate(new RTCIceCandidate(c));
                    }
                    delete pendingCandidates[senderSocketId];
                }
                
                const answer = await pc.createAnswer();
                await pc.setLocalDescription(answer);
                console.log(`[RTCPeerConnection] Local description set for Answer of sender: ${senderSocketId}`);
                
                socket.emit('send-answer', { targetSocketId: senderSocketId, sdp: answer });
                applySenderBitrates(pc);
            } catch (err) {
                console.error(`[Socket] Error handling receive-offer from ${senderSocketId}:`, err);
            }
        });

        socket.on('receive-answer', async ({ senderSocketId, sdp }) => {
            console.log(`[Socket] Received WebRTC Answer from sender: ${senderSocketId}`);
            try {
                const pc = peerConnections[senderSocketId];
                if (pc) {
                    await pc.setRemoteDescription(new RTCSessionDescription(sdp));
                    console.log(`[RTCPeerConnection] Remote description set for Answer from sender: ${senderSocketId}`);

                    // Flush buffered ICE candidates setelah remote description diset
                    if (pendingCandidates[senderSocketId]) {
                        console.log(`[RTCPeerConnection] Flushing ${pendingCandidates[senderSocketId].length} buffered ICE candidates for ${senderSocketId}`);
                        for (const c of pendingCandidates[senderSocketId]) {
                            await pc.addIceCandidate(new RTCIceCandidate(c));
                        }
                        delete pendingCandidates[senderSocketId];
                    }
                    applySenderBitrates(pc);
                } else {
                    console.warn(`[Socket] No peer connection found for sender: ${senderSocketId} to apply Answer.`);
                }
            } catch (err) {
                console.error(`[Socket] Error handling receive-answer from ${senderSocketId}:`, err);
            }
        });

        socket.on('receive-ice-candidate', async ({ senderSocketId, candidate }) => {
            console.log(`[Socket] Received ICE Candidate from sender: ${senderSocketId}`);
            try {
                const pc = peerConnections[senderSocketId];
                if (pc && pc.remoteDescription && pc.remoteDescription.type) {
                    await pc.addIceCandidate(new RTCIceCandidate(candidate));
                    console.log(`[RTCPeerConnection] Added ICE Candidate from sender: ${senderSocketId}`);
                } else {
                    // Buffer kandidat jika PC belum siap atau remote desc belum diset
                    console.warn(`[Socket] PC not ready for ${senderSocketId}. Buffering ICE candidate.`);
                    if (!pendingCandidates[senderSocketId]) pendingCandidates[senderSocketId] = [];
                    pendingCandidates[senderSocketId].push(candidate);
                }
            } catch (err) {
                console.error(`[Socket] Error adding ICE Candidate from ${senderSocketId}:`, err);
            }
        });

        socket.on('receive-ice-restart-request', async ({ senderSocketId }) => {
            console.log(`[Socket] Received ICE restart request from ${senderSocketId}`);
            const pc = peerConnections[senderSocketId];
            if (pc) {
                try {
                    const offer = await pc.createOffer({ iceRestart: true });
                    await pc.setLocalDescription(offer);
                    socket.emit('send-offer', { targetSocketId: senderSocketId, sdp: offer });
                } catch (e) {
                    console.error("[RTCPeerConnection] Failed to handle received ICE restart request:", e);
                }
            }
        });

        socket.on('user-media-state-changed', ({ socketId, isVideoOff, isAudioMuted }) => {
            console.log(`[Socket] Peer media changed for ${socketId}: videoOff=${isVideoOff}, audioMuted=${isAudioMuted}`);
            const p = participantMap.get(socketId);
            if (p) {
                if (isVideoOff !== undefined) p.isVideoOff = isVideoOff;
                if (isAudioMuted !== undefined) p.isAudioMuted = isAudioMuted;
            }
            updatePeerPlaceholder(socketId);
            updatePeerAudioIndicator(socketId);
        });

        let currentHostSocketId = null;
 
        socket.on('host-changed', ({ hostSocketId }) => {
            console.log(`[Socket] Host changed to: ${hostSocketId}`);
            currentHostSocketId = hostSocketId;
            updateHostBadges();
        });
 
        function updateHostBadges() {
            // Update local badge
            const localBadge = document.getElementById('badge-local');
            if (localBadge) {
                const isLocalHost = (socket.id === currentHostSocketId);
                localBadge.innerText = isLocalHost 
                    ? `{{ __('messages.student_you') }} | ${userClass} | HOST` 
                    : `{{ __('messages.student_you') }} | ${userClass}`;
            }
            
            // Update remote badges
            participantMap.forEach((p, sId) => {
                const remoteBadge = document.getElementById(`badge-${sId}`);
                if (remoteBadge) {
                    const isHost = (sId === currentHostSocketId);
                    if (p.role === 'teacher') {
                        remoteBadge.innerText = isHost ? "{{ __('messages.teacher_host') }}" : "{{ __('messages.teacher') }}";
                    } else if (p.role === 'teacher_screen') {
                        remoteBadge.innerText = "{{ __('messages.teacher_presentation') }}";
                    } else if (p.role === 'student_screen') {
                        remoteBadge.innerText = "{{ __('messages.student_presentation') }}";
                    } else {
                        remoteBadge.innerText = isHost ? `{{ __('messages.student') }} (${p.userClass || ''}) | HOST` : `{{ __('messages.student') }} (${p.userClass || ''})`;
                    }
                }
            });
        }
 
        socket.on('user-left', ({ socketId, role }) => {
            console.log(`[Socket] User ${socketId} left the class. Role: ${role}`);
            removeRemotePeer(socketId);
            if (role === 'teacher') {
                console.warn('Guru telah keluar atau terputus sementara dari kelas.');
            }
            updateTeacherStatusBanner();
        });

        socket.on('room:hand-raised', ({ userId, socketId, isRaised }) => {
            if (participantMap.has(socketId)) {
                participantMap.get(socketId).isHandRaised = isRaised;
            }
            updateHandBadge(socketId, isRaised);
        });

        function updateHandBadge(socketId, isRaised) {
            let containerId = socketId === socket.id ? 'localVideo' : `wrapper-${socketId}`;
            let container = document.getElementById(containerId);
            
            if (containerId === 'localVideo') {
                 container = container.parentElement;
            }
            
            if (!container) return;

            let badgeId = `hand-badge-${socketId}`;
            let badge = document.getElementById(badgeId);

            if (isRaised) {
                if (!badge) {
                    badge = document.createElement('div');
                    badge.id = badgeId;
                    badge.className = "absolute top-4 right-4 text-4xl animate-bounce z-10 drop-shadow-2xl";
                    badge.innerText = "✋";
                    container.appendChild(badge);
                }
            } else {
                if (badge) {
                    badge.remove();
                }
            }
        }

        socket.on('error-msg', (msg) => {
            console.error(`[Socket Error] ${msg}`);
            document.getElementById('not-started-overlay').classList.remove('hidden');
            
            // Matikan kamera/mikrofon lokal agar tidak aktif
            if (windowLocalStream) {
                windowLocalStream.getTracks().forEach(track => track.stop());
                console.log("[init] Stopped local streams due to error-msg.");
            }
            isLeavingIntentionally = true;
        });

        socket.on('meeting-ended', () => {
            console.log("[Socket] Meeting has been ended by teacher.");
            isLeavingIntentionally = true;
            
            const overlay = document.getElementById('ended-overlay');
            overlay.classList.remove('hidden');
            
            // Smooth fade in
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                overlay.classList.add('opacity-100');
            }, 10);
            
            // Matikan stream lokal
            if (windowLocalStream) {
                windowLocalStream.getTracks().forEach(track => track.stop());
            }

            // Putuskan koneksi peer dan socket
            Object.keys(peerConnections).forEach(socketId => {
                removeRemotePeer(socketId);
            });
            socket.disconnect();

            // Redirect otomatis setelah 3 detik
            setTimeout(() => {
                window.location.href = "{{ route('student.video-conferences.index') }}";
            }, 3000);
        });

        // =========================================================================
        // LOGIKA KONTROL ISOLASI GURU (MUTE & DEAFEN MODE - SISI MURID)
        // =========================================================================

        socket.on('student:event:isolated', ({ action }) => {
            if (action === 'isolate') {
                applyIsolation();
            } else if (action === 'restore') {
                removeIsolation();
            }
        });

        function applyIsolation() {
            isSelfMutedByTeacher = true;
            isSelfDeafenedByTeacher = true;

            console.warn("Isolasi diaktifkan oleh Guru. Mematikan microphone dan speaker.");

            // a. FORCE MUTE (Matikan Audio Mic Lokal)
            if (windowLocalStream) {
                windowLocalStream.getAudioTracks().forEach(track => {
                    track.enabled = false;
                    console.log(`[Mute] Track ${track.id} dinonaktifkan.`);
                });
            }

            // b. FORCE DEAFEN (Matikan Speaker/Suara Masuk)
            const videos = document.querySelectorAll('#conference-container video');
            videos.forEach(v => {
                if (v.id !== 'localVideo') {
                    v.muted = true;
                    console.log(`[Deafen] Elemen video ${v.id} dibungkam.`);
                }
            });
            const audios = document.querySelectorAll('#conference-container audio');
            audios.forEach(a => {
                a.muted = true;
                console.log(`[Deafen] Elemen audio ${a.id} dibungkam.`);
            });

            // Lapis 2: Matikan track audio dari RTCRtpReceiver di setiap peer connection
            Object.keys(peerConnections).forEach(socketId => {
                const pc = peerConnections[socketId];
                pc.getReceivers().forEach(receiver => {
                    if (receiver.track && receiver.track.kind === 'audio') {
                        receiver.track.enabled = false;
                        console.log(`[Deafen] Audio receiver untuk peer ${socketId} dinonaktifkan.`);
                    }
                });
            });

            // c. INTERFACE LOCK & SHOW OVERLAY
            lockControls();
        }

        function removeIsolation() {
            isSelfMutedByTeacher = false;
            isSelfDeafenedByTeacher = false;

            console.log("Isolasi dinonaktifkan oleh Guru. Mengembalikan microphone dan speaker.");

            // a. RESTORE MIC
            if (windowLocalStream && localMicEnabled) {
                windowLocalStream.getAudioTracks().forEach(track => {
                    track.enabled = true;
                    console.log(`[Restore] Track ${track.id} diaktifkan kembali.`);
                });
            }

            // b. RESTORE SPEAKER
            const audios = document.querySelectorAll('#conference-container audio');
            audios.forEach(a => {
                a.muted = false;
                a.play().catch(e => {});
                console.log(`[Restore] Elemen audio ${a.id} suara dikembalikan.`);
            });

            // Lapis 2: Aktifkan track audio receiver
            Object.keys(peerConnections).forEach(socketId => {
                const pc = peerConnections[socketId];
                pc.getReceivers().forEach(receiver => {
                    if (receiver.track && receiver.track.kind === 'audio') {
                        receiver.track.enabled = true;
                        console.log(`[Restore] Audio receiver untuk peer ${socketId} diaktifkan kembali.`);
                    }
                });
            });

            // c. UNLOCK INTERFACE & HIDE OVERLAY
            unlockControls();
        }

        function lockControls() {
            const btnMic = document.getElementById('btn-mic');
            const overlay = document.getElementById('isolation-overlay');

            btnMic.disabled = true;
            btnMic.className = "px-4 py-2.5 bg-slate-800 text-slate-500 rounded-2xl font-medium text-xs flex items-center gap-2 border border-slate-700 cursor-not-allowed";
            btnMic.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg><span>Mic Terkunci</span>';

            overlay.classList.remove('hidden');
        }

        function unlockControls() {
            const btnMic = document.getElementById('btn-mic');
            const overlay = document.getElementById('isolation-overlay');

            btnMic.disabled = false;
            btnMic.innerHTML = localMicEnabled 
                ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg><span>Mute</span>'
                : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="2" y1="2" x2="22" y2="22"></line><path d="M18.89 13.23A7.12 7.12 0 0 0 19 12v-2"></path><path d="M5 10v2a7 7 0 0 0 12 5"></path><path d="M15 9.34V5a3 3 0 0 0-5.68-1.33"></path><path d="M9 9v3a3 3 0 0 0 5.12 2.12"></path><line x1="12" y1="19" x2="12" y2="22"></line></svg><span>Unmute</span>';
            btnMic.className = localMicEnabled 
                ? "px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-purple-500/20 active:scale-95 transition-all"
                : "px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-rose-500/20 active:scale-95 transition-all";

            if (overlay) overlay.classList.add('hidden');
        }

        // Local Controls UI handlers
        document.getElementById('btn-mic').onclick = () => {
            if (isSelfMutedByTeacher) return;
            localMicEnabled = !localMicEnabled;
            if (windowLocalStream) {
                windowLocalStream.getAudioTracks().forEach(track => {
                    track.enabled = localMicEnabled;
                });
            }
            document.getElementById('btn-mic').innerHTML = localMicEnabled 
                ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg><span>Mute</span>'
                : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="2" y1="2" x2="22" y2="22"></line><path d="M18.89 13.23A7.12 7.12 0 0 0 19 12v-2"></path><path d="M5 10v2a7 7 0 0 0 12 5"></path><path d="M15 9.34V5a3 3 0 0 0-5.68-1.33"></path><path d="M9 9v3a3 3 0 0 0 5.12 2.12"></path><line x1="12" y1="19" x2="12" y2="22"></line></svg><span>Unmute</span>';
            document.getElementById('btn-mic').className = localMicEnabled 
                ? "px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-purple-500/20 active:scale-95 transition-all"
                : "px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-rose-500/20 active:scale-95 transition-all";
            
            socket.emit('toggle-media-state', { roomId, isVideoOff: !localVideoEnabled, isAudioMuted: !localMicEnabled });
        };

        document.getElementById('btn-video').onclick = () => {
            localVideoEnabled = !localVideoEnabled;
            if (windowLocalStream) {
                windowLocalStream.getVideoTracks().forEach(track => {
                    track.enabled = localVideoEnabled;
                });
            }
            const localPlaceholder = document.getElementById('localVideoPlaceholder');
            if (localPlaceholder) {
                localPlaceholder.classList.toggle('hidden', localVideoEnabled);
                localPlaceholder.classList.toggle('flex', !localVideoEnabled);
            }
            document.getElementById('btn-video').innerHTML = localVideoEnabled 
                ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg><span>Video Off</span>'
                : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg><span>Video On</span>';
            document.getElementById('btn-video').className = localVideoEnabled
                ? "px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl font-medium text-xs flex items-center gap-2 border border-slate-700 active:scale-95 transition-all"
                : "px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-rose-500/20 active:scale-95 transition-all";
            
            socket.emit('toggle-media-state', { roomId, isVideoOff: !localVideoEnabled, isAudioMuted: !localMicEnabled });
        };

        document.getElementById('btn-screen').onclick = async () => {
            if (!isScreenSharing) {
                try {
                    screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true });
                    
                    // Replace track locally for preview
                    document.getElementById('localVideo').srcObject = screenStream;
                    document.getElementById('localVideo').classList.remove('-scale-x-100');
                    
                    // Create a separate socket connection for the screen share
                    screenSocket = io(socketUrl, { transports: ['websocket'] });
                    
                    screenSocket.on('connect', () => {
                        console.log("[ScreenShare] Connected to signaling server");
                        screenSocket.emit('join-room', { 
                            roomId, 
                            userId: userId + '_screen', 
                            role: 'student_screen', 
                            userName: userName + ' (Presentasi)', 
                            userClass 
                        });
                    });

                    screenSocket.on('room-participants', (participants) => {
                        participants.forEach(p => {
                            createScreenPeerConnection(p.socketId, true);
                        });
                    });

                    screenSocket.on('receive-offer', async ({ senderSocketId, sdp }) => {
                        let pc = screenPcMap[senderSocketId];
                        if (!pc) {
                            pc = await createScreenPeerConnection(senderSocketId, false);
                        }
                        
                        // Handle Glare
                        if (pc.signalingState === 'have-local-offer') {
                            if (screenSocket.id > senderSocketId) {
                                await pc.setLocalDescription({ type: 'rollback' });
                            } else {
                                return;
                            }
                        }

                        await pc.setRemoteDescription(new RTCSessionDescription(sdp));
                        const answer = await pc.createAnswer();
                        await pc.setLocalDescription(answer);
                        screenSocket.emit('send-answer', { targetSocketId: senderSocketId, sdp: answer });
                    });

                    screenSocket.on('receive-answer', async ({ senderSocketId, sdp }) => {
                        const pc = screenPcMap[senderSocketId];
                        if (pc) await pc.setRemoteDescription(new RTCSessionDescription(sdp));
                    });

                    screenSocket.on('receive-ice-candidate', async ({ senderSocketId, candidate }) => {
                        const pc = screenPcMap[senderSocketId];
                        if (pc && pc.remoteDescription && pc.remoteDescription.type) {
                            await pc.addIceCandidate(new RTCIceCandidate(candidate));
                        }
                    });

                    async function createScreenPeerConnection(targetSocketId, isOfferCreator) {
                        const pc = new RTCPeerConnection(rtcConfig);
                        screenPcMap[targetSocketId] = pc;

                        screenStream.getTracks().forEach(track => {
                            pc.addTrack(track, screenStream);
                        });

                        pc.onicecandidate = (event) => {
                            if (event.candidate) {
                                screenSocket.emit('send-ice-candidate', { targetSocketId, candidate: event.candidate });
                            }
                        };
                        
                        pc.onconnectionstatechange = () => {
                            if (pc.connectionState === 'disconnected' || pc.connectionState === 'failed' || pc.connectionState === 'closed') {
                                if (screenPcMap[targetSocketId]) {
                                    try { pc.close(); } catch(e) {}
                                    delete screenPcMap[targetSocketId];
                                }
                            }
                        };

                        if (isOfferCreator) {
                            const offer = await pc.createOffer();
                            await pc.setLocalDescription(offer);
                            screenSocket.emit('send-offer', { targetSocketId, sdp: offer });
                        }
                        return pc;
                    }

                    isScreenSharing = true;
                    document.getElementById('btn-screen').innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Stop</span>';
                    document.getElementById('btn-screen').className = "px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-rose-500/20 active:scale-95 transition-all";

                    const screenTrack = screenStream.getVideoTracks()[0];
                    screenTrack.onended = () => {
                        stopScreenSharing();
                    };

                } catch (err) {
                    console.error("Error sharing screen:", err);
                }
            } else {
                stopScreenSharing();
            }
        };

        function stopScreenSharing() {
            if (!isScreenSharing) return;
            
            // Revert local video preview to camera and re-add mirror effect
            document.getElementById('localVideo').srcObject = windowLocalStream;
            document.getElementById('localVideo').classList.add('-scale-x-100');
            
            if (screenStream) {
                screenStream.getTracks().forEach(t => t.stop());
            }
            if (screenSocket) {
                screenSocket.disconnect();
            }
            
            for (const socketId in screenPcMap) {
                try { screenPcMap[socketId].close(); } catch(e) {}
            }
            screenPcMap = {};
            
            isScreenSharing = false;
            document.getElementById('btn-screen').innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg><span>Presentasi</span>';
            document.getElementById('btn-screen').className = "px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-cyan-500/20 active:scale-95 transition-all";
        }

        document.getElementById('btn-raise-hand').onclick = () => {
            isHandRaised = !isHandRaised;
            socket.emit('raise-hand', { isRaised: isHandRaised });
            
            document.getElementById('btn-raise-hand').innerHTML = isHandRaised
                ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Turunkan</span>'
                : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0V11m0-5.5v-1a1.5 1.5 0 113 0V11m0-6.5V3.5a1.5 1.5 0 113 0V11M17 11V8.5a1.5 1.5 0 113 0V15a7 7 0 01-14 0v-3"></path></svg><span>Tangan</span>';
            document.getElementById('btn-raise-hand').className = isHandRaised 
                ? "px-4 py-2.5 bg-amber-800 hover:bg-amber-900 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-amber-900/20 active:scale-95 transition-all"
                : "px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-2xl font-medium text-xs flex items-center gap-2 shadow-lg shadow-amber-500/20 active:scale-95 transition-all";

            updateHandBadge(socket.id, isHandRaised);
        };

        // Student Kick-out socket listener
        socket.on('student:event:kicked', () => {
            isLeavingIntentionally = true;
            
            // Show custom kicked modal
            const modal = document.getElementById('kicked-modal');
            const content = document.getElementById('kicked-content');
            if (modal && content) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            // Matikan stream lokal
            if (windowLocalStream) {
                windowLocalStream.getTracks().forEach(track => track.stop());
            }
            socket.disconnect();
        });

        // =========================================================================
        // REALTIME CHAT SYSTEM (ZOOM-LIKE CACHED CHAT)
        // =========================================================================
        function toggleChat() {
            const chatSidebar = document.getElementById('chat-sidebar');
            const chatBtn = document.getElementById('btn-chat');
            if (chatSidebar.classList.contains('hidden')) {
                chatSidebar.classList.remove('hidden');
                chatBtn.classList.replace('bg-slate-800', 'bg-purple-650');
                chatBtn.classList.replace('text-slate-200', 'text-white');
                chatBtn.classList.remove('border-indigo-500', 'animate-pulse'); // remove unread highlights
                
                const chatMsgs = document.getElementById('chat-messages');
                chatMsgs.scrollTop = chatMsgs.scrollHeight;
            } else {
                chatSidebar.classList.add('hidden');
                chatBtn.classList.replace('bg-purple-650', 'bg-slate-800');
                chatBtn.classList.replace('text-white', 'text-slate-200');
            }
        }

        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatMessages = document.getElementById('chat-messages');

        chatForm.onsubmit = (e) => {
            e.preventDefault();
            const text = chatInput.value.trim();
            if (!text) return;
            
            socket.emit('chat-message', { text });
            chatInput.value = '';
        };

        function appendChatMessage(msg) {
            const isMe = msg.senderUserId == userId;
            const bubble = document.createElement('div');
            bubble.className = `flex flex-col max-w-[85%] rounded-2xl p-3 text-xs ${
                isMe 
                    ? 'bg-purple-600 text-white self-end ml-auto rounded-tr-none' 
                    : 'bg-slate-800 text-slate-100 rounded-tl-none border border-slate-700'
            }`;
            
            // Format time
            const timeStr = new Date(msg.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            bubble.innerHTML = `
                <div class="flex items-center justify-between gap-2 mb-1.5 opacity-80 text-[10px] font-bold">
                    <span class="truncate">${isMe ? 'Anda' : msg.senderUserName}</span>
                    <span class="shrink-0 text-[8px] font-medium">${timeStr}</span>
                </div>
                <p class="break-words font-medium leading-relaxed">${escapeHtml(msg.text)}</p>
            `;
            
            chatMessages.appendChild(bubble);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function escapeHtml(text) {
            if (typeof text !== 'string') return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // =========================================================================
        // RESPONSIVE MOBILE CONTROLS FOLDING
        // =========================================================================
        function toggleMoreMenu() {
            const menu = document.getElementById('more-menu');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const btnMore = document.getElementById('btn-more');
            const menu = document.getElementById('more-menu');
            if (btnMore && menu && !btnMore.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        function handleResponsiveControls() {
            const moreMenu = document.getElementById('more-menu');
            const footer = document.querySelector('footer');
            const btnScreen = document.getElementById('btn-screen');
            const btnRaise = document.getElementById('btn-raise-hand');
            const btnChat = document.getElementById('btn-chat');
            const btnMoreWrapper = document.getElementById('btn-more') ? document.getElementById('btn-more').parentNode : null;

            if (!moreMenu || !footer || !btnScreen || !btnRaise || !btnChat || !btnMoreWrapper) return;

            if (window.innerWidth < 640) { // Mobile layout (< sm)
                if (btnScreen.parentNode !== moreMenu) {
                    moreMenu.appendChild(btnScreen);
                    moreMenu.appendChild(btnRaise);
                    btnScreen.classList.replace('w-auto', 'w-full');
                    btnRaise.classList.replace('w-auto', 'w-full');
                }
            } else { // Desktop layout
                if (btnScreen.parentNode !== footer) {
                    footer.insertBefore(btnScreen, btnMoreWrapper);
                    footer.insertBefore(btnRaise, btnMoreWrapper);
                    btnScreen.classList.replace('w-full', 'w-auto');
                    btnRaise.classList.replace('w-full', 'w-auto');
                }
                moreMenu.classList.add('hidden'); // auto-hide
            }
        }

        window.addEventListener('resize', handleResponsiveControls);

        socket.on('chat-history', (history) => {
            chatMessages.innerHTML = '';
            history.forEach(appendChatMessage);
        });

        socket.on('chat-message', (msg) => {
            appendChatMessage(msg);
            
            // Show highlight dot on chat button if closed
            const chatSidebar = document.getElementById('chat-sidebar');
            if (chatSidebar.classList.contains('hidden')) {
                const chatBtn = document.getElementById('btn-chat');
                chatBtn.classList.add('border-indigo-500', 'animate-pulse');
            }
        });

        // Anti-refresh / Cegah tutup tab tidak sengaja
        let isLeavingIntentionally = false;
        window.addEventListener('beforeunload', (event) => {
            if (isLeavingIntentionally) return;
            event.preventDefault();
            event.returnValue = '';
        });

        window.onload = () => {
            init();
            handleResponsiveControls();
        };
    </script>
</body>
</html>
