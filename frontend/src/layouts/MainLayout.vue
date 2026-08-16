<script setup>
  import AppHeader from "@/components/layout/AppHeader.vue";
  import AppFooter from "@/components/layout/AppFooter.vue";
  import {useRoute} from "vue-router";
  const route = useRoute();
</script>

<template>
  <AppHeader/>
  <RouterView v-slot="{ Component }">
    <Transition :name="route.meta.transition || ''">
      <component :is="Component" />
    </Transition>
  </RouterView>
  <AppFooter/>
</template>

<style>
.account-enter-active,
.account-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.account-enter-from {
  opacity: 0;
  transform: translateY(8px);
}
.account-leave-to {
  opacity: 0;
  transform: translateY(-4px);
}
</style>
