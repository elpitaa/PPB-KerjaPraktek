<?php

namespace App\Filament\Resources\DataDosenResource\Pages;

use App\Filament\Resources\DataDosenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataDosens extends ListRecords
{
    protected static string $resource = DataDosenResource::class;
    protected ?string $heading = 'Dosen';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
