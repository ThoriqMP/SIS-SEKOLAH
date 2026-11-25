@extends('layouts.app')

@section('title', __('messages.complete_personal_data'))

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-tr from-slate-50 via-slate-50 to-purple-100/30 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 flex items-center justify-center relative overflow-hidden">
    <!-- Decorative ambient glows -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>

    <div class="max-w-2xl w-full space-y-8 clay-card p-6 sm:p-10 border border-white/40 dark:border-gray-800/40 relative z-10">
        
        <!-- Language Switcher Widget -->
        <div class="flex justify-end">
            <div class="flex items-center gap-1 bg-gray-100/80 dark:bg-gray-900/85 p-0.5 rounded-lg text-xs font-bold border border-gray-200 dark:border-gray-800/50 shadow-inner">
                <a href="/lang/id" class="lang-switch-btn px-2.5 py-1 rounded-md transition-all {{ app()->getLocale() === 'id' ? 'bg-purple-650 text-white shadow-sm font-black' : 'text-gray-500 hover:text-gray-800 dark:hover:text-white' }}">ID</a>
                <a href="/lang/en" class="lang-switch-btn px-2.5 py-1 rounded-md transition-all {{ app()->getLocale() === 'en' ? 'bg-purple-650 text-white shadow-sm font-black' : 'text-gray-500 hover:text-gray-800 dark:hover:text-white' }}">EN</a>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-to-br from-purple-500 via-purple-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-500/20 mb-6">
                <svg class="w-8 h-8 animate-pulse text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">{{ __('messages.welcome_parent_portal') }}</h2>
            <p class="mt-3 text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-bold max-w-md mx-auto leading-relaxed">
                {{ __('messages.please_complete_profile_desc') }}
            </p>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-rose-50 dark:bg-rose-950/30 border-l-4 border-rose-500 p-5 rounded-2xl relative z-10 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-black text-rose-800 dark:text-rose-300 uppercase tracking-widest">{{ __('messages.please_check_input') }}</h3>
                        <ul class="mt-2 list-disc pl-5 text-xs font-bold text-rose-700 dark:text-rose-400 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form class="space-y-8 relative z-10" action="{{ route('student.complete-profile.store') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <!-- Section 2: Data Pribadi Siswa -->
            <div class="space-y-5">
                <h3 class="text-[10px] sm:text-xs font-black text-purple-650 dark:text-purple-400 uppercase tracking-[0.2em] border-b border-purple-100 dark:border-purple-900/40 pb-3 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                    {{ __('messages.student_personal_data') }}
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="col-span-full">
                        <label for="nama_siswa" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.student_full_name') }}</label>
                        <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa', $student->nama_siswa) }}" required
                               class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.date_of_birth') }}</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $student->tanggal_lahir ? $student->tanggal_lahir->format('Y-m-d') : '') }}" required
                               class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.gender') }}</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required
                                class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm cursor-pointer">
                            <option value="">{{ __('messages.select_gender') }}</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $student->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                        </select>
                    </div>

                    <div class="col-span-full">
                        <label for="email" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.email_parent_student') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Section 3: Data Orang Tua -->
            <div class="space-y-5">
                <h3 class="text-[10px] sm:text-xs font-black text-purple-650 dark:text-purple-400 uppercase tracking-[0.2em] border-b border-purple-100 dark:border-purple-900/40 pb-3 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-purple-500"></span>
                    {{ __('messages.parent_guardian_data') }}
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.parent_guardian_name') }}</label>
                        <input type="text" name="nama_orang_tua" id="nama_orang_tua" value="{{ old('nama_orang_tua', $student->nama_orang_tua === '-' ? '' : $student->nama_orang_tua) }}" required
                               class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                    </div>

                    <div>
                        <label for="telepon_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.whatsapp_phone_number') }}</label>
                        <input type="tel" name="telepon_orang_tua" id="telepon_orang_tua" value="{{ old('telepon_orang_tua', $student->telepon_orang_tua) }}" required placeholder="{{ __('messages.phone_placeholder') }}"
                               class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                    </div>

                    <div>
                        <label for="jenis_kelamin_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.parent_gender') }}</label>
                        <select name="jenis_kelamin_orang_tua" id="jenis_kelamin_orang_tua" required
                                class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm cursor-pointer">
                            <option value="">{{ __('messages.select_gender') }}</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin_orang_tua', $student->jenis_kelamin_orang_tua) === 'Laki-laki' ? 'selected' : '' }}>{{ __('messages.male') }}</option>
                            <option value="Perempuan" {{ old('jenis_kelamin_orang_tua', $student->jenis_kelamin_orang_tua) === 'Perempuan' ? 'selected' : '' }}>{{ __('messages.female') }}</option>
                        </select>
                    </div>

                    <div>
                        <label for="tanggal_lahir_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.parent_dob') }}</label>
                        <input type="date" name="tanggal_lahir_orang_tua" id="tanggal_lahir_orang_tua" value="{{ old('tanggal_lahir_orang_tua', $student->tanggal_lahir_orang_tua ? $student->tanggal_lahir_orang_tua->format('Y-m-d') : '') }}" required
                               class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm">
                    </div>

                    <div class="col-span-full">
                        <label for="alamat_orang_tua" class="block text-[10px] sm:text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">{{ __('messages.domicile_address') }}</label>
                        <textarea name="alamat_orang_tua" id="alamat_orang_tua" rows="3" required placeholder="{{ __('messages.address_placeholder') }}"
                                  class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-xs sm:text-sm font-semibold p-3.5 transition-all shadow-sm leading-relaxed">{{ old('alamat_orang_tua', $student->alamat_orang_tua === 'Bogor' || $student->alamat_orang_tua === '-' || empty($student->alamat_orang_tua) ? ($student->alamat_tagihan === 'Bogor' || $student->alamat_tagihan === '-' ? '' : $student->alamat_tagihan) : $student->alamat_orang_tua) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" :disabled="loading" 
                        class="w-full flex justify-center py-4 px-6 rounded-2xl shadow-xl text-xs sm:text-sm font-black uppercase tracking-widest text-white bg-purple-650 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 active:scale-95 disabled:opacity-50 shadow-purple-500/20">
                    <span x-show="!loading">{{ __('messages.save_and_enter_dashboard') }}</span>
                    <span x-show="loading" style="display: none;" class="flex items-center gap-2">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('messages.saving_data') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
