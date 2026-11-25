<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir Pendaftaran - {{ $registration->nomor_pendaftaran }}</title>
    
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 11px;
            margin: 0;
            padding: 30px;
            background: #fff;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
            position: relative;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header h2 {
            font-size: 13px;
            margin: 3px 0 0 0;
            font-weight: 600;
            color: #555;
        }

        .header .nomor {
            font-size: 11px;
            margin-top: 5px;
            font-weight: bold;
            color: #7c3aed;
        }

        .section-title {
            background-color: #f1f5f9;
            border-left: 4px solid #7c3aed;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 20px 0 10px 0;
            letter-spacing: 0.5px;
        }

        .grid {
            display: grid;
            grid-template-cols: 1fr 1fr;
            gap: 10px 25px;
        }

        .col-full {
            grid-column: span 2;
        }

        .field {
            margin-bottom: 5px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 4px;
        }

        .label {
            font-size: 9px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            display: block;
            margin-bottom: 1px;
        }

        .value {
            font-size: 11px;
            font-weight: 600;
            color: #0f172a;
        }

        .parent-columns {
            display: grid;
            grid-template-cols: 1fr 1fr;
            gap: 30px;
            margin-top: 10px;
        }

        .parent-sec {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
        }

        .parent-sec h3 {
            margin: 0 0 10px 0;
            font-size: 11px;
            font-weight: 800;
            color: #7c3aed;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
        }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>SAYS HOMESCHOOLING</h1>
        <h2>FORMULIR PENDAFTARAN SISWA BARU</h2>
        <div class="nomor">No. Registrasi: {{ $registration->nomor_pendaftaran }} | Tahun Ajaran: {{ $registration->tahun_pelajaran ?? '-' }}</div>
    </div>

    <!-- Section A: Data Diri Calon Siswa -->
    <div class="section-title">👤 DATA CALON SISWA</div>
    <div class="grid">
        <div class="field col-full">
            <span class="label">Nama Lengkap</span>
            <span class="value">{{ $registration->nama_lengkap }}</span>
        </div>
        <div class="field">
            <span class="label">Nama Panggilan</span>
            <span class="value">{{ $registration->nama_panggilan ?? '-' }}</span>
        </div>
        <div class="field">
            <span class="label">Jenis Kelamin</span>
            <span class="value">{{ $registration->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
        </div>
        <div class="field">
            <span class="label">Tempat, Tanggal Lahir</span>
            <span class="value">{{ $registration->tempat_lahir ?? '-' }}, {{ $registration->tanggal_lahir ? $registration->tanggal_lahir->format('d M Y') : '-' }}</span>
        </div>
        <div class="field">
            <span class="label">Anak Ke / Agama</span>
            <span class="value">Anak ke-{{ $registration->anak_ke ?? '-' }} | {{ $registration->agama ?? '-' }}</span>
        </div>
        <div class="field">
            <span class="label">Golongan Darah</span>
            <span class="value">{{ $registration->golongan_darah ?? '-' }}</span>
        </div>
        <div class="field">
            <span class="label">Berat / Tinggi Badan</span>
            <span class="value">
                {{ $registration->berat_badan ? $registration->berat_badan . ' kg' : '-' }} / 
                {{ $registration->tinggi_badan ? $registration->tinggi_badan . ' cm' : '-' }}
            </span>
        </div>
        <div class="field">
            <span class="label">Tinggal Dengan</span>
            <span class="value">
                @if ($registration->tinggal_dengan === 'orang_tua') Orang Tua
                @elseif ($registration->tinggal_dengan === 'asrama') Asrama
                @elseif ($registration->tinggal_dengan === 'saudara') Saudara
                @else -
                @endif
            </span>
        </div>
        <div class="field">
            <span class="label">No. WA / HP Calon Siswa/Wali</span>
            <span class="value">{{ $registration->no_telpon_wa ?? '-' }}</span>
        </div>
        <div class="field col-full">
            <span class="label">Alamat Rumah Lengkap</span>
            <span class="value">{{ $registration->alamat_rumah ?? '-' }}</span>
        </div>
    </div>

    <!-- Section B: Keterangan Pendidikan Sebelumnya -->
    <div class="section-title">🏫 PENDIDIKAN SEBELUMNYA</div>
    <div class="grid">
        <div class="field col-full">
            <span class="label">Asal Sekolah Sebelumnya</span>
            <span class="value">{{ $registration->asal_sekolah ?? '-' }}</span>
        </div>
        <div class="field col-full">
            <span class="label">Alamat Sekolah</span>
            <span class="value">{{ $registration->alamat_sekolah ?? '-' }}</span>
        </div>
        <div class="field">
            <span class="label">Nama Kepala Sekolah</span>
            <span class="value">{{ $registration->nama_kepala_sekolah ?? '-' }}</span>
        </div>
        <div class="field">
            <span class="label">NISN</span>
            <span class="value">{{ $registration->nisn ?? '-' }}</span>
        </div>
        <div class="field">
            <span class="label">Status Sekolah</span>
            <span class="value">
                @if ($registration->status_sekolah === 'negeri') Negeri
                @elseif ($registration->status_sekolah === 'swasta') Swasta
                @else -
                @endif
            </span>
        </div>
        <div class="field">
            <span class="label">Sistem Pendidikan & Lama Belajar</span>
            <span class="value">
                @if ($registration->sistem_pendidikan === 'pesantren') Pesantren
                @elseif ($registration->sistem_pendidikan === 'non_pesantren') Non-Pesantren
                @else -
                @endif
                ({{ $registration->lama_belajar ?? '-' }})
            </span>
        </div>
    </div>

    <!-- Section C: Data Orang Tua / Wali -->
    <div class="section-title">👨‍👩‍👦 DATA ORANG TUA / WALI</div>
    <div class="parent-columns">
        <!-- Ayah -->
        <div class="parent-sec">
            <h3>Data Ayah</h3>
            <div class="field">
                <span class="label">Nama Ayah</span>
                <span class="value">{{ $registration->nama_ayah ?? '-' }}</span>
            </div>
            <div class="field">
                <span class="label">Tempat, Tanggal Lahir</span>
                <span class="value">{{ $registration->ttl_ayah ?? '-' }}</span>
            </div>
            <div class="field">
                <span class="label">Pekerjaan & Pendidikan</span>
                <span class="value">{{ $registration->pekerjaan_ayah ?? '-' }} ({{ $registration->pendidikan_ayah ?? '-' }})</span>
            </div>
            <div class="field">
                <span class="label">Agama & No. HP</span>
                <span class="value">{{ $registration->agama_ayah ?? '-' }} | {{ $registration->hp_ayah ?? '-' }}</span>
            </div>
            <div class="field">
                <span class="label">Alamat Lengkap</span>
                <span class="value">{{ $registration->alamat_ayah ?? '-' }}</span>
            </div>
        </div>

        <!-- Ibu -->
        <div class="parent-sec">
            <h3>Data Ibu</h3>
            <div class="field">
                <span class="label">Nama Ibu</span>
                <span class="value">{{ $registration->nama_ibu ?? '-' }}</span>
            </div>
            <div class="field">
                <span class="label">Tempat, Tanggal Lahir</span>
                <span class="value">{{ $registration->ttl_ibu ?? '-' }}</span>
            </div>
            <div class="field">
                <span class="label">Pekerjaan & Pendidikan</span>
                <span class="value">{{ $registration->pekerjaan_ibu ?? '-' }} ({{ $registration->pendidikan_ibu ?? '-' }})</span>
            </div>
            <div class="field">
                <span class="label">Agama & No. HP</span>
                <span class="value">{{ $registration->agama_ibu ?? '-' }} | {{ $registration->hp_ibu ?? '-' }}</span>
            </div>
            <div class="field">
                <span class="label">Alamat Lengkap</span>
                <span class="value">{{ $registration->alamat_ibu ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Section D: Validasi Admin -->
    <div class="section-title">⚖️ VALIDASI ADMINISTRASI (ADMIN)</div>
    <div class="grid">
        <div class="field">
            <span class="label">Status Pendaftaran</span>
            <span class="value" style="font-weight: 800; color: #7c3aed; text-transform: uppercase;">
                @if ($registration->status === 'pending') Menunggu Review
                @elseif ($registration->status === 'reviewed') Sedang Direview
                @elseif ($registration->status === 'approved') Disetujui / Terdaftar
                @elseif ($registration->status === 'rejected') Ditolak
                @else -
                @endif
            </span>
        </div>
        <div class="field">
            <span class="label">Tanggal Validasi / Reviewer</span>
            <span class="value">
                @if ($registration->reviewed_at)
                    {{ $registration->reviewed_at->format('d M Y, H:i') }} oleh {{ $registration->reviewer->name ?? 'Admin' }}
                @else
                    Belum Divalidasi
                @endif
            </span>
        </div>
        <div class="field col-full">
            <span class="label">Catatan Validasi Admin</span>
            <span class="value">{{ $registration->catatan_admin ?? '-' }}</span>
        </div>
    </div>

    <!-- Page Footer -->
    <div class="footer text-right">
        Dicetak pada tanggal: {{ date('d M Y, H:i:s') }} | Dicetak otomatis via SAYS-APP.
    </div>

    <!-- Auto Print Script -->
    <script type="text/javascript">
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>
