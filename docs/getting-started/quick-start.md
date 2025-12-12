---
title: Quick Start
description: Get up and running with Laravilt in minutes.
---

# Quick Start

Create your first admin panel in minutes.

## Create a Resource

```bash
php artisan make:laravilt-resource ProductResource
```

This generates a resource file in `app/Laravilt/Resources/`.

## Define Your Form

```php
public static function form(): array
{
    return [
        TextInput::make('name')
            ->required()
            ->maxLength(255),

        Textarea::make('description')
            ->columnSpanFull(),

        TextInput::make('price')
            ->numeric()
            ->prefix('$'),
    ];
}
```

## Define Your Table

```php
public static function table(): array
{
    return [
        TextColumn::make('name')
            ->searchable()
            ->sortable(),

        TextColumn::make('price')
            ->money('USD')
            ->sortable(),

        TextColumn::make('created_at')
            ->dateTime()
            ->sortable(),
    ];
}
```

## Access Your Panel

Visit `/admin` to see your new admin panel!

## Next Steps

- Learn more about [Forms](/docs/forms/README)
- Explore [Table options](/docs/tables/README)
- Add [Widgets to your dashboard](/docs/widgets/README)
