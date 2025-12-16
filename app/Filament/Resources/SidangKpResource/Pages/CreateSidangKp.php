<?php

namespace App\Filament\Resources\SidangKpResource\Pages;

use App\Filament\Resources\SidangKpResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSidangKp extends CreateRecord
{
    protected static string $resource = SidangKpResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
