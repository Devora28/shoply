import { createRouter, createWebHistory } from 'vue-router'
import HomePage from "@/pages/HomePage.vue";
import MainLayout from "@/layouts/MainLayout.vue";
import LoginPage from "@/pages/LoginPage.vue";
import {useAuthStore} from "@/stores/auth.js";
import ProductDetails from "@/pages/ProductDetails.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior() {
    return {
      top: 0,
      left: 0,
    }
  },
  routes: [
    {
      path: '/',
      component: MainLayout,
      children: [
        {
          path: '',
          name: 'home',
          component: HomePage
        },
        {
          path: 'products/:id/:slug?',
          name: 'products.show',
          component: ProductDetails
        },
      ]
    },
    {
      path: '/login',
      name: 'login.page',
      component: LoginPage,
    },
  ],
});
router.beforeEach(async (to) => {
  const authStore = useAuthStore()
  if (to.meta.requiresAuth && !authStore.isAuth) {
    return '/login'
  }
});
export default router
