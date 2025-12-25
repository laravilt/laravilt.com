<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-white/[0.08] bg-[#0a0a0a]/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <Link href="/" class="flex items-center gap-2.5">
                    <svg class="h-8 w-8" viewBox="0 0 263 306.97" xmlns="http://www.w3.org/2000/svg">
                        <polygon fill="#04bdaf" points="223 18.65 148 53.63 95 78.34 95 161.09 148 136.38 148 306.84 223 271.87 223 101.4 263 82.75 263 0 223 18.65"/>
                        <polygon fill="#822478" points="75 189.76 75 35 0 0 0 146.46 0 217.47 0 233.07 75 276.37 128 306.97 128 220.36 75 189.76"/>
                    </svg>
                    <span class="text-lg font-semibold tracking-tight">Laravilt</span>
                </Link>
                <div class="hidden items-center gap-1 md:flex">
                    <Link href="/docs" class="rounded-lg px-3 py-2 text-sm text-zinc-400 transition hover:text-white">Documentation</Link>
                    <a href="https://github.com/laravilt/laravilt" target="_blank" class="rounded-lg px-3 py-2 text-sm text-zinc-400 transition hover:text-white">GitHub</a>
                    <a href="https://discord.gg/gyRhbVUXEZ" target="_blank" class="rounded-lg px-3 py-2 text-sm text-zinc-400 transition hover:text-white">Community</a>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="https://github.com/laravilt/laravilt" target="_blank" class="hidden items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-sm transition hover:bg-white/10 sm:inline-flex">
                    <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                    <span>Star</span>
                </a>
                <Link href="/admin" class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-black transition hover:bg-zinc-200">
                    Demo
                </Link>
            </div>
        </div>
    </nav>
</template>
