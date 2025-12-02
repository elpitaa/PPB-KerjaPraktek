<?php

namespace App\Filament\Mahasiswa\Resources\PengajuanKpResource\Pages;

use App\Filament\Mahasiswa\Resources\PengajuanKpResource;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
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
