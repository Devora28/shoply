<script setup>
import { computed } from 'vue'
const props = defineProps({
  modelValue: { type: Number, default: 0 },
  max: { type: Number, default: 5 },
  size: { type: String, default: 'md' },
  interactive: { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue'])
const sizeClass = computed(() => ({
  sm: 'w-3.5 h-3.5',
  md: 'w-4 h-4',
  lg: 'w-5 h-5',
  xl: 'w-6 h-6',
}[props.size] || 'w-4 h-4'))
const fullStars = computed(() => Math.floor(props.modelValue))
const hasHalf = computed(() => props.modelValue % 1 >= 0.25 && props.modelValue % 1 < 0.75)
function setRating(val) {
  if (props.interactive) emit('update:modelValue', val)
}
</script>

<template>
  <div class="inline-flex items-center gap-0.5" :role="interactive ? 'radiogroup' : 'img'" :aria-label="`Rating: ${modelValue} out of ${max}`">
    <template v-for="i in max" :key="i">
      <svg
        :class="[sizeClass, { 'cursor-pointer': interactive }]"
        viewBox="0 0 24 24"
        :fill="i <= fullStars ? '#f59e0b' : hasHalf && i === fullStars + 1 ? 'url(#half)' : 'none'"
        stroke="#f59e0b"
        stroke-width="1.5"
        @click="setRating(i)"
        @mouseenter="interactive && setRating(i)"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
      </svg>
    </template>
    <svg width="0" height="0">
      <defs>
        <linearGradient id="half">
          <stop offset="50%" stop-color="#f59e0b" />
          <stop offset="50%" stop-color="none" />
        </linearGradient>
      </defs>
    </svg>
  </div>
</template>
