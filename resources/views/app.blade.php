<!DOCTYPE html>
<html lang="{{ $locale ?? str_replace('_', '-', app()->getLocale()) }}" dir="{{ $direction ?? 'ltr' }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-F2V7GXGK4X"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-F2V7GXGK4X');
        </script>

        {{-- SEO Meta Tags (Server-side rendered for social sharing) --}}
        @php
            $seoData = $seo ?? [
                'fullTitle' => 'Laravilt - Modern Admin Panel Framework for Laravel + Vue',
                'description' => 'Laravilt is a modern admin panel framework for Laravel and Vue.js. Build beautiful, type-safe admin panels with forms, tables, widgets, and AI integration.',
                'keywords' => 'laravel, vue, admin panel, crud, forms, tables, typescript, inertia, filament alternative',
                'image' => url('/screenshots/14-dashboard-widgets.png'),
                'url' => url()->current(),
                'type' => 'website',
                'author' => 'Laravilt',
                'siteName' => 'Laravilt',
                'twitterHandle' => '@laravilt',
                'noindex' => false,
                'publishedTime' => null,
                'modifiedTime' => null,
                'section' => null,
            ];
        @endphp

        {{-- Primary Meta Tags --}}
        <meta name="title" content="{{ $seoData['fullTitle'] }}">
        <meta name="description" content="{{ $seoData['description'] }}">
        <meta name="keywords" content="{{ $seoData['keywords'] }}">
        <meta name="author" content="{{ $seoData['author'] ?? 'Laravilt' }}">
        <meta name="robots" content="{{ ($seoData['noindex'] ?? false) ? 'noindex, nofollow' : 'index, follow' }}">
        <link rel="canonical" href="{{ $seoData['url'] }}">

        {{-- Open Graph / Facebook --}}
        <meta property="og:type" content="{{ $seoData['type'] }}">
        <meta property="og:site_name" content="{{ $seoData['siteName'] }}">
        <meta property="og:url" content="{{ $seoData['url'] }}">
        <meta property="og:title" content="{{ $seoData['fullTitle'] }}">
        <meta property="og:description" content="{{ $seoData['description'] }}">
        <meta property="og:image" content="{{ $seoData['image'] }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:locale" content="en_US">

        {{-- Article specific (for docs/blog) --}}
        @if(($seoData['type'] ?? 'website') === 'article')
            @if(!empty($seoData['publishedTime']))
                <meta property="article:published_time" content="{{ $seoData['publishedTime'] }}">
            @endif
            @if(!empty($seoData['modifiedTime']))
                <meta property="article:modified_time" content="{{ $seoData['modifiedTime'] }}">
            @endif
            @if(!empty($seoData['section']))
                <meta property="article:section" content="{{ $seoData['section'] }}">
            @endif
            <meta property="article:author" content="{{ $seoData['author'] ?? 'Laravilt' }}">
        @endif

        {{-- Twitter --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="{{ $seoData['twitterHandle'] }}">
        <meta name="twitter:creator" content="{{ $seoData['twitterHandle'] }}">
        <meta name="twitter:url" content="{{ $seoData['url'] }}">
        <meta name="twitter:title" content="{{ $seoData['fullTitle'] }}">
        <meta name="twitter:description" content="{{ $seoData['description'] }}">
        <meta name="twitter:image" content="{{ $seoData['image'] }}">

        {{-- Additional SEO --}}
        <meta name="theme-color" content="#04bdaf">
        <meta name="msapplication-TileColor" content="#04bdaf">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        {{-- Also handle locale and direction from page props --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }

                // Handle locale and direction from Inertia page props
                // This will be updated by Vue after hydration
                window.addEventListener('DOMContentLoaded', function() {
                    // Check if Inertia page data has localization info
                    const pageData = document.getElementById('app')?.dataset?.page;
                    if (pageData) {
                        try {
                            const props = JSON.parse(pageData);
                            if (props.props?.localization) {
                                const { locale, direction } = props.props.localization;
                                if (locale) document.documentElement.lang = locale;
                                if (direction) document.documentElement.dir = direction;
                            }
                        } catch (e) {}
                    }
                });
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        {{-- Also includes v-cloak support to prevent flash of unstyled content --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }

            /* Hide elements with v-cloak until Vue has mounted */
            [v-cloak] {
                display: none !important;
            }
        </style>

        <title inertia>{{ $seoData['fullTitle'] }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/js/app.ts'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
