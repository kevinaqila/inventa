<?php

namespace App\Filament\Resources\Loans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LoanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('loan_code')
                    ->label('Kode Transaksi')
                    ->default(fn () => 'LOAN-' . date('Ymd') . '-' . strtoupper(Str::random(4)))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('items')
                    ->label('Barang yang Dipinjam')
                    ->relationship('items', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('borrower_id_number')
                    ->label('NIM / Identitas')
                    ->required()
                    ->maxLength(255),
                TextInput::make('borrower_name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('study_program')
                    ->label('Program Studi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone_number')
                    ->label('Nomor WhatsApp')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                TextInput::make('destination_building')
                    ->label('Gedung')
                    ->required()
                    ->maxLength(255),
                TextInput::make('destination_room')
                    ->label('Ruangan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('lecturer_name')
                    ->label('Dosen Pengajar')
                    ->required()
                    ->maxLength(255),
                Textarea::make('purpose')
                    ->label('Keperluan Peminjaman')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('requested_at')
                    ->label('Waktu Pengajuan')
                    ->default(now())
                    ->required(),
                DateTimePicker::make('expected_return_at')
                    ->label('Rencana Pengembalian')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu ACC',
                        'borrowed' => 'Sedang Dipinjam',
                        'returned' => 'Dikembalikan',
                        'rejected' => 'Ditolak',
                        'expired' => 'Kedaluwarsa',
                    ])
                    ->default('pending')
                    ->required(),
                Select::make('return_condition')
                    ->label('Kondisi')
                    ->options([
                        'good' => 'Baik / Normal',
                        'damaged' => 'Rusak / Perlu Perbaikan',
                    ]),
                Textarea::make('officer_notes')
                    ->label('Catatan Petugas')
                    ->columnSpanFull(),
            ]);
    }
}
