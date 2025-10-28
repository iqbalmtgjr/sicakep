<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kinerja - {{ $penilaian->user->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }

        /* .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        } */

        .header {
            /* position: fixed; */
            top: 0;
            left: 0;
            right: 0;
            bottom: 20px;
            height: 100px;
        }

        .header img {
            width: 119%;
            height: auto;
            margin-left: -65px;
            margin-top: -60px;
            object-fit: contain;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .content {
            margin-top: 40px;
            margin-bottom: 30px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .info-table .label {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 30%;
        }

        .nilai-section {
            margin-top: 30px;
            text-align: center;
            color: {{ $kategoriData ? ($kategoriData->badge == 'success' ? '#198754' : ($kategoriData->badge == 'primary' ? '#0d6efd' : ($kategoriData->badge == 'warning' ? '#fd7e14' : ($kategoriData->badge == 'danger' ? '#dc3545' : '#333')))) : '#333' }};
        }

        .nilai-box {
            display: inline-block;
            border: 2px solid {{ $kategoriData ? ($kategoriData->badge == 'success' ? '#198754' : ($kategoriData->badge == 'primary' ? '#0d6efd' : ($kategoriData->badge == 'warning' ? '#fd7e14' : ($kategoriData->badge == 'danger' ? '#dc3545' : '#333')))) : '#333' }};
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            color: {{ $kategoriData ? ($kategoriData->badge == 'success' ? '#198754' : ($kategoriData->badge == 'primary' ? '#0d6efd' : ($kategoriData->badge == 'warning' ? '#fd7e14' : ($kategoriData->badge == 'danger' ? '#dc3545' : '#333')))) : '#333' }};
        }

        .nilai-box .nilai {
            font-size: 36px;
            font-weight: bold;
            color: {{ $kategoriData ? ($kategoriData->badge == 'success' ? '#198754' : ($kategoriData->badge == 'primary' ? '#0d6efd' : ($kategoriData->badge == 'warning' ? '#fd7e14' : ($kategoriData->badge == 'danger' ? '#dc3545' : '#333')))) : '#333' }};
        }

        .nilai-box .kategori {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .catatan {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #333;
        }

        .catatan h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .signature {
            margin-top: 50px;
            display: table;
            width: 100%;
        }

        .signature .left,
        .signature .right {
            display: table-cell;
            width: 50%;
            text-align: center;
        }

        .signature .right {
            text-align: center;
        }

        .title-section {
            text-align: center;
        }
    </style>
</head>

<body>
    {{-- <div class="header">
        <img src="{{ asset('kop-surat-disperindag.png') }}" alt="header-kop">
    </div> --}}
    <header class="header" aria-label="Header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents('kop-surat-disperindag.png')) }}"
            alt="Logo HSS">
    </header>

    <div class="title-section">
        <h2> HASIL KINERJA EVALUASI <br> {{ strtoupper($penilaian->periode->nama_periode) }} TAHUN
            {{ strtoupper($penilaian->periode->tahun) }}
        </h2>
    </div>

    <div class="content">
        <table class="info-table">
            <tr>
                <td class="label">Nama Pegawai</td>
                <td>{{ $penilaian->user->name }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td>{{ $penilaian->user->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td>{{ $penilaian->user->jabatan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Periode</td>
                <td>{{ $penilaian->periode->nama_periode }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Penilaian</td>
                <td>{{ $penilaian->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Dinilai Oleh</td>
                <td>{{ $penilaian->penilai->name }} ({{ $penilaian->penilai->jabatan ?? $penilaian->penilai->role }})
                </td>
            </tr>
        </table>

        <div class="nilai-section">
            <div class="nilai-box">
                <div class="nilai">{{ number_format($nilai, 2) }}</div>
                <div class="kategori">{{ $kategoriData ? $kategoriData->kategori : 'Tidak Diketahui' }}</div>
            </div>
        </div>

        @if ($kategoriData)
            <div class="catatan">
                <h4>Keterangan:</h4>
                <p>{{ $kategoriData->pesan }}</p>
            </div>
        @endif

        @if ($penilaian->catatan)
            <div class="catatan">
                <h4>Catatan Penilai:</h4>
                <p>{{ $penilaian->catatan }}</p>
            </div>
        @endif
    </div>

    {{-- <div class="signature">
        <div class="left">
            <p>Pegawai Yang Dinilai</p>
            <br><br><br>
            <p><u>{{ $penilaian->user->name }}</u></p>
            <p>NIP: {{ $penilaian->user->nip ?? '-' }}</p>
        </div>
        <div class="right">
            <p>Penilai</p>
            <br><br><br>
            <p><u>{{ $penilaian->penilai->name }}</u></p>
            <p>{{ $penilaian->penilai->jabatan ?? $penilaian->penilai->role }}</p>
        </div>
    </div> --}}

    <div class="title-section" style="margin-top: 130px">
        <h2>LAMPIRAN</h2>
    </div>

    <div>
        <table class="info-table">
            <tr>
                <td class="label">Sasaran Strategis</td>
                <td>{{ $penilaian->targetKinerja->indikatorKinerja->sasaran_strategis }}</td>
            </tr>
            <tr>
                <td class="label">Indikator Kinerja Sasaran</td>
                <td>{{ $penilaian->targetKinerja->indikatorKinerja->nama_indikator }}</td>
            </tr>
            <tr>
                <td class="label">Target</td>
                <td>{{ $penilaian->targetKinerja->indikatorKinerja->target }}%</td>
            </tr>
            <tr>
                <td class="label">Program</td>
                <td>{{ $penilaian->targetKinerja->indikatorKinerja->sasaran_program }}</td>
            </tr>
            <tr>
                <td class="label">Indikator Kinerja Program</td>
                <td>{{ $penilaian->targetKinerja->indikatorKinerja->indikator_program }}</td>
            </tr>
            <tr>
                <td class="label">Target</td>
                <td>{{ $penilaian->targetKinerja->target }}%</td>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis pada {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>

</html>
