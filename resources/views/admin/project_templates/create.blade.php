@extends('layouts.app')

@section('title', 'Tambah Proyek Bulanan')

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('project-templates.index') }}" class="p-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-150 dark:border-gray-700 shadow-sm text-gray-550 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition active:scale-95 duration-100">
                &larr; Kembali
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Tambah Proyek Bulanan Baru</h1>
                <p class="text-xs text-gray-500 font-medium">Buat proyek bulanan baru untuk dijadikan referensi tema pada rapor probing.</p>
            </div>
        </div>

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/50 text-rose-800 dark:text-rose-455 rounded-2xl text-xs font-semibold space-y-1">
                <p class="font-extrabold mb-1">Mohon koreksi kesalahan berikut:</p>
                <ul class="list-disc pl-5 font-semibold space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-[32px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md"
             x-data="{
                kegiatan: [
                    { id: 1, category: 'Pembekalan', indicators: [{ id: 11, name: 'Menyimak Pembekalan' }, { id: 12, name: 'Kerjasama Tim' }, { id: 13, name: 'Keaktifan' }] },
                    { id: 2, category: 'Realisasi', indicators: [{ id: 21, name: 'Kedisiplinan' }, { id: 22, name: 'Kerjasama' }, { id: 23, name: 'Pengumpulan Bahan Materi' }] },
                    { id: 3, category: 'Persiapan Presentasi', indicators: [{ id: 31, name: 'Kreativitas' }, { id: 32, name: 'Kerjasama' }, { id: 33, name: 'Deadline Pengumpulan' }] },
                    { id: 4, category: 'Presentasi', indicators: [{ id: 41, name: 'Penguasaan Materi' }, { id: 42, name: 'Keaktifan Tanya Jawab' }, { id: 43, name: 'Penyampaian' }] }
                ],
                draggedIndIdx: null,
                draggedCatIdx: null,
                dragStart(e, indIdx, catIdx) {
                    this.draggedIndIdx = indIdx;
                    this.draggedCatIdx = catIdx;
                    e.dataTransfer.effectAllowed = 'move';
                },
                dragEnter(indIdx, catIdx) {
                    if (this.draggedCatIdx === catIdx && this.draggedIndIdx !== indIdx && this.draggedIndIdx !== null) {
                        const list = this.kegiatan[catIdx].indicators;
                        const temp = list[this.draggedIndIdx];
                        list.splice(this.draggedIndIdx, 1);
                        list.splice(indIdx, 0, temp);
                        this.draggedIndIdx = indIdx;
                    }
                },
                dragEnd() {
                    this.draggedIndIdx = null;
                    this.draggedCatIdx = null;
                },
                categoryDraggable: false,
                draggedCatIdxForCard: null,
                dragStartCat(e, catIdx) {
                    this.draggedCatIdxForCard = catIdx;
                    e.dataTransfer.effectAllowed = 'move';
                },
                dragEnterCat(catIdx) {
                    if (this.draggedCatIdxForCard !== null && this.draggedCatIdxForCard !== catIdx) {
                        const temp = this.kegiatan[this.draggedCatIdxForCard];
                        this.kegiatan.splice(this.draggedCatIdxForCard, 1);
                        this.kegiatan.splice(catIdx, 0, temp);
                        this.draggedCatIdxForCard = catIdx;
                    }
                },
                dragEndCat() {
                    this.draggedCatIdxForCard = null;
                    this.categoryDraggable = false;
                }
             }">
            <form action="{{ route('project-templates.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Judul / Tema Proyek *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-3 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-750 text-xs font-semibold text-gray-900 dark:text-white focus:outline-none focus:border-purple-550 transition-colors"
                           placeholder="Contoh: Month Of Literacy / Green Campaign">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">Deskripsi Proyek *</label>
                    <textarea name="description" rows="4" required
                              class="w-full px-4 py-3 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-750 text-xs font-semibold text-gray-900 dark:text-white focus:outline-none focus:border-purple-550 transition-colors"
                              placeholder="Deskripsikan tujuan dan aktivitas proyek secara ringkas...">{{ old('description') }}</textarea>
                </div>

                <!-- Dynamic Kegiatan / Indicators -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Kategori & Indikator Capaian</label>
                        <button type="button" @click="kegiatan.push({ id: Date.now() + Math.random(), category: '', indicators: [{ id: Date.now() + Math.random(), name: '' }] })"
                                class="text-xs font-bold text-purple-600 hover:text-purple-750 bg-purple-50 dark:bg-purple-900/30 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                            + Tambah Kategori
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <template x-for="(cat, catIdx) in kegiatan" :key="cat.id">
                            <div class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-gray-150 dark:border-gray-800 relative space-y-4"
                                 :draggable="categoryDraggable"
                                 @dragstart="dragStartCat($event, catIdx)"
                                 @dragover.prevent
                                 @dragenter="dragEnterCat(catIdx)"
                                 @dragend="dragEndCat()">
                                
                                <div class="flex justify-between items-center pb-2 border-b border-gray-200/50 dark:border-gray-850">
                                    <!-- Drag Handle & Category Title -->
                                    <div class="flex items-center gap-1.5 cursor-grab text-purple-500 hover:text-purple-700 select-none"
                                         @mousedown="categoryDraggable = true"
                                         @mouseup="categoryDraggable = false"
                                         @mouseleave="categoryDraggable = false">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                        <span class="text-[10px] font-black uppercase tracking-wider">Geser Kategori</span>
                                    </div>
                                    <!-- Delete Button -->
                                    <button type="button" @click="kegiatan.splice(catIdx, 1)" class="text-red-500 hover:text-red-750 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-950/20 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-purple-500 uppercase tracking-wider mb-1">Nama Kategori</label>
                                    <input type="text" x-model="cat.category" :name="`kegiatan[${catIdx}][category]`" required
                                           class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold text-gray-800 dark:text-white focus:outline-none focus:border-purple-550 transition-colors"
                                           placeholder="Contoh: Pembekalan / Presentasi">
                                </div>

                                <div class="space-y-3">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider">Daftar Indikator (Drag handle untuk mengurutkan)</label>
                                    <div class="space-y-2">
                                        <template x-for="(ind, indIdx) in cat.indicators" :key="ind.id">
                                            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-sm transition-all"
                                                 draggable="true"
                                                 @dragstart="dragStart($event, indIdx, catIdx)"
                                                 @dragover.prevent
                                                 @dragenter="dragEnter(indIdx, catIdx)"
                                                 @dragend="dragEnd()">
                                                <!-- Drag Handle -->
                                                <div class="cursor-grab text-gray-400 hover:text-gray-600 p-1 shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                                    </svg>
                                                </div>
                                                <input type="text" x-model="ind.name" :name="`kegiatan[${catIdx}][indicators][${indIdx}][name]`" required
                                                       class="w-full px-3 py-2 bg-transparent border-none focus:ring-0 text-xs font-bold text-gray-750 dark:text-gray-200"
                                                       placeholder="Contoh: Kerjasama Tim / Keaktifan">
                                                <button type="button" @click="cat.indicators.splice(indIdx, 1)" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-955/20 transition duration-150 shrink-0">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <button type="button" @click="cat.indicators.push({ id: Date.now() + Math.random(), name: '' })"
                                            class="w-full text-[10px] font-black text-purple-600 hover:text-purple-750 hover:bg-purple-50 dark:hover:bg-purple-900/20 py-2 border border-dashed border-purple-300 rounded-xl transition duration-150 uppercase tracking-wider">
                                        + Tambah Indikator
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-6 border-t border-gray-100 dark:border-gray-700/50">
                    <button type="submit"
                            class="w-full bg-gradient-to-tr from-purple-600 to-indigo-650 text-white font-extrabold text-xs uppercase tracking-wider py-4 rounded-2xl shadow-lg hover:opacity-95 transition active:scale-95 duration-200">
                        Simpan Proyek Bulanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

