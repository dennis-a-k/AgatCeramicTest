<template>
  <header>
    <AppHeaderTop />

    <AppHeaderBottom />

    <AppHeaderBottomMobile />

    <AppNavbar />

    <AppNavAlphabet />

    <SearchMobileForm />
  </header>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue'
import AppHeaderTop from '~/components/header/AppHeaderTop.vue'
import AppHeaderBottom from '~/components/header/AppHeaderBottom.vue'
import AppHeaderBottomMobile from '~/components/header/AppHeaderBottomMobile.vue'
import AppNavbar from '~/components/navbar/AppNavbar.vue'
import AppNavAlphabet from '~/components/navbar/AppNavAlphabet.vue'
import SearchMobileForm from '~/components/ui/SearchMobileForm.vue'

// Проверка на клиентское окружение
const isClient = typeof window !== 'undefined'

// Обработчик скролла
const handleScroll = () => {
  if (!isClient) return

  const windowTop = window.scrollY + 1
  const stickyNavs = document.querySelectorAll('.sticky-nav')

  stickyNavs.forEach(stickyNav => {
    if (windowTop > 250) {
      stickyNav.classList.add('menu_fixed', 'animated', 'fadeInDown')
    } else {
      stickyNav.classList.remove('menu_fixed', 'animated', 'fadeInDown')
    }
  })
}

// Анимации для Bootstrap dropdown
const initDropdownAnimations = () => {
  if (!isClient) return

  const dropdowns = document.querySelectorAll('.dropdown')

  dropdowns.forEach(dropdown => {
    dropdown.addEventListener('show.bs.dropdown', function () {
      const menu = this.querySelector('.dropdown-menu')
      if (!menu) return

      menu.classList.remove('slideUp')
      menu.classList.add('slideDown')
    })

    dropdown.addEventListener('hide.bs.dropdown', function () {
      const menu = this.querySelector('.dropdown-menu')
      if (!menu) return

      menu.classList.remove('slideDown')
      menu.classList.add('slideUp')
    })
  })
}

onMounted(() => {
  if (!isClient) return

  window.addEventListener('scroll', handleScroll)
  initDropdownAnimations()
  handleScroll() // Инициализация при загрузке
})

onUnmounted(() => {
  if (!isClient) return

  window.removeEventListener('scroll', handleScroll)
})
</script>