<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Наши товары</h1>

    <NuxtLink to="/"
      class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-medium transition-colors">
      Главная страница
    </NuxtLink>
    <hr>
  </div>
</template>

<script setup>
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