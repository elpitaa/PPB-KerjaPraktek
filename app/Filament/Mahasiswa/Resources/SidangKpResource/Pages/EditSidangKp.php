<?php

namespace App\Filament\Mahasiswa\Resources\SidangKpResource\Pages;

use App\Filament\Mahasiswa\Resources\SidangKpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSidangKp extends EditRecord
{
    protected static string $resource = SidangKpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
