<?php

namespace App\Filament\Resources\LaporanKpResource\Pages;

use App\Filament\Resources\LaporanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;

class ViewLaporanKp extends ViewRecord
{
    protected static string $resource = LaporanKpResource::class;

    protected function getFormSchema(): array
    {
        return [];
    }
}
