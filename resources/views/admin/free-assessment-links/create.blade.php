@extends('mbi.layouts.app')

@section('title', 'Buat Tautan Free Assessment')

@section('content')
<div class="py-10 bg-gradient-to-br from-gray-50 via-slate-50 to-purple-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-slate-900 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Top Nav -->
        <a href="{{ route('admin.free-links.index') }}" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Link
        </a>

        <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
            <div class="flex items-center gap-3.5 pb-5 border-b border-slate-100 dark:border-slate-800">
                <div class="w-12 h-12 rounded-2xl bg-purple-600/10 dark:bg-purple-900/40 border border-purple-500/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-link"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 dark:text-white">Buat Tautan Free Assessment Baru</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tautan ini dapat diakses secara publik tanpa batasan kode akses.</p>
                </div>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs space-y-1">
                    <strong class="font-bold block">Gagal menyimpan tautan:</strong>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.free-links.store') }}" class="space-y-5" x-data="{ title: '{{ old('title') }}', slug: '{{ old('slug') }}', autoSlug: true }">
                @csrf

                <!-- Judul -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Judul Tautan Asesmen <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" x-model="title" @input="if(autoSlug) slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')" required
                           placeholder="Contoh: Free Assessment Tes Personal Mapping Siswa Baru 2026"
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-500">
                </div>

                <!-- Slug -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Slug URL Publik <span class="text-rose-500">*</span>
                        </label>
                        <button type="button" @click="autoSlug = !autoSlug" class="text-[11px] text-purple-600 dark:text-purple-400 font-bold hover:underline">
                            <span x-text="autoSlug ? 'Edit Manual' : 'Otomatis dari Judul'"></span>
                        </button>
                    </div>
                    <div class="flex items-center rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 overflow-hidden px-4 py-3">
                        <span class="text-xs font-mono text-slate-400 select-none">/free-assessment/</span>
                        <input type="text" name="slug" x-model="slug" @input="autoSlug = false" required
                               placeholder="tes-minat-bakat-2026"
                               class="w-full bg-transparent border-none p-0 text-xs font-mono font-bold text-purple-600 dark:text-purple-300 focus:outline-none focus:ring-0">
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Hanya huruf kecil, angka, dan strip (-).</span>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Deskripsi / Keterangan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="description" rows="3" placeholder="Tuliskan petunjuk atau keterangan sasaran peserta..."
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-500">{{ old('description') }}</textarea>
                </div>

                <!-- Status Aktif -->
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                               class="w-4 h-4 rounded text-purple-600 border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-900 focus:ring-purple-500">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Aktifkan tautan ini langsung setelah dibuat</span>
                    </label>
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.free-links.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold shadow-lg shadow-purple-900/30 transition flex items-center gap-2">
                        <i class="fa-solid fa-save"></i>
                        <span>Simpan & Terbitkan Tautan</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
