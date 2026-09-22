<script setup>
import {
  Target,Heart,Users,TrendingUp,Award,Lightbulb,Rocket,ShieldCheck,
  ChevronRight,ArrowRight,Leaf
} from '@lucide/vue'
import BaseBreadcrumb from "@/components/ui/BaseBreadcrumb.vue";
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
import {onMounted, ref} from "vue";
const pageData = ref([]);
const stats = ref([]);
const values = ref([]);
const team = ref([]);
const milestones = ref([]);
const loading = ref(false);
const iconMap = {
  award: Award,
  heart: Heart,
  leaf: Leaf,
  lightbulb: Lightbulb,
  rocket: Rocket,
  'shield-check': ShieldCheck,
  'trending-up': TrendingUp,
  users: Users,
};
const fetchData = async () => {
  try {
    loading.value = true;
    const response = await api.get(endpoints.about);
    pageData.value = response.data.data.page;
    stats.value = response.data.data.stats;
    values.value = response.data.data.values;
    team.value = response.data.data.team;
    milestones.value = response.data.data.milestones;
  }
  catch (error) {
    console.error(error);
  }
  finally {
    loading.value = false;
  }
}
onMounted(() => {
  fetchData()
})
</script>

<template>
  <div class="min-h-screen bg-ink-50/40">
    <!-- BREADCRUMB -->
    <div class="max-w-7xl pb-5 mx-auto px-4 sm:px-6 lg:px-8 pt-6">
      <BaseBreadcrumb :items="['About Us']" />
    </div>
    <!-- HERO -->
    <section class="relative overflow-hidden gradient-primary text-white">
      <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cdefs%3E%3Cpattern id=%22g%22 width=%2260%22 height=%2260%22 patternUnits=%22userSpaceOnUse%22%3E%3Cpath d=%22M0 30L60 30M30 0L30 60%22 stroke=%22white%22 stroke-width=%220.5%22/%3E%3C/pattern%3E%3C/defs%3E%3Crect width=%22100%25%22 height=%22100%25%22 fill=%22url(%23g)%22/%3E%3C/svg%3E');"></div>
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 text-center">
        <template v-if="loading">
          <div class="skeleton h-7 w-32 rounded-full mx-auto mb-6"></div>
          <div class="skeleton h-12 sm:h-14 lg:h-16 w-72 sm:w-96 lg:w-[500px] mx-auto mb-5"></div>
          <div class="max-w-2xl mx-auto space-y-2">
            <div class="skeleton h-5 w-full"></div>
            <div class="skeleton h-5 w-5/6 mx-auto"></div>
          </div>
        </template>
        <template v-else>
          <span class="badge-accent inline-flex items-center gap-1.5 mb-6">
            <Heart class="w-3.5 h-3.5" />
            {{ pageData.hero_eyebrow }}
          </span>
          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-5 tracking-tight">
            {{ pageData.hero_title }}
          </h1>
          <p class="text-lg sm:text-xl text-white/80 max-w-2xl mx-auto leading-relaxed">
            {{ pageData.hero_description }}
          </p>
        </template>
        <div class="flex flex-wrap items-center justify-center gap-4 mt-8">
          <router-link to="/shop" class="btn-accent btn-lg">
            Explore Products
            <ArrowRight class="w-5 h-5" />
          </router-link>
          <router-link to="/contact" class="btn-ghost btn-lg !text-white !border-white/30 hover:!bg-white/10">
            Get in Touch
          </router-link>
        </div>
      </div>
    </section>
    <!-- STATS ROW -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
      <div class="card p-6 sm:p-8 grid grid-cols-2 lg:grid-cols-4 gap-6">
        <template v-if="loading">
          <div
            v-for="i in 4"
            :key="i"
            class="text-center"
          >
            <div class="skeleton w-12 h-12 rounded-2xl mx-auto mb-3"></div>
            <div class="skeleton h-8 w-20 mx-auto mb-2"></div>
            <div class="skeleton h-4 w-24 mx-auto"></div>
          </div>
        </template>
        <template v-else>
          <div
            v-for="stat in stats"
            :key="stat.label"
            class="text-center"
          >
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary-100 text-primary-700 mb-3">
              <component :is="iconMap[stat.icon]" class="w-6 h-6" />
            </div>
            <p class="text-3xl font-bold text-ink-900">
              {{ stat.value }}
            </p>
            <p class="text-sm text-ink-500 mt-0.5">
              {{ stat.label }}
            </p>
          </div>
        </template>
      </div>
    </section>
    <!-- MISSION -->
    <section class="section pt-24 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div>
          <template v-if="loading">
            <div class="skeleton h-7 w-28 rounded-full mb-4"></div>

            <div class="space-y-3 mb-5">
              <div class="skeleton h-10 w-full max-w-lg"></div>
              <div class="skeleton h-10 w-4/5"></div>
            </div>

            <div class="space-y-2">
              <div class="skeleton h-4 w-full"></div>
              <div class="skeleton h-4 w-full"></div>
              <div class="skeleton h-4 w-4/5"></div>
            </div>
          </template>
          <template v-else>
            <span class="badge-primary inline-flex items-center gap-1.5 mb-4">
              <Target class="w-3.5 h-3.5" />
              {{ pageData.mission_eyebrow }}
            </span>
            <h2 class="text-3xl sm:text-4xl font-bold text-ink-900 mb-5 leading-tight">
              {{ pageData.mission_title }}
            </h2>
            <p class="text-ink-600 leading-relaxed mb-4">
              {{ pageData.mission_description }}
            </p>
          </template>
          <div class="flex flex-wrap gap-3">
            <span class="chip">Ethical Sourcing</span>
            <span class="chip">Fair Pricing</span>
            <span class="chip">Carbon Neutral</span>
            <span class="chip">24/7 Support</span>
          </div>
        </div>
        <div class="relative">
          <div class="absolute -inset-4 gradient-mesh rounded-3xl blur-2xl opacity-30"></div>
          <div v-if="loading" class="skeleton relative rounded-3xl w-full aspect-[4/3]"></div>
          <img
            v-else
            :src="pageData.mission_image"
            alt="Our team collaborating"
            class="relative rounded-3xl shadow-xl w-full object-cover aspect-[4/3]"
          />
        </div>
      </div>
    </section>
    <!-- VALUES GRID -->
    <section class="section py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <template v-if="loading">
            <div class="skeleton h-7 w-28 rounded-full mx-auto mb-4"></div>
            <div class="skeleton h-8 w-56 mx-auto mb-3"></div>
            <div class="skeleton h-4 w-2/3 max-w-2xl mx-auto"></div>
          </template>
          <template v-else>
            <span class="badge-accent inline-flex items-center gap-1.5 mb-4">
              <Heart class="w-3.5 h-3.5" />
              {{ pageData.values_eyebrow }}
            </span>
            <h2 class="section-title">
              {{ pageData.values_title }}
            </h2>
            <p class="text-ink-500 max-w-2xl mx-auto mt-2">
              {{ pageData.values_description }}
            </p>
          </template>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <template v-if="loading">
            <div
              v-for="i in 6"
              :key="i"
              class="card p-6"
            >
              <div class="skeleton w-12 h-12 rounded-2xl mb-4"></div>
              <div class="skeleton h-6 w-32 mb-3"></div>
              <div class="space-y-2">
                <div class="skeleton h-4 w-full"></div>
                <div class="skeleton h-4 w-4/5"></div>
              </div>
            </div>
          </template>
          <template v-else>
            <div
              v-for="value in values"
              :key="value.title"
              class="card card-hover p-6"
            >
              <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary-100 text-primary-700 mb-4">
                <component :is="iconMap[value.icon]" class="w-6 h-6" />
              </div>
              <h3 class="font-semibold text-ink-900 text-lg mb-2">
                {{ value.title }}
              </h3>
              <p class="text-sm text-ink-500 leading-relaxed">
                {{ value.description }}
              </p>
            </div>
          </template>
        </div>
      </div>
    </section>
    <!-- TEAM -->
    <section class="section py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <template v-if="loading">
          <div class="skeleton h-7 w-28 rounded-full mx-auto mb-4"></div>
          <div class="skeleton h-8 w-56 mx-auto mb-3"></div>
          <div class="skeleton h-4 w-2/3 max-w-2xl mx-auto"></div>
        </template>
        <template v-else>
          <span class="badge-primary inline-flex items-center gap-1.5 mb-4">
            <Users class="w-3.5 h-3.5" />
            {{ pageData.team_eyebrow }}
          </span>
          <h2 class="section-title">
            {{ pageData.team_title }}
          </h2>
          <p class="text-ink-500 max-w-2xl mx-auto mt-2">
            {{ pageData.team_description }}
          </p>
        </template>
      </div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <template v-if="loading">
          <div
            v-for="i in 4"
            :key="i"
            class="card p-6 text-center"
          >
            <div class="skeleton w-24 h-24 rounded-2xl mx-auto mb-4"></div>
            <div class="skeleton h-5 w-28 mx-auto mb-2"></div>
            <div class="skeleton h-4 w-24 mx-auto"></div>
          </div>
        </template>
        <template v-else>
          <div
            v-for="member in team"
            :key="member.name"
            class="card card-hover p-6 text-center"
          >
            <img
              :src="member.avatar"
              :alt="member.name"
              class="w-24 h-24 rounded-2xl object-cover mx-auto mb-4 ring-4 ring-primary-100"
            />

            <h3 class="font-semibold text-ink-900">
              {{ member.name }}
            </h3>

            <p class="text-sm text-primary-600 mt-0.5">
              {{ member.role }}
            </p>
            <div class="flex items-center justify-center gap-2 mt-4">
            <span class="w-8 h-8 rounded-lg bg-ink-100 hover:bg-primary-100 hover:text-primary-600 flex items-center justify-center text-ink-400 transition-colors cursor-pointer">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/></svg>
            </span>
              <span class="w-8 h-8 rounded-lg bg-ink-100 hover:bg-primary-100 hover:text-primary-600 flex items-center justify-center text-ink-400 transition-colors cursor-pointer">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
            </span>
            </div>
          </div>
        </template>
        </div>
    </section>
    <!-- TIMELINE -->
    <section class="section py-20 bg-white">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <template v-if="loading">
            <div class="skeleton h-7 w-28 rounded-full mx-auto mb-4"></div>
            <div class="skeleton h-8 w-56 mx-auto mb-3"></div>
            <div class="skeleton h-4 w-2/3 max-w-2xl mx-auto"></div>
          </template>
          <template v-else>
            <span class="badge-accent inline-flex items-center gap-1.5 mb-4">
              <TrendingUp class="w-3.5 h-3.5" />
              {{ pageData.milestones_eyebrow }}
            </span>
            <h2 class="section-title">
              {{ pageData.milestones_title }}
            </h2>
            <p class="text-ink-500 max-w-2xl mx-auto mt-2">
              {{ pageData.milestones_description }}
            </p>
          </template>
        </div>
        <div class="relative">
          <!-- Vertical line -->
          <div class="absolute left-4 sm:left-1/2 top-0 bottom-0 w-0.5 bg-ink-200 sm:-translate-x-1/2"></div>
          <template v-if="loading">
            <div
              v-for="i in 4"
              :key="i"
              class="relative flex items-start gap-6 mb-8"
            >
              <div class="absolute left-4 sm:left-1/2 top-2 w-4 h-4 rounded-full bg-ink-300 ring-4 ring-ink-100 sm:-translate-x-1/2 z-10"></div>
              <div class="hidden sm:block flex-1"></div>
              <div class="flex-1 pl-12 sm:pl-12">
                <div class="card p-5">
                  <div class="skeleton h-6 w-16 rounded-full mb-3"></div>
                  <div class="skeleton h-6 w-48 mb-2"></div>
                  <div class="skeleton h-4 w-full"></div>
                </div>
              </div>
            </div>
          </template>
          <template v-else>
            <div
              v-for="(milestone, i) in milestones"
              :key="milestone.year"
              class="relative flex items-start gap-6 mb-8 last:mb-0"
              :class="{ 'sm:flex-row-reverse': i % 2 === 1 }"
            >
              <div class="absolute left-4 sm:left-1/2 top-2 w-4 h-4 rounded-full bg-primary-600 ring-4 ring-primary-100 sm:-translate-x-1/2 z-10"></div>
              <div
                class="hidden sm:block flex-1"
                :class="{ 'sm:order-2': i % 2 === 1 }"
              ></div>
              <div
                class="flex-1 pl-12 sm:pl-0"
                :class="i % 2 === 1 ? 'sm:pr-12 sm:text-right' : 'sm:pl-12'"
              >
                <div class="card card-hover p-5">
                  <span class="badge-primary text-xs mb-2 inline-flex">
                    {{ milestone.year }}
                  </span>
                  <h3 class="font-semibold text-ink-900 text-lg">
                    {{ milestone.title }}
                  </h3>
                  <p class="text-sm text-ink-500 mt-1 leading-relaxed">
                    {{ milestone.description }}
                  </p>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </section>
    <!-- CTA -->
    <section class="section pt-20 pb-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="relative overflow-hidden rounded-3xl gradient-dark text-white p-10 sm:p-16 text-center">
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=%2240%22 height=%2240%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Ccircle cx=%2220%22 cy=%2220%22 r=%221%22 fill=%22white%22/%3E%3C/svg%3E');"></div>
        <div class="relative">
          <h2 class="text-3xl sm:text-4xl font-bold mb-4">Join the Shoply Family</h2>
          <p class="text-white/70 max-w-xl mx-auto mb-8 leading-relaxed">
            Discover 50,000+ products from brands you love and creators you will adore. Your next favorite thing is just a click away.
          </p>
          <div class="flex flex-wrap items-center justify-center gap-4">
            <router-link to="/shop" class="btn-accent btn-lg">
              Start Shopping
              <ChevronRight class="w-5 h-5" />
            </router-link>
            <router-link to="/contact" class="btn-ghost btn-lg !text-white !border-white/30 hover:!bg-white/10">
              Contact Us
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
