@extends('mbi.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl space-y-6" x-data="{ showCreateModal: false, editModalItem: null }">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex text-xs text-gray-500 dark:text-gray-400 mb-1 space-x-2">
                <a href="{{ route('admin.assessment-packages.index') }}" class="hover:text-purple-600 transition">Paket Asesmen</a>
                <span>/</span>
                @if($instrument->package)
                    <a href="{{ route('admin.assessment-packages.show', $instrument->package) }}" class="hover:text-purple-600 transition">{{ $instrument->package->name }}</a>
                    <span>/</span>
                @endif
                <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ $instrument->name }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white flex items-center gap-3">
                <span>Item Pernyataan: {{ $instrument->name }}</span>
                <span class="px-2.5 py-0.5 bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 font-mono text-xs font-bold rounded-lg border border-purple-200 dark:border-purple-800">
                    {{ $instrument->section_key }}
                </span>
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kelola daftar item pernyataan, soal, pilihan jawaban (opsi), dan tag aspek di dalam instrumen ini.</p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="showCreateModal = true" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-purple-500/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Tambah Item Pernyataan
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search & Filters -->
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/80 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.assessment-instruments.items.index', $instrument) }}" method="GET" class="w-full sm:w-80 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, pernyataan, atau aspek..."
                   class="w-full pl-9 pr-4 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>

        <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold">
            Menampilkan {{ $items->total() }} Item Pernyataan
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-900/60 border-b border-gray-200 dark:border-slate-700 text-gray-500 dark:text-gray-400 uppercase font-semibold">
                        <th class="py-3.5 px-4 w-16 text-center">Urutan</th>
                        <th class="py-3.5 px-4 w-28">Kode Item</th>
                        <th class="py-3.5 px-4">Teks Pernyataan & Opsi Jawaban</th>
                        <th class="py-3.5 px-4 w-32">Aspek / Tag</th>
                        <th class="py-3.5 px-4 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/60 text-gray-700 dark:text-gray-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-gray-500 dark:text-gray-400">
                                #{{ $item->order_number }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 font-mono text-[11px] font-bold rounded-lg border border-purple-200 dark:border-purple-800">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-medium leading-relaxed space-y-2">
                                <div>{{ $item->question_text }}</div>

                                <!-- Render Pilihan Jawaban / Opsi -->
                                @if(!empty($item->options) && is_array($item->options))
                                    <div class="p-3 bg-gray-50 dark:bg-slate-900/80 rounded-xl border border-gray-200/80 dark:border-slate-700/80 space-y-1.5 mt-2">
                                        <div class="text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            Pilihan Jawaban ({{ count($item->options) }} Opsi):
                                        </div>
                                        <div class="grid grid-cols-1 gap-1">
                                            @foreach($item->options as $opt)
                                                <div class="text-[11px] text-gray-700 dark:text-gray-300 flex items-start gap-2 bg-white dark:bg-slate-800 p-1.5 rounded-lg border border-gray-100 dark:border-slate-700/60">
                                                    <span class="font-bold text-purple-600 dark:text-purple-400 font-mono w-5 flex-shrink-0">
                                                        {{ $opt['key'] ?? ($loop->iteration . '.') }}
                                                    </span>
                                                    <span class="flex-1">{{ $opt['text'] ?? (is_string($opt) ? $opt : json_encode($opt)) }}</span>
                                                    @if(!empty($opt['tag']))
                                                        <span class="text-[9px] font-bold px-1.5 py-0.5 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 rounded border border-indigo-200 dark:border-indigo-800 flex-shrink-0 font-mono">
                                                            {{ $opt['tag'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->aspect_name)
                                    <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] rounded border border-indigo-200 dark:border-indigo-800">
                                        {{ $item->aspect_name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" @click="editModalItem = {{ json_encode($item) }}" class="p-1.5 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-slate-700 rounded-lg transition border border-gray-200 dark:border-slate-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.assessment-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus item pernyataan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition border border-gray-200 dark:border-slate-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                Belum ada item pernyataan untuk instrumen ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-slate-700/60">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tambah Item -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl p-6 max-w-lg w-full shadow-2xl space-y-5" @click.away="showCreateModal = false">
            <div class="flex justify-between items-center">
                <h3 class="text-base font-extrabold text-gray-900 dark:text-white">+ Tambah Item Pernyataan Baru</h3>
                <button type="button" @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white font-bold">✕</button>
            </div>

            <form action="{{ route('admin.assessment-instruments.items.store', $instrument) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kode Item <span class="text-red-500">*</span></label>
                        <input type="text" name="code" required placeholder="Contoh: DISC_01, Q72"
                               class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Aspek / Tag (Optional)</label>
                        <input type="text" name="aspect_name" placeholder="Contoh: D, I, V, A, K"
                               class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Teks Pernyataan / Soal <span class="text-red-500">*</span></label>
                    <textarea name="question_text" rows="3" required placeholder="Tuliskan kalimat pernyataan atau pertanyaannya..."
                              class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Pilihan Jawaban / Opsi (JSON Format - Optional)</label>
                    <textarea name="options_json" rows="3" placeholder='[{"key": "A", "text": "Pernyataan A", "tag": "V"}, {"key": "B", "text": "Pernyataan B", "tag": "A"}]'
                              class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-purple-500"></textarea>
                    <span class="text-[10px] text-gray-400">Kosongkan jika bukan soal pilihan ganda.</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nomor Urut</label>
                    <input type="number" name="order_number" min="1" placeholder="Otomatik urutan terakhir"
                           class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="pt-3 border-t border-gray-100 dark:border-slate-700 flex justify-end gap-2">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold">Simpan Item</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Item -->
    <div x-show="editModalItem !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-2xl p-6 max-w-lg w-full shadow-2xl space-y-5" @click.away="editModalItem = null">
            <div class="flex justify-between items-center">
                <h3 class="text-base font-extrabold text-gray-900 dark:text-white">Edit Item Pernyataan</h3>
                <button type="button" @click="editModalItem = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-white font-bold">✕</button>
            </div>

            <form :action="'{{ url('/admin/assessment-items') }}/' + (editModalItem ? editModalItem.id : '')" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kode Item <span class="text-red-500">*</span></label>
                        <input type="text" name="code" :value="editModalItem ? editModalItem.code : ''" required
                               class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Aspek / Tag</label>
                        <input type="text" name="aspect_name" :value="editModalItem ? editModalItem.aspect_name : ''"
                               class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Teks Pernyataan / Soal <span class="text-red-500">*</span></label>
                    <textarea name="question_text" rows="3" required :value="editModalItem ? editModalItem.question_text : ''"
                              class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Pilihan Jawaban / Opsi (JSON Format)</label>
                    <textarea name="options_json" rows="3" :value="editModalItem && editModalItem.options ? JSON.stringify(editModalItem.options, null, 2) : ''"
                              class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-purple-500"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nomor Urut</label>
                    <input type="number" name="order_number" :value="editModalItem ? editModalItem.order_number : 1" min="1"
                           class="w-full px-3.5 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="pt-3 border-t border-gray-100 dark:border-slate-700 flex justify-end gap-2">
                    <button type="button" @click="editModalItem = null" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
