<?php

namespace App\Filament\Dosen\Resources\SidangKpResource\Pages;

use App\Filament\Dosen\Resources\SidangKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSidangKps extends ListRecords
{
    protected static string $resource = SidangKpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
