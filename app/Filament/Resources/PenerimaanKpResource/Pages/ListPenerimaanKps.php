<?php

namespace App\Filament\Resources\PenerimaanKpResource\Pages;

use App\Filament\Resources\PenerimaanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPenerimaanKps extends ListRecords
{
    protected static string $resource = PenerimaanKpResource::class;

    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'Diterima' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_penerimaan', 'diterima')),
            'Ditolak' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_penerimaan', 'ditolak')),
        ];
    }
}
