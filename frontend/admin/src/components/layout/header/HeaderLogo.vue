<template>
  <router-link to="/" class="lg:hidden flex flex-row items-center">
    <img class="dark:hidden mr-2" src="/images/logo/logo.svg"
      width="32"
      height="32"
      alt="Logo"
    />
    <div class="dark:hidden font-bold">
      <p>Agat<span class="text-gray-400">Ceramic</span></p>
      <p class="text-theme-2xs text-end">Админ-панель</p>
    </div>
    <img class="hidden dark:block mr-2" src="/images/logo/logo.svg"
      style="filter: invert(1) brightness(2)"
      width="32"
      height="32"
      alt="Logo"
    />
    <div class="hidden dark:block font-bold">
      <p class="text-white">Agat<span class="text-gray-400">Ceramic</span></p>
      <p class="text-white text-theme-2xs text-end">Админ-панель</p>
    </div>
  </router-link>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
