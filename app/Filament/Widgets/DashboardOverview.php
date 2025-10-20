<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\Product;
use App\Models\Inventory;

class DashboardOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', Order::count())
                ->description('Number of all orders')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('success'),

            Stat::make('Products in Catalog', Product::count())
                ->description('All available products')
                ->descriptionIcon('heroicon-o-archive-box')
                ->color('info'),

            Stat::make('Total Stock', Inventory::sum('quantity'))
                ->description('Sum of all items in inventory')
                ->descriptionIcon('heroicon-o-cube')
                ->color('warning'),
        ];
    }
}
