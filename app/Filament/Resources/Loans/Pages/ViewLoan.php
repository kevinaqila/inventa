<?php

namespace App\Filament\Resources\Loans\Pages;

use App\Filament\Resources\Loans\LoanResource;
use App\Models\Loan;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewLoan extends ViewRecord
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Setuju')
                ->color('success')
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
                ->label('Tolak')
                ->color('danger')
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

            EditAction::make()
                ->label('Ubah')
                ->visible(fn ($record) => $record->status === 'pending'),

            Action::make('return')
                ->label('Barang Kembali')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('info')
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

            Action::make('whatsapp')
                ->label('Hubungi via WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->color('success')
                ->url(fn (Loan $record): ?string => $record->whatsapp_url)
                ->openUrlInNewTab()
                ->visible(fn (Loan $record) => $record->isOverdue()),
        ];
    }
}
