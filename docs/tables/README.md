---
title: Tables
description: Advanced data tables with sorting, filtering, and actions.
---

# Tables

Build feature-rich data tables with minimal code.

## Features

- Sortable columns
- Searchable data
- Filters and filter groups
- Bulk actions
- Row actions
- Pagination
- Grid/List views

## Basic Example

```php
use Laravilt\Tables\Columns\TextColumn;
use Laravilt\Tables\Columns\BadgeColumn;
use Laravilt\Tables\Columns\ImageColumn;

public static function table(): array
{
    return [
        ImageColumn::make('avatar')
            ->circular(),

        TextColumn::make('name')
            ->searchable()
            ->sortable(),

        TextColumn::make('email')
            ->searchable(),

        BadgeColumn::make('status')
            ->colors([
                'success' => 'active',
                'danger' => 'inactive',
            ]),

        TextColumn::make('created_at')
            ->dateTime()
            ->sortable(),
    ];
}
```

## Adding Filters

```php
public static function filters(): array
{
    return [
        SelectFilter::make('status')
            ->options([
                'active' => 'Active',
                'inactive' => 'Inactive',
            ]),

        DateFilter::make('created_at'),
    ];
}
```

## Learn More

- [Getting Started with Tables](/docs/tables/getting-started)
- [Column Types](/docs/tables/columns)
- [Filters](/docs/tables/filters)
- [Actions](/docs/tables/actions)
