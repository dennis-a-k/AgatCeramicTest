<template>
  <div class="relative w-full">
    <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
      <component :is="searchIcon" />
    </span>
    <label for="searchQuery"></label>
    <input id="searchQuery" type="text" placeholder="Поиск по номеру заказа, ФИО или почте клиента" :value="searchQuery"
      @input="emit('update:searchQuery', $event.target.value)"
      class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-12 pl-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
    <button v-if="searchQuery" @click="clearSearch"
      class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl leading-none cursor-pointer bg-white dark:bg-gray-900 px-1 z-10">
      ✕
    </button>
  </div>
  <div class="relative w-full">
    <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
      <component :is="searchIcon" />
    </span>
    <label for="searchQueryPhone"></label>
    <input id="searchQueryPhone" type="tel" placeholder="+7 (___) ___-__-__" :value="searchQuery"
      @input="formatPhone"
      class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-12 pl-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
    <button v-if="searchQuery" @click="clearSearch"
      class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl leading-none cursor-pointer bg-white dark:bg-gray-900 px-1 z-10">
      ✕
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  searchQuery: String,
  searchIcon: Object
})

const emit = defineEmits(['update:searchQuery'])

const clearSearch = () => {
  emit('update:searchQuery', '')
}

const formatPhone = (event) => {
  let value = event.target.value.replace(/\D/g, '');
  if (value.startsWith('7')) {
    value = value.substring(1);
  }
  if (value.length > 10) {
    value = value.substring(0, 10);
  }
  let formatted = '+7';
  if (value.length > 0) {
    formatted += ' (' + value.substring(0, 3);
  }
  if (value.length >= 4) {
    formatted += ') ' + value.substring(3, 6);
  }
  if (value.length >= 7) {
    formatted += '-' + value.substring(6, 8);
  }
  if (value.length >= 9) {
    formatted += '-' + value.substring(8, 10);
  }
  emit('update:searchQuery', formatted);
}
</script>
