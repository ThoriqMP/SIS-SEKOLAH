<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi Pembayaran - {{ $invoice->no_invoice }}</title>
    <style>
        @page {
            margin: 30px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #222;
            line-height: 1.4;
        }
        .container {
            border: 2px solid #7B346C;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            background-color: #ffffff;
        }
        
        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #7B346C;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .logo-cell {
            width: 70px;
            vertical-align: middle;
        }
        .logo-cell img {
            max-width: 65px;
            max-height: 65px;
        }
        .school-cell {
            vertical-align: middle;
            padding-left: 10px;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #7B346C;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .school-address {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }
        .title-cell {
            vertical-align: middle;
            text-align: right;
            width: 220px;
        }
        .document-title {
            font-size: 22px;
            font-weight: bold;
            color: #7B346C;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .kwitansi-no {
            font-size: 11px;
            font-weight: bold;
            color: #444;
            margin-top: 3px;
        }

        /* Form Details Table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 8px 6px;
            vertical-align: top;
        }
        .label-col {
            width: 160px;
            font-weight: bold;
            color: #444;
        }
        .separator-col {
            width: 15px;
            font-weight: bold;
            color: #444;
        }
        .value-col {
            color: #111;
        }
        .terbilang-box {
            background-color: #F8F4F8;
            border-left: 4px solid #7B346C;
            padding: 8px 12px;
            font-weight: bold;
            font-style: italic;
            color: #5a264b;
            border-radius: 4px;
        }
        .amount-box {
            display: inline-block;
            background-color: #7B346C;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            padding: 8px 18px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        /* Items summary list */
        .items-list {
            margin: 0;
            padding-left: 18px;
        }
        .items-list li {
            margin-bottom: 3px;
        }

        /* Footer & Signatures */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .stamp-cell {
            width: 50%;
            vertical-align: bottom;
            padding-bottom: 10px;
        }
        .stamp-box {
            display: inline-block;
            border: 3px double #16a34a;
            color: #16a34a;
            padding: 6px 14px;
            border-radius: 10px;
            text-transform: uppercase;
            transform: rotate(-5deg);
            background-color: #f0fdf4;
        }
        .stamp-box-partial {
            display: inline-block;
            border: 3px double #d97706;
            color: #d97706;
            padding: 6px 14px;
            border-radius: 10px;
            text-transform: uppercase;
            transform: rotate(-5deg);
            background-color: #fffbeb;
        }
        .stamp-box-draft {
            display: inline-block;
            border: 3px double #2563eb;
            color: #2563eb;
            padding: 6px 14px;
            border-radius: 10px;
            text-transform: uppercase;
            transform: rotate(-5deg);
            background-color: #eff6ff;
        }
        .stamp-logo-img {
            max-width: 36px;
            max-height: 36px;
            vertical-align: middle;
            margin-right: 6px;
            display: inline-block;
        }
        .stamp-text {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1.5px;
            vertical-align: middle;
            display: inline-block;
        }
        .signature-cell {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .signature-date {
            font-size: 11px;
            color: #444;
            margin-bottom: 8px;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature-img {
            max-width: 110px;
            max-height: 70px;
            margin: 5px auto;
            display: block;
            background: transparent;
            mix-blend-mode: multiply;
        }
        .pimpinan-name {
            font-weight: bold;
            font-size: 13px;
            color: #111;
            margin-top: 4px;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if($invoice->profilSekolah && $invoice->profilSekolah->logo_path)
                        <img src="{{ storage_path('app/public/' . $invoice->profilSekolah->logo_path) }}" alt="Logo">
                    @else
                        <div style="width: 60px; height: 60px; border: 1px dashed #7B346C; border-radius: 50%; text-align: center; line-height: 60px; font-weight: bold; color: #7B346C;">SAYS</div>
                    @endif
                </td>
                <td class="school-cell">
                    <div class="school-name">{{ $invoice->profilSekolah->nama_sekolah ?? 'Sayyidah Homeschooling' }}</div>
                    <div class="school-address">{{ $invoice->profilSekolah->alamat ?? '-' }}</div>
                </td>
                <td class="title-cell">
                    <div class="document-title">KWITANSI</div>
                    <div class="kwitansi-no">No: KWT-{{ $invoice->no_invoice }}</div>
                </td>
            </tr>
        </table>

        <!-- Details -->
        <table class="details-table">
            <tr>
                <td class="label-col">Telah Diterima Dari</td>
                <td class="separator-col">:</td>
                <td class="value-col" style="font-size: 13px; font-weight: bold;">
                    {{ $invoice->siswa->nama_orang_tua ? $invoice->siswa->nama_orang_tua . ' (Orang Tua ' . $invoice->siswa->nama_siswa . ')' : $invoice->siswa->nama_siswa }}
                </td>
            </tr>
            <tr>
                <td class="label-col">Uang Sejumlah</td>
                <td class="separator-col">:</td>
                <td class="value-col">
                    <div class="amount-box">
                        Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-col">Terbilang</td>
                <td class="separator-col">:</td>
                <td class="value-col">
                    <div class="terbilang-box">
                        # {{ $terbilang }} #
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-col">Untuk Pembayaran</td>
                <td class="separator-col">:</td>
                <td class="value-col">
                    <ul class="items-list">
                        @foreach($invoice->invoiceDetails as $detail)
                            <li>
                                <strong>{{ $detail->layanan->nama_layanan ?? 'Layanan' }}</strong> 
                                @if($detail->deskripsi_tambahan) - {{ $detail->deskripsi_tambahan }} @endif
                                (Rp {{ number_format($detail->total_biaya, 0, ',', '.') }})
                            </li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </table>

        <!-- Footer / Signatures -->
        <table class="footer-table">
            <tr>
                <td class="stamp-cell">
                    @if($invoice->status == 'paid')
                        <div class="stamp-box">
                            @if($invoice->profilSekolah && $invoice->profilSekolah->logo_path)
                                <img src="{{ storage_path('app/public/' . $invoice->profilSekolah->logo_path) }}" class="stamp-logo-img" alt="Logo">
                            @endif
                            <span class="stamp-text">L U N A S</span>
                        </div>
                    @elseif($invoice->status == 'partially_paid')
                        <div class="stamp-box-partial">
                            @if(!empty($stampLogoPath) && file_exists($stampLogoPath))
                                <img src="{{ $stampLogoPath }}" class="stamp-logo-img" alt="Logo">
                            @elseif($invoice->profilSekolah && $invoice->profilSekolah->logo_path)
                                <img src="{{ storage_path('app/public/' . $invoice->profilSekolah->logo_path) }}" class="stamp-logo-img" alt="Logo">
                            @endif
                            <span class="stamp-text">DIBAYAR SETENGAH</span>
                        </div>
                    @else
                        <div class="stamp-box-draft">
                            @if(!empty($stampLogoPath) && file_exists($stampLogoPath))
                                <img src="{{ $stampLogoPath }}" class="stamp-logo-img" alt="Logo">
                            @elseif($invoice->profilSekolah && $invoice->profilSekolah->logo_path)
                                <img src="{{ storage_path('app/public/' . $invoice->profilSekolah->logo_path) }}" class="stamp-logo-img" alt="Logo">
                            @endif
                            <span class="stamp-text">KWITANSI</span>
                        </div>
                    @endif
                </td>
                <td class="signature-cell">
                    <div class="signature-date">
                        Bogor, {{ $invoice->payment_date ? $invoice->payment_date->format('d F Y') : ($invoice->tanggal_invoice ? $invoice->tanggal_invoice->format('d F Y') : date('d F Y')) }}
                    </div>
                    <div class="signature-title">Pimpinan / Bendahara</div>

                    @if(!empty($transparentSignaturePath) && file_exists($transparentSignaturePath))
                        <img src="{{ $transparentSignaturePath }}" alt="Signature" class="signature-img">
                    @elseif($invoice->profilSekolah && $invoice->profilSekolah->signature_path)
                        <img src="{{ storage_path('app/public/' . $invoice->profilSekolah->signature_path) }}" alt="Signature" class="signature-img">
                    @else
                        <div style="height: 55px;"></div>
                    @endif

                    <div class="pimpinan-name">{{ $invoice->profilSekolah->pimpinan_nama ?? 'Pimpinan Sayyidah School' }}</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
