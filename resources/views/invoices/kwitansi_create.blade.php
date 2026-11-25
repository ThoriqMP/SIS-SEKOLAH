@extends('layouts.app')

@section('title', 'Terbitkan Kwitansi Baru')

@section('content')
<div class="py-6" x-data="kwitansiForm()">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/80">
            <div class="flex items-center gap-3">
                <a href="{{ route('invoices.kwitansi-index') }}" class="p-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Terbitkan Kwitansi Pembayaran Baru
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Isi formulir penerimaan pembayaran untuk menerbitkan kwitansi resmi Sayyidah School.
                    </p>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl">
                <div class="font-bold text-sm mb-1">Terjadi kesalahan pada input data:</div>
                <ul class="list-disc pl-5 text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('invoices.kwitansi-store') }}" method="POST" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/80 p-6 md:p-8 space-y-6">
            @csrf

            <!-- Header Badge -->
            <div class="p-4 bg-purple-50 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-900/50 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-lg">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider">No. Kwitansi Otomatis</div>
                        <div class="text-base font-mono font-bold text-gray-900 dark:text-white">KWT-{{ $nextNoInvoice }}</div>
                    </div>
                </div>
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                    {{ $schoolProfile->nama_sekolah ?? 'Sayyidah School' }}
                </div>
            </div>

            <!-- Form Fields Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Pilih Siswa -->
                <div class="md:col-span-2">
                    <label for="id_siswa" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">
                        Pilih Siswa / Asesi <span class="text-rose-500">*</span>
                    </label>
                    <select name="id_siswa" id="id_siswa" x-model="studentId" @change="updateStudentInfo()" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}" data-parent="{{ $st->nama_orang_tua }}" data-name="{{ $st->nama_siswa }}" {{ old('id_siswa') == $st->id ? 'selected' : '' }}>
                                {{ $st->nama_siswa }} {{ $st->nama_orang_tua ? ' (Ortu: ' . $st->nama_orang_tua . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal Kwitansi -->
                <div>
                    <label for="tanggal_invoice" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">
                        Tanggal Kwitansi <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal_invoice" id="tanggal_invoice" value="{{ old('tanggal_invoice', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Status Pembayaran -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">
                        Status Kwitansi <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500 font-bold">
                        <option value="paid" {{ old('status', 'paid') === 'paid' ? 'selected' : '' }}>LUNAS (Paid)</option>
                        <option value="partially_paid" {{ old('status') === 'partially_paid' ? 'selected' : '' }}>DIBAYAR SETENGAH (Partially Paid)</option>
                    </select>
                </div>

                <!-- Diterima Dari -->
                <div class="md:col-span-2">
                    <label for="diterima_dari" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">
                        Telah Diterima Dari (Nama Orang Tua / Penyetor)
                    </label>
                    <input type="text" name="diterima_dari" id="diterima_dari" x-model="diterimaDari" 
                           placeholder="Contoh: Bapak Ahmad / Ibu Fatimah" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Untuk Pembayaran -->
                <div class="md:col-span-2">
                    <label for="untuk_pembayaran" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">
                        Untuk Pembayaran (Deskripsi Rincian) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="untuk_pembayaran" id="untuk_pembayaran" value="{{ old('untuk_pembayaran') }}" required
                           placeholder="Contoh: SPP Bulan September 2026 & Biaya Kegiatan Praktikum" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Jumlah Uang (Rp) -->
                <div class="md:col-span-2">
                    <label for="jumlah_uang" class="block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-2">
                        Jumlah Uang (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="jumlah_uang" id="jumlah_uang" x-model="jumlahUang" min="1" step="1000" required
                           placeholder="0" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-lg font-bold text-purple-600 dark:text-purple-400 dark:bg-gray-700 focus:ring-purple-500 focus:border-purple-500">
                    <div class="mt-2 text-xs font-semibold text-gray-500 dark:text-gray-400 italic">
                        Format Tampilan: <span class="font-bold text-gray-900 dark:text-white" x-text="formatRupiah(jumlahUang)"></span>
                    </div>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('invoices.kwitansi-index') }}" class="px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-xs transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-sm shadow-lg shadow-purple-600/25 transition active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Terbitkan Kwitansi</span>
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
function kwitansiForm() {
    return {
        studentId: '{{ old("id_siswa") }}',
        diterimaDari: '{{ old("diterima_dari") }}',
        jumlahUang: '{{ old("jumlah_uang", 0) }}',

        updateStudentInfo() {
            const select = document.getElementById('id_siswa');
            const selectedOption = select.options[select.selectedIndex];
            const parentName = selectedOption.getAttribute('data-parent');
            const studentName = selectedOption.getAttribute('data-name');
            if (parentName) {
                this.diterimaDari = parentName + ' (Orang Tua ' + studentName + ')';
            } else if (studentName) {
                this.diterimaDari = studentName;
            }
        },

        formatRupiah(angka) {
            const val = parseFloat(angka) || 0;
            return 'Rp ' + val.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 0});
        }
    }
}
</script>
@endpush
@endsection
