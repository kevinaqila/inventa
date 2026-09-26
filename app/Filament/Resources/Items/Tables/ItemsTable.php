<?php

namespace App\Filament\Resources\Items\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'Tersedia',
                        'borrowed' => 'Sedang Dipinjam',
                        'maintenance' => 'Perawatan',
                        'damaged' => 'Rusak',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'borrowed' => 'warning',
                        'maintenance' => 'info',
                        'damaged' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('condition')
                    ->label('Kondisi Fisik')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'good' => 'Baik',
                        'lightly_damaged' => 'Rusak Ringan',
                        'heavily_damaged' => 'Rusak Berat',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'good' => 'success',
                        'lightly_damaged' => 'warning',
                        'heavily_damaged' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Didaftarkan')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'available' => 'Tersedia',
                        'borrowed' => 'Sedang Dipinjam',
                        'maintenance' => 'Perawatan',
                        'damaged' => 'Rusak',
                    ]),
                SelectFilter::make('condition')
                    ->label('Kondisi')
                    ->options([
                        'good' => 'Baik',
                        'lightly_damaged' => 'Rusak Ringan',
                        'heavily_damaged' => 'Rusak Berat',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->hiddenLabel()->tooltip('Ubah Data Barang'),
                DeleteAction::make()->hiddenLabel()->tooltip('Hapus Barang'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
