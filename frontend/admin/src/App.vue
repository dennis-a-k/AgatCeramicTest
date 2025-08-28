<script setup>
import { ref, onMounted } from 'vue'

const products = ref([])
const loading = ref(false)
const error = ref(null)

const fetchProducts = async () => {
  loading.value = true
  error.value = null
  
  try {
    const response = await fetch('http://test.dennistp.beget.tech/api/products')
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    
    const data = await response.json()
    console.log(data);
    
    products.value = data.products.data
  } catch (err) {
    error.value = err.message
    console.error('Error fetching products:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProducts()
})
</script>

<template>
  <h1>You did it!</h1>
  
  <div v-if="loading">Загрузка товаров...</div>
  
  <div v-else-if="error" class="error">
    Ошибка при загрузке: {{ error }}
    <button @click="fetchProducts">Попробовать снова</button>
  </div>
  
  <div v-else-if="products.length === 0">
    Товары не найдены
  </div>
  
  <div v-else>
    <h2>Список товаров</h2>
    <div v-for="product in products" :key="product.id" class="product">
      <h3>{{ product.name }}</h3>
      <p>Цена: {{ product.price }}</p>
      <p v-if="product.description">{{ product.description }}</p>
    </div>
  </div>

  <p>
    Visit <a href="https://vuejs.org/" target="_blank" rel="noopener">vuejs.org</a> to read the
    documentation
    <p>Hello</p>
  </p>
</template>

<style scoped>
.product {
  border: 1px solid #ddd;
  padding: 1rem;
  margin: 1rem 0;
  border-radius: 4px;
}

.error {
  color: red;
  padding: 1rem;
  background-color: #ffe6e6;
  border: 1px solid red;
  border-radius: 4px;
}

button {
  margin-left: 1rem;
  padding: 0.5rem 1rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}
</style>