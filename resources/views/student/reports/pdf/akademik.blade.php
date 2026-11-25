<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Akademik - {{ $report->student->nama_siswa }}</title>
    <style>
        @page { margin: 1.5cm 1.5cm 2cm 1.5cm; }
        body { font-family: 'DejaVu Sans', sans-serif; margin: 0; padding: 0; color: #1f2937; line-height: 1.4; font-size: 8.5pt; background-color: #ffffff; }

        .header { text-align: center; margin-bottom: 25px; }
        .header h1 { font-size: 14pt; font-weight: bold; margin: 0; text-transform: uppercase; color: #5b21b6; letter-spacing: 0.5px; }

        .info-container { width: 100%; margin-bottom: 20px; border: 1px solid #7c3aed; padding: 12px; background-color: #f5f3ff; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 2px 4px; vertical-align: top; color: #374151; }
        .font-bold { font-weight: bold; color: #111827; }

        .trimester-title { font-weight: bold; font-size: 10pt; margin-bottom: 8px; color: #5b21b6; }

        table.main-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; table-layout: fixed; }
        table.main-table th, table.main-table td { border: 1px solid #e5e7eb; padding: 8px 6px; word-wrap: break-word; }
        table.main-table th { background-color: #7c3aed; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; text-transform: uppercase; }
        table.main-table tbody tr:nth-child(even) { background-color: #faf5ff; }
        
        .text-center { text-align: center; }
        .page-break { page-break-after: always; }

        .summary-section { margin-top: 20px; }
        .summary-box { border: 1px solid #e5e7eb; margin-bottom: 15px; }
        .summary-header { background-color: #f5f3ff; padding: 8px 10px; font-weight: bold; border-bottom: 1px solid #ddd6fe; color: #5b21b6; }
        .summary-content { padding: 10px; min-height: 60px; text-align: justify; color: #4b5563; }

        .signature-container {
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-table { width: 100%; margin-top: 0; border-collapse: collapse; }
        .signature-table td { text-align: center; vertical-align: top; width: 33.33%; padding-bottom: 5px; color: #374151; }
        .signature-name { font-weight: bold; display: block; color: #111827; }
        .sig-line { border-top: 1.5px solid #000000; width: 140px; margin: 45px auto 5px auto; }

        .footer {
            position: fixed; bottom: -1cm; left: 0; right: 0;
            text-align: center; font-size: 7pt; color: #9ca3af;
            border-top: 0.5px solid #e5e7eb; padding-top: 5px;
        }

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
            width: 400px;
            height: auto;
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


    <div class="header">
        <h1>LAPORAN PERKEMBANGAN AKADEMIK</h1>
    </div>

    <div class="info-container">
        <table class="info-table">
            <tr>
                <td width="15%">Nama Siswa</td><td width="2%">:</td><td width="40%" class="font-bold">{{ strtoupper($report->student->nama_siswa) }}</td>
                <td width="15%">Semester</td><td width="2%">:</td><td width="26%">{{ $report->period }}</td>
            </tr>
            <tr>
                <td>Kelas</td><td>:</td><td>{{ $report->student->class }}</td>
                <td>Tahun Pelajaran</td><td>:</td><td>{{ $tahunPelajaran }}</td>
            </tr>
        </table>
    </div>

 

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Mata Pelajaran</th>
                <th width="12%">Penilaian {{ $report->academic_type ?: 'Mid' }}</th>
                <th width="12%">Remedial</th>
                <th width="12%">Harian</th>
                <th width="12%">Nilai Akhir</th>
                <th width="12%">Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->grades as $index => $grade)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="padding-left: 10px;">{{ $grade->subject->name }}</td>
                    @php $formatScore = fn($val) => (empty($val) || $val == 0) ? '-' : round($val); @endphp
                    <td class="text-center">{{ $report->academic_type === 'PTS' ? $formatScore($grade->score_pts) : $formatScore($grade->score_pas) }}</td>
                    <td class="text-center">{{ $formatScore($grade->score_remedial) }}</td>
                    <td class="text-center">{{ $formatScore($grade->score_harian) }}</td>
                    <td class="text-center font-bold">{{ $formatScore($grade->score) }}</td>
                    <td class="text-center font-bold">{{ strtoupper($grade->predicate) ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="trimester-title" style="margin-top: 10px;">Tabel interval predikat berdasarkan KKM</div>
    <table class="main-table" style="width: 80%;">
        <thead>
            <tr>
                <th width="20%">KKM</th>
                @foreach(\App\Helpers\ReportHelper::getGradeRanges() as $range)
                    <th>{{ strtoupper($range->letter) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center font-bold">75</td>
                @foreach(\App\Helpers\ReportHelper::getGradeRanges() as $range)
                    <td class="text-center">
                        {{ $range->min_score }}-{{ $loop->first ? '100' : (\App\Helpers\ReportHelper::getGradeRanges()[$loop->index - 1]->min_score - 1) }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <td class="text-center" style="font-size: 7pt;">Keterangan</td>
                @foreach(\App\Helpers\ReportHelper::getGradeRanges() as $range)
                    <td class="text-center" style="font-size: 7pt;">{{ $range->description }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <table class="main-table" style="margin-top: 15px;">
        <thead>
            <tr>
                <th style="text-align: left; padding-left: 10px;">Catatan Wali Kelas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 10px; text-align: left; height: 50px; vertical-align: top; font-weight: normal; text-transform: none;">
                    {{ $report->summary_notes ?? '-' }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="header">
        <h1>DESKRIPSI AKTIVITAS</h1>
    </div>

    <div class="summary-section">
        <div class="summary-box">
            <div class="summary-header">Penilaian {{ $report->academic_type ?: 'Semester' }} :</div>
            <div class="summary-content">{{ $report->pts_pas_summary ?: 'Aspek pencapaian Ananda '. $report->student->nama_siswa .' dalam penilaian semester sudah menunjukkan hasil yang sesuai dengan kriteria yang ditetapkan.' }}</div>
        </div>

        <div class="summary-box">
            <div class="summary-header">Remedial :</div>
            <div class="summary-content">{{ $report->remedial_summary ?: 'Aspek pencapaian remedial Ananda '. $report->student->nama_siswa .' cukup baik pada semua mata pelajaran.' }}</div>
        </div>

        <div class="summary-box">
            <div class="summary-header">Penilaian Harian :</div>
            <div class="summary-content">{{ $report->harian_summary ?: 'Aspek pencapaian penilaian harian Ananda '. $report->student->nama_siswa .' sudah baik pada semua mata pelajaran.' }}</div>
        </div>
    </div>

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
                <td colspan="3" style="padding-top: 20px;">
                    Mengetahui<br>Principle Sayyidah School
                    <div class="sig-line"></div>
                    <span class="signature-name">{{ optional($school)->kepala_sekolah_nama ?? 'Anggia Chrisanti, S.Psi, M.Psi, Psikolog' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Sayyidah School | {{ $report->student->nama_siswa }} | {{ $report->student->class }} | {{ $report->period }}
    </div>
</body>
</html>
