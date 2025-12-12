<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <ul
    data-slot="sidebar-menu-sub"
    data-sidebar="menu-sub"
    :class="cn(
      // Full width sub-menu with minimal indent
      'flex min-w-0 flex-col gap-1 py-0.5 ps-4',
      'group-data-[collapsible=icon]:hidden',
      'sidebar-menu-sub',
      props.class,
    )"
  >
    <slot />
  </ul>
</template>
