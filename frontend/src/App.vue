<script setup>
  import { Toaster } from 'vue-sonner';
  import {useAuthStore} from "@/stores/auth.js";
  import {useCartStore} from "@/stores/cart.js";
  import {onMounted} from "vue";
  import {useWishlistStore} from "@/stores/wishlist.js";
  const authStore = useAuthStore();
  const cartStore = useCartStore();
  const wishlistStore = useWishlistStore();
  onMounted(async () => {
    await authStore.fetchUser();
    await cartStore.initialize(authStore.isAuth);
    if (authStore.isAuth) {
      await wishlistStore.loadWishlist()
    }
  })
</script>

<template>
  <RouterView/>
  <Toaster
    position="top-right"
    rich-colors
    duration="3500"
    expand
    visible-toasts="5"
    theme="light"
  />
</template>

<style scoped></style>
