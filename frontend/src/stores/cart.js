import {defineStore} from "pinia";
import {computed, ref} from "vue";
import {useAuthStore} from "@/stores/auth.js";
export const useCartStore = defineStore('cart', () => {
  const authStore = useAuthStore();
  const items = ref([]);
  const count = computed(() => items.value.reduce((sum, item) => sum + item, 0));
  return {
    items,
    count
  }
});
