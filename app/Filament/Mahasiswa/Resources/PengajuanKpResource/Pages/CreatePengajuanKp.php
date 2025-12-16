<?php

namespace App\Filament\Mahasiswa\Resources\PengajuanKpResource\Pages;

use App\Filament\Mahasiswa\Resources\PengajuanKpResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Mahasiswa;

class CreatePengajuanKp extends CreateRecord
{
    protected static string $resource = PengajuanKpResource::class;
    protected static ?string $title = 'Buat Pengajuan KP';
    protected ?string $heading = 'Buat Pengajuan KP';
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static bool $canCreateAnother = false;

    protected function beforeCreate(): void
    {
        // Ambil data mahasiswa berdasarkan email user yang login
        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        
        if (!$mahasiswa || $mahasiswa->jumlah_sks < 70) {
            Notification::make()
                ->danger()
                ->title('Jumlah SKS Tidak Mencukupi')
                ->body('Anda harus memiliki minimal 70 SKS untuk mengajukan KP.')
                ->persistent()
                ->actions([
                    Action::make('ok')
                        ->button()
                        ->close(),
                ])
                ->send();

            $this->halt();
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Simpan link_perusahaan ke data mahasiswa
        if (isset($data['link_perusahaan'])) {
            $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
            if ($mahasiswa) {
                $mahasiswa->link_perusahaan = $data['link_perusahaan'];
                $mahasiswa->save();
            }
            // Hapus dari data pengajuan karena bukan kolom di table pengajuan_kp
            unset($data['link_perusahaan']);
        }
        
        return $data;
    }
}
