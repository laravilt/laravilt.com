<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const isVisible = ref(false)
const sectionRef = ref<HTMLElement | null>(null)

const screenshots = [
    { src: '/screenshots/14-dashboard-widgets.png', title: 'Dashboard Widgets', description: 'Beautiful dashboard with charts and stats' },
    { src: '/screenshots/01-products-table-view.png', title: 'Data Tables', description: 'Feature-rich tables with sorting and filtering' },
    { src: '/screenshots/02-products-create-form.png', title: 'Form Builder', description: 'Dynamic forms with 30+ field types' },
    { src: '/screenshots/04-products-view-infolist.png', title: 'Info Lists', description: 'Read-only data display layouts' },
    { src: '/screenshots/06-products-grid-view.png', title: 'Grid View', description: 'Beautiful card layouts for your data' },
    { src: '/screenshots/22-ai-chat-conversation.png', title: 'AI Integration', description: 'Built-in AI chat and assistants' },
]

const currentIndex = ref(0)
let interval: number | null = null

const nextSlide = () => {
    currentIndex.value = (currentIndex.value + 1) % screenshots.length
}

const prevSlide = () => {
    currentIndex.value = (currentIndex.value - 1 + screenshots.length) % screenshots.length
}

const goToSlide = (index: number) => {
    currentIndex.value = index
}

onMounted(() => {
    interval = setInterval(nextSlide, 5000)

    // Intersection Observer for scroll animation
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    isVisible.value = true
                    observer.disconnect()
                }
            })
        },
        { threshold: 0.1 }
    )

    if (sectionRef.value) {
        observer.observe(sectionRef.value)
    }
})

onUnmounted(() => {
    if (interval) clearInterval(interval)
})
</script>

<template>
    <section ref="sectionRef" class="relative py-24 border-t border-white/5">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div :class="['text-center mb-16 transition-all duration-700', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">See it in action</h2>
                <p class="mt-4 text-lg text-zinc-400">Beautiful, responsive admin interfaces out of the box.</p>
            </div>

            <!-- Screenshot Carousel -->
            <div :class="['relative flex flex-col items-center transition-all duration-700 delay-200', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
                <!-- Main Screenshot Container -->
                <div class="relative w-full max-w-5xl">
                    <div class="rounded-2xl border border-white/10 bg-[#0f0f0f] overflow-hidden shadow-2xl">
                        <!-- macOS Title Bar -->
                        <div class="flex items-center justify-between px-4 py-3 bg-[#1a1a1a] border-b border-white/5">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-[#ff5f57]"></span>
                                <span class="w-3 h-3 rounded-full bg-[#febc2e]"></span>
                                <span class="w-3 h-3 rounded-full bg-[#28c840]"></span>
                            </div>
                            <span class="text-xs text-zinc-500 font-medium">{{ screenshots[currentIndex].title }}</span>
                            <div class="w-[52px]"></div>
                        </div>
                        <!-- Screenshot Image -->
                        <div class="relative overflow-hidden">
                            <transition name="fade" mode="out-in">
                                <img
                                    :key="currentIndex"
                                    :src="screenshots[currentIndex].src"
                                    :alt="screenshots[currentIndex].title"
                                    class="w-full h-auto block"
                                />
                            </transition>
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <button
                        @click="prevSlide"
                        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 p-3 rounded-full bg-black/50 backdrop-blur-sm border border-white/10 text-white transition hover:bg-black/70 hidden md:block"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button
                        @click="nextSlide"
                        class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 p-3 rounded-full bg-black/50 backdrop-blur-sm border border-white/10 text-white transition hover:bg-black/70 hidden md:block"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>

                <!-- Dots Navigation -->
                <div class="flex items-center justify-center gap-2 mt-8">
                    <button
                        v-for="(_, index) in screenshots"
                        :key="index"
                        @click="goToSlide(index)"
                        :class="[
                            'w-2 h-2 rounded-full transition-all',
                            index === currentIndex ? 'bg-[#04bdaf] w-6' : 'bg-white/20 hover:bg-white/40'
                        ]"
                    />
                </div>

                <!-- Screenshot Caption -->
                <div class="text-center mt-6">
                    <p class="text-zinc-400">{{ screenshots[currentIndex].description }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
