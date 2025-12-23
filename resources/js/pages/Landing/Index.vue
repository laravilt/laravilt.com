<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppHeader from '@/components/shared/AppHeader.vue'
import LandingHero from '@/components/landing/LandingHero.vue'
import LandingCodeExample from '@/components/landing/LandingCodeExample.vue'
import LandingFeatures from '@/components/landing/LandingFeatures.vue'
import LandingScreenshots from '@/components/landing/LandingScreenshots.vue'
import LandingPoweredBy from '@/components/landing/LandingPoweredBy.vue'
import LandingSponsors from '@/components/landing/LandingSponsors.vue'
import LandingCta from '@/components/landing/LandingCta.vue'
import LandingFooter from '@/components/landing/LandingFooter.vue'
</script>

<template>
    <Head title="Laravilt - Modern Admin Panel for Laravel + Vue">
        <meta name="description" content="Build beautiful, type-safe admin panels with Vue 3, Inertia.js, and Laravel. Inspired by Filament, powered by modern TypeScript." />
    </Head>

    <div class="min-h-screen bg-[#0a0a0a] text-white antialiased">
        <AppHeader :show-search="true" />
        <LandingHero />
        <LandingCodeExample />
        <LandingFeatures />
        <LandingScreenshots />
        <LandingPoweredBy />
        <LandingSponsors />
        <LandingCta />
        <LandingFooter />
    </div>
</template>
