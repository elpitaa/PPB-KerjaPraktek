<?php

namespace App\Filament\Resources\PengajuanKpResource\Pages;

use App\Filament\Resources\PengajuanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanKp extends EditRecord
{
    protected static string $resource = PengajuanKpResource::class;
    protected static ?string $title = 'Pengajuan KP';
    protected ?string $heading = 'Pengajuan KP';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
