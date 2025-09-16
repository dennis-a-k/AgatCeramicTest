<template>
  <NuxtLayout>
    <NuxtPage />

    <div class="offcanvas-overlay"></div>

    <CartAppOffcanvasCart />

    <MobileMenuAppMobileMenu />
  </NuxtLayout>
</template>

<script setup>
import { onMounted } from 'vue';

onMounted(() => {
  const offCanvasOverlay = document.querySelector(".offcanvas-overlay");
  
  document.querySelectorAll(".offcanvas-overlay").forEach(el => {
    el.addEventListener("click", function(e) {
      e.preventDefault();
      document.body.classList.remove("offcanvas-open");
      document.querySelectorAll('.offcanvas').forEach(el => {
        el.classList.remove("offcanvas-open");
      });
      offCanvasOverlay.style.display = "none";
    });
  });
});
</script>