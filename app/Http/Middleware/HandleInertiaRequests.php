<?php

namespace App\Http\Middleware;

use App\Support\SeoData;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'notifications' => session()->get('notifications', []),
            'actionUpdatedData' => session()->get('action_updated_data'),
            // Use closure for lazy evaluation - runs AFTER controller sets SEO data
            'seo' => fn () => $this->getSeoData($request),
        ];
    }

    /**
     * Get SEO data from request attributes (set by controllers) or use defaults.
     * This is called lazily after the controller has run.
     */
    protected function getSeoData(Request $request): array
    {
        $seo = $request->attributes->get('seo', new SeoData());

        // Share SEO data with the view for server-side rendering
        view()->share('seo', $seo->toArray());

        return $seo->toArray();
    }
}
