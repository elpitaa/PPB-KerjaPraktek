<?php

namespace App\Filament\Dosen\Resources\PenerimaanKpResource\Pages;

use App\Filament\Dosen\Resources\PenerimaanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenerimaanKp extends EditRecord
{
    protected static string $resource = PenerimaanKpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
