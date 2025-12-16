<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Ganti label di navigasi
    protected static ?string $navigationLabel = 'Admin';

    // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Manajemen User';

    protected static ?int $navigationSort = 10;

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
                    ->unique(User::class, 'email', ignoreRecord: true)
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
