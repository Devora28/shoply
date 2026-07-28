<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const props = defineProps({
  items: { type: Array, default: () => [] },
})

const route = useRoute()

const segments = computed(() => {
  const path = route.path
  const parts = path.split('/').filter(Boolean)
  return parts.map((seg, i) => ({
    label: props.items[i] || seg.charAt(0).toUpperCase() + seg.slice(1),
    to: '/' + parts.slice(0, i + 1).join('/'),
    last: i === parts.length - 1,
  }))
})
</script>

<template>
  <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm">
    <router-link to="/" class="text-ink-500 hover:text-ink-900 transition-colors">Home</router-link>
    <template v-for="(seg, i) in segments" :key="i">
      <svg class="w-4 h-4 text-ink-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
      <router-link
        v-if="!seg.last"
        :to="seg.to"
        class="text-ink-500 hover:text-ink-900 transition-colors"
      >{{ seg.label }}</router-link>
      <span v-else class="text-ink-900 font-medium">{{ seg.label }}</span>
    </template>
  </nav>
</template>
