<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $docs = Documentation::select('path', 'updated_at')->get();

        $content = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Static pages
        $staticPages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => '/docs', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/brand', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $content .= '  <url>' . PHP_EOL;
            $content .= '    <loc>' . url($page['url']) . '</loc>' . PHP_EOL;
            $content .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . PHP_EOL;
            $content .= '    <priority>' . $page['priority'] . '</priority>' . PHP_EOL;
            $content .= '  </url>' . PHP_EOL;
        }

        // Documentation pages
        foreach ($docs as $doc) {
            $content .= '  <url>' . PHP_EOL;
            $content .= '    <loc>' . url('/docs/' . $doc->path) . '</loc>' . PHP_EOL;
            $content .= '    <lastmod>' . $doc->updated_at->toIso8601String() . '</lastmod>' . PHP_EOL;
            $content .= '    <changefreq>weekly</changefreq>' . PHP_EOL;
            $content .= '    <priority>0.8</priority>' . PHP_EOL;
            $content .= '  </url>' . PHP_EOL;
        }

        $content .= '</urlset>';

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
