<?php

namespace App\Filament\Dosen\Resources\PengajuanKpResource\Pages;

use App\Filament\Dosen\Resources\PengajuanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanKp extends EditRecord
{
    protected static string $resource = PengajuanKpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
