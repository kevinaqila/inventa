<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Loans\LoanResource;
use App\Models\Loan;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestLoans extends TableWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Loan::query()
                    ->with(['items'])
                    ->latest('requested_at')
            )
            ->heading('Peminjaman Terbaru')
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->columns([
                TextColumn::make('loan_code')
                    ->label('Kode'),
                TextColumn::make('items.name')
                    ->label('Barang')
                    ->badge()
                    ->separator(','),
                TextColumn::make('borrower_name')
                    ->label('Peminjam')
                    ->description(fn (Loan $record): ?string => $record->borrower_id_number)
                    ->limit(20)
                    ->tooltip(function (TextColumn $column, $state): ?string {
                        return (strlen($state ?? '') > ($column->getCharacterLimit() ?? 20)) ? $state : null;
                    }),
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
                    }),
                TextColumn::make('requested_at')
                    ->label('Waktu Pinjam')
                    ->since(),
            ])
            ->recordUrl(fn (Loan $record) => LoanResource::getUrl('view', ['record' => $record]))
            ->paginated(false);
    }
}
