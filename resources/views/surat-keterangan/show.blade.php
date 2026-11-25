@extends('layouts.app')

@section('title', 'Detail Surat Keterangan')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Back & Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <a href="{{ route('surat-keterangan.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('surat-keterangan.edit', $suratKeterangan) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 hover:bg-amber-200 dark:hover:bg-amber-800/60 transition flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
                <a href="{{ route('surat-keterangan.pdf', $suratKeterangan) }}?preview=1" target="_blank"
                   class="px-4 py-2 text-sm font-medium rounded-lg bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 hover:bg-purple-200 dark:hover:bg-purple-800/60 transition flex items-center gap-2">
                    <i class="fa-solid fa-eye"></i> Preview PDF
                </a>
                <a href="{{ route('surat-keterangan.pdf', $suratKeterangan) }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition flex items-center gap-2 shadow-md">
                    <i class="fa-solid fa-download"></i> Download PDF
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Letter Preview Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden">
            
            {{-- Simulated Letter Preview --}}
            <div class="p-8 md:p-12 bg-white" style="font-family: 'Times New Roman', serif;">
                
                {{-- Header --}}
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <div style="flex-shrink: 0; margin-right: 20px;">
                        @if($logoProsekar)
                            <img src="{{ $logoProsekar }}" alt="Logo" style="height: 80px;">
                        @endif
                    </div>
                    <div style="text-align: right; flex: 1;">
                        <div style="font-size: 14px; font-weight: bold; color: #5b21b6; font-style: italic;">PRO SEKAR PSYCHOLOGICAL SERVICES</div>
                        <div style="font-size: 11px; color: #555; white-space: pre-line;">{{ $suratKeterangan->psikolog_alamat }}</div>
                    </div>
                </div>

                {{-- Separator --}}
                <div style="border-top: 3px solid #d946ef; margin: 15px 0;"></div>

                {{-- Title --}}
                <h2 style="text-align: center; font-size: 16px; font-weight: bold; text-decoration: underline; margin: 30px 0; color: #1f2937;">
                    SURAT KETERANGAN PSIKOLOG
                </h2>

                {{-- Content --}}
                <div style="font-size: 12px; line-height: 1.8; color: #1f2937;">
                    <p style="margin-bottom: 15px;">Yang bertanda tangan di bawah ini,</p>
                    
                    <table style="margin-left: 30px; margin-bottom: 20px;">
                        <tr>
                            <td style="padding: 2px 0; width: 120px;">Nama</td>
                            <td style="padding: 2px 10px;">:</td>
                            <td style="padding: 2px 0; font-weight: bold;">{{ $suratKeterangan->psikolog_nama }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0;">Jabatan</td>
                            <td style="padding: 2px 10px;">:</td>
                            <td style="padding: 2px 0;">{{ $suratKeterangan->psikolog_jabatan }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0; vertical-align: top;">Alamat</td>
                            <td style="padding: 2px 10px; vertical-align: top;">:</td>
                            <td style="padding: 2px 0;">{{ $suratKeterangan->psikolog_alamat }}</td>
                        </tr>
                    </table>

                    <p style="margin-bottom: 15px;">Menerangkan bahwa,</p>

                    <table style="margin-left: 30px; margin-bottom: 25px;">
                        <tr>
                            <td style="padding: 2px 0; width: 120px;">Nama Anak</td>
                            <td style="padding: 2px 10px;">:</td>
                            <td style="padding: 2px 0; font-weight: bold;">{{ $suratKeterangan->nama_anak }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0;">Tanggal Lahir</td>
                            <td style="padding: 2px 10px;">:</td>
                            <td style="padding: 2px 0;">{{ $suratKeterangan->tanggal_lahir->translatedFormat('j F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0;">Nama Orang Tua</td>
                            <td style="padding: 2px 10px;">:</td>
                            <td style="padding: 2px 0;">{{ $suratKeterangan->nama_orang_tua }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0; vertical-align: top;">Alamat</td>
                            <td style="padding: 2px 10px; vertical-align: top;">:</td>
                            <td style="padding: 2px 0;">{{ $suratKeterangan->alamat }}</td>
                        </tr>
                    </table>

                    {{-- Content Body --}}
                    @if($suratKeterangan->jenis_surat === 'cibi')
                        <p style="margin-bottom: 15px;">
                            Adalah benar merupakan anak CIBI (Cerdas Istimewa Berbakat Istimewa) dengan <strong>IQ {{ $suratKeterangan->iq_score ?? 'xxx' }}</strong>.
                        </p>
                    @endif

                    @if($suratKeterangan->paragraf_isi)
                        @foreach(preg_split('/\n\s*\n/', $suratKeterangan->paragraf_isi) as $paragraf)
                            <p style="margin-bottom: 12px; text-align: justify;">{{ trim($paragraf) }}</p>
                        @endforeach
                    @endif

                    <p style="margin-bottom: 15px;">
                        Surat pernyataan ini dibuat dengan penuh kesadaran, tanpa adanya paksaan dari pihak manapun.
                    </p>

                    {{-- Footer --}}
                    <div style="text-align: right; margin-top: 40px;">
                        <p>{{ $suratKeterangan->tempat_surat }}, {{ $suratKeterangan->tanggal_surat->translatedFormat('j F Y') }}</p>
                        <p style="margin-top: 10px; font-weight: bold;">Psikolog,</p>
                        
                        @if($signatureAmbu)
                            <div style="margin: 10px 0;">
                                <img src="{{ $signatureAmbu }}" style="height: 60px; display: inline-block;">
                            </div>
                        @else
                            <div style="height: 60px;"></div>
                        @endif

                        <p style="font-weight: bold; text-decoration: underline;">{{ $suratKeterangan->psikolog_nama }}</p>
                        <p style="font-size: 10px;">No. SIAP HIMPSI {{ $suratKeterangan->psikolog_no_himpsi }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Metadata Card --}}
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Informasi Surat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <div class="text-gray-400 dark:text-gray-500 text-xs mb-1">Jenis</div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $suratKeterangan->jenis_surat === 'cibi' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300' }}">
                        {{ $suratKeterangan->jenis_label }}
                    </span>
                </div>
                <div>
                    <div class="text-gray-400 dark:text-gray-500 text-xs mb-1">Dibuat oleh</div>
                    <div class="font-medium text-gray-800 dark:text-gray-200">{{ $suratKeterangan->creator?->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-gray-400 dark:text-gray-500 text-xs mb-1">Dibuat pada</div>
                    <div class="font-medium text-gray-800 dark:text-gray-200">{{ $suratKeterangan->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
