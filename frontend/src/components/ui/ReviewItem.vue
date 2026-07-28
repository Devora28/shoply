<script setup>
import { ref } from 'vue'
import { ThumbsUp, ThumbsDown, MessageSquare, Flag, CheckCircle2, X } from '@lucide/vue'
import RatingStars from "@/components/ui/RatingStars.vue";

const props = defineProps({
  review: { type: Object, required: true },
})

const helpfulCount = ref(props.review.helpful)
const notHelpfulCount = ref(props.review.notHelpful)
const voted = ref(null)

function vote(type) {
  if (voted.value === type) return
  if (type === 'up') helpfulCount.value++
  else notHelpfulCount.value++
  voted.value = type
}
</script>

<template>
  <div class="py-5 border-b border-ink-200 last:border-0">
    <div class="flex items-start gap-3">
      <img :src="review.avatar" :alt="review.author" class="w-10 h-10 rounded-full object-cover shrink-0" />
      <div class="flex-1">
        <div class="flex flex-wrap items-center gap-2 mb-1">
          <span class="font-semibold text-ink-900 text-sm">{{ review.author }}</span>
          <span v-if="review.verified" class="badge-success text-2xs">
            <CheckCircle2 class="w-3 h-3" /> Verified
          </span>
          <span class="text-xs text-ink-400">{{ review.date }}</span>
        </div>
        <RatingStars :model-value="review.rating" size="sm" />
        <p v-if="review.title" class="font-semibold text-ink-900 mt-2">{{ review.title }}</p>
        <p class="text-sm text-ink-600 mt-1 leading-relaxed">{{ review.text }}</p>

        <!-- Pros & Cons -->
        <div v-if="review.pros.length || review.cons.length" class="grid sm:grid-cols-2 gap-3 mt-3">
          <div v-if="review.pros.length" class="space-y-1.5">
            <p class="text-xs font-semibold text-success-700">Pros</p>
            <div v-for="(pro, i) in review.pros" :key="i" class="flex items-center gap-1.5 text-xs text-ink-600">
              <span class="w-1.5 h-1.5 rounded-full bg-success-500"></span>
              {{ pro }}
            </div>
          </div>
          <div v-if="review.cons.length" class="space-y-1.5">
            <p class="text-xs font-semibold text-danger-700">Cons</p>
            <div v-for="(con, i) in review.cons" :key="i" class="flex items-center gap-1.5 text-xs text-ink-600">
              <span class="w-1.5 h-1.5 rounded-full bg-danger-500"></span>
              {{ con }}
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-4 mt-3">
          <button
            class="inline-flex items-center gap-1.5 text-xs font-medium transition-colors"
            :class="voted === 'up' ? 'text-primary-600' : 'text-ink-500 hover:text-ink-900'"
            @click="vote('up')"
          >
            <ThumbsUp class="w-3.5 h-3.5" /> Helpful ({{ helpfulCount }})
          </button>
          <button
            class="inline-flex items-center gap-1.5 text-xs font-medium transition-colors"
            :class="voted === 'down' ? 'text-danger-600' : 'text-ink-500 hover:text-ink-900'"
            @click="vote('down')"
          >
            <ThumbsDown class="w-3.5 h-3.5" /> ({{ notHelpfulCount }})
          </button>
          <button class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-500 hover:text-ink-900 transition-colors">
            <MessageSquare class="w-3.5 h-3.5" /> Reply
          </button>
          <button class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-500 hover:text-danger-600 transition-colors ml-auto">
            <Flag class="w-3.5 h-3.5" /> Report
          </button>
        </div>

        <!-- Seller Reply -->
        <div v-if="review.sellerReply" class="mt-3 ml-4 pl-4 border-l-2 border-ink-200">
          <div class="flex items-center gap-2 mb-1">
            <span class="badge-primary text-2xs">Seller</span>
            <span class="text-xs text-ink-400">{{ review.sellerReply.date }}</span>
          </div>
          <p class="text-sm text-ink-600">{{ review.sellerReply.text }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
