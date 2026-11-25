@extends('layouts.app')

@section('title', isset($lesson) ? 'Edit Materi' : 'Tambah Materi Baru')

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 via-slate-50 to-purple-50/20 dark:from-gray-950 dark:via-gray-950 dark:to-purple-950/20 min-h-screen">
    <div class="max-w-3xl mx-auto">
        
        <!-- Back Button -->
        <a href="{{ route('teacher.lessons.index') }}" class="inline-flex items-center gap-2 text-xs font-black text-purple-600 dark:text-purple-400 hover:underline uppercase tracking-wider mb-6">
            &larr; Kembali ke Dashboard
        </a>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-900 border border-gray-250 dark:border-gray-800 rounded-[32px] p-6 sm:p-10 shadow-lg relative overflow-hidden">
            <!-- Glow background -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-purple-500/5 rounded-full blur-3xl"></div>
            
            <span class="px-3 py-1 bg-purple-50 dark:bg-purple-955/20 text-purple-700 dark:text-purple-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-purple-100/10">
                Kursus: {{ $course->title }}
            </span>

            <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight mt-4 mb-8">
                {{ isset($lesson) ? 'Sunting Materi Pembelajaran' : 'Tambah Materi Pembelajaran Baru' }}
            </h2>

            <form action="{{ isset($lesson) ? route('teacher.lessons.update', [$course->id, $lesson->id]) : route('teacher.lessons.store', $course->id) }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($lesson))
                    @method('PUT')
                @endif

                <!-- Lesson Title -->
                <div>
                    <label for="title" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Judul Materi</label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $lesson->title ?? '') }}" placeholder="Contoh: Pengenalan Aljabar, Modul 1: Sifat-Sifat Cahaya" 
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-600 focus:outline-none transition">
                    @error('title')
                        <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Material Type -->
                    <div>
                        <label for="type" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Jenis Format Materi</label>
                        <select name="type" id="type" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-600 focus:outline-none transition">
                            <option value="">Pilih Format</option>
                            <option value="video" {{ old('type', $lesson->type ?? '') === 'video' ? 'selected' : '' }}>Video Pembelajaran (Google Drive)</option>
                            <option value="pdf" {{ old('type', $lesson->type ?? '') === 'pdf' ? 'selected' : '' }}>Dokumen PDF (Google Drive)</option>
                            <option value="docx" {{ old('type', $lesson->type ?? '') === 'docx' ? 'selected' : '' }}>Dokumen Word / Docx (Google Drive)</option>
                        </select>
                        @error('type')
                            <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order Position -->
                    <div>
                        <label for="order_position" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Posisi Urutan (Order)</label>
                        <input type="number" name="order_position" id="order_position" required min="0" value="{{ old('order_position', $lesson->order_position ?? $defaultOrder ?? 1) }}" placeholder="Contoh: 1, 2" 
                               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-600 focus:outline-none transition">
                        @error('order_position')
                            <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Google Drive URL -->
                <div>
                    <label for="gdrive_url" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Tautan (URL) Google Drive</label>
                    <input type="url" name="gdrive_url" id="gdrive_url" required value="{{ old('gdrive_url', $lesson->gdrive_url ?? '') }}" placeholder="Contoh: https://drive.google.com/file/d/.../view" 
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-600 focus:outline-none transition">
                    @error('gdrive_url')
                        <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                    @enderror

                    <!-- Helper instructions for teacher -->
                    <div class="mt-3.5 p-4 bg-blue-50/40 dark:bg-blue-955/10 border border-blue-100/10 rounded-2xl">
                        <span class="text-[10px] font-black text-blue-800 dark:text-blue-450 uppercase tracking-wider block mb-1">Panduan Berbagi File Google Drive:</span>
                        <ul class="list-disc list-inside text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed font-medium space-y-1">
                            <li>Buka file Anda di Google Drive.</li>
                            <li>Klik tombol <strong>Bagikan (Share)</strong> di kanan atas.</li>
                            <li>Ubah akses umum menjadi <strong>Siapa saja yang memiliki tautan (Anyone with the link)</strong> dengan peran sebagai <strong>Pengakses lihat-saja (Viewer)</strong>.</li>
                            <li>Salin tautan bagikan tersebut dan tempelkan pada kolom di atas.</li>
                            <li><em>Sistem otomatis akan mendeteksi File ID dan mengubahnya ke format embed/preview imersif.</em></li>
                        </ul>
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('teacher.lessons.index') }}" class="px-6 py-3.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-250 rounded-2xl font-black text-xs transition active:scale-95">
                        Batalkan
                    </a>
                    <button type="submit" class="px-6 py-3.5 bg-purple-600 hover:bg-purple-755 text-white rounded-2xl font-black text-xs transition shadow-lg shadow-purple-600/10 active:scale-95">
                        {{ isset($lesson) ? 'Simpan Perubahan' : 'Tambah Materi' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
