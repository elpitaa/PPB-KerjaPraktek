<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\PenerimaanKpResource\Pages;
use App\Filament\Mahasiswa\Resources\PenerimaanKpResource\RelationManagers;
use App\Models\PenerimaanKp;
use App\Models\ProposalKp;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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

class PenerimaanKpResource extends Resource
{
    protected static ?string $model = PenerimaanKp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Penerimaan KP';

    // // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Kerja Praktek';
    protected static ?int $navigationSort = 4;
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
                Section::make('Data Penerimaan KP')
                    ->schema([
                        Forms\Components\Select::make('id_pengajuan_kp')
                            ->label('Pilih Pengajuan KP')
                            ->helperText('Hanya menampilkan pengajuan yang proposal-nya sudah diterima dan belum dibuat penerimaan')
                            ->options(function() use ($mahasiswa) {
                                if (!$mahasiswa) return [];
                                
                                // Ambil id pengajuan yang sudah ada di penerimaan_kp
                                $sudahAdaPenerimaan = PenerimaanKP::pluck('id_pengajuan_kp')->toArray();
                                
                                return ProposalKp::join('pengajuan_kp', 'pengajuan_kp.id', '=', 'proposal_kps.id_pengajuan_kp')
                                    ->where('pengajuan_kp.id_mahasiswa', $mahasiswa->id)
                                    ->where('pengajuan_kp.status_pengajuan', 'diterima')
                                    ->where('proposal_kps.status_proposal', 'diterima')
                                    ->whereNotIn('pengajuan_kp.id', $sudahAdaPenerimaan)
                                    ->pluck('pengajuan_kp.nama_perusahaan', 'pengajuan_kp.id')
                                    ->toArray();
                            })
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->selectablePlaceholder(false)
                            ->unique(ignoreRecord: true),
                        Select::make('status_penerimaan')
                            ->label('Hasil Penerimaan Peruusahaan')
                            ->options([
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                            ])
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->selectablePlaceholder(false),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan Peruusahaan')
                            ->required(),
                        Forms\Components\FileUpload::make('file')
                            ->label('Dokumen Bukti Penerimaan')
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->openable()
                            ->downloadable(),
                    ])
                    ->columns('2'), // Membuat card selebar form    
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
                IconColumn::make('status_penerimaan')
                    ->label('Status Proposal')
                    ->icon(fn(string $state): string => match ($state) {
                        'ditolak' => 'heroicon-m-x-mark',
                        'diterima' => 'heroicon-m-check',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'ditolak' => 'danger',
                        'diterima' => 'success',
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

    public static function getTabs(): array
    {
        return [
            'all' => Tables\Tabs\Tab::make('Semua'),
            'diterima' => Tables\Tabs\Tab::make('Diterima')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_penerimaan', 'diterima')),
            'ditolak' => Tables\Tabs\Tab::make('Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_penerimaan', 'ditolak')),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Status Penerimaan')
                    ->schema(
                        [
                            IconEntry::make('status_penerimaan')
                                ->label('Status Proposal')
                                ->icon(fn(string $state): string => match ($state) {
                                    'ditolak' => 'heroicon-m-x-mark',
                                    'diterima' => 'heroicon-m-check',
                                })
                                ->color(fn(string $state): string => match ($state) {
                                    'ditolak' => 'danger',
                                    'diterima' => 'success',
                                })
                                ->label(fn(string $state): string => match ($state) {
                                    'ditolak' => 'Ditolak',
                                    'diterima' => 'Diterima',
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
                Components\Section::make('Data Penerimaan Kp')
                    ->schema([
                        Components\TextEntry::make('keterangan')
                            ->label('Keterangan'),
                        // Components\TextEntry::make('file')
                        //     ->label('File Proposal')
                        //     ->url(fn(string $value): string => asset('storage/' . $value))
                        //     ->openUrlInNewTab(),
                        Components\TextEntry::make('file')
                            ->label('Dokumen Bukti Penerimaan')
                            ->url(fn($record): string => asset('storage/' . $record->file))
                            ->openUrlInNewTab(),

                    ])
                    ->columns('2')
                    ->collapsible(),
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
            'index' => Pages\ListPenerimaanKps::route('/'),
            'create' => Pages\CreatePenerimaanKp::route('/create'),
            'edit' => Pages\EditPenerimaanKp::route('/{record}/edit'),
            'view' => Pages\ViewPenerimaanKp::route('/{record}'),
        ];
    }
}
