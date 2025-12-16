<?php

namespace App\Filament\Resources\PengajuanKpResource\Pages;

use App\Filament\Resources\PengajuanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPengajuanKps extends ListRecords
{
    protected static string $resource = PengajuanKpResource::class;
    protected static ?string $title = 'Pengajuan KP';
    protected ?string $heading = 'Pengajuan KP';
    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'Diterima' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_pengajuan', 'diterima')),
            'Ditolak' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_pengajuan', 'ditolak')),
            'Pending' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_pengajuan', 'pending')),
        ];
    }
}
