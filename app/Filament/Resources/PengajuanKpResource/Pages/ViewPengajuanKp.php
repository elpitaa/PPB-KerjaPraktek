<?php

namespace App\Filament\Resources\PengajuanKpResource\Pages;

use App\Filament\Resources\PengajuanKpResource;
use Filament\Resources\Pages\ViewRecord;
// use Filament\Forms\Components\Tabs;
use Filament\Infolists\Components\TextEntry;

use Filament\Infolists\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;



class ViewPengajuanKp extends ViewRecord
{
    protected static string $resource = PengajuanKpResource::class;
    protected static ?string $title = 'Pengajuan KP';
    protected ?string $heading = 'Pengajuan KP';
    protected function getFormSchema(): array
    {
        return [];
    }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     // Tandai input sebagai submitted setelah data diisi
    //     if (!$this->record->proposal_submitted && isset($data['judul_proposal'])) {
    //         $data['proposal_submitted'] = true;
    //     }
    //     if (!$this->record->pelaksanaan_submitted && isset($data['lokasi_pelaksanaan'])) {
    //         $data['pelaksanaan_submitted'] = true;
    //     }

    //     return $data;
    // }
}
