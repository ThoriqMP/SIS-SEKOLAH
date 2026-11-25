@extends('layouts.app')

@section('content')
<div class="py-12" x-data="{ activeTab: 'predikat' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Pengaturan Akademik</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2 text-lg">Kelola standar KKM dan saran catatan wali kelas untuk mempermudah pelaporan.</p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-4 mb-8 bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl w-fit">
            <button @click="activeTab = 'predikat'" :class="activeTab === 'predikat' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="px-8 py-3 rounded-xl font-bold transition-all text-sm uppercase tracking-widest">
                Rentang Predikat
            </button>
            <button @click="activeTab = 'mutu'" :class="activeTab === 'mutu' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="px-8 py-3 rounded-xl font-bold transition-all text-sm uppercase tracking-widest">
                Mutu Non-Akademik
            </button>
            <button @click="activeTab = 'suggestions'" :class="activeTab === 'suggestions' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="px-8 py-3 rounded-xl font-bold transition-all text-sm uppercase tracking-widest">
                Saran Catatan
            </button>
        </div>



        <!-- Grade Ranges Management -->
        <div x-show="activeTab === 'predikat'" x-transition x-data="{ 
            ranges: {{ json_encode($gradeRanges) }},
            addRange() {
                this.ranges.push({ letter: '', min_score: 0, description: '' });
            },
            removeRange(index) {
                this.ranges.splice(index, 1);
            }
        }">
            <div class="bg-white dark:bg-gray-800 rounded-[32px] shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <form action="{{ route('admin.academic-settings.grade-ranges.update') }}" method="POST">
                    @csrf
                    <div class="p-8 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Pengaturan Rentang Predikat Nilai</h3>
                            <p class="text-gray-500 dark:text-gray-400">Tentukan huruf mutu berdasarkan nilai minimum (Misal: 93 = A).</p>
                        </div>
                        <div class="flex gap-4">
                            <button type="button" @click="addRange()" class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-6 py-4 rounded-2xl font-bold transition hover:bg-gray-50 uppercase tracking-widest text-sm">
                                Tambah Baris
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-blue-200 transition transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest text-sm">
                                Simpan Rentang
                            </button>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                        <th class="pb-6 pl-4">Huruf Mutu (Predikat)</th>
                                        <th class="pb-6">Skor Minimum (Min Score)</th>
                                        <th class="pb-6">Keterangan / Deskripsi</th>
                                        <th class="pb-6 text-right pr-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    <template x-for="(range, index) in ranges" :key="index">
                                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-900/20 transition-all">
                                            <td class="py-4 pl-4">
                                                <input type="text" :name="`ranges[${index}][letter]`" x-model="range.letter" required placeholder="A / A- / B" 
                                                       class="w-32 rounded-xl border-gray-200 dark:bg-gray-700 font-black text-blue-600 uppercase">
                                            </td>
                                            <td class="py-4">
                                                <input type="number" :name="`ranges[${index}][min_score]`" x-model="range.min_score" step="0.01" min="0" max="100" required 
                                                       class="w-32 rounded-xl border-gray-200 dark:bg-gray-700 font-bold">
                                            </td>
                                            <td class="py-4">
                                                <input type="text" :name="`ranges[${index}][description]`" x-model="range.description" placeholder="Deskripsi Capaian" 
                                                       class="w-full rounded-xl border-gray-200 dark:bg-gray-700 font-medium">
                                            </td>
                                            <td class="py-4 text-right pr-4">
                                                <button type="button" @click="removeRange(index)" class="text-red-400 hover:text-red-600 transition p-2">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Non-Academic Mutu Labels Management -->
        <div x-show="activeTab === 'mutu'" x-transition x-data="{ 
            labels: {{ json_encode($mutuLabels) }},
            addLabel() {
                this.labels.push({ type: 'Diniyah', code: '', label: '' });
            },
            removeLabel(index) {
                this.labels.splice(index, 1);
            }
        }">
            <div class="bg-white dark:bg-gray-800 rounded-[32px] shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <form action="{{ route('admin.academic-settings.mutu-labels.update') }}" method="POST">
                    @csrf
                    <div class="p-8 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-900/20">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Keterangan Huruf Mutu (Non-Akademik)</h3>
                            <p class="text-gray-500 dark:text-gray-400">Kelola keterangan untuk predikat Diniyah dan Praktek Ibadah.</p>
                        </div>
                        <div class="flex gap-4">
                            <button type="button" @click="addLabel()" class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-6 py-4 rounded-2xl font-bold transition hover:bg-gray-50 uppercase tracking-widest text-sm">
                                Tambah Baris
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black shadow-lg shadow-blue-200 transition transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest text-sm">
                                Simpan Keterangan
                            </button>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                        <th class="pb-6 pl-4">Modul</th>
                                        <th class="pb-6">Huruf Mutu</th>
                                        <th class="pb-6">Keterangan Deskripsi</th>
                                        <th class="pb-6 text-right pr-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    <template x-for="(label, index) in labels" :key="index">
                                        <tr class="group hover:bg-gray-50 dark:hover:bg-gray-900/20 transition-all">
                                            <td class="py-4 pl-4">
                                                <select :name="`labels[${index}][type]`" x-model="label.type" class="w-full rounded-xl border-gray-200 dark:bg-gray-700 font-bold">
                                                    <option value="Diniyah">Diniyah</option>
                                                    <option value="Ibadah">Ibadah</option>
                                                </select>
                                            </td>
                                            <td class="py-4">
                                                <input type="text" :name="`labels[${index}][code]`" x-model="label.code" required placeholder="A / B / C" 
                                                       class="w-32 rounded-xl border-gray-200 dark:bg-gray-700 font-black text-emerald-600 uppercase">
                                            </td>
                                            <td class="py-4">
                                                <input type="text" :name="`labels[${index}][label]`" x-model="label.label" required placeholder="Deskripsi untuk mutu ini" 
                                                       class="w-full rounded-xl border-gray-200 dark:bg-gray-700 font-medium">
                                            </td>
                                            <td class="py-4 text-right pr-4">
                                                <button type="button" @click="removeLabel(index)" class="text-red-400 hover:text-red-600 transition p-2">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Suggestions Management -->
        <div x-show="activeTab === 'suggestions'" x-transition>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Add New Suggestion -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-[32px] shadow-xl border border-gray-100 dark:border-gray-700 sticky top-8">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Tambah Saran Baru</h3>
                        <form action="{{ route('admin.academic-settings.suggestions.store') }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Kategori (Opsional)</label>
                                    <input type="text" name="category" placeholder="Contoh: Perkembangan Karakter" class="w-full rounded-2xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 p-4 font-bold">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Kalimat Saran</label>
                                    <textarea name="content" rows="6" required class="w-full rounded-2xl border-gray-200 dark:bg-gray-700 dark:border-gray-600 p-4" placeholder="Masukkan kalimat yang akan menjadi saran untuk guru..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 py-4 rounded-2xl font-black shadow-xl transition transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                                    Simpan Saran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Suggestions List -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-[32px] shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Saran Kalimat</h3>
                            <p class="text-gray-500 dark:text-gray-400">Kalimat-kalimat ini akan muncul sebagai pilihan saat guru mengisi catatan wali kelas.</p>
                        </div>
                        
                        @php
                            $suggestions = \App\Models\TeacherNoteSuggestion::latest()->get();
                        @endphp

                        <div class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($suggestions as $suggestion)
                                <div class="p-8 hover:bg-gray-50 dark:hover:bg-gray-900/20 transition-all group" x-data="{ editing: false }">
                                    <div x-show="!editing">
                                        <div class="flex justify-between items-start gap-4">
                                            <div class="flex-1">
                                                @if($suggestion->category)
                                                    <span class="inline-block px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest rounded-full mb-3">
                                                        {{ $suggestion->category }}
                                                    </span>
                                                @endif
                                                <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">{{ $suggestion->content }}</p>
                                            </div>
                                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                                <button @click="editing = true" class="p-2 text-amber-500 hover:bg-amber-50 rounded-xl transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                <form action="{{ route('admin.academic-settings.suggestions.destroy', $suggestion->id) }}" method="POST" onsubmit="return confirm('Hapus saran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="editing" x-cloak>
                                        <form action="{{ route('admin.academic-settings.suggestions.update', $suggestion->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="space-y-4">
                                                <input type="text" name="category" value="{{ $suggestion->category }}" class="w-full rounded-xl border-gray-200 dark:bg-gray-700 p-3 font-bold" placeholder="Kategori">
                                                <textarea name="content" rows="4" class="w-full rounded-xl border-gray-200 dark:bg-gray-700 p-4">{{ $suggestion->content }}</textarea>
                                                <div class="flex gap-2 justify-end">
                                                    <button type="button" @click="editing = false" class="px-6 py-2 text-gray-500 font-bold">Batal</button>
                                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold">Simpan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="p-20 text-center">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-400">Belum ada saran kalimat.</h4>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
