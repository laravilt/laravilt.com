<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted, nextTick } from 'vue'
import { router } from '@inertiajs/vue3'

interface SearchResult {
    path: string
    title: string
    description: string | null
}

const isOpen = ref(false)
const query = ref('')
const results = ref<SearchResult[]>([])
const isLoading = ref(false)
const selectedIndex = ref(0)
const inputRef = ref<HTMLInputElement | null>(null)

let searchTimeout: ReturnType<typeof setTimeout> | null = null

const open = () => {
    isOpen.value = true
    nextTick(() => {
        inputRef.value?.focus()
    })
}

const close = () => {
    isOpen.value = false
    query.value = ''
    results.value = []
    selectedIndex.value = 0
}

const search = async (searchQuery: string) => {
    if (searchQuery.length < 2) {
        results.value = []
        return
    }

    isLoading.value = true
    try {
        const response = await fetch(`/docs/search?q=${encodeURIComponent(searchQuery)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`)
        }

        const contentType = response.headers.get('content-type')
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON')
        }

        results.value = await response.json()
        selectedIndex.value = 0
    } catch (error) {
        console.error('Search error:', error)
        results.value = []
    } finally {
        isLoading.value = false
    }
}

watch(query, (newQuery) => {
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }
    searchTimeout = setTimeout(() => {
        search(newQuery)
    }, 200)
})

const navigateToResult = (result: SearchResult) => {
    close()
    router.visit(`/docs/${result.path}`)
}

const handleKeydown = (e: KeyboardEvent) => {
    if (e.key === 'ArrowDown') {
        e.preventDefault()
        selectedIndex.value = Math.min(selectedIndex.value + 1, results.value.length - 1)
    } else if (e.key === 'ArrowUp') {
        e.preventDefault()
        selectedIndex.value = Math.max(selectedIndex.value - 1, 0)
    } else if (e.key === 'Enter' && results.value[selectedIndex.value]) {
        e.preventDefault()
        navigateToResult(results.value[selectedIndex.value])
    } else if (e.key === 'Escape') {
        close()
    }
}

const handleGlobalKeydown = (e: KeyboardEvent) => {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault()
        if (isOpen.value) {
            close()
        } else {
            open()
        }
    }
}

onMounted(() => {
    document.addEventListener('keydown', handleGlobalKeydown)
})

onUnmounted(() => {
    document.removeEventListener('keydown', handleGlobalKeydown)
    if (searchTimeout) {
        clearTimeout(searchTimeout)
    }
})

defineExpose({ open })
</script>

<template>
    <!-- Search Button -->
    <button
        @click="open"
        class="flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-sm text-gray-400 transition hover:border-white/20 hover:bg-white/10 hover:text-white"
    >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <span class="hidden sm:inline">Search docs...</span>
        <kbd class="hidden rounded bg-white/10 px-1.5 py-0.5 text-xs font-medium text-gray-500 sm:inline">⌘K</kbd>
    </button>

    <!-- Modal Backdrop -->
    <Teleport to="body">
        <Transition
            enter-active-class="duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isOpen" class="fixed inset-0 z-[100] bg-black/70 backdrop-blur-sm" @click="close"></div>
        </Transition>

        <!-- Modal -->
        <Transition
            enter-active-class="duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-if="isOpen" class="fixed inset-x-4 top-[15%] z-[101] mx-auto max-w-2xl overflow-hidden rounded-xl border border-white/10 bg-gray-900 shadow-2xl">
                <!-- Search Input -->
                <div class="flex items-center gap-3 border-b border-white/10 px-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input
                        ref="inputRef"
                        v-model="query"
                        type="text"
                        placeholder="Search documentation..."
                        class="flex-1 bg-transparent py-4 text-white placeholder-gray-500 outline-none"
                        @keydown="handleKeydown"
                    />
                    <kbd class="rounded bg-white/10 px-2 py-1 text-xs text-gray-500">ESC</kbd>
                </div>

                <!-- Results -->
                <div class="max-h-96 overflow-y-auto">
                    <!-- Loading State -->
                    <div v-if="isLoading" class="flex items-center justify-center py-12">
                        <svg class="size-6 animate-spin text-[#04bdaf]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="query.length >= 2 && results.length === 0" class="py-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto size-12 text-gray-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <p class="mt-4 text-gray-500">No results found for "{{ query }}"</p>
                    </div>

                    <!-- Initial State -->
                    <div v-else-if="query.length < 2" class="py-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto size-12 text-gray-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <p class="mt-4 text-gray-500">Type to search documentation...</p>
                    </div>

                    <!-- Results List -->
                    <ul v-else class="py-2">
                        <li v-for="(result, index) in results" :key="result.path">
                            <button
                                @click="navigateToResult(result)"
                                :class="[
                                    'flex w-full items-start gap-3 px-4 py-3 text-left transition',
                                    index === selectedIndex ? 'bg-[#04bdaf]/10' : 'hover:bg-white/5'
                                ]"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="['mt-0.5 size-5 shrink-0', index === selectedIndex ? 'text-[#04bdaf]' : 'text-gray-500']">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <div class="min-w-0 flex-1">
                                    <div :class="['font-medium', index === selectedIndex ? 'text-[#04bdaf]' : 'text-white']">
                                        {{ result.title }}
                                    </div>
                                    <div class="mt-0.5 truncate text-sm text-gray-500">
                                        {{ result.path }}
                                    </div>
                                </div>
                                <svg v-if="index === selectedIndex" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mt-0.5 size-5 shrink-0 text-[#04bdaf]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between border-t border-white/10 px-4 py-3 text-xs text-gray-500">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1">
                            <kbd class="rounded bg-white/10 px-1.5 py-0.5">↑</kbd>
                            <kbd class="rounded bg-white/10 px-1.5 py-0.5">↓</kbd>
                            to navigate
                        </span>
                        <span class="flex items-center gap-1">
                            <kbd class="rounded bg-white/10 px-1.5 py-0.5">↵</kbd>
                            to select
                        </span>
                    </div>
                    <span class="flex items-center gap-1">
                        <kbd class="rounded bg-white/10 px-1.5 py-0.5">ESC</kbd>
                        to close
                    </span>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
