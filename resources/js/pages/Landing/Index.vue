<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, onMounted, computed } from 'vue'
import SeoHead from '@/components/SeoHead.vue'
import CodeBlock from '@/components/CodeBlock.vue'

interface Stat {
    label: string
    value: string
}

interface Feature {
    icon: string
    title: string
    description: string
}

interface Package {
    name: string
    description: string
}

const props = withDefaults(defineProps<{
    stats?: Stat[]
    features?: Feature[]
    packages?: Package[]
}>(), {
    stats: () => [],
    features: () => [],
    packages: () => [],
})

// Core features data based on actual API - Correct Laravilt style
const coreFeatures = [
    {
        id: 'resource',
        icon: 'resource',
        title: 'Resource Classes',
        subtitle: 'Filament v4 Style',
        description: 'Define your admin panel resources with modular architecture. Form, Table, AI, and API configurations in separate classes.',
        highlights: ['Modular design', 'Full CRUD', 'Relation managers', 'API & AI support'],
        code: `class ProductResource extends Resource
{
    protected static string $model = Product::class;
    protected static ?string $navigationIcon = 'Package';
    protected static bool $hasApi = true;

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductTable::configure($table);
    }

    public static function ai(AIAgent $ai): AIAgent
    {
        return ProductAi::configure($ai);
    }

    public static function api(ApiResource $api): ApiResource
    {
        return ProductApi::configure($api);
    }

    public static function getPages(): array
    {
        return [
            'list' => ListProduct::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}`,
    },
    {
        id: 'forms',
        icon: 'forms',
        title: 'Schema Builder',
        subtitle: '30+ Field Types',
        description: 'Build complex forms with TextInput, Select, RichEditor, DatePicker, FileUpload, and more. Full validation support with conditional logic.',
        highlights: ['30+ field types', 'Laravel validation', 'Conditional logic', 'Repeater fields'],
        code: `class ProductForm
{
    public static function configure(Schema $form): Schema
    {
        return $form->schema([
            Section::make('Basic Info')
                ->icon('Info')
                ->collapsible()
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload(),
                    MarkdownEditor::make('description')
                        ->columnSpanFull(),
                ]),
            Section::make('Pricing')
                ->icon('DollarSign')
                ->schema([
                    TextInput::make('price')
                        ->numeric()
                        ->prefix('$'),
                    Toggle::make('is_active')
                        ->default(true),
                ]),
        ]);
    }
}`,
    },
    {
        id: 'tables',
        icon: 'tables',
        title: 'Advanced Tables',
        subtitle: 'Sort, Filter, Search',
        description: 'Feature-rich data tables with sortable columns, searchable data, filters, bulk actions, row actions, and multiple view modes.',
        highlights: ['Sortable columns', 'Advanced filters', 'Bulk actions', 'Grid/List views'],
        code: `class ProductTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->card(fn (Card $card) => $card
                ->heading('Product Catalog')
                ->description('Manage your products')
                ->icon('Package')
            )
            ->columns([
                ImageColumn::make('image')->circular(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                ToggleColumn::make('is_active'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name'),
                TernaryFilter::make('is_active'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}`,
    },
    {
        id: 'widgets',
        icon: 'widgets',
        title: 'Dashboard Widgets',
        subtitle: 'Stats & Charts',
        description: 'Create informative dashboards with stats widgets, charts (line, bar, pie, area), compact tables, and custom widgets.',
        highlights: ['Stats overview', 'Interactive charts', 'Real-time data', 'Custom widgets'],
        code: `class StatsOverview extends Widget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->icon('package')
                ->color('primary'),
            Stat::make('Revenue', '$' . Order::sum('total'))
                ->icon('currency-dollar')
                ->trend('+12%')
                ->color('success'),
            Stat::make('New Customers', Customer::thisMonth())
                ->icon('users')
                ->description('This month')
                ->color('info'),
        ];
    }
}`,
    },
    {
        id: 'auth',
        icon: 'auth',
        title: 'Complete Auth',
        subtitle: 'Panel Provider',
        description: 'Full authentication configured in your panel provider. Social login, two-factor auth, passkeys, magic links, and session management.',
        highlights: ['Social login', 'Two-factor auth', 'Passkeys/WebAuthn', 'Magic links'],
        code: `class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->path('admin')
            ->default()
            ->brandName("Laravilt")
            ->brandLogo(asset('images/icon.svg'))
            ->discoverAutomatically()
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->otp()
            ->profile()
            ->socialLogin(function (SocialProviderBuilder $b) {
                $b->provider(GoogleProvider::class);
                $b->provider(GitHubProvider::class);
                $b->provider(FacebookProvider::class);
            })
            ->twoFactor(function (TwoFactorProviderBuilder $b) {
                $b->provider(TotpDriver::class);
                $b->provider(EmailDriver::class);
            })
            ->sessionManagement()
            ->apiTokens()
            ->passkeys()
            ->magicLinks()
            ->connectedAccounts();
    }
}`,
    },
    {
        id: 'ai',
        icon: 'ai',
        title: 'AI Integration',
        subtitle: 'AI-First Dashboard',
        description: 'Built for AI agents. Global search with AI, chat interface, resource mentions, AI queries, and full MCP support.',
        highlights: ['Global Search AI', 'AI Chat', '@mention Resources', 'MCP Protocol'],
        code: `class ProductAi
{
    public static function configure(AIAgent $ai): AIAgent
    {
        return $ai
            ->name('Products')
            ->columns([
                AIColumn::make('name')
                    ->searchable()
                    ->sortable(),
                AIColumn::make('sku')
                    ->searchable(),
                AIColumn::make('price')
                    ->sortable(),
                AIColumn::make('category.name')
                    ->searchable()
                    ->label('Category'),
                AIColumn::make('is_active')
                    ->boolean(),
            ])
            ->tools([
                UpdatePriceTool::make(),
                BulkImportTool::make(),
            ])
            ->canCreate()
            ->canUpdate()
            ->canDelete()
            ->canQuery();
    }
}`,
    },
]

// Modular packages with GitHub links
const modularPackages = [
    { name: 'laravilt', desc: 'Core framework', github: 'https://github.com/laravilt/laravilt' },
    { name: 'panel', desc: 'Admin panel builder', github: 'https://github.com/laravilt/panel' },
    { name: 'forms', desc: 'Form builder', github: 'https://github.com/laravilt/forms' },
    { name: 'tables', desc: 'Table builder', github: 'https://github.com/laravilt/tables' },
    { name: 'actions', desc: 'Actions & bulk actions', github: 'https://github.com/laravilt/actions' },
    { name: 'widgets', desc: 'Dashboard widgets', github: 'https://github.com/laravilt/widgets' },
    { name: 'auth', desc: 'Authentication system', github: 'https://github.com/laravilt/auth' },
    { name: 'ai', desc: 'AI integration', github: 'https://github.com/laravilt/ai' },
    { name: 'notifications', desc: 'Toast notifications', github: 'https://github.com/laravilt/notifications' },
    { name: 'infolists', desc: 'Info display lists', github: 'https://github.com/laravilt/infolists' },
    { name: 'schemas', desc: 'Schema builder', github: 'https://github.com/laravilt/schemas' },
    { name: 'support', desc: 'Core utilities', github: 'https://github.com/laravilt/support' },
]

const activeFeature = ref(coreFeatures[0])

// Screenshots gallery data
const screenshots = [
    { src: '/screenshots/01-products-table-view.png', title: 'Products Table View', category: 'Tables' },
    { src: '/screenshots/02-products-create-form.png', title: 'Create Form', category: 'Forms' },
    { src: '/screenshots/06-products-grid-view.png', title: 'Grid View', category: 'Tables' },
    { src: '/screenshots/14-dashboard-widgets.png', title: 'Dashboard Widgets', category: 'Dashboard' },
    { src: '/screenshots/17-global-search.png', title: 'Global Search', category: 'Features' },
    { src: '/screenshots/20-ai-chat-empty.png', title: 'AI Chat', category: 'AI' },
    { src: '/screenshots/22-ai-chat-conversation.png', title: 'AI Conversation', category: 'AI' },
    { src: '/screenshots/39-auth-login.png', title: 'Login Page', category: 'Auth' },
    { src: '/screenshots/23-settings-profile.png', title: 'Profile Settings', category: 'Settings' },
    { src: '/screenshots/25-settings-2fa-active.png', title: 'Two-Factor Auth', category: 'Security' },
    { src: '/screenshots/08-api-products-list-endpoint.png', title: 'API Documentation', category: 'API' },
    { src: '/screenshots/16-dashboard-light-mode.png', title: 'Light Mode', category: 'Themes' },
]

const selectedScreenshot = ref<typeof screenshots[0] | null>(null)

const openLightbox = (screenshot: typeof screenshots[0]) => {
    selectedScreenshot.value = screenshot
}

const closeLightbox = () => {
    selectedScreenshot.value = null
}

// Icon SVGs
const iconMap: Record<string, string> = {
    resource: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>`,
    forms: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>`,
    tables: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" /></svg>`,
    widgets: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" /></svg>`,
    typescript: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" /></svg>`,
    ai: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" /></svg>`,
    auth: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>`,
    vue: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" /></svg>`,
}

// Documentation sections for quick access
const docSections = [
    { title: 'Installation', href: '/docs/getting-started/installation', desc: 'Get up and running in minutes' },
    { title: 'Schema Builder', href: '/docs/schemas', desc: '30+ field types with validation' },
    { title: 'Tables', href: '/docs/tables', desc: 'Sorting, filtering, and actions' },
    { title: 'Panel', href: '/docs/panel', desc: 'Configure your admin panel' },
    { title: 'Authentication', href: '/docs/auth', desc: 'Social login, 2FA, passkeys' },
    { title: 'AI Integration', href: '/docs/ai', desc: 'AIAgent & global search' },
    { title: 'Frontend', href: '/docs/frontend', desc: 'Vue components & utilities' },
    { title: 'Brand', href: '/brand', desc: 'Logos and brand assets' },
]

// JSON-LD Structured Data
const jsonLd = {
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "Laravilt",
    "applicationCategory": "DeveloperApplication",
    "operatingSystem": "Web",
    "description": "Modern admin panel framework for Laravel and Vue.js. Build beautiful, type-safe admin panels with forms, tables, and widgets.",
    "url": "https://laravilt.com",
    "author": {
        "@type": "Organization",
        "name": "Laravilt",
        "url": "https://laravilt.com"
    },
    "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
    },
    "featureList": [
        "30+ form field types with validation",
        "Advanced data tables with filters",
        "Dashboard widgets and charts",
        "AI integration with chat and smart fields",
        "Complete authentication with 2FA and passkeys",
        "Vue 3 + TypeScript + Inertia.js"
    ]
}

onMounted(() => {
    // Inject JSON-LD
    const script = document.createElement('script')
    script.type = 'application/ld+json'
    script.textContent = JSON.stringify(jsonLd)
    document.head.appendChild(script)
})
</script>

<template>
    <SeoHead
        title="Home"
        description="Laravilt is a modern admin panel framework for Laravel and Vue.js. Build beautiful, type-safe admin panels with forms, tables, widgets, and AI integration."
        keywords="laravel admin panel, vue admin, crud generator, laravel forms, laravel tables, typescript admin, inertia admin panel, laravel vue dashboard"
        url="/"
    />

    <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-900 to-black text-white">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 border-b border-white/10 bg-gray-900/80 backdrop-blur-xl">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-8">
                    <Link href="/" class="flex items-center gap-3 text-xl font-bold">
                        <svg class="h-8 w-8" viewBox="0 0 263 306.97" xmlns="http://www.w3.org/2000/svg">
                            <polygon fill="#04bdaf" points="223 18.65 148 53.63 95 78.34 95 161.09 148 136.38 148 306.84 223 271.87 223 101.4 263 82.75 263 0 223 18.65"/>
                            <polygon fill="#822478" points="75 189.76 75 35 0 0 0 146.46 0 217.47 0 233.07 75 276.37 128 306.97 128 220.36 75 189.76"/>
                        </svg>
                        <span>Laravilt</span>
                    </Link>
                    <div class="hidden items-center gap-6 md:flex">
                        <Link href="/docs" class="text-sm text-gray-400 transition hover:text-white">Documentation</Link>
                        <a href="https://github.com/laravilt/laravilt" target="_blank" class="text-sm text-gray-400 transition hover:text-white">GitHub</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <Link href="/admin" class="hidden text-sm text-gray-400 transition hover:text-white sm:inline-flex">Demo</Link>
                    <Link href="/docs/getting-started/installation" class="rounded-lg bg-gradient-to-r from-[#04bdaf] to-[#822478] px-4 py-2 text-sm font-medium text-white transition hover:opacity-90">
                        Get Started
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-32 pb-20">
            <!-- Animated background -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-[#04bdaf]/20 via-transparent to-transparent"></div>
                <div class="absolute top-1/4 -left-1/4 h-96 w-96 rounded-full bg-[#04bdaf]/10 blur-3xl"></div>
                <div class="absolute top-1/3 -right-1/4 h-96 w-96 rounded-full bg-[#822478]/10 blur-3xl"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
                <div class="mb-6 flex flex-wrap items-center justify-center gap-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-[#04bdaf]/30 bg-[#04bdaf]/10 px-4 py-2 text-sm text-[#04bdaf]">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#04bdaf] opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-[#04bdaf]"></span>
                        </span>
                        v1.0 Now Available
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-[#822478]/30 bg-[#822478]/10 px-4 py-2 text-sm text-[#822478]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                        </svg>
                        AI-First Dashboard
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 text-sm text-gray-300">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                        MCP Protocol Support
                    </div>
                </div>

                <h1 class="mb-6 text-5xl font-bold tracking-tight sm:text-6xl lg:text-7xl">
                    The Modern <span class="bg-gradient-to-r from-[#04bdaf] to-[#822478] bg-clip-text text-transparent">Admin Panel</span>
                    <br />for Laravel + Vue
                </h1>

                <p class="mx-auto mb-10 max-w-3xl text-lg text-gray-400 sm:text-xl">
                    Build beautiful, type-safe admin panels with 30+ form fields, advanced tables, dashboard widgets, AI integration, and complete authentication.
                    <span class="text-[#04bdaf]">Inspired by Filament, powered by Vue 3.</span>
                </p>

                <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <Link href="/docs/getting-started/installation" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#04bdaf] to-[#822478] px-8 py-4 text-lg font-semibold text-white transition hover:opacity-90 hover:scale-105">
                        Get Started
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </Link>
                    <Link href="/admin" class="inline-flex items-center gap-2 rounded-xl border border-[#04bdaf]/50 bg-[#04bdaf]/10 px-8 py-4 text-lg font-semibold text-[#04bdaf] transition hover:bg-[#04bdaf]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                        </svg>
                        Live Demo
                    </Link>
                    <a href="https://github.com/laravilt/laravilt" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-8 py-4 text-lg font-semibold transition hover:bg-white/10">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                        GitHub
                    </a>
                </div>

                <!-- Quick Install -->
                <div class="mt-12 inline-flex items-center gap-3 rounded-xl border border-white/10 bg-gray-800/50 px-6 py-3 font-mono text-sm">
                    <span class="text-gray-500">$</span>
                    <span>composer require laravilt/laravilt</span>
                    <button class="ml-2 rounded bg-white/10 p-1 transition hover:bg-white/20" @click="navigator.clipboard.writeText('composer require laravilt/laravilt')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                        </svg>
                    </button>
                </div>

                <!-- Stats -->
                <div v-if="stats.length > 0" class="mt-16 grid grid-cols-2 gap-8 sm:grid-cols-4">
                    <div v-for="stat in stats" :key="stat.label" class="text-center">
                        <div class="text-3xl font-bold text-[#04bdaf] sm:text-4xl">{{ stat.value }}</div>
                        <div class="mt-1 text-sm text-gray-500">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Interactive Features Section -->
        <section class="relative py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-12 text-center">
                    <span class="mb-4 inline-block rounded-full bg-[#04bdaf]/10 px-4 py-1 text-sm text-[#04bdaf]">Core Features</span>
                    <h2 class="mb-4 text-3xl font-bold sm:text-4xl">Everything You Need to Build</h2>
                    <p class="mx-auto max-w-2xl text-lg text-gray-400">A complete toolkit for building modern admin panels, backed by comprehensive documentation.</p>
                </div>

                <!-- Feature Tabs -->
                <div class="mb-8 flex flex-wrap justify-center gap-2">
                    <button
                        v-for="feature in coreFeatures"
                        :key="feature.id"
                        class="flex items-center gap-2 rounded-xl border px-4 py-3 transition"
                        :class="activeFeature.id === feature.id
                            ? 'border-[#04bdaf] bg-[#04bdaf]/10 text-[#04bdaf]'
                            : 'border-white/10 bg-gray-800/30 text-gray-400 hover:border-white/20 hover:bg-gray-800/50 hover:text-white'"
                        @click="activeFeature = feature"
                    >
                        <div class="size-5" v-html="iconMap[feature.icon]"></div>
                        <span class="font-medium">{{ feature.title }}</span>
                    </button>
                </div>

                <!-- Active Feature Content -->
                <div class="rounded-2xl border border-white/10 bg-gray-800/20 p-6 lg:p-8">
                    <!-- Feature Header -->
                    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="rounded-lg bg-[#04bdaf]/20 p-2 text-[#04bdaf]" v-html="iconMap[activeFeature.icon]"></div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ activeFeature.title }}</h3>
                                    <span class="text-sm text-gray-500">{{ activeFeature.subtitle }}</span>
                                </div>
                            </div>
                            <p class="text-gray-400 max-w-2xl">{{ activeFeature.description }}</p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <Link :href="`/docs/${activeFeature.id === 'resource' ? 'panel/resources' : activeFeature.id}`" class="inline-flex items-center gap-2 rounded-lg border border-[#04bdaf]/50 bg-[#04bdaf]/10 px-4 py-2 text-sm text-[#04bdaf] transition hover:bg-[#04bdaf]/20">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                </svg>
                                Read Docs
                            </Link>
                            <Link href="/admin" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-[#04bdaf] to-[#822478] px-4 py-2 text-sm font-medium text-white transition hover:opacity-90">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                </svg>
                                Try Demo
                            </Link>
                        </div>
                    </div>

                    <!-- Feature Highlights -->
                    <div class="mb-6 flex flex-wrap gap-2">
                        <span
                            v-for="highlight in activeFeature.highlights"
                            :key="highlight"
                            class="rounded-full border border-[#04bdaf]/30 bg-[#04bdaf]/10 px-3 py-1 text-xs text-[#04bdaf]"
                        >
                            {{ highlight }}
                        </span>
                    </div>

                    <!-- Screenshot Preview (Full Width) -->
                    <div class="relative overflow-hidden rounded-xl border border-white/10 bg-gray-900 mb-6">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10 pointer-events-none"></div>
                        <img
                            :src="activeFeature.id === 'resource' ? '/screenshots/01-products-table-view.png' :
                                  activeFeature.id === 'forms' ? '/screenshots/02-products-create-form.png' :
                                  activeFeature.id === 'tables' ? '/screenshots/06-products-grid-view.png' :
                                  activeFeature.id === 'widgets' ? '/screenshots/14-dashboard-widgets.png' :
                                  activeFeature.id === 'auth' ? '/screenshots/39-auth-login.png' :
                                  activeFeature.id === 'ai' ? '/screenshots/17-global-search.png' :
                                  '/screenshots/14-dashboard-widgets.png'"
                            :alt="activeFeature.title + ' preview'"
                            class="w-full max-h-[500px] object-cover object-top"
                            loading="lazy"
                        />
                        <div class="absolute bottom-4 left-4 right-4 z-20 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="rounded-full bg-green-500/20 border border-green-500/50 px-3 py-1 text-xs text-green-400 flex items-center gap-1.5">
                                    <span class="relative flex h-2 w-2">
                                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                        <span class="relative inline-flex h-2 w-2 rounded-full bg-green-400"></span>
                                    </span>
                                    Live Preview
                                </span>
                                <span class="rounded-full bg-black/50 backdrop-blur-sm px-3 py-1 text-xs text-white">
                                    {{ activeFeature.title }}
                                </span>
                            </div>
                            <Link href="/admin" class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-sm px-4 py-1.5 text-sm text-white hover:bg-white/20 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                                Open Demo
                            </Link>
                        </div>
                    </div>

                    <!-- Code Block (Full Width Below) -->
                    <CodeBlock
                        :code="activeFeature.code"
                        language="php"
                        :filename="activeFeature.title + '.php'"
                    />
                </div>
            </div>
        </section>

        <!-- Screenshots Gallery -->
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-16 text-center">
                    <span class="mb-4 inline-block rounded-full bg-[#822478]/10 px-4 py-1 text-sm text-[#822478]">Screenshots</span>
                    <h2 class="mb-4 text-3xl font-bold sm:text-4xl">See It In Action</h2>
                    <p class="text-lg text-gray-400">Beautiful interfaces for every use case</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div
                        v-for="screenshot in screenshots"
                        :key="screenshot.src"
                        class="group relative cursor-pointer overflow-hidden rounded-xl border border-white/10 bg-gray-800/30 transition hover:border-[#04bdaf]/50"
                        @click="openLightbox(screenshot)"
                    >
                        <div class="aspect-video overflow-hidden">
                            <img
                                :src="screenshot.src"
                                :alt="screenshot.title"
                                class="h-full w-full object-cover object-top transition duration-300 group-hover:scale-105"
                                loading="lazy"
                            />
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 transition group-hover:opacity-100">
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <span class="mb-1 inline-block rounded-full bg-[#04bdaf]/20 px-2 py-0.5 text-xs text-[#04bdaf]">{{ screenshot.category }}</span>
                                <h3 class="text-sm font-semibold text-white">{{ screenshot.title }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Lightbox Modal -->
        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="selectedScreenshot"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
                    @click.self="closeLightbox"
                >
                    <button
                        class="absolute top-4 right-4 rounded-full bg-white/10 p-2 text-white transition hover:bg-white/20"
                        @click="closeLightbox"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="max-h-[90vh] max-w-[90vw] overflow-auto rounded-xl">
                        <img
                            :src="selectedScreenshot.src"
                            :alt="selectedScreenshot.title"
                            class="h-auto w-full rounded-xl"
                        />
                        <div class="mt-4 text-center">
                            <span class="mr-2 inline-block rounded-full bg-[#04bdaf]/20 px-3 py-1 text-sm text-[#04bdaf]">{{ selectedScreenshot.category }}</span>
                            <span class="text-lg font-semibold text-white">{{ selectedScreenshot.title }}</span>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Documentation Quick Links -->
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-16 text-center">
                    <span class="mb-4 inline-block rounded-full bg-[#04bdaf]/10 px-4 py-1 text-sm text-[#04bdaf]">Documentation</span>
                    <h2 class="mb-4 text-3xl font-bold sm:text-4xl">Comprehensive Docs</h2>
                    <p class="text-lg text-gray-400">Everything you need to get started and master Laravilt</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <Link
                        v-for="section in docSections"
                        :key="section.href"
                        :href="section.href"
                        class="group rounded-xl border border-white/10 bg-gray-800/30 p-6 transition hover:border-[#04bdaf]/50 hover:bg-gray-800/50"
                    >
                        <h3 class="mb-2 font-semibold text-white group-hover:text-[#04bdaf]">{{ section.title }}</h3>
                        <p class="text-sm text-gray-500">{{ section.desc }}</p>
                        <div class="mt-4 flex items-center text-sm text-[#04bdaf] opacity-0 transition group-hover:opacity-100">
                            Read more
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="ml-1 size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Modular Packages - Links to GitHub -->
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-16 text-center">
                    <span class="mb-4 inline-block rounded-full bg-[#822478]/10 px-4 py-1 text-sm text-[#822478]">Packages</span>
                    <h2 class="mb-4 text-3xl font-bold sm:text-4xl">Modular Architecture</h2>
                    <p class="mx-auto max-w-2xl text-lg text-gray-400">Use what you need, leave what you don't. Each package is independently versioned and can be used standalone.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <a
                        v-for="pkg in modularPackages"
                        :key="pkg.name"
                        :href="pkg.github"
                        target="_blank"
                        class="group flex items-center gap-4 rounded-xl border border-white/10 bg-gray-800/30 p-4 transition hover:border-[#04bdaf]/50 hover:bg-gray-800/50"
                    >
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-[#04bdaf] to-[#822478] text-lg font-bold text-white shrink-0">
                            {{ pkg.name[0].toUpperCase() }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-white group-hover:text-[#04bdaf] truncate">laravilt/{{ pkg.name }}</span>
                                <svg class="size-4 text-gray-500 group-hover:text-[#04bdaf] shrink-0" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="text-sm text-gray-500">{{ pkg.desc }}</div>
                        </div>
                    </a>
                </div>

                <div class="mt-8 text-center">
                    <a href="https://github.com/laravilt" target="_blank" class="inline-flex items-center gap-2 text-[#04bdaf] hover:underline">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                        View all packages on GitHub
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#04bdaf]/20 to-[#822478]/20 p-12 text-center">
                    <!-- Background decoration -->
                    <div class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-[#04bdaf]/20 blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 h-48 w-48 rounded-full bg-[#822478]/20 blur-3xl"></div>

                    <div class="relative">
                        <h2 class="mb-4 text-3xl font-bold sm:text-4xl">Ready to Build Your Admin Panel?</h2>
                        <p class="mx-auto mb-8 max-w-xl text-lg text-gray-400">
                            Install Laravilt in your Laravel project and start building beautiful interfaces in minutes.
                        </p>

                        <div class="mb-8 inline-flex items-center gap-3 rounded-xl bg-gray-800 px-6 py-3 font-mono text-sm">
                            <span class="text-gray-500">$</span>
                            <span>php artisan laravilt:install</span>
                        </div>

                        <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                            <Link href="/docs/getting-started/installation" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#04bdaf] to-[#822478] px-8 py-4 font-semibold text-white transition hover:opacity-90">
                                Read the Docs
                            </Link>
                            <a href="https://github.com/laravilt/laravilt" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-8 py-4 font-semibold transition hover:bg-white/10">
                                <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                                Star on GitHub
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-white/10 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <div class="mb-4 flex items-center gap-3 text-xl font-bold">
                            <svg class="h-8 w-8" viewBox="0 0 263 306.97" xmlns="http://www.w3.org/2000/svg">
                                <polygon fill="#04bdaf" points="223 18.65 148 53.63 95 78.34 95 161.09 148 136.38 148 306.84 223 271.87 223 101.4 263 82.75 263 0 223 18.65"/>
                                <polygon fill="#822478" points="75 189.76 75 35 0 0 0 146.46 0 217.47 0 233.07 75 276.37 128 306.97 128 220.36 75 189.76"/>
                            </svg>
                            <span>Laravilt</span>
                        </div>
                        <p class="text-sm text-gray-500">Modern admin panel framework for Laravel + Vue. Type-safe, beautiful, and powerful.</p>
                    </div>
                    <div>
                        <h4 class="mb-4 font-semibold">Documentation</h4>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><Link href="/docs/getting-started/installation" class="transition hover:text-[#04bdaf]">Installation</Link></li>
                            <li><Link href="/docs/forms" class="transition hover:text-[#04bdaf]">Forms</Link></li>
                            <li><Link href="/docs/tables" class="transition hover:text-[#04bdaf]">Tables</Link></li>
                            <li><Link href="/docs/panel" class="transition hover:text-[#04bdaf]">Panel</Link></li>
                            <li><Link href="/docs/widgets" class="transition hover:text-[#04bdaf]">Widgets</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 font-semibold">Resources</h4>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="https://github.com/laravilt/laravilt" target="_blank" class="transition hover:text-[#04bdaf]">GitHub</a></li>
                            <li><a href="https://github.com/laravilt/laravilt/releases" target="_blank" class="transition hover:text-[#04bdaf]">Changelog</a></li>
                            <li><a href="https://github.com/laravilt/laravilt/issues" target="_blank" class="transition hover:text-[#04bdaf]">Issues</a></li>
                            <li><Link href="/docs/ai" class="transition hover:text-[#04bdaf]">AI Integration</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="mb-4 font-semibold">Community</h4>
                        <ul class="space-y-2 text-sm text-gray-500">
                            <li><a href="https://twitter.com/laravilt" target="_blank" class="transition hover:text-[#04bdaf]">Twitter</a></li>
                            <li><a href="https://discord.gg/laravilt" target="_blank" class="transition hover:text-[#04bdaf]">Discord</a></li>
                            <li><a href="https://github.com/laravilt/laravilt/discussions" target="_blank" class="transition hover:text-[#04bdaf]">Discussions</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-12 border-t border-white/10 pt-8 text-center text-sm text-gray-500">
                    <p>&copy; {{ new Date().getFullYear() }} Laravilt. Built with Laravel, Vue 3, and TypeScript.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
