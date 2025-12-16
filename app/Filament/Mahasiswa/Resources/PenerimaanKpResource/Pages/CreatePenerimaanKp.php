<?php

namespace App\Filament\Mahasiswa\Resources\PenerimaanKpResource\Pages;

use App\Filament\Mahasiswa\Resources\PenerimaanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenerimaanKp extends CreateRecord
{
    protected static string $resource = PenerimaanKpResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static bool $canCreateAnother = false;
}
