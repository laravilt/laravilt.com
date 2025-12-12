<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
</script>

<template>
    <AppLayout :title="'Customer Insights'">
        <div class="space-y-6">
            <Heading>Customer Insights</Heading>

            <div class="rounded-lg border p-6">
                <p class="text-muted-foreground">
                    This is the CustomerInsights page. Add your content here.
                </p>
            </div>
        </div>
    </AppLayout>
</template>