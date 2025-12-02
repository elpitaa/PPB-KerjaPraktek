<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataDosenResource\Pages;
use App\Filament\Resources\DataDosenResource\RelationManagers;
use App\Models\Dosen as DataDosen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataDosenResource extends Resource
{
    protected static ?string $model = DataDosen::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'dosen';

    // Ganti label di navigasi
    protected static ?string $navigationLabel = 'Dosen';

    // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Manajemen User';
    protected static ?int $navigationSort = 11;

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
                TextInput::make('email')
                    ->placeholder('Masukkan email')
                    ->filled()
                    ->unique(DataDosen::class, 'email')
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
                TextColumn::make('email')
                    ->label('Email')
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
            'index' => Pages\ListDataDosens::route('/'),
            'create' => Pages\CreateDataDosen::route('/create'),
            'edit' => Pages\EditDataDosen::route('/{record}/edit'),
        ];
    }
}
