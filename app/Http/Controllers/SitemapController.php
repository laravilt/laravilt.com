<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $docs = Documentation::select('path', 'updated_at')->get();
        $now = now()->toIso8601String();

        $content = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;

        // Static pages with images
        $staticPages = [
            [
                'url' => '/',
                'priority' => '1.0',
                'changefreq' => 'weekly',
                'image' => '/screenshots/14-dashboard-widgets.png',
                'imageTitle' => 'Laravilt Dashboard',
            ],
            [
                'url' => '/docs',
                'priority' => '0.9',
                'changefreq' => 'daily',
                'image' => '/screenshots/01-products-table-view.png',
                'imageTitle' => 'Laravilt Documentation',
            ],
            [
                'url' => '/demo',
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'image' => '/screenshots/16-dashboard-light-mode.png',
                'imageTitle' => 'Try Laravilt Demo',
            ],
            [
                'url' => '/brand',
                'priority' => '0.6',
                'changefreq' => 'monthly',
                'image' => '/arts/logo.png',
                'imageTitle' => 'Laravilt Brand Assets',
            ],
        ];

        foreach ($staticPages as $page) {
            $content .= '  <url>' . PHP_EOL;
            $content .= '    <loc>' . url($page['url']) . '</loc>' . PHP_EOL;
            $content .= '    <lastmod>' . $now . '</lastmod>' . PHP_EOL;
            $content .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . PHP_EOL;
            $content .= '    <priority>' . $page['priority'] . '</priority>' . PHP_EOL;
            if (!empty($page['image'])) {
                $content .= '    <image:image>' . PHP_EOL;
                $content .= '      <image:loc>' . url($page['image']) . '</image:loc>' . PHP_EOL;
                $content .= '      <image:title>' . htmlspecialchars($page['imageTitle']) . '</image:title>' . PHP_EOL;
                $content .= '    </image:image>' . PHP_EOL;
            }
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

    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /api/\n";
        $content .= "\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
