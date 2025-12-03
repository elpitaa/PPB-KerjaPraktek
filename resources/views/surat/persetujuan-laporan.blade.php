<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Persetujuan Laporan KP</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 40px;
            font-size: 12pt;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h2 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
        }
        .kop-surat p {
            margin: 2px 0;
            font-size: 10pt;
        }
        .nomor-surat {
            text-align: center;
            margin: 20px 0;
        }
        .content {
            text-align: justify;
            line-height: 1.8;
            margin: 20px 0;
        }
        .data-mahasiswa {
            margin-left: 40px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
        }
        table.data td {
            padding: 5px;
            vertical-align: top;
        }
        table.data td:first-child {
            width: 150px;
        }
        .ttd {
            margin-top: 40px;
            float: right;
            text-align: center;
        }
        .ttd-space {
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>UNIVERSITAS [NAMA UNIVERSITAS]</h2>
        <h2>FAKULTAS TEKNIK</h2>
        <h2>PROGRAM STUDI TEKNIK INFORMATIKA</h2>
        <p>Alamat: Jl. Kampus No. 123, Kota, Provinsi 12345</p>
        <p>Telp: (021) 12345678 | Email: teknik@university.ac.id</p>
    </div>

    <div class="nomor-surat">
        <strong>SURAT PERSETUJUAN LAPORAN KERJA PRAKTEK</strong><br>
        Nomor: {{ str_pad($laporan->id, 3, '0', STR_PAD_LEFT) }}/KP-LAP/FT/{{ date('Y') }}
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini Dosen Pembimbing Kerja Praktek, menyatakan bahwa:</p>
        
        <div class="data-mahasiswa">
            <table class="data">
                <tr>
                    <td>Nama</td>
                    <td>: {{ $mahasiswa->name }}</td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td>: {{ $mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>: Teknik Informatika</td>
                </tr>
                <tr>
                    <td>Perusahaan</td>
                    <td>: {{ $pengajuan->nama_perusahaan }}</td>
                </tr>
                <tr>
                    <td>Judul Laporan</td>
                    <td>: {{ $laporan->judul }}</td>
                </tr>
            </table>
        </div>

        <p style="margin-top: 20px;">
            Telah menyelesaikan dan menyerahkan <strong>Laporan Kerja Praktek</strong> yang telah diperiksa, diperbaiki, dan disetujui untuk dilanjutkan ke tahap sidang.
        </p>

        <p style="margin-top: 20px;">
            Dengan ini mahasiswa yang bersangkutan diperkenankan untuk mengikuti Sidang Kerja Praktek.
        </p>

        <p style="margin-top: 20px;">
            Demikian surat persetujuan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="ttd">
        <p>{{ $pengajuan->city->city_name }}, {{ $tanggal }}</p>
        <p>Dosen Pembimbing,</p>
        <div class="ttd-space"></div>
        <p><strong>{{ $mahasiswa->dosen->name ?? 'Nama Dosen' }}</strong></p>
        <p>NIP. {{ $mahasiswa->dosen->nip ?? '-' }}</p>
    </div>

    <div style="clear: both;"></div>
</body>
</html>
