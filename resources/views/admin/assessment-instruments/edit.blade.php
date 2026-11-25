@extends('mbi.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl space-y-6">
    <nav class="flex text-xs text-gray-500 dark:text-gray-400 mb-1 space-x-2">
        <a href="{{ route('admin.assessment-packages.index') }}" class="hover:text-purple-600 transition">Paket Asesmen</a>
        <span>/</span>
        <a href="{{ route('admin.assessment-packages.show', $instrument->package_id) }}" class="hover:text-purple-600 transition">{{ $instrument->package->name ?? 'Paket' }}</a>
        <span>/</span>
        <span class="text-gray-800 dark:text-gray-200 font-semibold">Edit Instrumen</span>
    </nav>

    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/80 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900 dark:text-white">Edit Instrumen Asesmen</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Perbarui konfigurasi instrumen {{ $instrument->name }}.</p>
        </div>

        <form action="{{ route('admin.assessment-instruments.update', $instrument) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Instrumen <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $instrument->name) }}" required
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Section Key <span class="text-red-500">*</span></label>
                    <input type="text" name="section_key" value="{{ old('section_key', $instrument->section_key) }}" required
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-purple-500 transition">
                    @error('section_key') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Petunjuk Pengerjaan Instrumen</label>
                <textarea name="instruction" rows="3"
                          class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">{{ old('instruction', $instrument->instruction) }}</textarea>
                @error('instruction') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Jenis Input Tampilan <span class="text-red-500">*</span></label>
                    <select name="input_type" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                        <option value="radio" @selected(old('input_type', $instrument->input_type) === 'radio')>Pilihan Ganda / Radio</option>
                        <option value="checklist_item" @selected(old('input_type', $instrument->input_type) === 'checklist_item')>Checklist Item (Gue Banget / Strength)</option>
                        <option value="scale" @selected(old('input_type', $instrument->input_type) === 'scale')>Skala Nilai (Likert 1-4)</option>
                        <option value="form_input" @selected(old('input_type', $instrument->input_type) === 'form_input')>Isian Form / Biodata</option>
                    </select>
                    @error('input_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Min. Pilihan</label>
                    <input type="number" name="min_select" value="{{ old('min_select', $instrument->min_select) }}" min="0"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                    @error('min_select') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Max. Pilihan</label>
                    <input type="number" name="max_select" value="{{ old('max_select', $instrument->max_select) }}" min="0"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                    @error('max_select') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-center">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nomor Urut Tampilan</label>
                    <input type="number" name="order_number" value="{{ old('order_number', $instrument->order_number) }}" min="1"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition">
                    @error('order_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer text-xs font-semibold text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $instrument->is_active)) class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 bg-gray-100 border-gray-300 dark:bg-slate-900 dark:border-slate-700">
                        <span>Aktifkan Instrumen Ini</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-slate-700/60 flex justify-end gap-3">
                <a href="{{ route('admin.assessment-packages.show', $instrument->package_id) }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-purple-500/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
