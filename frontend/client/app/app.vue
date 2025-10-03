<template>
  <NuxtLayout>
    <NuxtPage />
    <div class="offcanvas-overlay"></div>
    <CartAppOffcanvasCart />
    <MobileMenuAppMobileMenu />
    <ModalsAppModalCall ref="modalCallRef" />
  </NuxtLayout>
</template>

<script setup>
import { onMounted, ref, provide } from 'vue';

// Ссылка на компонент модального окна
const modalCallRef = ref(null)

// Функция для открытия модального окна
const openCallModal = () => {
  if (modalCallRef.value) {
    modalCallRef.value.openModal()
  }
}

// Предоставляем функцию для дочерних компонентов
provide('openCallModal', openCallModal)

onMounted(() => {
  const offCanvasOverlay = document.querySelector(".offcanvas-overlay");

  document.querySelectorAll(".offcanvas-overlay").forEach(el => {
    el.addEventListener("click", function (e) {
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

<style scoped lang="scss">
.offcanvas-overlay {
  position: fixed;
  z-index: 999;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: none;
  background-color: rgba(0, 0, 0, 0.5);
}
</style>