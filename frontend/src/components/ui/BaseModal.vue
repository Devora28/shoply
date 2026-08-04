<script setup>
import { computed } from 'vue'
import {
  Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild,
} from '@headlessui/vue'
import { X } from '@lucide/vue'

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  title: { type: String, default: '' },
  size: { type: String, default: 'md' },
})

const emit = defineEmits(['update:modelValue'])
const isOpen = computed({
  get: () => props.modelValue,
  set: (v) => emit('update:modelValue', v),
})

const sizeClass = computed(() => ({
  sm: 'max-w-md',
  md: 'max-w-lg',
  lg: 'max-w-2xl',
  xl: 'max-w-4xl',
}[props.size] || 'max-w-lg'))
</script>

<template>
  <TransitionRoot appear :show="isOpen" as="template">
    <Dialog as="div" class="relative z-50" @close="isOpen = false">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-ink-950/50 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out-expo"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel :class="['w-full', sizeClass, 'bg-white rounded-2xl shadow-2xl']">
              <div v-if="title" class="flex items-center justify-between px-6 py-4 border-b border-ink-200">
                <DialogTitle class="text-lg font-bold text-ink-900">{{ title }}</DialogTitle>
                <button class="btn-ghost btn-icon" @click="isOpen = false">
                  <X class="w-5 h-5" />
                </button>
              </div>
              <div v-else class="absolute top-4 right-4">
                <button class="btn-ghost btn-icon bg-white/80 glass" @click="isOpen = false">
                  <X class="w-5 h-5" />
                </button>
              </div>
              <slot />
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
