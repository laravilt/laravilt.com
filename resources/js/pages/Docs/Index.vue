<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, nextTick } from 'vue'
import hljs from 'highlight.js/lib/core'
import php from 'highlight.js/lib/languages/php'
import typescript from 'highlight.js/lib/languages/typescript'
import bash from 'highlight.js/lib/languages/bash'
import xml from 'highlight.js/lib/languages/xml'
import javascript from 'highlight.js/lib/languages/javascript'
import 'highlight.js/styles/github-dark.css'
import DocSearch from '@/components/DocSearch.vue'

hljs.registerLanguage('php', php)
hljs.registerLanguage('typescript', typescript)
hljs.registerLanguage('bash', bash)
hljs.registerLanguage('xml', xml)
hljs.registerLanguage('html', xml)
hljs.registerLanguage('javascript', javascript)
hljs.registerLanguage('vue', xml)

interface NavItem {
    title: string
    path: string
}

interface NavSection {
    title: string
    items: NavItem[]
}

interface Content {
    title: string
    description?: string
    html: string
    editUrl: string
}

const props = defineProps<{
    navigation: NavSection[]
    content: Content
    currentPage: string
}>()

const sidebarOpen = ref(false)

const addCopyButtons = () => {
    document.querySelectorAll('pre').forEach((pre) => {
        if (pre.querySelector('.copy-button')) return

        const wrapper = document.createElement('div')
        wrapper.className = 'relative group'
        pre.parentNode?.insertBefore(wrapper, pre)
        wrapper.appendChild(pre)

        const button = document.createElement('button')
        button.className = 'copy-button absolute top-2 right-2 p-2 rounded-lg bg-white/10 text-gray-400 opacity-0 group-hover:opacity-100 transition hover:bg-white/20 hover:text-white'
        button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>`

        button.addEventListener('click', async () => {
            const code = pre.querySelector('code')?.textContent || ''
            await navigator.clipboard.writeText(code)

            button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-[#04bdaf]"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>`

            setTimeout(() => {
                button.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>`
            }, 2000)
        })

        wrapper.appendChild(button)
    })
}

onMounted(() => {
    nextTick(() => {
        document.querySelectorAll('pre code').forEach((el) => {
            hljs.highlightElement(el as HTMLElement)
        })
        addCopyButtons()
    })
})

const isActive = (path: string) => props.currentPage === path
</script>

<template>
    <Head :title="`${content.title} - Laravilt Documentation`" />

    <div class="min-h-screen bg-gray-900 text-white">
        <!-- Top Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 border-b border-white/10 bg-gray-900/80 backdrop-blur-xl">
            <div class="mx-auto flex h-16 items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <Link href="/" class="flex items-center gap-3 text-xl font-bold">
                        <svg class="h-8 w-8" viewBox="0 0 263 306.97" xmlns="http://www.w3.org/2000/svg">
                            <polygon fill="#04bdaf" points="223 18.65 148 53.63 95 78.34 95 161.09 148 136.38 148 306.84 223 271.87 223 101.4 263 82.75 263 0 223 18.65"/>
                            <polygon fill="#822478" points="75 189.76 75 35 0 0 0 146.46 0 217.47 0 233.07 75 276.37 128 306.97 128 220.36 75 189.76"/>
                        </svg>
                        <span>Laravilt</span>
                    </Link>
                </div>
                <div class="flex items-center gap-4">
                    <DocSearch />
                    <a href="https://github.com/laravilt/laravilt" target="_blank" class="text-gray-400 transition hover:text-white">
                        <svg class="size-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>
        </nav>

        <div class="flex pt-16">
            <!-- Sidebar -->
            <aside :class="['fixed inset-y-0 left-0 z-40 w-72 transform overflow-y-auto border-r border-white/10 bg-gray-900 pt-16 transition-transform duration-300 lg:translate-x-0', sidebarOpen ? 'translate-x-0' : '-translate-x-full']">
                <div class="p-6">
                    <nav class="space-y-8">
                        <div v-for="section in navigation" :key="section.title">
                            <h4 class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ section.title }}</h4>
                            <ul class="space-y-1">
                                <li v-for="item in section.items" :key="item.path">
                                    <Link :href="`/docs/${item.path}`" :class="['block rounded-lg px-3 py-2 text-sm transition', isActive(item.path) ? 'bg-[#04bdaf]/10 text-[#04bdaf]' : 'text-gray-400 hover:bg-white/5 hover:text-white']">
                                        {{ item.title }}
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Backdrop -->
            <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/50 lg:hidden"></div>

            <!-- Main Content -->
            <main class="min-h-screen flex-1 lg:pl-72">
                <div class="mx-auto max-w-4xl px-4 py-12 lg:px-8">
                    <article class="prose prose-invert max-w-none prose-headings:scroll-mt-20 prose-a:text-[#04bdaf] prose-code:rounded prose-code:bg-gray-800 prose-code:px-1 prose-code:py-0.5 prose-code:before:content-none prose-code:after:content-none prose-pre:bg-gray-800" v-html="content?.html"></article>

                    <!-- Edit Link -->
                    <div class="mt-12 border-t border-white/10 pt-8">
                        <a :href="content?.editUrl" target="_blank" class="inline-flex items-center gap-2 text-sm text-gray-500 transition hover:text-[#04bdaf]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            Edit this page on GitHub
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style>
.prose pre {
    border-radius: 0.75rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.prose pre code {
    font-size: 0.875rem;
}

.heading-permalink {
    margin-right: 0.5rem;
    color: rgb(75 85 99);
    text-decoration: none;
    opacity: 0;
    transition: opacity 150ms;
}

.prose h2:hover .heading-permalink,
.prose h3:hover .heading-permalink,
.prose h4:hover .heading-permalink {
    opacity: 1;
}
</style>
