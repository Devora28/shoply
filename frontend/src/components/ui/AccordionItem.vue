<script setup>
import { ref } from 'vue'
import { ChevronDown } from '@lucide/vue';

const props = defineProps({
  title: { type: String, default: '' },
  defaultOpen: { type: Boolean, default: false },
})

const open = ref(props.defaultOpen)
</script>

<template>
  <div class="border-b border-ink-200">
    <button
      class="flex w-full items-center justify-between py-4 text-left"
      @click="open = !open"
    >
      <span class="text-base font-semibold text-ink-900">{{ title }}</span>
      <ChevronDown
        class="w-5 h-5 text-ink-500 transition-transform duration-200"
        :class="{ 'rotate-180': open }"
      />
    </button>
    <div v-show="open" class="pb-4 text-sm text-ink-600 leading-relaxed">
      <slot />
    </div>
  </div>
</template>
