<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseBreadcrumb from '@/components/ui/BaseBreadcrumb.vue'
import StepProgress from '@/components/ui/StepProgress.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseInput from '@/components/ui/BaseInput.vue'
import {
  MapPin, CreditCard, Wallet, Check, ChevronRight, ChevronLeft,
  Lock, Truck, ShieldCheck, Plus, Banknote, Smartphone,
} from '@lucide/vue';
import {formatPrice} from "@/utils/helpers.js";
const router = useRouter()
const breadcrumbItems = ['Checkout']
const steps = ['Shipping', 'Payment', 'Review']
const currentStep = ref(1)
const addresses = ref([])
const showNewAddressForm = ref(false)
const newAddress = ref({
  title: 'Home',
  receiver_name: '',
  company: '',
  phone: '',
  country: '',
  state: '',
  city: '',
  postal_code: '',
  address: '',
  is_default: false,
})
const selectedAddressId = ref(null);
const paymentMethods = [
  { id: 'card', label: 'Credit / Debit Card', icon: CreditCard, desc: 'Visa, Mastercard, Amex' },
  { id: 'paypal', label: 'PayPal', icon: Wallet, desc: 'Pay with your PayPal balance' },
  { id: 'applePay', label: 'Apple Pay', icon: Smartphone, desc: 'Fast, secure checkout' },
  { id: 'klarna', label: 'Klarna', icon: Banknote, desc: 'Pay in 4 interest-free installments' },
]
const selectedPayment = ref('card')
const cardDetails = ref({
  number: '', name: '', expiry: '', cvc: '',
})
/*const shippingCost = computed(() => (cartSubtotal.value > 0 ? 15 : 0))
const tax = computed(() => Math.round(cartSubtotal.value * 0.08))
const grandTotal = computed(() => cartSubtotal.value + shippingCost.value + tax.value)*/
function nextStep() {
  if (currentStep.value < 3) currentStep.value++
}
function prevStep() {
  if (currentStep.value > 1) currentStep.value--
}
const orderNumber = 'ORD-' + Math.floor(100000 + Math.random() * 900000)
</script>

<template>
  <div class="min-h-screen bg-ink-50/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Breadcrumb -->
      <BaseBreadcrumb :items="breadcrumbItems" class="mb-5" />
      <!-- Header -->
      <div class="flex items-center gap-2 mb-6">
        <Lock class="w-5 h-5 text-primary-600" />
        <h1 class="text-2xl sm:text-3xl font-bold text-ink-900">Secure Checkout</h1>
      </div>
      <!-- Step progress -->
      <div class="card p-4 sm:p-5 mb-6">
        <StepProgress :step="currentStep" :steps="steps" />
      </div>
      <div class="grid lg:grid-cols-3 gap-6">
        <!-- ============ LEFT: step content ============ -->
        <div class="lg:col-span-2">
          <!-- STEP 1: SHIPPING -->
          <div v-if="currentStep === 1" class="space-y-5 animate-slide-up">
            <div class="card p-5 sm:p-6">
              <div class="flex items-center gap-2 mb-5">
                <MapPin class="w-5 h-5 text-primary-600" />
                <h2 class="text-lg font-bold text-ink-900">Shipping Address</h2>
              </div>
              <!-- Saved addresses -->
              <div class="space-y-3">
                <button
                  v-for="addr in addresses"
                  :key="addr.id"
                  class="w-full text-left p-4 rounded-2xl border-2 transition-all"
                  :class="selectedAddressId === addr.id
                    ? 'border-primary-500 bg-primary-50/50 ring-1 ring-primary-200'
                    : 'border-ink-200 hover:border-ink-300'"
                  @click="selectedAddressId = addr.id"
                >
                  <div class="flex items-start gap-3">
                    <div
                      class="mt-0.5 flex items-center justify-center w-5 h-5 rounded-full border-2 shrink-0 transition-colors"
                      :class="selectedAddressId === addr.id
                        ? 'border-primary-600 bg-primary-600'
                        : 'border-ink-300'"
                    >
                      <Check v-if="selectedAddressId === addr.id" class="w-3 h-3 text-white" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-ink-900">{{ addr.label }}</span>
                        <span v-if="addr.isDefault" class="badge-primary text-[10px]">Default</span>
                      </div>
                      <p class="text-sm font-medium text-ink-800">{{ addr.name }}</p>
                      <p class="text-sm text-ink-500">{{ addr.line1 }}</p>
                      <p class="text-sm text-ink-500">{{ addr.city }}, {{ addr.state }} {{ addr.zip }}</p>
                      <p class="text-sm text-ink-500">{{ addr.phone }}</p>
                    </div>
                  </div>
                </button>
              </div>
              <!-- Add new address toggle -->
              <button
                v-if="!showNewAddressForm"
                class="mt-3 w-full p-4 rounded-2xl border-2 border-dashed border-ink-200 text-ink-500 hover:border-primary-300 hover:text-primary-700 transition-colors flex items-center justify-center gap-2 font-medium"
                @click="showNewAddressForm = true"
              >
                <Plus class="w-5 h-5" />
                Add new address
              </button>
              <!-- New address form -->
              <div v-else class="mt-4 p-4 rounded-2xl bg-ink-50/60 border border-ink-200">
                <h3 class="font-semibold text-ink-900 mb-4">New Address</h3>
                <div class="grid sm:grid-cols-2 gap-3">
                  <BaseInput v-model="newAddress.label" label="Label" placeholder="Home, Work..." size="sm" />
                  <BaseInput v-model="newAddress.name" label="Full name" placeholder="Jordan Carter" size="sm" />
                  <BaseInput v-model="newAddress.phone" label="Phone" placeholder="+1 (555) 000-0000" size="sm" />
                  <BaseInput v-model="newAddress.line1" label="Address" placeholder="Street address" size="sm" />
                  <BaseInput v-model="newAddress.city" label="City" placeholder="Portland" size="sm" />
                  <div class="grid grid-cols-2 gap-3">
                    <BaseInput v-model="newAddress.state" label="State" placeholder="OR" size="sm" />
                    <BaseInput v-model="newAddress.zip" label="ZIP" placeholder="97201" size="sm" />
                  </div>
                </div>
                <div class="flex gap-2 mt-4">
                  <button class="btn-secondary btn-sm !rounded-xl" @click="showNewAddressForm = false">Cancel</button>
                  <button class="btn-primary btn-sm !rounded-xl" @click="addNewAddress">Save address</button>
                </div>
              </div>
            </div>
            <!-- Shipping method -->
            <div class="card p-5 sm:p-6">
              <div class="flex items-center gap-2 mb-4">
                <Truck class="w-5 h-5 text-primary-600" />
                <h2 class="text-lg font-bold text-ink-900">Delivery Method</h2>
              </div>
              <div class="space-y-3">
                <label class="flex items-center justify-between p-4 rounded-2xl border-2 border-primary-500 bg-primary-50/50 cursor-pointer">
                  <div class="flex items-center gap-3">
                    <Truck class="w-5 h-5 text-primary-600" />
                    <div>
                      <p class="font-semibold text-ink-900">Standard Shipping</p>
                      <p class="text-sm text-ink-500">3-5 business days</p>
                    </div>
                  </div>
                  <span class="font-semibold text-ink-900">{{ formatPrice(15) }}</span>
                </label>
                <label class="flex items-center justify-between p-4 rounded-2xl border-2 border-ink-200 hover:border-ink-300 cursor-pointer transition-colors">
                  <div class="flex items-center gap-3">
                    <Truck class="w-5 h-5 text-ink-400" />
                    <div>
                      <p class="font-semibold text-ink-900">Express Shipping</p>
                      <p class="text-sm text-ink-500">1-2 business days</p>
                    </div>
                  </div>
                  <span class="font-semibold text-ink-900">{{ formatPrice(35) }}</span>
                </label>
              </div>
            </div>
          </div>
          <!-- STEP 2: PAYMENT -->
          <div v-else-if="currentStep === 2" class="space-y-5 animate-slide-up">
            <div class="card p-5 sm:p-6">
              <div class="flex items-center gap-2 mb-5">
                <CreditCard class="w-5 h-5 text-primary-600" />
                <h2 class="text-lg font-bold text-ink-900">Payment Method</h2>
              </div>
              <!-- Method selection -->
              <div class="grid sm:grid-cols-2 gap-3 mb-6">
                <button
                  v-for="method in paymentMethods"
                  :key="method.id"
                  class="p-4 rounded-2xl border-2 text-left transition-all"
                  :class="selectedPayment === method.id
                    ? 'border-primary-500 bg-primary-50/50 ring-1 ring-primary-200'
                    : 'border-ink-200 hover:border-ink-300'"
                  @click="selectedPayment = method.id"
                >
                  <div class="flex items-start gap-3">
                    <component :is="method.icon" class="w-5 h-5 text-primary-600 shrink-0 mt-0.5" />
                    <div class="flex-1">
                      <p class="font-semibold text-ink-900">{{ method.label }}</p>
                      <p class="text-xs text-ink-500">{{ method.desc }}</p>
                    </div>
                    <div
                      class="flex items-center justify-center w-5 h-5 rounded-full border-2 shrink-0 transition-colors"
                      :class="selectedPayment === method.id
                        ? 'border-primary-600 bg-primary-600'
                        : 'border-ink-300'"
                    >
                      <Check v-if="selectedPayment === method.id" class="w-3 h-3 text-white" />
                    </div>
                  </div>
                </button>
              </div>
              <!-- Card details form -->
              <div v-if="selectedPayment === 'card'" class="animate-slide-up">
                <div class="divider mb-5"></div>
                <h3 class="font-semibold text-ink-900 mb-4">Card Details</h3>
                <div class="space-y-4">
                  <BaseInput
                    v-model="cardDetails.number"
                    label="Card number"
                    placeholder="1234 5678 9012 3456"
                    :icon="CreditCard"
                  />
                  <BaseInput
                    v-model="cardDetails.name"
                    label="Name on card"
                    placeholder="Jordan Carter"
                  />
                  <div class="grid grid-cols-2 gap-4">
                    <BaseInput
                      v-model="cardDetails.expiry"
                      label="Expiry date"
                      placeholder="MM / YY"
                    />
                    <BaseInput
                      v-model="cardDetails.cvc"
                      label="CVC"
                      placeholder="123"
                      :icon="Lock"
                    />
                  </div>
                </div>
              </div>
              <!-- PayPal info -->
              <div v-else-if="selectedPayment === 'paypal'" class="animate-slide-up">
                <div class="divider mb-5"></div>
                <div class="rounded-2xl bg-info-50 border border-info-100 p-5 text-center">
                  <Wallet class="w-10 h-10 text-info-600 mx-auto mb-3" />
                  <p class="font-semibold text-ink-900 mb-1">You'll be redirected to PayPal</p>
                  <p class="text-sm text-ink-500">Complete your purchase securely using your PayPal account.</p>
                </div>
              </div>
              <!-- Apple Pay info -->
              <div v-else-if="selectedPayment === 'applepay'" class="animate-slide-up">
                <div class="divider mb-5"></div>
                <div class="rounded-2xl bg-ink-900 text-white p-5 text-center">
                  <Smartphone class="w-10 h-10 mx-auto mb-3" />
                  <p class="font-semibold mb-1">Pay with Apple Pay</p>
                  <p class="text-sm text-white/70">Confirm payment with Touch ID or Face ID.</p>
                </div>
              </div>
              <!-- Klarna info -->
              <div v-else-if="selectedPayment === 'klarna'" class="animate-slide-up">
                <div class="divider mb-5"></div>
                <div class="rounded-2xl bg-accent-50 border border-accent-100 p-5 text-center">
                  <Banknote class="w-10 h-10 text-accent-600 mx-auto mb-3" />
                  <p class="font-semibold text-ink-900 mb-1">Pay in 4 with Klarna</p>
                  <p class="text-sm text-ink-500">
                    {{ formatPrice(Math.round(grandTotal / 4)) }} now, then 3 payments of
                    {{ formatPrice(Math.round(grandTotal / 4)) }}. No interest.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <!-- STEP 3: REVIEW -->
          <div v-else-if="currentStep === 3" class="space-y-5 animate-slide-up">
            <!-- Items review -->
            <div class="card p-5 sm:p-6">
              <h2 class="text-lg font-bold text-ink-900 mb-4">Order Items</h2>
              <div class="space-y-3">
                <div
                  v-for="item in cartItems"
                  :key="`${item.id}-${item.selectedColor}`"
                  class="flex items-center gap-3 py-3 border-b border-ink-100 last:border-0"
                >
                  <div class="w-14 h-14 rounded-xl overflow-hidden bg-ink-100 shrink-0">
                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ink-900 clamp-1">{{ item.name }}</p>
                    <p class="text-xs text-ink-500">{{ item.selectedColor }} · Qty {{ item.qty }}</p>
                  </div>
                  <span class="text-sm font-bold text-ink-900 shrink-0">
                    {{ formatPrice(item.price * item.qty) }}
                  </span>
                </div>
              </div>
            </div>
            <!-- Shipping + payment summary -->
            <div class="grid sm:grid-cols-2 gap-5">
              <div class="card p-5">
                <div class="flex items-center gap-2 mb-3">
                  <MapPin class="w-4 h-4 text-primary-600" />
                  <h3 class="font-semibold text-ink-900">Shipping to</h3>
                </div>
                <p class="text-sm font-medium text-ink-800">{{ selectedAddress?.name }}</p>
                <p class="text-sm text-ink-500">{{ selectedAddress?.line1 }}</p>
                <p class="text-sm text-ink-500">
                  {{ selectedAddress?.city }}, {{ selectedAddress?.state }} {{ selectedAddress?.zip }}
                </p>
                <button
                  class="mt-3 text-sm font-medium text-primary-700 hover:text-primary-800 transition-colors"
                  @click="currentStep = 1"
                >
                  Change
                </button>
              </div>
              <div class="card p-5">
                <div class="flex items-center gap-2 mb-3">
                  <CreditCard class="w-4 h-4 text-primary-600" />
                  <h3 class="font-semibold text-ink-900">Payment</h3>
                </div>
                <p class="text-sm font-medium text-ink-800">
                  {{ paymentMethods.find((m) => m.id === selectedPayment)?.label }}
                </p>
                <p class="text-sm text-ink-500">
                  <span v-if="selectedPayment === 'card' && cardDetails.number">
                    •••• {{ cardDetails.number.slice(-4) }}
                  </span>
                  <span v-else>Secure payment</span>
                </p>
                <button
                  class="mt-3 text-sm font-medium text-primary-700 hover:text-primary-800 transition-colors"
                  @click="currentStep = 2"
                >
                  Change
                </button>
              </div>
            </div>
            <!-- Place order -->
            <div class="card p-5 sm:p-6 gradient-primary text-white">
              <div class="flex items-center justify-between mb-4">
                <div>
                  <p class="text-sm text-white/80">Order Number</p>
                  <p class="text-lg font-bold">{{ orderNumber }}</p>
                </div>
                <div class="text-right">
                  <p class="text-sm text-white/80">Total</p>
                  <p class="text-2xl font-bold">{{ formatPrice(grandTotal) }}</p>
                </div>
              </div>
              <BaseButton
                variant="accent"
                size="lg"
                class="w-full !rounded-xl"
                @click="placeOrder"
              >
                <Lock class="w-5 h-5" />
                Place Order
              </BaseButton>
              <p class="text-center text-xs text-white/70 mt-3">
                By placing your order, you agree to our Terms & Privacy Policy.
              </p>
            </div>
          </div>
          <!-- Step navigation -->
          <div class="flex items-center justify-between mt-6">
            <button
              v-if="currentStep > 1"
              class="btn-secondary btn-md !rounded-xl flex items-center gap-2"
              @click="prevStep"
            >
              <ChevronLeft class="w-4 h-4" />
              Back
            </button>
            <span v-else></span>
            <button
              v-if="currentStep < 3"
              class="btn-primary btn-md !rounded-xl flex items-center gap-2"
              @click="nextStep"
            >
              Continue
              <ChevronRight class="w-4 h-4" />
            </button>
          </div>
        </div>
        <!-- RIGHT: order summary sidebar -->
        <div class="lg:col-span-1">
          <div class="sticky top-6 space-y-4">
            <div class="card p-5 sm:p-6">
              <h2 class="text-lg font-bold text-ink-900 mb-4">Order Summary</h2>
              <!-- Mini item list -->
              <div class="space-y-3 max-h-64 overflow-y-auto pr-1 mb-4">
                <div
                  v-for="item in cartItems"
                  :key="`${item.id}-${item.selectedColor}`"
                  class="flex items-center gap-3"
                >
                  <div class="relative w-12 h-12 rounded-xl overflow-hidden bg-ink-100 shrink-0">
                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" />
                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-primary-600 text-white text-2xs font-bold flex items-center justify-center">
                      {{ item.qty }}
                    </span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-ink-900 clamp-1">{{ item.name }}</p>
                    <p class="text-xs text-ink-500">{{ item.selectedColor }}</p>
                  </div>
                  <span class="text-sm font-semibold text-ink-900 shrink-0">
                    {{ formatPrice(item.price * item.qty) }}
                  </span>
                </div>
              </div>
              <div class="divider"></div>
              <dl class="space-y-2.5 text-sm">
                <div class="flex justify-between">
                  <dt class="text-ink-500">Subtotal</dt>
                  <dd class="font-semibold text-ink-900">{{ formatPrice(cartSubtotal) }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-ink-500">Shipping</dt>
                  <dd class="font-semibold text-ink-900">{{ formatPrice(shippingCost) }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-ink-500">Tax</dt>
                  <dd class="font-semibold text-ink-900">{{ formatPrice(tax) }}</dd>
                </div>
              </dl>
              <div class="divider my-4"></div>
              <div class="flex justify-between items-baseline">
                <span class="text-base font-semibold text-ink-900">Total</span>
                <span class="text-2xl font-bold text-primary-700">{{ formatPrice(grandTotal) }}</span>
              </div>
            </div>
            <!-- Security note -->
            <div class="card p-4 flex items-start gap-3">
              <ShieldCheck class="w-5 h-5 text-success-500 shrink-0 mt-0.5" />
              <div>
                <p class="text-sm font-semibold text-ink-900">Secure Checkout</p>
                <p class="text-xs text-ink-500">Your payment information is encrypted and never stored.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
