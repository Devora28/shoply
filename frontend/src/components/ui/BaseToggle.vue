<script setup>
import { computed } from 'vue'
const props = defineProps({
  modelValue: { type: Boolean, default: false },
  label: { type: String, default: '' },
  hint: { type: String, default: '' },
})
const emit = defineEmits(['update:modelValue'])
const model = computed({
  get: () => props.modelValue,
  set: (v) => emit('update:modelValue', v),
})
</script>

<template>
  <div class="flex items-start gap-3">
    <button
      type="button"
      role="switch"
      :aria-checked="model"
      class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors duration-200"
      :class="model ? 'bg-primary-600' : 'bg-ink-300'"
      @click="model = !model"
    >
      <span
        class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200"
        :class="model ? 'translate-x-5' : 'translate-x-0.5'"
      />
    </button>
    <div v-if="label || hint">
      <p v-if="label" class="text-sm font-medium text-ink-900">{{ label }}</p>
      <p v-if="hint" class="text-xs text-ink-500">{{ hint }}</p>
    </div>
  </div>
</template>
