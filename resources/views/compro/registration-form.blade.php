<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-adsense-account" content="ca-pub-1189692810742391">
    
    @php
        try {
            $globalSchoolProfile = \App\Models\ProfilSekolah::first();
        } catch (\Throwable $e) {
            $globalSchoolProfile = null;
        }
        $faviconUrl = ($globalSchoolProfile && $globalSchoolProfile->logo_path) 
            ? Storage::url($globalSchoolProfile->logo_path) 
            : asset('logos/sis-sekolah-logo.png');
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}">

    <title>Pendaftaran Siswa Baru - SIS SEKOLAH</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        function registrationWizard(feeSchemes) {
            return {
                step: 1,
                parentTab: 'ayah',
                showInitialModal: true,
                feeSchemes: feeSchemes || {},
                isSubmitting: false,
                errorMessage: '',
                resultNomor: '',

                formData: {
                    jenjang: '',
                    jenis_pendaftar: '',

                    nama_lengkap: '',
                    nama_panggilan: '',
                    jenis_kelamin: 'L',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    anak_ke: 1,
                    agama: 'Islam',
                    golongan_darah: '',
                    berat_badan: '',
                    tinggi_badan: '',
                    nama_sekolah_sebelumnya: '',
                    nisn: '',

                    nama_ayah: '',
                    hp_ayah: '',
                    pekerjaan_ayah: '',
                    pendidikan_ayah: '',
                    nama_ibu: '',
                    hp_ibu: '',
                    pekerjaan_ibu: '',
                    pendidikan_ibu: '',

                    skema_belajar: 'offline',
                    mengajukan_keringanan: false,
                    persentase_keringanan_diajukan: 15,

                    ayah_tempat_kerja: '',
                    ibu_tempat_kerja: '',
                    penghasilan_gabungan: '',
                    jumlah_tanggungan: 2,
                    daya_listrik_watt: 900,
                    vehicles: [{ jenis: 'Motor', merk: '', tahun: '' }],

                    no_telpon_wa: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    alamat_rumah: '',
                },

                startWizard() {
                    if (this.formData.jenjang && this.formData.jenis_pendaftar) {
                        this.showInitialModal = false;
                    }
                },

                stepTitle() {
                    if (this.step === 1) return 'Profil Siswa';
                    if (this.step === 2) return 'Profil Orang Tua';
                    if (this.step === 3) return 'Skema & Biaya';
                    if (this.step === 3.5) return 'Pengajuan Keringanan';
                    if (this.step === 4) return 'Akun & Kontak';
                    return 'Selesai';
                },

                progressPercent() {
                    if (this.step === 1) return 25;
                    if (this.step === 2) return 50;
                    if (this.step === 3) return 75;
                    if (this.step === 3.5) return 85;
                    if (this.step === 4) return 100;
                    return 100;
                },

                currentJenjangSchemes() {
                    let j = this.formData.jenjang || 'SMP';
                    return this.feeSchemes[j] || this.feeSchemes['SMP'] || {};
                },

                currentSchemeData() {
                    let schemes = this.currentJenjangSchemes();
                    return schemes[this.formData.skema_belajar] || { uang_gedung: 0, spp_bulanan: 0, kegiatan_semester: 0 };
                },

                calculateUangGedung() {
                    let base = this.currentSchemeData().uang_gedung || 0;
                    if (this.formData.mengajukan_keringanan && this.formData.persentase_keringanan_diajukan) {
                        return base * (1 - (this.formData.persentase_keringanan_diajukan / 100));
                    }
                    return base;
                },

                calculateSpp() {
                    let base = this.currentSchemeData().spp_bulanan || 0;
                    if (this.formData.mengajukan_keringanan && this.formData.persentase_keringanan_diajukan) {
                        return base * (1 - (this.formData.persentase_keringanan_diajukan / 100));
                    }
                    return base;
                },

                calculateKegiatan() {
                    let base = this.currentSchemeData().kegiatan_semester || 0;
                    if (this.formData.mengajukan_keringanan && this.formData.persentase_keringanan_diajukan) {
                        return base * (1 - (this.formData.persentase_keringanan_diajukan / 100));
                    }
                    return base;
                },

                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number || 0);
                },

                addVehicle() {
                    this.formData.vehicles.push({ jenis: 'Motor', merk: '', tahun: '' });
                },

                removeVehicle(index) {
                    if (this.formData.vehicles.length > 1) {
                        this.formData.vehicles.splice(index, 1);
                    }
                },

                nextStep(current) {
                    this.errorMessage = '';
                    
                    if (current === 1) {
                        if (!this.formData.nama_lengkap) {
                            this.errorMessage = 'Nama lengkap siswa wajib diisi.';
                            return;
                        }
                        if (this.formData.jenis_pendaftar === 'pindahan' && !this.formData.nama_sekolah_sebelumnya) {
                            this.errorMessage = 'Nama sekolah sebelumnya wajib diisi untuk murid pindahan.';
                            return;
                        }
                        this.step = 2;
                        this.parentTab = 'ayah';
                    } else if (current === 2) {
                        this.step = 3;
                    } else if (current === 3) {
                        if (this.formData.mengajukan_keringanan) {
                            this.step = 3.5;
                        } else {
                            this.step = 4;
                        }
                    } else if (current === 3.5) {
                        if (!this.formData.ayah_pekerjaan || !this.formData.ibu_pekerjaan) {
                            this.errorMessage = 'Pekerjaan Ayah dan Ibu wajib diisi untuk pengajuan keringanan.';
                            return;
                        }
                        this.step = 4;
                    }
                },

                async submitForm() {
                    this.errorMessage = '';
                    if (!this.formData.email || !this.formData.no_telpon_wa || !this.formData.password) {
                        this.errorMessage = 'Email, Nomor WA, dan Password wajib diisi.';
                        return;
                    }
                    if (this.formData.password !== this.formData.password_confirmation) {
                        this.errorMessage = 'Konfirmasi password tidak cocok.';
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        let response = await fetch('{{ route("registration.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(this.formData)
                        });

                        let result = await response.json();

                        if (response.ok && result.success) {
                            this.resultNomor = result.nomor_pendaftaran;
                            this.step = 5;
                        } else {
                            if (result.errors) {
                                let messages = Object.values(result.errors).flat().join(' ');
                                this.errorMessage = messages;
                            } else {
                                this.errorMessage = result.message || 'Terjadi kesalahan saat menyimpan pendaftaran.';
                            }
                        }
                    } catch (err) {
                        this.errorMessage = 'Koneksi gagal. Silakan periksa jaringan internet Anda.';
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            };
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Inter', sans-serif !important;
            background-color: #f8fafc !important;
            color: #334155 !important;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif !important;
            color: #0f172a;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased min-h-screen py-3 sm:py-8 px-2.5 sm:px-4 flex flex-col justify-start sm:justify-center items-center bg-slate-50">

    <div class="w-full max-w-xl sm:max-w-2xl my-auto flex flex-col gap-2 sm:gap-3" 
         x-data="registrationWizard(@js($feeSchemes))" 
         x-cloak>

        <!-- Initial Modal: Jenjang & Tipe Pendaftar -->
        <div x-show="showInitialModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/70 backdrop-blur-xs overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="bg-white rounded-2xl max-w-md w-full p-4 sm:p-6 shadow-xl border border-slate-200 text-left my-auto space-y-3.5">
                <div class="text-center space-y-1">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[10px] font-bold tracking-wider text-purple-700 bg-purple-50 rounded-full border border-purple-200">
                        Langkah Awal
                    </span>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Pilih Jenjang &amp; Status Masuk</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500">Tentukan jenjang dan status pendaftaran calon siswa.</p>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">1. Jenjang Pendidikan *</label>
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="j in ['SD', 'SMP', 'SMA']" :key="j">
                            <button type="button" 
                                    @click="formData.jenjang = j"
                                    class="py-2.5 px-2 rounded-xl border text-center font-bold text-xs sm:text-sm min-h-[42px] cursor-pointer transition"
                                    :class="formData.jenjang === j ? 'bg-purple-600 text-white border-purple-600 shadow-xs ring-1 ring-purple-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300'">
                                <span x-text="j"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div x-show="formData.jenjang" x-transition.opacity>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">2. Status Masuk *</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" 
                                @click="formData.jenis_pendaftar = 'baru'"
                                class="p-2.5 rounded-xl border text-left min-h-[44px] cursor-pointer transition"
                                :class="formData.jenis_pendaftar === 'baru' ? 'bg-purple-50 border-purple-600 ring-1 ring-purple-600 text-purple-900' : 'bg-slate-50 border-slate-200 hover:border-slate-300 text-slate-800'">
                            <span class="block text-xs font-bold leading-tight">Murid Baru</span>
                            <span class="block text-[10px] text-slate-500 mt-0.5 leading-tight">Masuk jenjang awal</span>
                        </button>

                        <button type="button" 
                                @click="formData.jenis_pendaftar = 'pindahan'"
                                class="p-2.5 rounded-xl border text-left min-h-[44px] cursor-pointer transition"
                                :class="formData.jenis_pendaftar === 'pindahan' ? 'bg-purple-50 border-purple-600 ring-1 ring-purple-600 text-purple-900' : 'bg-slate-50 border-slate-200 hover:border-slate-300 text-slate-800'">
                            <span class="block text-xs font-bold leading-tight">Murid Pindahan</span>
                            <span class="block text-[10px] text-slate-500 mt-0.5 leading-tight">Pindah antar sekolah</span>
                        </button>
                    </div>
                </div>

                <button type="button" 
                        @click="startWizard" 
                        :disabled="!formData.jenjang || !formData.jenis_pendaftar"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 rounded-xl transition disabled:opacity-40 disabled:cursor-not-allowed min-h-[42px] cursor-pointer">
                    Mulai Pendaftaran &rarr;
                </button>
            </div>
        </div>

        <!-- Top Header: Logo & Back Link -->
        <div class="flex items-center justify-between px-1">
            <a href="{{ route('compro.index') }}" class="flex items-center gap-2">
                <img src="{{ ($globalSchoolProfile && $globalSchoolProfile->logo_path) ? Storage::url($globalSchoolProfile->logo_path) : asset('storage/logos/sayyidah-school-logo.png') }}" 
                     alt="Sayyidah School" 
                     class="h-7 sm:h-8 w-auto object-contain">
                <div class="flex flex-col leading-none">
                    <span class="text-xs sm:text-sm font-bold tracking-tight text-slate-900">Sayyidah School</span>
                    <span class="text-[8px] sm:text-[9.5px] font-semibold text-purple-600 uppercase tracking-wider">PPDB Online</span>
                </div>
            </a>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-purple-700 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-full"
                      x-show="formData.jenjang"
                      x-text="formData.jenjang + ' • ' + (formData.jenis_pendaftar === 'pindahan' ? 'Pindahan' : 'Baru')"></span>
                <a href="{{ route('compro.index') }}" class="text-[11px] font-semibold text-slate-500 hover:text-slate-800 transition">&larr; Beranda</a>
            </div>
        </div>

        <!-- Wizard Card (Cohesive & Balanced Layout) -->
        <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm border border-slate-200/90 relative">

            <!-- Stepper Header -->
            <div class="mb-3.5 pb-2.5 border-b border-slate-100" x-show="step < 5">
                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                    <div class="flex items-center gap-1.5">
                        <span class="w-5 h-5 rounded-full bg-purple-600 text-white text-[10px] flex items-center justify-center font-mono font-bold" x-text="step === 3.5 ? '3a' : step"></span>
                        <span class="text-slate-900 font-bold" x-text="stepTitle()"></span>
                    </div>
                    <span class="text-purple-700 font-mono text-[11px]" x-text="step === 3.5 ? 'Tahap 3a dari 4' : 'Tahap ' + step + ' dari 4'"></span>
                </div>

                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden flex">
                    <div class="bg-purple-600 h-full transition-all duration-300 ease-out" 
                         :style="'width: ' + progressPercent() + '%'"></div>
                </div>
            </div>

            <!-- Error Banner -->
            <div x-show="errorMessage" class="mb-3 p-2.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold flex items-start gap-2" x-cloak>
                <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5"></i>
                <div x-text="errorMessage"></div>
            </div>

            <form @submit.prevent="submitForm">

                <!-- SLIDE 1: Profil Anak (Natural 2-Column Balanced Spacing) -->
                <div x-show="step === 1" x-transition.opacity.duration.150ms class="space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">Biodata Calon Siswa</span>
                        <span class="text-[10px] text-slate-400">* Wajib diisi</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <!-- Full Width: Nama Lengkap -->
                        <div class="col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap Siswa *</label>
                            <input type="text" x-model="formData.nama_lengkap" required
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]"
                                   placeholder="Sesuai Akta Kelahiran">
                        </div>

                        <!-- Row 2: Nama Panggilan & Jenis Kelamin -->
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Panggilan</label>
                            <input type="text" x-model="formData.nama_panggilan"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]"
                                   placeholder="Panggilan">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Jenis Kelamin *</label>
                            <select x-model="formData.jenis_kelamin" required
                                    class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <!-- Row 3: Tempat Lahir & Tanggal Lahir -->
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Tempat Lahir</label>
                            <input type="text" x-model="formData.tempat_lahir"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]"
                                   placeholder="Kota lahir">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal Lahir</label>
                            <input type="date" x-model="formData.tanggal_lahir"
                                   class="w-full px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]">
                        </div>

                        <!-- Row 4: Anak Ke- & Agama (Each gets its own proper column) -->
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Anak Ke-</label>
                            <input type="number" min="1" x-model="formData.anak_ke" placeholder="Contoh: 1" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Agama</label>
                            <input type="text" x-model="formData.agama" placeholder="Agama" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                        </div>

                        <!-- Row 5: BB/TB & Golongan Darah -->
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Berat &amp; Tinggi Badan</label>
                            <div class="flex gap-2">
                                <input type="number" x-model="formData.berat_badan" placeholder="BB kg" class="w-1/2 px-2.5 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                                <input type="number" x-model="formData.tinggi_badan" placeholder="TB cm" class="w-1/2 px-2.5 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Golongan Darah</label>
                            <select x-model="formData.golongan_darah" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                                <option value="">Tidak Tahu / Pilih</option>
                                <option value="A">Golongan A</option>
                                <option value="B">Golongan B</option>
                                <option value="AB">Golongan AB</option>
                                <option value="O">Golongan O</option>
                            </select>
                        </div>

                        <!-- Row 6 (Conditional): Sekolah Asal & NISN untuk Pindahan -->
                        <template x-if="formData.jenis_pendaftar === 'pindahan'">
                            <div class="col-span-2 grid grid-cols-2 gap-2 p-2.5 bg-purple-50/50 rounded-xl border border-purple-100">
                                <div>
                                    <label class="block text-[10px] font-bold text-purple-900 uppercase tracking-wider mb-1">Sekolah Asal *</label>
                                    <input type="text" x-model="formData.nama_sekolah_sebelumnya"
                                           class="w-full px-2.5 py-2 rounded-lg bg-white border border-purple-200 text-sm sm:text-xs font-medium min-h-[38px]"
                                           placeholder="Nama sekolah sebelumnya">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-purple-900 uppercase tracking-wider mb-1">NISN Siswa</label>
                                    <input type="text" x-model="formData.nisn"
                                           class="w-full px-2.5 py-2 rounded-lg bg-white border border-purple-200 text-sm sm:text-xs font-medium min-h-[38px]"
                                           placeholder="NISN (opsional)">
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Step 1 Action Bar (Right below fields with clean rhythm) -->
                    <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between gap-3">
                        <span class="text-[11px] text-slate-400 hidden sm:inline">Data tersimpan otomatis</span>
                        <button type="button" @click="nextStep(1)"
                                class="w-full sm:w-auto bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider py-3 px-6 rounded-xl transition min-h-[42px] cursor-pointer flex items-center justify-center gap-2 ml-auto shadow-xs">
                            <span>Lanjut ke Profil Orang Tua</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- SLIDE 2: Profil Orang Tua (Balanced Tab Layout) -->
                <div x-show="step === 2" x-transition.opacity.duration.150ms class="space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">Informasi Orang Tua / Wali</span>

                        <!-- Mobile Segmented Tab -->
                        <div class="flex sm:hidden p-0.5 bg-slate-100 rounded-lg text-[10.5px] font-bold">
                            <button type="button" 
                                    @click="parentTab = 'ayah'" 
                                    :class="parentTab === 'ayah' ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-600'" 
                                    class="px-3 py-1 rounded-md transition min-h-[28px]">
                                Data Ayah
                            </button>
                            <button type="button" 
                                    @click="parentTab = 'ibu'" 
                                    :class="parentTab === 'ibu' ? 'bg-purple-600 text-white shadow-xs' : 'text-slate-600'" 
                                    class="px-3 py-1 rounded-md transition min-h-[28px]">
                                Data Ibu
                            </button>
                        </div>
                    </div>

                    <!-- Data Ayah -->
                    <div x-show="parentTab === 'ayah' || window.innerWidth >= 640" 
                         class="bg-slate-50/70 p-3 sm:p-4 rounded-xl border border-slate-200/80 space-y-2">
                        <div class="flex items-center justify-between">
                            <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Data Ayah Kandung</h4>
                            <button type="button" @click="parentTab = 'ibu'" class="sm:hidden text-[10.5px] font-bold text-purple-600 hover:underline">
                                Isi Data Ibu &rarr;
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5 text-xs">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Ayah</label>
                                <input type="text" x-model="formData.nama_ayah" placeholder="Nama Lengkap" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">No. HP Ayah</label>
                                <input type="text" x-model="formData.hp_ayah" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pekerjaan Ayah</label>
                                <input type="text" x-model="formData.pekerjaan_ayah" placeholder="Karyawan / Usaha" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pendidikan</label>
                                <input type="text" x-model="formData.pendidikan_ayah" placeholder="SMA / S1 / S2" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                        </div>
                    </div>

                    <!-- Data Ibu -->
                    <div x-show="parentTab === 'ibu' || window.innerWidth >= 640" 
                         class="bg-slate-50/70 p-3 sm:p-4 rounded-xl border border-slate-200/80 space-y-2">
                        <div class="flex items-center justify-between">
                            <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-wider">Data Ibu Kandung</h4>
                            <button type="button" @click="parentTab = 'ayah'" class="sm:hidden text-[10.5px] font-bold text-purple-600 hover:underline">
                                &larr; Lihat Data Ayah
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5 text-xs">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Ibu</label>
                                <input type="text" x-model="formData.nama_ibu" placeholder="Nama Lengkap" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">No. HP Ibu</label>
                                <input type="text" x-model="formData.hp_ibu" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pekerjaan Ibu</label>
                                <input type="text" x-model="formData.pekerjaan_ibu" placeholder="Ibu Rumah Tangga / Usaha" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pendidikan</label>
                                <input type="text" x-model="formData.pendidikan_ibu" placeholder="SMA / S1 / S2" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 Action Bar -->
                    <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button type="button" @click="step = 1" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-4 py-2.5 rounded-xl bg-slate-100 min-h-[42px] cursor-pointer">
                            &larr; Kembali
                        </button>
                        <button type="button" @click="nextStep(2)"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider py-3 px-6 rounded-xl transition min-h-[42px] cursor-pointer flex items-center gap-2 shadow-xs">
                            <span>Lanjut ke Skema Belajar</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- SLIDE 3: Skema Belajar & Rincian Biaya -->
                <div x-show="step === 3" x-transition.opacity.duration.150ms class="space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">Pilihan Skema &amp; Rincian Biaya</span>
                        <span class="text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded" x-text="'Jenjang ' + formData.jenjang"></span>
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Skema Belajar *</label>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="(scheme, key) in currentJenjangSchemes()" :key="key">
                                <div @click="formData.skema_belajar = key" 
                                     class="cursor-pointer p-2.5 rounded-xl border text-center flex flex-col justify-between min-h-[56px] transition"
                                     :class="formData.skema_belajar === key ? 'bg-purple-50 border-purple-600 ring-1 ring-purple-600 text-purple-900' : 'bg-white border-slate-200 hover:border-slate-300 text-slate-800'">
                                    <div class="flex items-center justify-center gap-1">
                                        <span class="text-xs font-bold" x-text="scheme.label"></span>
                                        <span x-show="formData.skema_belajar === key" class="text-purple-600 font-bold text-xs">✓</span>
                                    </div>
                                    <span class="text-[9.5px] text-slate-400 mt-0.5 truncate" x-text="key === 'offline' ? 'Tatap Muka' : (key === 'online' ? 'Kelas Virtual' : 'Kombinasi')"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Cost Breakdown Box -->
                    <div class="bg-slate-900 text-white rounded-xl p-3 shadow-sm">
                        <div class="flex items-center justify-between mb-1.5 text-[10.5px]">
                            <span class="font-bold text-slate-200 uppercase tracking-wider text-[9.5px]">Estimasi Investasi Pendidikan</span>
                            <span x-show="formData.mengajukan_keringanan" class="text-[9.5px] font-bold text-emerald-300 bg-emerald-950 px-2 py-0.2 rounded-full border border-emerald-500/30" x-text="'Keringanan ' + formData.persentase_keringanan_diajukan + '%'"></span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-1.5 border-t border-slate-800 text-center">
                            <div class="p-1">
                                <span class="text-[9.5px] text-slate-400 block leading-tight">Uang Gedung</span>
                                <strong class="text-xs sm:text-sm font-bold text-emerald-400 font-mono block mt-0.5" x-text="formatRupiah(calculateUangGedung())"></strong>
                                <span class="text-[8.5px] text-slate-400">1x masuk</span>
                            </div>
                            <div class="p-1 border-x border-slate-800">
                                <span class="text-[9.5px] text-slate-400 block leading-tight">SPP Rutin</span>
                                <strong class="text-xs sm:text-sm font-bold text-slate-100 font-mono block mt-0.5" x-text="formatRupiah(calculateSpp())"></strong>
                                <span class="text-[8.5px] text-slate-400">per bulan</span>
                            </div>
                            <div class="p-1">
                                <span class="text-[9.5px] text-slate-400 block leading-tight">Kegiatan</span>
                                <strong class="text-xs sm:text-sm font-bold text-slate-100 font-mono block mt-0.5" x-text="formatRupiah(calculateKegiatan())"></strong>
                                <span class="text-[8.5px] text-slate-400">per semester</span>
                            </div>
                        </div>
                    </div>

                    <!-- Toggle Keringanan -->
                    <div class="p-2.5 rounded-xl border bg-slate-50 border-slate-200 space-y-2">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" x-model="formData.mengajukan_keringanan" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 shrink-0">
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-slate-900 block leading-tight">Ajukan Keringanan Biaya Masuk</span>
                                <span class="text-[10px] text-slate-500 block leading-tight">Pengajuan beasiswa potongan biaya investasi sekolah.</span>
                            </div>
                        </label>

                        <div x-show="formData.mengajukan_keringanan" x-collapse class="pt-2 border-t border-slate-200">
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="pct in [15, 25, 35]" :key="pct">
                                    <label class="cursor-pointer py-1.5 px-2 rounded-lg border text-center transition min-h-[34px] flex items-center justify-center font-bold text-xs"
                                           :class="formData.persentase_keringanan_diajukan == pct ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-slate-700 border-slate-200'">
                                        <input type="radio" name="pct_keringanan" :value="pct" x-model="formData.persentase_keringanan_diajukan" class="sr-only">
                                        <span x-text="'Potongan ' + pct + '%'"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 Action Bar -->
                    <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button type="button" @click="step = 2" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-4 py-2.5 rounded-xl bg-slate-100 min-h-[42px] cursor-pointer">
                            &larr; Kembali
                        </button>
                        <button type="button" @click="nextStep(3)"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider py-3 px-6 rounded-xl transition min-h-[42px] cursor-pointer flex items-center gap-2 shadow-xs">
                            <span x-text="formData.mengajukan_keringanan ? 'Lanjut ke Data Keringanan' : 'Lanjut ke Akun Portal'"></span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- SLIDE 3a: Data Keringanan (Kondisional) -->
                <div x-show="step === 3.5 && formData.mengajukan_keringanan" x-transition.opacity.duration.150ms class="space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">Verifikasi Kelayakan Keringanan</span>
                        <span class="text-[10px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded font-bold">Wajib Diisi</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Tempat Kerja Ayah</label>
                            <input type="text" x-model="formData.ayah_tempat_kerja" placeholder="Instansi / Kantor" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pekerjaan Ayah *</label>
                            <input type="text" x-model="formData.ayah_pekerjaan" required placeholder="Jabatan / Profesi" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Tempat Kerja Ibu</label>
                            <input type="text" x-model="formData.ibu_tempat_kerja" placeholder="Instansi / Kantor" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pekerjaan Ibu *</label>
                            <input type="text" x-model="formData.ibu_pekerjaan" required placeholder="Jabatan / Profesi" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                        </div>

                        <div class="col-span-2 grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-[9.5px] font-bold text-slate-700 uppercase tracking-wider mb-1">Penghasilan (Rp)</label>
                                <input type="number" x-model="formData.penghasilan_gabungan" placeholder="Nominal" class="w-full px-2.5 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                            <div>
                                <label class="block text-[9.5px] font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggungan *</label>
                                <input type="number" min="1" x-model="formData.jumlah_tanggungan" required placeholder="Jumlah" class="w-full px-2.5 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                            </div>
                            <div>
                                <label class="block text-[9.5px] font-bold text-slate-700 uppercase tracking-wider mb-1">Listrik *</label>
                                <select x-model="formData.daya_listrik_watt" required class="w-full px-2 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium min-h-[40px]">
                                    <option value="450">450 VA</option>
                                    <option value="900">900 VA</option>
                                    <option value="1300">1300 VA</option>
                                    <option value="2200">2200 VA</option>
                                    <option value="3500">> 2200 VA</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kendaraan Repeater -->
                        <div class="col-span-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                            <div class="flex justify-between items-center mb-1.5">
                                <span class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Aset Kendaraan</span>
                                <button type="button" @click="addVehicle" class="text-[10.5px] font-bold text-purple-600 hover:text-purple-800">+ Tambah</button>
                            </div>
                            <template x-for="(veh, index) in formData.vehicles" :key="index">
                                <div class="flex gap-2 items-center mb-1.5">
                                    <select x-model="veh.jenis" class="w-1/3 px-2 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-medium min-h-[34px]">
                                        <option value="Motor">Motor</option>
                                        <option value="Mobil">Mobil</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <input type="text" x-model="veh.merk" placeholder="Merk/Tipe" class="w-1/3 px-2 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-medium min-h-[34px]">
                                    <input type="number" x-model="veh.tahun" placeholder="Tahun" class="w-1/4 px-2 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-medium min-h-[34px]">
                                    <button type="button" @click="removeVehicle(index)" class="text-rose-500 font-bold px-1.5 text-xs">&times;</button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Step 3a Action Bar -->
                    <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button type="button" @click="step = 3" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-4 py-2.5 rounded-xl bg-slate-100 min-h-[42px] cursor-pointer">
                            &larr; Kembali
                        </button>
                        <button type="button" @click="nextStep(3.5)"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider py-3 px-6 rounded-xl transition min-h-[42px] cursor-pointer flex items-center gap-2 shadow-xs">
                            <span>Lanjut ke Akun Portal</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- SLIDE 4: Pembuatan Akun & Kontak -->
                <div x-show="step === 4" x-transition.opacity.duration.150ms class="space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">Akun Portal Pembelajaran Siswa</span>
                        <span class="text-[10px] text-slate-400">Finalisasi Akun</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">No. WhatsApp *</label>
                            <input type="text" x-model="formData.no_telpon_wa" required
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]"
                                   placeholder="081234567890">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Email Login *</label>
                            <input type="email" x-model="formData.email" required
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]"
                                   placeholder="email@domain.com">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Password *</label>
                            <input type="password" x-model="formData.password" required
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]"
                                   placeholder="Min. 8 karakter">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Ulangi Password *</label>
                            <input type="password" x-model="formData.password_confirmation" required
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]"
                                   placeholder="Konfirmasi password">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Rumah Lengkap</label>
                            <input type="text" x-model="formData.alamat_rumah"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-sm sm:text-xs font-medium focus:outline-none focus:border-purple-600 min-h-[40px]"
                                   placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota">
                        </div>
                    </div>

                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-slate-700 text-[10.5px] leading-tight flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-purple-600 text-xs shrink-0"></i>
                        <span>Akun aktif otomatis setelah pendaftaran diverifikasi oleh sekolah.</span>
                    </div>

                    <!-- Step 4 Action Bar -->
                    <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between gap-3">
                        <button type="button" @click="formData.mengajukan_keringanan ? step = 3.5 : step = 3" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-4 py-2.5 rounded-xl bg-slate-100 min-h-[42px] cursor-pointer">
                            &larr; Kembali
                        </button>
                        
                        <button type="submit" :disabled="isSubmitting"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider py-3 px-6 rounded-xl transition disabled:opacity-50 min-h-[42px] cursor-pointer flex items-center gap-2 shadow-xs">
                            <i class="fa-solid fa-paper-plane text-xs" x-show="!isSubmitting"></i>
                            <span x-text="isSubmitting ? 'Memproses...' : 'Kirim Pendaftaran'"></span>
                        </button>
                    </div>
                </div>

                <!-- SLIDE 5: Selesai -->
                <div x-show="step === 5" x-transition.opacity.duration.150ms class="text-center py-4 space-y-3" x-cloak>
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full flex items-center justify-center text-xl mx-auto font-bold">✓</div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Pendaftaran Berhasil Dikirim</h2>
                    <p class="text-[11px] text-slate-500">Data calon siswa baru telah tercatat pada sistem PPDB Says Homeschooling.</p>

                    <div class="bg-slate-900 text-white rounded-2xl p-4 my-2 shadow-sm w-full max-w-sm mx-auto">
                        <span class="text-[9.5px] uppercase font-bold tracking-wider text-slate-400 block mb-0.5">Nomor Registrasi</span>
                        <div class="text-2xl sm:text-3xl font-bold tracking-wider text-emerald-400 font-mono" x-text="resultNomor"></div>
                        <p class="text-[10.5px] text-slate-400 mt-1">Simpan nomor registrasi untuk melacak status verifikasi.</p>
                    </div>

                    <template x-if="formData.mengajukan_keringanan">
                        <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-left text-[11px] text-slate-700 max-w-sm mx-auto">
                            <strong>Pengajuan Keringanan <span x-text="formData.persentase_keringanan_diajukan + '%'"></span> Diterima:</strong><br>
                            Hasil verifikasi akan diinformasikan bersamaan dengan konfirmasi penerimaan sekolah.
                        </div>
                    </template>

                    <div class="flex flex-row gap-2 justify-center w-full max-w-sm mx-auto pt-2">
                        <a :href="'/pendaftaran/lacak/' + resultNomor" 
                           class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-3 rounded-xl transition min-h-[42px] flex items-center justify-center">
                            Lacak Status
                        </a>
                        <a href="{{ route('compro.index') }}" 
                           class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider py-2.5 px-3 rounded-xl transition min-h-[42px] flex items-center justify-center">
                            Beranda
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>

</body>
</html>
