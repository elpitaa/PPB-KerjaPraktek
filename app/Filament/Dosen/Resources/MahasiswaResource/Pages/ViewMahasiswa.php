<?php

namespace App\Filament\Dosen\Resources\MahasiswaResource\Pages;

use App\Filament\Dosen\Resources\MahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;

class ViewMahasiswa extends ViewRecord
{
    protected static string $resource = MahasiswaResource::class;

    protected function getFormSchema(): array
    {
        return [];
    }
}
