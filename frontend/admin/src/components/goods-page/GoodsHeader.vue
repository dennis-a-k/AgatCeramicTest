<template>
  <div
    class="flex flex-col justify-between gap-5 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center dark:border-gray-800">
    <h3 class="text-base font-medium text-gray-800 dark:text-white/90 flex flex-row items-center">
      <component :is="packageIcon" class="menu-item-icon-active mr-2" />
      Список товаров ({{ totalItems }})
    </h3>
    <div class="inline-flex items-center shadow-theme-xs">
      <button
        class="inline-flex items-center gap-2 px-4 py-3 -ml-px text-sm font-medium bg-transparent text-gray-700 ring-1 ring-inset ring-gray-300 first:rounded-l-lg last:rounded-r-lg hover:bg-gray-50 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
        <component :is="editIcon" />
        Редактировать
      </button>
      <button
        class="inline-flex items-center gap-2 px-4 py-3 -ml-px text-sm font-medium bg-transparent text-gray-700 ring-1 ring-inset ring-gray-300 first:rounded-l-lg last:rounded-r-lg hover:bg-gray-50 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
        Скачать
        <component :is="uploadIcon" width="18" height="18" />
      </button>
      <button @click="$emit('bulkUpload')"
        class="inline-flex items-center gap-2 px-4 py-3 -ml-px text-sm font-medium bg-transparent text-gray-700 ring-1 ring-inset ring-gray-300 first:rounded-l-lg last:rounded-r-lg hover:bg-gray-50 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
        Загрузить
        <component :is="downloadIcon" />
      </button>
      <router-link to="/products/create"
        class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition bg-brand-500 ring-1 ring-inset ring-brand-500 first:rounded-l-lg last:rounded-r-lg hover:bg-brand-500">
        <component :is="plusIcon" width="20" height="20" />
        Добавить
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
  uploadIcon: {
    type: Object,
    required: true
  },
  editIcon: {
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
