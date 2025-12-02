<?php

namespace App\Filament\Mahasiswa\Resources\LaporanKpResource\Pages;

use App\Filament\Mahasiswa\Resources\LaporanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporanKp extends CreateRecord
{
    protected static string $resource = LaporanKpResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
