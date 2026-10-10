<?php

namespace App\Filament\Resources\Loans\Schemas;

use App\Models\Loan;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LoanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('loan_code')
                    ->label('Kode Transaksi'),
                TextEntry::make('items.name')
                    ->label('Barang yang Dipinjam')
                    ->badge(),
                TextEntry::make('borrower_name')
                    ->label('Nama Lengkap'),
                TextEntry::make('borrower_id_number')
                    ->label('NIM / Identitas'),
                TextEntry::make('study_program')
                    ->label('Program Studi')
                    ->default('-'),
                TextEntry::make('phone_number')
                    ->label('Nomor WhatsApp')
                    ->default('-'),
                TextEntry::make('destination_building')
                    ->label('Gedung')
                    ->default('-'),
                TextEntry::make('destination_room')
                    ->label('Ruangan')
                    ->default('-'),
                TextEntry::make('lecturer_name')
                    ->label('Dosen Pengajar')
                    ->default('-'),
                TextEntry::make('purpose')
                    ->label('Keperluan')
                    ->default('-')
                    ->columnSpanFull(),
                TextEntry::make('requested_at')
                    ->label('Waktu Pengajuan')
                    ->default('-')
                    ->formatStateUsing(fn ($state) => ($state && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') : '-'),
                TextEntry::make('expected_return_at')
                    ->label('Rencana Pengembalian')
                    ->default('-')
                    ->formatStateUsing(fn ($state) => ($state && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') : '-'),
                TextEntry::make('approved_at')
                    ->label('Waktu Disetujui')
                    ->default('-')
                    ->formatStateUsing(fn ($state) => ($state && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') : '-'),
                TextEntry::make('returned_at')
                    ->label('Waktu Dikembalikan')
                    ->default('-')
                    ->formatStateUsing(fn ($state) => ($state && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') : '-'),
                TextEntry::make('status')
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
                    }),
                TextEntry::make('return_condition')
                    ->label('Kondisi Pengembalian')
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
                TextEntry::make('officer_notes')
                    ->label('Catatan Petugas')
                    ->default('-')
                    ->columnSpanFull(),
                TextEntry::make('officer.name')
                    ->label('Petugas yang Memproses')
                    ->default('-'),
            ]);
    }
}
