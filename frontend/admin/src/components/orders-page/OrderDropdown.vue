<template>
  <div class="relative" ref="filterRef" @click="closeFilter">
    <button
      class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition"
      @click.stop="toggleFilter" type="button">
      Сменить статус
    </button>
    <div v-show="showFilter" @click.stop
      class="absolute right-0 z-10 mt-2 w-35 rounded-lg border border-gray-200 bg-white p-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
      <button @click="handleStatusChange('pending')"
        class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded">
        Новый
      </button>
      <button @click="handleStatusChange('processing')"
        class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded">
        Выполнение
      </button>
      <button @click="handleStatusChange('shipped')"
        class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded">
        Отправлен
      </button>
      <button @click="handleStatusChange('cancelled')"
        class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded">
        Отменён
      </button>
      <button @click="handleStatusChange('return')"
        class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 rounded">
        Возврат
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useClickOutside } from '../../composables/useClickOutside'

const showFilter = ref(false)
const filterRef = ref(null)

const emit = defineEmits(['updateStatus'])

const toggleFilter = () => {
  showFilter.value = !showFilter.value
}

const closeFilter = () => {
  showFilter.value = false
}

const handleStatusChange = (status) => {
  emit('updateStatus', status)
  closeFilter()
}

useClickOutside(filterRef, closeFilter)
</script>
