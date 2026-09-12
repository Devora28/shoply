<script setup>
import {nextTick, ref} from 'vue'
import BaseBreadcrumb from '@/components/ui/BaseBreadcrumb.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import {
  Trash2, X, ShoppingBag, Tag, Truck, ShieldCheck, RotateCcw, ChevronRight, ShoppingCart, LogIn,
} from '@lucide/vue'
import {useCartStore} from "@/stores/cart.js";
import {useAuthStore} from "@/stores/auth.js";
import {formatPrice} from "@/utils/helpers.js";
import BaseModal from "@/components/ui/BaseModal.vue";
import {useRouter} from "vue-router";
const router = useRouter();
const breadcrumbItems = ['Cart'];
const showClearConfirm = ref(false);
const authStore = useAuthStore();
const cartStore = useCartStore();
const showLoginModal = ref(false);
const trustBadges = [
  { icon: Truck, label: 'Free shipping over $50' },
  { icon: ShieldCheck, label: 'Secure checkout' },
  { icon: RotateCcw, label: '30-day returns' },
  { icon: Tag, label: 'Price match guarantee' },
];
const promoCode = ref('');
const applyPromo = (code) => {
  const cleanCode = code.trim();
  if (!cleanCode) {
    return;
  }
  if (!authStore.isAuth){
    showLoginModal.value = true;
    return
  }
  cartStore.applyPromoCode(cleanCode);
  nextTick();
  promoCode.value = '';
}
const removePromo = () => {
  cartStore.removePromoCode()
}
const confirmClearCart = async () => {
  await cartStore.clearCart(authStore.isAuth);
  showClearConfirm.value = false;
}
</script>

<template>
  <div class="min-h-screen bg-ink-50/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Breadcrumb -->
      <BaseBreadcrumb :items="breadcrumbItems" class="mb-5" />
      <!-- Header -->
      <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
          <h1 class="text-2xl sm:text-3xl font-bold text-ink-900">
            Shopping Cart
          </h1>
          <!-- Items Skeleton -->
          <div v-if="cartStore.loading" class="skeleton h-6 w-16 rounded-full"></div>
          <!-- Items Count -->
          <span v-else-if="cartStore.totalItems > 0" class="badge-neutral">
            {{ cartStore.totalItems }}{{ cartStore.totalItems === 1 ? 'item' : 'items' }}
          </span>
        </div>
        <!-- Clear Cart Skeleton -->
        <div v-if="cartStore.loading" class="skeleton h-5 w-24 rounded"></div>
        <!-- Clear Cart -->
        <button
          v-else-if="cartStore.totalItems > 0"
          class="text-sm text-ink-500 hover:text-danger-600 transition-colors flex items-center gap-1.5"
          @click="showClearConfirm = true"
        >
          <Trash2 class="w-4 h-4" />
          <span class="hidden sm:inline">Clear cart</span>
        </button>
      </div>
      <!-- CART SKELETON -->
      <div v-if="cartStore.loading" class="grid lg:grid-cols-3 gap-6">
        <!-- LEFT -->
        <div class="lg:col-span-2 space-y-4">
          <div
            v-for="i in 3"
            :key="i"
            class="card p-4 sm:p-5 flex gap-4"
          >
            <!-- Image -->
            <div class="shrink-0 w-20 h-20 sm:w-28 sm:h-28 skeleton rounded-2xl"></div>
            <!-- Content -->
            <div class="flex-1 min-w-0 flex flex-col">
              <div class="space-y-2">
                <div class="skeleton h-3 w-20 rounded"></div>
                <div class="skeleton h-4 w-3/4 rounded"></div>
                <div class="skeleton h-4 w-1/2 rounded"></div>
              </div>
              <div class="mt-auto pt-4 flex items-center justify-between">
                <div class="skeleton h-8 w-24 rounded-lg"></div>
                <div class="space-y-2 flex flex-col items-end">
                  <div class="skeleton h-5 w-24 rounded"></div>
                  <div class="skeleton h-3 w-16 rounded"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- RIGHT -->
        <div class="lg:col-span-1">
          <div class="card p-5 sm:p-6">
            <div class="skeleton h-6 w-32 rounded mb-5"></div>
            <!-- Promo -->
            <div class="space-y-2 mb-5">
              <div class="skeleton h-4 w-20 rounded"></div>
              <div class="flex gap-2">
                <div class="skeleton h-9 flex-1 rounded-lg"></div>
                <div class="skeleton h-9 w-16 rounded-lg"></div>
              </div>
            </div>
            <div class="divider"></div>
            <!-- Summary -->
            <div class="space-y-4 mt-5">
              <div class="flex justify-between">
                <div class="skeleton h-4 w-16 rounded"></div>
                <div class="skeleton h-4 w-20 rounded"></div>
              </div>
              <div class="flex justify-between">
                <div class="skeleton h-4 w-16 rounded"></div>
                <div class="skeleton h-4 w-20 rounded"></div>
              </div>
              <div class="flex justify-between">
                <div class="skeleton h-4 w-16 rounded"></div>
                <div class="skeleton h-4 w-24 rounded"></div>
              </div>
            </div>
            <div class="divider my-4"></div>
            <div class="flex justify-between items-center mb-5">
              <div class="skeleton h-5 w-12 rounded"></div>
              <div class="skeleton h-7 w-28 rounded"></div>
            </div>
            <div class="skeleton h-12 w-full rounded-xl"></div>
          </div>
        </div>
      </div>
      <!-- EMPTY CART -->
      <div v-else-if="cartStore.totalItems === 0" class="py-16">
        <div class="card max-w-xl mx-auto p-8 sm:p-12 text-center">
          <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-ink-100 text-ink-400 mb-5">
            <ShoppingBag class="w-12 h-12" />
          </div>
          <h2 class="text-xl sm:text-2xl font-bold text-ink-900 mb-2">
            Your cart is empty
          </h2>
          <p class="text-ink-500 mb-8 max-w-sm mx-auto">
            Looks like you haven't added anything yet. Explore our curated
            collection and find something you'll love.
          </p>
          <BaseButton variant="primary" size="lg" to="/shop" class="!rounded-xl">
            <ShoppingCart class="w-5 h-5" />
            Start shopping
          </BaseButton>
        </div>
      </div>
      <!-- CART CONTENT -->
      <div v-else class="grid lg:grid-cols-3 gap-6">
        <!-- LEFT: cart items -->
        <div class="lg:col-span-2 space-y-4">
          <div
            v-for="item in cartStore.items"
            :key="item.id"
            class="card p-4 sm:p-5 flex gap-4"
          >
            <!-- Image -->
            <router-link
              :to="`/products/${item.product.id}/${item.product.slug}`"
              class="shrink-0 w-20 h-20 sm:w-28 sm:h-28 rounded-2xl overflow-hidden bg-ink-100"
            >
              <img
                :src="item.product.image"
                :alt="item.product.name"
                loading="lazy"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
              />
            </router-link>
            <!-- Details -->
            <div class="flex-1 min-w-0 flex flex-col">
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <span class="text-2xs font-medium text-ink-400">{{ item.product.brand.name }}</span>
                  <h3 class="text-sm sm:text-base font-semibold text-ink-900 clamp-2 leading-snug">
                    <router-link :to="`/products/${item.product.id}/${item.product.slug}`" class="hover:text-primary-700 transition-colors">
                      {{ item.product.name }}
                    </router-link>
                  </h3>
                  <div class="mt-1 flex items-center gap-2 text-xs text-ink-500">
                    <span v-if="item.variant?.attributes" class="inline-flex items-center gap-1">
                      <span class="w-3 h-3 rounded-full border border-ink-200 bg-ink-200"></span>
                      {{Object.values(item.variant.attributes).join(' - ')}}
                    </span>
                    <span class="text-ink-300">·</span>
                    <span class="text-success-600">In stock</span>
                  </div>
                </div>
                <button
                  class="p-2 rounded-xl text-ink-400 hover:text-danger-600 hover:bg-danger-50 transition-colors shrink-0"
                  aria-label="Remove item"
                  @click="cartStore.removeFromCart({item,isAuthenticated:authStore.isAuth})"
                >
                  <X class="w-5 h-5" />
                </button>
              </div>
              <!-- Footer: qty + price -->
              <div class="mt-auto pt-3 flex items-center justify-between gap-3">
                <div class="inline-flex items-center border border-ink-200 rounded-lg bg-white overflow-hidden">
                  <!-- Minus -->
                  <button
                    type="button"
                    class="w-8 h-8 flex items-center justify-center text-ink-500 hover:bg-ink-100 hover:text-ink-900 transition-colors"
                    @click.stop="
                      cartStore.updateQuantity({
                          item,
                          quantity: Number(item.quantity) - 1,
                          isAuthenticated: authStore.isAuth
                      })
                  "
                  >
                    −
                  </button>
                  <!-- Quantity -->
                  <span class="min-w-8 px-1 text-center text-xs font-semibold text-ink-900">
                    {{ item.quantity }}
                  </span>
                  <!-- Plus -->
                  <button
                    type="button"
                    class="w-8 h-8 flex items-center justify-center text-ink-500 hover:bg-ink-100 hover:text-ink-900 transition-colors"
                    @click.stop="
                      cartStore.updateQuantity({
                          item,
                          quantity: Number(item.quantity) + 1,
                          isAuthenticated: authStore.isAuth
                      })
                  "
                  >
                    +
                  </button>
                </div>
                <div class="text-right">
                  <div class="text-base sm:text-lg font-bold text-primary-700">
                    {{ formatPrice(cartStore.getItemFinalPrice(item) * item.quantity) }}
                  </div>
                  <div v-if="item.quantity > 1" class="text-xs text-ink-400">
                    {{ formatPrice(cartStore.getItemFinalPrice(item)) }} each
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Continue shopping link -->
          <div class="pt-2">
            <router-link
              to="/shop"
              class="inline-flex items-center gap-1.5 text-sm font-medium text-primary-700 hover:text-primary-800 transition-colors"
            >
              <ChevronRight class="w-4 h-4 rotate-180" />
              Continue shopping
            </router-link>
          </div>
        </div>
        <!-- RIGHT: order summary -->
        <div class="lg:col-span-1">
          <div class="sticky top-6 space-y-4">
            <!-- Summary card -->
            <div class="card p-5 sm:p-6">
              <h2 class="text-lg font-bold text-ink-900 mb-4">Order Summary</h2>
              <!-- Promo code -->
              <div class="mb-5">
                <label class="label">Promo code</label>
                <div v-if="!cartStore.appliedPromo" class="flex gap-2">
                  <div class="relative flex-1">
                    <Tag class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" />
                    <input
                      v-model="promoCode"
                      type="text"
                      placeholder="Enter code"
                      class="input input-sm pl-9"
                      @keyup.enter="applyPromo(promoCode)"
                    />
                  </div>
                  <button @click="applyPromo(promoCode)" :disabled="cartStore.promoCodeLoading" class="btn-secondary btn-sm">
                    Apply
                  </button>
                </div>
                <div v-else-if="authStore.isAuth" class="flex items-center justify-between bg-success-50 text-success-700 rounded-xl px-3 py-2 text-sm font-medium">
                  <span class="flex items-center gap-1.5">
                    <Tag class="w-4 h-4" />
                    {{ cartStore.appliedPromo }} applied
                  </span>
                  <button class="text-success-700 hover:text-success-800" @click="removePromo">
                    <X class="w-4 h-4" />
                  </button>
                </div>
                <p v-if="cartStore.promoError" class="error-text mt-1.5">{{ cartStore.promoError }}</p>
              </div>
              <div class="divider"></div>
              <!-- Line items -->
              <dl class="space-y-2.5 text-sm">
                <div class="flex justify-between">
                  <dt class="text-ink-500">Subtotal</dt>
                  <dd class="font-semibold text-ink-900">{{formatPrice(cartStore.subtotal)}}</dd>
                </div>
                <div v-if="cartStore.promoDiscount > 0" class="flex justify-between text-success-600">
                  <dt class="flex items-center gap-1">Discount</dt>
                  <dd class="font-semibold">-{{ formatPrice(cartStore.promoDiscount) }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-ink-500">Shipping</dt>
                  <dd class="font-semibold text-ink-900">At checkout</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-ink-500">Estimated tax</dt>
                  <dd class="font-semibold text-ink-900">At checkout</dd>
                </div>
              </dl>
              <div class="divider my-4"></div>
              <!-- Total -->
              <div class="flex justify-between items-baseline mb-5">
                <span class="text-base font-semibold text-ink-900">Total</span>
                <span class="text-2xl font-bold text-primary-700">{{ cartStore.appliedPromo ? formatPrice(cartStore.subtotal - cartStore.promoDiscount) : formatPrice(cartStore.subtotal)}}</span>
              </div>
              <!-- Checkout -->
              <BaseButton variant="primary" size="lg" to="/checkout" class="w-full !rounded-xl mb-3">
                <ShieldCheck class="w-5 h-5" />
                Proceed to checkout
              </BaseButton>
              <div class="flex items-center justify-center gap-1.5 text-xs text-ink-400">
                <ShieldCheck class="w-3.5 h-3.5" />
                Secure 256-bit SSL encryption
              </div>
            </div>
            <!-- Trust badges -->
            <div class="card p-4 grid grid-cols-2 gap-3">
              <div
                v-for="(badge, i) in trustBadges"
                :key="i"
                class="flex items-center gap-2 text-xs text-ink-600"
              >
                <component :is="badge.icon" class="w-4 h-4 text-primary-600 shrink-0" />
                <span class="font-medium leading-tight">{{ badge.label }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- YOU MAY ALSO LIKE -->
<!--      <section v-if="cartStore.totalItems > 0" class="mt-14">-->
<!--        <div class="flex items-center justify-between mb-6">-->
<!--          <h2 class="section-title">You may also like</h2>-->
<!--          <router-link-->
<!--            to="/shop"-->
<!--            class="text-sm font-medium text-primary-700 hover:text-primary-800 transition-colors flex items-center gap-1"-->
<!--          >-->
<!--            View all-->
<!--            <ArrowRight class="w-4 h-4" />-->
<!--          </router-link>-->
<!--        </div>-->
<!--        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">-->
<!--          <ProductCard-->
<!--            v-for="product in recommendations"-->
<!--            :key="product.id"-->
<!--            :product="product"-->
<!--          />-->
<!--        </div>-->
<!--      </section>-->
      <!-- CLEAR CART CONFIRMATION MODAL -->
      <teleport to="body">
        <transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-active-class="transition duration-150 ease-in"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div
            v-if="showClearConfirm"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink-900/50 backdrop-blur-sm"
            @click.self="showClearConfirm = false"
          >
            <div class="card max-w-sm w-full p-6 text-center animate-scale-in">
              <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-danger-50 text-danger-500 mb-4">
                <Trash2 class="w-7 h-7" />
              </div>
              <h3 class="text-lg font-bold text-ink-900 mb-2">Clear cart?</h3>
              <p class="text-sm text-ink-500 mb-6">
                This will remove all items from your cart. This action cannot be undone.
              </p>
              <div class="flex gap-3">
                <button class="btn-secondary btn-md flex-1 !rounded-xl" @click="showClearConfirm = false">
                  Cancel
                </button>
                <button class="btn-primary btn-md flex-1 !rounded-xl" @click="confirmClearCart">
                  Clear cart
                </button>
              </div>
            </div>
          </div>
        </transition>
      </teleport>
      <BaseModal v-model="showLoginModal" title="Sign in required" size="md">
        <div class="p-7 sm:p-9">
          <!-- Icon -->
          <div class="flex justify-center mb-6">
            <div
              class="w-18 h-18 rounded-2xl bg-primary-50 border border-primary-100
               flex items-center justify-center"
            >
              <LogIn class="w-8 h-8 text-primary-600" />
            </div>
          </div>
          <!-- Content -->
          <div class="text-center">
            <h3 class="text-xl sm:text-2xl font-bold text-ink-900">
              Sign in to continue
            </h3>
            <p class="mt-2.5 text-sm sm:text-base text-ink-500 leading-relaxed">
              You need to sign in to your account to continue.
            </p>
          </div>
          <!-- Actions -->
          <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3">
            <button
              type="button"
              class="btn-secondary btn-md sm:flex-1 !rounded-xl"
              @click="showLoginModal = false"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn-primary btn-md sm:flex-1 !rounded-xl"
              @click="
                router.push({
                  name: 'login.page',
                  query: {
                    redirect: router.currentRoute.value.fullPath
                  }
                })
              "
            >
              <LogIn class="w-4 h-4" />
              Sign in
            </button>
          </div>
        </div>
      </BaseModal>
    </div>
  </div>
</template>
