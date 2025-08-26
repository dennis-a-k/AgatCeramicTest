<template>
  <div>
    <h1>All Products</h1>
    <nuxt-link to="/">Home</nuxt-link>
    <ul v-if="products.length">
      <li v-for="product in products" :key="product.id">
        <h2>{{ product.name }}</h2>
        <p>{{ product.description }}</p>
        <p>Price: {{ product.price }}</p>
        <img v-if="product.image" :src="product.image" :alt="product.name" style="width: 100px; height: auto;"/>
      </li>
    </ul>
    <p v-else>No products found.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const products = ref([]);
const runtimeConfig = useRuntimeConfig(); // Добавляем useRuntimeConfig

onMounted(async () => {
  try {
    const response = await fetch(`${runtimeConfig.public.apiBaseUrl}products`); // Используем apiBaseUrl
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    products.value = data.products.data;
  } catch (error) {
    console.error("Error fetching products:", error);
  }
});
</script>

<style scoped>
/* Scoped styles for the page will go here */
ul {
  list-style: none;
  padding: 0;
}

li {
  border: 1px solid #ccc;
  margin-bottom: 10px;
  padding: 10px;
  border-radius: 5px;
}
</style>

<script setup lang="ts">
const { data: products, error } = await useFetch('/api/products', {
  baseURL: useRuntimeConfig().public.apiBaseUrl,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  credentials: 'include', // Для передачи кук при аутентификации
  onResponseError({ response }) {
    console.error('API error:', response.status, response.statusText)
  }
})
</script>

<template>
  <div>
    <h1>Products</h1>
    <div v-if="error">
      Error loading products: {{ error.message }}
    </div>
    <div v-else-if="products">
      <ul>
        <li v-for="product in products" :key="product.id">
          {{ product.name }} - {{ product.price }}
        </li>
      </ul>
    </div>
    <div v-else>
      Loading...
    </div>
  </div>
</template>
