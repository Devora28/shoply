<script setup>
import { computed } from 'vue'
import { VueDatePicker } from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
  modelValue: {
    type: [String, Date],
    default: '',
  },
  label: {
    type: String,
    default: '',
  },
  hint: {
    type: String,
    default: '',
  },
  error: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: '',
  },
  size: {
    type: String,
    default: 'md',
  },
  rounded: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

const model = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
})

const inputClass = computed(() => [
  {
    sm: 'input-sm',
    md: 'input',
    lg: 'input-lg',
  }[props.size] || 'input',

  {
    'input-error': props.error,
    'rounded-full': props.rounded,
  },
])
</script>

<template>
  <div>
    <label v-if="label" class="label">
      {{ label }}
    </label>
      <VueDatePicker
        v-model="model"
        :placeholder="placeholder"
        :time-config="{ enableTimePicker: false }"
        :formats="{ input: 'MMM dd, yyyy' }"
        model-type="yyyy-MM-dd"
        auto-apply
        :input-attrs="{ hideInputIcon: true }"
        :ui="{ input: inputClass }"
      />
    <p v-if="error" class="error-text">
      {{ error }}
    </p>
    <p v-else-if="hint" class="hint">
      {{ hint }}
    </p>
  </div>
</template>
<style scoped>
:deep(.dp--theme-light) {
  --dp-text-color: #1c1917;
  --dp-secondary-color: #78716c;
  --dp-hover-text-color: #1c1917;
  --dp-icon-color: #a8a29e;
  --dp-hover-icon-color: #78716c;
  --dp-primary-color: #0d9488;
  --dp-primary-text-color: #ffffff;
  --dp-background-color: #ffffff;
  --dp-hover-color: #f5f5f4;
  --dp-border-color: #e7e5e4;
  --dp-border-color-hover: #d6d3d1;
  --dp-border-color-focus: #0d9488;
}
</style>
