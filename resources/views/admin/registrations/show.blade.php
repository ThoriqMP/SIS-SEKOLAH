@extends('layouts.app')

@section('title', 'Detail & Validasi Pendaftaran - ' . $registration->nomor_pendaftaran)

@section('content')
@php
    $calc = $registration->calculateFinalFees();
    $waNum = preg_replace('/[^0-9]/', '', $registration->no_telpon_wa ?? '');
    if (str_starts_with($waNum, '0')) {
        $waNum = '62' . substr($waNum, 1);
    }
    $studentName = $registration->nama_lengkap;
    $nomorReg = $registration->nomor_pendaftaran;
    $jenjang = $registration->jenjang ?? 'Sekolah';
    
    $waApprovedText = urlencode("Halo Ayah/Bunda {$studentName},\n\nAlhamdulillah, pendaftaran ananda *{$studentName}* (No. Registrasi: {$nomorReg}) di Sayyidah School jenjang *{$jenjang}* telah *DISETUJUI*.\n\nAkun siswa ananda telah aktif dengan email {$registration->email}. Kami mengucapkan selamat bergabung! Untuk informasi orientasi dan berkas lanjutan, silakan hubungi tim kami.");
    $waGeneralText = urlencode("Halo Ayah/Bunda {$studentName},\n\nKami dari panitia PPDB Sayyidah School terkait berkas pendaftaran ananda *{$studentName}* (No. Registrasi: {$nomorReg}). Apakah ada waktu luang untuk konfirmasi berkas pendaftaran? Terima kasih.");
@endphp

<style>
    .compact-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .compact-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .dark .compact-scrollbar::-webkit-scrollbar-thumb {
        background: #4b5563;
    }
</style>

<!-- Eye-Level Vertically Centered Executive Dashboard Console -->
<div class="w-full min-h-[calc(100vh-2rem)] flex flex-col justify-center py-4 sm:py-6 px-3 sm:px-6 lg:px-8 pb-28 lg:pb-6" 
     x-data="adminDecision({
        currentStatus: '{{ $registration->status }}',
        hasKeringanan: {{ $registration->mengajukan_keringanan ? 'true' : 'false' }},
        requestedDiscount: {{ (int)($registration->persentase_keringanan_diajukan ?? 0) }},
        approvedDiscount: {{ (int)($registration->persentase_keringanan_disetujui ?? ($registration->persentase_keringanan_diajukan ?? 0)) }},
        keringananStatus: '{{ $registration->status_keringanan ?? 'pending' }}',
        baseUangGedung: {{ (int)($registration->biaya_uang_gedung ?? 0) }},
        baseSpp: {{ (int)($registration->biaya_spp ?? 0) }},
        baseKegiatan: {{ (int)($registration->biaya_kegiatan_semester ?? 0) }},
        studentName: '{{ addslashes($registration->nama_lengkap) }}',
        studentEmail: '{{ addslashes($registration->email) }}',
        initialCatatanKeringanan: '{{ addslashes(old('catatan_keringanan_admin', $registration->catatan_keringanan_admin ?? '')) }}',
        initialCatatanAdmin: '{{ addslashes(old('catatan_admin', $registration->catatan_admin ?? '')) }}'
     })">
    
    <div class="max-w-7xl mx-auto w-full my-auto space-y-3 sm:space-y-3.5">
        
        <!-- TOP CONTROL BAR: Identitas, Status & Quick CTA (Posisi Eye-Level Seimbang) -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-3.5 sm:p-4 shadow-sm shrink-0">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                
                <!-- Left: Back Navigation, Avatar, Student Name, Badges & Meta -->
                <div class="flex items-center gap-3 min-w-0">
                    <a href="{{ route('admin.registrations.index') }}" 
                       class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-gray-750 dark:hover:bg-gray-700 text-slate-500 hover:text-purple-600 transition flex items-center justify-center shrink-0" 
                       title="Kembali ke Daftar Pendaftaran">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </a>

                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 flex items-center justify-center font-black text-base shrink-0 border border-purple-200/70 dark:border-purple-800/60">
                        {{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-base sm:text-lg font-black text-slate-900 dark:text-white truncate leading-tight">
                                {{ $registration->nama_lengkap }}
                            </h1>

                            <!-- Status Badge -->
                            @if ($registration->status === 'pending')
                                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 rounded-md">
                                    Menunggu Review
                                </span>
                            @elseif ($registration->status === 'reviewed')
                                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 rounded-md">
                                    Sedang Direview
                                </span>
                            @elseif ($registration->status === 'approved')
                                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-md">
                                    Disetujui
                                </span>
                            @elseif ($registration->status === 'rejected')
                                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-md">
                                    Ditolak
                                </span>
                            @endif

                            <span class="text-[10px] font-bold text-purple-700 bg-purple-50 dark:bg-purple-950/40 border border-purple-200 px-2 py-0.5 rounded-md">
                                {{ $registration->jenjang ?? 'SMP' }}
                            </span>

                            <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-gray-750 px-2 py-0.5 rounded-md uppercase">
                                {{ $registration->skema_belajar }}
                            </span>

                            @if ($registration->mengajukan_keringanan)
                                <span class="text-[10px] font-bold text-amber-800 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 px-2 py-0.5 rounded-md">
                                    Keringanan: {{ $registration->persentase_keringanan_diajukan }}%
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-x-2 text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ $registration->nomor_pendaftaran }}</span>
                            <span>•</span>
                            <span>{{ $registration->jenis_pendaftar === 'pindahan' ? 'Pindahan' : 'Murid Baru' }} (TA {{ $registration->tahun_pelajaran ?? '-' }})</span>
                            <span class="hidden md:inline">•</span>
                            <span class="hidden md:inline">Masuk: {{ $registration->submitted_at ? $registration->submitted_at->format('d/m/Y H:i') : $registration->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Action Buttons -->
                <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
                    @if($waNum)
                        <a href="https://wa.me/{{ $waNum }}?text={{ $registration->status === 'approved' ? $waApprovedText : $waGeneralText }}" 
                           target="_blank"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs min-h-[36px]">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>Hubungi WA</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.registrations.pdf', $registration) }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-gray-750 dark:hover:bg-gray-700 text-slate-700 dark:text-slate-200 px-3.5 py-2 rounded-xl font-bold transition text-xs min-h-[36px]">
                        <i class="fa-solid fa-print text-xs text-slate-400"></i>
                        <span>Cetak PDF</span>
                    </a>

                    @if($registration->user && $registration->user->siswa)
                        <a href="{{ route('students.show', $registration->user->siswa->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition shadow-xs min-h-[36px]">
                            <i class="fa-solid fa-user-graduate text-xs"></i>
                            <span>Profil Siswa</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alert Notifications -->
        @if (session('success'))
            <div class="px-4 py-2.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/80 rounded-xl flex items-center gap-2.5 text-xs font-bold text-emerald-900 dark:text-emerald-200 shrink-0">
                <span class="text-emerald-600 font-bold">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="px-4 py-2.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/80 rounded-xl flex items-center gap-2.5 text-xs font-bold text-rose-900 dark:text-rose-200 shrink-0">
                <span class="text-rose-600 font-bold">!</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- BALANCED CONSOLE LAYOUT: Left 2x2 Dossier Grid (8 Cols) + Right Validation Console (4 Cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-stretch">
            
            <!-- LEFT 8 COLUMNS: 2x2 Balanced Dossier Grid -->
            <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                
                <!-- TOP-LEFT (Row 1, Col 1): Biodata Calon Siswa -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 pb-2.5 mb-3 border-b border-slate-100 dark:border-gray-700">
                            <i class="fa-solid fa-user-graduate text-purple-600 text-xs"></i>
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Biodata Calon Siswa</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-x-3 gap-y-2.5 text-xs">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Nama Lengkap</span>
                                <span class="font-bold text-slate-900 dark:text-white truncate block">{{ $registration->nama_lengkap }}</span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Panggilan &amp; Gender</span>
                                <span class="font-bold text-slate-800 dark:text-white truncate block">{{ $registration->nama_panggilan ?: '-' }} ({{ $registration->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }})</span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tempat &amp; Tgl Lahir</span>
                                <span class="font-bold text-slate-800 dark:text-white block truncate">
                                    {{ $registration->tempat_lahir ?: '-' }}, {{ $registration->tanggal_lahir ? $registration->tanggal_lahir->format('d/m/Y') : '-' }}
                                    @if($registration->tanggal_lahir)
                                        <span class="text-slate-400 font-normal">({{ \Carbon\Carbon::parse($registration->tanggal_lahir)->age }} th)</span>
                                    @endif
                                </span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Anak Ke / Agama</span>
                                <span class="font-bold text-slate-800 dark:text-white">
                                    Ke-{{ $registration->anak_ke ?: '1' }} • {{ $registration->agama ?: '-' }}
                                </span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Fisik &amp; Gol. Darah</span>
                                <span class="font-bold text-slate-800 dark:text-white">
                                    {{ $registration->berat_badan ? $registration->berat_badan . 'kg' : '-' }} • 
                                    {{ $registration->tinggi_badan ? $registration->tinggi_badan . 'cm' : '-' }} • 
                                    Gol {{ $registration->golongan_darah ?: '-' }}
                                </span>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">No. WhatsApp</span>
                                <span class="font-mono font-bold text-slate-800 dark:text-white">{{ $registration->no_telpon_wa ?: '-' }}</span>
                            </div>

                            <div class="col-span-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Email Calon Siswa</span>
                                <span class="font-mono text-purple-700 dark:text-purple-300 font-bold truncate block">{{ $registration->email ?: '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2.5 mt-3 border-t border-slate-100 dark:border-gray-700 text-xs">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Alamat Rumah</span>
                        <p class="text-slate-700 dark:text-slate-300 leading-snug line-clamp-2" title="{{ $registration->alamat_rumah }}">
                            {{ $registration->alamat_rumah ?: 'Alamat tidak dicantumkan.' }}
                            @if($registration->tinggal_dengan)
                                <span class="text-slate-400 block mt-0.5">Tinggal bersama: {{ $registration->tinggal_dengan }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- TOP-RIGHT (Row 1, Col 2): Skema Belajar & Rincian Biaya (Live Calculator) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-2.5 mb-3 border-b border-slate-100 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-receipt text-purple-600 text-xs"></i>
                                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Skema Belajar &amp; Biaya</h3>
                            </div>
                            <span class="text-[10px] font-bold text-purple-700 bg-purple-50 dark:bg-purple-950/50 px-2 py-0.5 rounded">
                                Metode: {{ ucfirst($registration->skema_belajar) }}
                            </span>
                        </div>

                        <!-- 3 Live Biaya Metrics -->
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <div class="p-2.5 bg-slate-50 dark:bg-gray-750/50 rounded-xl border border-slate-200/70 dark:border-gray-700 text-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Uang Gedung</span>
                                <div class="text-xs sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5" x-text="formatRupiah(calcFee(baseUangGedung))"></div>
                                <span x-show="activeDiscount > 0" class="text-[9px] text-emerald-600 font-bold block" x-text="'-' + activeDiscount + '%'"></span>
                                <span class="text-[8.5px] text-slate-400 block mt-0.5">1x registrasi</span>
                            </div>

                            <div class="p-2.5 bg-slate-50 dark:bg-gray-750/50 rounded-xl border border-slate-200/70 dark:border-gray-700 text-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">SPP Bulanan</span>
                                <div class="text-xs sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5" x-text="formatRupiah(calcFee(baseSpp))"></div>
                                <span x-show="activeDiscount > 0" class="text-[9px] text-emerald-600 font-bold block" x-text="'-' + activeDiscount + '%'"></span>
                                <span class="text-[8.5px] text-slate-400 block mt-0.5">Iuran rutin</span>
                            </div>

                            <div class="p-2.5 bg-slate-50 dark:bg-gray-750/50 rounded-xl border border-slate-200/70 dark:border-gray-700 text-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Kegiatan</span>
                                <div class="text-xs sm:text-sm font-black text-slate-900 dark:text-white font-mono mt-0.5" x-text="formatRupiah(calcFee(baseKegiatan))"></div>
                                <span x-show="activeDiscount > 0" class="text-[9px] text-emerald-600 font-bold block" x-text="'-' + activeDiscount + '%'"></span>
                                <span class="text-[8.5px] text-slate-400 block mt-0.5">Per semester</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Estimasi Investasi Awal -->
                    <div class="p-3 bg-purple-50/70 dark:bg-purple-950/30 rounded-xl border border-purple-100 dark:border-purple-800/40 flex items-center justify-between text-xs">
                        <span class="font-bold text-purple-950 dark:text-purple-200 text-xs">Total Investasi Awal Masuk:</span>
                        <strong class="font-mono text-sm font-black text-purple-700 dark:text-purple-300" x-text="formatRupiah(calcTotal())"></strong>
                    </div>
                </div>

                <!-- BOTTOM-LEFT (Row 2, Col 1): Data Orang Tua / Wali -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 pb-2.5 mb-3 border-b border-slate-100 dark:border-gray-700">
                            <i class="fa-solid fa-users text-purple-600 text-xs"></i>
                            <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Data Orang Tua / Wali</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <!-- Ayah -->
                            <div class="p-3 bg-slate-50/80 dark:bg-gray-750/50 rounded-xl border border-slate-200/60 dark:border-gray-700 space-y-1">
                                <span class="text-[9.5px] font-bold text-slate-500 uppercase tracking-wider block">Ayah Kandung</span>
                                <div class="font-bold text-slate-900 dark:text-white truncate text-xs sm:text-sm">{{ $registration->nama_ayah ?: '-' }}</div>
                                <div class="text-xs text-slate-600 dark:text-slate-300 font-mono truncate">{{ $registration->hp_ayah ?: '-' }}</div>
                                <div class="text-[10.5px] text-slate-500 truncate">{{ $registration->pekerjaan_ayah ?: '-' }} • {{ $registration->pendidikan_ayah ?: '-' }}</div>
                            </div>

                            <!-- Ibu -->
                            <div class="p-3 bg-slate-50/80 dark:bg-gray-750/50 rounded-xl border border-slate-200/60 dark:border-gray-700 space-y-1">
                                <span class="text-[9.5px] font-bold text-slate-500 uppercase tracking-wider block">Ibu Kandung</span>
                                <div class="font-bold text-slate-900 dark:text-white truncate text-xs sm:text-sm">{{ $registration->nama_ibu ?: '-' }}</div>
                                <div class="text-xs text-slate-600 dark:text-slate-300 font-mono truncate">{{ $registration->hp_ibu ?: '-' }}</div>
                                <div class="text-[10.5px] text-slate-500 truncate">{{ $registration->pekerjaan_ibu ?: '-' }} • {{ $registration->pendidikan_ibu ?: '-' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($registration->hp_ayah || $registration->hp_ibu)
                        <div class="pt-2.5 mt-3 border-t border-slate-100 dark:border-gray-700 flex items-center justify-between text-xs text-slate-400">
                            <span>Kontak Terverifikasi</span>
                            <span class="text-emerald-600 font-bold flex items-center gap-1">
                                <i class="fa-brands fa-whatsapp text-xs"></i> Siap Hubungi
                            </span>
                        </div>
                    @endif
                </div>

                <!-- BOTTOM-RIGHT (Row 2, Col 2): Berkas Pengajuan Keringanan ATAU Riwayat Sekolah Asal -->
                @if ($registration->mengajukan_keringanan)
                    <div class="bg-amber-50/40 dark:bg-gray-800 rounded-2xl border border-amber-200/70 dark:border-amber-900/40 p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-2.5 mb-3 border-b border-amber-200/60 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-hand-holding-dollar text-amber-700 text-xs"></i>
                                    <h3 class="text-xs font-black text-amber-950 dark:text-amber-300 uppercase tracking-wider">Berkas Pengajuan Keringanan</h3>
                                </div>
                                <span class="text-[9.5px] font-black px-2 py-0.5 bg-amber-100 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 rounded-md">
                                    Diajukan: {{ $registration->persentase_keringanan_diajukan }}%
                                </span>
                            </div>

                            @if ($registration->financialData)
                                <div class="grid grid-cols-2 gap-2.5 text-xs mb-2.5">
                                    <div class="p-2.5 bg-white dark:bg-gray-750/70 rounded-xl border border-amber-100 dark:border-gray-700">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Pekerjaan &amp; Instansi Ortu</span>
                                        <div class="font-bold text-slate-800 dark:text-white truncate text-xs">
                                            {{ $registration->financialData->ayah_pekerjaan ?: '-' }} / {{ $registration->financialData->ibu_pekerjaan ?: '-' }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 truncate">{{ $registration->financialData->ayah_tempat_kerja ?: '-' }}</div>
                                    </div>

                                    <div class="p-2.5 bg-white dark:bg-gray-750/70 rounded-xl border border-amber-100 dark:border-gray-700">
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Penghasilan Gabungan</span>
                                        <div class="font-black text-slate-900 dark:text-white text-xs font-mono">
                                            {{ $registration->financialData->penghasilan_gabungan ? 'Rp ' . number_format($registration->financialData->penghasilan_gabungan, 0, ',', '.') : '-' }}
                                        </div>
                                        <div class="text-[10.5px] text-slate-500">
                                            {{ $registration->financialData->jumlah_tanggungan }} Org • {{ $registration->financialData->daya_listrik_watt }} VA
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Aset Kendaraan Dimiliki -->
                        <div class="pt-2 border-t border-amber-200/60 dark:border-gray-700 text-xs">
                            <span class="text-[9.5px] font-bold text-amber-950 dark:text-amber-300 uppercase tracking-wider block mb-1">
                                Aset Kendaraan Keluarga
                            </span>
                            @if ($registration->vehicleAssets && $registration->vehicleAssets->count() > 0)
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($registration->vehicleAssets as $veh)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white dark:bg-gray-750 rounded-lg border border-amber-100 dark:border-gray-700 text-[11px]">
                                            <i class="fa-solid {{ strtolower($veh->jenis_kendaraan) === 'mobil' ? 'fa-car' : 'fa-motorcycle' }} text-slate-400"></i>
                                            <strong class="text-slate-800 dark:text-slate-200">{{ $veh->jenis_kendaraan }} {{ $veh->merk_tipe ? '- ' . $veh->merk_tipe : '' }}</strong>
                                            <span class="text-slate-400">{{ $veh->tahun ? '(' . $veh->tahun . ')' : '' }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-[11px] text-slate-400 italic">Tidak ada catatan kendaraan.</span>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Jika tidak mengajukan keringanan: Tampilkan Riwayat Sekolah & Persyaratan -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-4 sm:p-5 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 pb-2.5 mb-3 border-b border-slate-100 dark:border-gray-700">
                                <i class="fa-solid fa-school text-purple-600 text-xs"></i>
                                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Sekolah Asal &amp; Riwayat Belajar</h3>
                            </div>

                            <div class="space-y-2 text-xs">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Sekolah Sebelumnya</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $registration->nama_sekolah_sebelumnya ?: ($registration->asal_sekolah ?: '-') }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">NISN Siswa</span>
                                        <span class="font-mono text-slate-800 dark:text-white">{{ $registration->nisn ?: '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Status Sekolah</span>
                                        <span class="text-slate-800 dark:text-white">{{ $registration->status_sekolah ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2.5 border-t border-slate-100 dark:border-gray-700 text-xs text-slate-400">
                            Tarif Reguler (Tanpa Permohonan Keringanan)
                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT 4 COLUMNS: PUSAT KEPUTUSAN VALIDASI PPDB (Full-Height Cohesive Console) -->
            <div class="lg:col-span-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200/80 dark:border-gray-700 p-4 sm:p-5 shadow-xs h-full flex flex-col justify-between">
                    
                    <!-- KONDISI JIKA STATUS SUDAH DISETUJUI -->
                    @if ($registration->status === 'approved')
                        <div class="space-y-4">
                            <!-- Header & Banner Sukses -->
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 rounded-2xl border border-emerald-200/80 dark:border-emerald-800 text-center space-y-1.5">
                                <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center text-lg mx-auto shadow-xs font-bold">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <h3 class="text-sm font-black text-emerald-950 dark:text-emerald-200 uppercase tracking-wider">
                                    Pendaftaran Telah Disetujui
                                </h3>
                                <p class="text-xs text-emerald-800 dark:text-emerald-300">
                                    Siswa resmi diterima. Akun user siswa telah aktif di database sistem.
                                </p>
                            </div>

                            <!-- Detail Ringkasan Akun -->
                            <div class="p-3.5 bg-slate-50 dark:bg-gray-750/50 rounded-xl border border-slate-200/70 dark:border-gray-700 space-y-2 text-xs">
                                <div class="flex justify-between pb-1.5 border-b border-slate-200/60 dark:border-gray-700">
                                    <span class="text-slate-500">Email Akun:</span>
                                    <strong class="font-mono text-purple-700 dark:text-purple-300">{{ $registration->email }}</strong>
                                </div>
                                <div class="flex justify-between pb-1.5 border-b border-slate-200/60 dark:border-gray-700">
                                    <span class="text-slate-500">Diskon Keringanan:</span>
                                    <strong class="text-emerald-600">{{ $registration->persentase_keringanan_disetujui ?? 0 }}% Disetujui</strong>
                                </div>
                                <div class="flex justify-between pb-1.5 border-b border-slate-200/60 dark:border-gray-700">
                                    <span class="text-slate-500">Divalidasi Oleh:</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $registration->reviewer ? $registration->reviewer->name : 'Admin' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Waktu Validasi:</span>
                                    <span class="font-mono text-slate-700 dark:text-slate-300">{{ $registration->reviewed_at ? $registration->reviewed_at->format('d/m/Y H:i') : '-' }}</span>
                                </div>
                            </div>

                            <!-- Tombol Aksi Pasca Persetujuan -->
                            <div class="space-y-2 pt-1">
                                @if($registration->user && $registration->user->siswa)
                                    <a href="{{ route('students.show', $registration->user->siswa->id) }}" 
                                       class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider py-3 px-3 rounded-xl transition flex items-center justify-center gap-2 shadow-xs min-h-[42px]">
                                        <i class="fa-solid fa-user-graduate"></i>
                                        <span>Buka Profil di Student List</span>
                                    </a>
                                @endif

                                @if($waNum)
                                    <a href="https://wa.me/{{ $waNum }}?text={{ $waApprovedText }}" target="_blank"
                                       class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-3 rounded-xl transition flex items-center justify-center gap-2 shadow-xs min-h-[40px]">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>Kirim Konfirmasi WA</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Riwayat -->
                        <div class="pt-3 border-t border-slate-100 dark:border-gray-700 text-xs text-slate-500">
                            Pendaftaran Diajukan: <strong class="text-slate-800 dark:text-slate-200">{{ $registration->submitted_at ? $registration->submitted_at->format('d/m/Y H:i') : $registration->created_at->format('d/m/Y H:i') }}</strong>
                        </div>

                    <!-- KONDISI JIKA STATUS BELUM DISETUJUI (FORM KONSOL AKTIF) -->
                    @else
                        <!-- TOP KONSOL: Header & 3 Tombol Aksi Utama -->
                        <div>
                            <div class="flex items-center justify-between pb-2.5 mb-3 border-b border-slate-100 dark:border-gray-700">
                                <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fa-solid fa-gavel text-purple-600 text-xs"></i>
                                    <span>Pusat Keputusan Validasi</span>
                                </h3>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Pilih Aksi</span>
                            </div>

                            <!-- 3 Tombol Aksi (Tegas & Kontras) -->
                            <div class="grid grid-cols-3 gap-1.5 mb-3">
                                <button type="button" @click="decision = 'approved'"
                                        class="p-2 rounded-xl border text-center transition flex flex-col items-center justify-center gap-1 cursor-pointer min-h-[58px]"
                                        :class="decision === 'approved' 
                                            ? 'bg-emerald-50 dark:bg-emerald-950/50 border-emerald-500 ring-2 ring-emerald-500/20 text-emerald-900 dark:text-emerald-200' 
                                            : 'bg-white dark:bg-gray-750 border-slate-200 dark:border-gray-650 text-slate-600 dark:text-slate-300'">
                                    <i class="fa-solid fa-circle-check text-sm" :class="decision === 'approved' ? 'text-emerald-600' : 'text-slate-400'"></i>
                                    <span class="text-[10.5px] font-black leading-tight">Setujui Siswa</span>
                                </button>

                                <button type="button" @click="decision = 'reviewed'"
                                        class="p-2 rounded-xl border text-center transition flex flex-col items-center justify-center gap-1 cursor-pointer min-h-[58px]"
                                        :class="decision === 'reviewed' 
                                            ? 'bg-blue-50 dark:bg-blue-950/50 border-blue-500 ring-2 ring-blue-500/20 text-blue-900 dark:text-blue-200' 
                                            : 'bg-white dark:bg-gray-750 border-slate-200 dark:border-gray-650 text-slate-600 dark:text-slate-300'">
                                    <i class="fa-solid fa-clock text-sm" :class="decision === 'reviewed' ? 'text-blue-600' : 'text-slate-400'"></i>
                                    <span class="text-[10.5px] font-black leading-tight">Sedang Ditinjau</span>
                                </button>

                                <button type="button" @click="decision = 'rejected'"
                                        class="p-2 rounded-xl border text-center transition flex flex-col items-center justify-center gap-1 cursor-pointer min-h-[58px]"
                                        :class="decision === 'rejected' 
                                            ? 'bg-rose-50 dark:bg-rose-950/50 border-rose-500 ring-2 ring-rose-500/20 text-rose-900 dark:text-rose-200' 
                                            : 'bg-white dark:bg-gray-750 border-slate-200 dark:border-gray-650 text-slate-600 dark:text-slate-300'">
                                    <i class="fa-solid fa-circle-xmark text-sm" :class="decision === 'rejected' ? 'text-rose-600' : 'text-slate-400'"></i>
                                    <span class="text-[10.5px] font-black leading-tight">Tolak Berkas</span>
                                </button>
                            </div>

                            <!-- MIDDLE KONSOL: Detail Berdasarkan Aksi yang Dipilih -->
                            <div class="space-y-3">
                                
                                <!-- Jika Memilih: Setujui Siswa -->
                                <div x-show="decision === 'approved'" x-transition class="space-y-2.5">
                                    @if ($registration->mengajukan_keringanan)
                                        <div class="p-3 bg-purple-50/70 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-800/60 rounded-xl space-y-1.5">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="font-bold text-purple-950 dark:text-purple-200 uppercase tracking-wider">Keputusan Keringanan</span>
                                                <span class="font-mono text-purple-700 font-bold" x-text="activeDiscount + '% Disetujui'"></span>
                                            </div>

                                            <div class="space-y-1.5 text-xs">
                                                <label class="p-2 rounded-lg border bg-white dark:bg-gray-800 flex items-center justify-between cursor-pointer text-xs"
                                                       :class="keringananChoice === 'requested' ? 'border-purple-600 ring-1 ring-purple-600 font-bold text-purple-950 dark:text-purple-200' : 'border-slate-200 dark:border-gray-700 text-slate-700'">
                                                    <div class="flex items-center gap-2">
                                                        <input type="radio" name="radio_keringanan" value="requested" x-model="keringananChoice" class="text-purple-600">
                                                        <span>Setujui Penuh ({{ $registration->persentase_keringanan_diajukan }}%)</span>
                                                    </div>
                                                    <span class="text-[9.5px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded">Rekomendasi</span>
                                                </label>

                                                <label class="p-2 rounded-lg border bg-white dark:bg-gray-800 flex items-center justify-between cursor-pointer text-xs"
                                                       :class="keringananChoice === 'custom' ? 'border-purple-600 ring-1 ring-purple-600 font-bold text-purple-950 dark:text-purple-200' : 'border-slate-200 dark:border-gray-700 text-slate-700'">
                                                    <div class="flex items-center gap-2">
                                                        <input type="radio" name="radio_keringanan" value="custom" x-model="keringananChoice" class="text-purple-600">
                                                        <span>Sesuaikan Diskon</span>
                                                    </div>
                                                    <span class="text-xs font-mono text-purple-700 font-bold" x-show="keringananChoice === 'custom'" x-text="customDiscountPct + '%'"></span>
                                                </label>

                                                <div x-show="keringananChoice === 'custom'" class="flex gap-1.5 pt-0.5">
                                                    <template x-for="pct in [10, 15, 20, 25, 35]" :key="pct">
                                                        <button type="button" @click="customDiscountPct = pct" 
                                                                class="flex-1 py-1 text-[10px] font-bold rounded-lg border transition text-center"
                                                                :class="customDiscountPct == pct ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-slate-600 border-slate-200'"
                                                                x-text="pct + '%'"></button>
                                                    </template>
                                                </div>

                                                <label class="p-2 rounded-lg border bg-white dark:bg-gray-800 flex items-center justify-between cursor-pointer text-xs"
                                                       :class="keringananChoice === 'reject' ? 'border-amber-600 ring-1 ring-amber-600 font-bold text-amber-950 dark:text-amber-200' : 'border-slate-200 dark:border-gray-700 text-slate-700'">
                                                    <div class="flex items-center gap-2">
                                                        <input type="radio" name="radio_keringanan" value="reject" x-model="keringananChoice" class="text-amber-600">
                                                        <span>Tolak Keringanan (Tarif Reguler / 0%)</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl border border-emerald-200 text-xs text-emerald-900 dark:text-emerald-200 flex items-center gap-2">
                                        <i class="fa-solid fa-shield-check text-emerald-600 text-base shrink-0"></i>
                                        <span>Akun user siswa otomatis aktif dengan email <strong class="font-mono">{{ $registration->email }}</strong>.</span>
                                    </div>
                                </div>

                                <!-- Jika Memilih: Sedang Ditinjau -->
                                <div x-show="decision === 'reviewed'" x-transition class="space-y-2">
                                    <div class="p-3 bg-blue-50 dark:bg-blue-950/40 rounded-xl border border-blue-200 text-xs text-blue-900 dark:text-blue-200 flex items-start gap-2">
                                        <i class="fa-solid fa-clock text-blue-600 text-sm mt-0.5 shrink-0"></i>
                                        <span>Status ditandai 'Sedang Direview'. Berkas dalam pemeriksaan atau jadwal wawancara/observasi.</span>
                                    </div>
                                </div>

                                <!-- Jika Memilih: Tolak Berkas -->
                                <div x-show="decision === 'rejected'" x-transition class="space-y-2">
                                    <div class="p-3 bg-rose-50 dark:bg-rose-950/40 rounded-xl border border-rose-200 text-xs text-rose-900 dark:text-rose-200 flex items-start gap-2">
                                        <i class="fa-solid fa-ban text-rose-600 text-sm mt-0.5 shrink-0"></i>
                                        <span>Pendaftaran dibatalkan. Akun user siswa tidak akan dibuat pada sistem.</span>
                                    </div>

                                    <div class="flex flex-wrap gap-1.5">
                                        <button type="button" @click="catatanAdmin = 'Kuota pendaftaran jenjang yang dipilih telah penuh.'"
                                                class="text-[10px] px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100">
                                            Kuota Penuh
                                        </button>
                                        <button type="button" @click="catatanAdmin = 'Persyaratan dokumen administrasi belum lengkap.'"
                                                class="text-[10px] px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100">
                                            Berkas Kurang
                                        </button>
                                        <button type="button" @click="catatanAdmin = 'Hasil observasi belum memenuhi kriteria kualifikasi.'"
                                                class="text-[10px] px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100">
                                            Kriteria Belum Cukup
                                        </button>
                                    </div>
                                </div>

                                <!-- Input Catatan Admin -->
                                <div>
                                    <label class="block text-[10.5px] font-bold text-slate-500 uppercase tracking-wider mb-1"
                                           x-text="decision === 'rejected' ? 'Alasan Penolakan (Wajib Diisi)' : 'Catatan Admin Pendaftaran (Opsional)'">
                                    </label>
                                    <textarea x-model="catatanAdmin" rows="2" 
                                              class="w-full text-xs py-2 px-3 rounded-xl bg-slate-50 dark:bg-gray-750 border border-slate-200 dark:border-gray-650 text-slate-900 dark:text-white"
                                              :placeholder="decision === 'rejected' ? 'Tuliskan alasan penolakan...' : 'Tambahkan catatan verifikasi sekolah...'"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- BOTTOM KONSOL: Tombol CTA & Metadata Terintegrasi -->
                        <div class="pt-3 border-t border-slate-100 dark:border-gray-700 space-y-2">
                            <button type="button" @click="triggerSubmit"
                                    class="w-full text-white font-black text-xs uppercase tracking-wider py-3.5 px-3 rounded-xl transition min-h-[44px] flex items-center justify-center gap-2 shadow-sm active:scale-95 duration-150 cursor-pointer"
                                    :class="{
                                        'bg-emerald-600 hover:bg-emerald-700': decision === 'approved',
                                        'bg-blue-600 hover:bg-blue-700': decision === 'reviewed',
                                        'bg-rose-600 hover:bg-rose-700': decision === 'rejected'
                                    }">
                                <i class="fa-solid" :class="{
                                    'fa-user-check': decision === 'approved',
                                    'fa-clock': decision === 'reviewed',
                                    'fa-ban': decision === 'rejected'
                                }"></i>
                                <span x-text="decision === 'approved' ? 'Setujui & Terbitkan Akun Siswa' : (decision === 'reviewed' ? 'Simpan Status Ditinjau' : 'Konfirmasi Tolak Pendaftaran')"></span>
                            </button>

                            <!-- Riwayat Log Ringkas -->
                            <div class="flex items-center justify-between text-[10.5px] text-slate-400 px-1">
                                <span>Pendaftaran Masuk:</span>
                                <span class="font-mono text-slate-600 dark:text-slate-300 font-bold">
                                    {{ $registration->submitted_at ? $registration->submitted_at->format('d/m/Y H:i') : $registration->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>

    </div>

    <!-- Hidden Form Container for Deterministic Submission -->
    <form x-ref="statusForm" action="{{ route('admin.registrations.update-status', $registration) }}" method="POST" class="hidden">
        @csrf
        @method('PATCH')
        
        <input type="hidden" name="status" :value="decision">
        
        <template x-if="hasKeringanan">
            <div>
                <input type="hidden" name="status_keringanan" :value="statusKeringananValue">
                <input type="hidden" name="persentase_keringanan_disetujui" :value="activeDiscount">
                <input type="hidden" name="catatan_keringanan_admin" :value="catatanKeringanan">
            </div>
        </template>
        
        <input type="hidden" name="catatan_admin" :value="catatanAdmin">
    </form>

    <!-- Modal Konfirmasi Cepat -->
    <div x-show="showConfirmModal" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-sm w-full p-5 shadow-2xl border border-slate-200 dark:border-gray-700 text-center space-y-3"
             @click.away="showConfirmModal = false">
            
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl mx-auto shadow-xs"
                 :class="{
                     'bg-emerald-50 text-emerald-600 border border-emerald-200': decision === 'approved',
                     'bg-blue-50 text-blue-600 border border-blue-200': decision === 'reviewed',
                     'bg-rose-50 text-rose-600 border border-rose-200': decision === 'rejected'
                 }">
                <i class="fa-solid" :class="{
                    'fa-user-check': decision === 'approved',
                    'fa-clock': decision === 'reviewed',
                    'fa-ban': decision === 'rejected'
                }"></i>
            </div>

            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white"
                    x-text="decision === 'approved' ? 'Setujui Pendaftaran Siswa?' : (decision === 'reviewed' ? 'Tandai Sedang Ditinjau?' : 'Tolak Pendaftaran Ini?')">
                </h3>
                <p class="text-xs text-slate-500 mt-1"
                   x-text="decision === 'approved' 
                        ? 'Akun user siswa akan langsung dibuat aktif dengan email ' + studentEmail + '.' 
                        : (decision === 'reviewed' ? 'Status berkas akan diperbarui menjadi Sedang Direview.' : 'Pendaftaran akan berstatus Ditolak.')">
                </p>
            </div>

            <div class="flex gap-2 pt-1">
                <button type="button" @click="showConfirmModal = false" :disabled="isSubmitting"
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs py-2.5 rounded-xl transition min-h-[38px] cursor-pointer">
                    Batal
                </button>
                
                <button type="button" @click="executeSubmit" :disabled="isSubmitting"
                        class="flex-1 text-white font-black text-xs uppercase tracking-wider py-2.5 rounded-xl transition min-h-[38px] flex items-center justify-center gap-1 shadow-sm cursor-pointer"
                        :class="{
                            'bg-emerald-600 hover:bg-emerald-700': decision === 'approved',
                            'bg-blue-600 hover:bg-blue-700': decision === 'reviewed',
                            'bg-rose-600 hover:bg-rose-700': decision === 'rejected'
                        }">
                    <span x-show="!isSubmitting">Ya, Eksekusi</span>
                    <span x-show="isSubmitting" class="flex items-center gap-1">
                        <i class="fa-solid fa-spinner fa-spin"></i> Proses...
                    </span>
                </button>
            </div>
        </div>
    </div>

</div>

<script>
function adminDecision(config) {
    return {
        decision: config.currentStatus === 'rejected' ? 'rejected' : 'approved',
        currentStatus: config.currentStatus,
        keringananChoice: (config.keringananStatus === 'rejected' || (config.approvedDiscount === 0 && config.currentStatus === 'approved')) ? 'reject' : 'requested',
        customDiscountPct: config.approvedDiscount || config.requestedDiscount || 15,
        catatanKeringanan: config.initialCatatanKeringanan || '',
        catatanAdmin: config.initialCatatanAdmin || '',
        showConfirmModal: false,
        isSubmitting: false,

        baseUangGedung: config.baseUangGedung,
        baseSpp: config.baseSpp,
        baseKegiatan: config.baseKegiatan,
        requestedDiscount: config.requestedDiscount,
        hasKeringanan: config.hasKeringanan,
        studentName: config.studentName,
        studentEmail: config.studentEmail,

        get activeDiscount() {
            if (!this.hasKeringanan || this.decision === 'rejected') return 0;
            if (this.keringananChoice === 'requested') return this.requestedDiscount;
            if (this.keringananChoice === 'custom') return parseInt(this.customDiscountPct || 0);
            return 0; // reject keringanan
        },

        get statusKeringananValue() {
            if (!this.hasKeringanan) return 'none';
            if (this.decision === 'rejected') return 'rejected';
            if (this.keringananChoice === 'reject') return 'rejected';
            return 'approved';
        },

        calcFee(base) {
            const disc = this.activeDiscount;
            return Math.round(base * (1 - (disc / 100)));
        },

        calcTotal() {
            return this.calcFee(this.baseUangGedung) + this.calcFee(this.baseSpp) + this.calcFee(this.baseKegiatan);
        },

        formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        },

        triggerSubmit() {
            if (this.decision === 'rejected' && (!this.catatanAdmin || !this.catatanAdmin.trim())) {
                alert('Silakan tuliskan alasan penolakan pendaftaran untuk dicatat pada sistem.');
                return;
            }
            this.showConfirmModal = true;
        },

        executeSubmit() {
            this.isSubmitting = true;
            this.$refs.statusForm.submit();
        }
    };
}
</script>
@endsection
