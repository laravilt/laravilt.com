<?php

namespace App\Laravilt\Admin\Pages;

use App\Laravilt\Admin\Clusters\Reports;
use Laravilt\Panel\Pages\Page;
use Laravilt\Widgets\Stat;

class SalesAnalytics extends Page
{
    protected static ?string $navigationIcon = 'TrendingUp';

    protected static ?string $navigationLabel = 'Sales Analytics';

    protected static ?string $title = 'Sales Analytics';

    protected static ?string $slug = 'sales-analytics';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Reports::class;

    protected static string $view = 'laravilt/Dashboard';

    public function getWidgets(): array
    {
        return [
            Stat::make('Total Revenue', function () {
                return '$' . number_format(\App\Models\Order::where('user_id', auth()->id())
                    ->where('payment_status', 'paid')
                    ->sum('total'), 2);
            })
                ->description('All time revenue')
                ->descriptionIcon('DollarSign')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Orders This Month', function () {
                return \App\Models\Order::where('user_id', auth()->id())
                    ->whereMonth('created_at', now()->month)
                    ->count();
            })
                ->description('Monthly orders')
                ->descriptionIcon('ShoppingCart')
                ->color('info')
                ->chart([3, 5, 2, 8, 4, 9, 6]),

            Stat::make('Average Order Value', function () {
                $avg = \App\Models\Order::where('user_id', auth()->id())
                    ->where('payment_status', 'paid')
                    ->avg('total');
                return '$' . number_format($avg ?? 0, 2);
            })
                ->description('Per order')
                ->descriptionIcon('Calculator')
                ->color('warning'),

            Stat::make('Pending Orders', function () {
                return \App\Models\Order::where('user_id', auth()->id())
                    ->where('status', 'pending')
                    ->count();
            })
                ->description('Awaiting processing')
                ->descriptionIcon('Clock')
                ->color('danger'),
        ];
    }

    public static function getCluster(): ?string
    {
        return static::$cluster;
    }
}
