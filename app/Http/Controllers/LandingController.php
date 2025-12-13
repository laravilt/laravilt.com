<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\WithSeo;
use App\Support\SeoData;
use Inertia\Inertia;

class LandingController extends Controller
{
    use WithSeo;

    public function index()
    {
        $this->seo(
            SeoData::make('Home')
                ->description('Laravilt is a modern admin panel framework for Laravel and Vue.js. Build beautiful, type-safe admin panels with forms, tables, widgets, and AI integration.')
                ->keywords('laravel admin panel, vue admin, crud generator, laravel forms, laravel tables, typescript admin, inertia admin panel, filament alternative')
                ->image('/screenshots/14-dashboard-widgets.png')
                ->url('/')
        );

        return Inertia::render('Landing/Index', [
            'stats' => [
                ['label' => 'Packages', 'value' => '14+'],
                ['label' => 'Form Fields', 'value' => '30+'],
                ['label' => 'Type Safe', 'value' => '100%'],
                ['label' => 'AI Powered', 'value' => 'Yes'],
            ],
            'features' => [
                [
                    'icon' => 'forms',
                    'title' => 'Powerful Forms',
                    'description' => 'Build complex forms with 30+ field types, validation, and conditional logic.',
                ],
                [
                    'icon' => 'tables',
                    'title' => 'Advanced Tables',
                    'description' => 'Feature-rich tables with sorting, filtering, searching, and bulk actions.',
                ],
                [
                    'icon' => 'typescript',
                    'title' => 'TypeScript First',
                    'description' => 'Full type safety from PHP to Vue with automatic TypeScript generation.',
                ],
                [
                    'icon' => 'vue',
                    'title' => 'Vue 3 + Inertia',
                    'description' => 'Modern frontend stack with composition API and seamless SPA experience.',
                ],
                [
                    'icon' => 'ai',
                    'title' => 'AI Integration',
                    'description' => 'Built-in AI assistants, prompt management, and intelligent form fields.',
                ],
                [
                    'icon' => 'auth',
                    'title' => 'Complete Auth',
                    'description' => 'Social login, 2FA, passkeys, and role-based access control.',
                ],
            ],
            'packages' => [
                ['name' => 'laravilt', 'description' => 'Core framework and orchestration'],
                ['name' => 'panel', 'description' => 'Admin panel and multi-tenancy'],
                ['name' => 'forms', 'description' => 'Form builder with 30+ fields'],
                ['name' => 'tables', 'description' => 'Advanced data tables'],
                ['name' => 'infolists', 'description' => 'Read-only data display'],
                ['name' => 'actions', 'description' => 'Actions and modals'],
                ['name' => 'notifications', 'description' => 'Toast and database notifications'],
                ['name' => 'widgets', 'description' => 'Dashboard widgets and charts'],
                ['name' => 'auth', 'description' => 'Authentication and authorization'],
                ['name' => 'ai', 'description' => 'AI assistants and prompts'],
                ['name' => 'schemas', 'description' => 'Schema components'],
                ['name' => 'support', 'description' => 'Utilities and helpers'],
                ['name' => 'query-builder', 'description' => 'Advanced query building'],
            ],
        ]);
    }
}
