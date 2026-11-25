@extends('layouts.app')

@section('title', 'Kontrol Server Signaling PM2')

@section('content')
<div class="py-6 sm:py-10 bg-slate-50/50 dark:bg-gray-950/50 min-h-screen" x-data="signalingManager()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Header Card -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-slate-200/80 dark:border-gray-800 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1.5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-purple-150/50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl shadow-xs">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Kontrol Server Signaling (PM2)</h1>
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider border border-amber-200 dark:border-amber-800">
                                👑 Khusus Pimpinan
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                            Kelola status live WebRTC video conference untuk Student Portal (Port 3000) dan Subdomain Pro Sekar (Port 3001).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Global Action Controls -->
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" 
                        @click="refreshStatus()" 
                        :disabled="loading"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-slate-50 dark:hover:bg-gray-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition flex items-center gap-2 shadow-xs disabled:opacity-50">
                    <i class="fa-solid fa-arrows-rotate" :class="{ 'animate-spin': loading }"></i>
                    <span>Cek Status</span>
                </button>
                <button type="button" 
                        @click="executeAction('restart', 'all')" 
                        :disabled="loading"
                        class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm disabled:opacity-50">
                    <i class="fa-solid fa-rotate"></i>
                    <span>Restart Semua</span>
                </button>
                <button type="button" 
                        @click="executeAction('start', 'all')" 
                        :disabled="loading"
                        class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm disabled:opacity-50">
                    <i class="fa-solid fa-play"></i>
                    <span>Start Semua</span>
                </button>
                <button type="button" 
                        @click="executeAction('stop', 'all')" 
                        :disabled="loading"
                        class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm disabled:opacity-50">
                    <i class="fa-solid fa-stop"></i>
                    <span>Stop Semua</span>
                </button>
            </div>
        </div>

        <!-- Alert Notification -->
        <template x-if="message">
            <div class="p-4 rounded-2xl text-xs font-bold flex items-center justify-between transition-all"
                 :class="messageType === 'success' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800'">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid" :class="messageType === 'success' ? 'fa-circle-check' : 'fa-circle-xmark'"></i>
                    <span x-text="message"></span>
                </div>
                <button type="button" @click="message = ''" class="opacity-60 hover:opacity-100 text-sm">&times;</button>
            </div>
        </template>

        @php
            $globalSchoolProfile = \App\Models\ProfilSekolah::first();
            $sayyidahLogoUrl = ($globalSchoolProfile && $globalSchoolProfile->logo_path) 
                ? \Illuminate\Support\Facades\Storage::url($globalSchoolProfile->logo_path) 
                : asset('logos/sayyidah-school-logo.png');
            $prosekarLogoUrl = asset('logos/LogoProsekarWIthText-removebg-preview.png');
        @endphp

        <!-- Signaling Server Grid (2 Cards) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- 1. Student Portal Signaling (Port 3000) -->
            <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-3xl p-6 sm:p-7 border border-slate-200/80 dark:border-gray-800 shadow-sm flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800/80 p-1.5 border border-slate-200/70 dark:border-gray-700/60 flex items-center justify-center shrink-0 shadow-xs">
                                <img src="{{ $sayyidahLogoUrl }}" 
                                     alt="Logo Sayyidah School" 
                                     class="w-full h-full object-contain"
                                     onerror="this.src='/logos/sayyidah-school-logo.png'">
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Student Portal Signaling</h3>
                                <p class="text-[11px] text-slate-500 font-medium">Port 3000 &bull; Video Conference Guru &amp; Siswa (Sayyidah)</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold border"
                              :class="statusData.student?.is_online ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-800'">
                            <span class="w-2 h-2 rounded-full" :class="statusData.student?.is_online ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'"></span>
                            <span x-text="statusData.student?.is_online ? 'ONLINE' : 'STOPPED / OFFLINE'"></span>
                        </span>
                    </div>

                    <!-- Metrics -->
                    <div class="grid grid-cols-3 gap-3 p-3.5 rounded-2xl bg-slate-50 dark:bg-gray-800/60 border border-slate-100 dark:border-gray-800 text-center">
                        <div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">Memory</div>
                            <div class="text-xs font-black text-slate-800 dark:text-slate-200 mt-0.5" x-text="statusData.student?.pm2?.memory || '-'"></div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">CPU</div>
                            <div class="text-xs font-black text-slate-800 dark:text-slate-200 mt-0.5" x-text="statusData.student?.pm2?.cpu || '0%'"></div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">Restarts</div>
                            <div class="text-xs font-black text-slate-800 dark:text-slate-200 mt-0.5" x-text="statusData.student?.pm2?.restarts ?? 0"></div>
                        </div>
                    </div>

                    <!-- Process details -->
                    <div class="mt-4 text-[11px] text-slate-500 dark:text-slate-400 space-y-1">
                        <div class="flex justify-between">
                            <span>Process PM2:</span>
                            <code class="font-mono text-purple-600 dark:text-purple-400 font-bold">signaling-student</code>
                        </div>
                        <div class="flex justify-between">
                            <span>Health Endpoint:</span>
                            <code class="font-mono text-slate-600 dark:text-slate-300">http://127.0.0.1:3000/health</code>
                        </div>
                    </div>
                </div>

                <!-- Individual Buttons -->
                <div class="grid grid-cols-3 gap-2.5 pt-4 border-t border-slate-100 dark:border-gray-800">
                    <button type="button" 
                            @click="executeAction('start', 'student')" 
                            :disabled="loading"
                            class="py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-xs font-bold transition flex items-center justify-center gap-1.5 border border-emerald-200/60 dark:border-emerald-800/40 disabled:opacity-50">
                        <i class="fa-solid fa-play text-[10px]"></i>
                        <span>Start</span>
                    </button>
                    <button type="button" 
                            @click="executeAction('restart', 'student')" 
                            :disabled="loading"
                            class="py-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 hover:bg-amber-100 dark:hover:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-xs font-bold transition flex items-center justify-center gap-1.5 border border-amber-200/60 dark:border-amber-800/40 disabled:opacity-50">
                        <i class="fa-solid fa-rotate text-[10px]"></i>
                        <span>Restart</span>
                    </button>
                    <button type="button" 
                            @click="executeAction('stop', 'student')" 
                            :disabled="loading"
                            class="py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-rose-700 dark:text-rose-300 text-xs font-bold transition flex items-center justify-center gap-1.5 border border-rose-200/60 dark:border-rose-800/40 disabled:opacity-50">
                        <i class="fa-solid fa-stop text-[10px]"></i>
                        <span>Stop</span>
                    </button>
                </div>
            </div>

            <!-- 2. Subdomain Pro Sekar Signaling (Port 3001) -->
            <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl rounded-3xl p-6 sm:p-7 border border-slate-200/80 dark:border-gray-800 shadow-sm flex flex-col justify-between space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50/60 dark:bg-purple-950/40 p-1.5 border border-purple-200/70 dark:border-purple-900/50 flex items-center justify-center shrink-0 shadow-xs">
                                <img src="{{ $prosekarLogoUrl }}" 
                                     alt="Logo Pro Sekar" 
                                     class="w-full h-full object-contain"
                                     onerror="this.src='/storage/logos/LogoProsekarWIthText-removebg-preview.png'">
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Subdomain Pro Sekar Signaling</h3>
                                <p class="text-[11px] text-slate-500 font-medium">Port 3001 &bull; WebRTC Konseling Psikolog &amp; Klien</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold border"
                              :class="statusData.prosekar?.is_online ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-800'">
                            <span class="w-2 h-2 rounded-full" :class="statusData.prosekar?.is_online ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'"></span>
                            <span x-text="statusData.prosekar?.is_online ? 'ONLINE' : 'STOPPED / OFFLINE'"></span>
                        </span>
                    </div>

                    <!-- Metrics -->
                    <div class="grid grid-cols-3 gap-3 p-3.5 rounded-2xl bg-slate-50 dark:bg-gray-800/60 border border-slate-100 dark:border-gray-800 text-center">
                        <div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">Memory</div>
                            <div class="text-xs font-black text-slate-800 dark:text-slate-200 mt-0.5" x-text="statusData.prosekar?.pm2?.memory || '-'"></div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">CPU</div>
                            <div class="text-xs font-black text-slate-800 dark:text-slate-200 mt-0.5" x-text="statusData.prosekar?.pm2?.cpu || '0%'"></div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">Restarts</div>
                            <div class="text-xs font-black text-slate-800 dark:text-slate-200 mt-0.5" x-text="statusData.prosekar?.pm2?.restarts ?? 0"></div>
                        </div>
                    </div>

                    <!-- Process details -->
                    <div class="mt-4 text-[11px] text-slate-500 dark:text-slate-400 space-y-1">
                        <div class="flex justify-between">
                            <span>Process PM2:</span>
                            <code class="font-mono text-purple-600 dark:text-purple-400 font-bold">signaling-prosekar</code>
                        </div>
                        <div class="flex justify-between">
                            <span>Health Endpoint:</span>
                            <code class="font-mono text-slate-600 dark:text-slate-300">http://127.0.0.1:3001/health</code>
                        </div>
                    </div>
                </div>

                <!-- Individual Buttons -->
                <div class="grid grid-cols-3 gap-2.5 pt-4 border-t border-slate-100 dark:border-gray-800">
                    <button type="button" 
                            @click="executeAction('start', 'prosekar')" 
                            :disabled="loading"
                            class="py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-xs font-bold transition flex items-center justify-center gap-1.5 border border-emerald-200/60 dark:border-emerald-800/40 disabled:opacity-50">
                        <i class="fa-solid fa-play text-[10px]"></i>
                        <span>Start</span>
                    </button>
                    <button type="button" 
                            @click="executeAction('restart', 'prosekar')" 
                            :disabled="loading"
                            class="py-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 hover:bg-amber-100 dark:hover:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-xs font-bold transition flex items-center justify-center gap-1.5 border border-amber-200/60 dark:border-amber-800/40 disabled:opacity-50">
                        <i class="fa-solid fa-rotate text-[10px]"></i>
                        <span>Restart</span>
                    </button>
                    <button type="button" 
                            @click="executeAction('stop', 'prosekar')" 
                            :disabled="loading"
                            class="py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-rose-700 dark:text-rose-300 text-xs font-bold transition flex items-center justify-center gap-1.5 border border-rose-200/60 dark:border-rose-800/40 disabled:opacity-50">
                        <i class="fa-solid fa-stop text-[10px]"></i>
                        <span>Stop</span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Terminal Output Viewer (Collapsible) -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-3xl p-6 border border-slate-200/80 dark:border-gray-800 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                    <i class="fa-solid fa-terminal text-purple-600"></i>
                    <span>Log Output Eksekusi Terakhir</span>
                </h4>
                <button type="button" @click="terminalOutput = ''" class="text-[11px] text-slate-400 hover:text-rose-500 font-bold" x-show="terminalOutput">
                    Bersihkan Log
                </button>
            </div>
            <pre class="bg-slate-950 text-emerald-400 font-mono text-[11px] p-4 rounded-2xl overflow-x-auto max-h-48 custom-scrollbar border border-slate-800 leading-relaxed" 
                 x-text="terminalOutput || 'Belum ada perintah yang dieksekusi. Klik salah satu tombol di atas untuk menjalankan aksi.'"></pre>
        </div>

    </div>
</div>

<script>
function signalingManager() {
    return {
        loading: false,
        message: '',
        messageType: 'success',
        terminalOutput: '',
        statusData: @json($status),

        async refreshStatus() {
            this.loading = true;
            try {
                const res = await fetch('{{ route("signaling-pm2.status") }}');
                const json = await res.json();
                if (json.success) {
                    this.statusData = json.data;
                }
            } catch (err) {
                console.error("Error fetching PM2 status:", err);
            } finally {
                this.loading = false;
            }
        },

        async executeAction(action, target) {
            this.loading = true;
            this.message = '';
            
            try {
                const res = await fetch('{{ route("signaling-pm2.action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ action, target })
                });

                const json = await res.json();
                if (json.success) {
                    this.message = json.message;
                    this.messageType = 'success';
                    this.terminalOutput = json.output || '';
                    this.statusData = json.data || this.statusData;
                } else {
                    this.message = json.message || 'Gagal mengeksekusi aksi.';
                    this.messageType = 'error';
                }
            } catch (err) {
                this.message = 'Terjadi kesalahan jaringan saat mengirim perintah.';
                this.messageType = 'error';
                console.error(err);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection
