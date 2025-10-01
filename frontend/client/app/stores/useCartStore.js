import { defineStore } from 'pinia'
import { ref, computed, onMounted } from 'vue'

const CART_STORAGE_KEY = 'agat_cart'
const CART_EXPIRY_DAYS = 7

export const useCartStore = defineStore('cart', () => {
  const items = ref([])
  const expiry = ref(null)

  // Загрузка корзины из localStorage
  const loadCart = () => {
    if (process.client) {
      try {
        const stored = localStorage.getItem(CART_STORAGE_KEY)
        if (stored) {
          const data = JSON.parse(stored)
          const now = Date.now()
          if (data.expiry && now < data.expiry) {
            items.value = data.items || []
            expiry.value = data.expiry
          } else {
            // Корзина истекла, очищаем
            clearCart()
          }
        }
      } catch (error) {
        console.error('Error loading cart:', error)
        clearCart()
      }
    }
  }

  // Сохранение корзины в localStorage
  const saveCart = () => {
    if (process.client) {
      try {
        const now = Date.now()
        const expiryTime = now + (CART_EXPIRY_DAYS * 24 * 60 * 60 * 1000)
        expiry.value = expiryTime
        const data = {
          items: items.value,
          expiry: expiryTime
        }
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(data))
      } catch (error) {
        console.error('Error saving cart:', error)
      }
    }
  }

  // Добавление товара в корзину
  const addToCart = (product) => {
    const existingItem = items.value.find(item => item.id === product.id)
    if (existingItem) {
      existingItem.quantity += product.quantity || 1
    } else {
      items.value.push({
        id: product.id,
        title: product.title,
        weight_kg: product.weight_kg,
        quantity: product.quantity || 1,
        price: product.price,
        image: product.image
      })
    }
    saveCart()
  }

  // Удаление товара из корзины
  const removeFromCart = (id) => {
    items.value = items.value.filter(item => item.id !== id)
    saveCart()
  }

  // Обновление количества товара
  const updateQuantity = (id, quantity) => {
    const item = items.value.find(item => item.id === id)
    if (item) {
      item.quantity = Math.max(0, quantity)
      if (item.quantity === 0) {
        removeFromCart(id)
      } else {
        saveCart()
      }
    }
  }

  // Очистка корзины
  const clearCart = () => {
    items.value = []
    expiry.value = null
    if (process.client) {
      localStorage.removeItem(CART_STORAGE_KEY)
    }
  }

  // Общая стоимость
  const total = computed(() => {
    return items.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
  })

  // Количество товаров
  const itemCount = computed(() => {
    return items.value.reduce((sum, item) => sum + item.quantity, 0)
  })

  // Инициализация при создании store
  if (process.client) {
    onMounted(() => {
      loadCart()
    })
  }

  return {
    items,
    total,
    itemCount,
    addToCart,
    removeFromCart,
    updateQuantity,
    clearCart
  }
})