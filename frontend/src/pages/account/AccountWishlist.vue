<script setup>
import {computed, onMounted, ref} from 'vue'
import ProductCard from '@/components/product/ProductCard.vue';
import BaseBreadcrumb from '@/components/ui/BaseBreadcrumb.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import {
  Heart, ShoppingCart, Package,
} from '@lucide/vue';
import {calcDiscount, formatPrice} from "@/utils/helpers.js";
import {useWishlistStore} from "@/stores/wishlist.js";
const breadcrumbItems = ['Wishlist'];
const wishlistStore = useWishlistStore();
onMounted(() => {
  wishlistStore.loadWishlist();
});
const total = computed(() =>
  wishlistStore.wishlistItems.reduce((sum, item) => sum + Number(calcDiscount(item.product.price,item.product.discount)), 0)
);
</script>

<template>
  <div class="bg-ink-50/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Breadcrumb -->
      <BaseBreadcrumb :items="breadcrumbItems" class="mb-5" />
      <div v-if="wishlistStore.itemsCount > 0" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
          <div class="flex items-center justify-center w-11 h-11 rounded-2xl bg-danger-50 text-danger-500">
            <Heart class="w-6 h-6 fill-danger-500" />
          </div>
          <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-ink-900">My Wishlist</h1>
            <p class="text-sm text-ink-500">
              {{ wishlistStore.itemsCount }}
              {{ wishlistStore.itemsCount === 1 ? 'item' : 'items' }}
              <span v-if="wishlistStore.itemsCount > 0" class="text-ink-300">·</span>
              <span v-if="wishlistStore.itemsCount > 0" class="font-semibold text-ink-700">
                {{formatPrice(total)}}
              </span>
            </p>
          </div>
        </div>
        <div v-if="wishlistStore.itemsCount > 0" class="flex items-center gap-2">
          <BaseButton
            variant="secondary"
            to="/shop"
            class="!rounded-xl w-full sm:w-auto"
          >
            <Package class="w-4 h-4" />
            <span class="hidden sm:inline">Continue shopping</span>
            <span class="sm:hidden">Shop</span>
          </BaseButton>
        </div>
      </div>
      <!-- ============ WISHLIST LOADING ============ -->
      <div
        v-if="wishlistStore.loading"
        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5"
      >
        <div
          v-for="i in 4"
          :key="i"
          class="card overflow-hidden"
        >
          <!-- Product image -->
          <div class="skeleton w-full aspect-product rounded-none"></div>
          <!-- Product content -->
          <div class="p-4 space-y-3">
            <div class="skeleton h-4 w-4/5"></div>
            <div class="skeleton h-4 w-2/3"></div>
            <div class="flex items-center justify-between pt-2">
              <div class="skeleton h-5 w-24"></div>
              <div class="skeleton h-9 w-9 rounded-xl"></div>
            </div>
          </div>
        </div>
      </div>
      <!-- ============ WISHLIST GRID ============ -->
      <div
        v-else-if="wishlistStore.itemsCount > 0"
        class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5"
      >
        <ProductCard
          v-for="item in wishlistStore.wishlistItems"
          :key="item.id"
          :product="item.product"
        />
      </div>
      <!-- ============ EMPTY STATE ============ -->
      <div v-else class="py-16">
        <div class="card max-w-xl mx-auto p-8 sm:p-12 text-center">
          <div class="relative inline-flex items-center justify-center mb-6">
            <div class="absolute inset-0 bg-danger-100/60 blur-2xl rounded-full scale-150"></div>
            <div class="relative inline-flex items-center justify-center w-24 h-24 rounded-full bg-danger-50 text-danger-400">
              <Heart class="w-12 h-12" />
            </div>
          </div>
          <h2 class="text-xl sm:text-2xl font-bold text-ink-900 mb-2">
            Your wishlist is empty
          </h2>
          <p class="text-ink-500 mb-8 max-w-sm mx-auto">
            Save items you love by tapping the heart icon. Your wishlist makes it
            easy to find them again and keep track of products you want.
          </p>
          <BaseButton variant="primary" size="lg" to="/shop" class="!rounded-xl">
            <ShoppingCart class="w-5 h-5" />
            Start shopping
          </BaseButton>
        </div>
      </div>

      <!-- ============ FREE SHIPPING BANNER ============ -->
      <div
        v-if="wishlistStore.itemsCount > 0"
        class="mt-8 card p-5 flex flex-col sm:flex-row items-center justify-between gap-4 gradient-primary text-white"
      >
        <div class="flex items-center gap-3">
          <Package class="w-6 h-6 shrink-0" />
          <div>
            <p class="font-semibold">Keep exploring</p>
            <p class="text-sm text-white/80">Discover more products and find something new to love</p>
          </div>
        </div>
        <BaseButton variant="accent" class="!rounded-xl shrink-0">
          <ShoppingCart class="w-4 h-4" />
          Continue Shopping
        </BaseButton>
      </div>
    </div>
  </div>
</template>
