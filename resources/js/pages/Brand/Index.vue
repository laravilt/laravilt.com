<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import SeoHead from '@/components/SeoHead.vue'

const logos = [
    { name: 'Logo (Full Color)', file: 'logo.svg', png: 'logo.png', description: 'Primary logo with teal and purple colors' },
    { name: 'Logo (White)', file: 'logo-white.svg', description: 'White version for dark backgrounds' },
    { name: 'Logo (Black)', file: 'logo-black.svg', description: 'Black version for light backgrounds' },
    { name: 'Logo (Teal)', file: 'logo-gre.svg', description: 'Single color teal variant' },
    { name: 'Logo (Purple)', file: 'logo-pur.svg', description: 'Single color purple variant' },
]

const icons = [
    { name: 'Icon (SVG)', file: 'icon.svg', description: 'Vector icon for web use' },
    { name: 'Icon (PNG)', file: 'icon.png', description: 'PNG icon for general use' },
    { name: 'Profile', file: 'profile.png', description: 'Square profile image for social media' },
]

const colors = [
    { name: 'Teal', hex: '#04bdaf', rgb: 'rgb(4, 189, 175)', usage: 'Primary brand color' },
    { name: 'Purple', hex: '#822478', rgb: 'rgb(130, 36, 120)', usage: 'Secondary brand color' },
]

const downloadAsset = (filename: string) => {
    const link = document.createElement('a')
    link.href = `/arts/${filename}`
    link.download = filename
    link.click()
}
</script>

<template>
    <SeoHead
        title="Brand Assets"
        description="Download official Laravilt brand assets including logos, icons, and color guidelines. Use these resources for your projects, articles, and presentations."
        keywords="laravilt logo, laravilt brand, laravilt assets, laravilt icon, brand guidelines"
        url="/brand"
    />

    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-900 to-black text-white">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 border-b border-white/10 bg-gray-900/80 backdrop-blur-xl">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <Link href="/" class="flex items-center gap-3 text-xl font-bold">
                    <svg class="h-8 w-8" viewBox="0 0 263 306.97" xmlns="http://www.w3.org/2000/svg">
                        <polygon fill="#04bdaf" points="223 18.65 148 53.63 95 78.34 95 161.09 148 136.38 148 306.84 223 271.87 223 101.4 263 82.75 263 0 223 18.65"/>
                        <polygon fill="#822478" points="75 189.76 75 35 0 0 0 146.46 0 217.47 0 233.07 75 276.37 128 306.97 128 220.36 75 189.76"/>
                    </svg>
                    <span>Laravilt</span>
                </Link>
                <div class="flex items-center gap-4">
                    <Link href="/docs" class="text-sm text-gray-400 transition hover:text-white">Docs</Link>
                    <a href="https://github.com/laravilt/laravilt" target="_blank" class="text-gray-400 transition hover:text-white">
                        <svg class="size-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="pt-32 pb-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
                        Brand <span class="bg-gradient-to-r from-[#04bdaf] to-[#822478] bg-clip-text text-transparent">Assets</span>
                    </h1>
                    <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-400">
                        Download official Laravilt brand assets for your projects, articles, and presentations.
                    </p>
                </div>
            </div>
        </div>

        <!-- Logos Section -->
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="mb-8 text-2xl font-bold">Logos</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="logo in logos" :key="logo.file" class="group rounded-2xl border border-white/10 bg-gray-800/50 p-6 transition hover:border-[#04bdaf]/50">
                        <div class="mb-4 flex h-32 items-center justify-center rounded-xl" :class="logo.file.includes('white') ? 'bg-gray-700' : logo.file.includes('black') ? 'bg-white' : 'bg-gray-800'">
                            <img :src="`/arts/${logo.file}`" :alt="logo.name" class="h-16 w-auto" />
                        </div>
                        <h3 class="font-semibold">{{ logo.name }}</h3>
                        <p class="mt-1 text-sm text-gray-400">{{ logo.description }}</p>
                        <div class="mt-4 flex gap-2">
                            <button @click="downloadAsset(logo.file)" class="flex-1 rounded-lg bg-white/10 px-3 py-2 text-sm transition hover:bg-white/20">
                                SVG
                            </button>
                            <button v-if="logo.png" @click="downloadAsset(logo.png)" class="flex-1 rounded-lg bg-white/10 px-3 py-2 text-sm transition hover:bg-white/20">
                                PNG
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Icons Section -->
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="mb-8 text-2xl font-bold">Icons & Profile</h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="icon in icons" :key="icon.file" class="group rounded-2xl border border-white/10 bg-gray-800/50 p-6 transition hover:border-[#04bdaf]/50">
                        <div class="mb-4 flex h-32 items-center justify-center rounded-xl bg-gray-800">
                            <img :src="`/arts/${icon.file}`" :alt="icon.name" class="h-16 w-auto" />
                        </div>
                        <h3 class="font-semibold">{{ icon.name }}</h3>
                        <p class="mt-1 text-sm text-gray-400">{{ icon.description }}</p>
                        <button @click="downloadAsset(icon.file)" class="mt-4 w-full rounded-lg bg-white/10 px-3 py-2 text-sm transition hover:bg-white/20">
                            Download
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Colors Section -->
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="mb-8 text-2xl font-bold">Brand Colors</h2>
                <div class="grid gap-6 sm:grid-cols-2">
                    <div v-for="color in colors" :key="color.hex" class="rounded-2xl border border-white/10 bg-gray-800/50 p-6">
                        <div class="mb-4 h-24 rounded-xl" :style="{ backgroundColor: color.hex }"></div>
                        <h3 class="font-semibold">{{ color.name }}</h3>
                        <p class="mt-1 text-sm text-gray-400">{{ color.usage }}</p>
                        <div class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">HEX</span>
                                <code class="rounded bg-white/10 px-2 py-0.5">{{ color.hex }}</code>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">RGB</span>
                                <code class="rounded bg-white/10 px-2 py-0.5">{{ color.rgb }}</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Usage Guidelines -->
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="mb-8 text-2xl font-bold">Usage Guidelines</h2>
                <div class="rounded-2xl border border-white/10 bg-gray-800/50 p-8">
                    <div class="grid gap-8 md:grid-cols-2">
                        <div>
                            <h3 class="mb-4 flex items-center gap-2 font-semibold text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                Do
                            </h3>
                            <ul class="space-y-2 text-sm text-gray-400">
                                <li>Use the logo with adequate clear space</li>
                                <li>Use official brand colors</li>
                                <li>Use white logo on dark backgrounds</li>
                                <li>Use black logo on light backgrounds</li>
                                <li>Maintain aspect ratio when scaling</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="mb-4 flex items-center gap-2 font-semibold text-red-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                                Don't
                            </h3>
                            <ul class="space-y-2 text-sm text-gray-400">
                                <li>Alter or distort the logo</li>
                                <li>Change the logo colors</li>
                                <li>Add effects like shadows or gradients</li>
                                <li>Use on busy or low-contrast backgrounds</li>
                                <li>Rotate or flip the logo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-white/10 py-8">
            <div class="mx-auto max-w-7xl px-4 text-center text-sm text-gray-500 sm:px-6 lg:px-8">
                <p>&copy; {{ new Date().getFullYear() }} Laravilt. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>
