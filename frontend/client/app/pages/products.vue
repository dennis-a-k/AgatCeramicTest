<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Наши товары</h1>

    <NuxtLink to="/"
      class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-medium transition-colors">
      Главная страница
    </NuxtLink>
    <hr>
    <!-- Состояние загрузки -->
    <div v-if="pending" class="text-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
      <p class="mt-4">Загрузка товаров...</p>
    </div>

    <!-- Состояние ошибки -->
    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
      <p>Ошибка при загрузке товаров: {{ error.message }}</p>
      <button @click="refresh" class="mt-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
        Попробовать снова
      </button>
    </div>

    <!-- Состояние успешной загрузки -->
    <div v-else>
      <!-- Поиск и фильтрация -->
      <div class="mb-6">
        <input v-model="searchQuery" type="text" placeholder="Поиск товаров..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
      </div>

      <!-- Сетка товаров -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="product in filteredProducts" :key="product.id"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
          <div class="p-4">
            <h3 class="text-lg font-semibold mb-2">{{ product.name }}</h3>
            <p class="text-gray-600 text-sm mb-3">{{ product.description }}</p>
            <div class="flex justify-between items-center">
              <span class="text-2xl font-bold text-blue-600">{{ product.price }} ₽</span>
              <button @click="addToCart(product)"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                В корзину
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Пустой список -->
      <div v-if="filteredProducts.length === 0" class="text-center py-12">
        <p class="text-gray-500 text-lg">Товары не найдены</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Product } from '~/types/product'

const searchQuery = ref('')
const products = ref<Product[]>([])
const pending = ref(true)
const error = ref<Error | null>(null)

const loadProducts = async () => {
  try {
    pending.value = true
    error.value = null
    const data = await $fetch<Product[]>('/api/products')
    products.value = data.products.data
  } catch (err) {
    error.value = err as Error
    console.error('Ошибка загрузки товаров:', err)
  } finally {
    pending.value = false
  }
}

onMounted(() => {
  loadProducts()
})

const refresh = () => {
  loadProducts()
}

const filteredProducts = computed(() => {
  if (!products.value) return []

  const query = searchQuery.value.toLowerCase().trim()
  if (!query) return products.value

  return products.value.filter((product: Product) =>
    product.name?.toLowerCase().includes(query) ||
    product.description?.toLowerCase().includes(query)
  )
})

const addToCart = (product: Product) => {
  console.log('Добавлено в корзину:', product)
}
</script>

<style scoped>
.container {
  max-width: 1200px;
}

.animate-spin {
  animation: spin 1s linear infinite;
}

li {
  border: 1px solid #ccc;
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 5px;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
}
</style>