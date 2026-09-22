import axios from 'axios'
import {useAuthStore} from "@/stores/auth.js";
import router from "@/router/index.js";

const api = axios.create({
  baseURL: '/api',
  withCredentials: true
})
api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response?.status === 401 && error.config.url !== '/logout') {
      const authStore = useAuthStore()
      authStore.logout()
      if (router.currentRoute.value.meta.requiresAuth) {
        router.push({ name: 'login.page' })
      }
    }
    return Promise.reject(error)
  }
)
export default api
