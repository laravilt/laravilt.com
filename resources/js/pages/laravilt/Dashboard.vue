<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, markRaw } from 'vue';
import PanelLayout from '@laravilt/panel/layouts/PanelLayout.vue';
import WidgetRenderer from '@laravilt/widgets/components/WidgetRenderer.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import * as LucideIcons from 'lucide-vue-next';

const PanelLayoutRaw = markRaw(PanelLayout);

interface BreadcrumbItem {
    label: string;
    url: string | null;
}

interface ClusterNavItem {
    title: string;
    href: string;
    icon?: string;
}

interface WidgetData {
    component: string;
    stats?: any[];
    columns?: number;
    [key: string]: any;
}

const props = defineProps<{
    title?: string;
    breadcrumbs?: BreadcrumbItem[];
    headerWidgets?: WidgetData[];
    footerWidgets?: WidgetData[];
    clusterNavigation?: ClusterNavItem[] | null;
    clusterTitle?: string | null;
    clusterIcon?: string | null;
    clusterDescription?: string | null;
}>();

const page = usePage();

// Transform breadcrumbs to frontend format
const transformedBreadcrumbs = computed(() => {
    if (!props.breadcrumbs) return [];
    return props.breadcrumbs.map(item => ({
        title: item.label,
        href: item.url || '#',
    }));
});

// Check if current page matches the nav item
const isCurrentPage = (href: string) => {
    const currentUrl = page.url;
    // Extract path from both URLs for comparison
    const extractPath = (u: string) => {
        try {
            if (u.startsWith('http://') || u.startsWith('https://')) {
                const urlObj = new URL(u);
                return urlObj.pathname;
            }
            return u;
        } catch {
            return u;
        }
    };
    return extractPath(href) === extractPath(currentUrl);
};

// Get icon component by name
const getIcon = (iconName?: string) => {
    if (!iconName) return null;
    return (LucideIcons as any)[iconName] || null;
};
</script>

<template>
    <Head :title="title || 'Dashboard'" />

    <PanelLayoutRaw :breadcrumbs="transformedBreadcrumbs">
        <div class="px-4 py-6">
            <div class="flex flex-col lg:flex-row lg:space-x-12">
                <!-- Cluster Sidebar Navigation -->
                <aside
                    v-if="clusterNavigation && clusterNavigation.length > 0"
                    class="w-full max-w-xl lg:w-48 shrink-0"
                >
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold flex items-center gap-2">
                            <component
                                v-if="clusterIcon && getIcon(clusterIcon)"
                                :is="getIcon(clusterIcon)"
                                class="size-5"
                            />
                            {{ clusterTitle }}
                        </h2>
                        <p v-if="clusterDescription" class="text-sm text-muted-foreground">
                            {{ clusterDescription }}
                        </p>
                    </div>
                    <nav class="flex flex-col space-y-1">
                        <Button
                            v-for="item in clusterNavigation"
                            :key="item.href"
                            variant="ghost"
                            :class="[
                                'w-full justify-start',
                                { 'bg-muted': isCurrentPage(item.href) }
                            ]"
                            as-child
                        >
                            <Link :href="item.href">
                                <component
                                    v-if="item.icon && getIcon(item.icon)"
                                    :is="getIcon(item.icon)"
                                    class="mr-2 size-4"
                                />
                                {{ item.title }}
                            </Link>
                        </Button>
                    </nav>
                </aside>

                <Separator v-if="clusterNavigation && clusterNavigation.length > 0" class="my-6 lg:hidden" />

                <!-- Main Content -->
                <div class="flex-1 flex flex-col gap-6">
                <!-- Header Widgets -->
                <WidgetRenderer
                    v-if="headerWidgets && headerWidgets.length"
                    :widgets="headerWidgets"
                />

                <!-- Main Content Area (for extending) -->
                <slot />

                <!-- Footer Widgets -->
                <WidgetRenderer
                    v-if="footerWidgets && footerWidgets.length"
                    :widgets="footerWidgets"
                />
                </div>
            </div>
        </div>
    </PanelLayoutRaw>
</template>
