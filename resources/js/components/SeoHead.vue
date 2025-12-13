<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

interface Props {
    title?: string
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
    type: 'website',
    author: 'Laravilt',
    noindex: false,
})

// Get shared SEO data from server
const page = usePage()
const sharedSeo = computed(() => (page.props.seo as Record<string, any>) || {})

const siteName = 'Laravilt'
const twitterHandle = '@laravilt'

// Use props if provided, otherwise fall back to shared SEO data
const title = computed(() => props.title || sharedSeo.value.title || 'Home')
const description = computed(() => props.description || sharedSeo.value.description || 'Laravilt is a modern admin panel framework for Laravel and Vue.js.')
const keywords = computed(() => props.keywords || sharedSeo.value.keywords || 'laravel, vue, admin panel')
const image = computed(() => props.image || sharedSeo.value.image || '/og-image.png')
const type = computed(() => props.type || sharedSeo.value.type || 'website')

const fullTitle = computed(() => {
    if (title.value === 'Home' || title.value === siteName) {
        return `${siteName} - Modern Admin Panel Framework for Laravel + Vue`
    }
    return `${title.value} | ${siteName}`
})

const canonicalUrl = computed(() => {
    const url = props.url || sharedSeo.value.url
    if (url) {
        return url.startsWith('http') ? url : `${window.location.origin}${url}`
    }
    return typeof window !== 'undefined' ? window.location.href : ''
})

const imageUrl = computed(() => {
    const img = image.value
    if (img?.startsWith('http')) {
        return img
    }
    return typeof window !== 'undefined' ? `${window.location.origin}${img}` : img
})
</script>

<template>
    <Head>
        <!-- Title is the only thing that updates during SPA navigation -->
        <!-- All other meta tags are rendered server-side in app.blade.php for social crawlers -->
        <title>{{ fullTitle }}</title>
    </Head>
</template>
