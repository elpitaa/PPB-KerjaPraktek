<?php

namespace App\Filament\Resources\LaporanKpResource\Pages;

use App\Filament\Resources\LaporanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLaporanKps extends ListRecords
{
    protected static string $resource = LaporanKpResource::class;
    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'Diterima' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_laporan', 'diterima')),
            'Ditolak' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_laporan', 'ditolak')),
            'Revisi' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_laporan', 'revisi')),
            'Pending' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_laporan', 'pending')),
        ];
    }
}
