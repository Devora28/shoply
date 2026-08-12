<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: { type: String, default: 'primary' },
  size: { type: String, default: 'md' },
  to: { type: String, default: null },
  href: { type: String, default: null },
  icon: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
})

const classes = computed(() => {
  const variant = {
    primary: 'btn-primary',
    accent: 'btn-accent',
    secondary: 'btn-secondary',
    ghost: 'btn-ghost',
    danger: 'btn-danger',
  }[props.variant] || 'btn-primary'

  const size = props.icon ? 'btn-icon' : {
    sm: 'btn-sm',
    md: 'btn-md',
    lg: 'btn-lg',
    xl: 'btn-xl',
  }[props.size] || 'btn-md'

  return [variant, size]
})
</script>

<template>
  <button
    v-if="!to && !href"
    :class="classes"
    :disabled="disabled || loading"
    type="button"
  >
    <svg v-if="loading" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
    </svg>
    <slot />
  </button>
  <a v-else-if="href" :href="href" :class="classes">
    <slot />
  </a>
  <router-link v-else :to="to" :class="classes">
    <slot />
  </router-link>
</template>
