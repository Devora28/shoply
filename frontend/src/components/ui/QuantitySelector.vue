<script setup>
import { ref } from 'vue'

const props = defineProps({
  modelValue: { type: Number, default: 1 },
  min: { type: Number, default: 1 },
  max: { type: Number, default: 99 },
  size: { type: String, default: 'md' },
})

const emit = defineEmits(['update:modelValue'])

const localVal = ref(props.modelValue)

function change(delta) {
  localVal.value = Math.max(props.min, Math.min(props.max, localVal.value + delta))
  emit('update:modelValue', localVal.value)
}

function onInput(e) {
  localVal.value = Math.max(props.min, Math.min(props.max, parseInt(e.target.value) || props.min))
  emit('update:modelValue', localVal.value)
}

const sizeClass = props.size === 'sm' ? 'w-7 h-7' : 'w-9 h-9'
const inputSize = props.size === 'sm' ? 'text-xs w-8' : 'text-sm w-12'
</script>

<template>
  <div class="inline-flex items-center rounded-xl border border-ink-200 overflow-hidden">
    <button
      :class="['flex items-center justify-center bg-white hover:bg-ink-100 transition-colors text-ink-600', sizeClass]"
      @click="change(-1)"
      :disabled="modelValue <= min"
    >
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/></svg>
    </button>
    <input
      :value="modelValue"
      type="text"
      class="pointer-events-none select-none"
      :class="['text-center font-semibold border-x border-ink-200 focus:outline-none py-1', inputSize]"
      readonly
    />
    <button
      :class="['flex items-center justify-center bg-white hover:bg-ink-100 transition-colors text-ink-600', sizeClass]"
      @click="change(1)"
      :disabled="modelValue >= max"
    >
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
    </button>
  </div>
</template>
