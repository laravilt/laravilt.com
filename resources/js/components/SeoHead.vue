<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

// Get shared SEO data from server (set by controllers)
const page = usePage()
const seo = computed(() => (page.props.seo as Record<string, any>) || {})

// Use server SEO data directly - no component props needed
// Server-side controllers set all SEO data via SeoData class
const fullTitle = computed(() => seo.value.fullTitle || 'Laravilt - Modern Admin Panel Framework for Laravel + Vue')
const description = computed(() => seo.value.description || 'Laravilt is a modern admin panel framework for Laravel and Vue.js.')
const keywords = computed(() => seo.value.keywords || 'laravel, vue, admin panel')
const canonicalUrl = computed(() => seo.value.url || (typeof window !== 'undefined' ? window.location.href : ''))
const imageUrl = computed(() => seo.value.image || '/screenshots/14-dashboard-widgets.png')
const type = computed(() => seo.value.type || 'website')
const author = computed(() => seo.value.author || 'Laravilt')
const noindex = computed(() => seo.value.noindex || false)
const siteName = computed(() => seo.value.siteName || 'Laravilt')
const twitterHandle = computed(() => seo.value.twitterHandle || '@laravilt')
</script>

<template>
    <Head>
        <!-- Primary Meta Tags -->
        <title>{{ fullTitle }}</title>
        <meta name="title" :content="fullTitle" />
        <meta name="description" :content="description" />
        <meta name="keywords" :content="keywords" />
        <meta name="author" :content="author" />
        <meta name="robots" :content="noindex ? 'noindex, nofollow' : 'index, follow'" />
        <link rel="canonical" :href="canonicalUrl" />

        <!-- Open Graph / Facebook -->
        <meta property="og:type" :content="type" />
        <meta property="og:site_name" :content="siteName" />
        <meta property="og:url" :content="canonicalUrl" />
        <meta property="og:title" :content="fullTitle" />
        <meta property="og:description" :content="description" />
        <meta property="og:image" :content="imageUrl" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:locale" content="en_US" />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" :content="twitterHandle" />
        <meta name="twitter:creator" :content="twitterHandle" />
        <meta name="twitter:url" :content="canonicalUrl" />
        <meta name="twitter:title" :content="fullTitle" />
        <meta name="twitter:description" :content="description" />
        <meta name="twitter:image" :content="imageUrl" />
    </Head>
</template>
