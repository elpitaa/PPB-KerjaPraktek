<?php

namespace App\Filament\Mahasiswa\Resources\PenerimaanKpResource\Pages;

use App\Filament\Mahasiswa\Resources\PenerimaanKpResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class EditPenerimaanKp extends EditRecord
{
    protected static string $resource = PenerimaanKpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
