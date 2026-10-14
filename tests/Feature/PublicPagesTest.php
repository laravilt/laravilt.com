<?php

test('public pages render', function (string $uri) {
    $this->get($uri)->assertOk();
})->with([
    'home' => '/',
    'brand' => '/brand',
    'demo' => '/demo',
    'docs index' => '/docs',
    'docs page' => '/docs/getting-started/installation',
    'docs search' => '/docs/search?q=install',
    'sitemap' => '/sitemap.xml',
    'robots' => '/robots.txt',
]);

test('panel login page renders', function () {
    $this->get('/admin/login')->assertOk();
});
