<?php

namespace App\Filament\Dosen\Resources\ProposalKpResource\Pages;

use App\Filament\Dosen\Resources\ProposalKpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;

class ViewProposalKps extends ViewRecord
{
    protected static string $resource = ProposalKpResource::class;

    protected function getFormSchema(): array
    {
        return [];
    }
}
