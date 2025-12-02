<?php

namespace App\Filament\Mahasiswa\Resources\PenerimaanKpResource\Pages;

use App\Filament\Mahasiswa\Resources\PenerimaanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPenerimaanKp extends ViewRecord
{
    protected static string $resource = PenerimaanKpResource::class;

    protected function getFormSchema(): array
    {
        return [];
    }
}
