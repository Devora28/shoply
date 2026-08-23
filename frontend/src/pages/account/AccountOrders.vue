<script setup>
import {ref, onMounted, watch, nextTick} from 'vue'
import {
  Package, ChevronRight, Filter, Calendar, Search, ShoppingBag,
} from '@lucide/vue'
import BaseBreadcrumb from "@/components/ui/BaseBreadcrumb.vue";
import BaseButton from '@/components/ui/BaseButton.vue';
import BaseInput from '@/components/ui/BaseInput.vue';
import DesktopDashboardSidebar from "@/components/ui/DesktopDashboardSidebar.vue";
import MobileDashboardSidebar from "@/components/ui/MobileDashboardSidebar.vue";
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
import dayjs from "dayjs";
import Pagination from "@/components/paginations/Pagination.vue";
const ordersList = ref(null);
const userOrders = ref({});
const counts = ref(null);
const currentPage = ref(1);
const totalPages = ref(1);
const filters = [
  { key: 'all', label: 'All' },
  { key: 'processing', label: 'Processing' },
  { key: 'shipped', label: 'Shipped' },
  { key: 'delivered', label: 'Delivered' },
  { key: 'cancelled', label: 'Cancelled' },
]
const activeFilter = ref('all');
const searchQuery = ref('');
const sortOrder = ref('newest');
const isLoading = ref(false);
const statusBadge = {
  pending: 'badge-neutral',
  processing: 'badge-warning',
  shipped: 'badge-info',
  delivered: 'badge-success',
  cancelled: 'badge-danger',
}
const fetchFilters = async () => {
  isLoading.value = true;
  const token = localStorage.getItem('auth_token');
  try {
    const params = {
      page: currentPage.value,
      sort: sortOrder.value,
    }
    if (activeFilter.value !== 'all') {
      params.status = activeFilter.value
    }
    if (searchQuery.value.trim()) {
      params.search = searchQuery.value.trim()
    }
    const response = await api.get(endpoints.orders,{
      headers: {
        Authorization: `Bearer ${token}`,
      },
      params
    });
    userOrders.value = response.data.data;
    counts.value = response.data.counts;
    currentPage.value = response.data.meta.current_page;
    totalPages.value = response.data.meta.last_page;
  }
  catch (error) {
    console.error(error)
  }
  finally {
    isLoading.value = false;
  }
}
watch(
  currentPage,
  async () => {
    await fetchFilters();
    await nextTick();
    ordersList.value?.scrollIntoView({
      behavior: 'smooth',
      block: 'start',
    });
  }
)
watch(
  [activeFilter,searchQuery,sortOrder],
  async () => {
    if (currentPage.value !== 1) {
      currentPage.value = 1;
    } else {
      await fetchFilters();
      await nextTick();
      ordersList.value?.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
      });
    }
  }
)
onMounted(async () => {
  await fetchFilters();
});
</script>

<template>
  <div class="section py-8 lg:py-12">
    <BaseBreadcrumb :items="['Account', 'My Orders']" class="mb-6" />

    <div ref="ordersList" class="grid lg:grid-cols-[260px_1fr] gap-8">
      <!-- Sidebar (desktop) -->
      <DesktopDashboardSidebar/>
      <!-- Mobile tab bar -->
      <MobileDashboardSidebar/>
      <!-- Main content -->
      <div class="min-w-0 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold text-ink-900">My Orders</h1>
            <p class="text-ink-500 mt-1">Track, review, and manage all your purchases.</p>
          </div>
          <BaseButton variant="primary" size="md" to="/shop">
            <ShoppingBag class="w-4 h-4" />
            Shop Now
          </BaseButton>
        </div>

        <!-- Filter tabs + search -->
        <div class="card p-4 space-y-4">
          <div class="flex gap-2 overflow-x-auto no-scrollbar">
            <button
              v-for="f in filters"
              :key="f.key"
              class="chip shrink-0"
              :class="activeFilter === f.key && 'chip-active'"
              @click="activeFilter = f.key"
            >
              {{ f.label }}
              <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold"
                    :class="activeFilter === f.key ? 'bg-white/20' : 'bg-ink-200 text-ink-600'"
              >{{ counts ? counts[f.key] : 0 }}</span>
            </button>
          </div>
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
              <BaseInput
                v-model="searchQuery"
                placeholder="Search by order ID..."
                :icon="Search"
                size="md"
              />
            </div>
            <div class="flex items-center gap-2">
              <Filter class="w-4 h-4 text-ink-400 shrink-0" />
              <select v-model="sortOrder" class="input">
                <option value="newest">Newest first</option>
                <option value="oldest">Oldest first</option>
              </select>
            </div>
          </div>
        </div>
        <!-- Orders list -->
        <div v-if="userOrders.length" class="space-y-4">
          <div
            v-for="order in userOrders"
            :key="order.id"
            class="card card-hover p-5"
          >
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
              <!-- Order info -->
              <div class="flex items-start gap-3 lg:min-w-[200px]">
                <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                  <Package class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                  <p class="font-semibold text-ink-900 text-sm">{{ order.order_number }}</p>
                  <div class="flex items-center gap-1.5 text-xs text-ink-500 mt-0.5">
                    <Calendar class="w-3.5 h-3.5" />
                    {{ dayjs(order.created_at).format('YYYY-MM-DD') }}
                  </div>
                </div>
              </div>
              <!-- Item thumbnails -->
              <div class="flex items-center gap-2 flex-1 min-w-0">
                <div class="flex -space-x-3">
                  <div
                    v-for="item in order.items?.slice(0, 3)"
                    :key="item.id"
                    class="w-12 h-12 rounded-lg overflow-hidden border-2 border-white shadow-sm shrink-0 bg-ink-100"
                  >
                    <img :src="item.product?.image" :alt="item.product?.name" loading="lazy" class="w-full h-full object-cover" />
                  </div>
                </div>
                <div class="min-w-0">
                  <p class="text-sm text-ink-700 truncate">{{ order.items[0]?.product.name }}</p>
                  <p class="text-xs text-ink-500">
                    {{ order.items.length }} item{{ order.items.length > 1 ? 's' : '' }}
                    <span v-if="order.items.length > 1">  +{{ order.items.length - 1 }} more</span>
                  </p>
                </div>
              </div>
              <!-- Status + total + action -->
              <div class="flex items-center justify-between gap-4 lg:justify-end">
                <span :class="statusBadge[order.status]" class="capitalize shrink-0">{{ order.status }}</span>
                <div class="text-right shrink-0">
                  <p class="text-xs text-ink-500">Total</p>
                  <p class="font-bold text-ink-900">${{ order.total }}</p>
                </div>
                <BaseButton
                  variant="secondary"
                  size="sm"
                  :to="`/account/orders/${order.id}`"
                >
                  View Details
                  <ChevronRight class="w-4 h-4" />
                </BaseButton>
              </div>
            </div>
          </div>
          <Pagination
            v-model="currentPage"
            :total-pages="totalPages"
            :disabled="isLoading"
          />
        </div>
        <!-- Empty state -->
        <div v-else class="card p-12 text-center">
          <div class="w-16 h-16 rounded-full bg-ink-100 flex items-center justify-center mx-auto mb-4">
            <Package class="w-8 h-8 text-ink-400" />
          </div>
          <h3 class="text-lg font-semibold text-ink-900 mb-1">No orders found</h3>
          <p class="text-sm text-ink-500 mb-5 max-w-sm mx-auto">
            {{ searchQuery ? 'No orders match your search. Try a different query.' : 'You have no orders in this category yet.' }}
          </p>
          <BaseButton variant="primary" size="md" to="/shop">
            <ShoppingBag class="w-4 h-4" />
            Start Shopping
          </BaseButton>
        </div>
      </div>
    </div>
  </div>
</template>
