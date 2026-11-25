@extends('layouts.app')

@section('title', __('messages.student_profile'))

@section('content')
<div class="py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen">
    <div class="max-w-4xl mx-auto" x-data="{ activeTab: 'siswa' }">
        
        <!-- Header Card -->
        <div class="clay-card p-6 sm:p-8 border border-white/40 dark:border-gray-800/40 shadow-xl mb-8 relative overflow-hidden group">
            <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10">
                <div class="h-20 w-20 bg-gradient-to-br from-purple-500 via-purple-600 to-indigo-700 rounded-[24px] flex items-center justify-center text-white shadow-lg shadow-purple-500/20 text-3xl font-black shrink-0">
                    {{ substr($student->nama_siswa, 0, 1) }}
                </div>
                <div class="text-center sm:text-left">
                    <span class="px-3 py-1 bg-purple-150 text-purple-700 dark:bg-purple-950/40 dark:text-purple-400 rounded-full text-[9px] font-black uppercase tracking-widest border border-purple-200/20 shadow-sm">
                        {{ __('messages.user_info') }}
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-black text-gray-955 dark:text-white tracking-tight mt-2 leading-tight">{{ __('messages.student_profile') }}</h1>
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400 mt-1">{{ __('messages.manage_profile_desc') }}</p>
                </div>
            </div>
            <!-- Decorative Glow bubbles -->
            <div class="absolute -top-20 -right-20 w-60 h-60 bg-purple-500/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-indigo-500/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        </div>

        <!-- Session Status & Errors -->
        @if (session('success'))
            <div class="mb-6 bg-purple-50 dark:bg-purple-950/30 border-l-4 border-purple-500 p-5 rounded-2xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-black text-purple-800 dark:text-purple-300 uppercase tracking-widest">{{ __('messages.success') }}</h3>
                        <p class="mt-1 text-xs font-bold text-purple-700 dark:text-purple-400">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 dark:bg-rose-950/30 border-l-4 border-rose-500 p-5 rounded-2xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-black text-rose-800 dark:text-rose-300 uppercase tracking-widest">{{ __('messages.please_check_input') }}</h3>
                        <ul class="mt-2 list-disc pl-5 text-xs font-bold text-rose-700 dark:text-rose-400 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tab Buttons Navigation Pills (modern iOS style) -->
        <div class="flex flex-wrap p-1.5 bg-white/60 dark:bg-gray-800/40 backdrop-blur-md rounded-2xl border border-white/20 dark:border-gray-700/25 mb-8 gap-1.5 shadow-sm">
            <button @click="activeTab = 'siswa'" 
                    :class="activeTab === 'siswa' ? 'bg-purple-650 text-white shadow-lg shadow-purple-500/20' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/40 dark:hover:bg-gray-800/20'"
                    class="flex-1 min-w-[120px] py-3.5 px-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 active:scale-95">
                {{ __('messages.student_data_tab') }}
            </button>
            <button @click="activeTab = 'orangtua'" 
                    :class="activeTab === 'orangtua' ? 'bg-purple-650 text-white shadow-lg shadow-purple-500/20' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/40 dark:hover:bg-gray-800/20'"
                    class="flex-1 min-w-[120px] py-3.5 px-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 active:scale-95">
                {{ __('messages.parent_data_tab') }}
            </button>
            <button @click="activeTab = 'akun'" 
                    :class="activeTab === 'akun' ? 'bg-purple-650 text-white shadow-lg shadow-purple-500/20' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/40 dark:hover:bg-gray-800/20'"
                    class="flex-1 min-w-[120px] py-3.5 px-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 active:scale-95">
                {{ __('messages.account_security_tab') }}
            </button>
        </div>

        <!-- Main Form Container -->
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-8" x-data="{ loading: false }" @submit="loading = true">
            @csrf
            @method('PUT')

            <!-- TAB 1: DATA SISWA -->
            <div x-show="activeTab === 'siswa'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                <div class="clay-card p-6 sm:p-8 border border-white/40 dark:border-gray-800/40 space-y-6">
                    <h2 class="text-lg sm:text-xl font-black text-gray-955 dark:text-white tracking-tight border-b border-purple-105 dark:border-purple-900/40 pb-3 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                        {{ __('messages.student_personal_data') }}
                    </h2>
                    
                    <!-- Academic Info (Read-Only) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-purple-50/40 dark:bg-purple-950/10 p-5 rounded-2xl border border-purple-100/50 dark:border-purple-900/25 shadow-inner">
                        <div>
                            <span class="block text-[9px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-1.5">{{ __('messages.active_class') }}</span>
                            <div class="text-sm font-black text-purple-955 dark:text-purple-200">
                                {{ $student->class ?? 'Belum Ditentukan' }}
                            </div>
                        </div>
                        <div>
                            <span class="block text-[9px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest mb-1.5">{{ __('messages.student_nis') }}</span>
                            <div class="text-sm font-black text-purple-955 dark:text-purple-200">
                                {{ $student->nis ?? 'Belum Ditentukan' }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="col-span-full">
                            <label for="nama_siswa" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.student_full_name') }}</label>
                            <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa', $student->nama_siswa) }}" required
                                   class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.date_of_birth') }}</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $student->tanggal_lahir ? $student->tanggal_lahir->format('Y-m-d') : '') }}" required
                                   class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.gender') }}</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" required
                                    class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm cursor-pointer">
                                <option value="Laki-laki" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: DATA ORANG TUA -->
            <div x-show="activeTab === 'orangtua'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                <div class="clay-card p-6 sm:p-8 border border-white/40 dark:border-gray-800/40 space-y-6">
                    <h2 class="text-lg sm:text-xl font-black text-gray-955 dark:text-white tracking-tight border-b border-purple-105 dark:border-purple-900/40 pb-3 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                        {{ __('messages.parent_guardian_data') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.parent_guardian_name') }}</label>
                            <input type="text" name="nama_orang_tua" id="nama_orang_tua" value="{{ old('nama_orang_tua', $student->nama_orang_tua) }}" required
                                   class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                        </div>

                        <div>
                            <label for="telepon_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.whatsapp_phone_number') }}</label>
                            <input type="tel" name="telepon_orang_tua" id="telepon_orang_tua" value="{{ old('telepon_orang_tua', $student->telepon_orang_tua) }}" required
                                   class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                        </div>

                        <div>
                            <label for="jenis_kelamin_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.parent_gender') }}</label>
                            <select name="jenis_kelamin_orang_tua" id="jenis_kelamin_orang_tua" required
                                    class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm cursor-pointer">
                                <option value="Laki-laki" {{ old('jenis_kelamin_orang_tua', $student->jenis_kelamin_orang_tua) === 'Laki-laki' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                                <option value="Perempuan" {{ old('jenis_kelamin_orang_tua', $student->jenis_kelamin_orang_tua) === 'Perempuan' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_lahir_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.parent_dob') }}</label>
                            <input type="date" name="tanggal_lahir_orang_tua" id="tanggal_lahir_orang_tua" value="{{ old('tanggal_lahir_orang_tua', $student->tanggal_lahir_orang_tua ? $student->tanggal_lahir_orang_tua->format('Y-m-d') : '') }}" required
                                   class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                        </div>

                        <div class="col-span-full">
                            <label for="alamat_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.domicile_address') }}</label>
                            <textarea name="alamat_orang_tua" id="alamat_orang_tua" rows="3" required
                                      class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm leading-relaxed">{{ old('alamat_orang_tua', $student->alamat_orang_tua) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: DATA AKUN -->
            <div x-show="activeTab === 'akun'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                <!-- Credentials Card -->
                <div class="clay-card p-6 sm:p-8 border border-white/40 dark:border-gray-800/40 space-y-6">
                    <h2 class="text-lg sm:text-xl font-black text-gray-955 dark:text-white tracking-tight border-b border-purple-100 dark:border-purple-900/40 pb-3 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                        {{ __('messages.login_info') }}
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="col-span-full">
                            <label for="email" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.email_parent_student') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                        </div>

                        <div>
                            <label for="password" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.new_password_label') }}</label>
                            <input type="password" name="password" id="password" placeholder="{{ __('messages.new_password_placeholder') }}"
                                   class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.confirm_new_password_label') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="{{ __('messages.confirm_new_password_placeholder') }}"
                                   class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Save Trigger Button -->
            <div class="flex justify-end pt-2">
                <button type="submit" :disabled="loading" 
                        class="w-full sm:w-auto inline-flex justify-center items-center py-4 px-8 rounded-2xl shadow-xl text-xs font-black uppercase tracking-widest text-white bg-purple-650 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 active:scale-95 disabled:opacity-50 shadow-purple-500/20">
                    <span x-show="!loading">{{ __('messages.save_changes') }}</span>
                    <span x-show="loading" style="display: none;" class="flex items-center gap-2">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('messages.saving') }}
                    </span>
                </button>
            </div>
        </form>

        <!-- TAB 3 EXTRA: ACTIVE LOGIN DEVICES -->
        <div x-show="activeTab === 'akun'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="mt-8 space-y-6">
            <div class="clay-card p-6 sm:p-8 border border-white/40 dark:border-gray-800/40 space-y-6">
                <div class="border-b border-purple-100 dark:border-purple-900/40 pb-4">
                    <h2 class="text-lg sm:text-xl font-black text-gray-955 dark:text-white tracking-tight">{{ __('messages.login_devices_history') }}</h2>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">{{ __('messages.login_devices_desc') }}</p>
                </div>

                <div class="divide-y divide-purple-100/50 dark:divide-purple-900/40" x-data="{
                    deleteSession(sessionId, isCurrent) {
                        const confirmMsg = isCurrent ? '{{ __('messages.confirm_kick_current') }}' : '{{ __('messages.confirm_kick_other') }}';
                        if (!confirm(confirmMsg)) return;
                        
                        fetch('{{ route('profile.logout-device') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ session_id: sessionId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (isCurrent) {
                                    window.location.href = '{{ route('login') }}';
                                } else {
                                    const row = document.getElementById('session-row-' + sessionId);
                                    if (row) {
                                        row.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                                        row.style.opacity = '0';
                                        row.style.transform = 'scale(0.95) translateY(-5px)';
                                        setTimeout(() => row.remove(), 400);
                                    }
                                }
                            } else {
                                alert(data.message || '{{ __('messages.fail_kick_device') }}');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('{{ __('messages.network_error') }}');
                        });
                    }
                }">
                    @foreach ($sessions as $session)
                        <div id="session-row-{{ $session->id }}" class="py-5 flex items-center justify-between gap-4 transition-all hover:bg-slate-50/40 dark:hover:bg-slate-900/10 px-2 rounded-2xl">
                            <div class="flex items-center gap-4 min-w-0">
                                <!-- Device Icon Badge -->
                                <div class="h-12 w-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 flex items-center justify-center text-purple-600 dark:text-purple-400 border border-purple-100/30 dark:border-purple-900/30 flex-shrink-0 shadow-inner">
                                    @if ($session->os === 'iOS (iPhone/iPad)' || $session->os === 'Android')
                                        <!-- Mobile Device SVG -->
                                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    @else
                                        <!-- Desktop/PC SVG -->
                                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="overflow-hidden">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-sm font-black text-gray-900 dark:text-white truncate">
                                            {{ $session->browser }} di {{ $session->os }}
                                        </span>
                                        @if ($session->is_current)
                                            <span class="px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-400 animate-pulse border border-purple-200/20">
                                                {{ __('messages.this_device') }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider bg-slate-100 text-slate-500 dark:bg-slate-800/80 dark:text-slate-400">
                                                {{ __('messages.active') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500 mt-1 font-bold">
                                        <span>IP: {{ $session->ip_address }}</span>
                                        <span>&bull;</span>
                                        <span class="truncate">
                                            @if ($session->is_current)
                                                {{ __('messages.currently_active') }}
                                            @else
                                                {{ __('messages.last_active_at') }} {{ $session->last_active->diffForHumans() }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Logout Button next to device -->
                            <button type="button" @click="deleteSession('{{ $session->id }}', {{ $session->is_current ? 'true' : 'false' }})"
                                    class="inline-flex justify-center items-center py-2 px-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 active:scale-95 flex-shrink-0 {{ $session->is_current ? 'text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900/50 hover:bg-amber-50 dark:hover:bg-amber-950/20 shadow-sm' : 'text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50 hover:bg-rose-50 dark:hover:bg-rose-950/20 shadow-sm' }}">
                                {{ $session->is_current ? __('messages.logout') : __('messages.kick_device') }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
