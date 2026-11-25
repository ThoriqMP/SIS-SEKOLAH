@extends('mbi.layouts.app')

@section('title', 'Buat Sesi Jaringan Baru')

@section('content')
<div class="py-10 bg-gradient-to-br from-gray-50 via-slate-50 to-purple-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-slate-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.lan-assessment-sessions.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400 mb-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Sesi Jaringan
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">Buat Sesi Asesmen Jaringan Baru</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Konfigurasikan 1 link tes kolektif untuk kelompok/kelas dengan pembatasan IP Wi-Fi & kuota perangkat.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl border border-gray-200/80 dark:border-gray-800 shadow-xl overflow-hidden p-6 sm:p-8">
            <form action="{{ route('admin.lan-assessment-sessions.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Judul Sesi Asesmen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title') }}" 
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 text-gray-900 dark:text-gray-100 placeholder-gray-400"
                           placeholder="Contoh: Tes Personal Mapping Kelas X-A (30 Siswa)" 
                           required>
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Tipe Asesmen <span class="text-red-500">*</span>
                        </label>
                        <select name="assessment_type" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 text-gray-900 dark:text-gray-100">
                            <option value="standard" {{ old('assessment_type') == 'standard' ? 'selected' : '' }}>Personal Mapping Ultimate (SMP/SMA/Umum)</option>
                            <option value="personal_mapping_sd" {{ old('assessment_type') == 'personal_mapping_sd' ? 'selected' : '' }}>Personal Mapping SD (Anak / SD)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Maksimal Kuota Perangkat / Peserta <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="max_participants" 
                                   min="1" 
                                   max="500" 
                                   value="{{ old('max_participants', 30) }}" 
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 text-gray-900 dark:text-gray-100" 
                                   required>
                            <span class="absolute right-4 top-3 text-xs text-gray-400 font-medium">Perangkat / HP</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Jumlah maksimal entitas perangkat yang diizinkan terhubung ke sesi ini.</p>
                    </div>
                </div>

                <!-- Wi-Fi Network Protection Settings Box -->
                <div class="p-5 rounded-2xl bg-purple-50/60 dark:bg-purple-950/30 border border-purple-200/80 dark:border-purple-900/50 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" 
                                   id="require_same_network" 
                                   name="require_same_network" 
                                   value="1" 
                                   class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                   {{ old('require_same_network', 1) ? 'checked' : '' }}>
                            <label for="require_same_network" class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Wajib Terhubung ke Jaringan Wi-Fi / LAN Sekolah Sama
                            </label>
                        </div>
                    </div>

                    <div class="pl-8 space-y-2">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400">IP Jaringan Sekolah yang Diizinkan:</label>
                        <div class="flex gap-2">
                            <input type="text" 
                                   id="allowed_network_ip" 
                                   name="allowed_network_ip" 
                                   value="{{ old('allowed_network_ip', $currentAdminIp) }}" 
                                   class="flex-1 px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-mono text-gray-900 dark:text-gray-100"
                                   placeholder="e.g. 192.168.1.1 atau IP Publik ISP">
                            <button type="button" 
                                    onclick="document.getElementById('allowed_network_ip').value = '{{ $currentAdminIp }}'"
                                    class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 text-gray-800 dark:text-gray-200 text-xs font-semibold rounded-xl transition whitespace-nowrap">
                                Gunakan IP Saya saat ini
                            </button>
                        </div>
                        <p class="text-xs text-gray-500">IP Jaringan Anda saat ini: <strong class="font-mono text-purple-600 dark:text-purple-400">{{ $currentAdminIp }}</strong></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Nama Psikolog / Penanggung Jawab
                        </label>
                        <input type="text" 
                               name="psychologist_name" 
                               value="{{ old('psychologist_name', 'Tim Psikologi Says') }}" 
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 text-gray-900 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Masa Berlaku Sesi (Jam)
                        </label>
                        <select name="expires_in_hours" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 text-gray-900 dark:text-gray-100">
                            <option value="2">2 Jam</option>
                            <option value="4">4 Jam</option>
                            <option value="8" selected>8 Jam (1 Hari Kerja)</option>
                            <option value="24">24 Jam</option>
                            <option value="48">48 Jam</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <a href="{{ route('admin.lan-assessment-sessions.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold rounded-xl shadow-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Buat Sesi Jaringan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
