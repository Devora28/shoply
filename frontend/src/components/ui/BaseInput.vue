<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  type: { type: String, default: 'text' },
  label: { type: String, default: '' },
  hint: { type: String, default: '' },
  error: { type: String, default: '' },
  placeholder: { type: String, default: '' },
  size: { type: String, default: 'md' },
  icon: { type: Object, default: null },
  rounded: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue'])

const model = computed({
  get: () => props.modelValue,
  set: (v) => emit('update:modelValue', v),
})

const sizeClass = computed(() => ({
  sm: 'input-sm',
  md: 'input',
  lg: 'input-lg',
}[props.size] || 'input'))
</script>

<template>
  <div>
    <label v-if="label" class="label">{{ label }}</label>
    <div class="relative">
      <span v-if="icon" class="absolute left-3 top-1/2 -translate-y-1/2 text-ink-400">
        <component :is="icon" class="w-4 h-4" />
      </span>
      <input
        v-model="model"
        :type="type"
        :placeholder="placeholder"
        :class="[sizeClass, { 'input-error': error, 'pl-10': icon, 'rounded-full': rounded }]"
      />
    </div>
    <p v-if="error" class="error-text">{{ error }}</p>
    <p v-else-if="hint" class="hint">{{ hint }}</p>
  </div>
</template>
