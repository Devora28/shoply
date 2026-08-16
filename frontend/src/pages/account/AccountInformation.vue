<script setup>
import {ref, computed, onMounted} from 'vue'
import {
  User, Mail, Save, Camera, Lock, Eye, EyeOff,
} from '@lucide/vue'
import BaseBreadcrumb from "@/components/ui/BaseBreadcrumb.vue";
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseInput from '../../components/ui/BaseInput.vue'
import DesktopDashboardSidebar from "@/components/ui/DesktopDashboardSidebar.vue";
import MobileDashboardSidebar from "@/components/ui/MobileDashboardSidebar.vue";
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
import PhoneInput from "@/components/ui/PhoneInput.vue";
import BaseDatePicker from "@/components/ui/BaseDatePicker.vue";
import {toast} from "vue-sonner";
const user = ref({});
const fileInput = ref(null)
const file = ref(null);
const openFilePicker = () => {
  fileInput.value?.click()
}
const handleFileChange = async (event) => {
  file.value = event.target.files?.[0]
  if (!file.value) return
  await changeAvatar()
}
const changeAvatar = async () => {
  if (!file.value) return
  const token = localStorage.getItem('auth_token')
  const formData = new FormData();
  formData.append('avatar', file.value);
  formData.append('_method', 'PATCH');
  try {
    const response = await api.post(endpoints.accountAvatar,
      formData,
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )
    user.value = response.data.data;
    file.value = null;
    avatarErrors.value = {};
    toast.success(response.data.message)
  }
  catch (error) {
    Object.assign(avatarErrors.value,error.response?.data?.errors ?? {})
  }
}
const destroyAvatar = async () => {
  if (!user.value.avatar) return
  const token = localStorage.getItem('auth_token')
  try {
    const response = await api.delete(endpoints.accountAvatar,{
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    user.value = response.data.data;
    toast.success(response.data.message)
  }
  catch (error) {
    console.log(error)
  }
}
const avatarErrors = ref({});
const form = ref({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  dob: '',
  gender: 'prefer_not_to_say',
  bio: '',
});
const genderOptions = [
  { value: 'female', label: 'Female' },
  { value: 'male', label: 'Male' },
  { value: 'non_binary', label: 'Non binary' },
  { value: 'prefer_not_to_say', label: 'Prefer not to say' },
]
const showCurrent = ref(false)
const showNew = ref(false)
const showConfirm = ref(false)
const passwordForm = ref({
  current_password: '',
  password: '',
  password_confirmation: '',
})
const passwordError = ref({});
const formErrors = ref({});
const saveProfile = async () => {
  const token = localStorage.getItem('auth_token');
  const data = {
    first_name: form.value.firstName,
    last_name: form.value.lastName,
    email: form.value.email,
    phone: form.value.phone,
    date_of_birth: form.value.dob,
    gender: form.value.gender,
    bio: form.value.bio,
  }
  try{
    const response = await api.put(endpoints.accountInformation,
      data,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        }
      }
    )
    user.value = response.data.data;
    fillForm(user?.value);
    formErrors.value = {};
    toast.success(response.data.message);
  }
  catch (error) {
    Object.assign(formErrors.value,error.response?.data?.errors ?? {})
  }
}
const savePassword = async () => {
  const token = localStorage.getItem('auth_token');
  try {
    const response = await api.patch(endpoints.accountInformation,
      passwordForm.value,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        }
      }
    )
    toast.success(response.data.message);
    passwordError.value = {}
    passwordForm.value = {}
  }
  catch (error) {
    Object.assign(passwordError.value,error.response?.data?.errors ?? {});
  }
}
function cancelProfile() {
  fillForm(user?.value)
  formErrors.value = {};
}
function fillForm(user) {
  form.value = {
    firstName: user.first_name ?? '',
    lastName: user.last_name ?? '',
    email: user.email ?? '',
    phone: user.phone ?? '',
    dob: user.date_of_birth ?? '',
    gender: user.gender ?? 'prefer_not_to_say',
    bio: user.bio ?? '',
  }
}
onMounted(async () => {
  const token = localStorage.getItem('auth_token');
  try {
    const response = await api.get(endpoints.accountInformation,{
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    user.value = response.data.data;
    fillForm(user.value)
  }
  catch (error) {
    console.log(error)
  }
});
const sliceWord = computed(() => {
  const firstName = user.value?.first_name ?? ''
  const lastName = user.value?.last_name ?? ''
  return (firstName && lastName
      ? firstName[0] + lastName[0]
      : 'SU'
  )?.toUpperCase()
})
</script>

<template>
  <div class="section py-8 lg:py-12">
    <BaseBreadcrumb :items="['Account', 'Profile Info']" class="mb-6" />
    <div class="grid lg:grid-cols-[260px_1fr] gap-8">
      <!-- Sidebar (desktop) -->
      <DesktopDashboardSidebar/>
      <!-- Mobile tab bar -->
      <MobileDashboardSidebar/>
      <!-- Main content -->
      <div class="min-w-0 space-y-6">
        <!-- Page header -->
        <div>
          <h1 class="text-2xl font-bold text-ink-900">Profile Information</h1>
          <p class="text-ink-500 mt-1">Update your personal details and manage your account.</p>
        </div>
        <!-- Avatar section -->
        <div class="card p-6">
          <h2 class="font-semibold text-ink-900 mb-4">Avatar</h2>
          <div class="flex items-center gap-5">
            <div class="relative">
              <div class="w-20 h-20 rounded-full overflow-hidden gradient-primary flex items-center justify-center text-white text-2xl font-bold"
              >
                <img
                  v-if="user?.avatar"
                  :src="user.avatar ?? ''"
                  alt="Profile avatar"
                  class="w-full h-full object-cover"
                />
                <span v-else>
                  {{ sliceWord }}
                </span>
              </div>
              <button class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-white border border-ink-200 shadow-sm flex items-center justify-center text-ink-600 hover:text-primary-600 hover:border-primary-300 transition-colors" @click="openFilePicker">
                <Camera class="w-4 h-4" />
              </button>
            </div>
            <input
              ref="fileInput"
              type="file"
              accept="image/jpeg,image/png,image/gif"
              class="hidden"
              @change="handleFileChange"
            />
            <div class="flex-1">
              <p class="text-sm text-ink-700 font-medium">{{ form.firstName }} {{ form.lastName }}</p>
              <p class="text-xs text-ink-500 mb-3">JPG, JPEG , PNG or WEBP. Max 1MB</p>
              <div class="flex gap-2">
                <BaseButton @click="openFilePicker" variant="secondary" size="sm">
                  <Camera class="w-4 h-4" />
                  Change Photo
                </BaseButton>
                <BaseButton @click="destroyAvatar" :disabled="!user.avatar" variant="ghost" size="sm">Remove</BaseButton>
              </div>
              <p
                v-if="avatarErrors.avatar?.[0]"
                class="error-text mt-2"
              >
                {{ avatarErrors.avatar[0] }}
              </p>
            </div>
          </div>
        </div>
        <!-- Personal info form -->
        <div class="card p-6">
          <h2 class="font-semibold text-ink-900 mb-1">Personal Details</h2>
          <p class="text-sm text-ink-500 mb-5">This information will be displayed on your profile.</p>
          <div class="grid sm:grid-cols-2 gap-4">
            <BaseInput
              v-model="form.firstName"
              label="First name"
              placeholder="First name"
              :icon="User"
              :error="formErrors.first_name?.[0]"
            />
            <BaseInput
              v-model="form.lastName"
              label="Last name"
              placeholder="Last name"
              :icon="User"
              :error="formErrors.last_name?.[0]"
            />
            <BaseInput
              v-model="form.email"
              label="Email address"
              type="email"
              placeholder="you@example.com"
              :icon="Mail"
              :error="formErrors.email?.[0]"
            />
            <PhoneInput
              v-model="form.phone"
              label="Phone number"
              :error="formErrors.phone?.[0]"
            />
            <BaseDatePicker
              v-model="form.dob"
              label="Date of birth"
              placeholder="Date of birth"
              :error="formErrors.date_of_birth?.[0]"
            />
            <div>
              <label class="label">Gender</label>
              <select v-model="form.gender" class="input">
                <option v-for="gender in genderOptions" :key="gender.value" :value="gender.value">{{ gender.label }}</option>
              </select>
            </div>
          </div>
          <div class="mt-4">
            <label class="label">Bio</label>
            <textarea
              v-model="form.bio"
              rows="4"
              placeholder="Tell us about yourself..."
              class="input resize-none"
              :class="{ 'input-error': formErrors.bio?.[0] }"
            />
            <p v-if="formErrors.bio?.[0]" class="error-text">
              {{ formErrors.bio?.[0] }}
            </p>
            <p class="hint">{{ form.bio.length }} / 500 characters</p>
          </div>
          <div class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-end mt-6 pt-5 border-t border-ink-200">
            <BaseButton variant="ghost" size="md" @click="cancelProfile">Cancel</BaseButton>
            <BaseButton variant="primary" size="md" @click="saveProfile">
              <Save class="w-4 h-4" />
              Save Changes
            </BaseButton>
          </div>
        </div>

        <!-- Password change section -->
        <div class="card p-6">
          <div class="flex items-center gap-2 mb-1">
            <Lock class="w-5 h-5 text-primary-600" />
            <h2 class="font-semibold text-ink-900">Change Password</h2>
          </div>
          <p class="text-sm text-ink-500 mb-5">Ensure your account stays secure with a strong password.</p>
          <div class="grid sm:grid-cols-2 gap-4 max-w-2xl">
            <div class="sm:col-span-2">
              <label class="label">Current password</label>
              <div class="relative">
                <Lock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" />
                <input
                  v-model="passwordForm.current_password"
                  :type="showCurrent ? 'text' : 'password'"
                  placeholder="Enter current password"
                  class="input pl-10 pr-10"
                  :class="{ 'input-error': passwordError.current_password?.[0] }"
                />
                <button
                  type="button"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-400 hover:text-ink-600"
                  @click="showCurrent = !showCurrent"
                >
                  <component :is="showCurrent ? EyeOff : Eye" class="w-4 h-4" />
                </button>
              </div>
              <p v-if="passwordError.current_password?.[0]" class="error-text">
                {{ passwordError.current_password?.[0] }}
              </p>
              <p class="hint">
                If you haven't set a password yet, you can leave this field empty.
              </p>
            </div>
            <div>
              <label class="label">New password</label>
              <div class="relative">
                <Lock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" />
                <input
                  v-model="passwordForm.password"
                  :type="showNew ? 'text' : 'password'"
                  placeholder="Enter new password"
                  class="input pl-10 pr-10"
                  :class="{ 'input-error': passwordError.password?.[0] }"
                />
                <button
                  type="button"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-400 hover:text-ink-600"
                  @click="showNew = !showNew"
                >
                  <component :is="showNew ? EyeOff : Eye" class="w-4 h-4" />
                </button>
              </div>
              <p v-if="passwordError.password?.[0]" class="error-text">
                {{ passwordError.password?.[0] }}
              </p>
            </div>
            <div>
              <label class="label">Confirm password</label>
              <div class="relative">
                <Lock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" />
                <input
                  v-model="passwordForm.password_confirmation"
                  :type="showConfirm ? 'text' : 'password'"
                  placeholder="Confirm new password"
                  class="input pl-10 pr-10"
                  :class="{ 'input-error': passwordError.password?.[0] }"
                />
                <button
                  type="button"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-400 hover:text-ink-600"
                  @click="showConfirm = !showConfirm"
                >
                  <component :is="showConfirm ? EyeOff : Eye" class="w-4 h-4" />
                </button>
              </div>
              <p v-if="passwordError.password?.[0]" class="error-text">
                {{ passwordError.password?.[0] }}
              </p>
            </div>
          </div>
          <p class="hint mt-2">Use at least 8 characters with a mix of letters, numbers, and symbols.</p>
          <div class="mt-5">
            <BaseButton variant="primary" size="md" @click="savePassword">
              <Lock class="w-4 h-4" />
              Update Password
            </BaseButton>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
