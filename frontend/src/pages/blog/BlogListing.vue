<script setup>
import { ref, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import BaseBreadcrumb from '@/components/ui/BaseBreadcrumb.vue'
import Pagination from '../../components/paginations/Pagination.vue'
import {
  Search, Clock, Calendar, ArrowRight, Tag,
} from '@lucide/vue';
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
const router = useRouter();
const searchQuery = ref('');
const activeCategory = ref('all');
const currentPage = ref(1);
const isLoading = ref(false);
watch([searchQuery, activeCategory], () => {
  currentPage.value = 1;
});
const breadcrumbItems = ['Blog'];
const fetchBlogs = async () => {
  try {
    isLoading.value = true;
    const response = await api.get(endpoints)
  } catch (error) {

  } finally {

  }
}
</script>

<template>
  <div class="min-h-screen bg-ink-50/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <!-- Breadcrumb -->
      <BaseBreadcrumb :items="breadcrumbItems" class="mb-5"/>
      <!-- Page header -->
      <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
          <h1 class="text-2xl sm:text-3xl font-bold text-ink-900">The Blog</h1>
          <span class="badge-primary">{{ blogPosts.length }} articles</span>
        </div>
        <p class="text-sm text-ink-500 clamp-2 max-w-2xl">
          Expert insights, buying guides, and stories from the world of premium commerce — curated to help you shop smarter.
        </p>
      </div>
      <!-- Search bar -->
      <div class="relative mb-6 max-w-2xl">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-ink-400">
          <Search class="w-5 h-5" />
        </span>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search articles by title, content, or tags..."
          class="input-lg w-full pl-12 pr-4 rounded-2xl"
        />
      </div>
      <!-- Category filter chips -->
      <div class="flex items-center gap-2 mb-8 overflow-x-auto no-scrollbar pb-1">
        <button
          v-for="chip in categoryChips"
          :key="chip.slug"
          :class="[
            'chip flex items-center gap-1.5 whitespace-nowrap',
            activeCategory === chip.slug ? 'chip-active' : ''
          ]"
          @click="setCategory(chip.slug)"
        >
          <Tag v-if="chip.slug !== 'all'" class="w-3.5 h-3.5" />
          {{ chip.name }}
        </button>
      </div>
      <!-- Featured post -->
      <section v-if="featuredPost && !searchQuery && activeCategory === 'all'" class="mb-10">
        <div class="flex items-center gap-2 mb-4">
          <span class="w-1.5 h-6 rounded-full bg-accent-500"></span>
          <h2 class="text-lg font-bold text-ink-900">Featured Article</h2>
        </div>
        <article
          class="card card-hover overflow-hidden cursor-pointer group"
          @click="goToPost(featuredPost.id)"
        >
          <div class="grid lg:grid-cols-2 gap-0">
            <!-- Image -->
            <div class="relative aspect-[16/10] lg:aspect-auto overflow-hidden">
              <img
                :src="featuredPost.image"
                :alt="featuredPost.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
              />
              <div class="absolute top-4 left-4">
                <span :class="['badge-accent text-xs px-3 py-1.5', getCategoryColor(featuredPost.category)]">
                  {{ getCategoryName(featuredPost.category) }}
                </span>
              </div>
              <div class="absolute top-4 right-4">
                <span class="badge-primary text-xs px-3 py-1.5 bg-primary-600 text-white">
                  Featured
                </span>
              </div>
            </div>

            <!-- Content -->
            <div class="p-6 sm:p-8 flex flex-col justify-center">
              <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-ink-900 mb-3 leading-tight group-hover:text-primary-700 transition-colors">
                {{ featuredPost.title }}
              </h2>
              <p class="text-sm sm:text-base text-ink-600 leading-relaxed mb-5 clamp-3">
                {{ featuredPost.excerpt }}
              </p>

              <!-- Author + meta -->
              <div class="flex items-center gap-3 mb-5">
                <img
                  :src="featuredPost.authorAvatar"
                  :alt="featuredPost.author"
                  class="w-10 h-10 rounded-full object-cover ring-2 ring-primary-100"
                />
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold text-ink-900">{{ featuredPost.author }}</p>
                  <div class="flex items-center gap-3 text-xs text-ink-400">
                    <span class="flex items-center gap-1">
                      <Calendar class="w-3.5 h-3.5" />
                      {{ formatDate(featuredPost.date) }}
                    </span>
                    <span class="flex items-center gap-1">
                      <Clock class="w-3.5 h-3.5" />
                      {{ featuredPost.readTime }} min read
                    </span>
                  </div>
                </div>
              </div>

              <button class="btn-primary btn-md self-start flex items-center gap-2">
                Read article
                <ArrowRight class="w-4 h-4" />
              </button>
            </div>
          </div>
        </article>
      </section>

      <!-- Blog post grid -->
      <section>
        <div class="flex items-center justify-between mb-5">
          <h2 class="section-title">
            {{ searchQuery ? 'Search Results' : activeCategory === 'all' ? 'Latest Articles' : getCategoryName(activeCategory) }}
          </h2>
          <span class="text-sm text-ink-500">
            {{ filteredPosts.length }} {{ filteredPosts.length === 1 ? 'article' : 'articles' }}
          </span>
        </div>

        <!-- Empty state -->
        <div v-if="filteredPosts.length === 0" class="card p-12 text-center">
          <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-ink-100 flex items-center justify-center">
            <Search class="w-8 h-8 text-ink-400" />
          </div>
          <h3 class="text-lg font-semibold text-ink-900 mb-2">No articles found</h3>
          <p class="text-sm text-ink-500 mb-5 max-w-sm mx-auto">
            Try adjusting your search or browse a different category.
          </p>
          <button
            class="btn-secondary btn-md"
            @click="searchQuery = ''; activeCategory = 'all'"
          >
            Clear filters
          </button>
        </div>

        <!-- Grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
          <article
            v-for="post in paginatedPosts"
            :key="post.id"
            class="card card-hover overflow-hidden cursor-pointer group flex flex-col"
            @click="goToPost(post.id)"
          >
            <!-- Image -->
            <div class="relative aspect-card overflow-hidden">
              <img
                :src="post.image"
                :alt="post.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div class="absolute top-3 left-3">
                <span :class="['badge text-xs px-2.5 py-1', getCategoryColor(post.category)]">
                  {{ getCategoryName(post.category) }}
                </span>
              </div>
            </div>

            <!-- Content -->
            <div class="p-5 flex flex-col flex-1">
              <h3 class="text-base font-bold text-ink-900 mb-2 leading-snug group-hover:text-primary-700 transition-colors clamp-2">
                {{ post.title }}
              </h3>
              <p class="text-sm text-ink-500 leading-relaxed mb-4 clamp-2 flex-1">
                {{ post.excerpt }}
              </p>

              <!-- Author -->
              <div class="flex items-center gap-2.5 pt-4 border-t border-ink-100">
                <img
                  :src="post.authorAvatar"
                  :alt="post.author"
                  class="w-8 h-8 rounded-full object-cover"
                />
                <div class="flex-1 min-w-0">
                  <p class="text-xs font-semibold text-ink-900 truncate">{{ post.author }}</p>
                  <div class="flex items-center gap-2 text-xs text-ink-400">
                    <span class="flex items-center gap-0.5">
                      <Calendar class="w-3 h-3" />
                      {{ formatDate(post.date) }}
                    </span>
                    <span class="flex items-center gap-0.5">
                      <Clock class="w-3 h-3" />
                      {{ post.readTime }}m
                    </span>
                  </div>
                </div>
                <ArrowRight class="w-4 h-4 text-ink-300 group-hover:text-primary-600 group-hover:translate-x-0.5 transition-all" />
              </div>
            </div>
          </article>
        </div>
        <!-- Pagination -->
        <div v-if="filteredPosts.length > 0" class="mt-8">
          <Pagination
            v-model="currentPage"
            :total-pages="totalPages"
            :disabled="isLoading"
          />
        </div>
      </section>
      <!-- Newsletter CTA -->
      <section class="mt-12">
        <div class="card gradient-primary p-8 sm:p-10 text-center">
          <h3 class="text-xl sm:text-2xl font-bold text-white mb-2">Never miss an article</h3>
          <p class="text-sm text-white/80 mb-5 max-w-md mx-auto">
            Subscribe to our newsletter and get the latest guides, reviews, and exclusive deals delivered weekly.
          </p>
          <div class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <input
              type="email"
              placeholder="Enter your email"
              class="input-lg flex-1 rounded-2xl bg-white/95"
            />
            <button class="btn-accent btn-lg flex items-center justify-center gap-2 whitespace-nowrap">
              Subscribe
              <ArrowRight class="w-4 h-4" />
            </button>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>
