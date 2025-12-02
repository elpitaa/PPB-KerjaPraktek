<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\PengajuanKpResource\Pages;
use App\Filament\Mahasiswa\Resources\PengajuanKpResource\RelationManagers;
use App\Filament\Mahasiswa\Resources\ProposalKpResource\Pages\CreateProposalKp;
use App\Models\Pengajuan_kp as PengajuanKp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Navigation\NavigationItem;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Component;
use Filament\Infolists\Components\IconEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Teguh02\IndonesiaTerritoryForms\IndonesiaTerritoryForms;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ViewAction;
// use App\Filament\Resources\CustomerResource\Pages;
use Filament\Tables\Columns\IconColumn;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Facades\Auth;
use App\Filament\Mahasiswa\Resources\ProposalKpResource\Pages\ViewProposalKps;
use App\Models\Mahasiswa;
use App\Models\ProposalKp;
use Faker\Provider\ar_EG\Text;
use Teguh02\IndonesiaTerritoryForms\Traits\HasProvinceForm;
use Filament\Forms\Components\Section;
use Filament\Exceptions\Halt;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Schema\Blueprint;
use Teguh02\IndonesiaTerritoryForms\Traits\HasCityForm;
use Teguh02\IndonesiaTerritoryForms\Traits\HasDistrictForm;
use Teguh02\IndonesiaTerritoryForms\Traits\HasPostalCode;
use Teguh02\IndonesiaTerritoryForms\Traits\HasSubDistrictForm;

use function Laravel\Prompts\text;

class PengajuanKpResource extends Resource
{
    use HasProvinceForm,
        HasCityForm,
        HasDistrictForm,
        HasSubDistrictForm,
        HasPostalCode;

    protected static ?string $model = PengajuanKp::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    // protected static ?string $slug = 'mahasiswa';

    // Ganti label di navigasi
    protected static ?string $navigationLabel = 'Pengajuan KP';

    // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Kerja Praktek';
    protected static ?int $navigationSort = 0;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    public static function form(Form $form): Form
    {
        // dd(Auth::user());
        // Ambil data mahasiswa berdasarkan email user yang login
        $mahasiswa = Mahasiswa::where('email', auth::user()->email)->first();
        
        return $form
            ->schema([
                // Select::make('id_mahasiswa')
                //     ->label('Nama Mahasiswa') // Label untuk debugging, bisa dihilangkan nanti
                //     ->options([
                //         auth::user()->id =>  auth::user()->name,
                //     ])
                //     ->default(fn() => auth::user()->id) // Ambil ID user yang login
                //     // ->disabledOn('create')
                //     ->native(false)
                //     ->preload()
                //     ->selectablePlaceholder(false)
                //     ->live(),
                Section::make('Data Mahasiswa')
                    ->schema([
                        TextInput::make('Nama Mahasiswa')
                            ->label('Nama Mahasiswa')
                            ->default($mahasiswa ? $mahasiswa->name : auth::user()->name)
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
                    ->columns('2'), // Membuat card selebar form

                Hidden::make('status_pengajuan')
                    ->label('Status Pengajuan')
                    ->default('pending'),
                Hidden::make('id_mahasiswa')
                    ->label('Status Pengajuan')
                    ->default($mahasiswa ? $mahasiswa->id : null),

                Section::make('Data Perusahaan')
                    ->schema([
                        TextInput::make('nama_perusahaan')
                            ->label('Nama Perusahaan')
                            ->required()
                            ->filled(),

                        static::province_form(),
                        config('indonesia-territory-forms.forms_visibility.city') ? static::city_form() : null,
                        config('indonesia-territory-forms.forms_visibility.district') ? static::district_form() : null,
                        config('indonesia-territory-forms.forms_visibility.sub_district') ? static::sub_district_form() : null,
                        config('indonesia-territory-forms.forms_visibility.postal_code') ? static::postal_code_form() : null,
                    ])
                    ->columns('2'),
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
        ;
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
                // Actions::make([
                //     Action::make('status_pengajuan_edit')
                //         ->icon('heroicon-o-pencil-square')
                //         ->label('Edit Status Pengajuan')
                //         ->form([
                //             Select::make('status_pengajuan')
                //                 ->label('Status Pengajuan')
                //                 ->options([
                //                     'diterima' => 'Terima',
                //                     'ditolak' => 'Tolak',
                //                 ])
                //                 ->required()
                //                 ->native('false')
                //                 ->searchable(),
                //         ])
                //         ->action(function (array $data, PengajuanKp $record): void {
                //             $record->status_pengajuan = $data['status_pengajuan'];
                //             $record->save();
                //         })
                //         ->successNotificationTitle('Status updated'),
                // ]),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('id_mahasiswa', auth::id());
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanKp::route('/'),
            'create' => Pages\CreatePengajuanKp::route('/create'),
            'edit' => Pages\EditPengajuanKp::route('/{record}/edit'),
            'view' => Pages\ViewPengajuanKp::route('/{record}'),
        ];
    }
}
