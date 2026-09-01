<script setup>
import {ref, computed, onMounted} from 'vue'
import {
  MapPin, Plus, Edit, Trash2, Home, Check, Star, X,BriefcaseBusiness,UsersRound
} from '@lucide/vue'
import BaseBreadcrumb from '@/components/ui/BaseBreadcrumb.vue'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseInput from '@/components/ui/BaseInput.vue'
import BaseToggle from '@/components/ui/BaseToggle.vue'
import DesktopDashboardSidebar from "@/components/ui/DesktopDashboardSidebar.vue";
import MobileDashboardSidebar from "@/components/ui/MobileDashboardSidebar.vue";
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
import PhoneInput from "@/components/ui/PhoneInput.vue";
import {toast} from "vue-sonner";
const isLoading = ref(false);
const addresses = ref([]);
const titleIcons = { Home, Work: BriefcaseBusiness, Parents: UsersRound, Other: MapPin }
const showModal = ref(false)
const editingId = ref(null)
const showDeleteModal = ref(false)
const deletingAddress = ref(null)
const isDeleting = ref(false)
const form = ref({
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
});
const formErrors = ref({});
const labelOptions = ['Home', 'Work', 'Parents', 'Other']
const isEditing = computed(() => editingId.value !== null)
function openAdd() {
  editingId.value = null
  showModal.value = true
  form.value = {
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
  }
}
function openEdit(addr) {
  editingId.value = addr.id
  form.value = {
    title: addr.title ?? '',
    receiver_name: addr.receiver_name ?? '',
    company: addr.company ?? '',
    phone: addr.phone ?? '',
    country: addr.country ?? '',
    state: addr.state ?? '',
    city: addr.city ?? '',
    postal_code: addr.postal_code ?? '',
    address: addr.address ?? '',
    is_default: addr.is_default ?? false,
  }
  showModal.value = true
}
function closeModal() {
  showModal.value = false
  editingId.value = null
}
function openDelete(addr) {
  deletingAddress.value = addr
  showDeleteModal.value = true
}
function closeDeleteModal() {
  showDeleteModal.value = false
  deletingAddress.value = null
}
const saveAddress = async () => {
  const token = localStorage.getItem('auth_token');
  try {
    formErrors.value = {};
    const response = await api.post(endpoints.addresses, form.value,{
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    closeModal();
    await fetchAddresses();
    toast.success(response.data.message);
  }
  catch (error) {
    Object.assign(formErrors.value,error.response.data.errors ?? {});
    if (error.response.data.errors.addresses) {
      toast.error(error.response.data.errors.addresses[0]);
      closeModal();
    }
  }
}
const setDefault = async (addrId) => {
  const token = localStorage.getItem('auth_token');
  try {
    const response = await api.patch(endpoints.setDefaultAddr(addrId),
      {},
      {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    await fetchAddresses();
    toast.success(response.data.message);
  }
  catch (error) {
    console.log(error);
  }
}
const updateAddress = async (addrId) => {
  const token = localStorage.getItem('auth_token');
  try {
    formErrors.value = {};
    const response = await api.put(endpoints.address(addrId), form.value,{
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    closeModal();
    await fetchAddresses();
    toast.success(response.data.message);
  }
  catch (error) {
    Object.assign(formErrors.value,error?.response?.data?.errors ?? {});
  }
}
const deleteAddress = async () => {
  const token = localStorage.getItem('auth_token');
  try {
    isDeleting.value = true;
    const response = await api.delete(endpoints.address(deletingAddress.value.id),{
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    closeDeleteModal()
    await fetchAddresses();
    toast.success(response.data.message);
  }
  catch (error) {
    closeDeleteModal();
    toast.error(error?.response?.data?.errors?.[0] ?? 'Failed to delete address');
  }
  finally {
    isDeleting.value = false;
  }
}
const fetchAddresses = async () => {
  const token = localStorage.getItem('auth_token');
  try {
    isLoading.value = true;
    const response = await api.get(endpoints.addresses,{
      headers: {
        Authorization: `Bearer ${token}`,
      }
    });
    addresses.value = response.data.data.addresses
  }
  catch (error) {
    console.error(error)
  }
  finally {
    isLoading.value = false;
  }
}
onMounted( () => {
   fetchAddresses();
})
</script>

<template>
  <div class="section py-8 lg:py-12">
    <BaseBreadcrumb :items="['Account', 'Addresses']" class="mb-6" />
    <div class="grid lg:grid-cols-[260px_1fr] gap-8">
      <!-- Sidebar (desktop) -->
      <DesktopDashboardSidebar/>
      <!-- Mobile tab bar -->
      <MobileDashboardSidebar/>
      <!-- Main content -->
      <div class="min-w-0 space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold text-ink-900">Saved Addresses</h1>
            <p class="text-ink-500 mt-1">Manage your shipping and billing addresses.</p>
          </div>
          <BaseButton variant="primary" size="md" @click="openAdd">
            <Plus class="w-4 h-4" />
            Add New Address
          </BaseButton>
        </div>
        <!-- Loading state -->
        <div v-if="isLoading" class="grid sm:grid-cols-2 gap-4">
          <div
            v-for="i in 2"
            :key="i"
            class="card p-5 animate-pulse"
          >
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-ink-100"></div>
                <div class="w-20 h-4 rounded bg-ink-100"></div>
              </div>
              <div class="w-16 h-6 rounded-full bg-ink-100"></div>
            </div>
            <!-- Address details -->
            <div class="space-y-2 mb-5">
              <div class="w-32 h-4 rounded bg-ink-100"></div>
              <div class="w-full h-4 rounded bg-ink-100"></div>
              <div class="w-3/4 h-4 rounded bg-ink-100"></div>
              <div class="w-24 h-4 rounded bg-ink-100"></div>
              <div class="w-28 h-4 rounded bg-ink-100 mt-3"></div>
            </div>
            <!-- Actions -->
            <div class="flex items-center gap-2 pt-4 border-t border-ink-100">
              <div class="w-16 h-8 rounded-xl bg-ink-100"></div>
              <div class="w-20 h-8 rounded-xl bg-ink-100"></div>
              <div class="ml-auto w-24 h-4 rounded bg-ink-100"></div>
            </div>
          </div>
        </div>
        <!-- Address cards -->
        <div v-else-if="addresses.length" class="grid sm:grid-cols-2 gap-4">
          <!-- Existing addresses -->
          <div
            v-for="addr in addresses"
            :key="addr.id"
            class="card card-hover p-5 relative"
            :class="addresses.length === 1 && 'sm:col-span-2'"
          >
            <!-- Default badge -->
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center">
                  <component :is="titleIcons[addr.title]" class="w-4 h-4" />
                </div>
                <span class="font-semibold text-ink-900 text-sm">{{ addr.title }}</span>
              </div>
              <span v-if="addr.is_default" class="badge-primary">
                <Star class="w-3 h-3" />
                Default
              </span>
            </div>
            <!-- Address details -->
            <div class="text-sm text-ink-600 space-y-1 mb-4">
              <p class="font-medium text-ink-900">{{ addr.receiver_name }}</p>
              <p>{{ addr.address }}</p>
              <p>{{ addr.country }}, {{ addr.city }}, {{ addr.state }} {{ addr.postal_code }}</p>
              <p v-if="addr.company" class="text-sm text-ink-600">
                {{ addr.company }}
              </p>
              <p v-else class="text-sm text-ink-400">
                No company provided
              </p>
              <p class="pt-1 text-ink-500">{{ addr.phone }}</p>
            </div>
            <!-- Actions -->
            <div class="flex items-center gap-2 pt-4 border-t border-ink-200">
              <BaseButton variant="secondary" size="sm" @click="openEdit(addr)">
                <Edit class="w-3.5 h-3.5" />
                Edit
              </BaseButton>
              <BaseButton
                variant="ghost"
                size="sm"
                class="!text-danger-600 hover:!bg-danger-50"
                @click="openDelete(addr)"
              >
                <Trash2 class="w-3.5 h-3.5" />
                Delete
              </BaseButton>
              <button
                v-if="!addr.is_default"
                class="ml-auto text-xs text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1"
                @click="setDefault(addr.id)"
              >
                <Check class="w-3.5 h-3.5" />
                Set as default
              </button>
            </div>
          </div>
        </div>
        <!-- Empty state -->
        <div
          v-else
          class="card p-8 sm:p-10 flex flex-col items-center justify-center text-center min-h-[280px]"
        >
          <div class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mb-4">
            <MapPin class="w-7 h-7" />
          </div>
          <h3 class="font-semibold text-ink-900">
            No saved addresses yet
          </h3>
          <p class="text-sm text-ink-500 mt-1.5 max-w-sm">
            Add a shipping address to make checkout faster and easier next time.
          </p>
          <BaseButton
            variant="primary"
            size="sm"
            class="mt-5"
            @click="openAdd"
          >
            <Plus class="w-4 h-4" />
            Add New Address
          </BaseButton>
        </div>
      </div>
    </div>
    <!-- Add/Edit modal -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
          <div class="absolute inset-0 bg-ink-900/50 backdrop-blur-sm" @click="closeModal" />
          <div class="relative card w-full max-w-lg max-h-[90vh] overflow-y-auto p-6">
            <!-- Modal header -->
            <div class="flex items-center justify-between mb-5">
              <h2 class="text-lg font-bold text-ink-900">
                {{ isEditing ? 'Edit Address' : 'Add New Address' }}
              </h2>
              <button class="btn-ghost btn-icon" @click="closeModal">
                <X class="w-5 h-5" />
              </button>
            </div>
            <!-- Form -->
            <div class="space-y-4">
              <!-- Label selector -->
              <div>
                <label class="label">Address Title</label>
                <div class="flex flex-wrap gap-2">
                  <label
                    v-for="l in labelOptions"
                    :key="l"
                    class="chip cursor-pointer"
                    :class="form.title === l && 'chip-active'"
                  >
                    <input
                      v-model="form.title"
                      type="radio"
                      name="address-label"
                      :value="l"
                      class="sr-only"
                    />
                    <component :is="titleIcons[l]" class="w-4 h-4" />
                    {{ l }}
                  </label>
                </div>
              </div>
              <div class="grid sm:grid-cols-2 gap-4">
                <BaseInput
                  v-model="form.receiver_name"
                  label="Full name" placeholder="John Doe"
                  :error="formErrors.receiver_name?.[0]"
                />
                <BaseInput
                  v-model="form.company"
                  label="Company" placeholder="Microsoft"
                  :error="formErrors.company?.[0]"
                />
              </div>
              <PhoneInput
                v-model="form.phone"
                label="Phone number"
                placeholder="+1 (555) 000-0000"
                :error="formErrors.phone?.[0]"
              />
              <div class="grid sm:grid-cols-2 gap-4">
                <BaseInput
                  v-model="form.address"
                  label="Address"
                  placeholder="Street address, apartment, suite"
                  :error="formErrors.address?.[0]"
                />
                <div>
                  <label class="label">Country</label>
                  <select v-model="form.country" class="input">
                    <option value="" disabled>Select Option</option>
                    <option value="United States">United States</option>
                    <option value="Canada">Canada</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="Australia">Australia</option>
                    <option value="Germany">Germany</option>
                  </select>
                  <p v-if="formErrors.country?.[0]" class="error-text">
                    {{ formErrors.country?.[0] }}
                  </p>
                </div>
              </div>
              <div class="grid sm:grid-cols-3 gap-4">
                <BaseInput
                  v-model="form.city"
                  label="City"
                  placeholder="Portland"
                  :error="formErrors.city?.[0]"
                />
                <BaseInput
                  v-model="form.state"
                  label="State"
                  placeholder="OR"
                  :error="formErrors.state?.[0]"
                />
                <BaseInput
                  v-model="form.postal_code"
                  label="Postal code"
                  placeholder="97201"
                  :error="formErrors.postal_code?.[0]"
                />
              </div>
              <div class="pt-2">
                <BaseToggle
                  v-model="form.is_default"
                  label="Set as default address"
                  hint="This address will be pre-selected at checkout."
                />
              </div>
            </div>

            <!-- Modal actions -->
            <div class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-end mt-6 pt-5 border-t border-ink-200">
              <BaseButton variant="ghost" size="md" @click="closeModal">Cancel</BaseButton>
              <BaseButton variant="primary" size="md" @click="isEditing ? updateAddress(editingId) : saveAddress()">
                <Check class="w-4 h-4" />
                {{ isEditing ? 'Save Changes' : 'Add Address' }}
              </BaseButton>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
    <Teleport to="body">
      <Transition name="fade">
        <div
          v-if="showDeleteModal"
          class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
          <!-- Backdrop -->
          <div
            class="absolute inset-0 bg-ink-900/50 backdrop-blur-sm"
            @click="!isDeleting && closeDeleteModal()"
          />
          <!-- Modal -->
          <div class="relative card w-full max-w-md p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-bold text-ink-900">
                Delete Address
              </h2>
              <button
                class="btn-ghost btn-icon"
                :disabled="isDeleting"
                @click="closeDeleteModal"
              >
                <X class="w-5 h-5" />
              </button>
            </div>
            <!-- Content -->
            <div class="space-y-3">
              <div>
                <p class="font-semibold text-ink-900">
                  Are you sure you want to delete this address?
                </p>
                <p class="text-sm text-ink-500 mt-1">
                  This action cannot be undone.
                </p>
              </div>
              <!-- Address preview -->
              <div
                v-if="deletingAddress"
                class="rounded-xl border border-ink-200 bg-ink-50 p-3"
              >
                <div class="flex items-center gap-2 mb-1">
                  <component
                    :is="titleIcons[deletingAddress.title]"
                    class="w-4 h-4 text-primary-600"
                  />
                  <span class="font-semibold text-sm text-ink-900">
                {{ deletingAddress.title }}
              </span>
                </div>
                <p class="text-sm text-ink-700">
                  {{ deletingAddress.receiver_name }}
                </p>
                <p class="text-sm text-ink-500 truncate">
                  {{ deletingAddress.address }}
                </p>
              </div>
            </div>
            <!-- Actions -->
            <div
              class="flex flex-col-reverse sm:flex-row gap-3 sm:justify-end mt-6 pt-5 border-t border-ink-200"
            >
              <BaseButton
                variant="ghost"
                size="md"
                :disabled="isDeleting"
                @click="closeDeleteModal"
              >
                Cancel
              </BaseButton>
              <BaseButton
                variant="danger"
                size="md"
                :disabled="isDeleting"
                @click="deleteAddress"
              >
                <Trash2 class="w-4 h-4" />
                {{ isDeleting ? 'Deleting...' : 'Delete Address' }}
              </BaseButton>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
