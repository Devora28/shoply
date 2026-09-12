<script setup>
import { computed } from 'vue'
const props = defineProps({
  step: { type: Number, default: 1 },
  steps: { type: Array, default: () => ['Cart', 'Shipping', 'Payment', 'Review'] },
})
const current = computed(() => props.step - 1)
</script>

<template>
  <div class="flex items-center w-full">
    <template v-for="(s, i) in steps" :key="i">
      <div class="flex items-center gap-2 shrink-0">
        <div
          class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-full text-sm font-bold transition-all duration-300"
          :class="i < current ? 'bg-primary-600 text-white' : i === current ? 'bg-primary-600 text-white ring-4 ring-primary-100' : 'bg-ink-100 text-ink-400'"
        >
          <svg v-if="i < current" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
          <span v-else>{{ i + 1 }}</span>
        </div>
        <span
          class="hidden sm:block text-sm font-medium transition-colors"
          :class="i <= current ? 'text-ink-900' : 'text-ink-400'"
        >{{ s }}</span>
      </div>
      <div
        v-if="i < steps.length - 1"
        class="flex-1 h-0.5 mx-2 sm:mx-3 rounded-full transition-colors duration-300"
        :class="i < current ? 'bg-primary-600' : 'bg-ink-200'"
      />
    </template>
  </div>
</template>
