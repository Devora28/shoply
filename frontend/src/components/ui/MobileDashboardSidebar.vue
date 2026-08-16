<script setup>
import {useRoute} from "vue-router";
import {Bell, LayoutDashboard, MapPin, Package, Settings, User} from "@lucide/vue";
const route = useRoute();
const navLinks = [
  { label: 'Dashboard', to: '/account/dashboard', icon: LayoutDashboard },
  { label: 'Profile Info', to: '/account/information', icon: User },
  { label: 'My Orders', to: '/account/orders', icon: Package },
  { label: 'Notifications', to: '/account/notifications', icon: Bell },
  { label: 'Addresses', to: '/account/addresses', icon: MapPin },
  { label: 'Settings', to: '/account/settings', icon: Settings },
]
</script>

<template>
  <div class="lg:hidden w-full min-w-0 mb-2">
    <div class="flex gap-2 overflow-x-auto no-scrollbar pb-2">
      <router-link
        v-for="link in navLinks"
        :key="link.to"
        :to="link.to"
        class="chip shrink-0"
        :class="{ 'chip-active': route.path === link.to }"
      >
        <component :is="link.icon" class="w-4 h-4" />
        {{ link.label }}
      </router-link>
    </div>
  </div>
</template>

<style scoped>

</style>
