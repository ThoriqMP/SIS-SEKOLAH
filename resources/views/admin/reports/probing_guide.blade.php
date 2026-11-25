@extends('layouts.app')
@section('title', 'Panduan Pengisian Raport Probing & PBL')

@section('content')
<div class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'individual' }">
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-900 to-indigo-800 text-white rounded-[32px] p-6 sm:p-10 shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-amber-400/20 via-transparent to-transparent"></div>
            <div class="relative z-10 space-y-3">
                <span class="px-3 py-1 bg-amber-400 text-purple-950 text-[10px] font-black uppercase tracking-widest rounded-full">Interactive Guide</span>
                <h1 class="text-2xl sm:text-4xl font-black tracking-tight">Panduan Pengisian Raport Probing</h1>
                <p class="text-xs sm:text-sm text-purple-200 font-medium max-w-2xl">
                    Pelajari langkah demi langkah cara menginput Nilai Probing secara Mandiri (Individual) maupun secara Cepat (Kolektif/Bulk) untuk melahirkan laporan PBL berjenjang yang akurat.
                </p>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex bg-white dark:bg-gray-800 p-1.5 rounded-2xl border border-gray-150 dark:border-gray-700 shadow-sm max-w-md">
            <button @click="activeTab = 'individual'"
                    :class="activeTab === 'individual' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-purple-600'"
                    class="flex-1 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition duration-200">
                Individual Input
            </button>
            <button @click="activeTab = 'bulk'"
                    :class="activeTab === 'bulk' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-purple-600'"
                    class="flex-1 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition duration-200">
                Bulk / Collective Input
            </button>
        </div>

        {{-- Tab 1: Individual Input Guide --}}
        <div x-show="activeTab === 'individual'" x-transition class="space-y-6">
            {{-- Overview --}}
            <div class="bg-white dark:bg-gray-800 rounded-[28px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6">
                <div class="border-l-4 border-purple-500 pl-4">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wider">A. Alur Pengisian Individual</h3>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Digunakan saat ingin mengisi detail raport probing siswa secara mendalam satu-persatu.</p>
                </div>

                {{-- Steps --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-55/40 dark:bg-gray-900/30 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-3">
                        <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center font-black text-sm">1</div>
                        <h4 class="font-extrabold text-sm text-gray-900 dark:text-white">Pilih Siswa & Kategori</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Buka menu <span class="font-bold text-gray-700 dark:text-gray-300">Input Raport</span>, pilih nama siswa, tentukan periode tahun ajaran aktif, dan pilih Kategori Raport: <span class="font-bold text-purple-600">Project Based Learning / Probing</span>.
                        </p>
                    </div>

                    <div class="bg-gray-55/40 dark:bg-gray-900/30 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-3">
                        <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center font-black text-sm">2</div>
                        <h4 class="font-extrabold text-sm text-gray-900 dark:text-white">Catatan Perkembangan</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Tuliskan dua narasi penting perkembangan siswa pada kolom <span class="font-bold text-amber-600">Catatan Berkembang</span> (aspek yang sangat baik) dan <span class="font-bold text-purple-600">Perlu Dikembangkan</span> (aspek yang butuh stimulasi tambahan).
                        </p>
                    </div>

                    <div class="bg-gray-55/40 dark:bg-gray-900/30 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-3">
                        <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center font-black text-sm">3</div>
                        <h4 class="font-extrabold text-sm text-gray-900 dark:text-white">Matriks 14 Kompetensi</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Isi nilai skala <span class="font-bold text-gray-800 dark:text-gray-200">1 - 10</span> pada 14 Keterampilan Fondasi (seperti Team Building, Self Awareness) yang mencerminkan capaian siswa sepanjang semester berjalan.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Detail Proyek Bulanan --}}
            <div class="bg-white dark:bg-gray-800 rounded-[28px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6">
                <div class="border-l-4 border-amber-500 pl-4">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wider">B. Menambahkan & Menilai Proyek Bulanan</h3>
                </div>

                <div class="space-y-6 text-sm text-gray-600 dark:text-gray-300">
                    <p class="leading-relaxed">
                        Di bagian bawah halaman input individual, terdapat panel khusus untuk menambahkan proyek bulanan (misal: <em>Month of History</em>, <em>Month of Literacy</em>) dengan ketentuan sebagai berikut:
                    </p>

                    <div class="bg-amber-50/50 dark:bg-amber-950/10 p-5 rounded-2xl border border-amber-100 dark:border-amber-900/50 space-y-3">
                        <div class="flex items-center gap-2 text-amber-700 dark:text-amber-400 font-extrabold text-sm uppercase tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Opsi Sumber Template Proyek
                        </div>
                        <ul class="list-disc pl-5 space-y-2 text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                            <li>
                                <strong class="text-gray-900 dark:text-white">Proyek Bulanan (Rekomendasi):</strong> 
                                Opsi ini memuat template proyek bulanan yang telah didaftarkan dan di-link-kan pada menu Proyek Semester oleh admin. Memilih opsi ini akan secara otomatis memuat nama proyek, deskripsi, serta seluruh fase & indikator default.
                            </li>
                            <li>
                                <strong class="text-gray-900 dark:text-white">Proyek Semester:</strong> 
                                Opsi ini memuat data proyek semester langsung (kosong tanpa list template bulanan default di dalamnya).
                            </li>
                            <li>
                                <strong class="text-gray-900 dark:text-white">Tambah Manual (+):</strong> 
                                Menambahkan slot proyek kosong untuk diisi secara bebas jika proyek yang dijalankan tidak ada dalam template.
                            </li>
                        </ul>
                    </div>

                    {{-- Mockup Visual --}}
                    <div class="border border-gray-150 dark:border-gray-700 rounded-2xl p-6 bg-gray-50/50 dark:bg-gray-900/50 space-y-4">
                        <h4 class="font-extrabold text-xs text-gray-500 uppercase tracking-widest">Visualisasi Kontrol Proyek:</h4>
                        
                        <div class="p-5 border border-purple-200 dark:border-purple-800/50 bg-white dark:bg-gray-800 rounded-2xl shadow-sm space-y-4 max-w-lg">
                            <div class="flex justify-between items-center pb-2 border-b border-gray-150 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs">▼</span>
                                    <span class="text-xs font-black text-gray-700 dark:text-gray-300">Proyek #1: <span class="text-purple-600">Month of Literacy</span></span>
                                </div>
                                <span class="text-[10px] text-amber-600 font-bold px-2 py-0.5 bg-amber-50 dark:bg-amber-950/30 rounded border border-amber-200/50">Minimize / Expand Aktif</span>
                            </div>
                            <p class="text-[11px] text-gray-450 leading-relaxed">
                                Gunakan tombol toggle chevron (segitiga kecil) di header proyek untuk memperkecil (minimize) tampilan pengisian jika dirasa layar terlalu penuh. Halaman editor akan secara otomatis melakukan scroll-fokus saat Anda menambahkan proyek baru.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 2: Bulk Input Guide --}}
        <div x-show="activeTab === 'bulk'" x-transition class="space-y-6">
            {{-- Overview --}}
            <div class="bg-white dark:bg-gray-800 rounded-[28px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6">
                <div class="border-l-4 border-purple-500 pl-4">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wider">A. Alur Pengisian Kolektif (Bulk PBL)</h3>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">Sangat direkomendasikan karena guru dapat menginput nilai proyek bulanan yang sama untuk banyak siswa sekaligus.</p>
                </div>

                {{-- Steps --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-55/40 dark:bg-gray-900/30 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-black text-sm">1</div>
                        <h4 class="font-extrabold text-sm text-gray-900 dark:text-white">Buat Draf Kolektif</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Buka menu <span class="font-bold text-gray-700 dark:text-gray-300">Input Kolektif PBL</span>. Pilih Proyek Semester, isikan informasi detail (lokasi, deskripsi), lalu centang siswa-siswa yang mengikuti proyek tersebut.
                        </p>
                    </div>

                    <div class="bg-gray-55/40 dark:bg-gray-900/30 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-black text-sm">2</div>
                        <h4 class="font-extrabold text-sm text-gray-900 dark:text-white">Nilai Indikator & Catatan</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Untuk setiap siswa yang terpilih, klik tombol capaian kualitatif (<span class="font-bold text-purple-600">- SB B C K Br</span>). Jangan lupa isi <span class="font-bold text-amber-600">Catatan Perkembangan khusus</span> per anak di bagian bawah box potensinya.
                        </p>
                    </div>

                    <div class="bg-gray-55/40 dark:bg-gray-900/30 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 space-y-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center font-black text-sm">3</div>
                        <h4 class="font-extrabold text-sm text-gray-900 dark:text-white">Finalisasi Data</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Setelah draf tersimpan, klik tombol <span class="font-bold text-emerald-600">Finalisasi</span>. Sistem akan mendistribusikan nilai, potensi psikologis, foto dokumentasi, dan catatan perkembangan ke masing-masing lembar raport siswa.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Penting: Syarat Finalisasi --}}
            <div class="bg-white dark:bg-gray-800 rounded-[28px] border border-gray-150 dark:border-gray-700 p-6 sm:p-8 shadow-md space-y-6">
                <div class="border-l-4 border-rose-500 pl-4">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wider text-rose-600">Syarat Penting Sebelum Finalisasi Bulk PBL</h3>
                </div>

                <div class="bg-rose-50/50 dark:bg-rose-950/10 p-6 rounded-2xl border border-rose-150 dark:border-rose-900/50 space-y-3 text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                    <p class="font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider">Penting Dipahami:</p>
                    <p>
                        Agar data Input Kolektif (Bulk PBL) dapat didistribusikan dengan sukses ketika tombol <strong>Finalisasi</strong> ditekan:
                    </p>
                    <ol class="list-decimal pl-5 space-y-2 font-medium">
                        <li>
                            Guru <strong class="text-gray-900 dark:text-white">wajib membuat/mendaftarkan segmen raport individual (Probing) terlebih dahulu</strong> untuk masing-masing siswa bersangkutan di menu Input Raport.
                        </li>
                        <li>
                            Jika segmen raport probing individual siswa belum dibuat pada periode tersebut, proses finalisasi bulk akan memunculkan peringatan <em>(Warning)</em> dan melewati pendistribusian nilai untuk siswa tersebut.
                        </li>
                    </ol>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
