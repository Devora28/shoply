<script setup>
import { computed, ref, watch } from 'vue'
import {
  AsYouType,
  getCountries,
  getCountryCallingCode,
  parsePhoneNumberFromString,
} from 'libphonenumber-js'

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  label: {
    type: String,
    default: 'Phone number',
  },
  hint: {
    type: String,
    default: '',
  },
  error: {
    type: String,
    default: '',
  },
  size: {
    type: String,
    default: 'md',
  },
  rounded: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

const country = ref('US')
const displayPhone = ref('')

const countryNames = new Intl.DisplayNames(['en'], {
  type: 'region',
})

const getFlag = (code) =>
  code
    .split('')
    .map(char => String.fromCodePoint(127397 + char.charCodeAt()))
    .join('')

const countries = computed(() =>
  getCountries()
    .map(code => ({
      code,
      name: countryNames.of(code),
      dialCode: `+${getCountryCallingCode(code)}`,
      flag: getFlag(code),
    }))
    .sort((a, b) => a.name.localeCompare(b.name))
)

const sizeClass = computed(() => ({
  sm: 'input-sm',
  md: 'input',
  lg: 'input-lg',
}[props.size] || 'input'))

function formatPhone(value) {
  const formatter = new AsYouType(country.value)

  displayPhone.value = formatter.input(value)

  if (!displayPhone.value) {
    emit('update:modelValue', '')
    return
  }

  const phoneNumber = parsePhoneNumberFromString(
    displayPhone.value,
    country.value
  )

  if (phoneNumber?.isValid()) {
    emit(
      'update:modelValue',
      phoneNumber.format('E.164')
    )
  } else {
    emit('update:modelValue', displayPhone.value)
  }
}

function changeCountry() {
  displayPhone.value = ''

  emit('update:modelValue', '')
}

function setPhoneFromModel(value) {
  if (!value) {
    displayPhone.value = ''
    return
  }

  const phoneNumber = parsePhoneNumberFromString(value)

  if (!phoneNumber) {
    displayPhone.value = value
    return
  }

  country.value = phoneNumber.country || 'US'

  displayPhone.value = phoneNumber.formatNational()
}

watch(
  () => props.modelValue,
  value => {
    const current = parsePhoneNumberFromString(
      displayPhone.value,
      country.value
    )

    const currentE164 = current?.isValid()
      ? current.format('E.164')
      : ''

    if (value !== currentE164) {
      setPhoneFromModel(value)
    }
  },
  { immediate: true }
)
</script>

<template>
  <div>
    <label v-if="label" class="label">
      {{ label }}
    </label>
    <div class="flex gap-1">
      <select
        v-model="country"
        class="input w-[125px] shrink-0"
        @change="changeCountry"
      >
        <option
          v-for="item in countries"
          :key="item.code"
          :value="item.code"
        >
          {{ item.flag }} {{ item.dialCode }}
        </option>
      </select>
      <input
        :value="displayPhone"
        type="tel"
        placeholder="Phone number"
        class="input flex-1"
        :class="{ 'input-error': error }"
        @input="formatPhone($event.target.value)"
      />

    </div>
    <p v-if="error" class="error-text">
      {{ error }}
    </p>
    <p v-else-if="hint" class="hint">
      {{ hint }}
    </p>
  </div>
</template>
