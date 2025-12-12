<?php

namespace App\Http\Controllers;

use App\Services\DocumentationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentationController extends Controller
{
    public function __construct(protected DocumentationService $docs)
    {
    }

    public function index()
    {
        $content = $this->docs->get('getting-started/installation');

        // If no content exists, show a message to sync
        if (!$content) {
            $content = [
                'title' => 'Documentation',
                'description' => 'Run php artisan docs:sync to fetch documentation from GitHub.',
                'html' => '<h1>Documentation Not Synced</h1><p>Please run <code>php artisan docs:sync</code> to fetch the documentation from GitHub.</p>',
                'editUrl' => 'https://github.com/laravilt/laravilt/tree/master/docs',
            ];
        }

        return Inertia::render('Docs/Index', [
            'navigation' => $this->docs->getNavigation(),
            'content' => $content,
            'currentPage' => 'getting-started/installation',
        ]);
    }

    public function show(Request $request, string $page)
    {
        $path = str_replace('.', '/', $page);
        $content = $this->docs->get($path);

        if (!$content) {
            // Show a not found message instead of 404
            $content = [
                'title' => 'Page Not Found',
                'description' => 'This documentation page has not been synced yet.',
                'html' => '<h1>Page Not Found</h1><p>This documentation page does not exist or has not been synced yet.</p><p>Run <code>php artisan docs:sync</code> to fetch the latest documentation from GitHub.</p>',
                'editUrl' => "https://github.com/laravilt/laravilt/tree/master/docs/{$path}.md",
            ];
        }

        return Inertia::render('Docs/Show', [
            'navigation' => $this->docs->getNavigation(),
            'content' => $content,
            'currentPage' => $path,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = $this->docs->search($query);

        return response()->json($results);
    }
}
