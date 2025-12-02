<?php

namespace App\Filament\Resources\DataDosenResource\Pages;

use App\Filament\Resources\DataDosenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDataDosen extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = DataDosenResource::class;
}
