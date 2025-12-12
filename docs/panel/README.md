---
title: Panel
description: Configure and customize your admin panel.
---

# Panel

The Panel package provides the foundation for your admin interface.

## Features

- Multi-panel support
- Custom navigation
- Theming and branding
- User menu customization
- Global search
- Dashboard widgets

## Configuration

Configure your panel in `config/laravilt/panel.php`:

```php
return [
    'path' => 'admin',

    'colors' => [
        'primary' => '#04bdaf',
        'secondary' => '#822478',
    ],

    'brand_name' => 'My Admin',
    'brand_logo' => null,

    'middleware' => [
        'web',
        'auth',
    ],
];
```

## Creating Resources

```bash
php artisan make:laravilt-resource UserResource
```

## Navigation

Customize your sidebar navigation:

```php
public static function navigation(): array
{
    return [
        NavigationItem::make('Dashboard')
            ->icon('home')
            ->url('/admin'),

        NavigationGroup::make('Content')
            ->items([
                NavigationItem::make('Posts'),
                NavigationItem::make('Categories'),
            ]),
    ];
}
```

## Learn More

- [Panel Configuration](/docs/panel/configuration)
- [Navigation](/docs/panel/navigation)
- [Resources](/docs/panel/resources)
- [Pages](/docs/panel/pages)
