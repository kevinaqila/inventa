<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Widgets\ChartWidget;

class LoansChart extends ChartWidget
{
    protected static ?int $sort = 1;

    protected ?string $maxHeight = '280px';

    protected string $color = 'primary';

    protected ?string $heading = 'Peminjaman 7 Hari Terakhir';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i));

        $labels = $days->map(fn ($date) => $date->translatedFormat('d M'))->toArray();

        $loansPerDay = $days->map(fn ($date) => Loan::whereDate('requested_at', $date->toDateString())->count())->toArray();

        $returnsPerDay = $days->map(fn ($date) => Loan::whereDate('returned_at', $date->toDateString())->count())->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Peminjaman',
                    'data' => $loansPerDay,
                    'backgroundColor' => 'rgba(20, 184, 166, 0.6)',
                    'borderColor' => 'rgb(20, 184, 166)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Pengembalian',
                    'data' => $returnsPerDay,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.6)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
