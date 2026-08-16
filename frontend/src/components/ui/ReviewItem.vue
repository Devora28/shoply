<script setup>
import {computed, ref} from 'vue'
import { ThumbsUp, ThumbsDown, Flag, CheckCircle2, AlertTriangle } from '@lucide/vue'
import { Icon } from '@iconify/vue'
import RatingStars from '@/components/ui/RatingStars.vue'
import BaseModal from '@/components/ui/BaseModal.vue'
import dayjs from "dayjs";

const props = defineProps({
  review: { type: Object, required: true },
})

function maskEmail(email) {
  if (!email) return ''
  const [name, domain] = email.split('@')
  return `${name.slice(0, 4)}***@${domain}`
}

const authorName = computed(() => {
  const firstName = props.review?.user?.first_name?.trim() || ''
  const lastName = props.review?.user?.last_name?.trim() || ''
  const email = props.review?.user?.email || ''
  if (firstName && lastName) {
    return `${firstName.charAt(0).toUpperCase()}${firstName.slice(1).toLowerCase()} ${lastName.charAt(0).toUpperCase()}.`
  }
  return maskEmail(email)
});
// ---- Report modal ----
const showReportModal = ref(false)
const reportReason = ref('')
const reportDetails = ref('')
const reportSent = ref(false)

const reportReasons = [
  'Spam or promotional content',
  'Offensive or inappropriate language',
  'Irrelevant to the product',
  'False or misleading information',
  'Harassment or personal attack',
  'Other',
]

function openReport() {
  reportReason.value = ''
  reportDetails.value = ''
  reportSent.value = false
  showReportModal.value = true
}

function submitReport() {
  if (!reportReason.value) return
  reportSent.value = true
  setTimeout(() => {
    showReportModal.value = false
    reportSent.value = false
  }, 1500)
}
</script>

<template>
  <div class="py-5 border-b border-ink-200 last:border-0">
    <div class="flex items-start gap-3">
      <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 bg-ink-100 flex items-center justify-center pointer-events-none select-none"
      >
        <img
          v-if="review.user.avatar"
          :src="review.user.avatar"
          :alt="authorName"
          class="w-full h-full object-cover"
        />

        <Icon
          icon="solar:user-bold"
          class="w-full h-full text-primary-600 p-1.5"
        />
      </div>
      <div class="flex-1">
        <div class="flex flex-wrap items-center gap-2 mb-1 pointer-events-none select-none">
          <span class="font-semibold text-ink-900 text-sm">{{authorName}}</span>
          <span v-if="review.is_verified_purchase" class="badge-success text-2xs">
            <CheckCircle2 class="w-3 h-3" /> Verified
          </span>
          <span class="text-xs text-ink-400">{{ dayjs(review.created_at).format('MMM D, YYYY') }}</span>
        </div>
        <RatingStars :model-value="review.rating" size="sm" />
        <p v-if="review.title" class="font-semibold text-ink-900 mt-2">{{ review.title }}</p>
        <p class="text-sm text-ink-600 mt-1 leading-relaxed">{{ review.comment }}</p>

        <!--Pros & Cons-->
        <div v-if="review.pros.length || review.cons.length" class="grid sm:grid-cols-2 gap-3 mt-3">
          <div v-if="review.pros.length" class="space-y-1.5">
            <p class="text-xs font-semibold text-success-700">Pros</p>
            <div v-for="(pro, i) in review.pros" :key="i" class="flex items-center gap-1.5 text-xs text-ink-600">
              <span class="w-1.5 h-1.5 rounded-full bg-success-500"></span>
              {{ pro.text }}
            </div>
          </div>
          <div v-if="review.cons.length" class="space-y-1.5">
            <p class="text-xs font-semibold text-danger-700">Cons</p>
            <div v-for="(con, i) in review.cons" :key="i" class="flex items-center gap-1.5 text-xs text-ink-600">
              <span class="w-1.5 h-1.5 rounded-full bg-danger-500"></span>
              {{ con.text }}
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
            <ThumbsUp class="w-3.5 h-3.5" /> Helpful ({{ review.helpful_count }})
          </button>
          <button
            class="inline-flex items-center gap-1.5 text-xs font-medium transition-colors"
            :class="voted === 'down' ? 'text-danger-600' : 'text-ink-500 hover:text-ink-900'"
            @click="vote('down')"
          >
            <ThumbsDown class="w-3.5 h-3.5" /> ({{ review.not_helpful_count }})
          </button>
          <button
            class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-500 hover:text-danger-600 transition-colors ml-auto"
            @click="openReport"
          >
            <Flag class="w-3.5 h-3.5" /> Report
          </button>
        </div>

        <!-- Seller Reply -->
        <div v-if="review.seller_reply" class="mt-3 ml-4 pl-4 border-l-2 border-ink-200">
          <div class="flex items-center gap-2 mb-1">
            <span class="badge-primary text-2xs">Seller</span>
            <span class="text-xs text-ink-400">{{ formatDate(review.seller_replied_at) }}</span>
          </div>
          <p class="text-sm text-ink-600">{{ review.seller_reply }}</p>
        </div>
      </div>
    </div>

    <!-- Report Modal -->
    <BaseModal v-model="showReportModal" title="Report Review" size="sm">
      <div class="p-6">
        <div v-if="reportSent" class="p-4 rounded-xl bg-success-50 border border-success-200 flex items-center gap-2">
          <CheckCircle2 class="w-5 h-5 text-success-600 shrink-0" />
          <span class="text-sm font-medium text-success-700">Thank you. Our team will review this report.</span>
        </div>

        <div v-else>
          <div class="flex items-start gap-2.5 p-3 rounded-xl bg-warning-50 border border-warning-200 mb-4">
            <AlertTriangle class="w-5 h-5 text-warning-600 shrink-0 mt-0.5" />
            <p class="text-xs text-ink-600 leading-relaxed">
              Help us keep the community respectful. Reports are reviewed by our moderation team and may result in the review being removed if it violates our guidelines.
            </p>
          </div>

          <div class="mb-4">
            <label class="label">Reason</label>
            <div class="space-y-2">
              <label
                v-for="reason in reportReasons"
                :key="reason"
                class="flex items-center gap-2.5 p-2.5 rounded-xl border-2 cursor-pointer transition-all"
                :class="reportReason === reason ? 'border-primary-600 bg-primary-50' : 'border-ink-200 hover:border-ink-300'"
              >
                <input
                  type="radio"
                  :value="reason"
                  v-model="reportReason"
                  class="w-4 h-4 text-primary-600 accent-primary-600"
                />
                <span class="text-sm text-ink-700">{{ reason }}</span>
              </label>
            </div>
          </div>

          <div class="mb-4">
            <label class="label" for="report-details">Additional Details <span class="text-ink-400 font-normal">(optional)</span></label>
            <textarea
              id="report-details"
              v-model="reportDetails"
              rows="3"
              placeholder="Provide any additional context..."
              class="input resize-none"
            ></textarea>
          </div>

          <div class="flex justify-end gap-2">
            <button class="btn-secondary btn-md" @click="showReportModal = false">Cancel</button>
            <button class="btn-danger btn-md" :disabled="!reportReason" @click="submitReport">
              <Flag class="w-4 h-4" /> Submit Report
            </button>
          </div>
        </div>
      </div>
    </BaseModal>
  </div>
</template>
