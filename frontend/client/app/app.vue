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
  // Offcanvas toggle logic
  const offCanvasToggle = document.querySelectorAll(".offcanvas-toggle");
  const offCanvas = document.querySelector(".offcanvas");
  const offCanvasOverlay = document.querySelector(".offcanvas-overlay");
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
  
  offCanvasToggle.forEach(toggle => {
    toggle.addEventListener("click", function(e) {
      e.preventDefault();
      const target = this.getAttribute("href");
      document.body.classList.add("offcanvas-open");
      const targetElement = document.querySelector(target);
      targetElement.classList.add("offcanvas-open");
      
      // Добавляем класс offcanvas-open к мобильному меню
      if (target === "#offcanvas-mobile-menu") {
        document.getElementById("offcanvas-mobile-menu").classList.add("offcanvas-open");
      }
      offCanvasOverlay.style.display = "block";
      
      if (this.parentElement.classList.contains("mobile-menu-toggle")) {
        this.classList.add("close");
      }
    });
  });
  
  document.querySelectorAll(".offcanvas-close, .offcanvas-overlay").forEach(el => {
    el.addEventListener("click", function(e) {
      e.preventDefault();
      document.body.classList.remove("offcanvas-open");
      // Удаляем класс offcanvas-open со всех оффканвасов
      document.querySelectorAll('.offcanvas').forEach(el => {
        el.classList.remove("offcanvas-open");
      });
      offCanvasOverlay.style.display = "none";
      if (mobileMenuToggle) {
        mobileMenuToggle.querySelectorAll("a").forEach(a => a.classList.remove("close"));
      }
    });
  });
  
  // Offcanvas menu initialization
  const offCanvasNav = document.querySelectorAll(".offcanvas-menu, .overlay-menu");
  
  offCanvasNav.forEach(nav => {
    const subMenus = nav.querySelectorAll(".sub-menu");
    
    subMenus.forEach(subMenu => {
      const expander = document.createElement("span");
      expander.className = "menu-expand";
      subMenu.parentNode.insertBefore(expander, subMenu);
    });
    
    nav.addEventListener("click", function(e) {
      const target = e.target;
      
      if (target.getAttribute("href") === "#" || target.classList.contains("menu-expand")) {
        e.preventDefault();
        
        if (target.nextElementSibling && target.nextElementSibling.style.display === "block") {
          target.parentNode.classList.remove("active");
          target.nextElementSibling.style.display = "none";
        } else {
          target.parentNode.classList.add("active");
          target.nextElementSibling.style.display = "block";
        }
      }
    });
  });
});
</script>