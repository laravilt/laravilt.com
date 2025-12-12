<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Heading from '@/components/Heading.vue';
</script>

<template>
    <AppLayout :title="'Sales Analytics'">
        <div class="space-y-6">
            <Heading>Sales Analytics</Heading>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Add widget components here -->
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <!-- Add chart components here -->
            </div>
        </div>
    </AppLayout>
</template>