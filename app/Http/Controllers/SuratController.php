<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan_kp;
use App\Models\ProposalKp;
use App\Models\LaporanKp;
use App\Models\SidangKp;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    /**
     * Generate Surat Persetujuan Pengajuan KP
     */
    public static function generateSuratPengajuan(Pengajuan_kp $pengajuan)
    {
        $data = [
            'mahasiswa' => $pengajuan->mahasiswa,
            'pengajuan' => $pengajuan,
            'tanggal' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('surat.persetujuan-pengajuan', $data);
        $filename = 'surat-persetujuan-pengajuan-' . $pengajuan->mahasiswa->nim . '-' . time() . '.pdf';
        $path = 'surat/' . $filename;
        
        $pdf->save(storage_path('app/public/' . $path));
        
        return $path;
    }

    /**
     * Generate Surat Persetujuan Proposal KP
     */
    public static function generateSuratProposal(ProposalKp $proposal)
    {
        $data = [
            'mahasiswa' => $proposal->mahasiswa,
            'proposal' => $proposal,
            'pengajuan' => $proposal->pengajuanKp,
            'tanggal' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('surat.persetujuan-proposal', $data);
        $filename = 'surat-persetujuan-proposal-' . $proposal->mahasiswa->nim . '-' . time() . '.pdf';
        $path = 'surat/' . $filename;
        
        $pdf->save(storage_path('app/public/' . $path));
        
        return $path;
    }

    /**
     * Generate Surat Persetujuan Laporan KP
     */
    public static function generateSuratLaporan(LaporanKp $laporan)
    {
        $data = [
            'mahasiswa' => $laporan->mahasiswa,
            'laporan' => $laporan,
            'pengajuan' => $laporan->pengajuanKp,
            'tanggal' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('surat.persetujuan-laporan', $data);
        $filename = 'surat-persetujuan-laporan-' . $laporan->mahasiswa->nim . '-' . time() . '.pdf';
        $path = 'surat/' . $filename;
        
        $pdf->save(storage_path('app/public/' . $path));
        
        return $path;
    }

    /**
     * Generate Surat Selesai KP
     */
    public static function generateSuratSelesai(SidangKp $sidang)
    {
        $data = [
            'mahasiswa' => $sidang->mahasiswa,
            'sidang' => $sidang,
            'pengajuan' => $sidang->pengajuanKp,
            'tanggal' => now()->locale('id')->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('surat.selesai-kp', $data);
        $filename = 'surat-selesai-kp-' . $sidang->mahasiswa->nim . '-' . time() . '.pdf';
        $path = 'surat/' . $filename;
        
        $pdf->save(storage_path('app/public/' . $path));
        
        return $path;
    }
}
