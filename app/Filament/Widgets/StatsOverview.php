<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use App\Models\Loan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $totalItems = Item::count();
        $availableItems = Item::where('status', 'available')->count();
        $borrowedItems = Item::where('status', 'borrowed')->count();
        $damagedItems = Item::whereIn('status', ['damaged', 'maintenance'])->count();

        $pendingLoans = Loan::where('status', 'pending')->count();
        $activeLoans = Loan::where('status', 'borrowed')->count();
        $totalReturned = Loan::where('status', 'returned')->count();

        $days = collect(range(6, 0));

        $activeTrend = $days->map(fn ($i) => 
            Loan::where('status', 'borrowed')
                ->whereDate('approved_at', '<=', now()->subDays($i)->toDateString())
                ->count()
        )->toArray();

        $pendingTrend = $days->map(fn ($i) => 
            Loan::where('status', 'pending')
                ->whereDate('requested_at', now()->subDays($i)->toDateString())
                ->count()
        )->toArray();

        $returnedTrend = $days->map(fn ($i) => 
            Loan::where('status', 'returned')
                ->whereDate('returned_at', now()->subDays($i)->toDateString())
                ->count()
        )->toArray();

        $itemTrend = $days->map(fn ($i) =>
            Item::whereDate('created_at', '<=', now()->subDays($i)->toDateString())->count()
        )->toArray();

        return [
            Stat::make('Total Inventaris', $totalItems)
                ->description($availableItems . ' tersedia')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart($itemTrend)
                ->color('primary'),
            Stat::make('Sedang Dipinjam', $activeLoans)
                ->description($borrowedItems . ' barang sedang di luar')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($activeTrend)
                ->color('warning'),
            Stat::make('Menunggu', $pendingLoans)
                ->description('perlu ditinjau')
                ->descriptionIcon('heroicon-m-clock')
                ->chart($pendingTrend)
                ->color($pendingLoans > 0 ? 'danger' : 'success'),
            Stat::make('Total Dikembalikan', $totalReturned)
                ->description('peminjaman selesai')
                ->descriptionIcon('heroicon-m-arrow-uturn-left')
                ->chart($returnedTrend)
                ->color('success'),
        ];
    }
}
