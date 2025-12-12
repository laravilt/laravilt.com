<script setup lang="ts">
import { Link, useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import SeoHead from '@/components/SeoHead.vue'

const showRegisterForm = ref(false)

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post('/demo/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}

const startInstantDemo = () => {
    router.post('/demo/instant')
}
</script>

<template>
    <SeoHead
        title="Try Demo"
        description="Try Laravilt for free! Experience our modern admin panel framework with full features including forms, tables, widgets, and AI integration."
        keywords="laravilt demo, try laravilt, admin panel demo, laravel admin demo, free demo"
        url="/demo"
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
                    <Link href="/admin/login" class="text-sm text-gray-400 transition hover:text-white">Login</Link>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <div class="flex min-h-screen items-center pt-16">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
                    <!-- Left Side - Info -->
                    <div class="flex flex-col justify-center">
                        <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">
                            Try <span class="bg-gradient-to-r from-[#04bdaf] to-[#822478] bg-clip-text text-transparent">Laravilt</span> Demo
                        </h1>
                        <p class="mt-6 text-lg text-gray-400">
                            Experience the full power of Laravilt admin panel. Create, edit, and manage resources with our beautiful UI components.
                        </p>

                        <div class="mt-8 space-y-4">
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 size-5 shrink-0 text-[#04bdaf]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <div>
                                    <div class="font-medium">Full-featured Tables</div>
                                    <div class="text-sm text-gray-500">Search, sort, filter, and bulk actions</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 size-5 shrink-0 text-[#04bdaf]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <div>
                                    <div class="font-medium">Dynamic Forms</div>
                                    <div class="text-sm text-gray-500">Rich form builder with validation</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 size-5 shrink-0 text-[#04bdaf]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <div>
                                    <div class="font-medium">AI Integration</div>
                                    <div class="text-sm text-gray-500">Built-in AI assistant and chat</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 size-5 shrink-0 text-[#04bdaf]" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <div>
                                    <div class="font-medium">API Documentation</div>
                                    <div class="text-sm text-gray-500">Auto-generated REST API with tester</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 rounded-xl border border-yellow-500/20 bg-yellow-500/10 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="mt-0.5 size-5 shrink-0 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                                <div class="text-sm text-yellow-200">
                                    <strong>Demo Mode:</strong> Your data will be automatically cleared after your session expires. Instant demo lasts 2 hours, registered demo lasts 24 hours.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Actions -->
                    <div class="flex flex-col justify-center">
                        <div class="rounded-2xl border border-white/10 bg-gray-800/50 p-8">
                            <div v-if="!showRegisterForm">
                                <h2 class="text-2xl font-bold">Start Your Demo</h2>
                                <p class="mt-2 text-gray-400">Choose how you want to try Laravilt</p>

                                <div class="mt-8 space-y-4">
                                    <button
                                        @click="startInstantDemo"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#04bdaf] to-[#822478] px-6 py-4 font-semibold text-white transition hover:opacity-90"
                                    >
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                        </svg>
                                        Start Instant Demo
                                    </button>
                                    <p class="text-center text-sm text-gray-500">No registration required - 2 hour session</p>

                                    <div class="relative my-6">
                                        <div class="absolute inset-0 flex items-center">
                                            <div class="w-full border-t border-white/10"></div>
                                        </div>
                                        <div class="relative flex justify-center text-sm">
                                            <span class="bg-gray-800/50 px-4 text-gray-500">or</span>
                                        </div>
                                    </div>

                                    <button
                                        @click="showRegisterForm = true"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-6 py-4 font-semibold transition hover:bg-white/10"
                                    >
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                        </svg>
                                        Register with Email
                                    </button>
                                    <p class="text-center text-sm text-gray-500">24 hour session with saved progress</p>
                                </div>
                            </div>

                            <!-- Register Form -->
                            <div v-else>
                                <button @click="showRegisterForm = false" class="mb-4 flex items-center gap-2 text-sm text-gray-400 transition hover:text-white">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                    </svg>
                                    Back
                                </button>

                                <h2 class="text-2xl font-bold">Create Demo Account</h2>
                                <p class="mt-2 text-gray-400">Register to get a 24-hour demo session</p>

                                <form @submit.prevent="submit" class="mt-6 space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            required
                                            class="mt-1 block w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-gray-500 focus:border-[#04bdaf] focus:outline-none focus:ring-1 focus:ring-[#04bdaf]"
                                            placeholder="Your name"
                                        />
                                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            required
                                            class="mt-1 block w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-gray-500 focus:border-[#04bdaf] focus:outline-none focus:ring-1 focus:ring-[#04bdaf]"
                                            placeholder="you@example.com"
                                        />
                                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                                        <input
                                            id="password"
                                            v-model="form.password"
                                            type="password"
                                            required
                                            class="mt-1 block w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-gray-500 focus:border-[#04bdaf] focus:outline-none focus:ring-1 focus:ring-[#04bdaf]"
                                            placeholder="Min 8 characters"
                                        />
                                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-400">{{ form.errors.password }}</p>
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirm Password</label>
                                        <input
                                            id="password_confirmation"
                                            v-model="form.password_confirmation"
                                            type="password"
                                            required
                                            class="mt-1 block w-full rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-gray-500 focus:border-[#04bdaf] focus:outline-none focus:ring-1 focus:ring-[#04bdaf]"
                                            placeholder="Confirm password"
                                        />
                                    </div>

                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#04bdaf] to-[#822478] px-6 py-4 font-semibold text-white transition hover:opacity-90 disabled:opacity-50"
                                    >
                                        <svg v-if="form.processing" class="size-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ form.processing ? 'Creating...' : 'Start Demo' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
