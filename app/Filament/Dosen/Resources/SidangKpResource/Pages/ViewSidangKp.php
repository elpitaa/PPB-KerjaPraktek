<?php

namespace App\Filament\Dosen\Resources\SidangKpResource\Pages;

use App\Filament\Dosen\Resources\SidangKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;

class ViewSidangKp extends ViewRecord
{
    protected static string $resource = SidangKpResource::class;

    protected function getFormSchema(): array
    {
        return [];
    }
}
