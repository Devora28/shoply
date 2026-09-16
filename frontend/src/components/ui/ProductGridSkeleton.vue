<script setup>
const props = defineProps({
  count: { type: Number, default: 8 },
  cardClass: { type: String, default: '' },
})
</script>

<template>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
    <div v-for="i in count" :key="i" :class="['card p-4', cardClass]">
      <div class="aspect-product skeleton mb-3"></div>
      <div class="h-3 w-1/3 skeleton mb-2"></div>
      <div class="h-4 w-full skeleton mb-2"></div>
      <div class="h-4 w-2/3 skeleton mb-3"></div>
      <div class="h-6 w-1/2 skeleton"></div>
    </div>
  </div>
</template>
