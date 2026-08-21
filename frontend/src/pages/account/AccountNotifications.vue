<script setup>
import {ref, onMounted, watch, nextTick} from 'vue'
import {
  Bell, Package, Tag, ShieldAlert, Star, CheckCheck, Trash2,
  Settings, MailOpen,
} from '@lucide/vue'
import BaseBreadcrumb from "@/components/ui/BaseBreadcrumb.vue";
import BaseButton from '@/components/ui/BaseButton.vue';
import DesktopDashboardSidebar from "@/components/ui/DesktopDashboardSidebar.vue";
import MobileDashboardSidebar from "@/components/ui/MobileDashboardSidebar.vue";
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
import Pagination from "@/components/paginations/Pagination.vue";
import dayjs from "dayjs";
import relativeTime from 'dayjs/plugin/relativeTime';
dayjs.extend(relativeTime);
const notifications = ref({});
const currentPage = ref(1);
const totalPages = ref(1);
const notifList = ref(null);
const unreadCount = ref(0);
const filters = [
  { key: 'all', label: 'All' },
  { key: 'unread', label: 'Unread' },
  { key: 'order', label: 'Orders' },
  { key: 'promotion', label: 'Promotions' },
  { key: 'system', label: 'System' },
  { key: 'security', label: 'Security' },
  { key: 'review', label: 'Review' },
  { key: 'global', label: 'Global' },
]
const activeFilter = ref('all')
const notifIconMap = {
  order: Package,
  promotion: Tag,
  system: Settings,
  security: ShieldAlert,
  review: Star,
}
const typeColor = {
  order: 'bg-primary-50 text-primary-600',
  promotion: 'bg-accent-50 text-accent-600',
  system: 'bg-blue-50 text-blue-600',
  security: 'bg-danger-50 text-danger-600',
  review: 'bg-warning-50 text-warning-600',
}
const fetchNotifications = async () => {
  const token = localStorage.getItem('auth_token');
  const params = {
    page: currentPage.value,
  };
  if (activeFilter.value !== 'all') {
    params.type = activeFilter.value
  }
  try {
    const response = await api.get(endpoints.notifications,
      {
        params,
        headers: {
          Authorization: `Bearer ${token}`,
        }
      }
    )
    notifications.value = response.data.data.notifications.data;
    currentPage.value = response.data.data.notifications.current_page;
    totalPages.value = response.data.data.notifications.last_page;
    unreadCount.value = response.data.data.unreadCount;
  }
  catch (error) {
    console.error(error);
  }
}
watch(
  currentPage,
  async () => {
    await fetchNotifications();
    await nextTick();
    notifList.value?.scrollIntoView({
      behavior: 'smooth',
      block: 'start',
    });
  }
)
watch(
  activeFilter,
  async () => {
    if (currentPage.value !== 1){
      currentPage.value = 1;
    }
    else{
      await fetchNotifications();
      await nextTick();
      notifList.value?.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
      });
    }
  }
)
onMounted(async () => {
 await fetchNotifications()
})
</script>

<template>
  <div ref="notifList" class="section py-8 lg:py-12">
    <BaseBreadcrumb :items="['Account', 'Notifications']" class="mb-6" />
    <div class="grid lg:grid-cols-[260px_1fr] gap-8">
      <!-- Sidebar (desktop) -->
      <DesktopDashboardSidebar/>
      <!-- Mobile tab bar -->
      <MobileDashboardSidebar/>
      <!-- Main content -->
      <div class="min-w-0 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <div class="flex items-center gap-2">
              <h1 class="text-2xl font-bold text-ink-900">Notifications</h1>
              <span v-if="unreadCount" class="badge-primary">{{ unreadCount }} unread</span>
            </div>
            <p class="text-ink-500 mt-1">Stay updated on your orders, deals, and account activity.</p>
          </div>
          <BaseButton variant="secondary" size="md" :disabled="!unreadCount" @click="markAllRead">
            <CheckCheck class="w-4 h-4" />
            Mark all as read
          </BaseButton>
        </div>
        <!-- Filter tabs -->
        <div class="card p-4">
          <div class="flex gap-2 overflow-x-auto no-scrollbar">
            <button
              v-for="f in filters"
              :key="f.key"
              class="chip shrink-0"
              :class="activeFilter === f.key && 'chip-active'"
              @click="activeFilter = f.key"
            >
              {{ f.label }}
            </button>
          </div>
        </div>
        <!-- Notification list -->
        <div v-if="notifications.length" class="card divide-y divide-ink-200 overflow-hidden">
          <div
            v-for="n in notifications"
            :key="n.id"
            class="flex items-start gap-4 p-4 hover:bg-ink-50 transition-colors group"
            :class="!n.read_at && 'bg-primary-50/30'"
          >
            <!-- Icon -->
            <div
              class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
              :class="typeColor[n.type] || 'bg-ink-100 text-ink-600'"
            >
              <component :is="notifIconMap[n.type] || Bell" class="w-5 h-5" />
            </div>
            <!-- Content -->
            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-2">
                <p class="font-semibold text-ink-900 text-sm">{{ n.title }}</p>
                <span v-if="!n.read_at" class="w-2 h-2 rounded-full bg-primary-500 shrink-0" />
              </div>
              <p class="text-sm text-ink-600 mt-0.5 clamp-2">{{ n.body }}</p>
              <p class="text-xs text-ink-400 mt-1.5">{{ dayjs(n.created_at).fromNow() }}</p>
            </div>
            <!-- Actions -->
            <div class="flex items-center gap-1 shrink-0">
              <button
                v-if="!n.read_at"
                class="p-2 rounded-lg text-ink-400 hover:text-primary-600 hover:bg-primary-50 transition-colors"
                title="Mark as read"
              >
                <MailOpen class="w-4 h-4" />
              </button>
              <button
                class="p-2 rounded-lg text-ink-400 hover:text-danger-600 hover:bg-danger-50 transition-colors"
                title="Delete"
              >
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
        <!-- Empty state -->
        <div v-else class="card p-12 text-center">
          <div class="w-16 h-16 rounded-full bg-ink-100 flex items-center justify-center mx-auto mb-4">
            <Bell class="w-8 h-8 text-ink-400" />
          </div>
          <h3 class="text-lg font-semibold text-ink-900 mb-1">No notifications</h3>
          <p class="text-sm text-ink-500 mb-5 max-w-sm mx-auto">
            {{ activeFilter === 'unread' ? 'You\'re all caught up! No unread notifications.' : 'You don\'t have any notifications in this category.' }}
          </p>
          <BaseButton variant="secondary" size="md" to="/account/settings">
            <Settings class="w-4 h-4" />
            Notification Settings
          </BaseButton>
        </div>
        <Pagination
          v-model="currentPage"
          :total-pages="totalPages"
        />
      </div>
    </div>
  </div>
</template>
