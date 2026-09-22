<script setup>
import {computed, nextTick, onMounted, ref, watch} from 'vue';
import ProductCard from '@/components/product/ProductCard.vue';
import BaseBreadcrumb from '@/components/ui/BaseBreadcrumb.vue';
import Pagination from '@/components/paginations/Pagination.vue';
import ProductGridSkeleton from '@/components/ui/ProductGridSkeleton.vue';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import {
  SlidersHorizontal, Grid, List, ChevronDown, X, Star,
  Tag, Truck, ShieldCheck, RotateCcw, ArrowUpDown, Check,
} from '@lucide/vue';
import {endpoints} from "@/api/endpoints.js";
import api from "@/api/axios.js";
import {formatPrice} from "@/utils/helpers.js";
import BaseToggle from "@/components/ui/BaseToggle.vue";
import {useRoute, useRouter} from "vue-router";
const breadcrumbItems = ['Shop'];
const route = useRoute();
const router = useRouter();
const products = ref([]);
const productsMinPrice = ref(0);
const productsMaxPrice = ref(0);
const categories = ref([]);
const brands = ref([]);
const total = ref(0);
const isInitialLoading = ref(false);
const isProductsLoading = ref(false);
const currentPage = ref(1);
const totalPages = ref(1);
const mainContent = ref(null);
const selectedCategories = ref([]);
const selectedBrands = ref([]);
const selectedMinRating = ref(0);
const selectedInStock = ref(false);
const selectedDiscount = ref(false);
const minPrice = ref(null);
const maxPrice = ref(null);
const appliedCategories = ref([]);
const appliedBrands = ref([]);
const appliedMinPrice = ref(null);
const appliedMaxPrice = ref(null);
const sortOptions = [
  { value: 'popular', label: 'Popular' },
  { value: 'newest', label: 'Newest' },
  { value: 'price_asc', label: 'Price: Low to High' },
  { value: 'price_desc', label: 'Price: High to Low' },
  { value: 'rating', label: 'Best Rated' },
]
const sortBy = ref('popular');
const sortMenuOpen = ref(false);
const selectSort = async (value) => {
  sortBy.value = value;
  sortMenuOpen.value = false;
  currentPage.value = 1;
  await updateUrl();
  await fetchShopData();
}
const selectRating = async (value) => {
  selectedMinRating.value = selectedMinRating.value === value ? 0 : value;
  currentPage.value = 1;
  await updateUrl();
  await fetchShopData();
}
function readFiltersFromUrl() {
  const query = route.query;
  const categoriesFromUrl = query.categories
    ? String(query.categories).split(',')
    : [];
  const brandsFromUrl = query.brands
    ? String(query.brands).split(',')
    : [];
  selectedCategories.value = categoriesFromUrl;
  selectedBrands.value = brandsFromUrl;
  minPrice.value = query.min_price
    ? Number(query.min_price)
    : null;
  maxPrice.value = query.max_price
    ? Number(query.max_price)
    : null;
  selectedMinRating.value = query.min_rating
    ? Number(query.min_rating)
    : 0;
  selectedInStock.value = query.in_stock === '1';
  selectedDiscount.value = query.discount === '1';
  sortBy.value = query.sort || 'popular';
  currentPage.value = query.page
    ? Number(query.page)
    : 1;
  appliedCategories.value = [...selectedCategories.value];
  appliedBrands.value = [...selectedBrands.value];
  appliedMinPrice.value = minPrice.value;
  appliedMaxPrice.value = maxPrice.value;
}
async function updateUrl() {
  const query = {};
  if (selectedCategories.value.length) {
    query.categories = selectedCategories.value.join(',');
  }
  if (selectedBrands.value.length) {
    query.brands = selectedBrands.value.join(',');
  }
  if (minPrice.value !== null && minPrice.value !== '') {
    query.min_price = minPrice.value;
  }
  if (maxPrice.value !== null && maxPrice.value !== '') {
    query.max_price = maxPrice.value;
  }
  if (selectedMinRating.value) {
    query.min_rating = selectedMinRating.value;
  }
  if (selectedInStock.value) {
    query.in_stock = '1';
  }
  if (selectedDiscount.value) {
    query.discount = '1';
  }
  if (sortBy.value !== 'popular') {
    query.sort = sortBy.value;
  }
  if (currentPage.value > 1) {
    query.page = currentPage.value;
  }
  await router.replace({
    path: '/shop',
    query,
  });
}
async function applyFilters() {
  mobileFiltersOpen.value = false;
  currentPage.value = 1;
  await updateUrl();
  appliedCategories.value = [...selectedCategories.value];
  appliedBrands.value = [...selectedBrands.value];
  appliedMinPrice.value = minPrice.value;
  appliedMaxPrice.value = maxPrice.value;
  await fetchShopData();
}
const categoriesChanged = computed(() => {
  return JSON.stringify(selectedCategories.value) !==
    JSON.stringify(appliedCategories.value);
});
const brandsChanged = computed(() => {
  return JSON.stringify(selectedBrands.value) !==
    JSON.stringify(appliedBrands.value);
});
const priceChanged = computed(() => {
  return minPrice.value !== appliedMinPrice.value ||
    maxPrice.value !== appliedMaxPrice.value;
});
const activeFilterCount = computed(() => {
  return (
    appliedCategories.value.length +
    appliedBrands.value.length +
    (appliedMinPrice.value !== null ? 1 : 0) +
    (appliedMaxPrice.value !== null ? 1 : 0) +
    (selectedMinRating.value > 0 ? 1 : 0) +
    (selectedInStock.value ? 1 : 0) +
    (selectedDiscount.value ? 1 : 0)
  );
});
async function clearAllFilters() {
  selectedCategories.value = [];
  selectedBrands.value = [];
  appliedCategories.value = [];
  appliedBrands.value = [];
  minPrice.value = null;
  maxPrice.value = null;
  appliedMinPrice.value = null;
  appliedMaxPrice.value = null;
  selectedMinRating.value = 0;
  selectedInStock.value = false;
  selectedDiscount.value = false;
  currentPage.value = 1;
  await updateUrl();
  await fetchShopData();
}
async function removeCategoryFilter(category) {
  appliedCategories.value = appliedCategories.value.filter(cat => cat !== category);
  selectedCategories.value = [...appliedCategories.value];
  currentPage.value = 1;
  await updateUrl();
  await fetchShopData();
}
async function removeBrandFilter(brand) {
  appliedBrands.value = appliedBrands.value.filter(item => item !== brand);
  selectedBrands.value = [...appliedBrands.value];
  currentPage.value = 1;
  await updateUrl();
  await fetchShopData();
}
async function removeRatingFilter() {
  selectedMinRating.value = 0;
  currentPage.value = 1;
  await updateUrl();
  await fetchShopData();
}
async function removeInStockFilter() {
  selectedInStock.value = false;
  currentPage.value = 1;
  await updateUrl();
  await fetchShopData();
}
async function removeDiscountFilter() {
  selectedDiscount.value = false;
  currentPage.value = 1;
  await updateUrl();
  await fetchShopData();
}
const viewMode = ref('grid');
const mobileFiltersOpen = ref(false)
const ratingOptions = [
  { value: 4, label: '4 & Up' },
  { value: 3, label: '3 & Up' },
  { value: 2, label: '2 & Up' },
  { value: 1, label: '1 & Up' },
]
const trustBadges = [
  { icon: Truck, label: 'Free Shipping Over $50' },
  { icon: ShieldCheck, label: 'Secure Payment' },
  { icon: RotateCcw, label: '30-Day Returns' },
  { icon: Tag, label: 'Best Price Guarantee' },
]
const fetchShopData = async (initial = false) => {
  const token = localStorage.getItem('auth_token');
  try {
    if (initial) {
      isInitialLoading.value = true;
    } else {
      isProductsLoading.value = true;
    }
    const response = await api.get(
      endpoints.shop,
      {
      params: {
        page: currentPage.value,
        categories: appliedCategories.value,
        brands: appliedBrands.value,
        min_price: appliedMinPrice.value,
        max_price: appliedMaxPrice.value,
        min_rating: selectedMinRating.value || null,
        in_stock: selectedInStock.value,
        discount: selectedDiscount.value,
        sort: sortBy.value,
      },
      headers: {
        Authorization: `Bearer ${token}`,
      }
    });
    products.value = response.data.data.products.data;
    categories.value = response.data.data.categories;
    brands.value = response.data.data.brands;
    productsMinPrice.value = response.data.data.min_price;
    productsMaxPrice.value = response.data.data.max_price;
    total.value = response.data.data.products.total;
    currentPage.value = response.data.data.products.current_page;
    totalPages.value = response.data.data.products.last_page;
  }
  catch (error) {
    console.error(error);
  }
  finally {
    isInitialLoading.value = false;
    isProductsLoading.value = false;
  }
}
onMounted(async () => {
  readFiltersFromUrl();
  await fetchShopData(true);
});
watch(currentPage,
  async () => {
    await updateUrl();
    await fetchShopData();
    await nextTick();
    mainContent.value?.scrollIntoView({
      behavior: 'smooth',
      block: 'start',
    });
  }
)
</script>

<template>
  <div class="min-h-screen bg-ink-50/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Breadcrumb -->
      <BaseBreadcrumb :items="breadcrumbItems" class="mb-5" />
      <!-- Page header -->
      <div class="flex flex-col gap-2 mb-6">
        <div class="flex items-center gap-3">
          <h1 class="text-2xl sm:text-3xl font-bold text-ink-900">All Products</h1>
          <span v-if="isProductsLoading || isInitialLoading" class="skeleton w-16 h-6 rounded-full"></span>
          <span v-else class="badge-neutral">{{ total }} items</span>
        </div>
        <p ref="mainContent" class="text-sm text-ink-500 clamp-1">
          Discover premium <!--{{ pageTitle.toLowerCase() }}--> curated for you — top brands, best prices, and fast delivery.
        </p>
      </div>
      <!-- Trust badges strip -->
      <div class="hidden sm:flex items-center justify-between gap-4 mb-6 card px-5 py-3">
        <div
          v-for="(badge, i) in trustBadges"
          :key="i"
          class="flex items-center gap-2 text-sm text-ink-600"
        >
          <component :is="badge.icon" class="w-4 h-4 text-primary-600" />
          <span class="font-medium">{{ badge.label }}</span>
        </div>
      </div>
      <div class="flex gap-6">
        <!-- SIDEBAR FILTERS (desktop) -->
        <aside class="hidden lg:block w-64 shrink-0">
          <div class="sticky top-6 space-y-5">
            <div class="card p-5">
              <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                  <SlidersHorizontal class="w-5 h-5 text-primary-600" />
                  <h2 class="font-bold text-ink-900">Filters</h2>
                </div>
                <button
                  v-if="activeFilterCount > 0"
                  class="text-xs font-medium text-primary-600 hover:text-primary-700"
                  @click="clearAllFilters"
                >
                  Clear all
                </button>
              </div>
              <!-- Other filters -->
              <div class="space-y-3 pb-5 border-b border-ink-200">
                <BaseToggle
                  v-model="selectedInStock"
                  label="In Stock"
                  reverse
                  @update:modelValue="applyFilters"
                />
                <BaseToggle
                  v-model="selectedDiscount"
                  label="Discount Only"
                  reverse
                  @update:modelValue="applyFilters"
                />
              </div>
              <!-- Categories -->
              <div class="py-5 border-b border-ink-200">
                <h3 class="text-sm font-semibold text-ink-900 mb-3">
                  Categories
                </h3>
                <div class="space-y-2.5 max-h-36 overflow-y-auto pr-1">
                  <template v-if="isInitialLoading">
                    <div
                      v-for="i in 5"
                      :key="i"
                      class="flex items-center gap-2.5"
                    >
                      <span class="skeleton w-4 h-4 rounded"></span>
                      <span class="skeleton h-4 rounded-md" :class="i % 2 ? 'w-24' : 'w-32'"></span>
                    </div>
                  </template>
                  <template v-else>
                    <label
                      v-for="category in categories"
                      :key="category.id"
                      class="flex items-center gap-2.5 cursor-pointer"
                    >
                      <input
                        type="checkbox"
                        :value="category.slug"
                        v-model="selectedCategories"
                        class="w-4 h-4 rounded border-ink-300 text-primary-600 focus:ring-primary-500"
                      />
                      <span class="text-sm text-ink-600">
                      {{ category.name }}
                    </span>
                    </label>
                  </template>
                </div>
                <div
                  v-if="categoriesChanged"
                  class="flex justify-end mt-3"
                >
                  <button
                    type="button"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 bg-primary-50 hover:bg-primary-100 transition-colors"
                    @click="applyFilters"
                  >
                    Apply
                  </button>
                </div>
              </div>
              <!-- Brands -->
              <div class="py-5 border-b border-ink-200">
                <h3 class="text-sm font-semibold text-ink-900 mb-3">
                  Brands
                </h3>
                <div class="space-y-2.5 max-h-36 overflow-y-auto pr-1">
                  <template v-if="isInitialLoading">
                    <div
                      v-for="i in 5"
                      :key="i"
                      class="flex items-center gap-2.5"
                    >
                      <span class="skeleton w-4 h-4 rounded"></span>
                      <span class="skeleton h-4 rounded-md" :class="i % 2 ? 'w-20' : 'w-28'"></span>
                    </div>
                  </template>
                  <template v-else>
                    <label
                      v-for="brand in brands"
                      :key="brand.id ?? brand"
                      class="flex items-center gap-2.5 cursor-pointer"
                    >
                      <input
                        type="checkbox"
                        :value="brand.slug"
                        v-model="selectedBrands"
                        class="w-4 h-4 rounded border-ink-300 text-primary-600 focus:ring-primary-500"
                      />
                      <span class="text-sm text-ink-600">
                      {{brand.name}}
                    </span>
                    </label>
                  </template>
                </div>
                <div
                  v-if="brandsChanged"
                  class="flex justify-end mt-3"
                >
                  <button
                    type="button"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 bg-primary-50 hover:bg-primary-100 transition-colors"
                    @click="applyFilters"
                  >
                    Apply
                  </button>
                </div>
              </div>
              <!-- Price -->
              <div class="py-5 border-b border-ink-200">
                <h3 class="text-sm font-semibold text-ink-900 mb-3">
                  Price
                </h3>
                <div class="grid grid-cols-2 gap-2">
                  <input
                    v-model.number="minPrice"
                    type="number"
                    placeholder="Min"
                    class="input w-full"
                  />
                  <input
                    v-model.number="maxPrice"
                    type="number"
                    placeholder="Max"
                    class="input w-full"
                  />
                </div>
                <div class="flex justify-between mt-2 text-xs text-ink-400">
                  <span v-if="isProductsLoading || isInitialLoading" class="skeleton w-16 h-4 rounded-md"></span>
                  <span v-else>{{formatPrice(productsMinPrice)}}</span>
                  <span v-if="isProductsLoading || isInitialLoading" class="skeleton w-16 h-4 rounded-md"></span>
                  <span v-else>{{formatPrice(productsMaxPrice)}}</span>
                </div>
                <div v-if="priceChanged" class="flex justify-end mt-3">
                  <button
                    type="button"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium text-primary-600 bg-primary-50 hover:bg-primary-100"
                    @click="applyFilters"
                  >
                    Apply
                  </button>
                </div>
              </div>
              <!-- Rating -->
              <div class="py-5">
                <h3 class="text-sm font-semibold text-ink-900 mb-3">
                  Rating
                </h3>
                <div class="space-y-2">
                  <button
                    v-for="option in ratingOptions"
                    :key="option.value"
                    type="button"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium transition-all"
                    :class="selectedMinRating === option.value
                    ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-200'
                    : 'text-ink-600 hover:bg-ink-100'"
                    @click="selectRating(option.value)"
                  >
                    <div class="flex items-center gap-0.5">
                      <Star
                        v-for="i in 5"
                        :key="i"
                        class="w-3.5 h-3.5"
                        :class="i <= option.value
                        ? 'text-amber-400 fill-current'
                        : 'text-ink-200'"
                      />
                    </div>
                    <span>{{ option.label }}</span>
                    <Check
                      class="w-4 h-4 ml-auto"
                      :class="selectedMinRating === option.value
                      ? 'text-primary-600'
                      : 'invisible'"
                    />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </aside>
        <!-- MAIN CONTENT -->
        <div class="flex-1 min-w-0">
          <!-- Toolbar -->
          <div class="card px-4 py-3 mb-5 flex flex-wrap items-center justify-between gap-3">
            <!-- Result count + mobile filter button -->
            <div class="flex items-center gap-3">
              <button
                class="btn-secondary btn-sm lg:hidden flex items-center gap-2"
                @click="mobileFiltersOpen = true"
              >
                <SlidersHorizontal class="w-4 h-4" />
                <span>Filters</span>
                <span
                  v-if="activeFilterCount > 0"
                  class="badge-primary text-[10px] px-1.5 py-0.5"
                >{{ activeFilterCount }}</span>
              </button>
              <p v-if="isProductsLoading || isInitialLoading" class="hidden sm:block">
                <span class="skeleton inline-block w-24 h-5 rounded-md"></span>
              </p>
              <p v-else class="hidden sm:block text-sm text-ink-600">
                <span class="font-semibold text-ink-900">{{ total }}</span>
                results
              </p>
            </div>
            <!-- Sort + view toggle -->
            <div class="flex items-center gap-2">
              <!-- Sort dropdown -->
              <div class="relative">
                <button
                  class="btn-secondary btn-sm flex items-center gap-2"
                  @click="sortMenuOpen = !sortMenuOpen"
                >
                  <ArrowUpDown class="w-4 h-4" />
                  <span class="hidden sm:inline">
                    {{ sortOptions.find(option => option.value === sortBy)?.label }}
                  </span>
                  <span class="sm:hidden">Sort</span>
                  <ChevronDown
                    class="w-4 h-4 transition-transform"
                    :class="{ 'rotate-180': sortMenuOpen }"
                  />
                </button>
                <transition
                  enter-active-class="transition duration-150 ease-out"
                  enter-from-class="opacity-0 scale-95 -translate-y-1"
                  enter-to-class="opacity-100 scale-100 translate-y-0"
                  leave-active-class="transition duration-100 ease-in"
                  leave-from-class="opacity-100 scale-100"
                  leave-to-class="opacity-0 scale-95 -translate-y-1"
                >
                  <div
                    v-if="sortMenuOpen"
                    class="absolute right-0 mt-2 w-52 card p-1.5 z-30 shadow-lg"
                  >
                    <button
                      v-for="opt in sortOptions"
                      :key="opt.value"
                      @click="selectSort(opt.value)"
                      class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-sm font-medium transition-colors"
                      :class="sortBy === opt.value
                        ? 'bg-primary-50 text-primary-700'
                        : 'text-ink-600 hover:bg-ink-100'"
                    >
                      <span>{{ opt.label }}</span>
                      <Check v-if="sortBy === opt.value" class="w-4 h-4" />
                    </button>
                  </div>
                </transition>
              </div>
              <!-- View toggle -->
              <div class="flex items-center bg-ink-100 rounded-xl p-1">
                <button
                  class="p-1.5 rounded-lg transition-all"
                  :class="viewMode === 'grid' ? 'bg-white text-primary-600 shadow-sm' : 'text-ink-400'"
                  @click="viewMode = 'grid'"
                  aria-label="Grid view"
                >
                  <Grid class="w-4 h-4" />
                </button>
                <button
                  class="p-1.5 rounded-lg transition-all"
                  :class="viewMode === 'list' ? 'bg-white text-primary-600 shadow-sm' : 'text-ink-400'"
                  @click="viewMode = 'list'"
                  aria-label="List view"
                >
                  <List class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
          <!-- Active filter chips -->
          <div v-if="activeFilterCount > 0" class="flex flex-wrap items-center gap-2 mb-4">
            <span
              v-for="cat in appliedCategories"
              :key="'chip-cat-' + cat"
              class="chip chip-active flex items-center gap-1.5"
              @click="removeCategoryFilter(cat)"
            >
              {{categories.find(c => c.slug === cat)?.name}}
              <X class="w-3.5 h-3.5" />
            </span>
            <span
              v-for="brand in appliedBrands"
              :key="'chip-brand-' + brand"
              class="chip chip-active flex items-center gap-1.5"
              @click="removeBrandFilter(brand)"
            >
              {{brands.find(b => b.slug === brand)?.name}}
              <X class="w-3.5 h-3.5" />
            </span>
            <span
              v-if="selectedMinRating > 0"
              class="chip chip-active flex items-center gap-1.5"
              @click="removeRatingFilter"
            >
              <Star class="w-3.5 h-3.5 fill-current" />
              {{ selectedMinRating }}+ Stars
              <X class="w-3.5 h-3.5" />
            </span>
            <span
              v-if="selectedInStock"
              class="chip chip-active flex items-center gap-1.5"
              @click="removeInStockFilter"
            >
              In Stock
              <X class="w-3.5 h-3.5" />
            </span>
            <span
              v-if="selectedDiscount"
              class="chip chip-active flex items-center gap-1.5"
              @click="removeDiscountFilter"
            >
              Discount only
              <X class="w-3.5 h-3.5" />
            </span>
            <button
              class="text-sm font-medium text-primary-600 hover:text-primary-700 hover:underline ml-1"
              @click="clearAllFilters"
            >
              Clear all
            </button>
          </div>
          <!-- Product grid / list -->
          <ProductGridSkeleton v-if="isProductsLoading || isInitialLoading" :count="16" />
          <div
            v-else-if="products.length > 0"
            :class="viewMode === 'grid'
              ? 'grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4'
              : 'flex flex-col gap-3'"
          >
            <ProductCard
              v-for="product in products"
              :key="product.id"
              :product="product"
              :class="viewMode === 'list' ? 'flex-row sm:items-stretch' : ''"
            />
          </div>
          <!-- Empty state -->
          <div
            v-else
            class="card p-12 text-center min-h-[600px] flex flex-col items-center justify-center"
          >
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-ink-100 flex items-center justify-center">
              <SlidersHorizontal class="w-8 h-8 text-ink-400" />
            </div>
            <h3 class="text-lg font-semibold text-ink-900 mb-1">
              No products found
            </h3>
            <p class="text-sm text-ink-500 mb-4">
              Try adjusting your filters to see more results.
            </p>
          </div>
          <!-- Pagination -->
          <div class="mt-8">
            <Pagination
              v-model="currentPage"
              :total-pages="totalPages"
              :disabled="isLoading"
            />
          </div>
        </div>
      </div>
    </div>
    <!-- MOBILE FILTER DRAWER -->
    <TransitionRoot as="template" :show="mobileFiltersOpen">
      <Dialog as="div" class="relative z-50 lg:hidden" @close="mobileFiltersOpen = false">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-ink-900/50 backdrop-blur-sm" aria-hidden="true" />
        </TransitionChild>
        <div class="fixed inset-0 flex">
          <TransitionChild
            as="template"
            enter="transition duration-300 ease-out-expo"
            enter-from="translate-x-full"
            enter-to="translate-x-0"
            leave="transition duration-200 ease-in"
            leave-from="translate-x-0"
            leave-to="translate-x-full"
          >
            <DialogPanel class="ml-auto h-full w-full max-w-sm bg-white shadow-2xl flex flex-col">
              <!-- Drawer header -->
              <div class="flex items-center justify-between px-5 py-4 border-b border-ink-200">
                <div class="flex items-center gap-2">
                  <SlidersHorizontal class="w-5 h-5 text-primary-600" />
                  <h2 class="text-lg font-bold text-ink-900">Filters</h2>
                  <span
                    v-if="activeFilterCount > 0"
                    class="badge-primary text-[10px] px-1.5 py-0.5"
                  >{{ activeFilterCount }}</span>
                </div>
                <button
                  class="p-2 rounded-xl hover:bg-ink-100 transition-colors"
                  @click="mobileFiltersOpen = false"
                  aria-label="Close filters"
                >
                  <X class="w-5 h-5 text-ink-500" />
                </button>
              </div>
              <!-- Drawer body (scrollable) -->
              <div class="flex-1 overflow-y-auto p-5">
                <div class="space-y-6">
                  <!-- Categories -->
                  <div>
                    <h3 class="text-sm font-semibold text-ink-900 mb-3">
                      Categories
                    </h3>
                    <div class="space-y-3">
                      <label
                        v-for="category in categories"
                        :key="category.id"
                        class="flex items-center gap-3 cursor-pointer"
                      >
                        <input
                          type="checkbox"
                          :value="category.slug"
                          v-model="selectedCategories"
                          class="w-4 h-4 rounded border-ink-300 text-primary-600 focus:ring-primary-500"
                        />
                        <span class="text-sm text-ink-700">
                          {{ category.name }}
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="border-t border-ink-200"></div>
                  <!-- Brands -->
                  <div>
                    <h3 class="text-sm font-semibold text-ink-900 mb-3">
                      Brands
                    </h3>
                    <div class="space-y-3">
                      <label
                        v-for="brand in brands"
                        :key="brand.id"
                        class="flex items-center gap-3 cursor-pointer"
                      >
                        <input
                          type="checkbox"
                          :value="brand.slug"
                          v-model="selectedBrands"
                          class="w-4 h-4 rounded border-ink-300 text-primary-600 focus:ring-primary-500"
                        />
                        <span class="text-sm text-ink-700">
                          {{brand.name}}
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="border-t border-ink-200"></div>
                  <!-- Price -->
                  <div>
                    <h3 class="text-sm font-semibold text-ink-900 mb-3">
                      Price
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                      <input
                        v-model.number="minPrice"
                        type="number"
                        placeholder="Min"
                        class="input w-full"
                      />
                      <input
                        v-model.number="maxPrice"
                        type="number"
                        placeholder="Max"
                        class="input w-full"
                      />
                    </div>
                  </div>
                  <div class="border-t border-ink-200"></div>
                  <!-- Rating -->
                  <div>
                    <h3 class="text-sm font-semibold text-ink-900 mb-3">
                      Rating
                    </h3>
                    <div class="space-y-3">
                      <label
                        v-for="option in ratingOptions"
                        :key="option.value"
                        class="flex items-center gap-3 cursor-pointer"
                      >
                        <input
                          type="radio"
                          name="mobile-rating"
                          :value="option.value"
                          v-model="selectedMinRating"
                          class="w-4 h-4 text-primary-600 focus:ring-primary-500"
                        />
                        <Star class="w-4 h-4 fill-current text-amber-400" />
                        <span class="text-sm text-ink-700">
                          {{ option.label }}
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="border-t border-ink-200"></div>
                  <!-- Other filters -->
                  <div class="space-y-4">
                    <label class="flex items-center justify-between">
                      <span class="text-sm text-ink-700">
                        In Stock
                      </span>
                      <input
                        v-model="selectedInStock"
                        type="checkbox"
                        class="w-4 h-4 rounded border-ink-300 text-primary-600 focus:ring-primary-500"
                      />
                    </label>
                    <label class="flex items-center justify-between">
                      <span class="text-sm text-ink-700">
                        Discount Only
                      </span>
                      <input
                        v-model="selectedDiscount"
                        type="checkbox"
                        class="w-4 h-4 rounded border-ink-300 text-primary-600 focus:ring-primary-500"
                      />
                    </label>
                  </div>
                </div>
              </div>
              <!-- Drawer footer -->
              <div class="px-5 py-4 border-t border-ink-200 flex gap-3">
                <button class="btn-secondary flex-1" @click="clearAllFilters">
                  Clear all
                </button>
                <button class="btn-primary flex-1" @click="applyFilters">
                  Show results
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>
