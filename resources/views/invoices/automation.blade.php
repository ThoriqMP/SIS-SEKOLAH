@extends('layouts.app')

@section('title', 'Kontrol Otomatisasi Invoice')

@section('content')
<div class="py-12" x-data="{
    // Mapping form variables
    mappingId: '',
    idSiswa: '',
    idLayanan: '',
    nominal: '',
    month: '',
    isEditing: false,

    editMapping(mapping) {
        this.mappingId = mapping.id;
        this.idSiswa = mapping.id_siswa;
        this.idLayanan = mapping.id_layanan;
        this.nominal = Math.round(parseFloat(mapping.nominal));
        this.month = mapping.bulan ? mapping.tahun + '-' + String(mapping.bulan).padStart(2, '0') : '';
        this.isEditing = true;
        // Scroll to form nicely
        document.getElementById('mapping-card').scrollIntoView({ behavior: 'smooth' });
    },
    
    resetForm() {
        this.mappingId = '';
        this.idSiswa = '';
        this.idLayanan = '';
        this.nominal = '';
        this.month = '';
        this.isEditing = false;
    }
}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md p-6 rounded-[24px] border border-gray-100 dark:border-gray-700 shadow-sm animate-fade-in">
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wider">
                    Kontrol Otomatisasi & Mapping Biaya
                </h2>
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">
                    Atur jadwal billing, jalankan engine bulanan, dan kelola nominal kustom per siswa untuk semua komponen pembayaran.
                </p>
            </div>
            <a href="{{ route('invoices.index') }}" class="inline-flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-xl font-bold uppercase tracking-wider text-xs transition shadow-lg shadow-gray-500/10 active:scale-95">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.6" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <!-- Success/Error Alerts -->
        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 rounded-2xl p-4 text-emerald-800 dark:text-emerald-300 font-bold text-sm shadow-sm flex items-start gap-3">
                <svg class="h-5 w-5 mt-0.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-black">Berhasil!</p>
                    <p class="font-medium text-xs mt-1 whitespace-pre-line">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800/60 rounded-2xl p-4 text-red-800 dark:text-red-300 font-bold text-sm shadow-sm flex items-start gap-3">
                <svg class="h-5 w-5 mt-0.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-black">Terjadi Kesalahan!</p>
                    <p class="font-medium text-xs mt-1">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Section 1: Settings Panel (Billing Schedule Parameters) -->
        <div class="bg-white dark:bg-gray-800 rounded-[28px] border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-10 w-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-gray-900 dark:text-white uppercase tracking-wider">
                        Pengaturan Parameter Tanggal Billing
                    </h3>
                    <p class="text-xs text-gray-400 font-bold mt-0.5">Konfigurasikan tanggal pembuatan tagihan dan masa tenggang jatuh tempo bulanan.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('invoices.automation.save-settings') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @csrf
                <div>
                    <label for="invoice_generation_day" class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2 ml-1">Hari Penagihan Bulanan (Tanggal Invoice)</label>
                    <input type="number" id="invoice_generation_day" name="invoice_generation_day" required min="1" max="28" 
                           value="{{ old('invoice_generation_day', $billingSetting->invoice_generation_day ?? 25) }}"
                           class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition font-bold"
                           placeholder="1-28">
                    <p class="text-[10px] text-gray-400 font-bold mt-1 ml-1">Hari di mana sistem otomatis memicu pembuatan invoice.</p>
                </div>

                <div>
                    <label for="invoice_due_day" class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2 ml-1">Hari Jatuh Tempo Bulanan</label>
                    <input type="number" id="invoice_due_day" name="invoice_due_day" required min="1" max="28" 
                           value="{{ old('invoice_due_day', $billingSetting->invoice_due_day ?? 10) }}"
                           class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition font-bold"
                           placeholder="1-28">
                    <p class="text-[10px] text-gray-400 font-bold mt-1 ml-1">Batas akhir pembayaran pada bulan berikutnya.</p>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase tracking-widest text-xs py-3.5 rounded-2xl transition shadow-lg shadow-indigo-600/20 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Parameter
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Manual Trigger Card -->
            <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-[28px] border border-gray-100 dark:border-gray-700 p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-black text-gray-900 dark:text-white uppercase tracking-wider">
                            Jalankan Engine
                        </h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold leading-relaxed mb-6">
                        Pemicu manual untuk membuat invoice instan bagi seluruh siswa aktif untuk bulan tertentu. Menggunakan parameter penagihan dinamis di atas.
                    </p>
                </div>

                <form method="POST" action="{{ route('invoices.automation.run') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="month" class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2 ml-1">Periode Bulan</label>
                        <input type="month" id="month" name="month" required
                               value="{{ old('month', '2026-06') }}"
                               class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest text-xs py-3.5 rounded-2xl transition shadow-lg shadow-blue-600/20 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.2M7 9a7 7 0 1112.75 6.75"></path>
                        </svg>
                        Jalankan Auto-Generate
                    </button>
                </form>
            </div>

            <!-- Mapping SPP & Services Card (Supports Add and Edit) -->
            <div id="mapping-card" class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-[28px] border border-gray-100 dark:border-gray-700 p-6 shadow-sm transition-all duration-300">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-black text-gray-900 dark:text-white uppercase tracking-wider" 
                            x-text="isEditing ? 'Edit Mapping Biaya Kustom' : 'Mapping Biaya Kustom Siswa'">
                            Mapping Biaya Kustom Siswa
                        </h3>
                    </div>
                    <button type="button" @click="resetForm()" x-show="isEditing" style="display: none;"
                            class="px-3 py-1 bg-red-50 hover:bg-red-100 dark:bg-red-950/20 text-red-600 dark:text-red-400 text-xs font-black uppercase tracking-wider rounded-lg transition">
                        Batal Edit
                    </button>
                </div>

                <form method="POST" action="{{ route('invoices.automation.store-mapping') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    <!-- Hidden mapping ID for edit mode -->
                    <input type="hidden" name="mapping_id" x-model="mappingId">

                    <div>
                        <label for="id_siswa" class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                        <select id="id_siswa" name="id_siswa" required x-model="idSiswa"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Pilih Siswa...</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->nama_siswa }} (Ortu: {{ $student->nama_orang_tua ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_layanan" class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2 ml-1">Komponen Biaya / Layanan</label>
                        <select id="id_layanan" name="id_layanan" required x-model="idLayanan"
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Pilih Komponen...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->nama_layanan }} (Std: Rp {{ number_format($service->harga_standar, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="nominal" class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2 ml-1">Nominal Kustom (Rp)</label>
                        <input type="number" id="nominal" name="nominal" required min="0" placeholder="Contoh: 1500000" x-model="nominal"
                               class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition font-bold">
                    </div>

                    <div>
                        <label for="mapping_month" class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2 ml-1">Berlaku Bulan (Opsional)</label>
                        <input type="month" id="mapping_month" name="month" x-model="month"
                               class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-2xl text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition">
                        <p class="text-[10px] text-gray-400 font-bold mt-1.5 ml-1">Kosongkan jika ingin berlaku permanen untuk siswa tersebut.</p>
                    </div>

                    <div class="flex items-end md:col-span-2">
                        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black uppercase tracking-widest text-xs py-3.5 rounded-2xl transition shadow-lg shadow-amber-500/20 active:scale-95 flex items-center justify-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span x-text="isEditing ? 'Perbarui Mapping Biaya' : 'Simpan Mapping Biaya'">Simpan Mapping Biaya</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table: List of existing custom mappings (Dynamic component listing & Edit trigger) -->
        <div class="bg-white dark:bg-gray-800 rounded-[28px] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden animate-fade-in">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/30">
                <h3 class="text-base font-black text-gray-900 dark:text-white uppercase tracking-wider">
                    Daftar Mapping Biaya Khusus Aktif
                </h3>
                <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-black uppercase tracking-widest rounded-full">
                    {{ count($mappings) }} Mappings
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Siswa</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Komponen Layanan</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest font-black">Nominal Kustom</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Masa Berlaku</th>
                            <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($mappings as $mapping)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">{{ $mapping->siswa->nama_siswa ?? 'N/A' }}</div>
                                    <div class="text-xs font-bold text-gray-400 mt-0.5">Ortu: {{ $mapping->siswa->nama_orang_tua ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest rounded-md border border-blue-100 dark:border-blue-900/40">
                                        {{ $mapping->layanan->nama_layanan ?? 'SPP' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black text-sm text-emerald-600 dark:text-emerald-400">
                                    Rp {{ number_format($mapping->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400">
                                    @if($mapping->bulan && $mapping->tahun)
                                        @php
                                            $months = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                            $monthName = $months[$mapping->bulan] ?? '';
                                        @endphp
                                        <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest rounded-md border border-amber-100 dark:border-amber-900/40">
                                            {{ $monthName }} {{ $mapping->tahun }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-md border border-emerald-100 dark:border-emerald-900/40">
                                            Selamanya
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <!-- Edit Trigger Button -->
                                    <button type="button" @click="editMapping({{ json_encode($mapping) }})" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-950/20 dark:hover:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 transition">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>

                                    <!-- Delete Trigger Form -->
                                    <form method="POST" action="{{ route('invoices.automation.delete-mapping', $mapping) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus mapping nominal kustom ini? Siswa ini akan kembali menggunakan tarif standar.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 transition">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                    Belum ada mapping nominal kustom.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
