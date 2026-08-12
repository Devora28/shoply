import { createRouter, createWebHistory } from 'vue-router'
import HomePage from "@/pages/HomePage.vue";
import MainLayout from "@/layouts/MainLayout.vue";
import LoginPage from "@/pages/LoginPage.vue";
import {useAuthStore} from "@/stores/auth.js";
import ProductDetails from "@/pages/ProductDetails.vue";
import ProfileDashboard from "@/pages/user/ProfileDashboard.vue";

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
        {
          path: 'profile/dashboard',
          name: 'profile.dashboard',
          component: ProfileDashboard,
          meta: { requiresAuth: true },
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
  const authStore = useAuthStore();
  await authStore.fetchUser();
  if (to.meta.requiresAuth && !authStore.isAuth) {
    return {
      name: 'login.page',
      query: {
        redirect: to.fullPath,
      }
    }
  }
});
export default router
