<template>
  <div
    class="flex flex-col justify-between gap-5 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center dark:border-gray-800">
    <h3 class="text-base font-medium text-gray-800 dark:text-white/90 flex flex-row items-center">
      <component :is="packageIcon" class="menu-item-icon-active mr-2" />
      Список товаров ({{ totalItems }})
    </h3>
    <div class="flex flex-col md:flex-row gap-3">
      <button
        class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
        Скачать товары
        <component :is="downloadIcon" />
      </button>
      <button
        class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]"
        @click="$emit('bulkUpload')">
        Массовая загрузка
      </button>
      <router-link to="/products/create"
        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
        <component :is="plusIcon" width="20" height="20" />
        Добавить товар
      </router-link>
    </div>
  </div>
</template>

<script setup>
defineProps({
  totalItems: {
    type: Number,
    required: true
  },
  packageIcon: {
    type: Object,
    required: true
  },
  downloadIcon: {
    type: Object,
    required: true
  },
  plusIcon: {
    type: Object,
    required: true
  }
})

defineEmits(['bulkUpload'])
</script>
