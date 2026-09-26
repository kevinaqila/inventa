<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'borrowed' => 'Sedang Dipinjam',
                        'maintenance' => 'Perawatan',
                        'damaged' => 'Rusak',
                    ])
                    ->default('available')
                    ->required(),
                Select::make('condition')
                    ->label('Kondisi Fisik')
                    ->options([
                        'good' => 'Baik',
                        'lightly_damaged' => 'Rusak Ringan',
                        'heavily_damaged' => 'Rusak Berat',
                    ])
                    ->default('good')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi / Catatan')
                    ->columnSpanFull(),
            ]);
    }
}
