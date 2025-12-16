<?php

namespace App\Filament\Mahasiswa\Resources\ProposalKpResource\Pages;

use App\Filament\Mahasiswa\Resources\ProposalKpResource;
use Filament\Actions;
use App\Models\Pengajuan_kp;
use App\Models\ProposalKp;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;

class CreateProposalKp extends CreateRecord
{
    protected static string $resource = ProposalKpResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static bool $canCreateAnother = false;
}
