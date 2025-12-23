<script setup lang="ts">
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
        { threshold: 0.1 }
    )

    if (sectionRef.value) {
        observer.observe(sectionRef.value)
    }
})

const tabs = [
    { id: 'resource', label: 'Resource' },
    { id: 'form', label: 'Form' },
    { id: 'table', label: 'Table' },
    { id: 'infolist', label: 'Infolist' },
    { id: 'api', label: 'API' },
    { id: 'ai', label: 'AI' },
    { id: 'panel', label: 'Panel' },
]

const activeTab = ref('resource')
</script>

<template>
    <section ref="sectionRef" class="relative py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div :class="['text-center mb-16 transition-all duration-700', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Expressive, elegant syntax</h2>
                <p class="mt-4 text-lg text-zinc-400">Define your admin panel with a fluent, chainable API that feels natural.</p>
            </div>

            <!-- Code Window -->
            <div :class="['mx-auto max-w-4xl transition-all duration-700 delay-200', isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
                <div class="rounded-2xl border border-white/10 bg-[#0f0f0f] overflow-hidden">
                    <!-- Tab Bar -->
                    <div class="flex items-center justify-between border-b border-white/5 bg-[#141414] px-4">
                        <div class="flex items-center gap-1 overflow-x-auto scrollbar-hide">
                            <button
                                v-for="tab in tabs"
                                :key="tab.id"
                                @click="activeTab = tab.id"
                                :class="[
                                    'px-3 py-3 text-sm font-medium transition border-b-2 -mb-px whitespace-nowrap',
                                    activeTab === tab.id ? 'text-white border-[#04bdaf]' : 'text-zinc-500 border-transparent hover:text-zinc-300'
                                ]"
                            >
                                {{ tab.label }}
                            </button>
                        </div>
                        <div class="flex items-center gap-2 pl-4">
                            <span class="w-3 h-3 rounded-full bg-[#ff5f57]"></span>
                            <span class="w-3 h-3 rounded-full bg-[#febc2e]"></span>
                            <span class="w-3 h-3 rounded-full bg-[#28c840]"></span>
                        </div>
                    </div>
                    <!-- Code Content -->
                    <div class="p-6 overflow-x-auto">
                        <!-- Resource -->
                        <pre v-if="activeTab === 'resource'" class="text-sm leading-relaxed"><code class="text-zinc-300"><span class="text-zinc-500">namespace</span> <span class="text-[#04bdaf]">App\Laravilt\Admin\Resources\User</span>;

<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Panel\Resources\Resource</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Schemas\Schema</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Tables\Table</span>;

<span class="text-[#822478]">class</span> <span class="text-amber-400">UserResource</span> <span class="text-[#822478]">extends</span> <span class="text-[#04bdaf]">Resource</span>
{
    <span class="text-[#822478]">protected static</span> ?<span class="text-blue-400">string</span> <span class="text-white">$model</span> = User::<span class="text-[#822478]">class</span>;
    <span class="text-[#822478]">protected static</span> ?<span class="text-blue-400">string</span> <span class="text-white">$navigationIcon</span> = <span class="text-emerald-400">'Users'</span>;
    <span class="text-[#822478]">protected static</span> ?<span class="text-blue-400">string</span> <span class="text-white">$navigationGroup</span> = <span class="text-emerald-400">'System'</span>;

    <span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">form</span>(Schema <span class="text-white">$form</span>): Schema { ... }
    <span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">table</span>(Table <span class="text-white">$table</span>): Table { ... }
    <span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">api</span>(ApiResource <span class="text-white">$api</span>): ApiResource { ... }
    <span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">ai</span>(AIAgent <span class="text-white">$agent</span>): AIAgent { ... }
}</code></pre>

                        <!-- Form -->
                        <pre v-else-if="activeTab === 'form'" class="text-sm leading-relaxed"><code class="text-zinc-300"><span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Schemas\Schema</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Schemas\Components\Section</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Forms\Components\TextInput</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Forms\Components\Select</span>;

<span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">form</span>(Schema <span class="text-white">$form</span>): <span class="text-blue-400">Schema</span>
{
    <span class="text-[#822478]">return</span> <span class="text-white">$form</span>-><span class="text-[#04bdaf]">schema</span>([
        Section::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'User Information'</span>)
            -><span class="text-[#04bdaf]">columns</span>(<span class="text-amber-400">2</span>)
            -><span class="text-[#04bdaf]">schema</span>([
                TextInput::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'name'</span>)-><span class="text-[#04bdaf]">required</span>(),
                TextInput::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'email'</span>)
                    -><span class="text-[#04bdaf]">email</span>()-><span class="text-[#04bdaf]">required</span>()
                    -><span class="text-[#04bdaf]">unique</span>(<span class="text-white">ignoreRecord:</span> <span class="text-amber-400">true</span>),
                Select::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'role'</span>)
                    -><span class="text-[#04bdaf]">options</span>([<span class="text-emerald-400">'admin'</span> => <span class="text-emerald-400">'Admin'</span>, <span class="text-emerald-400">'user'</span> => <span class="text-emerald-400">'User'</span>]),
            ]),
    ]);
}</code></pre>

                        <!-- Table -->
                        <pre v-else-if="activeTab === 'table'" class="text-sm leading-relaxed"><code class="text-zinc-300"><span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Tables\Table</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Tables\Columns\TextColumn</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Tables\Filters\SelectFilter</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Actions\{ViewAction, EditAction, DeleteAction}</span>;

<span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">table</span>(Table <span class="text-white">$table</span>): <span class="text-blue-400">Table</span>
{
    <span class="text-[#822478]">return</span> <span class="text-white">$table</span>
        -><span class="text-[#04bdaf]">columns</span>([
            TextColumn::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'name'</span>)-><span class="text-[#04bdaf]">searchable</span>()-><span class="text-[#04bdaf]">sortable</span>(),
            TextColumn::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'email'</span>)-><span class="text-[#04bdaf]">searchable</span>(),
            TextColumn::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'created_at'</span>)-><span class="text-[#04bdaf]">dateTime</span>()-><span class="text-[#04bdaf]">sortable</span>(),
        ])
        -><span class="text-[#04bdaf]">filters</span>([
            SelectFilter::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'role'</span>)
                -><span class="text-[#04bdaf]">options</span>([<span class="text-emerald-400">'admin'</span> => <span class="text-emerald-400">'Admin'</span>, <span class="text-emerald-400">'user'</span> => <span class="text-emerald-400">'User'</span>]),
        ])
        -><span class="text-[#04bdaf]">recordActions</span>([ViewAction::<span class="text-[#04bdaf]">make</span>(), EditAction::<span class="text-[#04bdaf]">make</span>(), DeleteAction::<span class="text-[#04bdaf]">make</span>()]);
}</code></pre>

                        <!-- Infolist -->
                        <pre v-else-if="activeTab === 'infolist'" class="text-sm leading-relaxed"><code class="text-zinc-300"><span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Schemas\Schema</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Schemas\Components\Section</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Infolists\Entries\TextEntry</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Infolists\Entries\ImageEntry</span>;

<span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">infolist</span>(Schema <span class="text-white">$infolist</span>): <span class="text-blue-400">Schema</span>
{
    <span class="text-[#822478]">return</span> <span class="text-white">$infolist</span>-><span class="text-[#04bdaf]">schema</span>([
        Section::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'Profile Information'</span>)
            -><span class="text-[#04bdaf]">columns</span>(<span class="text-amber-400">2</span>)
            -><span class="text-[#04bdaf]">schema</span>([
                ImageEntry::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'avatar'</span>)-><span class="text-[#04bdaf]">circular</span>(),
                TextEntry::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'name'</span>)-><span class="text-[#04bdaf]">label</span>(<span class="text-emerald-400">'Name'</span>),
                TextEntry::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'email'</span>)-><span class="text-[#04bdaf]">label</span>(<span class="text-emerald-400">'Email'</span>),
                TextEntry::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'created_at'</span>)
                    -><span class="text-[#04bdaf]">label</span>(<span class="text-emerald-400">'Member Since'</span>)-><span class="text-[#04bdaf]">dateTime</span>(),
            ]),
    ]);
}</code></pre>

                        <!-- API -->
                        <pre v-else-if="activeTab === 'api'" class="text-sm leading-relaxed"><code class="text-zinc-300"><span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Tables\ApiResource</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Tables\ApiColumn</span>;

<span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">api</span>(ApiResource <span class="text-white">$api</span>): <span class="text-blue-400">ApiResource</span>
{
    <span class="text-[#822478]">return</span> <span class="text-white">$api</span>
        -><span class="text-[#04bdaf]">description</span>(<span class="text-emerald-400">'Categories API - Manage product categories'</span>)
        -><span class="text-[#04bdaf]">authenticated</span>()
        -><span class="text-[#04bdaf]">columns</span>([
            ApiColumn::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'id'</span>)-><span class="text-[#04bdaf]">type</span>(<span class="text-emerald-400">'integer'</span>),
            ApiColumn::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'name'</span>)-><span class="text-[#04bdaf]">type</span>(<span class="text-emerald-400">'string'</span>)-><span class="text-[#04bdaf]">searchable</span>(),
            ApiColumn::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'is_active'</span>)-><span class="text-[#04bdaf]">type</span>(<span class="text-emerald-400">'boolean'</span>)-><span class="text-[#04bdaf]">filterable</span>(),
        ])
        -><span class="text-[#04bdaf]">allowedFilters</span>([<span class="text-emerald-400">'is_active'</span>])
        -><span class="text-[#04bdaf]">allowedSorts</span>([<span class="text-emerald-400">'name'</span>, <span class="text-emerald-400">'created_at'</span>])
        -><span class="text-[#04bdaf]">allowedIncludes</span>([<span class="text-emerald-400">'products'</span>]);
}</code></pre>

                        <!-- AI -->
                        <pre v-else-if="activeTab === 'ai'" class="text-sm leading-relaxed"><code class="text-zinc-300"><span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\AI\AIAgent</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\AI\AIColumn</span>;

<span class="text-[#822478]">public static function</span> <span class="text-[#04bdaf]">ai</span>(AIAgent <span class="text-white">$agent</span>): <span class="text-blue-400">AIAgent</span>
{
    <span class="text-[#822478]">return</span> <span class="text-white">$agent</span>
        -><span class="text-[#04bdaf]">name</span>(<span class="text-emerald-400">'product_assistant'</span>)
        -><span class="text-[#04bdaf]">description</span>(<span class="text-emerald-400">'AI assistant for managing products'</span>)
        -><span class="text-[#04bdaf]">systemPrompt</span>(<span class="text-emerald-400">'You are a helpful product management assistant.'</span>)
        -><span class="text-[#04bdaf]">columns</span>([
            AIColumn::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'name'</span>)-><span class="text-[#04bdaf]">label</span>(<span class="text-emerald-400">'Product Name'</span>)-><span class="text-[#04bdaf]">searchable</span>(),
            AIColumn::<span class="text-[#04bdaf]">make</span>(<span class="text-emerald-400">'price'</span>)-><span class="text-[#04bdaf]">type</span>(<span class="text-emerald-400">'decimal'</span>)-><span class="text-[#04bdaf]">filterable</span>(),
        ])
        -><span class="text-[#04bdaf]">canCreate</span>(<span class="text-amber-400">true</span>)
        -><span class="text-[#04bdaf]">canUpdate</span>(<span class="text-amber-400">true</span>)
        -><span class="text-[#04bdaf]">canQuery</span>(<span class="text-amber-400">true</span>);
}</code></pre>

                        <!-- Panel Provider -->
                        <pre v-else-if="activeTab === 'panel'" class="text-sm leading-relaxed"><code class="text-zinc-300"><span class="text-zinc-500">namespace</span> <span class="text-[#04bdaf]">App\Laravilt\Admin</span>;

<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Panel\Panel</span>;
<span class="text-zinc-500">use</span> <span class="text-[#04bdaf]">Laravilt\Panel\PanelProvider</span>;

<span class="text-[#822478]">class</span> <span class="text-amber-400">AdminPanelProvider</span> <span class="text-[#822478]">extends</span> <span class="text-[#04bdaf]">PanelProvider</span>
{
    <span class="text-[#822478]">public function</span> <span class="text-[#04bdaf]">panel</span>(Panel <span class="text-white">$panel</span>): <span class="text-blue-400">Panel</span>
    {
        <span class="text-[#822478]">return</span> <span class="text-white">$panel</span>
            -><span class="text-[#04bdaf]">id</span>(<span class="text-emerald-400">'admin'</span>)
            -><span class="text-[#04bdaf]">path</span>(<span class="text-emerald-400">'admin'</span>)
            -><span class="text-[#04bdaf]">login</span>()
            -><span class="text-[#04bdaf]">registration</span>()
            -><span class="text-[#04bdaf]">colors</span>([<span class="text-emerald-400">'primary'</span> => <span class="text-emerald-400">'#3b82f6'</span>])
            -><span class="text-[#04bdaf]">brandName</span>(<span class="text-emerald-400">'My Admin'</span>)
            -><span class="text-[#04bdaf]">discoverResources</span>(<span class="text-white">in:</span> <span class="text-[#04bdaf]">app_path</span>(<span class="text-emerald-400">'Laravilt/Admin/Resources'</span>))
            -><span class="text-[#04bdaf]">discoverPages</span>(<span class="text-white">in:</span> <span class="text-[#04bdaf]">app_path</span>(<span class="text-emerald-400">'Laravilt/Admin/Pages'</span>));
    }
}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
