<script setup>
import RatingStars from "@/components/common/RatingStars.vue";
import { Heart, ShoppingCart, Eye } from '@lucide/vue';
import { useRouter } from 'vue-router';
import {calcDiscount} from "../../utils/helpers.js";
const router = useRouter();
defineProps({
  product: { type: Object, required: true },
});
</script>
<template>
  <div class="card card-hover group cursor-pointer relative flex flex-col" @click="router.push(`/product/${product.id}`)">
    <!-- Badges -->
    <div v-if="product.discount > 0" class="absolute top-3 left-3 z-10 flex flex-col gap-1.5">
      <span class="badge-danger"> {{product.discount}} % OFF</span>
    </div>
    <!-- Wishlist -->
    <button
      class="absolute top-3 right-3 z-10 p-2 rounded-full bg-white/80 glass hover:bg-white shadow-sm transition-all"
      @click.stop=""
      aria-label="Add to wishlist"
    >
      <Heart
        class="w-4 h-4 transition-colors"
      />
    </button>
    <!-- Image -->
    <div class="aspect-product overflow-hidden rounded-t-2xl bg-ink-50 relative">
      <img
        :src="product.image??''"
        :alt="product.name"
        loading="lazy"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
      />
      <!-- Quick actions on hover -->
      <div class="absolute inset-x-0 bottom-0 p-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
        <button
          class="flex-1 btn-primary btn-sm"
          @click.stop=""
        >
          <ShoppingCart class="w-4 h-4" /> Add
        </button>
        <button class="btn-secondary btn-sm btn-icon" @click.stop="router.push(`/product/${product.id}`)">
          <Eye class="w-4 h-4" />
        </button>
      </div>
    </div>
    <!-- Content -->
    <div class="p-3 sm:p-4 flex flex-col flex-1">
      <span class="text-2xs font-medium text-ink-400 mb-1">{{ product.brand.name }}</span>
      <h3 class="text-sm font-medium text-ink-900 line-clamp-2 min-h-[44px] mb-2 leading-snug">{{ product.name }}</h3>
      <div class="flex items-center gap-1.5 mb-2">
        <RatingStars :model-value="3.5" size="sm" />
        <span class="text-xs text-ink-500">(155)</span>
      </div>
      <div class="mt-auto flex items-baseline gap-2">
        <span class="text-lg font-bold text-primary-700">${{calcDiscount(product.price,product.discount)}}</span>
        <span class="text-sm text-ink-400 line-through">${{product.price}}</span>
      </div>
      <div class="mt-1.5 flex items-center gap-1 text-xs text-success-600">
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 18H3a1 1 0 01-1-1V8a1 1 0 011-1h12a1 1 0 011 1v10M9 18h6m-9 0a2 2 0 11-4 0m4 0a2 2 0 11-4 0m9-7h4l3 3v4a1 1 0 01-1 1h-2m-4 0a2 2 0 11-4 0m4 0a2 2 0 11-4 0"/></svg>
        Free Shipping
      </div>
    </div>
  </div>
</template>
