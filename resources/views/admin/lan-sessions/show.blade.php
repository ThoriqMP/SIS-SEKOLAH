@extends('mbi.layouts.app')

@section('title', 'Live Monitor Sesi Jaringan')

@section('content')
@php
    $gateUrl = route('public.lan-assessment.gate', $session->uuid);
    $waMessage = "Yth. Bapak/Ibu/Siswa/i Peserta Sesi Asesmen,\n\nBerikut ini kami sampaikan link dan kode akses pengerjaan Asesmen Jaringan (Wi-Fi Sekolah) Prosekar:\n\n📌 *Sesi Tes*: {$session->title}\n🔗 *Link Sesi*: {$gateUrl}\n🔑 *Kode Akses*: {$session->session_code}\n\nMohon untuk memastikan perangkat Anda (HP/Laptop) sudah terhubung ke Wi-Fi sekolah saat membuka link di atas.\n\nJika ada pertanyaan atau kendala, silakan menghubungi pengawas sesi.\n\nTerima kasih atas perhatian dan kerja samanya. 🙏\n\nSalam hangat,\n*Tim Prosekar*";
    $waEncoded = urlencode($waMessage);
@endphp

<div class="py-10 bg-gradient-to-br from-gray-50 via-slate-50 to-purple-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ copiedWa: false }">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.lan-assessment-sessions.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400 mb-3 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Sesi Jaringan
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 font-mono font-bold text-sm rounded-lg border border-purple-200 dark:border-purple-800">
                            Kode Sesi: {{ $session->session_code }}
                        </span>
                        @if($session->status === 'active')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-sm">Aktif</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-400 text-white">Ditutup</span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">{{ $session->title }}</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dibuat oleh {{ $session->createdBy->name ?? 'Admin' }} • Masa berlaku hingga {{ $session->expires_at ? $session->expires_at->format('d M Y, H:i') : '-' }}</p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" 
                            @click="navigator.clipboard.writeText(@js($waMessage)); copiedWa = true; setTimeout(() => copiedWa = false, 2500)" 
                            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-md flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        <span x-text="copiedWa ? 'Pesan WA Disalin!' : 'Salin Pesan WA Sopan'"></span>
                    </button>
                    <a href="https://api.whatsapp.com/send?text={{ $waEncoded }}" 
                       target="_blank" 
                       class="px-4 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded-xl transition shadow-md flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 2c-5.522 0-9.999 4.477-9.999 10 0 1.996.588 3.854 1.6 5.418l-1.632 5.96 6.136-1.609c1.498.818 3.208 1.231 4.895 1.231 5.523 0 10-4.477 10-10 0-5.523-4.477-10-10-10zm5.727 14.171c-.242.684-1.201 1.25-1.954 1.341-.516.062-1.189.109-3.447-.828-2.889-1.199-4.747-4.135-4.891-4.328-.143-.192-1.168-1.554-1.168-2.964 0-1.41.741-2.104 1.004-2.392.264-.288.577-.36.77-.36.192 0 .385.001.554.009.178.008.418-.067.653.498.242.583.823 2.012.894 2.157.072.144.12.313.024.505-.096.192-.144.312-.288.48-.144.168-.303.376-.433.505-.144.144-.294.301-.126.589.168.288.747 1.234 1.601 1.994 1.1.979 2.025 1.284 2.313 1.428.288.144.456.12.624-.072.168-.192.721-.84.913-1.129.192-.288.384-.24.648-.144.264.096 1.681.793 1.969.937.288.144.48.216.552.336.072.12.072.696-.17 1.38z"/></svg>
                        Kirim WA ↗
                    </a>
                    @if($session->status === 'active')
                        <form action="{{ route('admin.lan-assessment-sessions.close', $session->uuid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menutup sesi ini secara permanen?')">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-xl transition shadow flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                Tutup Sesi
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-emerald-50/90 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm backdrop-blur-sm">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md p-6 rounded-2xl border border-gray-200/80 dark:border-gray-800 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-purple-100 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Perangkat Terhubung</p>
                    <h3 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">
                        <span class="text-purple-600 dark:text-purple-400">{{ $participants->count() }}</span>
                        <span class="text-gray-400 text-base font-normal">/ {{ $session->max_participants }} Devices</span>
                    </h3>
                </div>
            </div>

            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md p-6 rounded-2xl border border-gray-200/80 dark:border-gray-800 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Proteksi Jaringan Wi-Fi</p>
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-gray-100 flex items-center gap-1.5">
                        @if($session->require_same_network)
                            <span class="text-emerald-600 dark:text-emerald-400">Wi-Fi Restricted</span>
                        @else
                            <span class="text-gray-500">Bebas</span>
                        @endif
                    </h3>
                    <p class="text-xs font-mono text-gray-400 mt-0.5">{{ $session->allowed_network_ip ?: 'Semua IP' }}</p>
                </div>
            </div>

            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md p-6 rounded-2xl border border-gray-200/80 dark:border-gray-800 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-blue-100 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Selesai Mengerjakan</p>
                    <h3 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">
                        {{ $participants->where('status', 'completed')->count() }}
                        <span class="text-gray-400 text-base font-normal">Siswa</span>
                    </h3>
                </div>
            </div>
        </div>

        <!-- WhatsApp Preview Box -->
        <div class="p-5 bg-emerald-50/80 dark:bg-emerald-950/30 rounded-2xl border border-emerald-200 dark:border-emerald-800/60 mb-8">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-6 h-6 rounded-md bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">
                    WA
                </div>
                <h3 class="text-sm font-bold text-emerald-900 dark:text-emerald-200">Preview Pesan WhatsApp Sesi Kolektif</h3>
            </div>
            <div class="p-4 bg-white dark:bg-gray-900 rounded-xl border border-emerald-200/60 dark:border-gray-800 text-xs font-mono text-gray-700 dark:text-gray-300 whitespace-pre-wrap select-all shadow-inner">
{{ $waMessage }}
            </div>
        </div>

        <!-- Live Devices Table Card -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl border border-gray-200/80 dark:border-gray-800 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Live Real-Time Monitoring Perangkat Peserta
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Daftar entitas perangkat yang terdaftar dan sedang mengerjakan asesmen pada sesi ini.</p>
                </div>
                <button type="button" onclick="window.location.reload()" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh Data
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/80 dark:bg-gray-800/80 text-xs uppercase font-bold text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Nama Peserta</th>
                            <th class="px-6 py-4">Entitas Perangkat / Fingerprint</th>
                            <th class="px-6 py-4">IP Address</th>
                            <th class="px-6 py-4">Status Progress</th>
                            <th class="px-6 py-4">Waktu Join</th>
                            <th class="px-6 py-4 text-right">Hasil Asesmen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($participants as $index => $participant)
                            <tr class="hover:bg-purple-50/50 dark:hover:bg-purple-950/20 transition">
                                <td class="px-6 py-4 font-bold text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-gray-100">
                                    {{ $participant->respondent_name ?: 'Peserta' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 font-mono text-xs rounded-lg border border-gray-200 dark:border-gray-700">
                                        {{ Str::limit($participant->device_fingerprint, 16) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-900 dark:text-gray-100">
                                    {{ $participant->ip_address }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($participant->status === 'completed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Selesai
                                        </span>
                                    @elseif($participant->status === 'in_progress')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                            Mengerjakan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            Terhubung
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    {{ $participant->joined_at ? $participant->joined_at->format('H:i:s') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($participant->assessment_id)
                                        <a href="{{ route('assessments.show', $participant->assessment_id) }}" 
                                           class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow transition">
                                            Lihat Raport
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="font-medium text-base text-gray-700 dark:text-gray-300">Belum Ada Perangkat Terhubung</p>
                                    <p class="text-xs mt-1">Perangkat peserta yang membuka link sesi ini akan otomatis muncul secara live di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
