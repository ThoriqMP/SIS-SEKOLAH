<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Diniyah - {{ $report->student->nama_siswa }}</title>
    <style>
        @page { margin: 0.8cm 1cm 1cm 1cm; }
        body { font-family: 'DejaVu Sans', sans-serif; margin: 0; padding: 0; color: #1f2937; line-height: 1.35; font-size: 8.5pt; background-color: #ffffff; }
        
        .header-container { position: relative; margin-bottom: 15px; text-align: center; padding-top: 5px; border-bottom: 2px solid #7c3aed; padding-bottom: 8px; }
        .header-title h1 { font-size: 13pt; font-weight: bold; margin: 0; text-transform: uppercase; color: #4c1d95; letter-spacing: 1px; }
        .header-title h2 { font-size: 16pt; font-weight: 900; margin: 5px 0 0 0; text-transform: uppercase; color: #4c1d95; letter-spacing: 2px; }

        .info-table { width: 100%; margin-bottom: 12px; border-collapse: collapse; background-color: #f5f3ff; border-left: 4px solid #7c3aed; }
        .info-table td { padding: 4px 8px; vertical-align: top; color: #374151; }
        .font-bold { font-weight: bold; color: #111827; }

        .section-bar { 
            background-color: #f5f3ff; color: #4c1d95; padding: 6px 10px; font-weight: bold; 
            font-size: 9pt; border: 1px solid #ddd6fe; border-bottom: none; border-radius: 6px 6px 0 0; text-transform: uppercase; letter-spacing: 0.5px;
        }

        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; table-layout: fixed; border: 1px solid #ddd6fe; border-top: none; }
        table.main-table th, table.main-table td { border: 1px solid #e5e7eb; padding: 6px 8px; word-wrap: break-word; }
        table.main-table th { background-color: #7c3aed; color: #ffffff; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 8.5pt; letter-spacing: 0.5px; }
        table.main-table tbody tr:nth-child(even) { background-color: #faf5ff; }
        
        .text-center { text-align: center; }

        .signature-container {
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-table { width: 100%; margin-top: 0; border-collapse: collapse; }
        .signature-table td { text-align: center; vertical-align: top; width: 33.33%; padding-bottom: 5px; color: #374151; }
        .signature-name { font-weight: bold; display: block; color: #111827; }
        .sig-line { border-top: 1.5px solid #000000; width: 140px; margin: 45px auto 5px auto; }

        /* Watermark Background */
        .watermark {
            position: fixed;
            top: 30%;
            left: 10%;
            width: 80%;
            text-align: center;
            opacity: 0.04;
            z-index: -1000;
        }
        .watermark img {
            width: 350px;
            height: auto;
        }

        .footer {
            position: fixed; bottom: -1cm; left: 0; right: 0;
            display: table; width: 100%; font-size: 8pt; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 8px;
        }
    </style>
</head>
<body>
    @php
        $logoPath = null;
        if ($school && $school->logo_path && file_exists(storage_path('app/public/' . $school->logo_path))) {
            $logoPath = storage_path('app/public/' . $school->logo_path);
        } elseif (file_exists(storage_path('app/public/logos/sayyidah-school-logo.png'))) {
            $logoPath = storage_path('app/public/logos/sayyidah-school-logo.png');
        } elseif (file_exists(storage_path('app/public/logos/LogoProsekarWIthText-removebg-preview.png'))) {
            $logoPath = storage_path('app/public/logos/LogoProsekarWIthText-removebg-preview.png');
        }

        $tahunPelajaran = '';
        if (preg_match('/(\d{4}\/\d{4})/', $report->period, $matches)) {
            $tahunPelajaran = $matches[1];
        } else {
            $tahunPelajaran = now()->format('Y') . '/' . now()->addYear()->format('Y');
        }
    @endphp
    @if($logoPath)
        <div class="watermark">
            <img src="{{ $logoPath }}" alt="Watermark Logo">
        </div>
    @endif

    <div class="header-container">
        <div class="header-title">
            <h1>LAPORAN PENILAIAN PROGRAM DINIYAH</h1>
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td width="10%">Nama</td><td width="2%">:</td><td width="40%" class="font-bold">{{ strtoupper($report->student->nama_siswa) }}</td>
            <td width="15%">Semester</td><td width="2%">:</td><td width="31%">{{ $report->period }}</td>
        </tr>
        <tr>
            <td>Kelas</td><td>:</td><td>{{ $report->student->class }}</td>
            <td>Tahun Pelajaran</td><td>:</td><td>{{ $tahunPelajaran }}</td>
        </tr>
    </table>

    <div class="section-bar">A. HAFALAN SURAT PILIHAN</div>
    <table class="main-table">
        <thead>
            <tr>
                <th width="30%">SURAT</th>
                <th width="15%">AYAT</th>
                <th width="15%">MUTU</th>
                <th width="40%">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->diniyahReports as $d)
                <tr>
                    <td style="padding-left: 15px;">{{ \App\Helpers\ReportHelper::getSurahName($d->surah_id) }}</td>
                    <td class="text-center">{{ $d->start_ayah }} - {{ $d->end_ayah }}</td>
                    <td class="text-center font-bold">{{ $d->mutu_code }}</td>
                    <td>{{ $d->description ?: \App\Helpers\ReportHelper::getMutuDescription($d->mutu_code, 'Diniyah') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>Orang tua/wali
                    <div class="sig-line"></div>
                </td>
                <td></td>
                <td>
                    {{ $printPlace }}, {{ $printDate }}<br>Fasilitator
                    <div class="sig-line"></div>
                    <span class="signature-name">{{ $report->teacher->name }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 30px;">
                    Mengetahui<br>Principle Sayyidah School
                    <div class="sig-line"></div>
                    <span class="signature-name">{{ optional($school)->kepala_sekolah_nama ?? 'Anggia Chrisanti, S.Psi, M.Psi, Psikolog' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div style="display: table-cell; width: 33.33%;">Sayyidah School</div>
        <div style="display: table-cell; width: 33.33%; text-align: center;">{{ $report->student->nama_siswa }}</div>
        <div style="display: table-cell; width: 33.33%; text-align: right;">Kelas: {{ $report->student->class }} | {{ $report->period }}</div>
    </div>
</body>
</html>
