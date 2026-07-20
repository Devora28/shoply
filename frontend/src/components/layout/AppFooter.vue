<script setup>
import {
  ShoppingCart,Truck,ShieldCheck,RotateCcw,Headphones,Mail,
  Phone,MapPin,Send,
} from '@lucide/vue';
import {Icon} from '@iconify/vue';
import {useCategoryStore} from "@/stores/category.js";
const categoryStore = useCategoryStore();
const trustBadges = [
  { icon: Truck, title: 'Fast Delivery', desc: '2-5 business days' },
  { icon: ShieldCheck, title: 'Secure Payment', desc: '256-bit SSL encryption' },
  { icon: RotateCcw, title: 'Easy Returns', desc: '30-day return policy' },
  { icon: Headphones, title: '24/7 Support', desc: 'Always here to help' },
]
const linkColumns = [
  {
    title: 'Customer Service',
    links: [
      { label: 'Contact Us', to: '/contact' },
      { label: 'FAQ', to: '/faq' },
      { label: 'Shipping Info', to: '/faq' },
      { label: 'Returns & Refunds', to: '/faq' },
      { label: 'Track Order', to: '/account/orders' },
      { label: 'Payment Methods', to: '/faq' },
    ],
  },
  {
    title: 'Company',
    links: [
      { label: 'About Us', to: '/about' },
      { label: 'Blog', to: '/blog' },
      { label: 'Privacy Policy', to: '/privacy' },
      { label: 'Terms & Conditions', to: '/terms' },
      { label: 'Careers', to: '/about' },
      { label: 'Affiliate Program', to: '/about' },
    ],
  },
  {
    title: 'My Account',
    links: [
      { label: 'Dashboard', to: '/account' },
      { label: 'My Orders', to: '/account/orders' },
      { label: 'Wishlist', to: '/wishlist' },
      { label: 'Addresses', to: '/account/addresses' },
      { label: 'Settings', to: '/account/settings' },
      { label: 'Notifications', to: '/account/notifications' },
    ],
  },
]
const paymentMethods = [
  { icon: 'logos:visa', label: 'Visa' },
  { icon: 'logos:mastercard', label: 'Mastercard' },
  { icon: 'logos:amex', label: 'American Express' },
  { icon: 'logos:paypal', label: 'PayPal' },
  { icon: 'logos:apple-pay', label: 'Apple Pay' },
]
const socials = [
  {label: 'Facebook', href: '#', icon: 'mdi:facebook',},
  {label: 'Instagram', href: '#', icon: 'mdi:instagram',},
  {label: 'X', href: '#', icon: 'ri:twitter-x-fill',},
  {label: 'YouTube', href: '#', icon: 'mdi:youtube',},
  {label: 'LinkedIn', href: '#', icon: 'mdi:linkedin',},
]
</script>

<template>
  <!-- Trust badges -->
  <div class="bg-white border-t border-ink-200">
    <div class="section py-8">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div v-for="badge in trustBadges" :key="badge.title" class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
            <component :is="badge.icon" class="w-6 h-6 text-primary-600" />
          </div>
          <div>
            <p class="text-sm font-bold text-ink-900">{{ badge.title }}</p>
            <p class="text-xs text-ink-500">{{ badge.desc }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <footer class="bg-ink-900 text-ink-300">
    <!-- Newsletter -->
    <div class="border-b border-ink-800">
      <div class="section py-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
          <div class="text-center lg:text-left">
            <h3 class="text-2xl font-bold text-white mb-2">Stay in the loop</h3>
            <p class="text-sm text-ink-400">Subscribe to get the latest products, exclusive offers, and updates.</p>
          </div>
          <div class="w-full max-w-md flex gap-2">
            <div class="relative flex-1">
              <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-ink-500" />
              <input
                v-model="email"
                type="email"
                placeholder="Enter your email"
                class="w-full pl-12 pr-4 py-3 text-sm bg-ink-800 text-white placeholder-ink-500 rounded-xl border border-ink-700 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 outline-none transition-all"
                @keydown.enter="subscribe"
              />
            </div>
            <button class="btn-primary btn-lg shrink-0" @click="subscribe">
              <Send class="w-4 h-4" /> Subscribe
            </button>
          </div>
        </div>
        <Transition name="fade">
          <p v-if="subscribed" class="text-sm text-success-400 mt-3 text-center lg:text-left">Thank you for subscribing!</p>
        </Transition>
      </div>
    </div>

    <!-- Links -->
    <div class="section py-12">
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
        <!-- Brand -->
        <div class="col-span-2">
          <router-link to="/" class="flex items-center gap-2 mb-4">
            <div class="w-9 h-9 rounded-xl gradient-primary flex items-center justify-center">
              <ShoppingCart class="w-5 h-5 text-white" />
            </div>
            <span class="text-xl font-bold text-white">Shoply</span>
          </router-link>
          <p class="text-sm text-ink-400 mb-4 leading-relaxed max-w-xs">
            Your premium online shopping destination. Quality products, unbeatable prices, and exceptional service.
          </p>
          <div class="space-y-2 text-sm">
            <div class="flex items-center gap-2">
              <Phone class="w-4 h-4 text-primary-400" />
              <span>+1 (800) 555-0100</span>
            </div>
            <div class="flex items-center gap-2">
              <Mail class="w-4 h-4 text-primary-400" />
              <span>support@shoply.com</span>
            </div>
            <div class="flex items-start gap-2">
              <MapPin class="w-4 h-4 text-primary-400 mt-0.5" />
              <span>123 Commerce St, Portland, OR 97201</span>
            </div>
          </div>
        </div>

        <!-- Link columns -->
        <div>
          <h4 class="text-sm font-bold text-white uppercase tracking-wide mb-4">Shop</h4>
          <ul class="space-y-2.5">
            <li v-for="link in categoryStore.categories" :key="link.id">
              <router-link to="/" class="text-sm text-ink-400 hover:text-primary-400 transition-colors">{{ link.name }}</router-link>
            </li>
          </ul>
        </div>
        <div v-for="col in linkColumns" :key="col.title">
          <h4 class="text-sm font-bold text-white uppercase tracking-wide mb-4">{{ col.title }}</h4>
          <ul class="space-y-2.5">
            <li v-for="link in col.links" :key="link.label">
              <router-link :to="link.to" class="text-sm text-ink-400 hover:text-primary-400 transition-colors">{{ link.label }}</router-link>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Social & Payment -->
    <div class="border-t border-ink-800">
      <div class="section py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <!-- Social -->
          <div class="flex items-center gap-2">
            <a
              v-for="s in socials"
              :key="s.label"
              :href="s.href"
              :aria-label="s.label"
              class="w-9 h-9 rounded-lg bg-ink-800 hover:bg-primary-600 flex items-center justify-center transition-colors"
            >
              <Icon :icon="s.icon" class="w-4 h-4 text-ink-300" />
            </a>
          </div>
          <!-- Payment methods -->
          <div class="flex flex-col items-center gap-3 md:flex-row md:justify-between">
            <span class="text-xs text-ink-500">We accept:</span>

            <div class="flex flex-wrap items-center justify-center gap-2">
              <div
                v-for="method in paymentMethods"
                :key="method.label"
                class="flex h-9 items-center justify-center rounded-lg border border-ink-700 bg-ink-800 px-3"
                :title="method.label"
              >
                <Icon
                  :icon="method.icon"
                  class="max-h-4 max-w-8"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Copyright -->
    <div class="border-t border-ink-800">
      <div class="section py-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-500">
          <p>&copy; 2026 Shoply. All rights reserved.</p>
          <div class="flex items-center gap-4">
            <router-link to="/privacy" class="hover:text-ink-300 transition-colors">Privacy</router-link>
            <router-link to="/terms" class="hover:text-ink-300 transition-colors">Terms</router-link>
            <router-link to="/faq" class="hover:text-ink-300 transition-colors">Help</router-link>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
