<?php

namespace App\Filament\Mahasiswa\Resources\SidangKpResource\Pages;

use App\Filament\Mahasiswa\Resources\SidangKpResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSidangKp extends CreateRecord
{
    protected static string $resource = SidangKpResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static bool $canCreateAnother = false;
}
