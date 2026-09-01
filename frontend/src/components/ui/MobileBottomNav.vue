<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import {
  TransitionRoot, TransitionChild, Dialog, DialogPanel,
} from '@headlessui/vue'
import {
  Home, Grid3x3, ShoppingCart, User, X,
  Smartphone, Shirt, Sofa, Sparkles, Dumbbell, BookOpen,
  Gamepad2, ShoppingBasket, ChevronRight,
} from '@lucide/vue'

const router = useRouter()

const iconMap = { Smartphone, Shirt, Sofa, Sparkles, Dumbbell, BookOpen, Gamepad2, ShoppingBasket }

const navItems = [
  { id: 'home', label: 'Home', icon: Home, route: '/' },
  { id: 'category', label: 'Category', icon: Grid3x3, action: '/category' },
  { id: 'cart', label: 'Cart', icon: ShoppingCart, route: '/api/cart' },
  { id: 'account', label: 'My Shoply', icon: User, route: '/account/dashboard' },
]

const activeId = ref('home')

function syncActive() {
  const path = router.currentRoute.value.path
  if (path === '/') activeId.value = 'home'
  else if (path.startsWith('/shop') || path.startsWith('/product')) activeId.value = 'category'
  else if (path.startsWith('/cart') || path.startsWith('/checkout')) activeId.value = 'cart'
  else if (path.startsWith('/account')) activeId.value = 'account'
  else activeId.value = ''
}

syncActive()
router.afterEach(() => syncActive())

const showCategory = ref(false)

function handleNav(item) {
  if (item.action === 'category') {
    showCategory.value = true
    return
  }
  if (item.route) {
    activeId.value = item.id
    router.push(item.route)
  }
}
</script>

<template>
  <!-- Floating pill bottom navigation (mobile only) -->
  <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 pointer-events-none">
    <div class="px-4 pb-4 pt-2">
      <div
        class="pointer-events-auto mx-auto max-w-sm flex items-center justify-between gap-1 px-2 py-2 rounded-full
               bg-white/90 glass
               border border-white/80
               shadow-[0_8px_30px_-6px_rgba(0,0,0,0.18),0_4px_12px_-4px_rgba(0,0,0,0.1),inset_0_1px_1px_0_rgba(255,255,255,0.9)]"
      >
        <button
          v-for="item in navItems"
          :key="item.id"
          @click="handleNav(item)"
          class="relative flex flex-col items-center justify-center gap-0.5 rounded-full transition-all duration-300 ease-out-expo active:scale-90"
          :class="[
            activeId === item.id
              ? 'px-5 py-2.5 bg-gradient-to-br from-primary-500 to-primary-700 text-white shadow-md'
              : 'px-4 py-2.5 text-ink-500 hover:text-ink-900',
          ]"
          :aria-label="item.label"
        >
          <component :is="item.icon" class="w-5 h-5" />
          <span
            class="text-2xs font-semibold leading-none transition-all"
            :class="activeId === item.id ? 'opacity-100' : 'opacity-80'"
          >{{ item.label }}</span>

          <!-- Cart badge -->
          <span
            v-if="item.id === 'cart' && cartCount"
            class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-danger-500 text-white text-2xs font-bold rounded-full flex items-center justify-center ring-2 ring-white"
          >{{ cartCount }}</span>
        </button>
      </div>
    </div>
  </nav>

  <!-- Category Panel (bottom sheet) -->
  <Teleport to="body">
    <TransitionRoot :show="showCategory" as="template">
      <Dialog as="div" class="relative z-[60] md:hidden" @close="showCategory = false">
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
                  <h3 class="text-lg font-bold text-ink-900">Browse Categories</h3>
                  <button @click="showCategory = false" class="btn-ghost btn-icon" aria-label="Close">
                    <X class="w-5 h-5" />
                  </button>
                </div>

                <!-- Category grid -->
                <div class="p-4 grid grid-cols-2 gap-3 max-h-[60vh] overflow-y-auto">
                  <router-link
                    v-for="cat in categories"
                    :key="cat.slug"
                    :to="`/shop?cat=${cat.slug}`"
                    @click="showCategory = false"
                    class="flex items-center gap-3 p-3 rounded-2xl border border-ink-200/70 hover:border-primary-300 hover:bg-primary-50/40 transition-all active:scale-[0.98]"
                  >
                    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center shrink-0', cat.color]">
                      <component :is="iconMap[cat.icon]" class="w-5 h-5" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-semibold text-ink-900 clamp-1">{{ cat.name }}</p>
                      <p class="text-2xs text-ink-400">{{ cat.subcategories.length }} subcategories</p>
                    </div>
                    <ChevronRight class="w-4 h-4 text-ink-300 shrink-0" />
                  </router-link>
                </div>

                <!-- Footer -->
                <router-link
                  to="/shop"
                  @click="showCategory = false"
                  class="block text-center py-3.5 text-sm font-semibold text-primary-600 hover:bg-ink-50 border-t border-ink-200"
                >
                  View All Products
                </router-link>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </Teleport>
</template>
