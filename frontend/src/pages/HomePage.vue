<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { products, categories, formatPrice } from '../../data/mock.js'
import ProductCard from '../components/product/ProductCard'

import {
  ChevronRight, ArrowRight, Zap, Clock, TrendingUp, Award, Sparkles,
  Tag, Truck, ShieldCheck, Star, Eye, Flame, Percent, Gift, Headphones,
} from 'lucide-vue-next'

const router = useRouter()
const { addToCart, toggleWishlist, isInWishlist } = useStore()

/* ------------------------------------------------------------------ */
/* Data selectors                                                      */
/* ------------------------------------------------------------------ */
const featuredProducts = computed(() =>
  [...products].sort((a, b) => b.rating - a.rating).slice(0, 8)
)

const flashSaleProducts = computed(() =>
  products.filter(p => p.discount >= 25).slice(0, 10)
)

const bestSellers = computed(() =>
  [...products].sort((a, b) => b.sold - a.sold).slice(0, 4)
)

const newArrivals = computed(() =>
  [...products].slice(-4).reverse()
)

const heroProduct = computed(() => products[0])

/* ------------------------------------------------------------------ */
/* Countdown timer for flash sale                                      */
/* ------------------------------------------------------------------ */
const saleEnd = ref(Date.now() + 8 * 60 * 60 * 1000 + 42 * 60 * 1000) // ~8h 42m from load
const remaining = ref(0)
let timer = null

const timeParts = computed(() => {
  const total = Math.max(0, remaining.value)
  const hours = Math.floor(total / 3_600_000)
  const minutes = Math.floor((total % 3_600_000) / 60_000)
  const seconds = Math.floor((total % 60_000) / 1000)
  return { hours, minutes, seconds }
})

const pad = (n) => String(n).padStart(2, '0')

onMounted(() => {
  const tick = () => { remaining.value = saleEnd.value - Date.now() }
  tick()
  timer = setInterval(tick, 1000)
})

onUnmounted(() => clearInterval(timer))

/* ------------------------------------------------------------------ */
/* Newsletter                                                          */
/* ------------------------------------------------------------------ */
const email = ref('')
const subscribed = ref(false)

function subscribe() {
  if (!email.value || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) return
  subscribed.value = true
  email.value = ''
  setTimeout(() => (subscribed.value = false), 4000)
}

/* ------------------------------------------------------------------ */
/* Trust badges                                                        */
/* ------------------------------------------------------------------ */
const trustBadges = [
  { icon: Truck, title: 'Fast Delivery', desc: 'Free shipping on orders over $50' },
  { icon: ShieldCheck, title: 'Secure Payment', desc: '256-bit SSL encrypted checkout' },
  { icon: Gift, title: 'Easy Returns', desc: '30-day hassle-free return policy' },
  { icon: Headphones, title: '24/7 Support', desc: 'Dedicated help anytime you need' },
]

/* ------------------------------------------------------------------ */
/* Promo banners                                                       */
/* ------------------------------------------------------------------ */
const promoBanners = [
  {
    title: 'Summer Collection',
    subtitle: 'Up to 40% off',
    desc: 'Refresh your wardrobe with the season\'s brightest picks.',
    cta: 'Shop Fashion',
    to: '/shop?cat=fashion',
    gradient: 'gradient-accent',
    image: 'https://images.pexels.com/photos/996329/pexels-photo-996329.jpeg?auto=compress&cs=tinysrgb&w=800',
  },
  {
    title: 'Smart Home Sale',
    subtitle: 'Save big on tech',
    desc: 'Upgrade your home with the latest connected devices.',
    cta: 'Shop Electronics',
    to: '/shop?cat=electronics',
    gradient: 'gradient-primary',
    image: 'https://images.pexels.com/photos/788946/pexels-photo-788946.jpeg?auto=compress&cs=tinysrgb&w=800',
  },
]

/* ------------------------------------------------------------------ */
/* Category icon map (mock stores icon names as strings)               */
/* ------------------------------------------------------------------ */
const categoryIcons = {
  Smartphone: Tag,
  Shirt: Sparkles,
  Sofa: Truck,
  Sparkles: Sparkles,
  Dumbbell: TrendingUp,
  BookOpen: Award,
  Gamepad2: Zap,
  ShoppingBasket: Gift,
}
</script>

<template>
  <div class="min-h-screen">
    <!-- ============================================================ -->
    <!-- 1. HERO BANNER                                               -->
    <!-- ============================================================ -->
    <section class="section pt-6 sm:pt-8">
      <div class="relative overflow-hidden rounded-3xl gradient-dark text-white">
        <!-- Decorative mesh -->
        <div class="absolute inset-0 gradient-mesh opacity-60" aria-hidden="true" />
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary-500/20 blur-3xl" aria-hidden="true" />
        <div class="absolute -bottom-32 -left-20 w-80 h-80 rounded-full bg-accent-500/15 blur-3xl" aria-hidden="true" />

        <div class="relative grid lg:grid-cols-2 gap-8 items-center px-6 py-10 sm:px-10 sm:py-14 lg:px-16 lg:py-20">
          <!-- Copy -->
          <div class="max-w-xl">
            <span class="badge badge-accent mb-4">
              <Sparkles class="w-3.5 h-3.5" /> Premium Shopping Experience
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight text-balance">
              Discover Deals That
              <span class="text-accent-400">Spark Joy</span>
            </h1>
            <p class="mt-4 text-base sm:text-lg text-ink-300 max-w-md">
              Shop thousands of curated products at unbeatable prices. Fast delivery, secure checkout, and 30-day easy returns.
            </p>

            <div class="mt-7 flex flex-wrap gap-3">
              <router-link to="/shop" class="btn-primary btn-lg">
                Shop Now
                <ArrowRight class="w-5 h-5" />
              </router-link>
              <router-link to="/shop?cat=electronics" class="btn-secondary btn-lg bg-white/10 text-white border-white/20 hover:bg-white/20 hover:border-white/30">
                Explore Electronics
              </router-link>
            </div>

            <!-- Mini stats -->
            <dl class="mt-9 grid grid-cols-3 gap-4 max-w-md">
              <div>
                <dt class="text-2xl font-bold text-white">50K+</dt>
                <dd class="text-xs text-ink-400 mt-0.5">Products</dd>
              </div>
              <div>
                <dt class="text-2xl font-bold text-white">120K+</dt>
                <dd class="text-xs text-ink-400 mt-0.5">Happy Customers</dd>
              </div>
              <div>
                <dt class="text-2xl font-bold text-white">4.8★</dt>
                <dd class="text-xs text-ink-400 mt-0.5">Average Rating</dd>
              </div>
            </dl>
          </div>

          <!-- Featured product image -->
          <div class="relative hidden lg:block">
            <div class="relative aspect-square max-w-md ml-auto">
              <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-primary-500/30 to-accent-500/20 blur-2xl" aria-hidden="true" />
              <img
                :src="heroProduct.image"
                :alt="heroProduct.name"
                class="relative rounded-3xl object-cover w-full h-full shadow-2xl ring-1 ring-white/10"
              />
              <!-- Floating price card -->
              <div class="absolute -bottom-5 -left-5 glass-dark rounded-2xl p-4 shadow-xl border border-white/10 max-w-[200px]">
                <p class="text-xs text-ink-400 mb-1">Featured Deal</p>
                <p class="text-sm font-semibold text-white clamp-2 leading-snug">{{ heroProduct.name }}</p>
                <div class="mt-2 flex items-baseline gap-2">
                  <span class="text-lg font-bold text-accent-400">{{ formatPrice(heroProduct.price) }}</span>
                  <span class="text-xs text-ink-400 line-through">{{ formatPrice(heroProduct.oldPrice) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- 2. CATEGORY GRID                                             -->
    <!-- ============================================================ -->
    <section class="section pt-10 sm:pt-14">
      <SectionHeader title="Shop by Category" subtitle="Find exactly what you need across our top categories" to="/shop" />
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4">
        <router-link
          v-for="cat in categories"
          :key="cat.slug"
          :to="`/shop?cat=${cat.slug}`"
          class="card card-hover group p-4 sm:p-5 flex flex-col items-center text-center gap-3"
        >
          <span
            class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center transition-transform duration-300 group-hover:scale-110"
            :class="cat.color"
          >
            <component :is="categoryIcons[cat.icon] || Tag" class="w-6 h-6" />
          </span>
          <span class="text-sm font-semibold text-ink-900 clamp-1">{{ cat.name }}</span>
          <span class="text-xs text-ink-400">{{ cat.subcategories.length }} subcategories</span>
        </router-link>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- 3. FLASH SALE                                                -->
    <!-- ============================================================ -->
    <section class="section pt-10 sm:pt-14">
      <div class="card overflow-hidden">
        <!-- Header bar -->
        <div class="gradient-dark text-white px-5 sm:px-7 py-4 flex flex-wrap items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-accent-500/20 flex items-center justify-center">
              <Zap class="w-5 h-5 text-accent-400" />
            </span>
            <div>
              <h2 class="text-lg sm:text-xl font-bold flex items-center gap-2">
                Flash Sale
                <Flame class="w-5 h-5 text-accent-400" />
              </h2>
              <p class="text-xs text-ink-400">Limited time — grab them before they're gone</p>
            </div>
          </div>

          <!-- Countdown -->
          <div class="flex items-center gap-2">
            <Clock class="w-4 h-4 text-accent-400 hidden sm:block" />
            <div class="flex items-center gap-1.5">
              <div class="flex flex-col items-center">
                <span class="px-2 py-1.5 rounded-lg bg-white/10 text-white font-mono font-bold text-base sm:text-lg min-w-[2.5rem] text-center tabular-nums">{{ pad(timeParts.hours) }}</span>
                <span class="text-2xs text-ink-400 mt-0.5">Hrs</span>
              </div>
              <span class="text-white/60 font-bold pb-4">:</span>
              <div class="flex flex-col items-center">
                <span class="px-2 py-1.5 rounded-lg bg-white/10 text-white font-mono font-bold text-base sm:text-lg min-w-[2.5rem] text-center tabular-nums">{{ pad(timeParts.minutes) }}</span>
                <span class="text-2xs text-ink-400 mt-0.5">Min</span>
              </div>
              <span class="text-white/60 font-bold pb-4">:</span>
              <div class="flex flex-col items-center">
                <span class="px-2 py-1.5 rounded-lg bg-white/10 text-white font-mono font-bold text-base sm:text-lg min-w-[2.5rem] text-center tabular-nums">{{ pad(timeParts.seconds) }}</span>
                <span class="text-2xs text-ink-400 mt-0.5">Sec</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Horizontal scroll of products -->
        <div class="overflow-x-auto no-scrollbar p-4 sm:p-5">
          <div class="flex gap-4 w-max">
            <div
              v-for="product in flashSaleProducts"
              :key="product.id"
              class="w-56 sm:w-64 flex-shrink-0"
            >
              <ProductCard :product="product" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- 4. FEATURED PRODUCTS GRID                                   -->
    <!-- ============================================================ -->
    <section class="section pt-10 sm:pt-14">
      <SectionHeader title="Featured Products" subtitle="Handpicked favorites our customers love" to="/shop" />
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
        <ProductCard
          v-for="product in featuredProducts"
          :key="product.id"
          :product="product"
        />
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- 5. PROMO BANNERS                                            -->
    <!-- ============================================================ -->
    <section class="section pt-10 sm:pt-14">
      <div class="grid md:grid-cols-2 gap-4 sm:gap-5">
        <router-link
          v-for="(promo, i) in promoBanners"
          :key="i"
          :to="promo.to"
          class="group relative overflow-hidden rounded-2xl p-6 sm:p-8 text-white min-h-[200px] flex flex-col justify-between"
          :class="promo.gradient"
        >
          <div class="relative z-10 max-w-[60%]">
            <span class="badge badge-accent mb-3 bg-white/20 text-white">
              <Percent class="w-3.5 h-3.5" /> {{ promo.subtitle }}
            </span>
            <h3 class="text-xl sm:text-2xl font-bold mb-2">{{ promo.title }}</h3>
            <p class="text-sm text-white/80 mb-4 hidden sm:block">{{ promo.desc }}</p>
            <span class="btn-secondary btn-sm bg-white/15 text-white border-white/20 hover:bg-white/25 hover:border-white/30">
              {{ promo.cta }}
              <ChevronRight class="w-4 h-4" />
            </span>
          </div>
          <img
            :src="promo.image"
            :alt="promo.title"
            loading="lazy"
            class="absolute right-0 top-0 h-full w-1/2 object-cover opacity-40 group-hover:opacity-50 group-hover:scale-105 transition-all duration-500"
          />
          <div class="absolute inset-0 bg-gradient-to-r from-black/30 via-black/10 to-transparent" aria-hidden="true" />
        </router-link>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- 6. BEST SELLERS                                              -->
    <!-- ============================================================ -->
    <section class="section pt-10 sm:pt-14">
      <SectionHeader title="Best Sellers" subtitle="Our most popular products this month" to="/shop" />
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
        <ProductCard
          v-for="product in bestSellers"
          :key="product.id"
          :product="product"
        />
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- 7. NEW ARRIVALS                                              -->
    <!-- ============================================================ -->
    <section class="section pt-10 sm:pt-14">
      <SectionHeader title="New Arrivals" subtitle="Fresh stock just landed — be the first to own them" to="/shop" />
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
        <ProductCard
          v-for="product in newArrivals"
          :key="product.id"
          :product="product"
        />
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- 8. TRUST / BRAND STRIP                                       -->
    <!-- ============================================================ -->
    <section class="section pt-10 sm:pt-14">
      <div class="card grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-ink-200/70">
        <div
          v-for="badge in trustBadges"
          :key="badge.title"
          class="flex items-center gap-3 p-5 sm:p-6"
        >
          <span class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center flex-shrink-0">
            <component :is="badge.icon" class="w-5 h-5 sm:w-6 sm:h-6" />
          </span>
          <div>
            <h3 class="text-sm font-semibold text-ink-900">{{ badge.title }}</h3>
            <p class="text-xs text-ink-500 mt-0.5 clamp-2">{{ badge.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ============================================================ -->
    <!-- 9. NEWSLETTER CTA                                            -->
    <!-- ============================================================ -->
    <section class="section pt-10 sm:pt-14 pb-16">
      <div class="relative overflow-hidden rounded-3xl gradient-primary text-white px-6 py-10 sm:px-10 sm:py-14 text-center">
        <div class="absolute inset-0 gradient-mesh opacity-50" aria-hidden="true" />
        <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-72 h-72 rounded-full bg-accent-500/20 blur-3xl" aria-hidden="true" />

        <div class="relative max-w-xl mx-auto">
          <span class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center mx-auto mb-5">
            <Gift class="w-7 h-7 text-accent-300" />
          </span>
          <h2 class="text-2xl sm:text-3xl font-bold mb-3">Get 10% Off Your First Order</h2>
          <p class="text-white/80 mb-6">
            Subscribe to our newsletter for exclusive deals, new arrivals, and insider-only discounts.
          </p>

          <form @submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <input
              v-model="email"
              type="email"
              required
              placeholder="Enter your email address"
              class="flex-1 px-5 py-3 rounded-xl text-ink-900 bg-white border-0 focus:outline-none focus:ring-2 focus:ring-accent-400 placeholder-ink-400"
            />
            <button type="submit" class="btn-accent btn-lg whitespace-nowrap">
              Subscribe
              <ArrowRight class="w-5 h-5" />
            </button>
          </form>

          <p
            v-if="subscribed"
            class="mt-4 text-sm text-accent-200 flex items-center justify-center gap-1.5"
          >
            <ShieldCheck class="w-4 h-4" />
            Thanks for subscribing! Check your inbox for the discount code.
          </p>
          <p v-else class="mt-4 text-xs text-white/60">
            No spam, unsubscribe at any time. We respect your privacy.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>
