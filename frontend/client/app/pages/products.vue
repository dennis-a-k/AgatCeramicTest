<template>
  <div>
    <h1>Все товары</h1>
    <nuxt-link to="/">Главная страница</nuxt-link>
    <div v-if="error">
      Error loading products: {{ error.message }}
    </div>
    <div v-else-if="products && products.length">
      <ul>
        <li v-for="product in products" :key="product.id">
          <h2>{{ product.name }}</h2>
          <p>{{ product.description }}</p>
          <p>Price: {{ product.price }}</p>
          <img v-if="product.image" :src="product.image" :alt="product.name" style="width: 100px; height: auto;"/>
        </li>
      </ul>
    </div>
    <div v-else>
      Loading...
    </div>
  </div>
</template>

<script setup lang="ts">
interface Product {
  id: number;
  name: string;
  description: string;
  price: number;
  image: string | null;
}

interface ProductResponse {
  products: {
    data: Product[];
  };
}

// Для Nuxt 4 используем прокси через /api/
const { data: productsData, error } = await useFetch<ProductResponse>('/api/products', {
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  credentials: 'include',
  onResponseError({ response }) {
    console.error('API error:', response.status, response.statusText)
  }
})

// Преобразуем данные в нужный формат
const products = computed(() => {
  if (productsData.value && productsData.value.products && productsData.value.products.data) {
    return productsData.value.products.data;
  }
  return [];
})
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