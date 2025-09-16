<template>
  <div class="page">
    <AppHeader />
    <main class="content">
      <slot />
    </main>
    <AppFooter />
  </div>
</template>

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