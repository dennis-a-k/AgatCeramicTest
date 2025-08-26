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
const products = ref([])
const error = ref(null)

try {
  // Используем проксированный путь
  const response = await $fetch('/api/products', {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    credentials: 'include'
  })
  
  if (response.products && response.products.data) {
    products.value = response.products.data
  }
} catch (err) {
  error.value = err
  console.error('API error:', err)
}
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