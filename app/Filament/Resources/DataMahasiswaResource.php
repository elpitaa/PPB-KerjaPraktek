<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataMahasiswaResource\Pages;
use App\Filament\Resources\DataMahasiswaResource\RelationManagers;
use App\Models\Mahasiswa as DataMahasiswa;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class DataMahasiswaResource extends Resource
{
    protected static ?string $model = DataMahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $slug = 'mahasiswa';

    // Ganti label di navigasi
    protected static ?string $navigationLabel = 'Mahasiswa';

    // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Manajemen User';
    protected static ?int $navigationSort = 12;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->placeholder('Masukkan nama')
                    ->filled()
                    ->autofocus()
                    ->validationMessages([
                        'filled' => 'Nama wajib diisi.',
                    ]),
                TextInput::make('nim')
                    ->numeric()
                    ->placeholder('Masukkan NIM')
                    ->filled()
                    ->minLength(12)
                    ->maxLength(12)
                    ->unique(DataMahasiswa::class, 'nim')
                    ->validationMessages([
                        'filled' => 'NIM wajib diisi.',
                        'unique' => 'NIM sudah digunakan.',
                        'minLength' => 'NIM harus 12 karakter.',
                        'numeric' => 'NIM harus berupa angka.',
                        'maxLength' => 'NIM harus 12 karakter.',
                    ]),
                TextInput::make('jumlah_sks')
                    ->numeric()
                    ->placeholder('Masukkan jumlah SKS')
                    ->filled()
                    ->minLength(1)
                    ->validationMessages([
                        'filled' => 'Jumlah SKS wajib diisi.',
                        'minLength' => 'Jumlah SKS harus 1 karakter.',
                        'numeric' => 'Jumlah SKS harus berupa angka.',
                    ]),
                TextInput::make('ipk')
                    ->label('IPK')
                    ->numeric()
                    ->step(0.01)
                    ->placeholder('Masukkan IPK')
                    ->filled()
                    ->validationMessages([
                        'filled' => 'IPK wajib diisi.',
                        'numeric' => 'IPK harus berupa angka.',
                    ]),

                Select::make('dosens')
                    ->label('Dosen')
                    ->options(
                        \App\Models\Dosen::all()->pluck('name', 'id')
                    )
                    ->searchable()
                    ->placeholder('Dosen Wali')
                    ->rules(['required']),


                TextInput::make('email')
                    ->placeholder('Masukkan email')
                    ->filled()
                    ->unique(DataMahasiswa::class, 'email')
                    ->regex('/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/')
                    ->validationMessages([
                        'filled' => 'Email wajib diisi.',
                        'unique' => 'Email sudah digunakan.',
                        'regex' => 'Email tidak valid.',
                    ]),
                TextInput::make('password')
                    ->placeholder('Masukkan password')
                    ->filled()
                    ->password()
                    ->minLength(8)
                    ->validationMessages([
                        'filled' => 'Password wajib diisi.',
                        'minLength' => 'Password harus minimal 8 karakter.',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah_sks')
                    ->sortable(),
                TextColumn::make('ipk')
                    ->Label('IPK')
                    ->sortable(),
                TextColumn::make('dosen.name')
                    ->label('Dosen Wali')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDataMahasiswas::route('/'),
            'create' => Pages\CreateDataMahasiswa::route('/create'),
            'edit' => Pages\EditDataMahasiswa::route('/{record}/edit'),
        ];
    }
}
