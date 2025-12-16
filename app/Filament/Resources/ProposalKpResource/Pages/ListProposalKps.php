<?php

namespace App\Filament\Resources\ProposalKpResource\Pages;

use App\Filament\Resources\ProposalKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProposalKps extends ListRecords
{
    protected static string $resource = ProposalKpResource::class;

    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'Diterima' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_proposal', 'diterima')),
            'revisi' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_proposal', 'revisi')),
            'Pending' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status_proposal', 'pending')),
        ];
    }
}
