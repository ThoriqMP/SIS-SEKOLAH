@extends('layouts.app')

@section('title', 'Profil Sekolah')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Profil Sekolah</h2>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400 dark:text-green-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-300">Berhasil</h3>
                                <div class="mt-1 text-sm text-green-700 dark:text-green-200">
                                    <p>{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 dark:text-red-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Error</h3>
                                <div class="mt-1 text-sm text-red-700 dark:text-red-200">
                                    <p>{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400 dark:text-red-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Terjadi kesalahan</h3>
                                <div class="mt-1 text-sm text-red-700 dark:text-red-200">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- Status Storage & Tanda Tangan -->
                <div class="mb-6 p-4 rounded-xl border {{ ($symlinkValid ?? true) && ($signatureExists ?? false) ? 'bg-slate-50 border-slate-200 dark:bg-slate-800/40 dark:border-slate-700' : 'bg-amber-50 border-amber-200 dark:bg-amber-950/30 dark:border-amber-800' }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ ($symlinkValid ?? false) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-sky-100 text-sky-800 dark:bg-sky-900/50 dark:text-sky-300' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ ($symlinkValid ?? false) ? 'bg-emerald-500' : 'bg-sky-500' }}"></span>
                                    {{ ($symlinkValid ?? false) ? 'Symlink Storage: Terhubung' : 'Storage: Fallback Controller Aktif' }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ ($signatureExists ?? false) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ ($signatureExists ?? false) ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ ($signatureExists ?? false) ? 'Tanda Tangan Pak Wiwit: Siap' : 'Tanda Tangan Pak Wiwit: Belum Tersambung' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                Berkas penyimpanan publik melayani tanda tangan kwitansi/rapor dan logo sekolah di seluruh subdomain (main domain & teacher).
                            </p>
                        </div>
                        <form method="POST" action="{{ route('school-profile.repair-storage') }}" class="shrink-0">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-lg text-xs font-semibold transition min-h-[44px]">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Perbaiki & Sinkronkan Storage
                            </button>
                        </form>
                    </div>
                </div>

                <form method="POST" action="{{ route('school-profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Logo Sekolah -->
                    <div class="mb-8">
                        <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo Sekolah</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            @if($schoolProfile->logo_path)
                                <img src="{{ Storage::url($schoolProfile->logo_path) }}" alt="Logo Sekolah" class="h-20 w-20 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                            @else
                                <div class="h-20 w-20 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center border border-gray-300 dark:border-gray-600">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">No Logo</span>
                                </div>
                            @endif
                            <div>
                                <input type="file" id="logo" name="logo" accept="image/*"
                                       class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Sekolah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="nama_sekolah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Nama Sekolah</label>
                            <input type="text" id="nama_sekolah" name="nama_sekolah" value="{{ old('nama_sekolah', $schoolProfile->nama_sekolah) }}" required
                                   placeholder="Masukkan nama sekolah"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                        </div>
                        
                        <div>
                            <label for="pimpinan_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Nama Pimpinan</label>
                            <input type="text" id="pimpinan_nama" name="pimpinan_nama" value="{{ old('pimpinan_nama', $schoolProfile->pimpinan_nama) }}"
                                   placeholder="Masukkan nama pimpinan"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                        </div>
                        
                        <div>
                            <label for="kepala_sekolah_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ml-1">Nama Kepala Sekolah</label>
                            <input type="text" id="kepala_sekolah_nama" name="kepala_sekolah_nama" value="{{ old('kepala_sekolah_nama', $schoolProfile->kepala_sekolah_nama) }}"
                                   placeholder="Masukkan nama kepala sekolah"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 focus:ring-2 focus:ring-opacity-50 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-base placeholder:text-gray-400 transition-all">
                        </div>
                    </div>

                    <!-- Tanda Tangan Pimpinan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Tanda Tangan Pimpinan</label>
                            
                            @if($schoolProfile->signature_path)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($schoolProfile->signature_path) }}" alt="Tanda Tangan" class="h-20 object-contain border border-gray-300 dark:border-gray-600 rounded-lg">
                                </div>
                            @endif

                            <div class="space-y-4">
                                <input type="file" name="signature" accept="image/*" class="block w-full text-sm text-gray-500">
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                                    <canvas id="signatureCanvas" width="400" height="150" class="border border-gray-200 dark:border-gray-600 rounded bg-white w-full"></canvas>
                                    <div class="mt-3 flex gap-2">
                                        <button type="button" id="clearSignature" class="px-3 py-1 bg-red-100 text-red-600 rounded text-xs">Hapus</button>
                                        <button type="button" id="saveSignature" class="px-3 py-1 bg-green-100 text-green-600 rounded text-xs">Simpan</button>
                                    </div>
                                    <input type="hidden" name="signature_data" id="signatureData">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Tanda Tangan Kepala Sekolah</label>
                            
                            @if($schoolProfile->kepala_sekolah_signature_path)
                                <div class="mb-4">
                                    <img src="{{ Storage::url($schoolProfile->kepala_sekolah_signature_path) }}" alt="Tanda Tangan KS" class="h-20 object-contain border border-gray-300 dark:border-gray-600 rounded-lg">
                                </div>
                            @endif

                            <div class="space-y-4">
                                <input type="file" name="kepala_sekolah_signature" accept="image/*" class="block w-full text-sm text-gray-500">
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                                    <canvas id="ksSignatureCanvas" width="400" height="150" class="border border-gray-200 dark:border-gray-600 rounded bg-white w-full"></canvas>
                                    <div class="mt-3 flex gap-2">
                                        <button type="button" id="clearKsSignature" class="px-3 py-1 bg-red-100 text-red-600 rounded text-xs">Hapus</button>
                                        <button type="button" id="saveKsSignature" class="px-3 py-1 bg-green-100 text-green-600 rounded text-xs">Simpan</button>
                                    </div>
                                    <input type="hidden" name="kepala_sekolah_signature_data" id="ksSignatureData">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat Sekolah</label>
                        <textarea id="alamat" name="alamat" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ old('alamat', $schoolProfile->alamat) }}</textarea>
                    </div>

                    <!-- Informasi Bank -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-8">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Informasi Rekening Bank</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="bank_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Bank</label>
                                <input type="text" id="bank_nama" name="bank_nama" value="{{ old('bank_nama', $schoolProfile->bank_nama) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            
                            <div>
                                <label for="no_rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Rekening</label>
                                <input type="text" id="no_rekening" name="no_rekening" value="{{ old('no_rekening', $schoolProfile->no_rekening) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            
                            <div>
                                <label for="atas_nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Atas Nama</label>
                                <input type="text" id="atas_nama" name="atas_nama" value="{{ old('atas_nama', $schoolProfile->atas_nama) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Semester -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-8">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Pengaturan Semester</h3>
                        <p class="text-sm text-gray-500 mb-6 italic">Tentukan bulan mulai masing-masing semester. Sistem akan otomatis menentukan periode tahun akademik.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="semester_ganjil_start_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan Mulai Semester Ganjil</label>
                                <select id="semester_ganjil_start_month" name="semester_ganjil_start_month" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $i => $month)
                                        <option value="{{ $i + 1 }}" {{ old('semester_ganjil_start_month', $schoolProfile->semester_ganjil_start_month) == ($i + 1) ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="semester_genap_start_month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan Mulai Semester Genap</label>
                                <select id="semester_genap_start_month" name="semester_genap_start_month" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $i => $month)
                                        <option value="{{ $i + 1 }}" {{ old('semester_genap_start_month', $schoolProfile->semester_genap_start_month) == ($i + 1) ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition w-full sm:w-auto">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupSignature(canvasId, clearBtnId, saveBtnId, dataInputId) {
        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext('2d');
        const clearBtn = document.getElementById(clearBtnId);
        const saveBtn = document.getElementById(saveBtnId);
        const signatureDataInput = document.getElementById(dataInputId);
        
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;
        
        function resizeCanvas() {
            const rect = canvas.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = rect.height;
            ctx.strokeStyle = '#000000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
        }
        
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        
        function getPosition(e) {
            const rect = canvas.getBoundingClientRect();
            const clientX = e.clientX || (e.touches && e.touches[0].clientX);
            const clientY = e.clientY || (e.touches && e.touches[0].clientY);
            return {
                x: (clientX - rect.left) * (canvas.width / rect.width),
                y: (clientY - rect.top) * (canvas.height / rect.height)
            };
        }
        
        function startDrawing(e) {
            isDrawing = true;
            const pos = getPosition(e);
            lastX = pos.x;
            lastY = pos.y;
        }
        
        function draw(e) {
            if (!isDrawing) return;
            const pos = getPosition(e);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            lastX = pos.x;
            lastY = pos.y;
        }
        
        function stopDrawing() { isDrawing = false; }
        
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        
        canvas.addEventListener('touchstart', (e) => { e.preventDefault(); startDrawing(e); });
        canvas.addEventListener('touchmove', (e) => { e.preventDefault(); draw(e); });
        canvas.addEventListener('touchend', stopDrawing);
        
        clearBtn.addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signatureDataInput.value = '';
        });
        
        saveBtn.addEventListener('click', () => {
            signatureDataInput.value = canvas.toDataURL('image/png');
            saveBtn.textContent = 'Tersimpan!';
            setTimeout(() => { saveBtn.textContent = 'Simpan'; }, 2000);
        });
    }

    setupSignature('signatureCanvas', 'clearSignature', 'saveSignature', 'signatureData');
    setupSignature('ksSignatureCanvas', 'clearKsSignature', 'saveKsSignature', 'ksSignatureData');
});
</script>
@endsection
