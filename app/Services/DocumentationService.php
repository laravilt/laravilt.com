<?php

namespace App\Services;

use App\Models\Documentation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class DocumentationService
{
    protected MarkdownConverter $converter;
    protected string $repo = 'laravilt/laravilt';
    protected string $branch = 'master';
    protected string $docsPath = 'docs';

    public function __construct()
    {
        $config = [
            'heading_permalink' => [
                'html_class' => 'heading-permalink',
                'id_prefix' => 'content',
                'fragment_prefix' => '',
                'insert' => 'before',
                'min_heading_level' => 2,
                'max_heading_level' => 4,
                'title' => 'Permalink',
                'symbol' => '#',
                'aria_hidden' => true,
            ],
            'table_of_contents' => [
                'html_class' => 'table-of-contents',
                'position' => 'placeholder',
                'style' => 'bullet',
                'min_heading_level' => 2,
                'max_heading_level' => 3,
                'normalize' => 'relative',
                'placeholder' => '[TOC]',
            ],
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new HeadingPermalinkExtension());
        $environment->addExtension(new TableOfContentsExtension());

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * Get documentation page by path.
     */
    public function get(string $path): ?array
    {
        $doc = Documentation::where('path', $path)->first();

        if (!$doc) {
            // Try with README suffix
            $doc = Documentation::where('path', $path . '/README')->first();
        }

        if (!$doc) {
            return null;
        }

        return [
            'title' => $doc->title,
            'description' => $doc->description,
            'html' => $doc->content_html,
            'editUrl' => $doc->edit_url,
        ];
    }

    /**
     * Search documentation.
     */
    public function search(string $query): array
    {
        return Documentation::search($query)
            ->select(['path', 'title', 'description'])
            ->limit(20)
            ->get()
            ->map(fn ($doc) => [
                'path' => $doc->path,
                'title' => $doc->title,
                'description' => $doc->description,
            ])
            ->toArray();
    }

    /**
     * Sync documentation from GitHub.
     */
    public function syncFromGithub(): array
    {
        $synced = [];
        $files = $this->getDocsFilesFromGithub();

        foreach ($files as $file) {
            if (!str_ends_with($file['path'], '.md')) {
                continue;
            }

            $relativePath = str_replace($this->docsPath . '/', '', $file['path']);
            $relativePath = str_replace('.md', '', $relativePath);

            // Fetch file content
            $content = $this->fetchFileContent($file['path'], $file['sha']);

            if ($content) {
                $this->saveDocumentation($relativePath, $content, $file['sha']);
                $synced[] = $relativePath;
            }
        }

        // Clear cache after sync
        Cache::forget('docs_navigation');

        return $synced;
    }

    /**
     * Get all markdown files from GitHub docs folder.
     */
    protected function getDocsFilesFromGithub(): array
    {
        $files = [];
        $this->fetchGithubTree($this->docsPath, $files);
        return $files;
    }

    /**
     * Recursively fetch files from GitHub tree.
     */
    protected function fetchGithubTree(string $path, array &$files): void
    {
        $url = "https://api.github.com/repos/{$this->repo}/contents/{$path}?ref={$this->branch}";

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'Laravilt-Docs',
        ])->get($url);

        if (!$response->successful()) {
            return;
        }

        foreach ($response->json() as $item) {
            if ($item['type'] === 'file' && str_ends_with($item['name'], '.md')) {
                $files[] = [
                    'path' => $item['path'],
                    'sha' => $item['sha'],
                ];
            } elseif ($item['type'] === 'dir') {
                $this->fetchGithubTree($item['path'], $files);
            }
        }
    }

    /**
     * Fetch file content from GitHub.
     */
    protected function fetchFileContent(string $path, string $sha): ?string
    {
        // Check if we already have this version
        $existing = Documentation::where('sha', $sha)->first();
        if ($existing) {
            return null; // Already up to date
        }

        $url = "https://raw.githubusercontent.com/{$this->repo}/{$this->branch}/{$path}";

        $response = Http::withHeaders([
            'User-Agent' => 'Laravilt-Docs',
        ])->get($url);

        if (!$response->successful()) {
            return null;
        }

        return $response->body();
    }

    /**
     * Save documentation to database.
     */
    protected function saveDocumentation(string $path, string $content, string $sha): void
    {
        $title = $this->extractTitle($content);
        $description = null;
        $body = $content;
        $order = 0;

        // Try to parse YAML frontmatter if it exists
        if (str_starts_with(trim($content), '---')) {
            try {
                $document = YamlFrontMatter::parse($content);
                $title = $document->matter('title') ?? $this->extractTitle($document->body());
                $description = $document->matter('description');
                $body = $document->body();
                $order = $document->matter('order') ?? 0;
            } catch (\Exception $e) {
                // Frontmatter parsing failed, use raw content
            }
        }

        $html = $this->converter->convert($body)->getContent();

        Documentation::updateOrCreate(
            ['path' => $path],
            [
                'title' => $title,
                'description' => $description,
                'content_raw' => $body,
                'content_html' => $html,
                'sha' => $sha,
                'order' => $order,
            ]
        );
    }

    /**
     * Extract title from markdown content.
     */
    protected function extractTitle(string $content): string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return trim($matches[1]);
        }
        return 'Documentation';
    }

    /**
     * Get navigation structure.
     */
    public function getNavigation(): array
    {
        return Cache::remember('docs_navigation', 3600, function () {
            return [
                [
                    'title' => 'Getting Started',
                    'items' => $this->getNavItems(['getting-started/installation', 'getting-started/quick-start', 'getting-started/architecture']),
                ],
                [
                    'title' => 'Panel',
                    'items' => $this->getNavItems(['panel/introduction', 'panel/creating-panels', 'panel/navigation', 'panel/resources', 'panel/pages', 'panel/themes']),
                ],
                [
                    'title' => 'Forms',
                    'items' => $this->getNavItems(['forms/introduction', 'forms/field-types', 'forms/custom-fields', 'forms/layouts', 'forms/validation', 'forms/reactive-fields']),
                ],
                [
                    'title' => 'Tables',
                    'items' => $this->getNavItems(['tables/introduction', 'tables/columns', 'tables/filters', 'tables/actions', 'tables/api']),
                ],
                [
                    'title' => 'Infolists',
                    'items' => $this->getNavItems(['infolists/introduction']),
                ],
                [
                    'title' => 'Actions',
                    'items' => $this->getNavItems(['actions/introduction']),
                ],
                [
                    'title' => 'Notifications',
                    'items' => $this->getNavItems(['notifications/introduction']),
                ],
                [
                    'title' => 'Widgets',
                    'items' => $this->getNavItems(['widgets/introduction']),
                ],
                [
                    'title' => 'Authentication',
                    'items' => $this->getNavItems(['auth/introduction', 'auth/methods', 'auth/social', 'auth/two-factor', 'auth/passkeys', 'auth/profile']),
                ],
                [
                    'title' => 'AI Integration',
                    'items' => $this->getNavItems(['ai/introduction']),
                ],
                [
                    'title' => 'Schemas',
                    'items' => $this->getNavItems(['schemas/introduction']),
                ],
                [
                    'title' => 'Advanced',
                    'items' => $this->getNavItems(['query-builder/introduction', 'plugins/introduction', 'support/introduction']),
                ],
            ];
        });
    }

    /**
     * Get nav items with titles from database.
     */
    protected function getNavItems(array $paths): array
    {
        $items = [];
        $docs = Documentation::whereIn('path', $paths)->get()->keyBy('path');

        foreach ($paths as $path) {
            $doc = $docs->get($path);
            $items[] = [
                'title' => $doc?->title ?? $this->pathToTitle($path),
                'path' => $path,
            ];
        }

        return $items;
    }

    /**
     * Convert path to readable title.
     */
    protected function pathToTitle(string $path): string
    {
        $name = basename($path);
        if ($name === 'README') {
            $name = basename(dirname($path));
        }
        return ucwords(str_replace(['-', '_'], ' ', $name));
    }
}
