<template>
  <NuxtLayout>
    <NuxtPage />

    <div class="offcanvas-overlay"></div>

    <AppOffcanvasCart />

    <AppMobileMenu />
  </NuxtLayout>
</template>

<script setup>
import AppOffcanvasCart from '~/components/cart/AppOffcanvasCart.vue';
import AppMobileMenu from '~/components/mobile-menu/AppMobileMenu.vue';
import { onMounted } from 'vue';

onMounted(() => {
  // Only keep overlay toggle logic
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