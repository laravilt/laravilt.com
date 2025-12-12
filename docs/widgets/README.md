---
title: Widgets
description: Dashboard widgets and charts.
---

# Widgets

Build informative dashboards with widgets.

## Available Widgets

- **Stats Widget**: Display key metrics
- **Chart Widget**: Line, bar, pie, and area charts
- **Table Widget**: Compact data tables
- **Custom Widget**: Build your own

## Stats Widget

```php
use Laravilt\Widgets\StatsWidget;

class StatsOverview extends StatsWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->icon('users')
                ->color('primary'),

            Stat::make('Revenue', '$' . number_format(Order::sum('total')))
                ->icon('currency-dollar')
                ->color('success'),
        ];
    }
}
```

## Chart Widget

```php
use Laravilt\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected function getData(): array
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => [1000, 2000, 1500, 3000],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
```

## Learn More

- [Getting Started](/docs/widgets/getting-started)
- [Stats Widgets](/docs/widgets/stats)
- [Chart Widgets](/docs/widgets/charts)
