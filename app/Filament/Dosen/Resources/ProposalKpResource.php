<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\ProposalKpResource\Pages;
use App\Filament\Dosen\Resources\ProposalKpResource\RelationManagers;
use App\Models\ProposalKp;
use App\Models\Dosen;
use App\Http\Controllers\SuratController;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\IconEntry;

class ProposalKpResource extends Resource
{
    protected static ?string $model = ProposalKp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Proposal KP';

    // // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Kerja Praktek';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mahasiswa.name')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pengajuanKp.nama_perusahaan')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('status_proposal')
                    ->label('Status Proposal')
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'diterima' => 'heroicon-m-check',
                        'revisi' => 'heroicon-s-arrow-path',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'secondary',
                        'diterima' => 'success',
                        'revisi' => 'warning',
                    }),
                TextColumn::make('keterangan')
                    ->label('Keterangan'),
                TextColumn::make('created_at')
                    ->label('dibuat')
                    ->sortable()
                    ->since()
                    ->dateTimeTooltip(),
                TextColumn::make('updated_at')
                    ->label('diperbarui')
                    ->sortable()
                    ->since()
                    ->dateTimeTooltip(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getTabs(): array
    {
        $dosen = Dosen::where('email', Auth::user()->email)->first();
        
        return [
            'all' => Tables\Tabs\Tab::make('Semua'),
            'pending' => Tables\Tabs\Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_proposal', 'pending')),
            'diterima' => Tables\Tabs\Tab::make('Diterima')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_proposal', 'diterima')),
            'revisi' => Tables\Tabs\Tab::make('Revisi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_proposal', 'revisi')),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Status Proposal')
                    ->schema(
                        [
                            IconEntry::make('status_proposal')
                                ->label('Status Proposal')
                                ->icon(fn(string $state): string => match ($state) {
                                    'pending' => 'heroicon-o-clock',
                                    'diterima' => 'heroicon-m-check',
                                    'revisi' => 'heroicon-s-arrow-path',
                                })
                                ->color(fn(string $state): string => match ($state) {
                                    'pending' => 'secondary',
                                    'diterima' => 'success',
                                    'revisi' => 'warning',
                                })
                                ->label(fn(string $state): string => match ($state) {
                                    'pending' => 'Pending',
                                    'diterima' => 'Diterima',
                                    'revisi' => 'Perlu Revisi',
                                }),
                        ]
                    ),

                Components\Section::make('Data Mahasiswa')
                    ->schema([
                        Components\TextEntry::make('mahasiswa.name')
                            ->label('Nama Mahasiswa'),
                        Components\TextEntry::make('mahasiswa.nim')
                            ->label('NIM Mahasiswa'),
                        Components\TextEntry::make('mahasiswa.jumlah_sks')
                            ->label('Sks Mahasiswa'),
                        Components\TextEntry::make('mahasiswa.ipk')
                            ->label('IPK Mahasiswa'),
                    ])
                    ->columns('2')
                    ->collapsible(),
                Components\Section::make('Data Perusahaan')
                    ->schema([
                        Components\TextEntry::make('pengajuanKp.nama_perusahaan')
                            ->label('Nama Perusahaan'),
                        Components\TextEntry::make('province.prov_name')
                            ->label('Provinsi'),
                        Components\TextEntry::make('city.city_name')
                            ->label('Kota'),
                        Components\TextEntry::make('district.dis_name')
                            ->label('Kecamatan'),
                        Components\TextEntry::make('subdistrict.subdis_name')
                            ->label('Kelurahan'),
                        // Components\TextEntry::make('postalcode.postal_code')
                        //     ->label('Kode Pos'),
                    ])
                    ->columns('2')
                    ->collapsible(),
                Components\Section::make('Data Proposal')
                    ->schema([
                        Components\TextEntry::make('keterangan')
                            ->label('Keterangan'),
                        // Components\TextEntry::make('file')
                        //     ->label('File Proposal')
                        //     ->url(fn(string $value): string => asset('storage/' . $value))
                        //     ->openUrlInNewTab(),
                        Components\TextEntry::make('file')
                            ->label('File Proposal')
                            ->url(fn($record): string => asset('storage/' . $record->file))
                            ->openUrlInNewTab(),

                    ])
                    ->columns('2')
                    ->collapsible(),
                
                Actions::make([
                    Action::make('download_surat')
                        ->label('Download Surat Persetujuan Proposal')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->visible(fn ($record) => $record->surat_persetujuan)
                        ->url(fn ($record) => asset('storage/' . $record->surat_persetujuan))
                        ->openUrlInNewTab(),
                    
                    Action::make('status_proposal_edit')
                        ->icon('heroicon-o-pencil-square')
                        ->label('Edit Status Proposal')
                        ->form([
                            Select::make('status_proposal')
                                ->label('Status Pengajuan')
                                ->options([
                                    'diterima' => 'Terima',
                                    'revisi' => 'Revisi',
                                ])
                                ->required()
                                ->native('false')
                                ->searchable(),
                            Textarea::make('keterangan')
                                ->label('Keterangan')
                                ->required(),

                        ])
                        ->action(function (array $data, ProposalKp $record): void {
                            $record->status_proposal = $data['status_proposal'];
                            $record->keterangan = $data['keterangan'];
                            
                            // Generate surat jika status diterima
                            if ($data['status_proposal'] === 'diterima') {
                                try {
                                    $suratPath = SuratController::generateSuratProposal($record);
                                    $record->surat_persetujuan = $suratPath;
                                } catch (\Exception $e) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Gagal generate surat: ' . $e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }
                            
                            $record->save();
                        })
                        ->successNotificationTitle('Status updated'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $dosen = Dosen::where('email', Auth::user()->email)->first();
        if (!$dosen) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
        return parent::getEloquentQuery()->whereHas('mahasiswa', function (Builder $query) use ($dosen) {
            $query->where('dosens', $dosen->id);
        });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposalKps::route('/'),
            'create' => Pages\CreateProposalKp::route('/create'),
            'edit' => Pages\EditProposalKp::route('/{record}/edit'),
            'view' => Pages\ViewProposalKps::route('/{record}'),
        ];
    }
}
