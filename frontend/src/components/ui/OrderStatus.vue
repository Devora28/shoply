<script setup>
import { computed } from 'vue'
import { CheckCircle2, Package, Truck, Home, XCircle } from '@lucide/vue'

const props = defineProps({
  status: { type: String, default: 'processing' },
})

const steps = [
  { key: 'processing', label: 'Processing', icon: Package },
  { key: 'shipped', label: 'Shipped', icon: Truck },
  { key: 'delivered', label: 'Delivered', icon: Home },
]

const currentIndex = computed(() => {
  if (props.status === 'cancelled') return -1
  return steps.findIndex(s => s.key === props.status)
})
</script>

<template>
  <div v-if="status === 'cancelled'" class="flex items-center gap-2 text-danger-600">
    <XCircle class="w-5 h-5" />
    <span class="font-medium">Order Cancelled</span>
  </div>
  <div v-else class="flex items-center w-full">
    <template v-for="(step, i) in steps" :key="step.key">
      <div class="flex flex-col items-center gap-1.5 shrink-0">
        <div
          class="flex items-center justify-center w-10 h-10 rounded-full transition-all duration-300"
          :class="i <= currentIndex ? 'bg-primary-600 text-white' : 'bg-ink-100 text-ink-400'"
        >
          <component :is="step.icon" class="w-5 h-5" />
        </div>
        <span
          class="text-xs font-medium"
          :class="i <= currentIndex ? 'text-ink-900' : 'text-ink-400'"
        >{{ step.label }}</span>
      </div>
      <div
        v-if="i < steps.length - 1"
        class="flex-1 h-0.5 mx-2 rounded-full transition-colors duration-300"
        :class="i < currentIndex ? 'bg-primary-600' : 'bg-ink-200'"
      />
    </template>
  </div>
</template>
