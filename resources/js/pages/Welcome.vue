<script setup lang="ts">
import { Link, Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

interface Stat {
    label: string
    value: string
}

interface Feature {
    icon: string
    title: string
    description: string
}

interface Package {
    name: string
    description: string
}

const props = withDefaults(defineProps<{
    stats?: Stat[]
    features?: Feature[]
    packages?: Package[]
}>(), {
    stats: () => [],
    features: () => [],
    packages: () => [],
})

const iconMap: Record<string, string> = {
    forms: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>`,
    tables: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" /></svg>`,
    typescript: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>`,
    vue: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" /></svg>`,
    ai: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" /></svg>`,
    auth: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>`,
}
</script>

<template>
    <Head title="Welcome to Laravilt">
        <meta name="description" content="Laravilt is a modern admin panel framework for Laravel and Vue.js. Build beautiful, type-safe admin panels with forms, tables, widgets, and AI integration." />
    </Head>

    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-900 to-black text-white">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 border-b border-white/10 bg-gray-900/80 backdrop-blur-xl">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-8">
                    <Link href="/" class="flex items-center gap-3 text-xl font-bold">
                        <svg class="h-8 w-8" viewBox="0 0 263 306.97" xmlns="http://www.w3.org/2000/svg">
                            <polygon fill="#04bdaf" points="223 18.65 148 53.63 95 78.34 95 161.09 148 136.38 148 306.84 223 271.87 223 101.4 263 82.75 263 0 223 18.65"/>
                            <polygon fill="#822478" points="75 189.76 75 35 0 0 0 146.46 0 217.47 0 233.07 75 276.37 128 306.97 128 220.36 75 189.76"/>
                        </svg>
                        <span>Laravilt</span>
                    </Link>
                    <div class="hidden items-center gap-6 md:flex">
                        <a href="https://laravilt.com/docs" target="_blank" class="text-sm text-gray-400 transition hover:text-white">Docs</a>
                        <a href="https://github.com/laravilt/laravilt" target="_blank" class="text-sm text-gray-400 transition hover:text-white">GitHub</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <Link href="/admin" class="rounded-lg bg-gradient-to-r from-[#04bdaf] to-[#822478] px-4 py-2 text-sm font-medium text-white transition hover:opacity-90">
                        Admin Panel
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-32 pb-20">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-[#04bdaf]/20 via-transparent to-transparent"></div>
            <div class="relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-[#04bdaf]/30 bg-[#04bdaf]/10 px-4 py-2 text-sm text-[#04bdaf]">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#04bdaf] opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-[#04bdaf]"></span>
                    </span>
                    v1.0 Now Available
                </div>

                <h1 class="mb-6 text-5xl font-bold tracking-tight sm:text-6xl lg:text-7xl">
                    The Modern <span class="bg-gradient-to-r from-[#04bdaf] to-[#822478] bg-clip-text text-transparent">Admin Panel</span>
                    <br />for Laravel + Vue
                </h1>

                <p class="mx-auto mb-10 max-w-2xl text-lg text-gray-400">
                    Build beautiful, type-safe admin panels with Vue 3, Inertia.js, and Laravel.
                    Inspired by Filament, powered by modern TypeScript.
                </p>

                <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <a href="https://laravilt.com/docs" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#04bdaf] to-[#822478] px-8 py-4 text-lg font-semibold text-white transition hover:opacity-90">
                        Get Started
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <a href="https://github.com/laravilt/laravilt" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-8 py-4 text-lg font-semibold transition hover:bg-white/10">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                        View on GitHub
                    </a>
                </div>

                <!-- Stats -->
                <div v-if="stats.length > 0" class="mt-16 grid grid-cols-2 gap-8 sm:grid-cols-4">
                    <div v-for="stat in stats" :key="stat.label" class="text-center">
                        <div class="text-3xl font-bold text-[#04bdaf] sm:text-4xl">{{ stat.value }}</div>
                        <div class="mt-1 text-sm text-gray-500">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Code Example -->
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-2xl border border-white/10 bg-gray-800/50 p-8">
                    <div class="mb-4 flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-red-500"></span>
                        <span class="h-3 w-3 rounded-full bg-yellow-500"></span>
                        <span class="h-3 w-3 rounded-full bg-green-500"></span>
                        <span class="ml-4 text-sm text-gray-500">ProductResource.php</span>
                    </div>
                    <pre class="overflow-x-auto text-sm"><code class="text-gray-300"><span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">form</span>(): <span class="text-blue-400">array</span>
{
    <span class="text-[#822478]">return</span> [
        TextInput::<span class="text-[#04bdaf]">make</span>(<span class="text-green-400">'name'</span>)
            -><span class="text-[#04bdaf]">required</span>()
            -><span class="text-[#04bdaf]">maxLength</span>(<span class="text-[#04bdaf]">255</span>),

        RichEditor::<span class="text-[#04bdaf]">make</span>(<span class="text-green-400">'description'</span>)
            -><span class="text-[#04bdaf]">columnSpanFull</span>(),

        Select::<span class="text-[#04bdaf]">make</span>(<span class="text-green-400">'category_id'</span>)
            -><span class="text-[#04bdaf]">relationship</span>(<span class="text-green-400">'category'</span>, <span class="text-green-400">'name'</span>)
            -><span class="text-[#04bdaf]">searchable</span>()
            -><span class="text-[#04bdaf]">preload</span>(),
    ];
}</code></pre>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section v-if="features.length > 0" class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-16 text-center">
                    <h2 class="mb-4 text-3xl font-bold sm:text-4xl">Everything You Need</h2>
                    <p class="text-lg text-gray-400">A complete toolkit for building modern admin panels</p>
                </div>

                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="feature in features" :key="feature.title" class="group rounded-2xl border border-white/10 bg-gray-800/30 p-8 transition hover:border-[#04bdaf]/50 hover:bg-gray-800/50">
                        <div class="mb-4 inline-flex rounded-xl bg-gradient-to-br from-[#04bdaf]/20 to-[#822478]/20 p-3 text-[#04bdaf]" v-html="iconMap[feature.icon]"></div>
                        <h3 class="mb-2 text-xl font-semibold">{{ feature.title }}</h3>
                        <p class="text-gray-400">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-3xl bg-gradient-to-r from-[#04bdaf]/20 to-[#822478]/20 p-12 text-center">
                    <h2 class="mb-4 text-3xl font-bold sm:text-4xl">Ready to Get Started?</h2>
                    <p class="mx-auto mb-8 max-w-xl text-lg text-gray-400">
                        Install Laravilt in your Laravel project and start building your admin panel in minutes.
                    </p>
                    <div class="mb-8 inline-block rounded-xl bg-gray-800 px-6 py-3 font-mono text-sm">
                        <span class="text-gray-500">$</span> composer require laravilt/laravilt
                    </div>
                    <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <a href="https://laravilt.com/docs" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#04bdaf] to-[#822478] px-8 py-4 font-semibold text-white transition hover:opacity-90">
                            Read the Docs
                        </a>
                        <a href="https://github.com/laravilt/laravilt" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-8 py-4 font-semibold transition hover:bg-white/10">
                            Star on GitHub
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-white/10 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <div class="flex items-center gap-3 text-xl font-bold">
                        <svg class="h-8 w-8" viewBox="0 0 263 306.97" xmlns="http://www.w3.org/2000/svg">
                            <polygon fill="#04bdaf" points="223 18.65 148 53.63 95 78.34 95 161.09 148 136.38 148 306.84 223 271.87 223 101.4 263 82.75 263 0 223 18.65"/>
                            <polygon fill="#822478" points="75 189.76 75 35 0 0 0 146.46 0 217.47 0 233.07 75 276.37 128 306.97 128 220.36 75 189.76"/>
                        </svg>
                        <span>Laravilt</span>
                    </div>
                    <p class="text-sm text-gray-500">
                        &copy; {{ new Date().getFullYear() }} Laravilt. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
