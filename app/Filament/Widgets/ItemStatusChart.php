<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Widgets\ChartWidget;

class ItemStatusChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $maxHeight = '280px';

    protected ?string $heading = 'Status & Kondisi Barang';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $available = Item::where('status', 'available')->count();
        $borrowed = Item::where('status', 'borrowed')->count();
        $maintenance = Item::where('status', 'maintenance')->count();
        $damaged = Item::where('status', 'damaged')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Barang',
                    'data' => [$available, $borrowed, $maintenance, $damaged],
                    'backgroundColor' => [
                        'rgba(20, 184, 166, 0.85)',
                        'rgba(59, 130, 246, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                    ],
                    'borderColor' => [
                        '#14b8a6',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Tersedia', 'Sedang Dipinjam', 'Perawatan', 'Rusak'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
