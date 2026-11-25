<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan - {{ $surat->nama_anak }}</title>
    <style>
        /* 1. STRUKTUR HALAMAN & LAYOUT GLOBAL (CSS Paged Media) */
        @page {
            size: A4;
            margin: 0;
            background-color: #ffffff;
        }

        html, body {
            padding: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #000000;
            font-size: 10pt;
            line-height: 1.3;
            margin: 0.15cm 3cm 3cm 3cm; /* Set margins directly on body to ensure A4 compliance with 1cm top margin */
        }

        p {
            margin: 0; /* Menghindari margin default bawaan browser */
        }

        /* 2. STRUKTUR KOP SURAT (Header Table Layout) */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .header-logo-cell {
            width: 25%;
            vertical-align: middle;
            text-align: left;
        }

        .header-logo-cell img {
            max-height: 120px;
            max-width: 100%;
            display: block;
        }

        .header-address-cell {
            width: 75%;
            vertical-align: middle;
            text-align: right;
        }

        .header-title-text {
            font-size: 10pt;
            font-weight: bold;
            color: #000000;
            margin-bottom: 2px;
        }

        .header-address-text {
            font-size: 9pt;
            color: #555555;
            line-height: 1.2;
            word-wrap: break-word;
        }

        .header-line {
            width: 50%;
            margin-left: auto;
            border-bottom: 2px solid #e28ec1;
            margin-top: 2px;
            margin-bottom: 25px;
        }

        /* 3. JUDUL DOKUMEN */
        .doc-title {
            text-align: center;
            font-size: 11.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        /* 4. BAGIAN DATA IDENTITAS (Strict Column Width) */
        .meta-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10pt;
            line-height: 1.35;
        }

        .meta-table .label {
            width: 24%;
            padding-left: 0;
        }

        .meta-table .separator {
            width: 3%;
            text-align: left;
        }

        .meta-table .value {
            width: 79%;
            text-align: left;
            word-wrap: break-word;
            font-weight: normal;
        }

        .meta-table .value-bold {
            font-weight: bold;
        }

        /* 5. PARAGRAF INTI & CLOSING */
        .intro-text {
            margin-top: 20px;
            margin-bottom: 6px;
        }

        .statement-body {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: justify;
            line-height: 1.4;
            text-indent: 0.75cm;
        }

        .closing-sec {
            margin-top: 12px;
            margin-bottom: 5px;
            text-align: justify;
            line-height: 1.4;
            text-indent: 0.75cm;
        }

        /* 6. BLOK TANDA TANGAN (Table Layout Pattern) */
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .sig-cell {
            width: 220px;
            text-align: center;
        }

        .sig-date {
            font-size: 10pt;
            margin-bottom: 2px;
        }

        .sig-role {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .sig-container {
            position: relative;
            height: 65px;
            width: 220px;
            margin: 0 auto;
        }

        .sig-stamp {
            position: absolute;
            top: 0;
            left: 39.5px; /* Diletakkan di sebelah kiri dengan margin centering */
            width: 80px;
            height: 80px;
            z-index: 1;
            opacity: 0.85;
        }

        .sig-image {
            position: absolute;
            top: 5px;
            left: 82.5px; /* Bergeser ke kanan dan menimpa stempel sebanyak 20px */
            width: 110px;
            height: 60px;
            z-index: 2;
        }

        .sig-name {
            font-weight: bold;
            font-size: 10pt;
            text-decoration: underline;
            margin-top: 2px;
        }

        .sig-himpsi {
            font-size: 9pt;
            margin-top: 1px;
            color: #333333;
        }
    </style>
</head>
<body>
    {{-- ===== HEADER ===== --}}
    <table class="header-table">
        <tr>
            <td class="header-logo-cell">
                @if($logoProsekar)
                    <img src="{{ $logoProsekar }}" alt="Pro Sekar Logo">
                @endif
            </td>
            <td class="header-address-cell">
                <div class="header-title-text">PRO SEKAR PSYCHOLOGICAL SERVICES</div>
                <div class="header-address-text">
                    {!! nl2br(e($surat->psikolog_alamat)) !!}
                </div>
            </td>
        </tr>
    </table>

    <div class="header-line"></div>

    {{-- ===== TITLE ===== --}}
    <div class="doc-title">SURAT KETERANGAN PSIKOLOG</div>

    {{-- ===== BODY ===== --}}
    <div>
        <p class="intro-text">Yang bertanda tangan di bawah ini,</p>

        <table class="meta-table block-one">
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td class="value value-bold">{{ $surat->psikolog_nama }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="separator">:</td>
                <td class="value">{{ $surat->psikolog_jabatan }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td class="value">{{ $surat->psikolog_alamat }}</td>
            </tr>
        </table>

        <p class="intro-text">Menerangkan bahwa,</p>

        <table class="meta-table block-two">
            <tr>
                <td class="label">Nama Anak</td>
                <td class="separator">:</td>
                <td class="value value-bold">{{ $surat->nama_anak }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Lahir</td>
                <td class="separator">:</td>
                <td class="value">{{ $surat->tanggal_lahir->translatedFormat('j F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Nama Orang Tua</td>
                <td class="separator">:</td>
                <td class="value">{{ $surat->nama_orang_tua }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td class="value">{{ $surat->alamat }}</td>
            </tr>
        </table>

        {{-- ===== CONTENT BODY ===== --}}
        @if($surat->jenis_surat === 'cibi')
            <p class="statement-body">
                Adalah benar merupakan anak CIBI (Cerdas Istimewa Berbakat Istimewa) dengan <strong>IQ {{ $surat->iq_score ?? 'xxx' }}</strong>.
            </p>
        @endif

        @if($surat->paragraf_isi)
            @foreach(preg_split('/\n\s*\n/', $surat->paragraf_isi) as $paragraf)
                @if(trim($paragraf))
                    <p class="statement-body">{!! nl2br(e(trim($paragraf))) !!}</p>
                @endif
            @endforeach
        @endif

        {{-- Closing --}}
        @if(empty($surat->paragraf_isi) || !str_contains($surat->paragraf_isi, 'Surat pernyataan ini dibuat dengan penuh kesadaran'))
            <p class="closing-sec">
                Surat pernyataan ini dibuat dengan penuh kesadaran, tanpa adanya paksaan dari pihak manapun.
            </p>
        @endif
    </div>

    {{-- ===== SIGNATURE ===== --}}
    <table class="sig-table">
        <tr>
            <td style="width: 60%;"></td>
            <td class="sig-cell">
                <div class="sig-date">{{ $surat->tempat_surat }}, {{ $surat->tanggal_surat->translatedFormat('j F Y') }}</div>
                <div class="sig-role">Psikolog,</div>
                
                <div class="sig-container">
                    @if($logoProsekar)
                        <img src="{{ $logoProsekar }}" class="sig-stamp" alt="Stempel Pro Sekar">
                    @endif
                    @if(!empty($signature))
                        <img src="{{ $signature }}" class="sig-image" alt="Tanda Tangan Psikolog">
                    @endif
                </div>

                <div class="sig-name">{{ $surat->psikolog_nama }}</div>
                <div class="sig-himpsi">No. SIAP HIMPSI {{ $surat->psikolog_no_himpsi }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
