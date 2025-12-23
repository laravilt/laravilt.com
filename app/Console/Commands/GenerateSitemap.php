<?php

namespace App\Console\Commands;

use App\Models\Documentation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap.xml...');

        $baseUrl = config('app.url', 'https://laravilt.com');
        $urls = [];

        // Add static pages
        $urls[] = [
            'loc' => $baseUrl,
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];

        $urls[] = [
            'loc' => $baseUrl . '/docs',
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];

        // Add all documentation pages
        $docs = Documentation::all();
        $this->info("Found {$docs->count()} documentation pages.");

        foreach ($docs as $doc) {
            $path = $doc->path === 'README' ? '' : '/' . $doc->path;
            $urls[] = [
                'loc' => $baseUrl . '/docs' . $path,
                'lastmod' => $doc->updated_at->toW3cString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // Generate XML
        $xml = $this->generateXml($urls);

        // Save to public folder
        $path = public_path('sitemap.xml');
        File::put($path, $xml);

        $this->info("Sitemap generated with " . count($urls) . " URLs.");
        $this->info("Saved to: {$path}");

        return Command::SUCCESS;
    }

    /**
     * Generate XML sitemap content.
     */
    protected function generateXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;

            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            }

            if (isset($url['changefreq'])) {
                $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            }

            if (isset($url['priority'])) {
                $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            }

            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>' . PHP_EOL;

        return $xml;
    }
}
