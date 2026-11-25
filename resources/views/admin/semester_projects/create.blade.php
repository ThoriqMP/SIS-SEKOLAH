@extends('layouts.app')
@section('title', 'Tambah Proyek ke Semester')

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.semester-projects.index') }}"
               class="p-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-150 dark:border-gray-700 shadow-sm text-gray-500 hover:text-purple-600 transition active:scale-95">
                ← Kembali
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">Tambah Proyek ke Semester</h1>
                <p class="text-xs text-gray-500 font-medium mt-0.5">Definisikan proyek semester dan pilih proyek bulanan yang berasosiasi.</p>
            </div>
        </div>

        <form action="{{ route('admin.semester-projects.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Konfigurasi Proyek Semester --}}
            <div class="bg-white dark:bg-gray-800 rounded-[28px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6">
                <div class="border-l-4 border-purple-500 pl-4">
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">1. Detail Proyek Semester</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6" x-data="academicYearSelector()">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Semester *</label>
                        <select name="semester" required x-model="selectedSemester"
                                class="w-full px-4 py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all appearance-none">
                            <option value="Ganjil">Semester Ganjil</option>
                            <option value="Genap">Semester Genap</option>
                        </select>
                        @error('semester') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Tahun Ajaran *</label>
                        <select name="year_name" required x-model="selectedAcademicYear"
                                class="w-full px-4 py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all appearance-none">
                            <template x-for="year in getAcademicYears()" :key="year">
                                <option :value="year" x-text="year" :selected="year === selectedAcademicYear"></option>
                            </template>
                        </select>
                        @error('year_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Urutan Tampil (sort order)</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                               class="w-full px-4 py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Nama Proyek Semester *</label>
                        <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Proyek Semester Ganjil"
                               class="w-full px-4 py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                        @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wider">Deskripsi Proyek Semester</label>
                    <textarea name="description" rows="3" placeholder="Deskripsi singkat target dan alur proyek semester..."
                              class="w-full px-4 py-3 rounded-2xl bg-gray-50/80 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Pemilihan Proyek Bulanan (Project templates) --}}
            <div class="bg-white dark:bg-gray-800 rounded-[28px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6">
                <div class="border-l-4 border-amber-500 pl-4">
                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">2. Asosiasi Proyek Bulanan (PBL)</h3>
                    <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-tight">Pilih satu atau beberapa proyek bulanan yang dilaksanakan pada semester ini.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($projectTemplates as $tpl)
                        <label class="relative flex items-start p-5 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 hover:bg-amber-50/10 dark:hover:bg-amber-950/10 cursor-pointer transition group">
                            <div class="flex items-center h-5 mr-3">
                                <input type="checkbox" name="project_template_ids[]" value="{{ $tpl->id }}"
                                       class="rounded text-amber-500 focus:ring-amber-500 border-gray-300 dark:border-gray-650 bg-white dark:bg-gray-900 w-5 h-5 transition">
                            </div>
                            <div class="text-sm">
                                <span class="block font-black text-gray-900 dark:text-white group-hover:text-amber-700 dark:group-hover:text-amber-450">{{ $tpl->title }}</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $tpl->description ?: 'Tidak ada deskripsi.' }}</span>
                            </div>
                        </label>
                    @empty
                        <div class="col-span-2 p-8 text-center text-gray-400 italic text-xs bg-gray-50 dark:bg-gray-900/20 rounded-2xl border border-dashed border-gray-200">
                            Belum ada template Proyek Bulanan yang terdaftar. Buat proyek bulanan terlebih dahulu.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.semester-projects.index') }}"
                   class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-sm rounded-xl shadow-md transition-all active:scale-95">
                    Simpan Proyek Semester
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('academicYearSelector', () => ({
            selectedSemester: '{{ old("semester", $activeYear->semester ?? "Ganjil") }}',
            selectedAcademicYear: '{{ old("year_name", $activeYear->year_name ?? "") }}',
            init() {
                if (!this.selectedAcademicYear) {
                    const currentYear = new Date().getFullYear();
                    this.selectedAcademicYear = `${currentYear}/${currentYear + 1}`;
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
            }
        }));
    });
</script>
@endsection
