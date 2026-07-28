<script setup>
import {ref, computed, onMounted, watch} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import { Swiper, SwiperSlide } from 'swiper/vue'
import {FreeMode, Navigation, Pagination, Thumbs} from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import 'swiper/css/thumbs'
import {
  Heart, ShoppingCart, Share2, Truck, ShieldCheck, RotateCcw, Check,
  Star, ChevronDown, Package, BadgeCheck, MessageSquare, AlertCircle,
} from '@lucide/vue'
import RatingStars from "@/components/ui/RatingStars.vue";
import ProductCard from "@/components/product/ProductCard.vue";
import SectionHeader from "@/components/ui/SectionHeader.vue";
import QuantitySelector from "@/components/ui/QuantitySelector.vue";
import ReviewItem from "@/components/ui/ReviewItem.vue";
import BaseBreadcrumb from "@/components/ui/BaseBreadcrumb.vue";
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
import {calcDiscount} from "@/utils/helpers.js";
const route = useRoute();
const router = useRouter();
const loading = ref(false);
const product = ref(null);
const relatedProducts = ref(null);
const galleryImages = computed(() => {
  if (!product.value) return []

  return [
    {
      path: product.value.image
    },
    ...(product.value.images ?? [])
  ]
});
const loadProduct = async () => {
  try {
    loading.value = true;
    const response = await api.get(endpoints.product(route.params.id));
    product.value = response.data.data.product;
    relatedProducts.value = response.data.data.relatedProducts;
  }
  catch (error) {
    console.log(error);
  }
  finally {
    loading.value = false;
  }
}
watch(
  () => route.params.id,
  loadProduct,
  { immediate: true }
)
const allReviews = [
  {
    id: '1-0', author: 'Sarah M.', avatar: 'https://i.pravatar.cc/100?img=1', rating: 5,
    date: '2025-01-15', title: 'Highly recommended!', text: 'Absolutely love this product! The quality is outstanding and it exceeded all my expectations.',
    pros: ['Excellent build quality', 'Great value for money'], cons: [],
    helpful: 42, notHelpful: 2, verified: true,
    images: ['https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?auto=compress&cs=tinysrgb&w=800'],
    sellerReply: { text: 'Thank you for your feedback! We are glad you enjoyed the product.', date: '2025-02-15' },
  },
  {
    id: '1-1', author: 'James K.', avatar: 'https://i.pravatar.cc/100?img=2', rating: 5,
    date: '2025-02-03', title: 'Worth every penny', text: 'Great purchase, works exactly as described. Would definitely recommend to friends.',
    pros: ['Fast shipping', 'Easy to use'], cons: ['Slightly pricey'],
    helpful: 28, notHelpful: 1, verified: true,
    images: [],
    sellerReply: null,
  },
  {
    id: '1-2', author: 'Lina P.', avatar: 'https://i.pravatar.cc/100?img=3', rating: 4,
    date: '2025-03-12', title: 'Good buy', text: 'Good product overall. A few minor issues but nothing that would stop me from buying again.',
    pros: ['Beautiful design'], cons: ['Manual could be better'],
    helpful: 15, notHelpful: 3, verified: true,
    images: [],
    sellerReply: null,
  },
  {
    id: '1-3', author: 'David O.', avatar: 'https://i.pravatar.cc/100?img=4', rating: 5,
    date: '2025-04-08', title: 'Very satisfied', text: 'Fantastic value. The build quality feels premium and it looks even better in person.',
    pros: ['Durable and reliable', 'Exceeds expectations'], cons: [],
    helpful: 35, notHelpful: 0, verified: true,
    images: ['https://images.pexels.com/photos/56562/pexels-photo-56562.jpeg?auto=compress&cs=tinysrgb&w=800'],
    sellerReply: null,
  },
  {
    id: '1-4', author: 'Emma R.', avatar: 'https://i.pravatar.cc/100?img=5', rating: 5,
    date: '2025-05-20', title: '', text: 'Been using it for a month now, no complaints. Does exactly what it says.',
    pros: ['Great value for money'], cons: [],
    helpful: 19, notHelpful: 2, verified: false,
    images: [],
    sellerReply: null,
  },
  {
    id: '1-5', author: 'Michael B.', avatar: 'https://i.pravatar.cc/100?img=6', rating: 4,
    date: '2025-06-14', title: 'Good buy', text: 'Impressive quality for the price point. Shipping was fast too.',
    pros: ['Fast shipping'], cons: ['Limited color options'],
    helpful: 12, notHelpful: 1, verified: true,
    images: [],
    sellerReply: null,
  },
  {
    id: '1-6', author: 'Sofia C.', avatar: 'https://i.pravatar.cc/100?img=7', rating: 5,
    date: '2025-07-01', title: 'Highly recommended!', text: 'My third purchase from this brand and they never disappoint. Top notch.',
    pros: ['Excellent build quality', 'Durable and reliable'], cons: [],
    helpful: 31, notHelpful: 0, verified: true,
    images: [],
    sellerReply: { text: 'Thank you for your feedback! We are glad you enjoyed the product.', date: '2025-07-05' },
  },
  {
    id: '1-7', author: 'Ryan T.', avatar: 'https://i.pravatar.cc/100?img=8', rating: 3,
    date: '2025-07-18', title: '', text: 'Solid product but the packaging could be improved. Otherwise very happy.',
    pros: [], cons: ['Takes time to set up'],
    helpful: 8, notHelpful: 4, verified: false,
    images: [],
    sellerReply: null,
  },
]

// ---- Gallery ----
const thumbsSwiper = ref(null)
const setThumbsSwiper = (swiper) => { thumbsSwiper.value = swiper }
const modules = [Navigation, Pagination, Thumbs]

// ---- Variants ----
const quantity = ref(1)

// ---- Tabs ----
const activeTab = ref('specs')
const tabs = [
  { id: 'specs', label: 'Specifications' },
  { id: 'description', label: 'Description' },
  { id: 'reviews', label: 'Reviews' },
]

// ---- Reviews filtering ----
const reviewSort = ref('helpful')
const reviewFilter = ref('all')
const sortOptions = [
  { value: 'helpful', label: 'Most helpful' },
  { value: 'newest', label: 'Newest' },
  { value: 'highest', label: 'Highest rated' },
  { value: 'lowest', label: 'Lowest rated' },
]
const filterChips = [
  { value: 'all', label: 'All' },
  { value: '5', label: '5 stars' },
  { value: '4', label: '4 stars' },
  { value: '3', label: '3 stars' },
  { value: 'photos', label: 'With photos' },
  { value: 'verified', label: 'Verified only' },
]

const ratingSummary = computed(() => {
  if (!allReviews.length) return { average: 0, total: 0, breakdown: [0, 0, 0, 0, 0], satisfaction: 0 }
  const breakdown = [0, 0, 0, 0, 0]
  allReviews.forEach(r => { breakdown[5 - r.rating]++ })
  const average = (allReviews.reduce((s, r) => s + r.rating, 0) / allReviews.length).toFixed(1)
  const satisfaction = Math.round((allReviews.filter(r => r.rating >= 4).length / allReviews.length) * 100)
  return { average: parseFloat(average), total: allReviews.length, breakdown, satisfaction }
})

const filteredReviews = computed(() => {
  let list = [...allReviews]
  if (reviewFilter.value === '5') list = list.filter(r => r.rating === 5)
  else if (reviewFilter.value === '4') list = list.filter(r => r.rating === 4)
  else if (reviewFilter.value === '3') list = list.filter(r => r.rating === 3)
  else if (reviewFilter.value === 'photos') list = list.filter(r => r.images.length > 0)
  else if (reviewFilter.value === 'verified') list = list.filter(r => r.verified)
  if (reviewSort.value === 'helpful') list.sort((a, b) => b.helpful - a.helpful)
  else if (reviewSort.value === 'newest') list.sort((a, b) => new Date(b.date) - new Date(a.date))
  else if (reviewSort.value === 'highest') list.sort((a, b) => b.rating - a.rating)
  else if (reviewSort.value === 'lowest') list.sort((a, b) => a.rating - b.rating)
  return list
})

// ---- Write a review form ----
const reviewForm = ref({ rating: 5, title: '', text: '', pros: '', cons: '' })
const reviewSubmitted = ref(false)

function submitReview() {
  reviewSubmitted.value = true
  setTimeout(() => {
    reviewSubmitted.value = false
    reviewForm.value = { rating: 5, title: '', text: '', pros: '', cons: '' }
  }, 3000)
}

// ---- Actions ----
const showAddedToast = ref(false)
function handleShare() {
  if (navigator.share) {
    navigator.share({ title: product.value?.name, url: window.location.href }).catch(() => {})
  } else {
    navigator.clipboard?.writeText(window.location.href)
  }
}

// ---- Stock ----
const stockLevel = computed(() => {
  if (product.value?.stock > 20) return 'high'
  if (product.value?.stock > 5) return 'medium'
  return 'low'
})
const stockColor = { high: 'text-success-600', medium: 'text-warning-600', low: 'text-danger-600' }
const stockBg = { high: 'bg-success-100', medium: 'bg-warning-100', low: 'bg-danger-100' }

// ---- Trust badges ----
const trustBadges = [
  { icon: Truck, title: 'Free Shipping', desc: 'On orders over $50' },
  { icon: ShieldCheck, title: 'Secure Payment', desc: '256-bit SSL encryption' },
  { icon: RotateCcw, title: 'Easy Returns', desc: '30-day return policy' },
  { icon: Package, title: 'Warranty', desc: '12-month manufacturer' },
]
</script>

<template>
  <div class="min-h-screen bg-ink-50 pb-16">
    <!-- Breadcrumb -->
    <div class="section pt-5 pb-2">
      <BaseBreadcrumb :items="['Shop', product?.category?.name, product?.name]" />
    </div>

    <!-- Main product section skeleton -->
    <div v-if="loading" class="section pt-4">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Gallery -->
        <div>
          <div class="aspect-square skeleton rounded-2xl"></div>

          <div class="flex gap-3 mt-4">
            <div
              v-for="i in 4"
              :key="i"
              class="w-20 h-20 skeleton rounded-xl"
            ></div>
          </div>
        </div>

        <!-- Right -->
        <div>

          <div class="h-4 w-24 skeleton rounded mb-4"></div>

          <div class="h-8 w-4/5 skeleton rounded mb-3"></div>

          <div class="h-5 w-40 skeleton rounded mb-6"></div>

          <div class="h-10 w-36 skeleton rounded mb-5"></div>

          <div class="space-y-2 mb-6">
            <div class="h-4 skeleton rounded"></div>
            <div class="h-4 w-5/6 skeleton rounded"></div>
            <div class="h-4 w-2/3 skeleton rounded"></div>
          </div>

          <div class="flex gap-4 mb-6">
            <div class="w-36 h-12 skeleton rounded-xl"></div>
            <div class="flex-1 h-12 skeleton rounded-xl"></div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div
              v-for="i in 4"
              :key="i"
              class="h-24 skeleton rounded-xl"
            ></div>
          </div>

        </div>

      </div>
    </div>
    <!-- Main product section -->
    <div v-else class="section pt-4">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
        <!-- Gallery (left) -->
        <div class="flex flex-col gap-4">
          <!-- Main Swiper -->
          <div class="card overflow-hidden">
            <Swiper
              :modules="modules"
              :thumbs="{ swiper: thumbsSwiper && !thumbsSwiper.destroyed ? thumbsSwiper : null }"
              :navigation="true"
              :pagination="{ clickable: true }"
              :loop="true"
              class="aspect-product rounded-2xl"
            >
              <SwiperSlide v-for="(img, i) in galleryImages" :key="i">
                <div class="aspect-product bg-ink-50 flex items-center justify-center">
                  <img :src="img.path" :alt="`${product?.name} - view ${i + 1}`" class="w-full h-full object-cover" />
                </div>
              </SwiperSlide>
            </Swiper>
          </div>

          <!-- Thumbnails Swiper -->
          <Swiper
            :modules="modules"
            @swiper="setThumbsSwiper"
            :slides-per-view="4"
            :space-between="12"
            :watch-slides-progress="true"
            class="!pb-0 thumbs-swiper"
          >
            <SwiperSlide v-for="(img, i) in galleryImages" :key="`thumb-${i}`" class="thumbs-slide">
              <div class="h-20 w-20 rounded-xl overflow-hidden border-2 border-transparent cursor-pointer transition-all hover:border-primary-400">
                <img :src="img.path" :alt="`${product?.name} thumbnail ${i + 1}`" class="w-full h-full object-cover" />
              </div>
            </SwiperSlide>
          </Swiper>
        </div>

        <!-- Product info (right) -->
        <div class="flex flex-col">
          <!-- Brand + badge -->
          <div class="flex items-center gap-2 mb-2">
            <span class="text-sm font-semibold text-primary-600">{{ product?.brand?.name }}</span>
            <BadgeCheck class="w-4 h-4 text-primary-500" />
          </div>

          <!-- Product name -->
          <h1 class="text-2xl sm:text-3xl font-bold text-ink-900 leading-tight mb-3">{{ product?.name }}</h1>

          <!-- Rating + reviews + sold -->
          <div class="flex flex-wrap items-center gap-3 mb-5">
            <div class="flex items-center gap-1.5">
              <RatingStars :model-value="product?.rating" size="md" />
              <span class="text-sm font-semibold text-ink-900">{{ parseFloat(product?.rating ?? 0) }}</span>
            </div>
            <span class="text-sm text-ink-500">{{ product?.reviews ?? 0 }} reviews</span>
            <span class="w-px h-4 bg-ink-200"></span>
            <span class="text-sm text-ink-500">{{ product?.sold_count }} sold</span>
          </div>

          <!-- Price -->
          <div class="flex flex-wrap items-baseline gap-3 mb-4">
            <span class="text-3xl font-bold text-primary-700">${{calcDiscount(product?.price,product?.discount)}}</span>
            <span v-if="product?.discount > 0" class="text-lg text-ink-400 line-through">${{product?.price}}</span>
            <span v-if="product?.discount > 0" class="badge-danger">Save {{ product?.discount }}%</span>
          </div>
          <!-- Short description -->
          <p class="text-sm text-ink-600 leading-relaxed mb-6">{{ product?.short_description }}</p>
          <div class="divider mb-6"></div>
          <!-- Color variants -->
<!--          <div class="mb-5">
            <div class="flex items-center gap-2 mb-2.5">
              <span class="label !mb-0">Color:</span>
              <span class="text-sm font-semibold text-ink-900">{{ selectedColor }}</span>
            </div>
            <div class="flex flex-wrap gap-2.5">
              <button
                v-for="color in product.colors"
                :key="color"
                @click="selectedColor = color"
                :class="[
                  'px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all',
                  selectedColor === color
                    ? 'border-primary-600 bg-primary-50 text-primary-700'
                    : 'border-ink-200 bg-white text-ink-700 hover:border-ink-300'
                ]"
              >
                {{ color }}
              </button>
            </div>
          </div>-->

          <!-- Quantity + Stock -->
          <div class="flex flex-wrap items-center gap-4 mb-6">
            <div>
              <span class="label">Quantity</span>
              <QuantitySelector v-model="quantity" :min="1" :max="product?.stock" />
            </div>
            <div class="flex items-center gap-2 mt-6">
              <span class="inline-flex items-center justify-center w-2.5 h-2.5 rounded-full" :class="stockBg[stockLevel]"></span>
              <span class="text-sm font-medium" :class="stockColor[stockLevel]">
                <template v-if="stockLevel === 'high'">In stock</template>
                <template v-else-if="stockLevel === 'medium'">Only {{ product?.stock }} left</template>
                <template v-else>Low stock — only {{ product?.stock }} left!</template>
              </span>
            </div>
          </div>

          <!-- Action buttons -->
          <div class="flex flex-col sm:flex-row gap-3 mb-4">
            <button class="btn-primary btn-lg flex-1">
              <ShoppingCart class="w-5 h-5" />
              Add to Cart
            </button>
            <button class="btn-accent btn-lg flex-1">
              Buy Now
            </button>
          </div>

          <!-- Wishlist + Share -->
          <div class="flex items-center gap-3 mb-6">
            <button
              class="btn-secondary btn-md flex-1"
            >
<!--              <Heart
                class="w-4 h-4"
                :class="isInWishlist(product.id) ? 'fill-danger-500 text-danger-500' : ''"
              />
              {{ isInWishlist(product.id) ? 'In Wishlist' : 'Add to Wishlist' }}-->
            </button>
            <button class="btn-secondary btn-md flex-1" @click="handleShare">
              <Share2 class="w-4 h-4" />
              Share
            </button>
          </div>

          <!-- Trust badges -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div
              v-for="badge in trustBadges"
              :key="badge.title"
              class="flex flex-col items-center text-center gap-1.5 p-3 rounded-xl bg-white border border-ink-200/70"
            >
              <component :is="badge.icon" class="w-5 h-5 text-primary-600" />
              <span class="text-xs font-semibold text-ink-900">{{ badge.title }}</span>
              <span class="text-2xs text-ink-500 leading-tight">{{ badge.desc }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs Skeleton -->
    <div class="section mt-12" v-if="loading">
      <!-- Tab buttons -->
      <div class="flex gap-4 border-b border-ink-200 pb-3">
        <div
          v-for="i in 3"
          :key="i"
          class="h-5 w-28 skeleton rounded-md"
        ></div>
      </div>

      <div class="py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left (Rating Summary) -->
          <div class="card p-5">
            <div class="skeleton h-6 w-40 mb-6"></div>

            <div class="flex gap-4 mb-6">
              <div class="space-y-2">
                <div class="skeleton h-10 w-16"></div>
                <div class="skeleton h-4 w-24"></div>
                <div class="skeleton h-3 w-20"></div>
              </div>

              <div class="flex-1">
                <div class="skeleton h-3 w-24 mb-3"></div>
                <div class="skeleton h-2 w-full rounded-full"></div>
              </div>
            </div>

            <div
              v-for="i in 5"
              :key="i"
              class="flex items-center gap-3 mb-3"
            >
              <div class="skeleton h-3 w-8"></div>
              <div class="flex-1 skeleton h-2 rounded-full"></div>
              <div class="skeleton h-3 w-6"></div>
            </div>

            <div class="skeleton h-10 w-full rounded-xl mt-6"></div>
          </div>

          <!-- Right (Reviews) -->
          <div class="lg:col-span-2">
            <!-- Filters -->
            <div class="flex gap-3 mb-6">
              <div class="skeleton h-10 w-40 rounded-xl"></div>

              <div class="flex gap-2 flex-wrap">
                <div
                  v-for="i in 5"
                  :key="i"
                  class="skeleton h-9 w-20 rounded-full"
                ></div>
              </div>
            </div>

            <!-- Reviews -->
            <div class="card p-5">
              <div
                v-for="i in 3"
                :key="i"
                class="pb-6 mb-6 border-b last:border-0"
              >
                <div class="flex items-center gap-3 mb-3">
                  <div class="skeleton w-12 h-12 rounded-full"></div>

                  <div class="flex-1">
                    <div class="skeleton h-4 w-36 mb-2"></div>
                    <div class="skeleton h-3 w-24"></div>
                  </div>
                </div>

                <div class="skeleton h-4 w-32 mb-3"></div>

                <div class="space-y-2">
                  <div class="skeleton h-3 w-full"></div>
                  <div class="skeleton h-3 w-11/12"></div>
                  <div class="skeleton h-3 w-4/5"></div>
                </div>
              </div>
            </div>

            <!-- Review Form -->
            <div class="card p-5 mt-6">
              <div class="skeleton h-6 w-40 mb-6"></div>

              <div class="space-y-4">
                <div class="skeleton h-10 w-full rounded-xl"></div>
                <div class="skeleton h-28 w-full rounded-xl"></div>

                <div class="grid grid-cols-2 gap-4">
                  <div class="skeleton h-10 rounded-xl"></div>
                  <div class="skeleton h-10 rounded-xl"></div>
                </div>

                <div class="skeleton h-11 w-40 rounded-xl"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Tabs section -->
    <div v-else class="section mt-12">
      <!-- Tab nav -->
      <div class="flex gap-1 border-b border-ink-200 overflow-x-auto no-scrollbar">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'px-5 py-3 text-sm font-semibold whitespace-nowrap border-b-2 transition-all',
            activeTab === tab.id
              ? 'border-primary-600 text-primary-700'
              : 'border-transparent text-ink-500 hover:text-ink-900'
          ]"
        >
          {{ tab.label }}
          <span v-if="tab.id === 'reviews'" class="ml-1.5 text-xs text-ink-400">(0)</span>
        </button>
      </div>

      <!-- Tab panels -->
      <div class="py-6">
        <!-- Specifications tab -->
        <div v-if="activeTab === 'specs'" class="max-w-2xl">
          <table class="w-full">
            <tbody>
            <tr
              v-for="(value,key) in product?.meta"
              :key="key"
              class="border-b border-ink-100 last:border-0"
            >
              <td class="py-3 pr-6 text-sm font-medium text-ink-500 w-40 align-top">{{key}}</td>
              <td class="py-3 text-sm text-ink-900">{{value}}</td>
            </tr>
            </tbody>
          </table>
        </div>

        <!-- Description tab -->
        <div v-else-if="activeTab === 'description'" class="max-w-3xl">
          <p class="text-sm text-ink-600 leading-relaxed mb-4">{{ product?.short_description }}</p>
          <p class="text-sm text-ink-600 leading-relaxed mb-4">{{ product?.description }}</p>
          <ul class="space-y-2">
            <li v-for="(value,key) in product?.meta" :key="key" class="flex items-center gap-2 text-sm text-ink-700">
              <Check class="w-4 h-4 text-success-600 shrink-0" />
              <span class="font-medium">{{key}}:</span>
              <span>{{value}}</span>
            </li>
          </ul>
        </div>

        <!-- Reviews tab -->
        <div v-else-if="activeTab === 'reviews'" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Rating summary (left) -->
          <div class="lg:col-span-1">
            <div class="card p-5 lg:sticky lg:top-24">
              <h3 class="text-lg font-bold text-ink-900 mb-4">Rating Summary</h3>

              <!-- Overall score -->
              <div class="flex items-center gap-4 mb-5">
                <div class="text-center">
                  <p class="text-4xl font-bold text-ink-900">{{ ratingSummary.average }}</p>
                  <RatingStars :model-value="ratingSummary.average" size="sm" class="mt-1" />
                  <p class="text-xs text-ink-500 mt-1.5">{{ ratingSummary.total }} reviews</p>
                </div>
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-1">
                    <span class="text-sm font-semibold text-success-600">{{ ratingSummary.satisfaction }}%</span>
                    <span class="text-xs text-ink-500">satisfied</span>
                  </div>
                  <div class="w-full h-2 rounded-full bg-ink-100 overflow-hidden">
                    <div class="h-full rounded-full bg-success-500 transition-all duration-500" :style="{ width: `${ratingSummary.satisfaction}%` }"></div>
                  </div>
                </div>
              </div>

              <!-- Star breakdown -->
              <div class="space-y-2">
                <div v-for="i in 5" :key="i" class="flex items-center gap-2">
                  <span class="text-xs text-ink-500 w-8 flex items-center gap-0.5">
                    {{ 6 - i }} <Star class="w-3 h-3 fill-warning-400 text-warning-400" />
                  </span>
                  <div class="flex-1 h-2 rounded-full bg-ink-100 overflow-hidden">
                    <div
                      class="h-full rounded-full bg-warning-400 transition-all duration-500"
                      :style="{ width: `${ratingSummary.total ? (ratingSummary.breakdown[i - 1] / ratingSummary.total) * 100 : 0}%` }"
                    ></div>
                  </div>
                  <span class="text-xs text-ink-500 w-8 text-right">{{ ratingSummary.breakdown[i - 1] }}</span>
                </div>
              </div>

              <button class="btn-primary btn-md w-full mt-5">
                Write a Review
              </button>
            </div>
          </div>

          <!-- Reviews list + filters (right) -->
          <div class="lg:col-span-2">
            <!-- Sort + Filter -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
              <div class="relative">
                <select v-model="reviewSort" class="input input-sm pr-8 appearance-none cursor-pointer">
                  <option v-for="opt in sortOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                </select>
                <ChevronDown class="w-4 h-4 text-ink-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" />
              </div>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="chip in filterChips"
                  :key="chip.value"
                  @click="reviewFilter = chip.value"
                  :class="['chip', reviewFilter === chip.value ? 'chip-active' : '']"
                >
                  {{ chip.label }}
                </button>
              </div>
            </div>

            <!-- Review list -->
            <div class="card p-5">
              <ReviewItem
                v-for="review in filteredReviews"
                :key="review.id"
                :review="review"
              />
              <p v-if="filteredReviews.length === 0" class="text-center text-sm text-ink-500 py-8">
                No reviews match this filter.
              </p>
            </div>

            <!-- Write a review form -->
            <div id="write-review" class="card p-5 mt-6">
              <h3 class="text-lg font-bold text-ink-900 mb-4">Write a Review</h3>

              <div v-if="reviewSubmitted" class="mb-4 p-3 rounded-xl bg-success-50 border border-success-200 flex items-center gap-2">
                <Check class="w-5 h-5 text-success-600" />
                <span class="text-sm font-medium text-success-700">Thank you! Your review has been submitted.</span>
              </div>

              <form @submit.prevent="submitReview" class="space-y-4">
                <!-- Rating selector -->
                <div>
                  <label class="label">Your Rating</label>
                  <div class="flex items-center gap-1">
                    <button
                      v-for="i in 5"
                      :key="i"
                      type="button"
                      @click="reviewForm.rating = i"
                      @mouseenter="reviewForm.rating = i"
                      class="p-0.5"
                    >
                      <Star
                        class="w-6 h-6 transition-colors"
                        :class="i <= reviewForm.rating ? 'fill-warning-400 text-warning-400' : 'text-ink-300'"
                      />
                    </button>
                    <span class="ml-2 text-sm font-medium text-ink-700">{{ reviewForm.rating }} / 5</span>
                  </div>
                </div>

                <!-- Title -->
                <div>
                  <label class="label" for="review-title">Review Title</label>
                  <input id="review-title" v-model="reviewForm.title" type="text" placeholder="Summarize your experience" class="input" />
                </div>

                <!-- Review text -->
                <div>
                  <label class="label" for="review-text">Your Review</label>
                  <textarea id="review-text" v-model="reviewForm.text" rows="4" placeholder="What did you like or dislike? How was the quality?" class="input resize-none"></textarea>
                </div>

                <!-- Pros / Cons -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="label" for="review-pros">Pros</label>
                    <input id="review-pros" v-model="reviewForm.pros" type="text" placeholder="What did you love?" class="input" />
                  </div>
                  <div>
                    <label class="label" for="review-cons">Cons</label>
                    <input id="review-cons" v-model="reviewForm.cons" type="text" placeholder="What could be better?" class="input" />
                  </div>
                </div>

                <button type="submit" class="btn-primary btn-md">
                  <MessageSquare class="w-4 h-4" />
                  Submit Review
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Related products skeleton -->
    <div v-if="loading" class="section mt-12">
      <div class="h-8 w-52 skeleton rounded mb-6"></div>

      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5">
        <div
          v-for="i in 5"
          :key="i"
          class="card p-3"
        >
          <div class="aspect-square skeleton rounded-xl mb-3"></div>
          <div class="h-4 skeleton rounded mb-2"></div>
          <div class="h-4 w-2/3 skeleton rounded mb-3"></div>
          <div class="h-6 w-20 skeleton rounded"></div>
        </div>
      </div>
    </div>
    <!-- Related products -->
    <div v-else-if="relatedProducts?.length" class="section mt-12">
      <SectionHeader title="Related Products" subtitle="You might also like these" />
      <Swiper
        :modules="[FreeMode]"
        :space-between="20"
        :free-mode="true"
        :grab-cursor="true"
        :free-mode-momentum="true"
        :breakpoints="{
            0: {slidesPerView: 1.2},
            640: {slidesPerView: 2},
            768: {slidesPerView: 3},
            1024: {slidesPerView: 4},
            1280: {slidesPerView: 5}
          }"
        class="p-4 sm:p-5"
      >
        <SwiperSlide
          v-for="rp in relatedProducts"
          :key="rp.id"
        >
          <ProductCard :product="rp" />
        </SwiperSlide>
      </Swiper>
    </div>

    <!-- Added to cart toast -->
    <Transition
      enter-active-class="transition-all duration-300 ease-out"
      enter-from-class="opacity-0 translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-300 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-4"
    >
      <div v-if="showAddedToast" class="fixed bottom-6 right-6 z-50 card p-4 flex items-center gap-3 shadow-card-hover">
        <div class="w-10 h-10 rounded-full bg-success-100 flex items-center justify-center shrink-0">
          <Check class="w-5 h-5 text-success-600" />
        </div>
        <div>
          <p class="text-sm font-semibold text-ink-900">Added to cart</p>
          <p class="text-xs text-ink-500">{{ product.name }} × {{ quantity }}</p>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.thumbs-swiper :deep(.swiper-wrapper) {
  align-items: stretch;
}

.thumbs-slide {
  width: 80px !important;
  flex-shrink: 0;
}
</style>
