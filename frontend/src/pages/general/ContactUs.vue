<script setup>
import {computed, onMounted, ref} from 'vue'
import { Icon } from '@iconify/vue'
import BaseBreadcrumb from '@/components/ui/BaseBreadcrumb.vue'
import BaseInput from '@/components/ui/BaseInput.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import AlertComponent from '@/components/ui/AlertComponent.vue'
import {
  Mail,Phone,MapPin,Clock,Send,MessageSquare,ChevronRight,CheckCircle2,
} from '@lucide/vue';
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
const settings = ref({});
const fetchData = async () => {
  try {
    loading.value = true;
    const response = await api.get(endpoints.contact);
    settings.value = response.data.data.settings;
  }
  catch (error) {
    console.log(error)
  }
  finally {
    loading.value = false;
  }
}
onMounted(() => {
  fetchData();
})
const form = ref({
  name: '',
  email: '',
  subject: '',
  message: '',
})
const errors = ref({})
const submitted = ref(false)
const loading = ref(false)
const submitLoading = ref(false)
const handleSubmit = async () => {
  try {
    submitLoading.value = true
    await api.post(endpoints.contactMessage,form.value);
    submitted.value = true
    errors.value = {}
    form.value = {
      name: '',
      email: '',
      subject: '',
      message: '',
    }
    setTimeout(() => {
      submitted.value = false
    }, 3500)
  } catch (error) {
    Object.assign(errors.value, error.response?.data?.errors ?? {})
  } finally {
    submitLoading.value = false
  }
}
const contactInfo = computed(() => [
  {
    icon: Mail,
    label: 'Email',
    value: settings.value.contact_email,
    sub: 'We reply within 24 hours',
  },
  {
    icon: Phone,
    label: 'Phone',
    value: settings.value.contact_phone,
    sub: 'Mon-Fri, 9am to 6pm PST',
  },
  {
    icon: MapPin,
    label: 'Address',
    value: [
      settings.value.address_line,
      settings.value.city,
      settings.value.state,
    ].filter(Boolean).join(', '),
    sub: 'Visit our flagship store',
  },
  {
    icon: Clock,
    label: 'Hours',
    value: 'Mon-Fri: 9am - 6pm',
    sub: 'Sat: 10am - 4pm, Sun: Closed',
  },
])
</script>

<template>
  <div class="min-h-screen bg-ink-50/40">
    <!-- BREADCRUMB -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
      <BaseBreadcrumb :items="['Contact Us']" />
    </div>
    <!-- HERO -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 text-center">
      <span class="badge-primary inline-flex items-center gap-1.5 mb-4">
        <MessageSquare class="w-3.5 h-3.5" />
        We're Here to Help
      </span>
      <h1 class="text-4xl sm:text-5xl font-bold text-ink-900 mb-4 tracking-tight">
        Get in Touch
      </h1>
      <p class="text-lg text-ink-500 max-w-2xl mx-auto leading-relaxed">
        Questions, feedback, or just want to say hello? Our team is ready to help
        you with anything you need.
      </p>
    </section>
    <!-- CONTACT INFO CARDS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4 mb-12">
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <template v-if="loading">
          <div
            v-for="i in 4"
            :key="i"
            class="card p-6 text-center"
          >
            <div class="skeleton w-12 h-12 rounded-2xl mx-auto mb-4"></div>
            <div class="skeleton h-3 w-16 mx-auto mb-2"></div>
            <div class="skeleton h-5 w-32 mx-auto"></div>
            <div class="skeleton h-4 w-40 mx-auto mt-2"></div>
          </div>
        </template>
        <!-- Actual Content -->
        <template v-else>
          <div
            v-for="info in contactInfo"
            :key="info.label"
            class="card card-hover p-6 text-center"
          >
            <div
              class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary-100 text-primary-700 mb-4"
            >
              <component :is="info.icon" class="w-6 h-6" />
            </div>
            <p class="text-xs font-medium text-ink-400 uppercase tracking-wide mb-1">
              {{ info.label }}
            </p>
            <p class="font-semibold text-ink-900 clamp-1">
              {{ info.value }}
            </p>
            <p class="text-sm text-ink-500 mt-1">
              {{ info.sub }}
            </p>
          </div>
        </template>
      </div>
    </section>
    <!-- FORM + SOCIAL -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
      <div class="grid lg:grid-cols-5 gap-8">
        <!-- Form -->
        <div class="lg:col-span-3">
          <div class="card p-6 sm:p-8">
            <h2 class="text-2xl font-bold text-ink-900 mb-2">Send us a Message</h2>
            <p class="text-ink-500 mb-6">Fill out the form below and we'll get back to you as soon as possible.</p>
            <AlertComponent
              v-if="submitted"
              variant="success"
              :icon="CheckCircle2"
              title="Message sent successfully!"
              description="Thank you for reaching out. Our team will respond within 24 hours."
            />
            <form class="space-y-5 mt-5">
              <div class="grid sm:grid-cols-2 gap-5">
                <BaseInput
                  v-model="form.name"
                  label="Name"
                  placeholder="Your full name"
                  :error="errors?.name?.[0]"
                />
                <BaseInput
                  v-model="form.email"
                  type="email"
                  label="Email"
                  placeholder="you@example.com"
                  :error="errors?.email?.[0]"
                />
              </div>
              <BaseInput
                v-model="form.subject"
                label="Subject"
                placeholder="What can we help you with?"
                :error="errors?.subject?.[0]"
              />
              <div>
                <label class="label">Message</label>
                <textarea
                  v-model="form.message"
                  rows="5"
                  placeholder="Tell us more..."
                  :class="['w-full rounded-xl border bg-white px-4 py-3 text-sm text-ink-900 placeholder:text-ink-400 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors resize-none', errors?.message?.[0] ? 'border-danger-400' : 'border-ink-200']"
                ></textarea>
                <p v-if="errors.message?.[0]" class="text-sm text-danger-500 mt-1">{{ errors.message[0] }}</p>
              </div>
              <BaseButton
                variant="primary"
                size="lg"
                :loading="submitLoading"
                class="w-full sm:w-auto"
                @click="handleSubmit"
              >
                <Send class="w-5 h-5" />
                Send Message
              </BaseButton>
            </form>
          </div>
        </div>
        <!-- Sidebar: Social + FAQ teaser -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Social -->
          <div class="card p-6">
            <h3 class="font-semibold text-ink-900 mb-4">Follow Us</h3>
            <p class="text-sm text-ink-500 mb-4">
              Stay connected for the latest products, deals, and updates.
            </p>
            <div v-if="settings.socials" class="flex flex-wrap gap-3">
              <a
                v-for="social in settings.socials"
                :key="social?.name"
                :href="social?.url"
                target="_blank"
                rel="noopener noreferrer"
                :aria-label="social?.name"
                class="w-11 h-11 rounded-xl bg-ink-100 hover:bg-primary-600 hover:text-white flex items-center justify-center text-ink-500 transition-all hover:scale-105"
              >
                <Icon :icon="social.icon" class="w-5 h-5"/>
              </a>
            </div>
          </div>
          <!-- FAQ teaser -->
          <div class="card p-6 gradient-primary text-white">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/20 mb-4">
              <MessageSquare class="w-6 h-6" />
            </div>
            <h3 class="font-semibold text-lg mb-2">Frequently Asked Questions</h3>
            <p class="text-sm text-white/80 mb-4">
              Find quick answers to common questions about orders, shipping, returns, and more.
            </p>
            <router-link
              to="/faq"
              class="inline-flex items-center gap-1.5 text-sm font-semibold text-white hover:gap-2.5 transition-all"
            >
              Browse FAQs
              <ChevronRight class="w-4 h-4" />
            </router-link>
          </div>
          <!-- Response time -->
          <div class="card p-6 flex items-start gap-4">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-success-100 text-success-600 shrink-0">
              <CheckCircle2 class="w-5 h-5" />
            </div>
            <div>
              <p class="font-semibold text-ink-900 text-sm">Fast Response Guarantee</p>
              <p class="text-sm text-ink-500 mt-1">
                We respond to all inquiries within 24 hours, often much sooner.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- MAP PLACEHOLDER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
      <div class="relative overflow-hidden rounded-3xl gradient-mesh p-16 sm:p-24 text-center">
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=%2240%22 height=%2240%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath d=%22M0 0h40v40H0z%22 fill=%22none%22 stroke=%22white%22 stroke-width=%221%22/%3E%3C/svg%3E');"></div>
        <div class="relative">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-white/20 backdrop-blur-sm mb-5">
            <MapPin class="w-8 h-8 text-white" />
          </div>
          <h2 class="text-2xl font-bold text-white mb-2">Visit Our Store</h2>
          <p class="text-white/70 max-w-md mx-auto">
            123 Commerce St, Portland, OR 97201
          </p>
          <p class="text-white/50 text-sm mt-1">Map integration coming soon</p>
        </div>
      </div>
    </section>
  </div>
</template>
