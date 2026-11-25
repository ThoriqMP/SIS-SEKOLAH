@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
<div class="py-8 px-4 sm:px-6 lg:px-8" x-data="reportFlow()">
    <!-- Flow Modal -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white dark:bg-gray-800 rounded-[40px] w-full max-w-2xl shadow-2xl overflow-hidden transform transition-all"
             @click.away="resetModal()">
            
            <!-- Step 1: Pilih Siswa -->
            <div id="tutorial-modal-step1" x-show="step === 1" class="p-10">
                <div class="mb-8">
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Pilih Siswa</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Cari dan pilih siswa yang ingin dibuatkan raport.</p>
                </div>

                <div class="relative mb-6">
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Ketik nama atau NIS siswa..." 
                           class="w-full pl-12 pr-6 py-4 bg-gray-50 dark:bg-gray-900 border-2 border-transparent focus:border-blue-500 rounded-2xl focus:ring-4 focus:ring-blue-500/20 dark:text-white font-bold placeholder:text-gray-400 text-base transition-all">
                    <svg class="w-6 h-6 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="max-h-64 overflow-y-auto space-y-2 mb-8 custom-scrollbar">
                    <template x-for="student in filteredStudents" :key="student.id">
                        <button @click="selectStudent(student)" 
                                class="w-full flex items-center justify-between p-4 rounded-2xl hover:bg-blue-50 dark:hover:bg-blue-900/20 border-2 border-transparent hover:border-blue-600 transition-all text-left group">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900/40 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 font-black">
                                    <span x-text="student.nama_siswa.charAt(0)"></span>
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 dark:text-white" x-text="student.nama_siswa"></p>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest" x-text="'NIS: ' + (student.nis || '-') + ' • Kelas: ' + (student.class || '-')"></p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </template>
                    <div x-show="filteredStudents.length === 0" class="py-10 text-center text-gray-400 font-bold">
                        Siswa tidak ditemukan...
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="resetModal()" class="px-8 py-4 text-gray-400 font-black uppercase tracking-widest hover:text-gray-600 transition">Batal</button>
                </div>
            </div>

            <!-- Step 2: Pilih Periode (Semester & Tahun Ajaran) -->
            <div x-show="step === 2" class="p-10">
                <div class="mb-8">
                    <button @click="step = 1" class="flex items-center gap-2 text-blue-600 font-black text-xs uppercase tracking-widest mb-4 hover:gap-3 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali ke Siswa
                    </button>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Pilih Periode Rapor</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-medium" x-text="'Tentukan tahun ajaran dan semester untuk ' + selectedStudent?.nama_siswa"></p>
                </div>

                <div class="space-y-6 mb-8">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Semester</label>
                        <select x-model="selectedSemester" class="w-full pl-6 pr-10 py-4 bg-gray-50 dark:bg-gray-900 border-2 border-transparent focus:border-blue-500 rounded-2xl focus:ring-4 focus:ring-blue-500/20 dark:text-white font-bold text-base transition-all cursor-pointer">
                            <option value="Ganjil">Semester Ganjil</option>
                            <option value="Genap">Semester Genap</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Tahun Ajaran</label>
                        <select x-model="selectedAcademicYear" class="w-full pl-6 pr-10 py-4 bg-gray-50 dark:bg-gray-900 border-2 border-transparent focus:border-blue-500 rounded-2xl focus:ring-4 focus:ring-blue-500/20 dark:text-white font-bold text-base transition-all cursor-pointer">
                            <template x-for="year in getAcademicYears()" :key="year">
                                <option :value="year" x-text="year" :selected="year === selectedAcademicYear"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="resetModal()" class="px-8 py-4 text-gray-400 font-black uppercase tracking-widest hover:text-gray-600 transition">Batal</button>
                    <button @click="step = 3" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 transition-all active:scale-95">Lanjut</button>
                </div>
            </div>

            <!-- Step 3: Pilih Kategori -->
            <div id="tutorial-modal-step2" x-show="step === 3" class="p-10">
                <div class="mb-8">
                    <button @click="step = hasPeriodPreset ? 1 : 2" class="flex items-center gap-2 text-blue-600 font-black text-xs uppercase tracking-widest mb-4 hover:gap-3 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali
                    </button>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Pilih Kategori</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-medium" x-text="'Membuat raport untuk ' + selectedStudent?.nama_siswa"></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    @foreach($categories as $category)
                        <button @click="selectCategory('{{ $category->id }}', '{{ $category->name }}')" 
                                class="flex items-center gap-4 p-6 bg-gray-50 dark:bg-gray-900 border-2 border-transparent hover:border-blue-600 rounded-[24px] transition-all group text-left">
                            <div class="h-12 w-12 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <p class="font-black text-gray-900 dark:text-white">{{ $category->name }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $category->subjects_count }} Subjek</p>
                            </div>
                        </button>
                    @endforeach
                </div>

                <!-- Info Section for Tutorial/Guidance -->
                <div id="tutorial-cheatsheet" class="bg-blue-50/70 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/50 p-6 rounded-[24px] mb-6">
                    <h4 class="text-xs font-black text-blue-800 dark:text-blue-300 flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Panduan Ringkas Pengisian Raport
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[11px] font-medium text-gray-600 dark:text-gray-400">
                        <div>
                            <p class="font-black text-blue-700 dark:text-blue-400 mb-1">📝 Akademik (PTS & PAS)</p>
                            <p class="leading-relaxed">Masukkan nilai mata pelajaran (skor 0-100) serta predikat dan deskripsi perkembangan kognitif siswa.</p>
                        </div>
                        <div>
                            <p class="font-black text-emerald-700 dark:text-emerald-400 mb-1">📖 Diniyah & Tahfidz</p>
                            <p class="leading-relaxed">Catat kemajuan hafalan (tahfidz), adab harian keagamaan, serta pemahaman hadits dan doa.</p>
                        </div>
                        <div>
                            <p class="font-black text-indigo-700 dark:text-indigo-400 mb-1">🕌 Ibadah & Adab</p>
                            <p class="leading-relaxed">Nilai kedisiplinan beribadah (sholat wajib, dhuha, dzikir) menggunakan tingkat capaian berkala.</p>
                        </div>
                        <div>
                            <p class="font-black text-purple-700 dark:text-purple-400 mb-1">🚀 Probing (Project Based Learning)</p>
                            <p class="leading-relaxed">Tentukan kategori & indikator dinamis. Upload foto dokumentasi project. Centang 'Raport Terakhir' untuk rangkuman otomatis.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button @click="resetModal()" class="px-8 py-4 text-gray-400 font-black uppercase tracking-widest hover:text-gray-600 transition">Batal</button>
                </div>
            </div>

            <!-- Step 4: Konfirmasi Tipe Akademik -->
            <div x-show="step === 4" class="p-10">
                <div class="mb-8 text-center">
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Mau bikin raport apa?</h3>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Pilih jenis raport akademik yang ingin dibuat.</p>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <button @click="confirmAcademicType('PTS')" 
                            class="p-8 bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-600 rounded-[32px] hover:bg-blue-600 hover:text-white transition-all group">
                        <p class="text-2xl font-black">PTS</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest mt-2">Penilaian Tengah Semester</p>
                    </button>
                    <button @click="confirmAcademicType('PAS')" 
                            class="p-8 bg-indigo-50 dark:bg-indigo-900/20 border-2 border-indigo-600 rounded-[32px] hover:bg-indigo-600 hover:text-white transition-all group">
                        <p class="text-2xl font-black">PAS</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest mt-2">Penilaian Akhir Semester</p>
                    </button>
                </div>

                <div class="flex justify-center">
                    <button @click="step = 3" class="px-8 py-4 text-gray-400 font-black uppercase tracking-widest hover:text-gray-600 transition">Kembali Pilih Kategori</button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
            @if($isFolderView)
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white">Manajemen Raport</h2>
                    <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">Pilih Tahun Ajaran dan Semester untuk mengelola raport siswa.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button id="btn-buat-raport" @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Raport Baru
                    </button>
                </div>
            @else
                <div id="tutorial-step-header">
                    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-xs font-black uppercase tracking-widest mb-3 transition-all hover:gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali ke Semua Folder
                    </a>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white">Manajemen Raport</h2>
                    <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">
                        Melihat data untuk periode: <span class="text-blue-600 dark:text-blue-400 font-extrabold">{{ $selectedPeriod }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if(auth()->user()->isPimpinan())
                        @php
                            $draftsCount = \App\Models\StudentReport::where('period', $selectedPeriod)->where('is_draft', true)->count();
                        @endphp
                        @if($draftsCount > 0)
                            <button @click="showBulkFinalizeModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-emerald-600/20 transition-all active:scale-95 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Finalisasi Semua Rapor ({{ $draftsCount }})
                            </button>
                        @else
                            <button disabled class="bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 px-6 py-3 rounded-2xl font-black border border-gray-200 dark:border-gray-700 cursor-not-allowed flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Semua Rapor Sudah Final
                            </button>
                        @endif
                    @endif
                    <button id="btn-tutorial" @click="startTutorial()" class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 px-5 py-3 rounded-2xl font-bold transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Panduan Pengisian
                    </button>
                    <button id="btn-buat-raport" @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Raport Baru
                    </button>
                </div>
            @endif
        </div>

        @if($isFolderView)
            <!-- Folders Grid -->
            @if(count($periods) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($periods as $period)
                        @php
                            $cardBorderColor = 'border-gray-150 dark:border-gray-700';
                            $iconColor = 'text-blue-500';
                            $statusBadge = 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
                            $statusText = 'Belum Ada Rapor';

                            if ($period['total'] > 0) {
                                if ($period['draft'] > 0) {
                                    $cardBorderColor = 'border-amber-200 dark:border-amber-900/40';
                                    $iconColor = 'text-amber-500';
                                    $statusBadge = 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';
                                    $statusText = 'Ada Draft';
                                } else {
                                    $cardBorderColor = 'border-emerald-200 dark:border-emerald-900/40';
                                    $iconColor = 'text-emerald-500';
                                    $statusBadge = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400';
                                    $statusText = 'Semua Final';
                                }
                            }
                        @endphp
                        <a href="{{ route('admin.reports.index', ['period' => $period['name']]) }}" 
                           class="bg-white dark:bg-gray-800 rounded-[32px] border {{ $cardBorderColor }} p-8 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-between h-64 transform hover:-translate-y-1">
                            <div>
                                <div class="flex justify-between items-start mb-6">
                                    <div class="h-16 w-16 bg-gray-50 dark:bg-gray-700/50 rounded-[24px] flex items-center justify-center {{ $iconColor }} group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider {{ $statusBadge }}">
                                        {{ $statusText }}
                                    </span>
                                </div>
                                
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $period['name'] }}
                                    </h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                        {{ $period['name'] === \App\Helpers\ReportHelper::getCurrentPeriod() ? 'Periode Berjalan' : 'Periode Arsip' }}
                                    </p>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700/60 pt-4 flex justify-between text-xs font-bold text-gray-500 dark:text-gray-400">
                                <div>
                                    Total: <span class="text-gray-900 dark:text-white font-black">{{ $period['total'] }}</span>
                                </div>
                                <div class="flex gap-3">
                                    <span class="text-emerald-600 dark:text-emerald-400">Final: {{ $period['final'] }}</span>
                                    <span class="text-amber-600 dark:text-amber-400">Draf: {{ $period['draft'] }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-[40px] border-2 border-dashed border-gray-200 dark:border-gray-700 p-20 text-center">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Belum Ada Periode Rapor</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto font-medium mb-6">Belum ada data raport yang tersimpan untuk periode apapun di sistem.</p>
                    <button @click="showModal = true" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black shadow-lg shadow-blue-600/20 transition-all active:scale-95 mx-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Raport Pertama
                    </button>
                </div>
            @endif
        @else
            <!-- Filters -->
            <div id="tutorial-step-filters" class="bg-white dark:bg-gray-800 p-6 rounded-[32px] border border-gray-100 dark:border-gray-700 shadow-sm mb-10">
                <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <input type="hidden" name="period" value="{{ $selectedPeriod }}">

                    <div class="relative">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Nama Siswa</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama siswa..." 
                                   class="w-full pl-12 pr-4 py-3.5 bg-gray-50 dark:bg-gray-900 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 dark:text-white font-bold placeholder-gray-400">
                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Kategori Raport</label>
                        <select name="category_id" onchange="this.form.submit()" 
                                class="w-full px-4 py-3.5 bg-gray-50 dark:bg-gray-900 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 dark:text-white font-bold appearance-none cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-3">
                        <button type="submit" class="flex-1 bg-gray-900 dark:bg-gray-700 text-white py-3.5 rounded-2xl font-black hover:bg-blue-600 transition-colors active:scale-95">
                            Terapkan Filter
                        </button>
                        @if(request()->anyFilled(['search', 'category_id']))
                            <a href="{{ route('admin.reports.index', ['period' => $selectedPeriod]) }}" class="px-4 py-3.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl font-black hover:bg-red-100 transition-colors" title="Reset Filter">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-8 bg-green-50 dark:bg-green-900/30 border border-green-100 dark:border-green-800 p-6 rounded-[24px] flex items-center gap-4 text-green-700 dark:text-green-400">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Students Grid (Cards) -->
            <div id="tutorial-step-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($students as $student)
                    <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 group overflow-hidden flex flex-col">
                        <div class="p-8 flex-1">
                            <div class="flex justify-between items-start mb-6">
                                <div class="h-16 w-16 bg-blue-50 dark:bg-blue-900/40 rounded-[24px] flex items-center justify-center text-blue-600 dark:text-blue-400 font-black text-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                    {{ substr($student->nama_siswa, 0, 1) }}
                                </div>
                                <div class="text-right">
                                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Raport</span>
                                    <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-black">{{ $student->reports_count }}</span>
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-1">{{ $student->nama_siswa }}</h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">NIS: {{ $student->nis ?? '-' }} • Kelas: {{ $student->class ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('admin.reports.student-list', [$student, 'tahun_ajaran' => request('period')]) }}" class="flex items-center justify-center gap-2 w-full py-4 bg-gray-900 dark:bg-gray-700 text-white rounded-2xl font-black text-sm hover:bg-blue-600 transition-colors active:scale-95 shadow-lg shadow-gray-900/10">
                                Lihat Raport
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-[40px] border-2 border-dashed border-gray-200 dark:border-gray-700 p-20 text-center">
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Siswa Tidak Ditemukan</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto font-medium">Data siswa tidak ditemukan untuk kriteria pencarian ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $students->links() }}
            </div>

            @if(auth()->user()->isPimpinan())
                @php
                    $modalDraftsCount = \App\Models\StudentReport::where('period', $selectedPeriod)->where('is_draft', true)->count();
                @endphp
                <!-- Bulk Finalize Confirmation Modal (Immersive Design) -->
                <div x-show="showBulkFinalizeModal" 
                     class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-gray-950/80 backdrop-blur-xl"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;">
                    
                    <div class="bg-white dark:bg-gray-900 border border-gray-150 dark:border-gray-800 rounded-[40px] w-full max-w-lg shadow-2xl overflow-hidden transform transition-all flex flex-col p-8 md:p-10 relative"
                         @click.away="showBulkFinalizeModal = false">
                        
                        <!-- Warning Badge Decorator -->
                        <div class="mx-auto w-16 h-16 bg-amber-50 dark:bg-amber-950/30 rounded-3xl flex items-center justify-center text-amber-500 dark:text-amber-400 mb-6 border border-amber-100 dark:border-amber-900/30 relative">
                            <span class="absolute inset-0 rounded-3xl bg-amber-400 animate-ping opacity-25"></span>
                            <svg class="w-8 h-8 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>

                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-wide uppercase">Finalkan Semua Rapor</h3>
                            <p class="text-sm font-bold text-purple-600 dark:text-purple-400 uppercase tracking-widest leading-none mb-4">
                                Periode: {{ $selectedPeriod }}
                            </p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-relaxed">
                                Apakah Anda yakin ingin memfinalkan <strong>semua ({{ $modalDraftsCount }}) rapor draf</strong> untuk periode ini? Aksi ini akan memicu hal-hal berikut:
                            </p>
                        </div>

                        <!-- Immersive Checkpoints -->
                        <div class="space-y-4 mb-8 bg-gray-50 dark:bg-gray-950/50 p-6 rounded-[28px] border border-gray-150 dark:border-gray-850">
                            <div class="flex items-start gap-4">
                                <div class="h-8 w-8 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-wider">Rilis ke Student Portal</h4>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed mt-0.5">Seluruh nilai & perkembangan siswa di periode ini akan langsung dipublikasikan dan dapat diakses oleh murid & orang tua.</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="h-8 w-8 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-xl flex items-center justify-center shrink-0">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-gray-800 dark:text-white uppercase tracking-wider">Kunci Perubahan Nilai</h4>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium leading-relaxed mt-0.5">Semua rapor akan dikunci dan <strong>tidak dapat diedit kembali</strong> oleh guru (kecuali dengan persetujuan pimpinan).</p>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Action Buttons -->
                        <form action="{{ route('admin.reports.finalize-all') }}" method="POST" class="flex flex-col sm:flex-row gap-3 justify-end">
                            @csrf
                            <input type="hidden" name="period" value="{{ $selectedPeriod }}">
                            <button type="button" @click="showBulkFinalizeModal = false" 
                                    class="w-full sm:w-auto px-6 py-4 text-center text-xs font-black text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 uppercase tracking-widest transition">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 shadow-lg shadow-emerald-600/10 active:scale-95">
                                Ya, Finalkan & Kunci Semua Rapor
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<script>
function reportFlow() {
    return {
        showModal: false,
        showBulkFinalizeModal: false,
        step: 1,
        searchQuery: '',
        allStudents: @json($allStudents),
        selectedStudent: null,
        selectedCategoryId: null,
        hasPeriodPreset: {{ isset($selectedPeriod) && $selectedPeriod ? 'true' : 'false' }},
        selectedSemester: 'Ganjil',
        selectedAcademicYear: '{{ $academicYears->firstWhere('is_active', true)->name ?? ($academicYears->first()->name ?? '') }}',

        init() {
            @if(isset($selectedPeriod) && $selectedPeriod)
                const periodStr = '{{ $selectedPeriod }}';
                const match = periodStr.match(/Semester\s+(Ganjil|Genap)\s+(\d{4}\/\d{4})/i);
                if (match) {
                    this.selectedSemester = match[1];
                    this.selectedAcademicYear = match[2];
                }
            @endif

            @if(!$isFolderView)
            this.$nextTick(() => {
                const completed = localStorage.getItem('admin_report_tutorial_completed');
                if (!completed) {
                    setTimeout(() => {
                        this.startTutorial();
                    }, 1000);
                }
            });
            @endif
        },

        startTutorial() {
            if (typeof window.driver === 'undefined') return;
            
            const self = this;
            
            const driverObj = window.driver.js.driver({
                showProgress: true,
                steps: [
                    {
                        element: '#tutorial-step-header',
                        popover: {
                            title: 'Panel Manajemen Raport 🎓',
                            description: 'Selamat datang! Di sini Anda dapat mengelola, membuat, mengedit, dan mencetak raport perkembangan siswa.',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        element: '#btn-buat-raport',
                        popover: {
                            title: 'Tombol Buat Raport ➕',
                            description: 'Klik tombol ini untuk mulai membuat raport baru bagi siswa Sayyidah School. Mari kita lihat alurnya!',
                            side: "left",
                            align: 'center'
                        }
                    },
                    {
                        element: '#tutorial-modal-step1',
                        popover: {
                            title: 'Langkah 1: Pilih Siswa 👤',
                            description: 'Di jendela pop-up ini, ketik nama atau NIS siswa pada kolom pencarian, lalu pilih siswa yang ingin dibuatkan raport.',
                            side: "bottom",
                            align: 'center'
                        },
                        onHighlighted: () => {
                            self.showModal = true;
                            self.step = 1;
                        }
                    },
                    {
                        element: '#tutorial-modal-step2',
                        popover: {
                            title: 'Langkah 2: Pilih Kategori Laporan 📋',
                            description: 'Pilih jenis raport yang ingin diisi: Akademik, Diniyah, Ibadah, atau Probing (PBL). Di bawahnya, terdapat rangkuman penjelasan untuk masing-masing raport.',
                            side: "bottom",
                            align: 'center'
                        },
                        onHighlighted: () => {
                            self.showModal = true;
                            self.selectedStudent = { nama_siswa: 'Siswa Contoh' };
                            self.step = 3;
                        }
                    },
                    {
                        element: '#tutorial-cheatsheet',
                        popover: {
                            title: 'Panduan Detil Pengisian Raport 💡',
                            description: 'Cheatsheet ringkas ini menjelaskan detil apa saja yang bisa Anda isi untuk Akademik, Diniyah, Ibadah, dan Probing (PBL) agar pengisian raport tepat dan optimal.',
                            side: "top",
                            align: 'center'
                        }
                    },
                    {
                        element: '#tutorial-step-filters',
                        popover: {
                            title: 'Filter Laporan 🔍',
                            description: 'Kembali ke dashboard utama! Gunakan panel ini untuk menyaring laporan berdasarkan nama siswa atau kategori raport agar pencarian lebih cepat.',
                            side: "bottom",
                            align: 'center'
                        },
                        onHighlighted: () => {
                            self.resetModal();
                        }
                    },
                    {
                        element: '#tutorial-step-grid',
                        popover: {
                            title: 'Daftar Siswa & Laporan 📇',
                            description: 'Di sini ditampilkan seluruh siswa Anda beserta jumlah raport mereka. Klik tombol **Lihat Raport** untuk masuk ke menu pratinjau dan download PDF.',
                            side: "top",
                            align: 'center'
                        }
                    },
                    {
                        element: '#btn-tutorial',
                        popover: {
                            title: 'Selesai! 🎉',
                            description: 'Jika Anda lupa cara kerjanya, klik tombol **Panduan Pengisian** ini kapan saja untuk memutar ulang petunjuk interaktif ini.',
                            side: "bottom",
                            align: 'center'
                        }
                    }
                ],
                onDestroyed: () => {
                    self.resetModal();
                    localStorage.setItem('admin_report_tutorial_completed', 'true');
                }
            });
            
            driverObj.drive();
        },
        
        get filteredStudents() {
            if (this.searchQuery.trim() === '') {
                return this.allStudents;
            }
            const query = this.searchQuery.toLowerCase();
            return this.allStudents.filter(s => 
                s.nama_siswa.toLowerCase().includes(query) || 
                (s.nis && s.nis.toLowerCase().includes(query))
            );
        },

        resetModal() {
            this.showModal = false;
            this.step = 1;
            this.searchQuery = '';
            this.selectedStudent = null;
            this.selectedCategoryId = null;
        },
        
        async fetchStudents() {
            // No longer needed as we use computed filteredStudents
        },
        
        selectStudent(student) {
            this.selectedStudent = student;
            if (this.hasPeriodPreset) {
                this.step = 3;
            } else {
                this.step = 2;
            }
        },
        
        selectCategory(categoryId, categoryName) {
            this.selectedCategoryId = categoryId;
            const periodStr = `Semester ${this.selectedSemester} ${this.selectedAcademicYear}`;
            const periodParam = encodeURIComponent(periodStr);
            if (categoryName.trim().toLowerCase() === 'akademik') {
                this.step = 4;
            } else {
                window.location.href = `{{ route('admin.reports.create') }}?student_id=${this.selectedStudent.id}&category_id=${categoryId}&period=${periodParam}`;
            }
        },

        getAcademicYears() {
            const currentYear = new Date().getFullYear();
            const years = [];
            for (let i = -3; i <= 3; i++) {
                const y = currentYear + i;
                years.push(`${y}/${y + 1}`);
            }
            return years;
        },

        confirmAcademicType(type) {
            const periodStr = `Semester ${this.selectedSemester} ${this.selectedAcademicYear}`;
            const periodParam = encodeURIComponent(periodStr);
            window.location.href = `{{ route('admin.reports.create') }}?student_id=${this.selectedStudent.id}&category_id=${this.selectedCategoryId}&academic_type=${type}&period=${periodParam}`;
        }
    }
}
</script>
@endpush
