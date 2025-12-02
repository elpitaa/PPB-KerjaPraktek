<?php

namespace App\Filament\Mahasiswa\Resources\LaporanKpResource\Pages;

use App\Filament\Mahasiswa\Resources\LaporanKpResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class ListLaporanKps extends ListRecords
{
    protected static string $resource = LaporanKpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

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
