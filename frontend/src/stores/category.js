import {defineStore} from "pinia";
import {ref} from "vue";
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
export const useCategoryStore = defineStore('category', () => {
  const categories = ref([]);
  const loading = ref(false);
  const fetchCategories = async () => {
    try {
      loading.value = true;
      const {data} = await api.get(endpoints.categories);
      categories.value = data.categories ?? [];
    }
    finally {
      loading.value = false;
    }
  }
  return {
    categories,
    loading,
    fetchCategories
  }
});
