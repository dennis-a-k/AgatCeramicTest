<template>
  <div class="border-t border-gray-200 py-4 dark:border-gray-800">
    <div class="flex items-center justify-between">
      <button @click="previousPage" :disabled="currentPage === 1"
        class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
        :aria-label="'Предыдущая страница'">
        <ChevronLeftIcon />
      </button>
      <span class="block text-sm font-medium text-gray-700 sm:hidden dark:text-gray-400" aria-live="polite">
        {{ currentPage }} из {{ totalPages }}
      </span>
      <ul class="hidden items-center gap-0.5 sm:flex" role="navigation" aria-label="Пагинация">
        <li v-for="page in totalPages" :key="page">
          <button @click="goToPage(page)" :class="[
            'text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium',
            page === currentPage
              ? 'bg-brand-500/[0.08] text-brand-500 dark:text-brand-500'
              : 'text-gray-700 dark:text-gray-400'
          ]"
          :aria-label="`Перейти на страницу ${page}`"
          :aria-current="page === currentPage ? 'page' : null">
            {{ page }}
          </button>
        </li>
      </ul>
      <button @click="nextPage" :disabled="currentPage === totalPages"
        class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
        :aria-label="'Следующая страница'">
        <ChevronRightIcon />
      </button>
    </div>
  </div>
</template>

<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@/icons'

const props = defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  totalPages: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['page-change'])

const goToPage = (page) => {
  if (page >= 1 && page <= props.totalPages) {
    emit('page-change', page)
  }
}

const previousPage = () => {
  if (props.currentPage > 1) {
    emit('page-change', props.currentPage - 1)
  }
}

const nextPage = () => {
  if (props.currentPage < props.totalPages) {
    emit('page-change', props.currentPage + 1)
  }
}
</script>
