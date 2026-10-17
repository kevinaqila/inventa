<?php

namespace App\Filament\Resources\Loans\Schemas;

use App\Models\Loan;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LoanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 2])
            ->components([
                Section::make('Informasi Transaksi & Inventaris')
                    ->icon('heroicon-o-cube')
                    ->extraAttributes([
                        'class' => 'h-full flex flex-col [&_.fi-section-content-ctn]:flex-1 [&_.fi-section-content]:h-full',
                        'style' => 'height: 100%;',
                    ])
                    ->schema([
                        TextEntry::make('loan_code')
                            ->label('Kode Transaksi')
                            ->copyable()
                            ->weight('bold')
                            ->fontFamily('mono'),

                        TextEntry::make('status')
                            ->label('Status Peminjaman')
                            ->badge()
                            ->formatStateUsing(function (string $state, Loan $record): string {
                                if ($record->isOverdue()) {
                                    return 'Terlambat';
                                }

                                return match ($state) {
                                    'pending' => 'Menunggu ACC TU',
                                    'borrowed' => 'Sedang Dipinjam',
                                    'return_pending' => 'Menunggu Verifikasi',
                                    'returned' => 'Selesai Dikembalikan',
                                    'rejected' => 'Ditolak',
                                    'expired' => 'Kedaluwarsa',
                                    default => $state,
                                };
                            })
                            ->color(fn (string $state, Loan $record): string => match (true) {
                                $record->isOverdue() => 'danger',
                                $state === 'pending' => 'warning',
                                $state === 'borrowed' => 'info',
                                $state === 'return_pending' => 'warning',
                                $state === 'returned' => 'success',
                                $state === 'rejected' => 'danger',
                                $state === 'expired' => 'gray',
                                default => 'gray',
                            }),

                        TextEntry::make('expected_return_at')
                            ->label('Batas Pengembalian')
                            ->placeholder('-')
                            ->formatStateUsing(fn ($state) => (!blank($state) && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') . ' WIB' : '-')
                            ->color(fn (Loan $record) => $record->isOverdue() ? 'danger' : null)
                            ->weight(fn (Loan $record) => $record->isOverdue() ? 'bold' : null),

                        TextEntry::make('items.name')
                            ->label('Barang yang Dipinjam')
                            ->badge()
                            ->color('primary')
                            ->columnSpan(2),

                        TextEntry::make('purpose')
                            ->label('Keperluan')
                            ->placeholder('-')
                            ->columnSpan(1),
                    ])
                    ->columns(3),

                Section::make('Data Mahasiswa & Lokasi Perkuliahan')
                    ->icon('heroicon-o-user')
                    ->extraAttributes([
                        'class' => 'h-full flex flex-col [&_.fi-section-content-ctn]:flex-1 [&_.fi-section-content]:h-full',
                        'style' => 'height: 100%;',
                    ])
                    ->schema([
                        TextEntry::make('borrower_name')
                            ->label('Nama Mahasiswa')
                            ->weight('bold'),

                        TextEntry::make('borrower_id_number')
                            ->label('NIM / Identitas')
                            ->copyable(),

                        TextEntry::make('study_program')
                            ->label('Program Studi')
                            ->placeholder('-'),

                        TextEntry::make('phone_number')
                            ->label('Nomor WhatsApp')
                            ->placeholder('-')
                            ->copyable()
                            ->url(fn (Loan $record) => $record->whatsapp_url, shouldOpenInNewTab: true),

                        TextEntry::make('location')
                            ->label('Lokasi Pemakaian')
                            ->state(fn (Loan $record) => "{$record->destination_building} • {$record->destination_room}"),

                        TextEntry::make('lecturer_name')
                            ->label('Dosen Pengajar')
                            ->placeholder('-'),
                    ])
                    ->columns(3),

                Section::make('Log Waktu & Verifikasi Pengembalian')
                    ->icon('heroicon-o-clock')
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('requested_at')
                            ->label('Waktu Pengajuan')
                            ->placeholder('-')
                            ->formatStateUsing(fn ($state) => (!blank($state) && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') . ' WIB' : '-'),

                        TextEntry::make('approved_at')
                            ->label('Waktu Disetujui')
                            ->placeholder('-')
                            ->formatStateUsing(fn ($state) => (!blank($state) && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') . ' WIB' : '-'),

                        TextEntry::make('return_requested_at')
                            ->label('Submit Bukti Pengembalian')
                            ->placeholder('-')
                            ->formatStateUsing(fn ($state) => (!blank($state) && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') . ' WIB' : '-'),

                        TextEntry::make('returned_at')
                            ->label('Selesai Dikembalikan')
                            ->placeholder('-')
                            ->formatStateUsing(fn ($state) => (!blank($state) && $state !== '-') ? \Carbon\Carbon::parse($state)->translatedFormat('d M Y, H:i') . ' WIB' : '-'),

                        TextEntry::make('return_condition')
                            ->label('Kondisi Barang')
                            ->placeholder('-')
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

                        TextEntry::make('officer.name')
                            ->label('Petugas yang Memproses')
                            ->placeholder('-'),

                        TextEntry::make('officer_notes')
                            ->label('Catatan Petugas')
                            ->placeholder('-')
                            ->columnSpan(2),

                        ImageEntry::make('return_photo_path')
                            ->label('Foto Bukti Pengembalian')
                            ->state(fn ($record) => $record->return_photo_path ? asset($record->return_photo_path) : null)
                            ->extraImgAttributes(['class' => 'rounded-xl border border-slate-700 max-h-64 object-contain shadow-xs'])
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->return_photo_path)),
                    ])
                    ->columns(4),
            ]);
    }
}
