<script setup>
  import { Toaster } from 'vue-sonner';
  import {useAuthStore} from "@/stores/auth.js";
  import {onMounted} from "vue";
  const authStore = useAuthStore()
  onMounted(async () => {
    await authStore.fetchUser();
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
