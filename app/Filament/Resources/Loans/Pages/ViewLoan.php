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

            Action::make('view_return_photo')
                ->label('Lihat Foto Bukti')
                ->icon('heroicon-o-camera')
                ->color('warning')
                ->visible(fn ($record) => !empty($record->return_photo_path))
                ->url(fn ($record) => asset($record->return_photo_path))
                ->openUrlInNewTab(),

            Action::make('return')
                ->label(fn ($record) => $record->status === 'return_pending' ? 'Konfirmasi Pengembalian' : 'Barang Kembali')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('info')
                ->visible(fn ($record) => in_array($record->status, ['borrowed', 'return_pending']))
                ->modalHeading(fn ($record) => $record->status === 'return_pending' ? 'Konfirmasi Verifikasi Pengembalian' : 'Proses Pengembalian Barang')
                ->modalDescription(fn ($record) => $record->status === 'return_pending' ? 'Mahasiswa telah mengunggah bukti foto fisik barang. Pastikan kondisi fisik barang inventaris telah diperiksa di loket TU sebelum menyelesaikan transaksi.' : null)
                ->modalSubmitActionLabel('Simpan & Selesaikan')
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
                    $officerNotes = $data['officer_notes'] ?? null;
                    if ($record->officer_notes && $officerNotes) {
                        $finalNotes = $record->officer_notes . "\n\n[Catatan Petugas: " . $officerNotes . "]";
                    } elseif ($officerNotes) {
                        $finalNotes = '[Catatan Petugas: ' . $officerNotes . ']';
                    } else {
                        $finalNotes = $record->officer_notes;
                    }

                    $record->update([
                        'status' => 'returned',
                        'returned_at' => now(),
                        'return_condition' => $data['return_condition'],
                        'officer_notes' => $finalNotes,
                        'officer_id' => auth()->id(),
                    ]);

                    $itemStatus = $data['return_condition'] === 'damaged' ? 'damaged' : 'available';
                    $itemCondition = $data['return_condition'] === 'damaged' ? 'lightly_damaged' : 'good';

                    $record->items()->update([
                        'status' => $itemStatus,
                        'condition' => $itemCondition,
                    ]);

                    Notification::make()
                        ->title('Pengembalian barang berhasil dikonfirmasi dan diselesaikan')
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
