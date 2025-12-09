<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Selesai KP</title>
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
        .ttd-container {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .ttd {
            display: table-cell;
            text-align: center;
            width: 50%;
        }
        .ttd-space {
            height: 60px;
        }
        .nilai-box {
            border: 2px solid #000;
            padding: 15px;
            margin: 20px 40px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>UNIVERSITAS TRUNOJOYO MADURA</h2>
        <h2>FAKULTAS TEKNIK</h2>
        <h2>PROGRAM STUDI TEKNIK INFORMATIKA</h2>
        <p>Alamat: Jl. Kampus No. 123, Kota, Provinsi 12345</p>
        <p>Telp: (021) 12345678 | Email: teknik@university.ac.id</p>
    </div>

    <div class="nomor-surat">
        <strong>SURAT KETERANGAN SELESAI KERJA PRAKTEK</strong><br>
        Nomor: {{ str_pad($sidang->id, 3, '0', STR_PAD_LEFT) }}/KP-SELESAI/FT/{{ date('Y') }}
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
        
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
            </table>
        </div>

        <p style="margin-top: 20px;">
            Telah menyelesaikan seluruh rangkaian kegiatan <strong>Kerja Praktek</strong> dan telah mengikuti Sidang Kerja Praktek pada:
        </p>

        <div class="data-mahasiswa">
            <table class="data">
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($sidang->tanggal)->locale('id')->translatedFormat('d F Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>: {{ $sidang->ruangan }}</td>
                </tr>
            </table>
        </div>

        @if($sidang->nilai)
        <div class="nilai-box">
            <h3 style="margin: 0;">NILAI AKHIR KERJA PRAKTEK</h3>
            <h1 style="margin: 10px 0; font-size: 36pt;">{{ number_format($sidang->nilai, 2) }}</h1>
            <p style="margin: 0;">
                <strong>
                    @if($sidang->nilai >= 80)
                        (A - SANGAT BAIK)
                    @elseif($sidang->nilai >= 70)
                        (B - BAIK)
                    @elseif($sidang->nilai >= 60)
                        (C - CUKUP)
                    @elseif($sidang->nilai >= 50)
                        (D - KURANG)
                    @else
                        (E - GAGAL)
                    @endif
                </strong>
            </p>
        </div>
        @endif

        <p style="margin-top: 20px;">
            Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="ttd-container">
        <div class="ttd">
            <p>Ketua Program Studi,</p>
            <div class="ttd-space"></div>
            <p><strong>Dr. [Nama Kaprodi]</strong></p>
            <p>NIP. -</p>
        </div>
        <div class="ttd">
            <p>{{ $pengajuan->city->city_name }}, {{ $tanggal }}</p>
            <p>Dosen Pembimbing,</p>
            <div class="ttd-space"></div>
            <p><strong>{{ $mahasiswa->dosen->name ?? 'Nama Dosen' }}</strong></p>
            <p>NIP. {{ $mahasiswa->dosen->nip ?? '-' }}</p>
        </div>
    </div>
</body>
</html>
