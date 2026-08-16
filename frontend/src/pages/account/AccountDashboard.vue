<script setup>
import {computed, onMounted, ref} from 'vue'
import {
  Package, Clock, CheckCircle2, Heart, TrendingUp,
  ChevronRight, Star, Truck, RotateCcw, ShoppingBag, Bell,BellOff,Plus
} from '@lucide/vue'
import BaseBreadcrumb from "@/components/ui/BaseBreadcrumb.vue";
import BaseButton from '@/components/ui/BaseButton.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import {FreeMode} from "swiper/modules";
import 'swiper/css'
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
import dayjs from 'dayjs';
import ProductCard from "@/components/product/ProductCard.vue";
import {useTitle} from "@vueuse/core";
import DesktopDashboardSidebar from "@/components/ui/DesktopDashboardSidebar.vue";
import MobileDashboardSidebar from "@/components/ui/MobileDashboardSidebar.vue";
useTitle('Shoply | Profile Dashboard')
const user = ref({});
const isLoading = ref(true);
const recommendedProducts = ref({});
const wishlistCount = ref(0);
const stats = computed(() => {
  const total = user.value?.orders?.length
  const pending = user.value?.orders?.filter(o => o.status === 'processing' || o.status === 'shipped').length
  const delivered = user.value?.orders?.filter(o => o.status === 'delivered').length
  const wishlist = wishlistCount?.value;
  return [
    { label: 'Total Orders', value: total, icon: Package, color: 'primary', to: '/account/orders' },
    { label: 'Pending', value: pending, icon: Clock, color: 'warning', to: '/account/orders' },
    { label: 'Delivered', value: delivered, icon: CheckCircle2, color: 'success', to: '/account/orders' },
    { label: 'Wishlist', value: wishlist, icon: Heart, color: 'rose', to: '/wishlist' },
  ]
})
const recentOrders = computed(() => user.value?.orders);
const unreadNotifCount = computed(() => user.value?.notifications?.length);
const quickActions = [
  { label: 'Track Order', desc: 'Follow your active shipments', icon: Truck, to: '/account/orders' },
  { label: 'Buy Again', desc: 'Reorder your favorites', icon: RotateCcw, to: '/account/orders' },
  { label: 'Wishlist', desc: 'Your saved items', icon: Heart, to: '/wishlist' },
  { label: 'Reviews', desc: 'Share your feedback', icon: Star, to: '/account/orders' },
]
const statusBadge = {
  pending: 'badge-neutral',
  processing: 'badge-warning',
  shipped: 'badge-info',
  delivered: 'badge-success',
  cancelled: 'badge-danger',
}
const statColor = {
  primary: 'bg-primary-50 text-primary-600',
  warning: 'bg-warning-50 text-warning-600',
  success: 'bg-success-50 text-success-600',
  accent: 'bg-accent-50 text-accent-600',
  rose: 'bg-rose-50 text-rose-600',
}
onMounted(async () => {
  const token = localStorage.getItem('auth_token');
  try {
    const response = await api.get(endpoints.accountDashboard,{
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    user.value = response.data.data.user;
    recommendedProducts.value = response.data.data.recommendedProducts;
    wishlistCount.value = response.data.data.user.wishlist.items_count;
  }
  catch (error) {
    console.error(error)
  }
  finally {
    isLoading.value = false;
  }
});

const userFullName = computed(() => {
  const firstName = user.value?.first_name ?? '';
  const lastName = user.value?.last_name ?? '';
  const capitalize = (value) =>
    value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
  const name = `${capitalize(firstName)} ${capitalize(lastName)}`.trim();
  if (!name) return 'Shoply User';
  return name.length > 30
    ? name.slice(0,30) + '...' : name;
})
</script>

<template>
  <div class="section py-8 lg:py-12">
    <BaseBreadcrumb :items="['Profile', 'Dashboard']" class="mb-6" />
    <div class="grid lg:grid-cols-[260px_1fr] gap-8">
      <!-- Sidebar (desktop) / Tab bar (mobile) -->
      <DesktopDashboardSidebar/>
      <!-- Mobile tab bar -->
      <MobileDashboardSidebar/>
      <!-- Main content -->
      <div class="min-w-0 space-y-8">
        <!-- Welcome header -->
        <div class="card gradient-primary text-white p-6 sm:p-8 relative overflow-hidden">
          <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-white/10" />
          <div class="absolute -right-4 bottom-0 w-24 h-24 rounded-full bg-white/10" />
          <div class="relative">
            <p class="text-white/80 text-sm mb-1">Welcome back</p>
            <div v-if="isLoading" class="h-9 w-56 rounded-lg skeleton-dark mb-2"></div>
            <h1 v-else class="text-2xl sm:text-3xl font-bold mb-2">{{ userFullName }} 👋</h1>
            <div v-if="isLoading" class="h-5 w-72 rounded-md skeleton-dark"></div>
            <p v-else class="text-white/80 text-sm">Member since {{ dayjs(user?.created_at).format('MMM D, YYYY') }} · {{ user?.email }}</p>
            <div class="flex flex-wrap gap-3 mt-5">
              <BaseButton variant="accent" size="sm" to="/shop">
                <ShoppingBag class="w-4 h-4" />
                Continue Shopping
              </BaseButton>
              <!-- Notifications -->
              <div v-if="isLoading" class="h-9 w-44 rounded-xl skeleton-dark"></div>
              <BaseButton
                v-else-if="unreadNotifCount > 0"
                variant="ghost"
                size="sm"
                to="/account/notifications"
                class="!text-white hover:!bg-white/10"
              >
                <Bell class="w-4 h-4" />
                {{ unreadNotifCount }} new notifications
              </BaseButton>
              <BaseButton
                v-else
                variant="ghost"
                size="sm"
                class="!text-white hover:!bg-white/10"
              >
                <BellOff class="w-4 h-4" />
                No new notifications
              </BaseButton>
            </div>
          </div>
        </div>

        <!-- Stats cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <router-link
            v-for="stat in stats"
            :key="stat.label"
            :to="stat.to"
            class="card card-hover p-5 group"
          >
            <div class="flex items-center justify-between mb-3">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center" :class="statColor[stat.color]">
                <component :is="stat.icon" class="w-5 h-5" />
              </div>
              <ChevronRight class="w-4 h-4 text-ink-300 group-hover:text-ink-500 group-hover:translate-x-0.5 transition-all" />
            </div>
            <template v-if="isLoading">
              <!-- Number skeleton -->
              <div class="h-8 w-12 rounded-md skeleton mb-1"></div>
              <!-- Label skeleton -->
              <div class="h-4 w-20 rounded-md skeleton"></div>
            </template>
            <template v-else>
              <p class="text-2xl font-bold text-ink-900">{{ stat.value }}</p>
              <p class="text-sm text-ink-500">{{ stat.label }}</p>
            </template>
          </router-link>
        </div>

        <!-- Recent orders -->
        <div class="card overflow-hidden">
          <div class="flex items-center justify-between p-5 border-b border-ink-200">
            <div class="flex items-center gap-2">
              <Package class="w-5 h-5 text-primary-600" />
              <h2 class="section-title !text-lg">Recent Orders</h2>
            </div>
            <BaseButton variant="ghost" size="sm" to="/account/orders">
              View all
              <ChevronRight class="w-4 h-4" />
            </BaseButton>
          </div>
          <div v-if="recentOrders?.length" class="divide-y divide-ink-200">
            <div
              v-for="order in recentOrders"
              :key="order.id"
              class="flex items-center gap-4 p-4 hover:bg-ink-50 transition-colors"
            >
              <div class="w-12 h-12 rounded-xl bg-ink-100 flex items-center justify-center shrink-0">
                <Package class="w-5 h-5 text-ink-500" />
              </div>
              <div class="min-w-0 flex-1">
                <p class="font-semibold text-ink-900 text-sm truncate">{{ order?.order_number }}</p>
                <p class="text-xs text-ink-500">{{ dayjs(order?.created_at).format('YYYY-MM-DD HH:mm') }} · {{ order?.items?.length }} item{{ order?.items?.length > 1 ? 's' : '' }}</p>
              </div>
              <div class="hidden sm:block">
                <span :class="statusBadge[order?.status]" class="capitalize">{{ order?.status }}</span>
              </div>
              <div class="text-right shrink-0">
                <p class="font-semibold text-ink-900 text-sm">${{ order?.total }}</p>
                <router-link :to="`/account/orders/${order?.id}`" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                  Details
                </router-link>
              </div>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center py-12 px-6 text-center">
            <div class="relative mb-5">
              <div class="w-16 h-16 rounded-2xl bg-primary-50 flex items-center justify-center">
                <ShoppingBag class="w-7 h-7 text-primary-500" />
              </div>

              <div class="absolute -right-1 -bottom-1 w-6 h-6 rounded-full bg-white border border-ink-200 flex items-center justify-center">
                <Plus class="w-3.5 h-3.5 text-primary-500" />
              </div>
            </div>

            <h3 class="font-semibold text-ink-900 mb-1">
              No orders yet
            </h3>

            <p class="text-sm text-ink-500 max-w-xs leading-relaxed mb-5">
              Your recent orders will appear here once you make your first purchase.
            </p>

            <BaseButton variant="primary" size="sm" to="/shop">
              <ShoppingBag class="w-4 h-4" />
              Start Shopping
            </BaseButton>
          </div>
        </div>

        <!-- Quick actions -->
        <div>
          <h2 class="section-title !text-lg mb-4">Quick Actions</h2>
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <router-link
              v-for="action in quickActions"
              :key="action.label"
              :to="action.to"
              class="card card-hover p-5 group"
            >
              <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <component :is="action.icon" class="w-5 h-5" />
              </div>
              <p class="font-semibold text-ink-900 text-sm">{{ action.label }}</p>
              <p class="text-xs text-ink-500 mt-0.5 clamp-2">{{ action.desc }}</p>
            </router-link>
          </div>
        </div>

        <!-- Recommended products -->
        <div v-if="recommendedProducts.length">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
              <TrendingUp class="w-5 h-5 text-accent-500" />
              <h2 class="section-title !text-lg">Recommended For You</h2>
            </div>
            <BaseButton variant="ghost" size="sm" to="/shop">
              Browse all
              <ChevronRight class="w-4 h-4" />
            </BaseButton>
          </div>
          <Swiper
            :modules="[FreeMode]"
            :space-between="16"
            :free-mode="true"
            :grab-cursor="true"
            :free-mode-momentum="true"
            :breakpoints="{
            0: {slidesPerView: 1.2},
            640: {slidesPerView: 3},
            768: {slidesPerView: 3},
            1024: {slidesPerView: 4},
            1280: {slidesPerView: 4}
          }"
            class="recommended-swiper"
          >
            <SwiperSlide
              v-for="product in recommendedProducts"
              :key="product.id"
            >
              <ProductCard :product="product" />
            </SwiperSlide>
          </Swiper>
        </div>
      </div>
    </div>
  </div>
</template>
