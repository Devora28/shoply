<script setup>
import { ref, computed, watch, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import {
  TransitionRoot, TransitionChild, Dialog, DialogPanel,
} from '@headlessui/vue'
import {
  Search, Bell, X, ArrowLeft, Star,
  TrendingUp, ChevronRight, Package, Truck, Tag,
} from '@lucide/vue'
const router = useRouter()
// ---- Search overlay ----
const showSearch = ref(false)
const localQuery = ref('')
const searchInputRef = ref(null)
const isSearching = ref(false)
let searchTimer = null
function openSearch() {
  localQuery.value = ''
  isSearching.value = false
  showSearch.value = true
  nextTick(() => searchInputRef.value?.focus())
}
function closeSearch() {
  showSearch.value = false
}
function submitSearch() {
  if (localQuery.value.trim()) {
    /*searchQuery.value = localQuery.value*/
    router.push({ path: '/search', query: { q: localQuery.value } })
    showSearch.value = false
  }
}

// Simulated loading state for UI demonstration
watch(localQuery, (val) => {
  if (searchTimer) clearTimeout(searchTimer)
  if (!val.trim()) {
    isSearching.value = false
    return
  }
  isSearching.value = true
  searchTimer = setTimeout(() => {
    isSearching.value = false
  }, 650)
})

// Mock product results for UI demonstration
const mockProducts = [
  { id: 1, name: 'Aurora Wireless Noise-Cancel Headphones', price: 249, oldPrice: 349, rating: 4.7, image: 'https://images.pexels.com/photos/788946/pexels-photo-788946.jpeg?auto=compress&cs=tinysrgb&w=200' },
  { id: 2, name: 'Pulse Pro Smartwatch Series 7', price: 299, oldPrice: 399, rating: 4.5, image: 'https://images.pexels.com/photos/7974872/pexels-photo-7974872.jpeg?auto=compress&cs=tinysrgb&w=200' },
  { id: 3, name: 'ZenBook Duo 14 Ultra-Slim Laptop', price: 1299, oldPrice: 1599, rating: 4.8, image: 'https://images.pexels.com/photos/1841841/pexels-photo-1841841.jpeg?auto=compress&cs=tinysrgb&w=200' },
  { id: 4, name: 'Galaxy X14 Smartphone 256GB', price: 799, oldPrice: 899, rating: 4.4, image: 'https://images.pexels.com/photos/614117/pexels-photo-614117.jpeg?auto=compress&cs=tinysrgb&w=200' },
  { id: 5, name: 'Mirrorless Camera 4K 24MP', price: 1099, oldPrice: 1299, rating: 4.6, image: 'https://images.pexels.com/photos/777001/pexels-photo-777001.jpeg?auto=compress&cs=tinysrgb&w=200' },
  { id: 6, name: 'Studio Monitor Speakers Pair', price: 449, oldPrice: 599, rating: 4.3, image: 'https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?auto=compress&cs=tinysrgb&w=200' },
]

const searchResults = computed(() => {
  const q = localQuery.value.trim().toLowerCase()
  if (!q) return mockProducts
  return mockProducts.filter(p => p.name.toLowerCase().includes(q))
})

const trendingSearches = ['Headphones', 'Smartwatch', 'Laptop', 'Camera', 'Sneakers']

// ---- Notification panel ----
const showNotifs = ref(false)

const notifications = [
  { id: 1, icon: Truck, title: 'Order shipped', body: 'ORD-2025-0810 is on the way', time: '2h', unread: true },
  { id: 2, icon: Tag, title: 'Flash Sale 40% off', body: 'Electronics sale ends in 6h', time: '5h', unread: true },
  { id: 3, icon: Bell, title: 'Review your purchase', body: 'Share your thoughts!', time: '1d', unread: true },
  { id: 4, icon: Package, title: 'Order delivered', body: 'ORD-2025-0823 delivered', time: '2d', unread: false },
]
const unreadCount = notifications.filter(n => n.unread).length

// Close overlays on route change
watch(() => router.currentRoute.value.path, () => {
  showSearch.value = false
  showNotifs.value = false
})
</script>

<template>
  <!-- Mobile Header — shares the bottom nav's glass + layered-shadow language -->
  <header
    class="md:hidden sticky top-0 z-40 glass bg-white/90 border-b border-white/80
           shadow-[0_4px_20px_-4px_rgba(0,0,0,0.10),0_2px_8px_-2px_rgba(0,0,0,0.06),inset_0_1px_1px_0_rgba(255,255,255,0.9)]"
  >
    <div class="flex items-center gap-2.5 px-4 h-14">
      <!-- Search trigger (pill-shaped to match bottom nav) -->
      <button
        @click="openSearch"
        class="flex-1 flex items-center gap-2.5 h-10 px-4 bg-ink-100/80 rounded-full text-sm text-ink-400
               transition-all duration-300 ease-out-expo
               hover:bg-ink-200/60 active:scale-[0.98]
               border border-ink-200/40"
      >
        <Search class="w-4 h-4 shrink-0" />
        <span class="truncate">Search products...</span>
      </button>

      <!-- Notification icon (pill-shaped to match bottom nav) -->
      <button
        @click="showNotifs = true"
        class="relative shrink-0 w-10 h-10 flex items-center justify-center rounded-full text-ink-600
               bg-ink-100/60 border border-ink-200/40
               hover:bg-ink-200/60 active:scale-90
               transition-all duration-300 ease-out-expo"
        aria-label="Notifications"
      >
        <Bell class="w-5 h-5" />
        <span
          v-if="unreadCount"
          class="absolute top-1 right-1 w-4 h-4 bg-danger-500 text-white text-2xs font-bold rounded-full flex items-center justify-center ring-2 ring-white"
        >{{ unreadCount }}</span>
      </button>
    </div>
  </header>

  <!-- Full-screen Search Overlay -->
  <Teleport to="body">
    <TransitionRoot :show="showSearch" as="template">
      <Dialog as="div" class="relative z-[60] md:hidden" @close="closeSearch">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out-expo"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-white" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full flex-col">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out-expo"
              enter-from="opacity-0 translate-y-2"
              enter-to="opacity-100 translate-y-0"
              leave="duration-200 ease-in"
              leave-from="opacity-100 translate-y-0"
              leave-to="opacity-0 translate-y-2"
            >
              <DialogPanel class="flex flex-col h-screen">
                <!-- Search bar row -->
                <div class="flex items-center gap-2.5 px-4 h-14 border-b border-ink-200 shrink-0">
                  <button @click="closeSearch" class="btn-ghost btn-icon shrink-0" aria-label="Back">
                    <ArrowLeft class="w-5 h-5" />
                  </button>
                  <div class="relative flex-1">
                    <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" />
                    <input
                      ref="searchInputRef"
                      v-model="localQuery"
                      type="text"
                      placeholder="Search products, brands..."
                      class="w-full pl-10 pr-4 h-10 text-sm bg-ink-100 rounded-full outline-none focus:bg-white focus:ring-2 focus:ring-primary-500/20 transition-all"
                      @keydown.enter="submitSearch"
                    />
                  </div>
                  <button
                    v-if="localQuery"
                    @click="localQuery = ''"
                    class="btn-ghost btn-icon shrink-0"
                    aria-label="Clear"
                  >
                    <X class="w-4 h-4" />
                  </button>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto px-4 py-4">
                  <!-- Empty state (no query yet) -->
                  <div v-if="!localQuery.trim()">
                    <div class="mb-6">
                      <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-3">Trending Searches</p>
                      <div class="flex flex-wrap gap-2">
                        <button
                          v-for="t in trendingSearches"
                          :key="t"
                          @click="localQuery = t"
                          class="chip"
                        >
                          <TrendingUp class="w-3 h-3" /> {{ t }}
                        </button>
                      </div>
                    </div>

                    <p class="text-sm font-semibold text-ink-900 mb-3">Popular Products</p>
                    <div class="space-y-2.5">
                      <router-link
                        v-for="p in mockProducts"
                        :key="p.id"
                        :to="`/product/${p.id}`"
                        @click="closeSearch"
                        class="flex items-center gap-3 p-2.5 rounded-2xl bg-white border border-ink-200/70 hover:border-primary-300 hover:shadow-card transition-all active:scale-[0.99]"
                      >
                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-ink-100 shrink-0">
                          <img :src="p.image" :alt="p.name" class="w-full h-full object-cover" />
                        </div>
                        <div class="flex-1 min-w-0">
                          <p class="text-sm font-medium text-ink-900 clamp-2">{{ p.name }}</p>
                          <div class="flex items-center gap-1 mt-0.5">
                            <Star class="w-3 h-3 fill-warning-400 text-warning-400" />
                            <span class="text-xs text-ink-500">{{ p.rating }}</span>
                          </div>
                          <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-bold text-primary-700">{{ p.price }}</span>
                            <span v-if="p.oldPrice" class="text-xs text-ink-400 line-through">{{ p.oldPrice }}</span>
                          </div>
                        </div>
                        <ChevronRight class="w-4 h-4 text-ink-300 shrink-0" />
                      </router-link>
                    </div>
                  </div>

                  <!-- Loading state -->
                  <div v-else-if="isSearching">
                    <p class="text-sm font-semibold text-ink-900 mb-3">Searching...</p>
                    <div class="space-y-2.5">
                      <div v-for="i in 4" :key="i" class="flex items-center gap-3 p-2.5 rounded-2xl bg-white border border-ink-200/70">
                        <div class="w-14 h-14 rounded-xl skeleton shrink-0"></div>
                        <div class="flex-1 space-y-2">
                          <div class="h-4 w-3/4 rounded skeleton"></div>
                          <div class="h-3 w-1/2 rounded skeleton"></div>
                          <div class="h-4 w-1/4 rounded skeleton"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Results -->
                  <div v-else-if="searchResults.length">
                    <div class="flex items-center justify-between mb-3">
                      <p class="text-sm font-semibold text-ink-900">{{ searchResults.length }} results</p>
                    </div>
                    <div class="space-y-2.5">
                      <router-link
                        v-for="p in searchResults"
                        :key="p.id"
                        :to="`/product/${p.id}`"
                        @click="closeSearch"
                        class="flex items-center gap-3 p-2.5 rounded-2xl bg-white border border-ink-200/70 hover:border-primary-300 hover:shadow-card transition-all active:scale-[0.99]"
                      >
                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-ink-100 shrink-0">
                          <img :src="p.image" :alt="p.name" class="w-full h-full object-cover" />
                        </div>
                        <div class="flex-1 min-w-0">
                          <p class="text-sm font-medium text-ink-900 clamp-2">{{ p.name }}</p>
                          <div class="flex items-center gap-1 mt-0.5">
                            <Star class="w-3 h-3 fill-warning-400 text-warning-400" />
                            <span class="text-xs text-ink-500">{{ p.rating }}</span>
                          </div>
                          <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-bold text-primary-700">{{ formatPrice(p.price) }}</span>
                            <span v-if="p.oldPrice" class="text-xs text-ink-400 line-through">{{ formatPrice(p.oldPrice) }}</span>
                          </div>
                        </div>
                        <ChevronRight class="w-4 h-4 text-ink-300 shrink-0" />
                      </router-link>
                    </div>
                  </div>

                  <!-- No results state -->
                  <div v-else class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-14 h-14 rounded-full bg-ink-100 flex items-center justify-center mb-3">
                      <Search class="w-6 h-6 text-ink-400" />
                    </div>
                    <p class="text-sm font-medium text-ink-900 mb-1">No products found</p>
                    <p class="text-xs text-ink-500">Try a different search term</p>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </Teleport>

  <!-- Notification Panel (bottom sheet matching the category panel's style) -->
  <Teleport to="body">
    <TransitionRoot :show="showNotifs" as="template">
      <Dialog as="div" class="relative z-[60] md:hidden" @close="showNotifs = false">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-ink-950/50 backdrop-blur-sm" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-end">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out-expo"
              enter-from="opacity-0 translate-y-8"
              enter-to="opacity-100 translate-y-0"
              leave="duration-200 ease-in"
              leave-from="opacity-100 translate-y-0"
              leave-to="opacity-0 translate-y-8"
            >
              <DialogPanel class="w-full bg-white rounded-t-3xl shadow-2xl">
                <!-- Handle -->
                <div class="flex justify-center pt-3 pb-1">
                  <div class="w-10 h-1 rounded-full bg-ink-200"></div>
                </div>

                <!-- Header -->
                <div class="flex items-center justify-between px-5 py-3 border-b border-ink-200">
                  <div class="flex items-center gap-2">
                    <h3 class="text-lg font-bold text-ink-900">Notifications</h3>
                    <span v-if="unreadCount" class="badge-primary text-2xs">{{ unreadCount }} new</span>
                  </div>
                  <button @click="showNotifs = false" class="btn-ghost btn-icon" aria-label="Close">
                    <X class="w-5 h-5" />
                  </button>
                </div>

                <!-- List -->
                <div class="max-h-[60vh] overflow-y-auto">
                  <div
                    v-for="n in notifications"
                    :key="n.id"
                    class="flex items-start gap-3 px-5 py-3.5 border-b border-ink-100 last:border-0 hover:bg-ink-50 transition-colors"
                    :class="n.unread ? 'bg-primary-50/40' : ''"
                  >
                    <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                      <component :is="n.icon" class="w-4 h-4 text-primary-600" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-ink-900">{{ n.title }}</p>
                      <p class="text-xs text-ink-500 clamp-2 mt-0.5">{{ n.body }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1 shrink-0">
                      <span class="text-2xs text-ink-400">{{ n.time }}</span>
                      <span v-if="n.unread" class="w-2 h-2 rounded-full bg-primary-500"></span>
                    </div>
                  </div>
                </div>

                <!-- Footer -->
                <router-link
                  to="/account/notifications"
                  @click="showNotifs = false"
                  class="block text-center py-3.5 text-sm font-semibold text-primary-600 hover:bg-ink-50 border-t border-ink-200"
                >
                  View All Notifications
                </router-link>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </Teleport>
</template>
