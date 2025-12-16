<?php

namespace App\Filament\Resources\DataDosenResource\Pages;

use App\Filament\Resources\DataDosenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDataDosen extends EditRecord
{
    protected static string $resource = DataDosenResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
