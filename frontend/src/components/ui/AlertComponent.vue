<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'success' },
  title: { type: String, default: '' },
  description: { type: String, default: '' },
  icon: { type: Object, default: null },
})

const styles = computed(() => ({
  success: 'bg-success-50 border-success-200 text-success-800',
  warning: 'bg-warning-50 border-warning-200 text-warning-800',
  danger: 'bg-danger-50 border-danger-200 text-danger-800',
  info: 'bg-info-50 border-info-200 text-info-800',
  primary: 'bg-primary-50 border-primary-200 text-primary-800',
}[props.variant] || 'bg-success-50 border-success-200 text-success-800'))

const iconColor = computed(() => ({
  success: 'text-success-500',
  warning: 'text-warning-500',
  danger: 'text-danger-500',
  info: 'text-info-500',
  primary: 'text-primary-500',
}[props.variant] || 'text-success-500'))
</script>

<template>
  <div :class="['flex items-start gap-3 p-4 rounded-xl border', styles]">
    <component v-if="icon" :is="icon" :class="['w-5 h-5 shrink-0 mt-0.5', iconColor]" />
    <div>
      <p v-if="title" class="font-semibold">{{ title }}</p>
      <p v-if="description" class="text-sm opacity-90 mt-0.5">{{ description }}</p>
    </div>
  </div>
</template>
