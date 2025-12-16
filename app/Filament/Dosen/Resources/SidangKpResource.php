<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\SidangKpResource\Pages;
use App\Filament\Dosen\Resources\SidangKpResource\RelationManagers;
use App\Models\SidangKp;
use App\Models\Dosen;
use App\Http\Controllers\SuratController;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\IconEntry;
use Filament\Tables\Columns\TextColumn;

class SidangKpResource extends Resource
{
    protected static ?string $model = SidangKp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Sidang KP';

    // // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Kerja Praktek';
    protected static ?int $navigationSort = 6;

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
                TextColumn::make('tanggal')
                    ->label('Tanggal Sidang')
                    ->searchable()
                    ->sortable()
                    ->dateTimeTooltip(),
                TextColumn::make('ruangan')
                    ->label('Ruangan Sidang')
                    ->searchable()
                    ->sortable(),
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
            ])
        ;
    }

    public static function getTabs(): array
    {
        $dosen = Dosen::where('email', Auth::user()->email)->first();
        
        return [
            'all' => Tables\Tabs\Tab::make('Semua'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

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
                Components\Section::make('Data Sidang Kp')
                    ->schema([
                        Components\TextEntry::make('tanggal')
                            ->label('Tanggal Sidang')
                            ->dateTimeTooltip(),
                        Components\TextEntry::make('ruangan')
                            ->label('Ruangan Sidang'),
                        Components\TextEntry::make('penguji')
                            ->label('Penguji')
                            ->formatStateUsing(function ($state) {
                                $stateArray = explode(',', $state);
                                return Dosen::whereIn('id', $stateArray)->pluck('name')->join(', ');
                            }),
                        Components\TextEntry::make('nilai')
                            ->label('Nilai Akhir')
                            ->default('Belum ada nilai')
                            ->suffix(function ($state) {
                                if ($state) {
                                    if ($state >= 85) return ' (A)';
                                    if ($state >= 80) return ' (B+)';
                                    if ($state >= 75) return ' (B)';
                                    if ($state >= 70) return ' (C+)';
                                    if ($state >= 65) return ' (C)';
                                    if ($state >= 60) return ' (D)';
                                    return ' (E)';
                                }
                                return '';
                            }),
                        Components\TextEntry::make('keterangan')
                            ->label('Keterangan'),
                        Components\TextEntry::make('file_mahasiswa')
                            ->label('File Mahasiswa')
                            ->url(fn($record) => $record->file_mahasiswa ? asset('storage/' . $record->file_mahasiswa) : null)
                            ->openUrlInNewTab()
                            ->default('-')
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'gray'),

                    ])
                    ->columns('2')
                    ->collapsible(),
                
                Actions::make([
                    Action::make('download_file_mahasiswa')
                        ->label('Download File Mahasiswa')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('primary')
                        ->visible(fn ($record) => $record->file_mahasiswa)
                        ->url(fn ($record) => asset('storage/' . $record->file_mahasiswa))
                        ->openUrlInNewTab(),
                    Action::make('input_nilai')
                        ->label('Input Nilai & Generate Surat Selesai KP')
                        ->icon('heroicon-o-academic-cap')
                        ->visible(fn ($record) => !$record->nilai)
                        ->form([
                            Forms\Components\TextInput::make('nilai')
                                ->label('Nilai Akhir (0-100)')
                                ->numeric()
                                ->required()
                                ->minValue(0)
                                ->maxValue(100)
                                ->suffix('/100'),
                            Textarea::make('keterangan')
                                ->label('Keterangan')
                                ->rows(3),
                        ])
                        ->action(function (array $data, $record) {
                            $record->nilai = $data['nilai'];
                            if (isset($data['keterangan'])) {
                                $record->keterangan = $data['keterangan'];
                            }
                            
                            // Auto-generate Surat Selesai KP
                            try {
                                $suratPath = SuratController::generateSuratSelesai($record);
                                $record->surat_selesai = $suratPath;
                                $record->save();
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Berhasil!')
                                    ->body('Nilai berhasil disimpan dan Surat Selesai KP telah digenerate.')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                $record->save(); // Save nilai even if PDF generation fails
                                
                                \Filament\Notifications\Notification::make()
                                    ->title('Nilai Tersimpan')
                                    ->body('Nilai berhasil disimpan, tetapi gagal generate surat: ' . $e->getMessage())
                                    ->warning()
                                    ->send();
                            }
                        }),
                    
                    Action::make('download_surat')
                        ->label('Download Surat Selesai KP')
                        ->icon('heroicon-o-document-arrow-down')
                        ->visible(fn ($record) => $record->surat_selesai)
                        ->url(fn ($record) => asset('storage/' . $record->surat_selesai))
                        ->openUrlInNewTab(),
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
            'index' => Pages\ListSidangKps::route('/'),
            'create' => Pages\CreateSidangKp::route('/create'),
            'edit' => Pages\EditSidangKp::route('/{record}/edit'),
            'view' => Pages\ViewSidangKp::route('/{record}'),
        ];
    }
}
