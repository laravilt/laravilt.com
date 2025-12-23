<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue'
import SeoHead from '@/components/SeoHead.vue'
import AppHeader from '@/components/shared/AppHeader.vue'
import hljs from 'highlight.js/lib/core'
import {
    Rocket,
    LayoutDashboard,
    FileEdit,
    Table,
    List,
    Zap,
    Bell,
    LayoutGrid,
    Shield,
    Bot,
    Layers,
    Monitor,
    Database,
    Puzzle,
    Wrench,
    FileText,
    Server,
    HelpCircle,
    ChevronDown
} from 'lucide-vue-next'
import php from 'highlight.js/lib/languages/php'
import typescript from 'highlight.js/lib/languages/typescript'
import bash from 'highlight.js/lib/languages/bash'
import xml from 'highlight.js/lib/languages/xml'
import javascript from 'highlight.js/lib/languages/javascript'
import ini from 'highlight.js/lib/languages/ini'
import json from 'highlight.js/lib/languages/json'
import css from 'highlight.js/lib/languages/css'
import 'highlight.js/styles/github-dark.css'

hljs.registerLanguage('php', php)
hljs.registerLanguage('typescript', typescript)
hljs.registerLanguage('bash', bash)
hljs.registerLanguage('shell', bash)
hljs.registerLanguage('xml', xml)
hljs.registerLanguage('html', xml)
hljs.registerLanguage('javascript', javascript)
hljs.registerLanguage('js', javascript)
hljs.registerLanguage('vue', xml)
hljs.registerLanguage('ini', ini)
hljs.registerLanguage('env', ini)
hljs.registerLanguage('dotenv', ini)
hljs.registerLanguage('json', json)
hljs.registerLanguage('css', css)

interface NavItem {
    title: string
    path: string | null
    children?: NavItem[]
}

interface NavSection {
    title: string
    icon?: string
    items: NavItem[]
}

// Navigation ready state - prevents flash
const navReady = ref(false)

// Track expanded submenus
const expandedSubmenus = ref<Set<string>>(new Set())

const toggleSubmenu = (title: string) => {
    if (expandedSubmenus.value.has(title)) {
        expandedSubmenus.value.delete(title)
    } else {
        expandedSubmenus.value.add(title)
    }
}

const isSubmenuExpanded = (title: string) => expandedSubmenus.value.has(title)

// Auto-expand submenu containing the current page
const expandCurrentSubmenu = () => {
    props.navigation.forEach(section => {
        section.items.forEach(item => {
            if (item.children) {
                const hasActivePage = item.children.some(child => child.path === props.currentPage)
                if (hasActivePage) {
                    expandedSubmenus.value.add(item.title)
                }
            }
        })
    })
}

interface Content {
    title: string
    description?: string
    html: string
    editUrl: string
}

interface TocItem {
    id: string
    text: string
    level: number
}

const props = defineProps<{
    navigation: NavSection[]
    content: Content
    currentPage: string
}>()

// Table of contents - extracted from content headings
const toc = ref<TocItem[]>([])
const activeHeading = ref<string>('')

// Extract headings from rendered article content
const extractToc = () => {
    const article = document.querySelector('article')
    if (!article) return

    const headings = article.querySelectorAll('h2, h3')
    const items: TocItem[] = []

    headings.forEach((heading) => {
        // ID is on the anchor inside the heading, not on the heading itself
        const anchor = heading.querySelector('a.heading-permalink[id]')
        const id = anchor?.id || heading.id
        if (!id) return

        // Get text content excluding the permalink # symbol
        let text = ''
        heading.childNodes.forEach((node) => {
            if (node.nodeType === Node.TEXT_NODE) {
                text += node.textContent
            } else if (node.nodeType === Node.ELEMENT_NODE && !(node as Element).classList.contains('heading-permalink')) {
                text += (node as Element).textContent
            }
        })

        items.push({
            id,
            text: text.trim(),
            level: parseInt(heading.tagName[1])
        })
    })

    toc.value = items
}

// Track active heading on scroll
const updateActiveHeading = () => {
    const headings = document.querySelectorAll('article h2, article h3')
    let current = ''

    headings.forEach((heading) => {
        const anchor = heading.querySelector('a.heading-permalink[id]')
        const id = anchor?.id || heading.id
        if (!id) return

        const rect = heading.getBoundingClientRect()
        if (rect.top <= 100) {
            current = id
        }
    })

    activeHeading.value = current
}

const scrollToHeading = (id: string) => {
    const element = document.getElementById(id)
    if (element) {
        const headerOffset = 80 // Account for fixed header
        const elementPosition = element.getBoundingClientRect().top
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        })
    }
}

const sidebarOpen = ref(false)
const sidebarCollapsed = ref(false)

// Currently expanded section - only one at a time (accordion style like Filament)
const expandedSection = ref<string | null>(null)

// Find section containing current page
const findCurrentSection = (): string | null => {
    for (const section of props.navigation) {
        const hasCurrentPage = section.items.some(item => {
            if (item.path === props.currentPage) return true
            if (item.children) {
                return item.children.some(child => child.path === props.currentPage)
            }
            return false
        })
        if (hasCurrentPage) {
            return section.title
        }
    }
    return props.navigation[0]?.title || null
}

// Initialize expanded section
const initExpandedSection = () => {
    // Find and expand the section containing current page
    expandedSection.value = findCurrentSection()
    // Auto-expand submenu if current page is in a submenu
    expandCurrentSubmenu()
    // Mark navigation as ready
    navReady.value = true
}

// Auto-expand current page's section when page changes
watch(() => props.currentPage, () => {
    expandedSection.value = findCurrentSection()
    expandCurrentSubmenu()
})

// Toggle section - accordion style (only one open at a time)
const toggleSection = (sectionTitle: string) => {
    if (expandedSection.value === sectionTitle) {
        expandedSection.value = null
    } else {
        expandedSection.value = sectionTitle
    }
}

const isSectionExpanded = (sectionTitle: string) => expandedSection.value === sectionTitle

// Persist sidebar state in localStorage
onMounted(() => {
    // Load sidebar collapse state from localStorage
    const saved = localStorage.getItem('docs-sidebar-collapsed')
    if (saved !== null) {
        sidebarCollapsed.value = saved === 'true'
    }

    // Initialize expanded section (accordion style - only one open)
    initExpandedSection()

    // Extract TOC and set up scroll tracking (with delay to ensure content is rendered)
    nextTick(() => {
        setTimeout(() => {
            extractToc()
            updateActiveHeading()
        }, 100)
        window.addEventListener('scroll', updateActiveHeading)
    })
})

// Clean up scroll listener
onUnmounted(() => {
    window.removeEventListener('scroll', updateActiveHeading)
})

watch(() => props.content, () => {
    nextTick(() => {
        setTimeout(() => {
            extractToc()
            updateActiveHeading()
        }, 100)
    })
})

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value
    localStorage.setItem('docs-sidebar-collapsed', String(sidebarCollapsed.value))
}

const addCodeBlockButtons = () => {
    document.querySelectorAll('pre').forEach((pre) => {
        if (pre.parentElement?.classList.contains('code-block-wrapper')) return

        // Get language from code element
        const codeEl = pre.querySelector('code')
        const langClass = codeEl?.className.match(/language-(\w+)/)
        const lang = langClass ? langClass[1].toUpperCase() : 'CODE'

        // Create wrapper with macOS window styling
        const wrapper = document.createElement('div')
        wrapper.className = 'code-block-wrapper group relative rounded-xl overflow-hidden border border-white/10 bg-[#1e1e2e]'
        pre.parentNode?.insertBefore(wrapper, pre)

        // Create title bar
        const titleBar = document.createElement('div')
        titleBar.className = 'flex items-center px-4 py-2.5 bg-[#2d2d3a]'

        // Traffic light buttons
        const trafficLights = document.createElement('div')
        trafficLights.className = 'flex items-center gap-2'
        trafficLights.innerHTML = `
            <span class="w-3 h-3 rounded-full bg-[#ff5f57]"></span>
            <span class="w-3 h-3 rounded-full bg-[#febc2e]"></span>
            <span class="w-3 h-3 rounded-full bg-[#28c840]"></span>
        `

        // Language label (centered)
        const langLabel = document.createElement('span')
        langLabel.className = 'flex-1 text-center text-xs text-gray-500 font-medium'
        langLabel.textContent = lang

        // Spacer for symmetry
        const spacer = document.createElement('div')
        spacer.className = 'w-[52px]'

        titleBar.appendChild(trafficLights)
        titleBar.appendChild(langLabel)
        titleBar.appendChild(spacer)
        wrapper.appendChild(titleBar)

        // Style the pre element
        pre.style.margin = '0'
        pre.style.borderRadius = '0'
        pre.style.border = 'none'
        pre.style.padding = '1rem'
        pre.style.background = 'transparent'
        wrapper.appendChild(pre)

        // Button container (floating top right over code content on hover)
        const buttonContainer = document.createElement('div')
        buttonContainer.className = 'code-buttons absolute top-12 right-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition z-10'
        wrapper.appendChild(buttonContainer)

        // Copy button
        const copyBtn = document.createElement('button')
        copyBtn.className = 'p-1.5 rounded-md text-gray-400 transition hover:bg-white/10 hover:text-white'
        copyBtn.title = 'Copy code'
        copyBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>`

        copyBtn.addEventListener('click', async () => {
            const code = pre.querySelector('code')?.textContent || ''
            await navigator.clipboard.writeText(code)
            copyBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-[#04bdaf]"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>`
            setTimeout(() => {
                copyBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>`
            }, 2000)
        })

        // Download as image button
        const downloadBtn = document.createElement('button')
        downloadBtn.className = 'p-1.5 rounded-md text-gray-400 transition hover:bg-white/10 hover:text-white'
        downloadBtn.title = 'Download as image'
        downloadBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>`

        downloadBtn.addEventListener('click', async () => {
            downloadBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 animate-spin"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>`

            try {
                // Get code element
                const codeEl = pre.querySelector('code')
                if (!codeEl) throw new Error('No code element found')

                // Collect all text segments with their colors
                interface TextSegment {
                    text: string
                    color: string
                }

                const segments: TextSegment[][] = [[]] // Array of lines, each containing segments

                const collectSegments = (node: Node, currentColor: string) => {
                    if (node.nodeType === Node.TEXT_NODE) {
                        const text = node.textContent || ''
                        const parts = text.split('\n')
                        parts.forEach((part, index) => {
                            if (index > 0) {
                                segments.push([]) // Start a new line
                            }
                            if (part) {
                                segments[segments.length - 1].push({ text: part, color: currentColor })
                            }
                        })
                    } else if (node.nodeType === Node.ELEMENT_NODE) {
                        const el = node as HTMLElement
                        const computed = window.getComputedStyle(el)
                        const color = computed.color || currentColor
                        node.childNodes.forEach(child => collectSegments(child, color))
                    }
                }

                // Get default code color
                const defaultColor = window.getComputedStyle(codeEl).color || '#e5e7eb'
                codeEl.childNodes.forEach(child => collectSegments(child, defaultColor))

                // Canvas settings
                const scale = 2
                const padding = 24 * scale
                const titleBarHeight = 40 * scale
                const lineHeight = 22 * scale
                const fontSize = 14 * scale
                const cornerRadius = 12 * scale
                const outerPadding = 32 * scale

                // Measure max line width
                const measureCanvas = document.createElement('canvas')
                const measureCtx = measureCanvas.getContext('2d')!
                measureCtx.font = `${fontSize}px ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace`

                let maxLineWidth = 0
                segments.forEach(line => {
                    let lineWidth = 0
                    line.forEach(segment => {
                        lineWidth += measureCtx.measureText(segment.text).width
                    })
                    if (lineWidth > maxLineWidth) maxLineWidth = lineWidth
                })

                // Calculate dimensions
                const windowWidth = Math.max(400 * scale, maxLineWidth + padding * 2)
                const windowHeight = titleBarHeight + segments.length * lineHeight + padding * 2
                const canvasWidth = windowWidth + outerPadding * 2
                const canvasHeight = windowHeight + outerPadding * 2

                // Create canvas
                const canvas = document.createElement('canvas')
                canvas.width = canvasWidth
                canvas.height = canvasHeight
                const ctx = canvas.getContext('2d')!

                // Background gradient
                const bgGradient = ctx.createLinearGradient(0, 0, canvasWidth, canvasHeight)
                bgGradient.addColorStop(0, '#667eea')
                bgGradient.addColorStop(1, '#764ba2')
                ctx.fillStyle = bgGradient
                ctx.fillRect(0, 0, canvasWidth, canvasHeight)

                // Window shadow
                ctx.shadowColor = 'rgba(0, 0, 0, 0.4)'
                ctx.shadowBlur = 30 * scale
                ctx.shadowOffsetY = 10 * scale

                // Window background with rounded corners
                const windowX = outerPadding
                const windowY = outerPadding
                ctx.fillStyle = '#1e1e2e'
                ctx.beginPath()
                ctx.roundRect(windowX, windowY, windowWidth, windowHeight, cornerRadius)
                ctx.fill()

                // Reset shadow
                ctx.shadowColor = 'transparent'
                ctx.shadowBlur = 0
                ctx.shadowOffsetY = 0

                // Title bar background
                ctx.fillStyle = '#2d2d3a'
                ctx.beginPath()
                ctx.roundRect(windowX, windowY, windowWidth, titleBarHeight, [cornerRadius, cornerRadius, 0, 0])
                ctx.fill()

                // Traffic light buttons
                const buttonY = windowY + titleBarHeight / 2
                const buttonRadius = 6 * scale
                const buttonSpacing = 20 * scale
                const buttonStartX = windowX + 18 * scale

                // Red button
                ctx.fillStyle = '#ff5f57'
                ctx.beginPath()
                ctx.arc(buttonStartX, buttonY, buttonRadius, 0, Math.PI * 2)
                ctx.fill()

                // Yellow button
                ctx.fillStyle = '#febc2e'
                ctx.beginPath()
                ctx.arc(buttonStartX + buttonSpacing, buttonY, buttonRadius, 0, Math.PI * 2)
                ctx.fill()

                // Green button
                ctx.fillStyle = '#28c840'
                ctx.beginPath()
                ctx.arc(buttonStartX + buttonSpacing * 2, buttonY, buttonRadius, 0, Math.PI * 2)
                ctx.fill()

                // Title text (language or filename)
                const langClass = codeEl.className.match(/language-(\w+)/)
                const lang = langClass ? langClass[1].toUpperCase() : 'CODE'
                ctx.fillStyle = '#6b7280'
                ctx.font = `${12 * scale}px -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`
                ctx.textAlign = 'center'
                ctx.fillText(lang, windowX + windowWidth / 2, buttonY + 4 * scale)

                // Draw code with syntax highlighting colors
                ctx.font = `${fontSize}px ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace`
                ctx.textAlign = 'left'
                ctx.textBaseline = 'top'

                const codeStartY = windowY + titleBarHeight + padding
                segments.forEach((line, lineIndex) => {
                    let xOffset = windowX + padding
                    line.forEach(segment => {
                        ctx.fillStyle = segment.color
                        ctx.fillText(segment.text, xOffset, codeStartY + lineIndex * lineHeight)
                        xOffset += ctx.measureText(segment.text).width
                    })
                })

                // Download the image
                const link = document.createElement('a')
                link.download = `code-${Date.now()}.png`
                link.href = canvas.toDataURL('image/png')
                link.click()

                downloadBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-[#04bdaf]"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>`
                setTimeout(() => {
                    downloadBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>`
                }, 2000)
            } catch (error) {
                console.error('Failed to generate image:', error)
                downloadBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-red-500"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>`
                setTimeout(() => {
                    downloadBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>`
                }, 2000)
            }
        })

        buttonContainer.appendChild(copyBtn)
        buttonContainer.appendChild(downloadBtn)
        wrapper.appendChild(buttonContainer)
    })
}

// Social share functions
const getShareUrl = () => {
    return typeof window !== 'undefined' ? window.location.href : ''
}

const shareOnTwitter = () => {
    const url = encodeURIComponent(getShareUrl())
    const text = encodeURIComponent(`${props.content.title} - Laravilt Documentation`)
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400')
}

const shareOnFacebook = () => {
    const url = encodeURIComponent(getShareUrl())
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400')
}

const shareOnLinkedIn = () => {
    const url = encodeURIComponent(getShareUrl())
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400')
}

const shareOnReddit = () => {
    const url = encodeURIComponent(getShareUrl())
    const title = encodeURIComponent(props.content.title)
    window.open(`https://reddit.com/submit?url=${url}&title=${title}`, '_blank', 'width=600,height=400')
}

const copyPageLink = async () => {
    await navigator.clipboard.writeText(getShareUrl())
}

onMounted(() => {
    nextTick(() => {
        document.querySelectorAll('pre code').forEach((el) => {
            hljs.highlightElement(el as HTMLElement)
        })
        addCodeBlockButtons()
    })
})

const isActive = (path: string) => props.currentPage === path

// Icon component mapping for GitBook-style navigation
const iconComponents: Record<string, any> = {
    Rocket,
    LayoutDashboard,
    FileEdit,
    Table,
    List,
    Zap,
    Bell,
    LayoutGrid,
    Shield,
    Bot,
    Layers,
    Monitor,
    Database,
    Puzzle,
    Wrench,
    FileText,
    Server,
    HelpCircle,
    ChevronDown
}

const getIconComponent = (iconName?: string) => {
    if (!iconName) return FileText
    return iconComponents[iconName] || FileText
}
</script>

<template>
    <SeoHead />

    <div v-cloak class="min-h-screen bg-[#0a0a0a] text-white antialiased">
        <!-- Top Navigation -->
        <AppHeader :show-mobile-menu="true" :show-search="true" @toggle-mobile-menu="sidebarOpen = !sidebarOpen" />

        <div class="pt-16">
            <!-- Backdrop -->
            <Transition
                enter-active-class="transition-opacity duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-300"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"></div>
            </Transition>

            <!-- Sidebar -->
            <aside :class="[
                'fixed top-16 bottom-0 left-0 z-50 transform overflow-y-auto border-r border-white/[0.08] bg-[#0a0a0a] transition-all duration-300 w-72',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                'lg:z-30 lg:translate-x-0'
            ]">
                <div class="p-4 pb-24">
                    <!-- Loading skeleton -->
                    <div v-if="!navReady" class="space-y-3 animate-pulse">
                        <div v-for="i in 5" :key="i" class="space-y-2">
                            <div class="h-8 bg-white/5 rounded-lg"></div>
                        </div>
                    </div>

                    <!-- Navigation - Filament/GitBook style -->
                    <nav v-else class="space-y-1">
                        <div v-for="section in navigation" :key="section.title">
                            <!-- Section Header - Accordion toggle -->
                            <button
                                @click="toggleSection(section.title)"
                                class="group flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-medium transition-colors"
                                :class="isSectionExpanded(section.title) ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5'"
                            >
                                <span class="flex items-center gap-2.5">
                                    <component
                                        :is="getIconComponent(section.icon)"
                                        class="size-4 shrink-0 transition-colors"
                                        :class="isSectionExpanded(section.title) ? 'text-[#04bdaf]' : 'text-gray-500 group-hover:text-gray-400'"
                                    />
                                    {{ section.title }}
                                </span>
                                <ChevronDown
                                    class="size-4 shrink-0 text-gray-500 transition-transform duration-200"
                                    :class="isSectionExpanded(section.title) ? 'rotate-180' : ''"
                                />
                            </button>

                            <!-- Section Items - Collapsible with smooth animation -->
                            <div
                                class="overflow-hidden transition-all duration-200 ease-out"
                                :class="isSectionExpanded(section.title) ? 'max-h-[2000px] opacity-100' : 'max-h-0 opacity-0'"
                            >
                                <ul class="mt-1 space-y-0.5 pl-4 ml-2 border-l border-white/10">
                                    <li v-for="item in section.items" :key="item.path || item.title">
                                        <!-- Regular item with path -->
                                        <Link
                                            v-if="item.path"
                                            :href="`/docs/${item.path}`"
                                            @click="sidebarOpen = false"
                                            preserve-scroll
                                            :class="[
                                                'block rounded-md px-3 py-1.5 text-sm transition-colors',
                                                isActive(item.path)
                                                    ? 'bg-[#04bdaf]/10 text-[#04bdaf] font-medium border-l-2 border-[#04bdaf] -ml-[2px] pl-[14px]'
                                                    : 'text-gray-400 hover:bg-white/5 hover:text-white'
                                            ]"
                                        >
                                            {{ item.title }}
                                        </Link>

                                        <!-- Submenu group with children -->
                                        <div v-else-if="item.children">
                                            <button
                                                @click="toggleSubmenu(item.title)"
                                                class="group flex w-full items-center justify-between rounded-md px-3 py-1.5 text-left text-sm transition-colors"
                                                :class="isSubmenuExpanded(item.title) ? 'text-white' : 'text-gray-500 hover:text-white hover:bg-white/5'"
                                            >
                                                <span>{{ item.title }}</span>
                                                <ChevronDown
                                                    class="size-3 shrink-0 text-gray-600 transition-transform duration-200"
                                                    :class="isSubmenuExpanded(item.title) ? 'rotate-180' : ''"
                                                />
                                            </button>
                                            <div
                                                class="overflow-hidden transition-all duration-150 ease-out"
                                                :class="isSubmenuExpanded(item.title) ? 'max-h-[500px] opacity-100' : 'max-h-0 opacity-0'"
                                            >
                                                <ul class="mt-0.5 space-y-0.5 pl-3 ml-2 border-l border-white/5">
                                                    <li v-for="child in item.children" :key="child.path">
                                                        <Link
                                                            :href="`/docs/${child.path}`"
                                                            @click="sidebarOpen = false"
                                                            preserve-scroll
                                                            :class="[
                                                                'block rounded-md px-3 py-1 text-sm transition-colors',
                                                                isActive(child.path)
                                                                    ? 'text-[#04bdaf] font-medium'
                                                                    : 'text-gray-500 hover:text-gray-300 hover:bg-white/5'
                                                            ]"
                                                        >
                                                            {{ child.title }}
                                                        </Link>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="min-h-screen lg:pl-72">
                <div class="mx-auto max-w-7xl px-4 py-8 lg:px-8 lg:py-12">
                    <div class="lg:flex lg:gap-8">
                        <!-- Article Content -->
                        <div class="flex-1 min-w-0 max-w-4xl">
                            <article class="prose prose-invert max-w-none prose-headings:scroll-mt-20 prose-a:text-[#04bdaf] prose-code:rounded prose-code:bg-gray-800 prose-code:px-1 prose-code:py-0.5 prose-code:before:content-none prose-code:after:content-none prose-pre:bg-gray-800" v-html="content?.html"></article>

                            <!-- Footer with Edit Link and Share Buttons -->
                            <div class="mt-12 border-t border-white/10 pt-8">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <!-- Edit Link -->
                                    <a :href="content?.editUrl" target="_blank" class="inline-flex items-center gap-2 text-sm text-gray-500 transition hover:text-[#04bdaf]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Edit this page on GitHub
                                    </a>

                                    <!-- Share Buttons -->
                                    <div class="flex items-center gap-1">
                                        <span class="text-sm text-gray-500 mr-2">Share:</span>
                                        <!-- Twitter/X -->
                                        <button @click="shareOnTwitter" class="p-2 rounded-lg text-gray-400 transition hover:bg-white/10 hover:text-white" title="Share on X (Twitter)">
                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                        </button>
                                        <!-- Facebook -->
                                        <button @click="shareOnFacebook" class="p-2 rounded-lg text-gray-400 transition hover:bg-white/10 hover:text-[#1877F2]" title="Share on Facebook">
                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        </button>
                                        <!-- LinkedIn -->
                                        <button @click="shareOnLinkedIn" class="p-2 rounded-lg text-gray-400 transition hover:bg-white/10 hover:text-[#0A66C2]" title="Share on LinkedIn">
                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                        </button>
                                        <!-- Reddit -->
                                        <button @click="shareOnReddit" class="p-2 rounded-lg text-gray-400 transition hover:bg-white/10 hover:text-[#FF4500]" title="Share on Reddit">
                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 0 1 .042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 0 1 4.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a.342.342 0 0 1 .14-.197.35.35 0 0 1 .238-.042l2.906.617a1.214 1.214 0 0 1 1.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249zm-5.466 3.99a.327.327 0 0 0-.231.094.33.33 0 0 0 0 .463c.842.842 2.484.913 2.961.913.477 0 2.105-.056 2.961-.913a.361.361 0 0 0 .029-.463.33.33 0 0 0-.464 0c-.547.533-1.684.73-2.512.73-.828 0-1.979-.196-2.512-.73a.326.326 0 0 0-.232-.095z"/></svg>
                                        </button>
                                        <!-- Copy Link -->
                                        <button @click="copyPageLink" class="p-2 rounded-lg text-gray-400 transition hover:bg-white/10 hover:text-[#04bdaf]" title="Copy link">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table of Contents - Right Sidebar -->
                        <aside v-if="toc.length > 0" class="hidden lg:block w-56 shrink-0">
                            <div class="sticky top-24">
                                <h4 class="mb-4 text-sm font-semibold text-white">On this page</h4>
                                <nav class="space-y-1">
                                    <button
                                        v-for="item in toc"
                                        :key="item.id"
                                        @click="scrollToHeading(item.id)"
                                        :class="[
                                            'block w-full text-left text-sm transition-colors duration-150 py-1.5 border-l-2 -ml-px',
                                            item.level === 3 ? 'pl-6' : 'pl-4',
                                            activeHeading === item.id
                                                ? 'border-[#04bdaf] text-[#04bdaf]'
                                                : 'border-transparent text-gray-400 hover:text-white hover:border-gray-600'
                                        ]"
                                    >
                                        {{ item.text }}
                                    </button>
                                </nav>
                            </div>
                        </aside>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<style>
[v-cloak] {
    display: none;
}

/* Transparent scrollbar for sidebar */
aside::-webkit-scrollbar {
    width: 6px;
}

aside::-webkit-scrollbar-track {
    background: transparent;
}

aside::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

aside::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}

.prose .code-block-wrapper pre {
    border-radius: 0;
    border: none;
    margin: 0;
    padding: 1rem;
    background: #1e1e2e;
}

.prose .code-block-wrapper pre code {
    font-size: 0.875rem;
    background: transparent;
}

/* Code block wrapper styling to match landing page */
.prose .code-block-wrapper {
    background: #1e1e2e;
    border-color: rgba(255, 255, 255, 0.08);
}

/* Inline code styling */
.prose code:not(pre code) {
    background: rgba(255, 255, 255, 0.08);
    color: #e5e7eb;
}

.heading-permalink {
    margin-right: 0.5rem;
    color: rgb(75 85 99);
    text-decoration: none;
    transition: color 150ms;
}

.prose h2:hover .heading-permalink,
.prose h3:hover .heading-permalink,
.prose h4:hover .heading-permalink {
    color: #04bdaf;
}
</style>
