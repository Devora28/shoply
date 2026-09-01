import { createRouter, createWebHistory } from 'vue-router'
import HomePage from "@/pages/HomePage.vue";
import MainLayout from "@/layouts/MainLayout.vue";
import LoginPage from "@/pages/LoginPage.vue";
import {useAuthStore} from "@/stores/auth.js";
import ProductDetails from "@/pages/ProductDetails.vue";
import AccountDashboard from "@/pages/account/AccountDashboard.vue";
import AccountInformation from "@/pages/account/AccountInformation.vue";
import AccountOrders from "@/pages/account/AccountOrders.vue";
import AccountNotifications from "@/pages/account/AccountNotifications.vue";
import AccountAddresses from "@/pages/account/AccountAddresses.vue";
import AccountWishlist from "@/pages/account/AccountWishlist.vue";


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
          component: HomePage,
          meta: {title: 'Home'}
        },
        {
          path: 'products/:id/:slug?',
          name: 'products.show',
          component: ProductDetails
        },
        {
          path: 'account/dashboard',
          name: 'account.dashboard',
          component: AccountDashboard,
          meta: {
            requiresAuth: true,
            transition: 'account'
          },
        },
        {
          path: 'account/information',
          name: 'account.information',
          component: AccountInformation,
          meta: {
            requiresAuth: true,
            transition: 'account'
          },
        },
        {
          path: 'account/orders',
          name: 'account.orders',
          component: AccountOrders,
          meta: {
            requiresAuth: true,
            transition: 'account'
          },
        },
        {
          path: 'account/notifications',
          name: 'account.notifications',
          component: AccountNotifications,
          meta: {
            requiresAuth: true,
            transition: 'account'
          },
        },
        {
          path: 'account/addresses',
          name: 'account.addresses',
          component: AccountAddresses,
          meta: {
            requiresAuth: true,
            transition: 'account'
          },
        },
        {
          path: 'wishlist',
          name: 'wishlist',
          component: AccountWishlist,
          meta: {
            requiresAuth: true,
          },
        },
      ]
    },
    {
      path: '/login',
      name: 'login.page',
      component: LoginPage,
    },
   /* {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: () => import('@/views/NotFound.vue'),
    }*/
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
