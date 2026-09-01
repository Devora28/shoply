import {defineStore} from "pinia";
import {ref} from "vue";
import {endpoints} from "@/api/endpoints.js";
import api from "@/api/axios.js";
export const useWishlistStore = defineStore('wishlist',() => {
  const wishlistItems = ref([]);
  const itemsCount = ref(0);
  const loading = ref(true);
  const toggling = ref(false);
  const loadWishlist = async () => {
    const token = localStorage.getItem('auth_token')
    try {
      const response = await api.get(endpoints.wishlist,{
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
      wishlistItems.value = response.data.data.items;
      itemsCount.value = response.data.data.items_count;
    }
    catch (error) {
      console.log(error);
    }
    finally {
      loading.value = false;
    }
  }
  const getWishlistItem = (productId) => {
    return wishlistItems.value.find(
      item => Number(item.product_id) === Number(productId)
    );
  };
  const isInWishlist = (productId) => {
    return !!getWishlistItem(productId);
  };
  const toggleWishlist = async (productId) => {
    const token = localStorage.getItem('auth_token');
    try {
      toggling.value = true;
      const wishlistItem = getWishlistItem(productId);
      if (!wishlistItem) {
        await api.post(endpoints.wishlistItem(productId),
          {
            product_id: productId
          },
          {
            headers: {
              Authorization: `Bearer ${token}`
            }
          }
        );
      } else {
        await api.delete(
          endpoints.wishlistItem(wishlistItem.id),
          {
            headers: {
              Authorization: `Bearer ${token}`
            }
          }
        );
      }
      await loadWishlist();
    }
    catch (error) {
      console.log(error);
    }
    finally {
      toggling.value = false;
    }
  };
  const clearWishlist = () => {
    wishlistItems.value = []
    itemsCount.value = 0
  }
  return {
    wishlistItems,
    itemsCount,
    loading,
    loadWishlist,
    isInWishlist,
    toggleWishlist,
    clearWishlist
  }
});
