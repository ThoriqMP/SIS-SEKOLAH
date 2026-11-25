@extends('mbi.layouts.app')

@section('title', 'Asesmen Jaringan (LAN/Wi-Fi)')

@section('content')
<div class="py-10 bg-gradient-to-br from-gray-50 via-slate-50 to-purple-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-700 dark:from-purple-950 dark:via-indigo-950 dark:to-blue-900 text-white shadow-xl mb-8 p-6 sm:p-8">
            <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-md border border-white/30 mb-3">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                        Fitur Asesmen Kolektif Wi-Fi / LAN
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Sesi Asesmen Jaringan (LAN/Wi-Fi)</h1>
                    <p class="text-purple-100 text-sm mt-1">Buka 1 link tes asesmen kolektif untuk banyak peserta dengan proteksi Wi-Fi & pemantauan entitas perangkat.</p>
                </div>
                <a href="{{ route('admin.lan-assessment-sessions.create') }}" 
                   class="inline-flex items-center justify-center gap-2 bg-white text-purple-900 hover:bg-purple-50 px-5 py-3 rounded-xl font-bold transition shadow-lg text-sm w-full sm:w-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Sesi Jaringan Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-emerald-50/90 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm backdrop-blur-sm">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter and Search -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl p-4 sm:p-6 mb-8 border border-gray-200/80 dark:border-gray-800 shadow-sm">
            <form action="{{ route('admin.lan-assessment-sessions.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 text-gray-900 dark:text-gray-100 placeholder-gray-400" 
                           placeholder="Cari judul atau kode akses sesi...">
                </div>

                <select name="status" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-sm px-4 py-2.5 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                </select>

                <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                    Filter
                </button>
            </form>
        </div>

        <!-- Sessions List Table -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md rounded-2xl border border-gray-200/80 dark:border-gray-800 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/80 dark:bg-gray-800/80 text-xs uppercase font-bold text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">Judul Sesi</th>
                            <th class="px-6 py-4">Kode Akses</th>
                            <th class="px-6 py-4">Perangkat Terhubung</th>
                            <th class="px-6 py-4">Proteksi Wi-Fi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Dibuat Pada</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-purple-50/50 dark:hover:bg-purple-950/20 transition">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('admin.lan-assessment-sessions.show', $session->uuid) }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition">
                                        {{ $session->title }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 font-mono font-bold text-xs rounded-lg border border-purple-200 dark:border-purple-800">
                                        {{ $session->session_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-purple-600 dark:text-purple-400">{{ $session->participants_count }}</span>
                                    <span class="text-gray-400">/ {{ $session->max_participants }} Devices</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($session->require_same_network)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            Wi-Fi Restricted
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                            Bebas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($session->status === 'active')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-sm">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-400 text-white">
                                            Ditutup
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $session->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.lan-assessment-sessions.show', $session->uuid) }}" 
                                           class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg shadow transition flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            Live Monitor
                                        </a>
                                        <form action="{{ route('admin.lan-assessment-sessions.destroy', $session->uuid) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 rounded-lg transition" title="Hapus Sesi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                                    <p class="font-medium text-base text-gray-700 dark:text-gray-300">Belum Ada Sesi Asesmen Jaringan</p>
                                    <p class="text-xs mt-1">Klik tombol "Buat Sesi Jaringan Baru" di atas untuk membuat sesi tes kolektif Wi-Fi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
                {{ $sessions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
