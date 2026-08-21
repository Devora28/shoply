<script setup>
const props = defineProps({
  totalPages: { type: Number, default: 1 },
  modelValue: { type: Number, default: 1 },
})

const emit = defineEmits(['update:modelValue'])

function go(page) {
  if (
    page >= 1 &&
    page <= props.totalPages &&
    page !== props.modelValue
  ) {
    emit('update:modelValue', page)
  }
}

function pages() {
  const total = props.totalPages
  const current = props.modelValue

  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1)
  }

  if (current <= 4) {
    return [1, 2, 3, 4, 5, '...', total]
  }

  if (current >= total - 3) {
    return [
      1,
      '...',
      total - 4,
      total - 3,
      total - 2,
      total - 1,
      total
    ]
  }

  return [1, '...', current - 1, current, current + 1, '...', total]
}
</script>

<template>
  <nav
    v-if="totalPages > 1"
    class="flex items-center justify-center gap-1.5"
  >
    <button
      class="btn-secondary btn-sm btn-icon disabled:opacity-40"
      @click="go(modelValue - 1)"
      :disabled="modelValue === 1"
      aria-label="Previous page"
    >
      <svg
        class="w-4 h-4"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
      >
        <path d="M15 18l-6-6 6-6"/>
      </svg>
    </button>

    <template v-for="(p, i) in pages()" :key="`${p}-${i}`">
      <span
        v-if="p === '...'"
        class="px-2 text-ink-400"
      >
        ...
      </span>

      <button
        v-else
        @click="go(p)"
        :class="[
          'inline-flex items-center justify-center min-w-[36px] h-9 px-3 rounded-xl text-sm font-semibold transition-all',
          p === modelValue
            ? 'bg-primary-600 text-white shadow-sm'
            : 'bg-white text-ink-600 border border-ink-200 hover:bg-ink-100'
        ]"
      >
        {{ p }}
      </button>
    </template>

    <button
      class="btn-secondary btn-sm btn-icon disabled:opacity-40"
      @click="go(modelValue + 1)"
      :disabled="modelValue === totalPages"
      aria-label="Next page"
    >
      <svg
        class="w-4 h-4"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
      >
        <path d="M9 18l6-6-6-6"/>
      </svg>
    </button>
  </nav>
</template>
