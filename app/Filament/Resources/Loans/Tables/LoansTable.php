<?php

namespace App\Filament\Resources\Loans\Tables;

use App\Models\Loan;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('loan_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('items.name')
                    ->label('Barang')
                    ->badge()
                    ->separator(',')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('borrower_name')
                    ->label('Peminjam')
                    ->searchable(['borrower_name', 'borrower_id_number'])
                    ->description(fn (Loan $record): ?string => $record->borrower_id_number)
                    ->limit(15)
                    ->tooltip(function (TextColumn $column, $state): ?string {
                        return (strlen($state ?? '') > ($column->getCharacterLimit() ?? 15)) ? $state : null;
                    }),
                TextColumn::make('destination_building')
                    ->label('Gedung')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('destination_room')
                    ->label('Ruangan')
                    ->default('-')
                    ->limit(12)
                    ->tooltip(function (TextColumn $column, $state): ?string {
                        return (strlen($state ?? '') > ($column->getCharacterLimit() ?? 12)) ? $state : null;
                    }),
                TextColumn::make('lecturer_name')
                    ->label('Dosen Pengajar')
                    ->default('-')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('requested_at')
                    ->label('Waktu Peminjaman')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('expected_return_at')
                    ->label('Batas Pengembalian')
                    ->default('-')
                    ->formatStateUsing(fn ($state) => ($state && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') : '-')
                    ->color(fn (Loan $record) => $record->isOverdue() ? 'danger' : null)
                    ->weight(fn (Loan $record) => $record->isOverdue() ? 'bold' : null)
                    ->sortable(),
                TextColumn::make('returned_at')
                    ->label('Waktu Dikembalikan')
                    ->default('-')
                    ->formatStateUsing(fn ($state) => ($state && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function (string $state, Loan $record): string {
                        if ($record->isOverdue()) {
                            return 'Terlambat';
                        }

                        return match ($state) {
                            'pending' => 'Menunggu',
                            'borrowed' => 'Sedang Dipinjam',
                            'returned' => 'Dikembalikan',
                            'rejected' => 'Ditolak',
                            'expired' => 'Kedaluwarsa',
                            default => $state,
                        };
                    })
                    ->color(fn (string $state, Loan $record): string => match (true) {
                        $record->isOverdue() => 'danger',
                        $state === 'pending' => 'warning',
                        $state === 'borrowed' => 'info',
                        $state === 'returned' => 'success',
                        $state === 'rejected' => 'danger',
                        $state === 'expired' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('return_condition')
                    ->label('Kondisi')
                    ->default('-')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'good' => 'Baik',
                        'damaged' => 'Rusak',
                        default => '-',
                    })
                    ->badge(fn (?string $state): bool => in_array($state, ['good', 'damaged']))
                    ->color(fn (?string $state): ?string => match ($state) {
                        'good' => 'success',
                        'damaged' => 'danger',
                        default => null,
                    }),
            ])
            ->defaultSort('requested_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Peminjaman')
                    ->options([
                        'pending' => 'Menunggu',
                        'borrowed' => 'Sedang Dipinjam',
                        'returned' => 'Dikembalikan',
                        'rejected' => 'Ditolak',
                        'expired' => 'Kedaluwarsa',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->tooltip('Setujui Peminjaman')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Peminjaman Barang')
                    ->modalDescription('Pastikan barang fisik telah diserahkan langsung kepada mahasiswa / peminjam.')
                    ->modalSubmitActionLabel('Ya, Setujui')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'borrowed',
                            'approved_at' => now(),
                            'officer_id' => auth()->id(),
                        ]);
                        $record->items()->update(['status' => 'borrowed']);

                        Notification::make()
                            ->title('Peminjaman berhasil disetujui')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->tooltip('Tolak Pengajuan')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pengajuan Peminjaman')
                    ->modalDescription('Apakah Anda yakin ingin menolak pengajuan peminjaman ini?')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'rejected',
                            'officer_id' => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Pengajuan peminjaman ditolak')
                            ->danger()
                            ->send();
                    }),
                Action::make('return')
                    ->label('')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('info')
                    ->tooltip('Proses Pengembalian Barang')
                    ->visible(fn ($record) => $record->status === 'borrowed')
                    ->modalHeading('Proses Pengembalian Barang')
                    ->modalSubmitActionLabel('Simpan Pengembalian')
                    ->schema([
                        Select::make('return_condition')
                            ->label('Kondisi Saat Dikembalikan')
                            ->options([
                                'good' => 'Baik / Normal',
                                'damaged' => 'Rusak / Perlu Perbaikan',
                            ])
                            ->default('good')
                            ->required(),
                        Textarea::make('officer_notes')
                            ->label('Catatan Petugas (Opsional)'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'returned',
                            'returned_at' => now(),
                            'return_condition' => $data['return_condition'],
                            'officer_notes' => $data['officer_notes'] ?? null,
                        ]);

                        $itemStatus = $data['return_condition'] === 'damaged' ? 'damaged' : 'available';
                        $itemCondition = $data['return_condition'] === 'damaged' ? 'lightly_damaged' : 'good';

                        $record->items()->update([
                            'status' => $itemStatus,
                            'condition' => $itemCondition,
                        ]);

                        Notification::make()
                            ->title('Pengembalian barang berhasil dicatat')
                            ->success()
                            ->send();
                    }),
                ViewAction::make()
                    ->hiddenLabel()
                    ->tooltip('Lihat Detail Peminjaman'),
                EditAction::make()
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->hiddenLabel()
                    ->tooltip('Ubah Data Peminjaman'),
                Action::make('whatsapp')
                    ->label('')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->tooltip('Hubungi Mahasiswa via WhatsApp')
                    ->url(fn (Loan $record): ?string => $record->whatsapp_url)
                    ->openUrlInNewTab()
                    ->visible(fn (Loan $record) => $record->isOverdue()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
