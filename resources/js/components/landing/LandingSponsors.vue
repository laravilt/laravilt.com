<script setup lang="ts">
</script>

<template>
    <section class="relative py-24 border-t border-white/5">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Become a Sponsor</h2>
                <p class="mt-4 text-lg text-zinc-400 max-w-2xl mx-auto">
                    Support Laravilt development and get your brand in front of thousands of developers.
                </p>

                <!-- Sponsor Tiers -->
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 max-w-4xl mx-auto">
                    <!-- Gold Sponsor -->
                    <div class="relative rounded-2xl border border-amber-500/20 bg-gradient-to-b from-amber-500/10 to-transparent p-8 transition hover:border-amber-500/40">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 px-4 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs font-semibold">
                            GOLD
                        </div>
                        <div class="mt-4">
                            <div class="text-4xl font-bold text-amber-400">$500</div>
                            <div class="text-sm text-zinc-500 mt-1">/month</div>
                        </div>
                        <ul class="mt-6 space-y-3 text-left">
                            <li class="flex items-center gap-2 text-sm text-zinc-400">
                                <svg class="size-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Large logo on homepage
                            </li>
                            <li class="flex items-center gap-2 text-sm text-zinc-400">
                                <svg class="size-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Logo in documentation
                            </li>
                            <li class="flex items-center gap-2 text-sm text-zinc-400">
                                <svg class="size-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Logo in README
                            </li>
                        </ul>
                    </div>

                    <!-- Silver Sponsor -->
                    <div class="relative rounded-2xl border border-zinc-500/20 bg-gradient-to-b from-zinc-500/10 to-transparent p-8 transition hover:border-zinc-500/40">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 px-4 py-1 rounded-full bg-zinc-500/20 text-zinc-400 text-xs font-semibold">
                            SILVER
                        </div>
                        <div class="mt-4">
                            <div class="text-4xl font-bold text-zinc-400">$200</div>
                            <div class="text-sm text-zinc-500 mt-1">/month</div>
                        </div>
                        <ul class="mt-6 space-y-3 text-left">
                            <li class="flex items-center gap-2 text-sm text-zinc-400">
                                <svg class="size-4 text-zinc-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Medium logo on homepage
                            </li>
                            <li class="flex items-center gap-2 text-sm text-zinc-400">
                                <svg class="size-4 text-zinc-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Logo in README
                            </li>
                        </ul>
                    </div>

                    <!-- Bronze Sponsor -->
                    <div class="relative rounded-2xl border border-orange-700/20 bg-gradient-to-b from-orange-700/10 to-transparent p-8 transition hover:border-orange-700/40">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 px-4 py-1 rounded-full bg-orange-700/20 text-orange-500 text-xs font-semibold">
                            BRONZE
                        </div>
                        <div class="mt-4">
                            <div class="text-4xl font-bold text-orange-500">$50</div>
                            <div class="text-sm text-zinc-500 mt-1">/month</div>
                        </div>
                        <ul class="mt-6 space-y-3 text-left">
                            <li class="flex items-center gap-2 text-sm text-zinc-400">
                                <svg class="size-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Small logo on homepage
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- CTA -->
                <div class="mt-12">
                    <a
                        href="https://github.com/sponsors/fadymondy"
                        target="_blank"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#04bdaf] to-[#822478] px-8 py-4 text-base font-semibold text-white transition hover:opacity-90"
                    >
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                        Sponsor on GitHub
                    </a>
                    <p class="mt-4 text-sm text-zinc-500">
                        Support open source development via <a href="https://github.com/sponsors/fadymondy" target="_blank" class="text-[#04bdaf] hover:underline">GitHub Sponsors</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
