@extends('layouts.app')

@section('title', 'Pengaturan Surat Keterangan')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back --}}
        <a href="{{ route('surat-keterangan.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 mb-6 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-5">
                <h1 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fa-solid fa-cogs"></i>
                    Pengaturan Surat Keterangan
                </h1>
                <p class="text-purple-200 text-sm mt-1">Kelola logo, tanda tangan, dan identitas default psikolog</p>
            </div>

            <form action="{{ route('surat-keterangan.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
                @csrf
                @method('PUT')

                @if (session('success'))
                    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl shadow-sm">
                        <div class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                            <div>
                                <p class="font-bold text-sm">Terjadi Kesalahan:</p>
                                <ul class="list-disc list-inside text-xs mt-1 space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Section: Logo & Signature Upload --}}
                <div>
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">1</span>
                        File Logo & Tanda Tangan
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Logo Pro Sekar --}}
                        <div class="flex flex-col gap-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo Pro Sekar</label>
                            <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                <div class="w-24 h-20 flex-shrink-0 bg-white border dark:border-gray-600 rounded-lg overflow-hidden flex items-center justify-center p-2">
                                    @if($setting->logo_path)
                                        <img src="{{ Storage::url($setting->logo_path) }}" alt="Logo Pro Sekar" class="max-h-full max-w-full object-contain">
                                    @else
                                        <div class="text-[10px] text-gray-400 text-center font-semibold">Menggunakan Logo Default</div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo" accept="image/*"
                                           class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 dark:file:bg-gray-700 dark:file:text-gray-300 hover:file:bg-purple-100 cursor-pointer">
                                    <p class="mt-1 text-[10px] text-gray-400">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Tanda Tangan Psikolog --}}
                        <div class="flex flex-col gap-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanda Tangan Psikolog</label>
                            <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                <div class="w-24 h-20 flex-shrink-0 bg-white border dark:border-gray-600 rounded-lg overflow-hidden flex items-center justify-center p-2">
                                    @if($setting->signature_path)
                                        <img src="{{ Storage::url($setting->signature_path) }}" alt="Tanda Tangan" class="max-h-full max-w-full object-contain">
                                    @else
                                        <div class="text-[10px] text-gray-400 text-center font-semibold">Menggunakan Ttd Default</div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="signature" accept="image/*"
                                           class="block w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 dark:file:bg-gray-700 dark:file:text-gray-300 hover:file:bg-purple-100 cursor-pointer">
                                    <p class="mt-1 text-[10px] text-gray-400">Format: JPG, PNG. Maksimal 1MB.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Identitas Psikolog --}}
                <div>
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">2</span>
                        Identitas & Kredensial Psikolog
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Psikolog <span class="text-red-500">*</span></label>
                            <input type="text" name="psikolog_nama" value="{{ old('psikolog_nama', $setting->psikolog_nama) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" name="psikolog_jabatan" value="{{ old('psikolog_jabatan', $setting->psikolog_jabatan) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Kantor Psikolog <span class="text-red-500">*</span></label>
                            <textarea name="psikolog_alamat" rows="3" required
                                      class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm">{{ old('psikolog_alamat', $setting->psikolog_alamat) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. SIAP HIMPSI <span class="text-red-500">*</span></label>
                            <input type="text" name="psikolog_no_himpsi" value="{{ old('psikolog_no_himpsi', $setting->psikolog_no_himpsi) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('surat-keterangan.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-2.5 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
