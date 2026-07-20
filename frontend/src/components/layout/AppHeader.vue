<script setup>
import {ref} from 'vue';
import {TransitionRoot, TransitionChild, Dialog, DialogPanel,} from '@headlessui/vue';
import {
  Shirt,Dumbbell,BookOpen,Gamepad2,ChevronDown,Menu,Search,Heart,Bell,User,ShoppingCart,X,Package, MapPin,Settings,LogOut,ChevronRight,Truck,Tag,Home,Info,Mail,HelpCircle,ShoppingBag,Trash2,HeartPulse,House,Laptop
} from "@lucide/vue";
import {useCategoryStore} from "@/stores/category.js";
const mobileMenuOpen = ref(false)
const iconMap = { Laptop, Shirt, House, HeartPulse, Dumbbell, BookOpen, Gamepad2 }
const hoveredCategory = ref(null)
const showMegaMenu = ref(false)
const showCartDropdown = ref(false)
const showProfileDropdown = ref(false)
const showNotifDropdown = ref(false)
const notifications = [
  { id: 1, icon: Truck, title: 'Order shipped', body: 'ORD-2025-0810 is on the way', time: '2h', unread: true },
  { id: 2, icon: Tag, title: 'Flash Sale 40% off', body: 'Electronics sale ends in 6h', time: '5h', unread: true },
  { id: 3, icon: Bell, title: 'Review your purchase', body: 'Share your thoughts!', time: '1d', unread: true },
  { id: 4, icon: Package, title: 'Order delivered', body: 'ORD-2025-0823 delivered', time: '2d', unread: false },
]
const unreadCount = notifications.filter(n => n.unread).length;
function closeAll() {
  showMegaMenu.value = false
  showCartDropdown.value = false
  showProfileDropdown.value = false
  showNotifDropdown.value = false
}
const categoryStore = useCategoryStore();
</script>

<template>
  <!-- Announcement Bar -->
  <div class="bg-ink-900 text-white text-xs sm:text-sm py-2 px-4 text-center">
    <span class="font-medium">Free shipping on orders over $50</span>
    <span class="hidden sm:inline mx-2 text-ink-600">|</span>
    <span class="hidden sm:inline text-ink-300">Use code <span class="font-bold text-accent-400">SHOPLY10</span> for 10% off your first order</span>
  </div>
  <!-- Main Header -->
  <header class="sticky top-0 z-40 bg-white border-b border-ink-200 shadow-sm">
    <div class="section">
      <div class="flex items-center gap-4 h-16">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2 shrink-0">
          <div class="w-9 h-9 rounded-xl gradient-primary flex items-center justify-center">
            <ShoppingCart class="w-5 h-5 text-white" />
          </div>
          <span class="text-xl font-bold text-ink-900 hidden sm:block">Shoply</span>
        </router-link>
        <!-- Search (desktop) -->
        <div class="hidden md:flex flex-1 max-w-2xl">
          <div class="relative w-full">
            <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-ink-400" />
            <input
              type="text"
              placeholder="Search for products, brands and categories..."
              class="w-full pl-12 pr-4 py-2.5 text-sm bg-ink-100 border border-transparent rounded-xl focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all outline-none"
            />
          </div>
        </div>
        <!-- Actions -->
        <div class="flex items-center gap-1 sm:gap-2 ml-auto">
          <!-- Notifications -->
          <div class="relative hidden sm:block">
            <button
              class="relative btn-ghost btn-icon"
              @click="showNotifDropdown = !showNotifDropdown; showCartDropdown = false; showProfileDropdown = false"
              aria-label="Notifications"
            >
              <Bell class="w-5 h-5" />
              <span v-if="unreadCount" class="absolute top-1 right-1 w-4 h-4 bg-danger-500 text-white text-2xs font-bold rounded-full flex items-center justify-center">{{ unreadCount }}</span>
            </button>
            <Transition name="dropdown">
              <div v-if="showNotifDropdown" class="absolute right-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-dropdown border border-ink-200 overflow-hidden z-50">
                <div class="flex items-center justify-between px-4 py-3 border-b border-ink-200">
                  <span class="font-semibold text-ink-900">Notifications</span>
                  <span class="badge-primary text-2xs">{{ unreadCount }} new</span>
                </div>
                <div class="max-h-80 overflow-y-auto">
                  <div v-for="n in notifications" :key="n.id" class="flex items-start gap-3 px-4 py-3 hover:bg-ink-50 cursor-pointer border-b border-ink-100 last:border-0">
                    <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                      <component :is="n.icon" class="w-4 h-4 text-primary-600" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-ink-900">{{ n.title }}</p>
                      <p class="text-xs text-ink-500 clamp-1">{{ n.body }}</p>
                    </div>
                    <span class="text-2xs text-ink-400 shrink-0">{{ n.time }}</span>
                  </div>
                </div>
                <router-link to="/account/notifications" class="block text-center py-3 text-sm font-medium text-primary-600 hover:bg-ink-50" @click="showNotifDropdown = false">View all</router-link>
              </div>
            </Transition>
          </div>

          <!-- Wishlist -->
          <router-link to="/wishlist" class="relative btn-ghost btn-icon hidden sm:flex" aria-label="Wishlist">
            <Heart class="w-5 h-5" />
            <span  class="absolute top-1 right-1 w-4 h-4 bg-danger-500 text-white text-2xs font-bold rounded-full flex items-center justify-center">5</span>
          </router-link>

          <!-- Cart -->
          <div class="relative">
            <button
              class="relative btn-ghost btn-icon"
              @click="showCartDropdown = !showCartDropdown; showProfileDropdown = false; showNotifDropdown = false"
              aria-label="Cart"
            >
              <ShoppingCart class="w-5 h-5" />
              <span class="absolute top-1 right-1 w-4 h-4 bg-primary-600 text-white text-2xs font-bold rounded-full flex items-center justify-center">5</span>
            </button>
            <Transition name="dropdown">
              <div v-if="showCartDropdown" class="absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-dropdown border border-ink-200 overflow-hidden z-50">
                <div class="flex items-center justify-between px-4 py-3 border-b border-ink-200">
                  <span class="font-semibold text-ink-900">Cart (5)</span>
                  <button class="text-ink-400 hover:text-ink-900" @click="showCartDropdown = false"><X class="w-4 h-4" /></button>
                </div>
                <div class="max-h-80 overflow-y-auto p-3 space-y-2">
                  <div class="flex gap-3 p-2 rounded-xl hover:bg-ink-50">
                    <img src="" alt="item.name" class="w-14 h-14 rounded-lg object-cover shrink-0" />
                    <div class="flex-1 min-w-0">
                      <router-link to="" class="text-sm font-medium text-ink-900 clamp-1 hover:text-primary-600" @click="showCartDropdown = false">ssss</router-link>
                      <p class="text-xs text-ink-500">sss - Qty: 2</p>
                      <div class="flex items-center justify-between mt-1">
                        <span class="text-sm font-bold text-primary-700">55</span>
                        <button class="text-ink-400 hover:text-danger-500">
                          <Trash2 class="w-3.5 h-3.5" />
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <!--<div v-else class="p-8 text-center">
                  <ShoppingCart class="w-10 h-10 text-ink-300 mx-auto mb-2" />
                  <p class="text-sm text-ink-500">Your cart is empty</p>
                </div>-->
                <div class="p-4 border-t border-ink-200 bg-ink-50">
                  <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-ink-600">Subtotal</span>
                    <span class="text-lg font-bold text-ink-900">1000</span>
                  </div>
                  <div class="grid grid-cols-2 gap-2">
                    <router-link to="/cart" class="btn-secondary btn-md" @click="showCartDropdown = false">View Cart</router-link>
                    <router-link to="/checkout" class="btn-primary btn-md" @click="showCartDropdown = false">Checkout</router-link>
                  </div>
                </div>
              </div>
            </Transition>
          </div>

          <!-- Profile -->
          <div class="relative hidden sm:block">
            <button
              class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-ink-100 transition-colors"
              @click="showProfileDropdown = !showProfileDropdown; showCartDropdown = false; showNotifDropdown = false"
            >
              <div class="w-7 h-7 rounded-full gradient-primary flex items-center justify-center text-white text-xs font-bold">JC</div>
              <span class="text-sm font-medium text-ink-700 hidden lg:block">Jordan</span>
              <ChevronDown class="w-4 h-4 text-ink-400" />
            </button>
            <Transition name="dropdown">
              <div v-if="showProfileDropdown" class="absolute right-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-dropdown border border-ink-200 overflow-hidden z-50">
                <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-primary-50 to-ink-50">
                  <div class="w-12 h-12 rounded-full gradient-primary flex items-center justify-center text-white font-bold">JC</div>
                  <div>
                    <p class="font-semibold text-ink-900">Jordan Carter</p>
                    <p class="text-xs text-ink-500">jordan@email.com</p>
                  </div>
                </div>
                <div class="py-2">
                  <router-link to="/account" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-700 hover:bg-ink-50" @click="showProfileDropdown = false">
                    <Package class="w-4 h-4 text-ink-400" /> Dashboard
                  </router-link>
                  <router-link to="/account/orders" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-700 hover:bg-ink-50" @click="showProfileDropdown = false">
                    <ShoppingBag class="w-4 h-4 text-ink-400" /> My Orders
                  </router-link>
                  <router-link to="/account/notifications" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-700 hover:bg-ink-50" @click="showProfileDropdown = false">
                    <Bell class="w-4 h-4 text-ink-400" /> Notifications
                  </router-link>
                  <router-link to="/account/addresses" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-700 hover:bg-ink-50" @click="showProfileDropdown = false">
                    <MapPin class="w-4 h-4 text-ink-400" /> Addresses
                  </router-link>
                  <router-link to="/account/settings" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-700 hover:bg-ink-50" @click="showProfileDropdown = false">
                    <Settings class="w-4 h-4 text-ink-400" /> Settings
                  </router-link>
                </div>
                <div class="border-t border-ink-200 py-2">
                  <button class="flex items-center gap-3 px-4 py-2.5 text-sm text-danger-600 hover:bg-danger-50 w-full text-left">
                    <LogOut class="w-4 h-4" /> Logout
                  </button>
                </div>
              </div>
            </Transition>
          </div>

          <!-- Mobile menu button -->
          <button class="btn-ghost btn-icon md:hidden" @click="mobileMenuOpen = true" aria-label="Open menu">
            <Menu class="w-5 h-5" />
          </button>
        </div>
      </div>
      <!-- Category Nav (desktop) -->
      <nav class="hidden md:flex items-center gap-1 h-12 border-t border-ink-100">
        <button
          class="flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-ink-700 hover:text-primary-600 rounded-lg hover:bg-ink-50 transition-colors"
          @mouseenter="showMegaMenu = true; hoveredCategory = null"
        >
          <Menu class="w-4 h-4" /> All
          <ChevronDown class="w-4 h-4" />
        </button>
        <router-link
          v-for="cat in categoryStore.categories"
          :key="cat.slug"
          :to="`/shop?cat=${cat.slug}`"
          class="px-3 py-2 text-sm font-medium text-ink-600 hover:text-primary-600 rounded-lg hover:bg-ink-50 transition-colors"
          @mouseenter="showMegaMenu = true; hoveredCategory = cat.slug"
        >
          {{ cat.name }}
        </router-link>
        <router-link to="/blog" class="px-3 py-2 text-sm font-medium text-ink-600 hover:text-primary-600 rounded-lg hover:bg-ink-50 transition-colors">Blog</router-link>
        <router-link to="/about" class="px-3 py-2 text-sm font-medium text-ink-600 hover:text-primary-600 rounded-lg hover:bg-ink-50 transition-colors">About</router-link>
        <router-link to="/contact" class="px-3 py-2 text-sm font-medium text-ink-600 hover:text-primary-600 rounded-lg hover:bg-ink-50 transition-colors">Contact</router-link>
      </nav>
    </div>
    <!-- Mega Menu -->
    <Transition name="dropdown">
      <div
        v-if="showMegaMenu"
        class="absolute inset-x-0 top-full bg-white shadow-mega border-b border-ink-200 z-30"
        @mouseleave="showMegaMenu = false"
      >
        <div class="section py-6">
          <div class="grid grid-cols-12 gap-6">
            <div class="col-span-3 space-y-1">
              <div
                v-for="cat in categoryStore.categories"
                :key="cat.slug"
                class="flex items-center gap-3 p-2.5 rounded-xl cursor-pointer transition-colors"
                :class="hoveredCategory === cat.slug ? 'bg-primary-50' : 'hover:bg-ink-50'"
                @mouseenter="hoveredCategory = cat.slug"
              >
                <div :class="['w-9 h-9 rounded-lg flex items-center justify-center', cat.color]">
                  <component :is="iconMap[cat.image]" class="w-4 h-4" />
                </div>
                <span class="text-sm font-medium text-ink-900">{{ cat.name }}</span>
                <ChevronRight class="w-4 h-4 text-ink-300 ml-auto" />
              </div>
            </div>
            <div class="col-span-6">
              <template v-for="cat in categoryStore.categories" :key="cat.slug">
                <div v-if="hoveredCategory === cat.slug">
                  <h3 class="text-sm font-bold text-ink-900 mb-3">{{ cat.name }}</h3>
                  <div class="grid grid-cols-2 gap-x-6 gap-y-2">
                    <router-link
                      v-for="sub in cat.children"
                      :key="sub.slug"
                      :to="`/shop?cat=${cat.slug}&sub=${sub.slug}`"
                      class="text-sm text-ink-600 hover:text-primary-600 transition-colors py-1"
                      @click="showMegaMenu = false"
                    >{{ sub.name }}</router-link>
                  </div>
                </div>
              </template>
            </div>
            <div class="col-span-3">
              <router-link to="/shop" class="block relative rounded-2xl overflow-hidden h-full min-h-[200px] group" @click="showMegaMenu = false">
                <img src="https://images.pexels.com/photos/56562/pexels-photo-56562.jpeg?auto=compress&cs=tinysrgb&w=600" class="w-full h-full object-cover absolute inset-0" alt="Featured" />
                <div class="absolute inset-0 bg-gradient-to-t from-ink-950/80 to-transparent"></div>
                <div class="relative p-4 flex flex-col justify-end h-full">
                  <p class="text-xs text-white/80 mb-1">Featured</p>
                  <p class="text-lg font-bold text-white">Summer Sale</p>
                  <p class="text-sm text-white/90">Up to 50% off</p>
                </div>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </Transition>
    <div v-if="showCartDropdown || showProfileDropdown || showNotifDropdown" class="fixed inset-0 z-30" @click="closeAll"></div>
  </header>
  <!-- Mobile Menu Drawer -->
  <Teleport to="body">
    <TransitionRoot :show="mobileMenuOpen" as="template">
      <Dialog as="div" class="relative z-50 md:hidden" @close="mobileMenuOpen = false">
        <TransitionChild enter="duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200" leave-from="opacity-100" leave-to="opacity-0" as="template">
          <div class="fixed inset-0 bg-ink-950/50 backdrop-blur-sm" />
        </TransitionChild>
        <TransitionChild enter="duration-300 ease-out-expo" enter-from="translate-x-full" enter-to="translate-x-0" leave="duration-200 ease-in" leave-from="translate-x-0" leave-to="translate-x-full" as="template">
          <DialogPanel class="fixed right-0 top-0 bottom-0 w-full max-w-sm bg-white flex flex-col">
            <div class="flex items-center justify-between px-4 h-16 border-b border-ink-200">
              <span class="font-bold text-lg text-ink-900">Menu</span>
              <button class="btn-ghost btn-icon" @click="mobileMenuOpen = false"><X class="w-5 h-5" /></button>
            </div>
            <div class="p-4 border-b border-ink-200">
              <div class="relative">
                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" />
                <input
                  type="text"
                  placeholder="Search..."
                  class="w-full pl-10 pr-4 py-2.5 text-sm bg-ink-100 rounded-xl outline-none focus:bg-white focus:ring-2 focus:ring-primary-500/20"
                  @keydown.enter=" mobileMenuOpen = false"
                />
              </div>
            </div>
            <div class="flex-1 overflow-y-auto py-2">
              <router-link to="/" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-ink-700 hover:bg-ink-50" @click="mobileMenuOpen = false">
                <Home class="w-5 h-5 text-ink-400" /> Home
              </router-link>
              <div class="px-4 py-2">
                <p class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-1">Categories</p>
              </div>
              <router-link v-for="cat in categoryStore.categories" :key="cat.slug" :to="`/shop?cat=${cat.slug}`" class="flex items-center gap-3 px-4 py-2.5 text-sm text-ink-600 hover:bg-ink-50" @click="mobileMenuOpen = false">
                <div :class="['w-8 h-8 rounded-lg flex items-center justify-center', cat.color]">
                  <component :is="iconMap[cat.image]" class="w-4 h-4" />
                </div>
                {{ cat.name }}
              </router-link>
              <div class="border-t border-ink-200 my-2"></div>
              <router-link to="/wishlist" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-ink-700 hover:bg-ink-50" @click="mobileMenuOpen = false">
                <Heart class="w-5 h-5 text-ink-400" /> Wishlist
              </router-link>
              <router-link to="/blog" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-ink-700 hover:bg-ink-50" @click="mobileMenuOpen = false">
                <BookOpen class="w-5 h-5 text-ink-400" /> Blog
              </router-link>
              <router-link to="/about" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-ink-700 hover:bg-ink-50" @click="mobileMenuOpen = false">
                <Info class="w-5 h-5 text-ink-400" /> About Us
              </router-link>
              <router-link to="/contact" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-ink-700 hover:bg-ink-50" @click="mobileMenuOpen = false">
                <Mail class="w-5 h-5 text-ink-400" /> Contact
              </router-link>
              <router-link to="/faq" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-ink-700 hover:bg-ink-50" @click="mobileMenuOpen = false">
                <HelpCircle class="w-5 h-5 text-ink-400" /> FAQ
              </router-link>
              <div class="border-t border-ink-200 my-2"></div>
              <router-link to="/account" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-ink-700 hover:bg-ink-50" @click="mobileMenuOpen = false">
                <User class="w-5 h-5 text-ink-400" /> My Account
              </router-link>
              <router-link to="/account/orders" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-ink-700 hover:bg-ink-50" @click="mobileMenuOpen = false">
                <Package class="w-5 h-5 text-ink-400" /> My Orders
              </router-link>
              <button class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-danger-600 hover:bg-danger-50 w-full text-left">
                <LogOut class="w-5 h-5" /> Logout
              </button>
            </div>
          </DialogPanel>
        </TransitionChild>
      </Dialog>
    </TransitionRoot>
  </Teleport>
</template>

<style scoped>
.dropdown-enter-active, .dropdown-leave-active {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.dropdown-enter-from, .dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
