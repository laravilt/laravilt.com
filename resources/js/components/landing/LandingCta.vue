<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const isVisible = ref(false)
const sectionRef = ref<HTMLElement | null>(null)

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    isVisible.value = true
                    observer.disconnect()
                }
            })
        },
        { threshold: 0.2 }
    )

    if (sectionRef.value) {
        observer.observe(sectionRef.value)
    }
})
</script>

<template>
    <section ref="sectionRef" class="relative py-24 border-t border-white/5">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[600px] h-[400px] bg-gradient-to-t from-[#04bdaf]/10 via-[#822478]/5 to-transparent rounded-full blur-3xl"></div>
        </div>
        <div :class="['relative mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8 transition-all duration-700', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
            <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Ready to build something amazing?</h2>
            <p class="mt-4 text-lg text-zinc-400">
                Get started with Laravilt today and build your admin panel in minutes.
            </p>
            <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <Link href="/docs/getting-started/installation" class="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-4 text-base font-semibold text-black transition hover:bg-zinc-200">
                    Read the Documentation
                </Link>
                <Link href="/admin" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-8 py-4 text-base font-semibold transition hover:bg-white/10">
                    Try the Demo
                </Link>
            </div>
        </div>
    </section>
</template>
