import {defineStore} from "pinia";
import {computed, ref} from "vue";
import api from "@/api/axios.js";
import {endpoints} from "@/api/endpoints.js";
export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const isAuth = computed(() => user.value !== null);
  const fetchUser = async () => {
    try {
      const token = localStorage.getItem('auth_token');
      if (!token) {
        user.value = null;
        return;
      }
      const response = await api.get(endpoints.user,{
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
      user.value = response.data;
    }
    catch (error) {
      user.value = null;
    }
  }
  const login = async (token) => {
    localStorage.setItem('auth_token', token);
    await fetchUser();
  };
  const logout = () => {
    user.value = null;
    localStorage.removeItem('auth_token');
  }
  return {
    user,
    isAuth,
    fetchUser,
    login,
    logout
  }
});
