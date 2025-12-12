<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'

interface Props {
    title: string
    description?: string
    keywords?: string
    image?: string
    url?: string
    type?: 'website' | 'article'
    author?: string
    publishedTime?: string
    modifiedTime?: string
    section?: string
    noindex?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    description: 'Laravilt is a modern admin panel framework for Laravel and Vue.js. Build beautiful, type-safe admin panels with forms, tables, and widgets.',
    keywords: 'laravel, vue, admin panel, crud, forms, tables, typescript, inertia',
    image: '/hero.jpg',
    type: 'website',
    author: 'Laravilt',
    noindex: false,
})

const siteName = 'Laravilt'
const twitterHandle = '@laravilt'

const fullTitle = computed(() => {
    if (props.title === 'Home' || props.title === siteName) {
        return `${siteName} - Modern Admin Panel Framework for Laravel + Vue`
    }
    return `${props.title} | ${siteName}`
})

const canonicalUrl = computed(() => {
    if (props.url) {
        return props.url.startsWith('http') ? props.url : `${window.location.origin}${props.url}`
    }
    return typeof window !== 'undefined' ? window.location.href : ''
})

const imageUrl = computed(() => {
    if (props.image?.startsWith('http')) {
        return props.image
    }
    return typeof window !== 'undefined' ? `${window.location.origin}${props.image}` : props.image
})
</script>

<template>
    <Head>
        <title>{{ fullTitle }}</title>

        <!-- Primary Meta Tags -->
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

        <!-- Article specific (for docs) -->
        <meta v-if="type === 'article' && publishedTime" property="article:published_time" :content="publishedTime" />
        <meta v-if="type === 'article' && modifiedTime" property="article:modified_time" :content="modifiedTime" />
        <meta v-if="type === 'article' && section" property="article:section" :content="section" />
        <meta v-if="type === 'article'" property="article:author" :content="author" />

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" :content="twitterHandle" />
        <meta name="twitter:creator" :content="twitterHandle" />
        <meta name="twitter:url" :content="canonicalUrl" />
        <meta name="twitter:title" :content="fullTitle" />
        <meta name="twitter:description" :content="description" />
        <meta name="twitter:image" :content="imageUrl" />

        <!-- Additional SEO -->
        <meta name="theme-color" content="#04bdaf" />
        <meta name="msapplication-TileColor" content="#04bdaf" />
    </Head>
</template>
