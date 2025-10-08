<template>
  <div class="relative flex-1 sm:flex-auto sm:w-auto sm:min-w-[300px]">
    <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
      <component :is="searchIcon" />
    </span>
    <input type="text" placeholder="Поиск товара" :value="searchQuery" @input="emit('update:searchQuery', $event.target.value)"
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
</script>
