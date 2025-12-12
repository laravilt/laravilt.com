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
        return Inertia::render('Docs/Index', [
            'navigation' => $this->docs->getNavigation(),
            'content' => $this->docs->get('getting-started/README'),
            'currentPage' => 'getting-started/README',
        ]);
    }

    public function show(Request $request, string $page)
    {
        $path = str_replace('.', '/', $page);
        $content = $this->docs->get($path);

        if (!$content) {
            abort(404);
        }

        return Inertia::render('Docs/Show', [
            'navigation' => $this->docs->getNavigation(),
            'content' => $content,
            'currentPage' => $path,
        ]);
    }
}
