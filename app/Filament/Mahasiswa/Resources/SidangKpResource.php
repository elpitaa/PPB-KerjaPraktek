<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\SidangKpResource\Pages;
use App\Filament\Mahasiswa\Resources\SidangKpResource\RelationManagers;
use App\Models\LaporanKp;
use App\Models\SidangKp;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

class SidangKpResource extends Resource
{
    protected static ?string $model = SidangKp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Sidang KP';

    // // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Kerja Praktek';
    protected static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        // Ambil data mahasiswa berdasarkan email user yang login
        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        
        return $form
            ->schema([
                Section::make('Data Pengajuan Mahasiswa')
                    ->schema([
                        TextInput::make('Nama Mahasiswa')
                            ->default($mahasiswa ? $mahasiswa->name : Auth::user()->name)
                            ->disabled(),
                        TextInput::make('NIM Mahasiswa')
                            ->label('NIM Mahasiswa')
                            ->default($mahasiswa ? $mahasiswa->nim : '-')
                            ->disabled(),
                        TextInput::make('Sks Mahasiswa')
                            ->label('Sks Mahasiswa')
                            ->default($mahasiswa ? $mahasiswa->jumlah_sks : '-')
                            ->disabled(),
                        TextInput::make('IPK Mahasiswa')
                            ->label('IPK Mahasiswa')
                            ->default($mahasiswa ? $mahasiswa->ipk : '-')
                            ->disabled(),

                    ])
                    ->columns('2'),
                Section::make('Data Sidang KP')
                    ->schema([
                        // Select::make('status_penerimaan')
                        //     ->label('Hasil Penerimaan Peruusahaan')
                        //     ->options([
                        //         'diterima' => 'Diterima',
                        //         'ditolak' => 'Ditolak',
                        //     ])
                        //     ->required()
                        //     ->native(false)
                        //     ->searchable()
                        //     ->selectablePlaceholder(false)
                        //     ->unique(),
                        Hidden::make('status_laporan')
                            ->default('pending')
                            ->required(),
                        Forms\Components\Select::make('id_pengajuan_kp')
                            ->label('Pilih Pengajuan KP')
                            ->helperText('Hanya menampilkan pengajuan yang laporan-nya sudah diterima dan belum dibuat sidang')
                            ->options(function() use ($mahasiswa) {
                                if (!$mahasiswa) return [];
                                
                                // Ambil id pengajuan yang sudah ada di sidang_kp
                                $sudahAdaSidang = SidangKp::pluck('id_pengajuan_kp')->toArray();
                                
                                return LaporanKp::join('pengajuan_kp', 'pengajuan_kp.id', '=', 'laporan_kp.id_pengajuan_kp')
                                    ->join('proposal_kps', 'proposal_kps.id_pengajuan_kp', '=', 'pengajuan_kp.id')
                                    ->join('penerimaan_kp', 'penerimaan_kp.id_pengajuan_kp', '=', 'pengajuan_kp.id')
                                    ->where('pengajuan_kp.id_mahasiswa', $mahasiswa->id)
                                    ->where('pengajuan_kp.status_pengajuan', 'diterima')
                                    ->where('proposal_kps.status_proposal', 'diterima')
                                    ->where('penerimaan_kp.status_penerimaan', 'diterima')
                                    ->where('laporan_kp.status_laporan', 'diterima')
                                    ->whereNotIn('pengajuan_kp.id', $sudahAdaSidang)
                                    ->pluck('pengajuan_kp.nama_perusahaan', 'pengajuan_kp.id')
                                    ->toArray();
                            })
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->selectablePlaceholder(false),

                        TextInput::make('ruangan')
                            ->label('Ruangan Sidang')
                            ->required(),
                        DateTimePicker::make('tanggal')
                            ->label('Tanggal Sidang')
                            ->required()
                            ->displayFormat('Y-m-d H:i:s')
                            ->placeholder('Pilih Tanggal dan Waktu')
                            ->native(false)
                            ->minDate(now()),
                        Select::make('penguji')
                            ->label('Penguji')
                            ->options(
                                Dosen::all()->pluck('name', 'id')
                            )
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->multiple(),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Sidang')
                            ->required(),
                    ])
                    ->columns('2'), // Membuat card selebar form    
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
            ]);
    }

    public static function getTabs(): array
    {
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

                    ])
                    ->columns('2')
                    ->collapsible(),
                
                Actions::make([
                    Action::make('download_surat')
                        ->label('Download Surat Selesai KP')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->visible(fn ($record) => $record->surat_selesai)
                        ->url(fn ($record) => asset('storage/' . $record->surat_selesai))
                        ->openUrlInNewTab(),
                ]),
                // Actions::make([
                //     Action::make('Input Nilai Akhir')
                //         ->icon('heroicon-o-pencil-square')
                //         ->label('Input Nilai Akhir')
                //         ->form([

                //             TextInput::make('nilai')
                //                 ->label('Nilai Akhir')
                //                 ->required()
                //                 ->step('0.01')
                //                 ->numeric(),

                //             Textarea::make('keterangan')
                //                 ->label('Keterangan')
                //                 ->required(),

                //         ])
                //         ->action(function (array $data, SidangKp $record): void {
                //             $record->nilai = $data['nilai'];
                //             $record->keterangan = $data['keterangan'];
                //             $record->save();
                //         })
                //         ->successNotificationTitle('Data updated'),
                // ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $mahasiswa = Mahasiswa::where('email', Auth::user()->email)->first();
        if (!$mahasiswa) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
        return parent::getEloquentQuery()->whereHas('pengajuanKp', function (Builder $query) use ($mahasiswa) {
            $query->where('id_mahasiswa', $mahasiswa->id);
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
