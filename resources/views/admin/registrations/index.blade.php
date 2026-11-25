@extends('layouts.app')

@section('title', 'Pendaftaran Siswa Baru')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-[24px] border border-gray-100 dark:border-gray-750">
            <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">
                
                <!-- Header Title & Metadata -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-gray-950 dark:text-white tracking-tight">Pendaftaran Siswa Baru</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-1">Daftar pengajuan pendaftaran online yang bersifat sementara sebelum diverifikasi oleh admin.</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800/80 rounded-2xl p-4 flex items-center">
                        <svg class="h-5 w-5 text-green-500 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-green-800 dark:text-green-300 text-xs font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/80 rounded-2xl p-4 flex items-center">
                        <svg class="h-5 w-5 text-red-500 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-red-800 dark:text-red-300 text-xs font-bold">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Status Filter Tabs -->
                <div class="flex border-b border-gray-200 dark:border-gray-700 mb-8 overflow-x-auto gap-2">
                    <a href="{{ route('admin.registrations.index', ['status' => 'all']) }}" 
                       class="pb-3 px-4 text-xs font-black uppercase tracking-wider border-b-2 transition whitespace-nowrap flex items-center gap-2 {{ $status === 'all' ? 'border-purple-600 text-purple-600 dark:text-purple-400 dark:border-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Semua
                        <span class="px-2 py-0.5 text-[10px] bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-md font-bold">{{ $counts['all'] }}</span>
                    </a>
                    <a href="{{ route('admin.registrations.index', ['status' => 'pending']) }}" 
                       class="pb-3 px-4 text-xs font-black uppercase tracking-wider border-b-2 transition whitespace-nowrap flex items-center gap-2 {{ $status === 'pending' ? 'border-amber-500 text-amber-600 dark:text-amber-400 dark:border-amber-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Menunggu Review
                        <span class="px-2 py-0.5 text-[10px] bg-amber-50 dark:bg-amber-955/35 text-amber-700 dark:text-amber-300 rounded-md font-bold">{{ $counts['pending'] }}</span>
                    </a>
                    <a href="{{ route('admin.registrations.index', ['status' => 'reviewed']) }}" 
                       class="pb-3 px-4 text-xs font-black uppercase tracking-wider border-b-2 transition whitespace-nowrap flex items-center gap-2 {{ $status === 'reviewed' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Sedang Direview
                        <span class="px-2 py-0.5 text-[10px] bg-blue-50 dark:bg-blue-955/35 text-blue-700 dark:text-blue-300 rounded-md font-bold">{{ $counts['reviewed'] }}</span>
                    </a>
                    <a href="{{ route('admin.registrations.index', ['status' => 'approved']) }}" 
                       class="pb-3 px-4 text-xs font-black uppercase tracking-wider border-b-2 transition whitespace-nowrap flex items-center gap-2 {{ $status === 'approved' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-400 dark:border-emerald-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Disetujui
                        <span class="px-2 py-0.5 text-[10px] bg-emerald-50 dark:bg-emerald-955/35 text-emerald-700 dark:text-emerald-300 rounded-md font-bold">{{ $counts['approved'] }}</span>
                    </a>
                    <a href="{{ route('admin.registrations.index', ['status' => 'rejected']) }}" 
                       class="pb-3 px-4 text-xs font-black uppercase tracking-wider border-b-2 transition whitespace-nowrap flex items-center gap-2 {{ $status === 'rejected' ? 'border-rose-600 text-rose-600 dark:text-rose-400 dark:border-rose-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Ditolak
                        <span class="px-2 py-0.5 text-[10px] bg-rose-50 dark:bg-rose-955/35 text-rose-700 dark:text-rose-300 rounded-md font-bold">{{ $counts['rejected'] }}</span>
                    </a>
                </div>

                <!-- Main Table -->
                <div class="overflow-x-auto rounded-[16px] border border-gray-250/60 dark:border-gray-700">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                <th class="py-4 px-6">No. Pendaftaran</th>
                                <th class="py-4 px-6">Nama Calon Siswa</th>
                                <th class="py-4 px-6">Kontak</th>
                                <th class="py-4 px-6">Asal Sekolah</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6">Tanggal Masuk</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-150 dark:divide-gray-700 font-medium">
                            @forelse ($registrations as $reg)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                        {{ $reg->nomor_pendaftaran }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-sm text-gray-950 dark:text-white">{{ $reg->nama_lengkap }}</div>
                                        <div class="text-[10px] text-gray-400 mt-0.5">Panggilan: {{ $reg->nama_panggilan ?? '-' }} ({{ $reg->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }})</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col gap-1">
                                            <span class="font-bold text-gray-750 dark:text-gray-300">{{ $reg->email ?? '-' }}</span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] text-gray-400 font-semibold">{{ $reg->no_telpon_wa }}</span>
                                                @if($reg->no_telpon_wa)
                                                    @php
                                                        $waNum = preg_replace('/[^0-9]/', '', $reg->no_telpon_wa);
                                                        if (strpos($waNum, '0') === 0) {
                                                            $waNum = '62' . substr($waNum, 1);
                                                        }
                                                    @endphp
                                                    <a href="https://wa.me/{{ $waNum }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-[9px] font-black rounded-lg transition active:scale-95 duration-150 shadow-sm" title="Hubungi via WhatsApp">
                                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.863-9.73.001-2.597-1.002-5.038-2.825-6.865-1.821-1.826-4.244-2.832-6.839-2.833-5.439 0-9.865 4.372-9.868 9.733-.001 1.761.488 3.481 1.417 4.98l-.995 3.637 3.737-.978z"/>
                                                        </svg>
                                                        WhatsApp
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-gray-600 dark:text-gray-300">
                                        {{ $reg->asal_sekolah ?? '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if ($reg->status === 'pending')
                                            <span class="inline-flex px-2.5 py-1 text-[9px] font-black uppercase tracking-wider bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/50 dark:border-amber-800/50 rounded-lg">Menunggu Review</span>
                                        @elseif ($reg->status === 'reviewed')
                                            <span class="inline-flex px-2.5 py-1 text-[9px] font-black uppercase tracking-wider bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-200/50 dark:border-blue-800/50 rounded-lg">Direview</span>
                                        @elseif ($reg->status === 'approved')
                                            <span class="inline-flex px-2.5 py-1 text-[9px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-800/50 rounded-lg">Disetujui</span>
                                        @elseif ($reg->status === 'rejected')
                                            <span class="inline-flex px-2.5 py-1 text-[9px] font-black uppercase tracking-wider bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/50 dark:border-rose-800/50 rounded-lg">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-gray-500 dark:text-gray-400">
                                        {{ $reg->submitted_at ? $reg->submitted_at->format('d M Y, H:i') : $reg->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.registrations.show', $reg) }}" 
                                               class="inline-flex px-4 py-2 bg-slate-900 hover:bg-purple-700 text-white rounded-xl font-bold uppercase tracking-wider text-[10px] shadow-sm transition active:scale-95 duration-200">
                                                Detail & Proses
                                            </a>
                                            <a href="{{ route('admin.registrations.pdf', $reg) }}" 
                                               target="_blank"
                                               class="p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 text-slate-500 dark:text-slate-300 rounded-xl transition"
                                               title="Unduh / Cetak">
                                                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center py-6 text-slate-400 dark:text-slate-500">
                                            <svg class="h-10 w-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <h4 class="font-bold text-sm text-gray-900 dark:text-gray-100 mb-1">Belum ada pengajuan pendaftaran</h4>
                                            <p class="text-[11px] max-w-sm">Semua pengajuan pendaftaran online yang masuk akan tampil di sini untuk divalidasi.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $registrations->appends(request()->query())->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
