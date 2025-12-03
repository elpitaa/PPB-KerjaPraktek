<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\MahasiswaResource\Pages;
use App\Filament\Dosen\Resources\MahasiswaResource\RelationManagers;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
use Filament\Tables\Columns\IconColumn;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Data Mahasiswa';

    // // Ganti nama grup di navigasi (jika perlu)
    protected static ?string $navigationGroup = 'Kerja Praktek';
    protected static ?int $navigationSort = 1;
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
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama Mahasiswa'),
                TextColumn::make('nim')
                    ->searchable()
                    ->label('NIM'),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),
                TextColumn::make('dosen.name')
                    ->label('Dosen Pembimbing')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Data Mahasiswa')
                    ->schema([
                        Components\TextEntry::make('name')
                            ->label('Nama Mahasiswa'),
                        Components\TextEntry::make('nim')
                            ->label('NIM Mahasiswa'),
                        Components\TextEntry::make('email')
                            ->label('Email Mahasiswa'),
                        Components\TextEntry::make('jumlah_sks')
                            ->label('Sks Mahasiswa'),
                        Components\TextEntry::make('ipk')
                            ->label('IPK Mahasiswa'),
                        Components\TextEntry::make('dosen.name')
                            ->label('Dosen Pembimbing'),
                    ])
                    ->columns('2')
                    ->collapsible(),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Ambil data dosen berdasarkan email user yang login
        $dosen = Dosen::where('email', Auth::user()->email)->first();
        
        if (!$dosen) {
            // Jika dosen tidak ditemukan, return query kosong
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }
        
        // Filter mahasiswa berdasarkan dosen pembimbing
        return parent::getEloquentQuery()->where('dosens', $dosen->id);
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
            'index' => Pages\ListMahasiswas::route('/'),
            // Dosen tidak bisa create/edit mahasiswa, hanya view
            'view' => Pages\ViewMahasiswa::route('/{record}'),
        ];
    }
}
