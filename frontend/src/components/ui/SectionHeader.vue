<script setup>
import { computed } from 'vue'
import { ChevronRight } from '@lucide/vue'

const props = defineProps({
  title: { type: String, default: '' },
  subtitle: { type: String, default: '' },
  to: { type: String, default: null },
})

const show = computed(() => props.title)
</script>

<template>
  <div v-if="show" class="flex items-center justify-between mb-4 sm:mb-5">
    <div>
      <h2 class="section-title">{{ title }}</h2>
      <p v-if="subtitle" class="text-sm text-ink-500 mt-1">{{ subtitle }}</p>
    </div>
    <router-link v-if="to" :to="to" class="btn-ghost btn-sm text-primary-600 hover:text-primary-700">
      View all
      <ChevronRight class="w-4 h-4" />
    </router-link>
  </div>
</template>
