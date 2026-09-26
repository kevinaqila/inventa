<?php

namespace App\Filament\Resources\Loans\Pages;

use App\Filament\Resources\Loans\LoanResource;
use App\Models\Loan;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLoans extends ListRecords
{
    protected static string $resource = LoanResource::class;

    public function mount(): void
    {
        parent::mount();

        Loan::where('status', 'pending')
            ->where('requested_at', '<', now()->subMinutes(30))
            ->update(['status' => 'expired']);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'pending' => Tab::make('Menunggu')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(fn () => Loan::where('status', 'pending')->count())
                ->badgeColor('warning'),
            'borrowed' => Tab::make('Sedang Dipinjam')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'borrowed'))
                ->badge(fn () => Loan::where('status', 'borrowed')->count())
                ->badgeColor('info'),
            'overdue' => Tab::make('Terlambat')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'borrowed')->where('expected_return_at', '<', now()))
                ->badge(fn () => Loan::where('status', 'borrowed')->where('expected_return_at', '<', now())->count())
                ->badgeColor('danger'),
            'returned' => Tab::make('Dikembalikan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'returned')),
            'archived' => Tab::make('Arsip / Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['rejected', 'expired'])),
        ];
    }
}
