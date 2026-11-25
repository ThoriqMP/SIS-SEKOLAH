@extends('layouts.app')

@section('title', 'Input Kolektif PBL')

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8" x-data="bulkPblData()">
    
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reports.bulk-pbl.index') }}" class="p-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-150 dark:border-gray-700 shadow-sm text-gray-550 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition active:scale-95 duration-100">
                    &larr; Kembali
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Input Rapor PBL Kolektif</h1>
                    <p class="text-xs text-gray-500 font-medium">Pilih siswa secara acak/kolektif untuk menginput nilai proyek bulanan, potensi, dan foto.</p>
                </div>
            </div>
        </div>

        <!-- Main Form (POST) -->
        <form action="{{ route('admin.reports.bulk-pbl.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Step 1: Config & Template -->
            <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6">
                <div class="border-l-4 border-amber-500 pl-4 mb-4">
                    <h3 class="text-md font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">1. Konfigurasi Proyek Bulanan</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Periode Rapor *</label>
                        <input type="text" name="period" value="{{ $defaultPeriod }}" required
                               class="w-full px-4 py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 backdrop-blur-sm border border-gray-200 dark:border-gray-700 text-sm font-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white dark:focus:bg-gray-900 focus:shadow-md transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Pilih Proyek Semester *</label>
                        <select name="semester_project_id" x-model="selectedProjectId" @change="handleProjectChange($event)" required
                                class="w-full px-4 py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 backdrop-blur-sm border border-gray-200 dark:border-gray-700 text-sm font-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white dark:focus:bg-gray-900 focus:shadow-md transition-all appearance-none cursor-pointer">
                            <option value="">-- Pilih Proyek Semester --</option>
                            @foreach($semesterProjects as $sp)
                                <option value="{{ $sp->id }}" data-kegiatan="{{ json_encode($sp->kegiatan) }}" data-desc="{{ $sp->description }}" data-title="{{ $sp->title }}">
                                    {{ $sp->title }} ({{ $sp->academicYear?->year_name }} {{ $sp->academicYear?->semester }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Tema Proyek *</label>
                        <input type="text" name="tema" required x-model="tema" readonly placeholder="Dipilih dari Proyek Semester"
                               class="w-full px-4 py-3 rounded-2xl bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-black text-gray-550 dark:text-gray-400 focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Lokasi Proyek *</label>
                        <input type="text" name="location" required placeholder="Contoh: Pasar Jambu Dua"
                               class="w-full px-4 py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 backdrop-blur-sm border border-gray-200 dark:border-gray-700 text-sm font-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:bg-white dark:focus:bg-gray-900 focus:shadow-md transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Deskripsi Aktivitas</label>
                    <textarea name="description" rows="2" x-model="description" readonly placeholder="Deskripsi akan terisi otomatis berdasarkan proyek semester yang dipilih..."
                              class="w-full px-4 py-3.5 rounded-2xl bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-550 dark:text-gray-400 focus:outline-none"></textarea>
                </div>
            </div>

            <!-- Step 2: Student Selection & Search Panel -->
            <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6">
                <div class="border-l-4 border-emerald-500 pl-4">
                    <h3 class="text-md font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">2. Pilih Siswa yang Mengikuti Proyek</h3>
                    <p class="text-[10px] text-gray-400 font-semibold mt-0.5 uppercase tracking-wide">Cari dan centang nama-nama siswa di bawah untuk menambahkannya ke tabel nilai.</p>
                </div>

                <div class="space-y-4">
                    <!-- Search Input -->
                    <div class="relative max-w-md">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" x-model="searchQuery" placeholder="Cari nama siswa..." 
                               class="w-full pl-10 pr-4 py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 backdrop-blur-sm border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-gray-900 focus:shadow-md transition-all">
                    </div>

                    <!-- Scrollable Student Checklist Grid -->
                    <div class="border border-gray-100 dark:border-gray-750 rounded-2xl p-4 bg-gray-50/50 dark:bg-gray-900/30 max-h-[220px] overflow-y-auto custom-scrollbar">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <template x-for="st in filteredStudents" :key="st.id">
                                <label class="flex items-center p-3 rounded-2xl bg-white dark:bg-gray-800 border border-gray-150 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-500/50 shadow-sm transition-all cursor-pointer">
                                    <input type="checkbox" :checked="isStudentSelected(st.id)" @change="toggleStudentSelection(st.id)"
                                           class="rounded text-emerald-500 focus:ring-emerald-500 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 w-5 h-5 mr-3 transition-colors">
                                    <div class="flex-1 min-w-0">
                                        <span class="block text-sm font-black text-gray-800 dark:text-gray-200 truncate" x-text="st.nama_siswa"></span>
                                        <span class="block text-[10px] text-gray-500 font-bold" x-text="`${st.class || 'No Class'} - ${st.nis || '-'}`"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Selected Indicator Badge -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 tracking-wider">Total Siswa Terpilih:</span>
                        <span class="inline-block px-3 py-1.5 text-xs font-black bg-emerald-50/80 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-xl" x-text="`${selectedStudents.length} Siswa`"></span>
                    </div>
                </div>
            </div>

            <!-- Step 3: Evaluation Matrix Grid -->
            <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6"
                 x-show="selectedStudents.length > 0">
                <div class="flex justify-between items-center border-l-4 border-purple-550 pl-4 mb-4">
                    <div>
                        <h3 class="text-md font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">3. Matriks Penilaian Siswa</h3>
                        <p class="text-xs text-gray-500 font-bold mt-1 tracking-wide">Isi nilai indikator kegiatan, tipe kepribadian, kecerdasan majemuk, dan kekuatan potensi untuk masing-masing siswa.</p>
                    </div>
                </div>

                <!-- Grid Cards per Student -->
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    <template x-for="stId in selectedStudents" :key="stId">
                        <div x-data="{ expanded: false }" class="bg-gray-50/50 dark:bg-gray-900/30 rounded-3xl p-5 border border-gray-200 dark:border-gray-750 shadow-sm relative transition-all hover:shadow-md" :class="expanded ? 'ring-2 ring-purple-500/20' : ''">
                            <!-- Hidden Input for Form Submit -->
                            <input type="hidden" name="students[]" :value="stId">
                            
                            <!-- Student Header -->
                            <div class="flex items-center justify-between" :class="expanded ? 'mb-5 border-b border-gray-200 dark:border-gray-700 pb-4' : ''">
                                <div class="flex-1 cursor-pointer select-none group" @click="expanded = !expanded">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-black text-gray-800 dark:text-white text-base md:text-lg truncate max-w-[200px]" x-text="getStudentName(stId)" :title="getStudentName(stId)"></h4>
                                        <div class="w-6 h-6 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center shadow-sm group-hover:border-purple-300 transition-colors">
                                            <svg :class="expanded ? 'rotate-180 text-purple-600' : 'text-gray-400'" class="w-3.5 h-3.5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                    <p class="text-xs font-bold text-gray-500 mt-0.5" x-text="getStudentNis(stId)"></p>
                                </div>
                                <button type="button" @click="toggleStudentSelection(stId)" class="w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-500 flex items-center justify-center transition-colors ml-3 shrink-0" title="Hapus Siswa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <!-- Evaluation Sections -->
                            <div x-show="expanded" 
                                 x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="opacity-0 -translate-y-2" 
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="space-y-6">
                                <!-- Indicators Loop -->
                                <template x-for="(cat, catIdx) in kegiatan" :key="catIdx">
                                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 border border-gray-150 dark:border-gray-700 shadow-sm">
                                        <input type="hidden" :name="`kegiatan[${catIdx}][category]`" :value="cat.category">
                                        <h5 class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            <span x-text="cat.category"></span>
                                        </h5>
                                        <div class="space-y-3">
                                            <template x-for="(ind, indIdx) in cat.indicators" :key="indIdx">
                                                <div class="border-b border-gray-100 dark:border-gray-750 pb-3 last:border-0 last:pb-0">
                                                    <input type="hidden" :name="`kegiatan[${catIdx}][indicators][${indIdx}][name]`" :value="ind.name">
                                                    <p class="text-xs font-extrabold text-gray-700 dark:text-gray-300 mb-2.5" x-text="ind.name"></p>
                                                    <div class="flex items-center justify-between gap-1 max-w-[260px]">
                                                        <template x-for="scoreVal in ['-', 1, 2, 3, 4, 5]">
                                                            <button type="button" 
                                                                    @click="scores[stId][catIdx][indIdx] = scoreVal" 
                                                                    :class="scores[stId][catIdx][indIdx] == scoreVal ? 'bg-purple-600 text-white shadow-md ring-2 ring-purple-300 dark:ring-purple-800 scale-110 relative z-10' : 'bg-gray-50 hover:bg-gray-100 dark:bg-gray-900 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700'" 
                                                                    class="w-9 h-9 rounded-full flex items-center justify-center font-black text-xs transition-all duration-200 active:scale-95">
                                                                <span x-text="scoreVal"></span>
                                                            </button>
                                                        </template>
                                                        <input type="hidden" :name="`scores[${stId}][${catIdx}][${indIdx}]`" :value="scores[stId][catIdx][indIdx]">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Personality, Intelligence, Strength Accordions/Grids -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Kepribadian -->
                                    <div class="bg-amber-50/50 dark:bg-amber-950/20 rounded-2xl p-4 border border-amber-100 dark:border-amber-900/50">
                                        <h5 class="text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-wider mb-2.5">Kepribadian</h5>
                                        <div class="space-y-2 text-left">
                                            @foreach(['Sanguinis', 'Koleris', 'Plegmatis', 'Melankolis'] as $type)
                                                <label class="flex items-center text-xs font-bold text-gray-700 dark:text-gray-300 cursor-pointer hover:text-amber-600 group">
                                                    <input type="checkbox" :name="`personality[${stId}][]`" value="{{ $type }}"
                                                           class="rounded text-amber-500 focus:ring-amber-500 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 w-4 h-4 mr-2.5 transition">
                                                    {{ $type }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Kecerdasan -->
                                    <div class="bg-blue-50/50 dark:bg-blue-950/20 rounded-2xl p-4 border border-blue-100 dark:border-blue-900/50">
                                        <h5 class="text-[10px] font-black text-blue-600 dark:text-blue-500 uppercase tracking-wider mb-2.5">Kecerdasan</h5>
                                        <div class="space-y-2 max-h-[140px] overflow-y-auto custom-scrollbar pr-1 text-left">
                                            @foreach($intelligences as $intel)
                                                <label class="flex items-center text-xs font-bold text-gray-700 dark:text-gray-300 cursor-pointer hover:text-blue-600 group">
                                                    <input type="checkbox" :name="`intelligence[${stId}][]`" value="{{ $intel }}"
                                                           class="rounded text-blue-500 focus:ring-blue-500 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 w-4 h-4 mr-2.5 transition">
                                                    {{ $intel }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Potensi Kekuatan -->
                                    <div class="bg-emerald-50/50 dark:bg-emerald-950/20 rounded-2xl p-4 border border-emerald-100 dark:border-emerald-900/50 sm:col-span-2">
                                        <h5 class="text-[10px] font-black text-emerald-600 dark:text-emerald-500 uppercase tracking-wider mb-2.5">Potensi Kekuatan</h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-[160px] overflow-y-auto custom-scrollbar pr-1 text-left">
                                            @foreach($strengths as $strength)
                                                <label class="flex items-center text-xs font-bold text-gray-700 dark:text-gray-300 cursor-pointer hover:text-emerald-600 group">
                                                    <input type="checkbox" :name="`strength[${stId}][]`" value="{{ $strength }}"
                                                           class="rounded text-emerald-500 focus:ring-emerald-500 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 w-4 h-4 mr-2.5 transition">
                                                    {{ $strength }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Catatan Perkembangan -->
                                    <div class="sm:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                        <div class="space-y-1">
                                            <label class="block text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase tracking-widest">Catatan Perkembangan: Berkembang</label>
                                            <textarea :name="`notes_developed[${stId}]`" rows="3" class="w-full text-xs font-bold rounded-2xl border-amber-200/50 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Tuliskan aspek perkembangan ananda yang sangat baik semester ini..."></textarea>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="block text-[10px] font-black text-purple-600 dark:text-purple-500 uppercase tracking-widest">Catatan Perkembangan: Perlu Dikembangkan</label>
                                            <textarea :name="`notes_need_development[${stId}]`" rows="3" class="w-full text-xs font-bold rounded-2xl border-purple-200/50 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Tuliskan aspek perkembangan ananda yang masih perlu distimulasi..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Empty Matrix Fallback -->
            <div class="p-12 text-center bg-white dark:bg-gray-800 rounded-[32px] border border-gray-150 dark:border-gray-700 shadow-md"
                 x-show="selectedStudents.length === 0">
                <div class="max-w-md mx-auto space-y-3">
                    <div class="w-16 h-16 bg-purple-50 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400 rounded-full flex items-center justify-center mx-auto shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h4 class="text-sm font-black text-gray-800 dark:text-gray-200 uppercase tracking-wider">Matriks Penilaian Kosong</h4>
                    <p class="text-xs text-gray-500 font-medium">Silakan centang nama siswa di panel pencarian langkah kedua terlebih dahulu untuk memulai pengisian nilai secara kolektif.</p>
                </div>
            </div>

            <!-- Step 4: Shared Documentation -->
            <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6"
                 x-show="selectedStudents.length > 0">
                <div class="border-l-4 border-amber-500 pl-4 mb-4">
                    <h3 class="text-md font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">4. Dokumentasi Kegiatan & Keterangan Foto</h3>
                    <p class="text-[10px] text-gray-400 font-semibold mt-0.5 uppercase tracking-wide">Unggah dokumentasi foto kegiatan proyek bulanan (maks 3) dan lengkapi deskripsi keterangan/caption di bawahnya.</p>
                </div>

                 <div x-data="{ 
                    slots: [{ id: Date.now(), preview: null, caption: '' }],
                    async handleFile(slotIdx, event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        
                        if (file.type.startsWith('image/')) {
                            try {
                                const compressedFile = await this.compressImage(file);
                                
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(compressedFile);
                                event.target.files = dataTransfer.files;
                                
                                this.slots[slotIdx].preview = URL.createObjectURL(compressedFile);
                            } catch (error) {
                                console.error('Gagal mengkompres gambar:', error);
                                this.slots[slotIdx].preview = URL.createObjectURL(file);
                            }
                        } else {
                            this.slots[slotIdx].preview = URL.createObjectURL(file);
                        }
                    },
                    compressImage(file) {
                        return new Promise((resolve, reject) => {
                            const reader = new FileReader();
                            reader.readAsDataURL(file);
                            reader.onload = event => {
                                const img = new Image();
                                img.src = event.target.result;
                                img.onload = () => {
                                    const canvas = document.createElement('canvas');
                                    const MAX_WIDTH = 1200;
                                    const MAX_HEIGHT = 1200;
                                    let width = img.width;
                                    let height = img.height;

                                    if (width > height) {
                                        if (width > MAX_WIDTH) {
                                            height *= MAX_WIDTH / width;
                                            width = MAX_WIDTH;
                                        }
                                    } else {
                                        if (height > MAX_HEIGHT) {
                                            width *= MAX_HEIGHT / height;
                                            height = MAX_HEIGHT;
                                        }
                                    }
                                    canvas.width = width;
                                    canvas.height = height;
                                    const ctx = canvas.getContext('2d');
                                    ctx.drawImage(img, 0, 0, width, height);
                                    
                                    canvas.toBlob(blob => {
                                        if (!blob) return reject(new Error('Canvas kosong'));
                                        const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                                            type: 'image/jpeg',
                                            lastModified: Date.now()
                                        });
                                        resolve(newFile);
                                    }, 'image/jpeg', 0.7);
                                };
                                img.onerror = error => reject(error);
                            };
                            reader.onerror = error => reject(error);
                        });
                    }
                }">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <template x-for="(slot, slotIdx) in slots" :key="`slot-${slot.id}`">
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-2xl border border-gray-150 dark:border-gray-700 flex flex-col items-center gap-3 relative">
                                <div class="w-full h-32 rounded-xl border border-gray-200 dark:border-gray-600 flex items-center justify-center relative overflow-hidden bg-white dark:bg-gray-800">
                                    <template x-if="slot.preview">
                                        <img :src="slot.preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!slot.preview">
                                        <div class="text-center p-4">
                                            <svg class="w-6 h-6 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            <span class="text-[9px] font-bold text-gray-450 uppercase tracking-wider block">Unggah Foto</span>
                                        </div>
                                    </template>
                                    <input type="file" accept="image/*" name="images[]" @change="handleFile(slotIdx, $event)" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                <input type="text" name="photo_captions[]" x-model="slot.caption" placeholder="Tulis caption foto..." 
                                       class="w-full px-3 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs font-semibold text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                
                                <button type="button" @click="slots.splice(slotIdx, 1)" x-show="slots.length > 1"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center shadow-md hover:bg-red-600 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </template>
                        
                        <div class="flex items-center justify-center" x-show="slots.length < 3">
                            <button type="button" @click="slots.push({ id: Date.now(), preview: null, caption: '' })"
                                    class="w-full h-32 rounded-2xl border-2 border-dashed border-purple-200 dark:border-purple-800/50 bg-purple-50/20 dark:bg-purple-900/10 flex flex-col items-center justify-center text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:border-purple-400 transition-all group">
                                <svg class="w-8 h-8 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-[9px] font-black uppercase tracking-widest">Tambah Foto Slot</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="pt-6 border-t border-gray-150 dark:border-gray-700 flex justify-end gap-3"
                 x-show="selectedStudents.length > 0">
                <a href="{{ route('admin.reports.bulk-pbl.index') }}" 
                   class="px-6 py-3.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-2xl text-xs font-black uppercase tracking-wider transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-8 py-3.5 bg-gradient-to-tr from-purple-600 to-indigo-650 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-lg hover:opacity-95 transition active:scale-95 duration-200">
                    Simpan sebagai Draft Rapor
                </button>
            </div>
        </form>
        
    </div>
</div>
<script>
function bulkPblData() {
    return {
        allStudents: @json($students ?? []),
        searchQuery: '',
        selectedProjectId: '',
        tema: '',
        description: '',
        kegiatan: [],
        selectedStudents: [],
        scores: {},
        personality: {},
        intelligence: {},
        strength: {},
        
        get filteredStudents() {
            if (!this.searchQuery) return this.allStudents;
            return this.allStudents.filter(s => s.nama_siswa.toLowerCase().includes(this.searchQuery.toLowerCase()));
        },
        
        init() {
            // Default: no students selected, let user check them
        },
        
        toggleStudentSelection(studentId) {
            if (this.selectedStudents.includes(studentId)) {
                this.selectedStudents = this.selectedStudents.filter(id => id !== studentId);
            } else {
                this.selectedStudents.push(studentId);
                this.initStudentData(studentId);
            }
        },
        
        isStudentSelected(studentId) {
            return this.selectedStudents.includes(studentId);
        },
        
        initStudentData(studentId) {
            if (!this.scores[studentId]) {
                this.scores[studentId] = {};
                this.kegiatan.forEach((cat, catIdx) => {
                    this.scores[studentId][catIdx] = {};
                    cat.indicators.forEach((ind, indIdx) => {
                        this.scores[studentId][catIdx][indIdx] = 3; // Default score 3
                    });
                });
            }
            if (!this.personality[studentId]) this.personality[studentId] = [];
            if (!this.intelligence[studentId]) this.intelligence[studentId] = [];
            if (!this.strength[studentId]) this.strength[studentId] = [];
        },
        
        handleProjectChange(event) {
            const select = event.target;
            const opt = select.options[select.selectedIndex];
            if (!opt.value) {
                this.tema = '';
                this.description = '';
                this.kegiatan = [];
                return;
            }
            
            this.tema = opt.dataset.title || '';
            this.description = opt.dataset.desc || '';
            
            try {
                const kegiatan = JSON.parse(opt.dataset.kegiatan || '[]');
                this.kegiatan = Array.isArray(kegiatan) ? kegiatan : [];
                
                // Re-initialize scores for currently selected students
                this.selectedStudents.forEach(studentId => {
                    this.scores[studentId] = {};
                    this.kegiatan.forEach((cat, catIdx) => {
                        this.scores[studentId][catIdx] = {};
                        cat.indicators.forEach((ind, indIdx) => {
                            this.scores[studentId][catIdx][indIdx] = 3;
                        });
                    });
                });
            } catch (e) {
                this.kegiatan = [];
            }
        },

        getStudentName(studentId) {
            const st = this.allStudents.find(s => s.id == studentId);
            return st ? st.nama_siswa : '';
        },

        getStudentNis(studentId) {
            const st = this.allStudents.find(s => s.id == studentId);
            return st ? (st.nis || '') : '';
        }
    }
}
</script>
@endsection
