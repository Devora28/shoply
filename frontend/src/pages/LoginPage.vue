<script setup>
import { ref, reactive, computed, nextTick } from 'vue'
import {useRoute, useRouter} from 'vue-router'
import { ShoppingCart, Mail, Lock, ArrowLeft, ShieldCheck, KeyRound } from '@lucide/vue'
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
import {useAuthStore} from "@/stores/auth.js";
import {toast} from "vue-sonner";
const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()
const email = ref('')
const password = ref('')
const showPassword = ref(false)

// 'idle' = email only, 'otp' = 6-digit code, 'password' = password field
const mode = ref('idle')

const otpDigits = reactive(Array(6).fill(''))
const otpRefs = ref([])
const otpError = ref('')
const passwordError = ref('')
const emailError = ref('')
const loading = ref(false)

const emailValid = computed(() => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value))
const otpComplete = computed(() => otpDigits.every(d => d !== ''))
const canSubmitEmail = computed(() => emailValid.value && !loading.value)

function setOtpRef(el, index) {
  if (el) otpRefs.value[index] = el
}

function onOtpInput(index, event) {
  const value = event.target.value
  otpError.value = ''
  // If a single char was typed, move to next
  if (value && value.length >= 1) {
    const digit = value[value.length - 1]
    if (/\d/.test(digit)) {
      otpDigits[index] = digit
      if (index < 5) {
        nextTick(() => otpRefs.value[index + 1]?.focus())
      }
    } else {
      otpDigits[index] = ''
    }
  } else {
    otpDigits[index] = ''
  }
  // Auto-submit when all 6 filled
  if (otpComplete.value) {
    submitOtp()
  }
}

function onOtpKeydown(index, event) {
  if (event.key === 'Backspace') {
    if (otpDigits[index]) {
      // Clear current field
      otpDigits[index] = ''
    } else if (index > 0) {
      // Move back and clear previous
      event.preventDefault()
      otpDigits[index - 1] = ''
      nextTick(() => otpRefs.value[index - 1]?.focus())
    }
  } else if (event.key === 'ArrowLeft' && index > 0) {
    event.preventDefault()
    nextTick(() => otpRefs.value[index - 1]?.focus())
  } else if (event.key === 'ArrowRight' && index < 6) {
    event.preventDefault()
    nextTick(() => otpRefs.value[index + 1]?.focus())
  } else if (event.key === 'Enter') {
    if (otpComplete.value) submitOtp()
  }
}

function onOtpPaste(event) {
  event.preventDefault()
  const pasted = (event.clipboardData || window.clipboardData).getData('text')
  const digits = pasted.replace(/\D/g, '').slice(0, 6).split('')
  if (!digits.length) return
  otpError.value = ''
  for (let i = 0; i < 6; i++) {
    otpDigits[i] = digits[i] || ''
  }
  const lastFilled = Math.min(digits.length, 5)
  nextTick(() => otpRefs.value[lastFilled]?.focus())
  if (otpComplete.value) submitOtp()
}

async function chooseOtp() {
  if (!emailValid.value) {
    emailError.value = 'Please enter a valid email address'
    return
  }
  emailError.value = '';
  try {
    loading.value = true;
    const response = await api.post(endpoints.loginOtpRequest,{
      email: email.value,
    });
    if (response.data.success){
      mode.value = 'otp';
      otpDigits.fill('')
      await nextTick(() => otpRefs.value[0]?.focus())
    }
  }
  catch (error) {
    emailError.value = error.response?.data?.message ?? 'Something went wrong'
  }
  finally {
    loading.value = false;
  }
}
async function choosePassword() {
  if (!emailValid.value) {
    emailError.value = 'Please enter a valid email address'
    return
  }
  emailError.value = ''
  mode.value = 'password'
  await nextTick(() => {
    const el = document.querySelector('input[name="password-field"]')
    el?.focus()
  })
}
function backToEmail() {
  mode.value = 'idle'
  otpError.value = ''
  passwordError.value = ''
  otpDigits.fill('')
  password.value = ''
}
async function submitOtp() {
  if (!otpComplete.value) {
    otpError.value = 'Please enter all 6 digits'
    return
  }
  try {
    loading.value = true;
    otpError.value = '';
    const response = await api.post(endpoints.authOtp,{
      email: email.value,
      otp: otpDigits.join(''),
    });
    if (response.data.success){
      await authStore.login(response.data.token);
      const redirectUrl = route.query.redirect
      setTimeout(() => {
        router.push(redirectUrl || '/')
      }, 500)
      toast.success(response.data.message);
    }
  }
  catch (error) {
    otpError.value = error.response?.data?.message ?? 'Something went wrong';
  }
  finally {
    loading.value = false;
  }
}

async function submitPassword() {
  if (!password.value) {
    passwordError.value = 'Please enter your password'
    return
  }
  try {
    loading.value = true
    passwordError.value = ''
    const response = await api.post(endpoints.authPassword,{
      email: email.value,
      password: password.value,
    });
    if (response.data.success){
      await authStore.login(response.data.token);
      const redirectUrl = route.query.redirect
      setTimeout(() => {
        router.push(redirectUrl || '/')
      }, 500)
      toast.success(response.data.message);
    }
  }
  catch (error) {
    passwordError.value = error.response?.data?.message ?? 'Something went wrong';
  }
  finally {
    loading.value = false;
  }
}

function googleLogin() {
  // Backend handles Google OAuth
  loading.value = true
  setTimeout(() => {
    loading.value = false
    router.push('/account')
  }, 500)
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gradient-to-br from-ink-50 via-white to-primary-50/30">
    <div class="w-full max-w-md">
      <!-- Brand -->
      <div class="flex flex-col items-center mb-8">
        <router-link to="/" class="flex items-center gap-2.5 mb-6">
          <div class="w-11 h-11 rounded-2xl gradient-primary flex items-center justify-center shadow-md">
            <ShoppingCart class="w-6 h-6 text-white" />
          </div>
          <span class="text-2xl font-bold text-ink-900">Shoply</span>
        </router-link>
        <h1 class="text-2xl font-bold text-ink-900">Welcome back</h1>
        <p class="text-sm text-ink-500 mt-1">Sign in to continue to your account</p>
      </div>

      <!-- Card -->
      <div class="card p-7 sm:p-8 overflow-hidden">
        <Transition name="card-swap" mode="out-in">
          <!-- IDLE: email + google + buttons -->
          <div :key="'idle'" v-if="mode === 'idle'">
            <!-- Google Sign In -->
            <button
              type="button"
              :disabled="loading"
              class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl border border-ink-200 bg-white text-sm font-semibold text-ink-700 hover:bg-ink-50 hover:border-ink-300 transition-all active:scale-[0.98] disabled:opacity-50"
              @click="googleLogin"
            >
              <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              Continue with Google
            </button>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-6">
              <div class="flex-1 h-px bg-ink-200"></div>
              <span class="text-xs font-medium text-ink-400 uppercase tracking-wide">or</span>
              <div class="flex-1 h-px bg-ink-200"></div>
            </div>

            <form class="space-y-4" @submit.prevent>
              <!-- Email field -->
              <div>
                <label class="label">Email address</label>
                <div class="relative">
                  <Mail class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" />
                  <input
                    v-model="email"
                    type="email"
                    placeholder="you@example.com"
                    :class="[
                      'w-full pl-10 pr-4 py-3 text-sm text-ink-900 placeholder-ink-400 bg-white border rounded-xl transition-all duration-200 outline-none',
                      emailError
                        ? 'border-danger-300 focus:border-danger-500 focus:ring-2 focus:ring-danger-500/20'
                        : 'border-ink-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20'
                    ]"
                    @input="emailError = ''"
                  />
                </div>
                <p v-if="emailError" class="error-text">{{ emailError }}</p>
              </div>

              <!-- Action buttons -->
              <div class="grid grid-cols-1 gap-2.5 pt-1">
                <button
                  type="button"
                  :disabled="!canSubmitEmail"
                  class="btn-primary btn-lg w-full"
                  @click="chooseOtp"
                >
                  <ShieldCheck class="w-4 h-4" />
                  Continue with OTP
                </button>
                <button
                  type="button"
                  :disabled="!canSubmitEmail"
                  class="btn-secondary btn-lg w-full"
                  @click="choosePassword"
                >
                  <KeyRound class="w-4 h-4" />
                  Continue with password
                </button>
              </div>
            </form>
          </div>

          <!-- OTP MODE -->
          <div :key="'otp'" v-else-if="mode === 'otp'">
            <div class="flex items-center gap-3 mb-5">
              <button
                type="button"
                class="btn-ghost btn-icon -ml-2"
                aria-label="Back"
                @click="backToEmail"
              >
                <ArrowLeft class="w-5 h-5" />
              </button>
              <div class="flex-1 min-w-0">
                <p class="text-xs text-ink-500">Sign in with OTP</p>
                <p class="text-sm font-semibold text-ink-900 truncate">{{ email }}</p>
              </div>
              <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                <ShieldCheck class="w-4 h-4 text-primary-600" />
              </div>
            </div>

            <form class="space-y-4" @submit.prevent>
              <div>
                <label class="label">Enter 6-digit code</label>
                <p class="hint mb-3">We sent a one-time code to your email</p>
                <div class="flex gap-1.5 sm:gap-2 justify-between" @paste="onOtpPaste">
                  <input
                    v-for="(_, index) in 6"
                    :key="index"
                    :ref="(el) => setOtpRef(el, index)"
                    :value="otpDigits[index]"
                    type="text"
                    inputmode="numeric"
                    maxlength="1"
                    :aria-label="`Digit ${index + 1}`"
                    class="otp-box"
                    @input="onOtpInput(index, $event)"
                    @keydown="onOtpKeydown(index, $event)"
                    @paste="index === 0 ? null : $event.preventDefault()"
                  />
                </div>
                <p v-if="otpError" class="error-text">{{ otpError }}</p>
              </div>

              <button
                type="button"
                :disabled="loading || !otpComplete"
                class="btn-primary btn-lg w-full"
                @click="submitOtp"
              >
                <svg v-if="loading" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span v-else>Verify & sign in</span>
              </button>

              <div class="flex items-center justify-between pt-1">
                <button type="button" class="link text-xs" @click="backToEmail">Use a different email</button>
                <button type="button" class="link text-xs" @click="choosePassword">Sign in with password</button>
              </div>
            </form>
          </div>

          <!-- PASSWORD MODE -->
          <div :key="'password'" v-else-if="mode === 'password'">
            <div class="flex items-center gap-3 mb-5">
              <button
                type="button"
                class="btn-ghost btn-icon -ml-2"
                aria-label="Back"
                @click="backToEmail"
              >
                <ArrowLeft class="w-5 h-5" />
              </button>
              <div class="flex-1 min-w-0">
                <p class="text-xs text-ink-500">Sign in with password</p>
                <p class="text-sm font-semibold text-ink-900 truncate">{{ email }}</p>
              </div>
              <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                <Lock class="w-4 h-4 text-primary-600" />
              </div>
            </div>

            <form class="space-y-4" @submit.prevent>
              <div>
                <label class="label">Password</label>
                <div class="relative">
                  <Lock class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" />
                  <input
                    v-model="password"
                    :type="showPassword ? 'text' : 'password'"
                    name="password-field"
                    placeholder="Enter your password"
                    :class="[
                      'w-full pl-10 pr-11 py-3 text-sm text-ink-900 placeholder-ink-400 bg-white border rounded-xl transition-all duration-200 outline-none',
                      passwordError
                        ? 'border-danger-300 focus:border-danger-500 focus:ring-2 focus:ring-danger-500/20'
                        : 'border-ink-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20'
                    ]"
                    @keydown.enter="submitPassword"
                    @input="passwordError = ''"
                  />
                  <button
                    type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-400 hover:text-ink-700 transition-colors"
                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                    @click="showPassword = !showPassword"
                  >
                    <svg v-if="!showPassword" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg v-else class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-10-7-10-7a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 10 7 10 7a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24 4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                  </button>
                </div>
                <p v-if="passwordError" class="error-text">{{ passwordError }}</p>
              </div>

              <button
                type="button"
                :disabled="loading || !password"
                class="btn-primary btn-lg w-full"
                @click="submitPassword"
              >
                <svg v-if="loading" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span v-else>Sign in</span>
              </button>

              <div class="flex items-center justify-between pt-1">
                <button type="button" class="link text-xs" @click="backToEmail">Use a different email</button>
                <button type="button" class="link text-xs" @click="chooseOtp">Sign in with code instead</button>
              </div>
            </form>
          </div>
        </Transition>
      </div>

      <!-- Footer -->
      <p class="text-center text-sm text-ink-500 mt-6">
        New to Shoply?
        <router-link to="/login" class="link">Create an account</router-link>
      </p>
    </div>
  </div>
</template>

<style scoped>
.otp-box {
  width: 2.5rem;
  height: 3rem;
  text-align: center;
  font-size: 1.25rem;
  font-weight: 700;
  color: #1c1917;
  background: #fff;
  border: 1.5px solid #e7e5e4;
  border-radius: 0.75rem;
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  outline: none;
}
@media (min-width: 640px) {
  .otp-box {
    width: 2.75rem;
    height: 3.25rem;
  }
}
.otp-box:hover {
  border-color: #d6d3d1;
}
.otp-box:focus {
  border-color: #0d9488;
  box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
  transform: translateY(-1px);
}
.otp-box:not(:placeholder-shown) {
  border-color: #14b8a6;
  background: #f0fdfa;
}

.card-swap-enter-active,
.card-swap-leave-active {
  transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1),
  transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.card-swap-enter-from {
  opacity: 0;
  transform: translateY(12px) scale(0.98);
}
.card-swap-leave-to {
  opacity: 0;
  transform: translateY(-12px) scale(0.98);
}
</style>
