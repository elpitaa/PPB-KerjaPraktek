<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\LaporanKpResource\Pages;
use App\Filament\Mahasiswa\Resources\LaporanKpResource\RelationManagers;
use App\Models\LaporanKp;
use App\Models\PenerimaanKP;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Illuminate\Support\Facades\Auth;

class LaporanKpResource extends Resource
{
    protected static ?string $model = LaporanKp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    // // protected static ?string $slug = 'mahasiswa';

    // // Ganti label di navigasi
    protected static ?string $navigationLabel = 'Laporan KP';

    // // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Kerja Praktek';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pengajuan Mahasiswa')
                    ->schema([
                        TextInput::make('Nama Mahasiswa')
                            ->default(Auth::user()->name)
                            ->default(auth::user()->name)
                            ->disabled(),
                        TextInput::make('NIM Mahasiswa')
                            ->label('NIM Mahasiswa')
                            ->default(auth::user()->nim)
                            ->disabled(),
                        TextInput::make('Sks Mahasiswa')
                            ->label('Sks Mahasiswa')
                            ->default(auth::user()->jumlah_sks)
                            ->disabled(),
                        TextInput::make('IPK Mahasiswa')
                            ->label('IPK Mahasiswa')
                            ->default(auth::user()->ipk)
                            ->disabled(),

                    ])
                    ->columns('2'),
                Section::make('Data Laporan KP')
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
                            ->options(
                                PenerimaanKP::join('pengajuan_kp', 'pengajuan_kp.id', '=', 'penerimaan_kp.id_pengajuan_kp')
                                    ->join('proposal_kps', 'proposal_kps.id_pengajuan_kp', '=', 'pengajuan_kp.id')
                                    ->where('pengajuan_kp.id_mahasiswa', Auth::user()->id)
                                    ->where('pengajuan_kp.status_pengajuan', 'diterima')
                                    ->where('proposal_kps.status_proposal', 'diterima')
                                    ->where('penerimaan_kp.status_penerimaan', 'diterima')
                                    ->pluck('pengajuan_kp.nama_perusahaan', 'pengajuan_kp.id')
                                    ->toArray()
                            )
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->selectablePlaceholder(false),

                        TextInput::make('judul')
                            ->label('Judul Laporan')
                            ->required(),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Laporan')
                            ->required(),
                        Forms\Components\FileUpload::make('file')
                            ->label('Dokumen Laporan KP')
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->openable()
                            ->downloadable(),
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
                TextColumn::make('judul')
                    ->label('Judul Laporan KP')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('status_laporan')
                    ->label('Status Laporan')
                    ->icon(fn(string $state): string => match ($state) {
                        'ditolak' => 'heroicon-m-x-mark',
                        'diterima' => 'heroicon-m-check',
                        'revisi' => 'heroicon-s-arrow-path',
                        'pending' => 'heroicon-o-clock',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'ditolak' => 'danger',
                        'diterima' => 'success',
                        'revisi' => 'warning',
                        'pending' => 'secondary',
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
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Status Penerimaan')
                    ->schema(
                        [
                            IconEntry::make('status_laporan')
                                ->label('Status Proposal')
                                ->icon(fn(string $state): string => match ($state) {
                                    'ditolak' => 'heroicon-m-x-mark',
                                    'diterima' => 'heroicon-m-check',
                                    'revisi' => 'heroicon-s-arrow-path',
                                    'pending' => 'heroicon-o-clock',
                                })
                                ->color(fn(string $state): string => match ($state) {
                                    'ditolak' => 'danger',
                                    'diterima' => 'success',
                                    'revisi' => 'warning',
                                    'pending' => 'secondary',
                                })
                                ->label(fn(string $state): string => match ($state) {
                                    'ditolak' => 'Ditolak',
                                    'diterima' => 'Diterima',
                                    'revisi' => 'Revisi',
                                    'pending' => 'Pending',
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
                Components\Section::make('Data Laporan Kp')
                    ->schema([
                        Components\TextEntry::make('keterangan')
                            ->label('Keterangan'),
                        // Components\TextEntry::make('file')
                        //     ->label('File Proposal')
                        //     ->url(fn(string $value): string => asset('storage/' . $value))
                        //     ->openUrlInNewTab(),
                        Components\TextEntry::make('file')
                            ->label('Dokumen Laporan Kp')
                            ->url(fn($record): string => asset('storage/' . $record->file))
                            ->openUrlInNewTab(),

                    ])
                    ->columns('2')
                    ->collapsible(),
                Actions::make([
                    // Action::make('status_laporan_edit')
                    //     ->icon('heroicon-o-pencil-square')
                    //     ->label('Edit Status Proposal')
                    //     ->form([
                    //         Select::make('status_laporan')
                    //             ->label('Status Laporan')
                    //             ->options([
                    //                 'ditolak' => 'Ditolak',
                    //                 'diterima' => 'Diterima',
                    //                 'revisi' => 'Revisi',
                    //             ])
                    //             ->required()
                    //             ->native('false')
                    //             ->searchable(),
                    //         Textarea::make('keterangan')
                    //             ->label('Keterangan')
                    //             ->required(),

                    //     ])
                    //     ->action(function (array $data, LaporanKp $record): void {
                    //         $record->status_laporan = $data['status_laporan'];
                    //         $record->keterangan = $data['keterangan'];
                    //         $record->save();
                    //     })
                    //     ->successNotificationTitle('Status updated'),

                    Action::make('revisi')
                        ->icon('heroicon-o-pencil-square')
                        ->label('Revisi Proposal')
                        ->visible(fn(LaporanKp $record): bool => $record->status_laporan === 'revisi')
                        ->form([
                            FileUpload::make('file')
                                ->label('File Proposal Revisi')
                                ->required()
                                ->acceptedFileTypes(['application/pdf'])
                                ->openable()
                                ->downloadable(),

                        ])
                        ->action(function (array $data, LaporanKp $record): void {
                            $record->status_laporan = 'pending';
                            $record->file = $data['file'];
                            $record->save();
                        })
                        ->successNotificationTitle('File Revisi uploaded'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('pengajuanKp', function (Builder $query) {
            $query->join('laporan_kp', 'laporan_kp.id_pengajuan_kp', '=', 'pengajuan_kp.id')
                ->join('mahasiswas as mahasiswa', 'mahasiswa.id', '=', 'pengajuan_kp.id_mahasiswa')
                ->where('pengajuan_kp.id_mahasiswa', Auth::user()->id);
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanKps::route('/'),
            'create' => Pages\CreateLaporanKp::route('/create'),
            'edit' => Pages\EditLaporanKp::route('/{record}/edit'),
            'view' => Pages\ViewLaporanKp::route('/{record}'),
        ];
    }
}
