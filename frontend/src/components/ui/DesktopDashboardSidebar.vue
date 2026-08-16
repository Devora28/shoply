<script setup>
  import {useRoute} from "vue-router";
  import {Bell, LayoutDashboard, MapPin, Package, Settings, User} from "@lucide/vue";
  import {computed} from "vue";
  import {useAuthStore} from "@/stores/auth.js";
  const authStore = useAuthStore();
  const displayName = computed(() => {
    const firstName = authStore.user?.first_name?.trim() || '';
    const lastName = authStore.user?.last_name?.trim() || '';
    if (firstName && lastName) {
      return `${firstName.charAt(0).toUpperCase()}${firstName.slice(1).toLowerCase()} ${lastName.charAt(0).toUpperCase()}.`
    }
  });
  const displayMail = computed(() => {
    const mail = authStore.user?.email;
    return mail?.length > 17 ? mail.slice(0,17) + '...' : mail;
  });
  const sliceWord = computed(() => {
    const firstName = authStore.user?.first_name ?? ''
    const lastName = authStore.user?.last_name ?? ''
    const mail = authStore.user?.email ?? ''
    return (firstName && lastName
        ? firstName[0] + lastName[0]
        : mail[0]
    )?.toUpperCase()
  })
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
  <aside class="hidden lg:block">
    <div class="card p-4 sticky top-24">
      <div class="flex items-center gap-3 px-2 pb-4 mb-2 border-b border-ink-200">
        <div class="w-10 h-10 rounded-full gradient-primary flex items-center justify-center text-white font-bold">
          {{sliceWord}}
        </div>
        <div class="min-w-0">
          <p class="font-semibold text-ink-900 text-sm truncate">{{ displayName ?? 'Shoply User' }}</p>
          <p class="text-xs text-ink-500 truncate">{{ displayMail }}</p>
        </div>
      </div>
      <nav class="space-y-1">
        <router-link
          v-for="link in navLinks"
          :key="link.to"
          :to="link.to"
          class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors"
          :class="route.path === link.to
                ? 'bg-primary-50 text-primary-700'
                : 'text-ink-600 hover:bg-ink-50 hover:text-ink-900'"
        >
          <component :is="link.icon" class="w-4 h-4 shrink-0" />
          <span>{{ link.label }}</span>
        </router-link>
      </nav>
    </div>
  </aside>
</template>

<style scoped>

</style>
