<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class DocumentationService
{
    protected string $docsPath;
    protected MarkdownConverter $converter;

    public function __construct()
    {
        // Point to the docs folder in the main laravilt repo
        $this->docsPath = base_path('../laravilt/packages/laravilt/laravilt/docs');

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

    public function get(string $path): ?array
    {
        $filePath = $this->docsPath . '/' . $path . '.md';

        if (!File::exists($filePath)) {
            // Try README.md in directory
            $filePath = $this->docsPath . '/' . $path . '/README.md';
            if (!File::exists($filePath)) {
                return null;
            }
        }

        $content = File::get($filePath);
        $document = YamlFrontMatter::parse($content);

        $html = $this->converter->convert($document->body())->getContent();

        return [
            'title' => $document->matter('title') ?? $this->extractTitle($document->body()),
            'description' => $document->matter('description'),
            'html' => $html,
            'editUrl' => 'https://github.com/laravilt/laravilt/edit/main/docs/' . $path . '.md',
        ];
    }

    protected function extractTitle(string $content): string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return trim($matches[1]);
        }
        return 'Documentation';
    }

    public function getNavigation(): array
    {
        return [
            [
                'title' => 'Getting Started',
                'items' => [
                    ['title' => 'Introduction', 'path' => 'getting-started/README'],
                    ['title' => 'Installation', 'path' => 'getting-started/installation'],
                    ['title' => 'Quick Start', 'path' => 'getting-started/quick-start'],
                    ['title' => 'Directory Structure', 'path' => 'getting-started/directory-structure'],
                    ['title' => 'Configuration', 'path' => 'getting-started/configuration'],
                ],
            ],
            [
                'title' => 'Core Concepts',
                'items' => [
                    ['title' => 'Overview', 'path' => 'core-concepts/README'],
                    ['title' => 'Architecture', 'path' => 'core-concepts/architecture'],
                    ['title' => 'Type Safety', 'path' => 'core-concepts/type-safety'],
                    ['title' => 'Frontend Integration', 'path' => 'core-concepts/frontend-integration'],
                ],
            ],
            [
                'title' => 'Panel',
                'items' => [
                    ['title' => 'Overview', 'path' => 'panel/README'],
                    ['title' => 'Configuration', 'path' => 'panel/configuration'],
                    ['title' => 'Navigation', 'path' => 'panel/navigation'],
                    ['title' => 'Resources', 'path' => 'panel/resources'],
                    ['title' => 'Pages', 'path' => 'panel/pages'],
                ],
            ],
            [
                'title' => 'Forms',
                'items' => [
                    ['title' => 'Overview', 'path' => 'forms/README'],
                    ['title' => 'Getting Started', 'path' => 'forms/getting-started'],
                    ['title' => 'Fields', 'path' => 'forms/fields'],
                    ['title' => 'Layout', 'path' => 'forms/layout'],
                    ['title' => 'Validation', 'path' => 'forms/validation'],
                ],
            ],
            [
                'title' => 'Tables',
                'items' => [
                    ['title' => 'Overview', 'path' => 'tables/README'],
                    ['title' => 'Getting Started', 'path' => 'tables/getting-started'],
                    ['title' => 'Columns', 'path' => 'tables/columns'],
                    ['title' => 'Filters', 'path' => 'tables/filters'],
                    ['title' => 'Actions', 'path' => 'tables/actions'],
                ],
            ],
            [
                'title' => 'Infolists',
                'items' => [
                    ['title' => 'Overview', 'path' => 'infolists/README'],
                    ['title' => 'Getting Started', 'path' => 'infolists/getting-started'],
                    ['title' => 'Entries', 'path' => 'infolists/entries'],
                    ['title' => 'Layout', 'path' => 'infolists/layout'],
                ],
            ],
            [
                'title' => 'Actions',
                'items' => [
                    ['title' => 'Overview', 'path' => 'actions/README'],
                    ['title' => 'Getting Started', 'path' => 'actions/getting-started'],
                    ['title' => 'Modals', 'path' => 'actions/modals'],
                    ['title' => 'Notifications', 'path' => 'actions/notifications'],
                ],
            ],
            [
                'title' => 'Notifications',
                'items' => [
                    ['title' => 'Overview', 'path' => 'notifications/README'],
                    ['title' => 'Getting Started', 'path' => 'notifications/getting-started'],
                    ['title' => 'Database Notifications', 'path' => 'notifications/database-notifications'],
                ],
            ],
            [
                'title' => 'Widgets',
                'items' => [
                    ['title' => 'Overview', 'path' => 'widgets/README'],
                    ['title' => 'Getting Started', 'path' => 'widgets/getting-started'],
                    ['title' => 'Stats', 'path' => 'widgets/stats'],
                    ['title' => 'Charts', 'path' => 'widgets/charts'],
                ],
            ],
            [
                'title' => 'Authentication',
                'items' => [
                    ['title' => 'Overview', 'path' => 'authentication/README'],
                    ['title' => 'Configuration', 'path' => 'authentication/configuration'],
                    ['title' => 'Social Login', 'path' => 'authentication/social-login'],
                    ['title' => 'Two-Factor Auth', 'path' => 'authentication/two-factor'],
                    ['title' => 'Passkeys', 'path' => 'authentication/passkeys'],
                ],
            ],
            [
                'title' => 'AI Integration',
                'items' => [
                    ['title' => 'Overview', 'path' => 'ai/README'],
                    ['title' => 'Configuration', 'path' => 'ai/configuration'],
                    ['title' => 'Assistants', 'path' => 'ai/assistants'],
                    ['title' => 'Prompts', 'path' => 'ai/prompts'],
                ],
            ],
            [
                'title' => 'Schemas',
                'items' => [
                    ['title' => 'Overview', 'path' => 'schemas/README'],
                    ['title' => 'Components', 'path' => 'schemas/components'],
                    ['title' => 'Utilities', 'path' => 'schemas/utilities'],
                ],
            ],
            [
                'title' => 'Advanced',
                'items' => [
                    ['title' => 'Query Builder', 'path' => 'query-builder/README'],
                    ['title' => 'Plugins', 'path' => 'plugins/README'],
                    ['title' => 'Support Utilities', 'path' => 'support/README'],
                ],
            ],
        ];
    }

    public function syncWithGit(): void
    {
        $cwd = getcwd();
        chdir($this->docsPath);
        exec('git pull origin main 2>&1', $output, $returnCode);
        chdir($cwd);
    }
}
