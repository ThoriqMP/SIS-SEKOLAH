@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Data Siswa</h2>
                    
                    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                        <form action="{{ route('students.index') }}" method="GET" class="w-full md:w-64 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400 placeholder:text-gray-400" 
                       placeholder="Cari siswa...">
                        </form>

                        <a href="{{ route('students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center whitespace-nowrap">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Siswa
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center">
                        <svg class="h-5 w-5 text-green-400 dark:text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-green-800 dark:text-green-300 text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 flex items-center">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <p class="text-red-800 dark:text-red-300 text-sm font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Status Filter Tabs -->
                <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6 overflow-x-auto">
                    @php
                        $currentStatus = request('status', 'aktif');
                    @endphp
                    <a href="{{ route('students.index', array_merge(request()->query(), ['status' => 'aktif'])) }}" 
                       class="py-2.5 px-4 font-semibold text-sm border-b-2 transition whitespace-nowrap {{ $currentStatus === 'aktif' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Siswa Aktif
                    </a>
                    <a href="{{ route('students.index', array_merge(request()->query(), ['status' => 'lulus'])) }}" 
                       class="py-2.5 px-4 font-semibold text-sm border-b-2 transition whitespace-nowrap {{ $currentStatus === 'lulus' ? 'border-green-600 text-green-600 dark:text-green-400 dark:border-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Lulus (Arsip)
                    </a>
                    <a href="{{ route('students.index', array_merge(request()->query(), ['status' => 'pindah'])) }}" 
                       class="py-2.5 px-4 font-semibold text-sm border-b-2 transition whitespace-nowrap {{ $currentStatus === 'pindah' ? 'border-yellow-600 text-yellow-600 dark:text-yellow-400 dark:border-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Pindah Sekolah (Arsip)
                    </a>
                    <a href="{{ route('students.index', array_merge(request()->query(), ['status' => 'all'])) }}" 
                       class="py-2.5 px-4 font-semibold text-sm border-b-2 transition whitespace-nowrap {{ $currentStatus === 'all' ? 'border-purple-600 text-purple-600 dark:text-purple-400 dark:border-purple-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Semua Siswa
                    </a>
                </div>

                <!-- Grid Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($students as $student)
                        <div class="bg-white dark:bg-gray-800 rounded-[24px] border border-gray-100 dark:border-gray-700 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group relative overflow-hidden">
                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-32 h-32 -mr-16 -mt-16 bg-blue-600 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity rotate-45"></div>

                            <div class="relative z-10 flex flex-col h-full">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-base sm:text-xl font-black text-gray-900 dark:text-white leading-tight mb-1 truncate" title="{{ $student->nama_siswa }}">
                                            {{ $student->nama_siswa }}
                                        </h3>
                                        <div class="flex items-center gap-2 text-sm text-gray-500 font-bold uppercase tracking-wider">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $student->nama_orang_tua }}
                                        </div>
                                        
                                        <!-- Status Badge & Countdown -->
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            @if($student->status === 'lulus')
                                                <span class="px-2 py-0.5 bg-green-100 dark:bg-green-950/40 text-green-800 dark:text-green-300 rounded-lg text-[10px] font-bold uppercase tracking-wide">Lulus</span>
                                            @elseif($student->status === 'pindah')
                                                <span class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-950/40 text-yellow-800 dark:text-yellow-300 rounded-lg text-[10px] font-bold uppercase tracking-wide">Pindah</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-950/40 text-blue-800 dark:text-blue-300 rounded-lg text-[10px] font-bold uppercase tracking-wide">Aktif</span>
                                            @endif

                                            @if($student->status !== 'aktif' && $student->account_expires_at)
                                                <span class="text-[10px] text-red-500 dark:text-red-400 font-bold" title="Penutupan akun login: {{ $student->account_expires_at->format('d M Y') }}">
                                                    Akun dihapus {{ \Carbon\Carbon::parse($student->account_expires_at)->diffForHumans() }}
                                                </span>
                                            @elseif(!$student->user_id)
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">Akun login dinonaktifkan</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="h-12 w-12 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 font-black text-xl">
                                        {{ substr($student->nama_siswa, 0, 1) }}
                                    </div>
                                </div>

                                <div class="space-y-4 mb-8">
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center gap-3">
                                        <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span class="text-sm font-bold text-gray-600 dark:text-gray-300 line-clamp-1">
                                            {{ $student->sekolah ?? 'Sayyidah School' }}
                                        </span>
                                    </div>
                                    @if($student->nis || $student->class)
                                        <div class="flex gap-2">
                                            @if($student->nis)
                                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg text-[10px] font-black uppercase tracking-widest">NIS: {{ $student->nis }}</span>
                                            @endif
                                            @if($student->class)
                                                <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-lg text-[10px] font-black uppercase tracking-widest">Kelas: {{ $student->class }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-auto flex items-center justify-between pt-6 border-t border-gray-50 dark:border-gray-700">
                                    <div class="flex gap-2">
                                        @if($student->telepon_orang_tua)
                                            @php
                                                $cleanWa = preg_replace('/[^0-9]/', '', $student->telepon_orang_tua);
                                                if (str_starts_with($cleanWa, '0')) {
                                                    $cleanWa = '62' . substr($cleanWa, 1);
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode('Halo Bapak/Ibu wali dari ' . $student->nama_siswa . ', kami dari pihak sekolah...') }}" 
                                               target="_blank"
                                               class="p-3 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-xl transition-all active:scale-90" 
                                               title="Hubungi WhatsApp">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('students.edit', $student) }}" 
                                           class="p-3 bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-yellow-500 hover:text-white rounded-xl transition-all active:scale-90" title="Edit Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 00 2-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        @if(auth()->user()->isPimpinan())
                                            <form action="{{ route('students.destroy', $student) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini? Seluruh data akun akan ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-3 bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-red-600 hover:text-white rounded-xl transition-all active:scale-90" title="Hapus Siswa">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('students.show', $student) }}" 
                                       class="flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 dark:hover:bg-blue-600 hover:text-white dark:hover:text-white transition-all active:scale-95 shadow-lg shadow-black/10">
                                        {{ __('messages.contact_detail') }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12 px-4 text-center">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-full p-4 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Belum ada data siswa</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                                Mulai dengan menambahkan data siswa baru untuk keperluan tagihan dan laporan.
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
