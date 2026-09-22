<script setup>
import { ref, computed } from 'vue'
import BaseBreadcrumb from '@/components/ui/BaseBreadcrumb.vue'
import AccordionItem from '@/components/ui/AccordionItem.vue'
import {
  Search, HelpCircle, MessageSquare, ArrowRight, Tag, Mail,
} from '@lucide/vue';
const faqCategories = [
  { name: 'Orders & Shipping', icon: 'Truck', items: [
      { q: 'How long does delivery take?', a: 'Standard delivery takes 2-5 business days depending on your location. Express delivery (1-2 days) is available for an additional fee.' },
      { q: 'How can I track my order?', a: 'Once your order ships, you will receive a tracking number via email and notification. You can also track it from your account dashboard.' },
      { q: 'Do you ship internationally?', a: 'Yes, we ship to over 40 countries. International shipping costs and delivery times vary by destination.' },
      { q: 'Can I change my shipping address after ordering?', a: 'You can change the address before the order ships. Go to your order details and click "Edit Address" or contact support immediately.' },
    ]},
  { name: 'Returns & Refunds', icon: 'RotateCcw', items: [
      { q: 'What is your return policy?', a: 'We offer a 30-day return policy on most items. Products must be unused and in original packaging. Some categories have specific conditions.' },
      { q: 'How do I get a refund?', a: 'Once we receive and inspect your returned item, the refund is processed within 3-5 business days to your original payment method.' },
      { q: 'Can I exchange an item?', a: 'Yes, exchanges are free within 30 days. Simply request an exchange from your order page and we will arrange pickup.' },
      { q: 'What items cannot be returned?', a: 'For hygiene reasons, personal care products, opened cosmetics, and digital downloads cannot be returned.' },
    ]},
  { name: 'Payment & Pricing', icon: 'CreditCard', items: [
      { q: 'What payment methods do you accept?', a: 'We accept all major credit cards, PayPal, Apple Pay, Google Pay, and buy-now-pay-later options like Klarna.' },
      { q: 'Is it safe to shop on your site?', a: 'Absolutely. We use 256-bit SSL encryption and are PCI DSS compliant. Your payment information is never stored on our servers.' },
      { q: 'Why was my payment declined?', a: 'Payments can be declined due to insufficient funds, incorrect card details, or bank security checks. Try an alternative method or contact your bank.' },
      { q: 'Do you offer price matching?', a: 'Yes, if you find a lower price on an identical item within 14 days of purchase, we will refund the difference.' },
    ]},
  { name: 'Account & Security', icon: 'User', items: [
      { q: 'How do I reset my password?', a: 'Click "Forgot Password" on the login page. Enter your email and we will send a reset link valid for 1 hour.' },
      { q: 'How can I delete my account?', a: 'Go to Settings > Account > Delete Account. This action is permanent and cannot be undone.' },
      { q: 'Is my personal information secure?', a: 'We take privacy seriously. Your data is encrypted and never sold to third parties. Read our Privacy Policy for details.' },
      { q: 'Can I have multiple addresses?', a: 'Yes, you can save unlimited addresses in your account and select any one at checkout.' },
    ]},
  { name: 'Products', icon: 'Package', items: [
      { q: 'Are your products authentic?', a: 'All products are 100% authentic and sourced directly from authorized distributors and brands.' },
      { q: 'Do products come with warranty?', a: 'Most products include a manufacturer warranty. The warranty period is listed on each product page.' },
      { q: 'Can I cancel my order?', a: 'Orders can be cancelled before they ship. Go to your order page and click "Cancel Order" or contact support.' },
      { q: 'Do you offer gift wrapping?', a: 'Yes, gift wrapping is available at checkout for a small fee. You can also add a personalized message.' },
    ]},
]
const searchQuery = ref('')
const activeCategory = ref('All')
const categories = computed(() => [
  { name: 'All', icon: 'HelpCircle' },
  ...faqCategories,
])
const filteredCategories = computed(() => {
  const query = searchQuery.value.trim().toLowerCase()
  return faqCategories
    .filter(cat => activeCategory.value === 'All' || cat.name === activeCategory.value)
    .map(cat => ({
      ...cat,
      items: cat.items.filter(item => {
        if (!query) return true
        return item.q.toLowerCase().includes(query) || item.a.toLowerCase().includes(query)
      }),
    }))
    .filter(cat => cat.items.length > 0)
})
const totalResults = computed(() =>
  filteredCategories.value.reduce((sum, cat) => sum + cat.items.length, 0)
)
const hasResults = computed(() => totalResults.value > 0)
</script>

<template>
  <div class="min-h-screen bg-ink-50/40">
    <!-- BREADCRUMB -->
    <div class="max-w-7xl pb-5 mx-auto px-4 sm:px-6 lg:px-8 pt-6">
      <BaseBreadcrumb :items="['FAQ']" />
    </div>
    <!-- HERO -->
    <section class="relative overflow-hidden gradient-primary text-white">
      <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cdefs%3E%3Cpattern id=%22g%22 width=%2260%22 height=%2260%22 patternUnits=%22userSpaceOnUse%22%3E%3Cpath d=%22M0 30L60 30M30 0L30 60%22 stroke=%22white%22 stroke-width=%220.5%22/%3E%3C/pattern%3E%3C/defs%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22url(%23g)%22/%3E%3C/svg%3E');"></div>
      <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 text-center">
        <span class="badge-accent inline-flex items-center gap-1.5 mb-5">
          <HelpCircle class="w-3.5 h-3.5" />
          Help Center
        </span>
        <h1 class="text-4xl sm:text-5xl font-bold mb-4 tracking-tight">
          Frequently Asked Questions
        </h1>
        <p class="text-lg text-white/80 mb-8 max-w-xl mx-auto">
          Find quick answers to common questions about orders, shipping, returns, payments, and more.
        </p>
        <!-- Search bar -->
        <div class="relative max-w-xl mx-auto">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-ink-400">
            <Search class="w-5 h-5" />
          </span>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search for answers..."
            class="w-full rounded-2xl border-0 bg-white py-4 pl-12 pr-4 text-sm text-ink-900 placeholder:text-ink-400 shadow-xl focus:outline-none focus:ring-4 focus:ring-white/30 transition-all"
          />
        </div>
      </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid lg:grid-cols-4 gap-8">
        <!-- ---- Category sidebar ---- -->
        <aside class="lg:col-span-1">
          <div class="lg:sticky lg:top-6">
            <h3 class="text-xs font-semibold text-ink-400 uppercase tracking-wide mb-4 px-2">Categories</h3>
            <div class="flex lg:flex-col gap-2 overflow-x-auto lg:overflow-visible pb-2 lg:pb-0">
              <button
                v-for="cat in categories"
                :key="cat.name"
                @click="activeCategory = cat.name"
                :class="[
                  'flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-medium transition-all whitespace-nowrap lg:w-full text-left',
                  activeCategory === cat.name
                    ? 'bg-primary-600 text-white shadow-md shadow-primary-600/20'
                    : 'text-ink-600 hover:bg-ink-100'
                ]"
              >
                <Tag class="w-4 h-4 shrink-0" />
                {{ cat.name }}
                <span
                  v-if="activeCategory === cat.name"
                  class="ml-auto text-xs bg-white/20 px-2 py-0.5 rounded-full"
                >
                  {{ cat.name === 'All' ? faqCategories.reduce((s, c) => s + c.items.length, 0) : cat.items.length }}
                </span>
              </button>
            </div>
          </div>
        </aside>
        <!-- Accordion list -->
        <div class="lg:col-span-3">
          <!-- Results count -->
          <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-ink-500">
              <span v-if="searchQuery">{{ totalResults }} result{{ totalResults !== 1 ? 's' : '' }} for "{{ searchQuery }}"</span>
              <span v-else>{{ totalResults }} question{{ totalResults !== 1 ? 's' : '' }} in {{ activeCategory }}</span>
            </p>
          </div>
          <!-- No results -->
          <div v-if="!hasResults" class="card p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-ink-100 text-ink-400 mb-4">
              <Search class="w-8 h-8" />
            </div>
            <h3 class="font-semibold text-ink-900 text-lg mb-2">No results found</h3>
            <p class="text-ink-500 text-sm mb-6 max-w-sm mx-auto">
              Try different keywords or browse a different category. If you still can't find what you're looking for, contact our support team.
            </p>
            <button
              @click="searchQuery = ''; activeCategory = 'All'"
              class="btn-secondary btn-md"
            >
              Clear filters
            </button>
          </div>
          <!-- Results -->
          <div v-else class="space-y-8">
            <div
              v-for="cat in filteredCategories"
              :key="cat.name"
            >
              <h2 class="text-lg font-bold text-ink-900 mb-3 flex items-center gap-2">
                <Tag class="w-5 h-5 text-primary-600" />
                {{ cat.name }}
              </h2>
              <div class="card p-2 sm:p-4">
                <AccordionItem
                  v-for="(item, i) in cat.items"
                  :key="i"
                  :title="item.q"
                  :default-open="i === 0 && activeCategory === 'All' && !searchQuery"
                >
                  <p class="text-ink-600 leading-relaxed">{{ item.a }}</p>
                </AccordionItem>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- CTA -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
      <div class="relative overflow-hidden rounded-3xl gradient-dark text-white p-10 sm:p-14 text-center">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2240%22 height=%2240%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Ccircle cx=%2220%22 cy=%2220%22 r=%221%22 fill=%22white%22/%3E%3C/svg%3E');"></div>
        <div class="relative">
          <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/10 mb-4">
            <MessageSquare class="w-7 h-7" />
          </div>
          <h2 class="text-2xl sm:text-3xl font-bold mb-3">Still have questions?</h2>
          <p class="text-white/70 max-w-lg mx-auto mb-8 leading-relaxed">
            Our friendly support team is here to help. Reach out and we'll get you the answers you need.
          </p>
          <div class="flex flex-wrap items-center justify-center gap-4">
            <router-link to="/contact" class="btn-accent btn-lg">
              <Mail class="w-5 h-5" />
              Contact Support
            </router-link>
            <router-link to="/shop" class="btn-ghost btn-lg !text-white !border-white/30 hover:!bg-white/10">
              Browse Products
              <ArrowRight class="w-5 h-5" />
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
