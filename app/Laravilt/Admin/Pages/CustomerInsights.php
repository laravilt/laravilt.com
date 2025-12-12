<?php

namespace App\Laravilt\Admin\Pages;

use App\Laravilt\Admin\Clusters\Reports;
use Laravilt\Panel\Pages\Page;
use Laravilt\Widgets\Stat;

class CustomerInsights extends Page
{
    protected static ?string $navigationIcon = 'UserCheck';

    protected static ?string $navigationLabel = 'Customer Insights';

    protected static ?string $title = 'Customer Insights';

    protected static ?string $slug = 'customer-insights';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Reports::class;

    protected static string $view = 'laravilt/Dashboard';

    public function getWidgets(): array
    {
        return [
            Stat::make('Total Customers', function () {
                return \App\Models\Customer::where('user_id', auth()->id())->count();
            })
                ->description('Registered customers')
                ->descriptionIcon('Users')
                ->color('primary')
                ->chart([5, 8, 12, 15, 18, 22, 25]),

            Stat::make('Active Customers', function () {
                return \App\Models\Customer::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->count();
            })
                ->description('Currently active')
                ->descriptionIcon('UserCheck')
                ->color('success'),

            Stat::make('New This Month', function () {
                return \App\Models\Customer::where('user_id', auth()->id())
                    ->whereMonth('created_at', now()->month)
                    ->count();
            })
                ->description('New registrations')
                ->descriptionIcon('UserPlus')
                ->color('info')
                ->chart([2, 4, 3, 6, 5, 8, 10]),

            Stat::make('Top Spender', function () {
                $top = \App\Models\Customer::where('user_id', auth()->id())
                    ->orderByDesc('total_spent')
                    ->first();
                return $top ? '$' . number_format($top->total_spent, 2) : '$0.00';
            })
                ->description('Highest spending customer')
                ->descriptionIcon('Trophy')
                ->color('warning'),
        ];
    }

    public static function getCluster(): ?string
    {
        return static::$cluster;
    }
}
