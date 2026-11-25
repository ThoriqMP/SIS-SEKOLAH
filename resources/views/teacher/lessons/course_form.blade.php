@extends('layouts.app')

@section('title', isset($course) ? 'Edit Kursus' : 'Buat Kursus Baru')

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
            
            <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight mb-8">
                {{ isset($course) ? 'Sunting Data Kursus' : 'Buat Kursus Pembelajaran Baru' }}
            </h2>

            <form action="{{ isset($course) ? route('teacher.courses.update', $course->id) : route('teacher.courses.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($course))
                    @method('PUT')
                @endif

                <!-- Course Title -->
                <div>
                    <label for="title" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Judul Kursus</label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $course->title ?? '') }}" placeholder="Contoh: Matematika Dasar Perkalian, Sejarah Kemerdekaan" 
                           class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-600 focus:outline-none transition">
                    @error('title')
                        <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Education Level -->
                    <div>
                        <label for="education_level" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Jenjang Pendidikan</label>
                        <select name="education_level" id="education_level" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-600 focus:outline-none transition">
                            <option value="">Pilih Jenjang</option>
                            <option value="SD" {{ old('education_level', $course->education_level ?? request()->education_level) === 'SD' ? 'selected' : '' }}>Sekolah Dasar (SD)</option>
                            <option value="SMP" {{ old('education_level', $course->education_level ?? request()->education_level) === 'SMP' ? 'selected' : '' }}>Sekolah Menengah Pertama (SMP)</option>
                            <option value="SMA" {{ old('education_level', $course->education_level ?? request()->education_level) === 'SMA' ? 'selected' : '' }}>Sekolah Menengah Atas (SMA)</option>
                        </select>
                        @error('education_level')
                            <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grade Level -->
                    <div>
                        <label for="grade" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Tingkat Kelas (Grade)</label>
                        <select name="grade" id="grade" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-600 focus:outline-none transition">
                            <option value="">Pilih Tingkat</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('grade', $course->grade ?? '') == $i ? 'selected' : '' }}>Kelas {{ $i }}</option>
                            @endfor
                        </select>
                        @error('grade')
                            <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="p-6 bg-purple-50/40 dark:bg-purple-950/10 border border-purple-100/20 rounded-[24px] space-y-6">
                    <h3 class="text-sm font-black text-purple-900 dark:text-purple-300 uppercase tracking-wider">
                        🏅 Penghargaan Lencana Otomatis (100% Progres)
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                        Lencana ini akan didapatkan secara otomatis oleh siswa sesaat setelah menyelesaikan seluruh materi pada kursus ini.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Badge Name -->
                        <div>
                            <label for="badge_name" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Nama Lencana</label>
                            <input type="text" name="badge_name" id="badge_name" required value="{{ old('badge_name', $badge->badge_name ?? '') }}" placeholder="Contoh: Bintang Aljabar Cilik" 
                                   class="w-full px-4 py-3 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-600 focus:outline-none transition">
                            @error('badge_name')
                                <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Badge Icon/Image -->
                        <div>
                            <label for="badge_image_url" class="block text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Emoji / URL Gambar Lencana</label>
                            <input type="text" name="badge_image_url" id="badge_image_url" value="{{ old('badge_image_url', $badge->badge_image_url ?? '') }}" placeholder="Contoh: 🏆 atau link gambar" 
                                   class="w-full px-4 py-3 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-600 focus:outline-none transition">
                            
                            <!-- Quick Emoji Picker -->
                            <div class="mt-3" x-data="{ emojis: ['🏆', '🎖', '⭐', '🥇', '🎨', '🧪', '📐', '📖', '🚀', '💻', '🧠', '🗺'] }">
                                <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider block mb-1.5">Pilih Emoji Cepat:</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <template x-for="em in emojis" :key="em">
                                        <button type="button" @click="document.getElementById('badge_image_url').value = em" 
                                                class="w-8 h-8 flex items-center justify-center bg-white dark:bg-gray-950 hover:bg-purple-100 dark:hover:bg-purple-950/30 border border-gray-200 dark:border-gray-800 rounded-xl transition text-base">
                                            <span x-text="em"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            @error('badge_image_url')
                                <p class="text-xs text-red-500 font-bold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('teacher.lessons.index') }}" class="px-6 py-3.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-250 rounded-2xl font-black text-xs transition active:scale-95">
                        Batalkan
                    </a>
                    <button type="submit" class="px-6 py-3.5 bg-purple-600 hover:bg-purple-755 text-white rounded-2xl font-black text-xs transition shadow-lg shadow-purple-600/10 active:scale-95">
                        {{ isset($course) ? 'Simpan Perubahan' : 'Buat Kursus' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
