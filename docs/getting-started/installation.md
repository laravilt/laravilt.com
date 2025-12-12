---
title: Installation
description: How to install Laravilt in your Laravel project.
---

# Installation

Get started with Laravilt in just a few steps.

## Requirements

- PHP 8.2 or higher
- Laravel 11.x
- Node.js 18.x or higher
- Composer 2.x

## Installation

### 1. Install via Composer

```bash
composer require laravilt/laravilt
```

### 2. Run the Install Command

```bash
php artisan laravilt:install
```

This command will:
- Publish the configuration files
- Set up the frontend assets
- Configure Inertia.js and Vue 3
- Install npm dependencies

### 3. Build Assets

```bash
npm install && npm run build
```

### 4. Run Migrations

```bash
php artisan migrate
```

## Configuration

After installation, you can configure Laravilt by editing the config files in `config/laravilt/`.

### Panel Configuration

```php
// config/laravilt/panel.php
return [
    'path' => 'admin',
    'colors' => [
        'primary' => '#04bdaf',
    ],
];
```

## Next Steps

- [Quick Start Guide](/docs/getting-started/quick-start)
- [Create Your First Resource](/docs/panel/resources)
- [Customize Forms](/docs/forms/getting-started)
- [Build Tables](/docs/tables/getting-started)

---

Need help? Check out our [GitHub repository](https://github.com/laravilt/laravilt) or join our community.
