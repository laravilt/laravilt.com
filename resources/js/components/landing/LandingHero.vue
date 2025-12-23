<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const isVisible = ref(false)
const copied = ref(false)

const copyInstallCommand = async () => {
    await navigator.clipboard.writeText('composer require laravilt/laravilt')
    copied.value = true
    setTimeout(() => copied.value = false, 2000)
}

const animatedStats = ref([
    { label: 'GitHub Stars', value: 0, target: 1200, suffix: '+' },
    { label: 'Downloads', value: 0, target: 50, suffix: 'K+' },
    { label: 'Contributors', value: 0, target: 45, suffix: '+' },
    { label: 'Components', value: 0, target: 100, suffix: '+' },
])

onMounted(() => {
    // Trigger entrance animations
    setTimeout(() => isVisible.value = true, 100)

    animatedStats.value.forEach((stat, index) => {
        const duration = 2000
        const steps = 60
        const increment = stat.target / steps
        let current = 0
        const interval = setInterval(() => {
            current += increment
            if (current >= stat.target) {
                animatedStats.value[index].value = stat.target
                clearInterval(interval)
            } else {
                animatedStats.value[index].value = Math.floor(current)
            }
        }, duration / steps)
    })
})
</script>

<template>
    <section class="relative overflow-hidden pt-32 pb-24 lg:pt-40 lg:pb-32">
        <!-- Background Effects -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[600px] bg-gradient-to-b from-[#04bdaf]/20 via-[#822478]/10 to-transparent rounded-full blur-3xl"></div>
            <div class="absolute top-20 left-1/4 w-72 h-72 bg-[#04bdaf]/10 rounded-full blur-3xl"></div>
            <div class="absolute top-40 right-1/4 w-96 h-96 bg-[#822478]/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:64px_64px]"></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <!-- Badge -->
                <div :class="['mb-8 inline-flex items-center gap-2 rounded-full border border-[#04bdaf]/20 bg-[#04bdaf]/5 px-4 py-1.5 text-sm transition-all duration-700', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4']">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#04bdaf] opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-[#04bdaf]"></span>
                    </span>
                    <span class="text-[#04bdaf]">v1.0 is here</span>
                    <span class="text-zinc-500">—</span>
                    <a href="/docs/getting-started/installation" class="text-zinc-400 hover:text-white transition">Read the announcement</a>
                </div>

                <!-- Headline -->
                <h1 :class="['mx-auto max-w-4xl text-4xl font-bold tracking-tight sm:text-6xl lg:text-7xl transition-all duration-700 delay-100', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4']">
                    Build admin panels
                    <span class="block mt-2 bg-gradient-to-r from-[#04bdaf] via-[#04bdaf] to-[#822478] bg-clip-text text-transparent">without the complexity</span>
                </h1>

                <!-- Subheadline -->
                <p :class="['mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-zinc-400 sm:text-xl transition-all duration-700 delay-200', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4']">
                    A beautifully designed admin panel framework for Laravel.
                    Type-safe forms, tables, and widgets powered by Vue 3 and Inertia.js.
                </p>

                <!-- CTA Buttons -->
                <div :class="['mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row transition-all duration-700 delay-300', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4']">
                    <Link href="/docs/getting-started/installation" class="group inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 text-base font-semibold text-black transition hover:bg-zinc-200">
                        Get Started
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 transition group-hover:translate-x-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                    <button
                        @click="copyInstallCommand"
                        class="group inline-flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-6 py-3 font-mono text-sm transition hover:bg-white/10"
                    >
                        <span class="text-zinc-500">$</span>
                        <span>composer require laravilt/laravilt</span>
                        <svg v-if="!copied" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-zinc-500 transition group-hover:text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 text-[#04bdaf]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </button>
                </div>

                <!-- Stats -->
                <div :class="['mt-16 grid grid-cols-2 gap-8 sm:grid-cols-4 transition-all duration-700 delay-500', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4']">
                    <div v-for="stat in animatedStats" :key="stat.label" class="text-center">
                        <div class="text-2xl font-bold text-white sm:text-3xl">{{ stat.value }}{{ stat.suffix }}</div>
                        <div class="mt-1 text-sm text-zinc-500">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
