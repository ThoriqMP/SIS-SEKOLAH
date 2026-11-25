@extends('layouts.app')

@section('title', 'Edit Surat Keterangan')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back --}}
        <a href="{{ route('surat-keterangan.show', $suratKeterangan) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 mb-6 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-5">
                <h1 class="text-xl font-bold text-white flex items-center gap-3">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Edit Surat Keterangan
                </h1>
                <p class="text-amber-100 text-sm mt-1">{{ $suratKeterangan->jenis_label }} — {{ $suratKeterangan->nama_anak }}</p>
            </div>

            <form action="{{ route('surat-keterangan.update', $suratKeterangan) }}" method="POST" class="p-6 space-y-8" x-data="suratForm()">
                @csrf
                @method('PUT')

                {{-- Section: Jenis Surat --}}
                <div>
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">1</span>
                        Jenis Surat
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_surat" value="cibi" x-model="jenis" class="peer hidden" {{ old('jenis_surat', $suratKeterangan->jenis_surat) === 'cibi' ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 transition text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                                <i class="fa-solid fa-star text-2xl mb-2 text-emerald-500"></i>
                                <div class="font-bold text-sm text-gray-800 dark:text-gray-200">SK CIBI</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Cerdas Istimewa Berbakat Istimewa</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_surat" value="abk" x-model="jenis" class="peer hidden" {{ old('jenis_surat', $suratKeterangan->jenis_surat) === 'abk' ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 transition text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600">
                                <i class="fa-solid fa-heart text-2xl mb-2 text-blue-500"></i>
                                <div class="font-bold text-sm text-gray-800 dark:text-gray-200">SK ABK</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Anak Berkebutuhan Khusus</div>
                            </div>
                        </label>
                    </div>
                    @error('jenis_surat')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Section: Data Psikolog --}}
                <div>
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">2</span>
                        Data Psikolog
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Psikolog <span class="text-red-500">*</span></label>
                            <input type="text" name="psikolog_nama" value="{{ old('psikolog_nama', $suratKeterangan->psikolog_nama) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm">
                            @error('psikolog_nama') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jabatan <span class="text-red-500">*</span></label>
                            <input type="text" name="psikolog_jabatan" value="{{ old('psikolog_jabatan', $suratKeterangan->psikolog_jabatan) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm">
                            @error('psikolog_jabatan') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Psikolog <span class="text-red-500">*</span></label>
                            <textarea name="psikolog_alamat" rows="2" 
                                      class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm">{{ old('psikolog_alamat', $suratKeterangan->psikolog_alamat) }}</textarea>
                            @error('psikolog_alamat') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. SIAP HIMPSI <span class="text-red-500">*</span></label>
                            <input type="text" name="psikolog_no_himpsi" value="{{ old('psikolog_no_himpsi', $suratKeterangan->psikolog_no_himpsi) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm">
                            @error('psikolog_no_himpsi') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section: Data Anak --}}
                <div>
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">3</span>
                        Data Anak
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Anak <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_anak" value="{{ old('nama_anak', $suratKeterangan->nama_anak) }}" x-model="nama_anak"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm" required>
                            @error('nama_anak') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $suratKeterangan->tanggal_lahir->format('Y-m-d')) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm" required>
                            @error('tanggal_lahir') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Orang Tua <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua', $suratKeterangan->nama_orang_tua) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm" required>
                            @error('nama_orang_tua') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IQ Score <span class="text-gray-400">(opsional)</span></label>
                            <input type="text" name="iq_score" value="{{ old('iq_score', $suratKeterangan->iq_score) }}" x-model="iq_score"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm"
                                   placeholder="Contoh: 130">
                            @error('iq_score') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat <span class="text-red-500">*</span></label>
                            <textarea name="alamat" rows="2" 
                                      class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm" required>{{ old('alamat', $suratKeterangan->alamat) }}</textarea>
                            @error('alamat') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Section: Isi Surat --}}
                <div>
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">4</span>
                        Isi Surat
                    </h2>

                    <div x-show="jenis === 'cibi'" x-cloak>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-info text-emerald-500 mt-0.5"></i>
                                <div class="text-sm text-emerald-700 dark:text-emerald-300">
                                    <p class="font-medium mb-1">Template SK CIBI</p>
                                    <p>Surat akan otomatis berisi kalimat: <em>"Adalah benar merupakan anak CIBI (Cerdas Istimewa Berbakat Istimewa) dengan <strong>IQ [score]</strong>."</em></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="jenis === 'abk'" x-cloak>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-4">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    <p class="font-medium mb-1">Template SK ABK</p>
                                    <p>Tulis isi surat lengkap. Setiap paragraf baru pisahkan dengan baris kosong.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Paragraf Isi Surat
                                <span x-show="jenis === 'abk'" class="text-red-500">*</span>
                                <span x-show="jenis === 'cibi'" class="text-gray-400">(opsional)</span>
                            </label>
                            <button type="button" x-show="jenis === 'abk'" @click="applyAbkTemplate()"
                                    class="text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 font-medium flex items-center gap-1 transition bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 px-2 py-1 rounded">
                                <i class="fa-solid fa-magic"></i> Gunakan Template ABK
                            </button>
                        </div>
                        <textarea name="paragraf_isi" rows="12" x-model="paragraf_isi"
                                  class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm">{{ old('paragraf_isi', $suratKeterangan->paragraf_isi) }}</textarea>
                        @error('paragraf_isi') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Section: Tanggal Surat --}}
                <div>
                    <h2 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">5</span>
                        Tanggal & Tempat Surat
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tempat <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_surat" value="{{ old('tempat_surat', $suratKeterangan->tempat_surat) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm" required>
                            @error('tempat_surat') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $suratKeterangan->tanggal_surat->format('Y-m-d')) }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-sm" required>
                            @error('tanggal_surat') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('surat-keterangan.show', $suratKeterangan) }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-2.5 text-sm font-medium text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Perbarui Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function suratForm() {
        return {
            jenis: '{{ old('jenis_surat', $suratKeterangan->jenis_surat) }}',
            nama_anak: '{{ old('nama_anak', $suratKeterangan->nama_anak) }}',
            iq_score: '{{ old('iq_score', $suratKeterangan->iq_score) }}',
            paragraf_isi: {!! json_encode(old('paragraf_isi', $suratKeterangan->paragraf_isi)) !!},
            lastNama: '',
            lastIq: '',

            init() {
                this.lastNama = this.nama_anak || '[Nama Anak]';
                this.lastIq = this.iq_score || '[xxx]';

                this.$watch('nama_anak', (newVal, oldVal) => {
                    const from = oldVal || '[Nama Anak]';
                    const to = newVal || '[Nama Anak]';
                    if (this.paragraf_isi) {
                        this.paragraf_isi = this.paragraf_isi.replaceAll(from, to);
                    }
                    this.lastNama = to;
                });

                this.$watch('iq_score', (newVal, oldVal) => {
                    const from = oldVal || '[xxx]';
                    const to = newVal || '[xxx]';
                    if (this.paragraf_isi) {
                        this.paragraf_isi = this.paragraf_isi.replaceAll(from, to);
                    }
                    this.lastIq = to;
                });
            },
            
            get abkTemplate() {
                const nama = this.nama_anak || '[Nama Anak]';
                const iq = this.iq_score || '[xxx]';
                return `Berdasarkan hasil pemeriksaan psikologis dan fungsional yang telah dilakukan, Ananda ${nama} dengan IQ ${iq} belum menunjukkan kematangan aspek kognitif dasar dan kemandirian untuk mengikuti kegiatan belajar di lingkungan sekolah inklusi/reguler.

Oleh karenanya diperlukan implementasi Program Pembelajaran Individual (PPI) atau modifikasi kurikulum yang fleksibel sesuai dengan kecepatan dan kebutuhan belajar anak.

Sekolah disarankan berkoordinasi dengan orang tua untuk penyediaan Guru Pendamping Khusus atau Shadow Teacher guna membantu transisi dan fokus belajar Ananda di bulan-bulan awal sekolah.

Orang tua berkomitmen penuh untuk melanjutkan terapi suportif di luar jam sekolah dan bersikap terbuka untuk evaluasi berkala bersama pihak sekolah secara kooperatif. Surat pernyataan ini dibuat dengan penuh kesadaran, tanpa adanya paksaan dari pihak manapun.`;
            },
            
            applyAbkTemplate() {
                this.paragraf_isi = this.abkTemplate;
                this.lastNama = this.nama_anak || '[Nama Anak]';
                this.lastIq = this.iq_score || '[xxx]';
            }
        }
    }
</script>
@endpush
@endsection
