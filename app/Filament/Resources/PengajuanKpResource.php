<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanKpResource\Pages;
use App\Filament\Resources\PengajuanKpResource\RelationManagers;
use App\Models\Pengajuan_kp as PengajuanKp;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\IconEntry;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class PengajuanKpResource extends Resource
{
    protected static ?string $model = PengajuanKp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pengajuan KP';

    // // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Kerja Praktek';
    protected static ?int $navigationSort = 0;

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
                TextColumn::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mahasiswa.name')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('status_pengajuan')
                    ->label('status pengajuan')
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'diterima' => 'heroicon-m-check',
                        'ditolak' => 'heroicon-m-x-mark',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'secondary',
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                    })
                    ->sortable(),
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
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Status Pengajuan')
                    ->schema(
                        [
                            IconEntry::make('status_pengajuan')
                                ->label('Status Pengajuan')
                                ->icon(fn(string $state): string => match ($state) {
                                    'pending' => 'heroicon-o-clock',
                                    'diterima' => 'heroicon-m-check',
                                    'ditolak' => 'heroicon-m-x-mark',
                                })
                                ->color(fn(string $state): string => match ($state) {
                                    'pending' => 'secondary',
                                    'diterima' => 'success',
                                    'ditolak' => 'danger',
                                })
                                ->label(fn(string $state): string => match ($state) {
                                    'pending' => 'Pending',
                                    'diterima' => 'Diterima',
                                    'ditolak' => 'Ditolak',
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
                        Components\TextEntry::make('nama_perusahaan')
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
                Actions::make([
                    Action::make('status_pengajuan_edit')
                        ->icon('heroicon-o-pencil-square')
                        ->label('Edit Status Pengajuan')
                        ->form([
                            Select::make('status_pengajuan')
                                ->label('Status Pengajuan')
                                ->options([
                                    'diterima' => 'Terima',
                                    'ditolak' => 'Tolak',
                                ])
                                ->required()
                                ->native('false')
                                ->searchable(),
                        ])
                        ->action(function (array $data, PengajuanKp $record): void {
                            $record->status_pengajuan = $data['status_pengajuan'];
                            $record->save();
                        })
                        ->successNotificationTitle('Status updated'),
                ]),
            ]);
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
            'index' => Pages\ListPengajuanKps::route('/'),
            'create' => Pages\CreatePengajuanKp::route('/create'),
            'edit' => Pages\EditPengajuanKp::route('/{record}/edit'),
            'view' => Pages\ViewPengajuanKp::route('/{record}'),
        ];
    }
}
