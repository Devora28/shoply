import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import api from '@/api/axios.js'
import { endpoints } from '@/api/endpoints.js'
import {calcDiscount} from "@/utils/helpers.js";
export const useCartStore = defineStore('cart', () => {
  const items = ref([])
  const loading = ref(false)
  const initialized = ref(false)
  const totalItems = computed(() => {
    return items.value.reduce((total, item) => total + Number(item.quantity),0)
  })
  const getItemFinalPrice = (item) => {
    return calcDiscount(
      getItemPrice(item),
      Number(item.product?.discount ?? 0)
    )
  }
  const subtotal = computed(() => {
    return items.value.reduce(
      (total, item) =>
        total +
        getItemFinalPrice(item) * Number(item.quantity),
      0
    )
  })
  const getItemPrice = (item) => {
    return Number(
      item.variant?.price ??
      item.product?.price ??
      item.price ??
      0
    )
  }
  const isEmpty = computed(() => items.value.length === 0)
  const getItemKey = (productId, variantId = null) => {
    return `${productId}:${variantId ?? 'default'}`
  }
  const findItem = (productId, variantId = null) => {
    const key = getItemKey(productId, variantId)
    return items.value.find(item => {
      return getItemKey(
        item.product_id,
        item.variant_id
      ) === key
    }) ?? null
  }
  const saveGuestCart = () => {
    localStorage.setItem('cart', JSON.stringify(items.value))
  }
  const loadGuestCart = () => {
    try {
      const cart = localStorage.getItem('cart')
      if (!cart) {
        items.value = []
        return
      }
      const parsedCart = JSON.parse(cart)
      items.value = Array.isArray(parsedCart)
        ? parsedCart
        : []

    } catch (error) {
      console.error('Failed to load guest cart:', error)
      items.value = []
    }
  }
  const initialize = async (isAuthenticated) => {
    if (initialized.value) {
      return
    }
    if (isAuthenticated) {
      await fetchCart()
    } else {
      loadGuestCart()
    }
    initialized.value = true
  }
  const fetchCart = async () => {
    const token = localStorage.getItem('auth_token');
    try {
      loading.value = true
      const response = await api.get(endpoints.cart,{
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
      items.value =
        response.data.data.items ?? []
    } catch (error) {
      console.error('Failed to fetch cart:', error)
      throw error
    } finally {
      loading.value = false
    }
  }
  const addToServerCart = async ({productId, variantId, quantity}) => {
    const token = localStorage.getItem('auth_token');
    await api.post(endpoints.cart,
      {
        product_id: productId,
        variant_id: variantId,
        quantity
      },
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )
  }
  const updateServerCartItem = async (cartItemId, quantity) => {
    const token = localStorage.getItem('auth_token');
    await api.patch(endpoints.cartAction(cartItemId),
      {
        quantity
      },
      {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    )
  }
  const removeServerCartItem = async (cartItemId) => {
    const token = localStorage.getItem('auth_token');
    await api.delete(endpoints.cartAction(cartItemId),{
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
  }
  const addToCart = async ({product, variant = null, quantity = 1, isAuthenticated = false}) => {
    if (!product) {
      throw new Error('Product is required.')
    }
    if (quantity < 1) {
      throw new Error('Quantity must be at least 1.')
    }
    const productId = product.id
    const variantId = variant?.id ?? null
    const stock = Number(variant?.stock ?? product.stock ?? 0)
    if (stock <= 0) {
      throw new Error('Product is out of stock.')
    }
    if (quantity > stock) {
      throw new Error(
        'Requested quantity exceeds available stock.'
      )
    }
    if (isAuthenticated) {
      await addToServerCart({
        productId,
        variantId,
        quantity
      })
      await fetchCart()
      return
    }
    const existingItem = findItem(productId, variantId)
    if (existingItem) {
      const newQuantity =
        Number(existingItem.quantity) +
        Number(quantity)
      if (newQuantity > stock) {
        throw new Error(
          'Requested quantity exceeds available stock.'
        )
      }
      existingItem.quantity = newQuantity
    } else {
      items.value.push({
        id: crypto.randomUUID(),
        product_id: productId,
        variant_id: variantId,
        quantity,
        price: variant?.price ?? product.price,
        product,
        variant
      })
    }
    saveGuestCart()
  }
  const updateQuantity = async ({item, quantity, isAuthenticated = false}) => {
    if (!item) {
      return
    }
    quantity = Number(quantity)
    if (quantity < 1) {
      return removeFromCart({
        item,
        isAuthenticated
      })
    }
    const stock = Number(
      item.variant?.stock ??
      item.product?.stock ??
      0
    )
    if (quantity > stock) {
      throw new Error(
        'Requested quantity exceeds available stock.'
      )
    }
    if (isAuthenticated) {
      await updateServerCartItem(
        item.id,
        quantity
      )
      await fetchCart()
      return
    }
    item.quantity = quantity
    saveGuestCart()
  }
  const removeFromCart = async ({item, isAuthenticated = false}) => {
    if (!item) {
      return
    }
    if (isAuthenticated) {
      await removeServerCartItem(item.id)
      await fetchCart()
      return
    }
    items.value = items.value.filter(
      cartItem => cartItem.id !== item.id
    )
    saveGuestCart()
  }
  const clearCart = async (isAuthenticated = false) => {
    const token = localStorage.getItem('auth_token');
    if (isAuthenticated) {
      await api.delete(endpoints.cartClear,{
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
      items.value = []
      return
    }
    items.value = []
    saveGuestCart()
  }
  const mergeGuestCart = async () => {
    const token = localStorage.getItem('auth_token');
    try {
      const storedCart = localStorage.getItem('cart')
      if (!storedCart) {
        await fetchCart()
        return
      }
      const guestCart = JSON.parse(storedCart)
      if (!Array.isArray(guestCart) || guestCart.length === 0) {
        await fetchCart()
        return
      }
      await api.post(endpoints.cartMerge,
        {
          items: guestCart.map(item => ({
            product_id: item.product_id,
            variant_id: item.variant_id ?? null,
            quantity: item.quantity
          }))
        },
        {
          headers: {
            Authorization: `Bearer ${token}`
          }
        }
      )
      localStorage.removeItem('cart')
      await fetchCart();
    } catch (error) {
      console.error(
        'Failed to merge guest cart:',
        error
      )
      throw error
    }
  }
  const reset = () => {
    items.value = []
    loading.value = false
    initialized.value = false
  }
  return {
    items,
    loading,
    initialized,
    totalItems,
    subtotal,
    isEmpty,
    findItem,
    initialize,
    fetchCart,
    addToCart,
    getItemPrice,
    updateQuantity,
    removeFromCart,
    clearCart,
    mergeGuestCart,
    reset
  }
})
