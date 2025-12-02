<?php

namespace App\Filament\Mahasiswa\Resources\PenerimaanKpResource\Pages;

use App\Filament\Mahasiswa\Resources\PenerimaanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPenerimaanKps extends ListRecords
{
    protected static string $resource = PenerimaanKpResource::class;

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
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_penerimaan', 'diterima')),
            'Ditolak' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_penerimaan', 'ditolak')),
        ];
    }
}
