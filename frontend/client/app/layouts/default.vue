<template>
  <div class="page">
    <Header />
    <main class="content">
      <slot />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import Header from '~/components/AppHeader.vue'
import Footer from '~/components/AppFooter.vue'
</script>

<style scoped>
.page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.content {
  flex: 1;
}
</style>