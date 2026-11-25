@extends('mbi.layouts.app')

@section('title', 'Detail Tautan Free Assessment')

@section('content')
<div class="py-10 bg-gradient-to-br from-gray-50 via-slate-50 to-purple-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Top Nav -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.free-links.index') }}" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Link Free Asesmen
            </a>

            <!-- Status Indicator -->
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black {{ $link->is_active ? 'bg-emerald-50 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                    <span class="w-2 h-2 rounded-full {{ $link->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                    {{ $link->is_active ? 'TAUTAN AKTIF' : 'NONAKTIF' }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/80 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-bold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @php
            $appUrl = config('app.url');
            $rootHost = parse_url($appUrl, PHP_URL_HOST);
            if (empty($rootHost) || $rootHost === 'localhost' || $rootHost === '127.0.0.1') {
                $currentHost = preg_replace('/^(teacher|student)\./', '', request()->getHost());
                $scheme = request()->getScheme();
                $port = request()->getPort();
                $portStr = ($port && !in_array($port, [80, 443])) ? ':' . $port : '';
                $publicUrl = $scheme . '://' . $currentHost . $portStr . '/free-assessment/' . $link->slug;
            } else {
                $publicUrl = rtrim($appUrl, '/') . '/free-assessment/' . $link->slug;
            }
        @endphp

        <!-- Link Overview Card -->
        <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-1.5">
                    <span class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest block">
                        Detail Tautan Asesmen Publik
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $link->title }}</h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-3xl">
                        {{ $link->description ?: 'Tautan asesmen gratis tanpa kode akses untuk seluruh peserta.' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <form method="POST" action="{{ route('admin.free-links.toggle', $link->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition flex items-center gap-2">
                            <i class="fa-solid {{ $link->is_active ? 'fa-toggle-on text-emerald-500' : 'fa-toggle-off' }}"></i>
                            <span>{{ $link->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.free-links.destroy', $link->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tautan ini beserta seluruh data responden di dalamnya?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900/50 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-xs font-bold transition flex items-center gap-2">
                            <i class="fa-solid fa-trash"></i>
                            <span>Hapus Link</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Public Link Box -->
            <div class="p-4 rounded-2xl bg-purple-50/50 dark:bg-slate-950 border border-purple-200/60 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-xl bg-purple-600/10 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 flex items-center justify-center text-base shrink-0">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <div class="overflow-hidden">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Tautan Pengisian Publik:</span>
                        <a href="{{ $publicUrl }}" target="_blank" class="text-xs font-mono font-bold text-purple-600 dark:text-purple-300 hover:underline truncate block">
                            {{ $publicUrl }}
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $publicUrl }}'); alert('Tautan berhasil disalin!');"
                            class="px-4 py-2 rounded-xl bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-copy text-purple-500"></i> Salin URL
                    </button>
                    <a href="{{ $publicUrl }}" target="_blank"
                       class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Link
                    </a>
                </div>
            </div>

            <!-- Mini Stats Row -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Total Responden</span>
                    <span class="text-2xl font-black text-slate-900 dark:text-white mt-1 block">{{ $totalRespondents }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Selesai (Completed)</span>
                    <span class="text-2xl font-black text-emerald-500 mt-1 block">{{ $completedCount }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Dalam Proses</span>
                    <span class="text-2xl font-black text-amber-500 mt-1 block">{{ $totalRespondents - $completedCount }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800">
                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Tgl Dibuat</span>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 mt-2 block">{{ $link->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Respondents List Card -->
        <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Daftar Responden & Hasil</h2>
                    <p class="text-xs text-slate-400">Seluruh peserta yang telah mengisi formulir dan asesmen melalui tautan ini.</p>
                </div>

                <!-- Search Input -->
                <form method="GET" action="{{ route('admin.free-links.show', $link->id) }}" class="w-full sm:w-80">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, asal sekolah..."
                               class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-500">
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 font-extrabold uppercase border-b border-slate-100 dark:border-slate-800">
                            <th class="pb-3 px-3">Responden</th>
                            <th class="pb-3 px-3">Sekolah & Kontak</th>
                            <th class="pb-3 px-3">Hasil Kepribadian & VAK</th>
                            <th class="pb-3 px-3">Waktu</th>
                            <th class="pb-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
                        @forelse($respondents as $resp)
                            @php
                                $session = $resp->sessions->sortByDesc('id')->first();
                                $result = $session ? $session->result : null;
                                $cleanPhone = preg_replace('/[^0-9]/', '', $resp->no_hp ?? '');
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-4 px-3">
                                    <strong class="text-slate-900 dark:text-white text-sm block">{{ $resp->nama }}</strong>
                                    <span class="text-[11px] text-slate-400">
                                        {{ $resp->tgl_lahir ? $resp->tgl_lahir->format('d/m/Y') . ' (' . $resp->tgl_lahir->age . ' thn)' : '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-3 space-y-1.5">
                                    @if($resp->asal_sekolah)
                                        <div class="text-slate-800 dark:text-slate-200 font-semibold flex items-center gap-1.5">
                                            <i class="fa-solid fa-school text-slate-400 text-[10px]"></i>
                                            <span>{{ $resp->asal_sekolah }}</span>
                                        </div>
                                    @else
                                        <div class="text-slate-400 text-[11px]">-</div>
                                    @endif

                                    @if(!empty($cleanPhone))
                                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo ' . $resp->nama . ', kami dari Sayyidah School terkait hasil asesmen Personal Mapping Anda.') }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 font-bold text-[11px] border border-emerald-200 dark:border-emerald-800/60 transition shadow-sm"
                                           title="Kirim pesan WhatsApp">
                                            <i class="fa-brands fa-whatsapp text-xs"></i>
                                            <span>Hubungi WA</span>
                                        </a>
                                    @endif
                                </td>
                                <td class="py-4 px-3">
                                    @if($result)
                                        <div class="space-y-1">
                                            <span class="inline-block px-2 py-0.5 rounded-md bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 font-bold text-[10px]">
                                                {{ strtoupper($result->role_category) }}
                                            </span>
                                            <span class="inline-block px-2 py-0.5 rounded-md bg-cyan-100 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-300 font-bold text-[10px]">
                                                VAK: {{ strtoupper($result->learning_style) }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-amber-500 font-bold text-[11px]">Belum selesai</span>
                                    @endif
                                </td>
                                <td class="py-4 px-3 text-slate-400 text-[11px]">
                                    {{ $resp->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-4 px-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($session && $result)
                                            <a href="{{ route('admin.free-links.respondent', ['link' => $link->id, 'respondent' => $resp->id]) }}"
                                               class="p-2 rounded-xl bg-purple-50 dark:bg-purple-950/50 hover:bg-purple-100 dark:hover:bg-purple-900/60 text-purple-600 dark:text-purple-300 font-bold text-xs transition" title="Lihat Hasil Lengkap">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('free-assessment.result-pdf', ['slug' => $link->slug, 'sessionToken' => $session->session_token, 'download' => 1]) }}"
                                               class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition" title="Unduh PDF">
                                                <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('admin.free-links.respondent.destroy', ['link' => $link->id, 'respondent' => $resp->id]) }}" onsubmit="return confirm('Hapus data responden {{ $resp->nama }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 font-bold text-xs transition" title="Hapus Responden">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    Belum ada responden yang mengisi melalui tautan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                {{ $respondents->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
