<template>
  <tr class="border-t border-gray-100 dark:border-gray-800">
    <td class="py-3 whitespace-nowrap">
      <div class="flex items-center gap-3">
        <div>
          <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
            {{ category.name }}
          </p>
        </div>
      </div>
    </td>
    <td class="py-3 whitespace-nowrap">
      <div v-if="category.description"
        class="flex items-center justify-center w-6 h-6 bg-success-50 dark:bg-success-500/15 rounded-full">
        <CheckCircleIcon class="w-4 h-4 text-success-600 dark:text-success-500" />
      </div>
      <div v-else class="flex items-center justify-center w-6 h-6 bg-error-50 dark:bg-error-500/15 rounded-full">
        <ErrorIcon class="w-4 h-4 text-error-600 dark:text-error-500" />
      </div>
    </td>
    <td class="py-3 whitespace-nowrap">
      <p class="text-gray-500 text-theme-sm dark:text-gray-400">
        {{ category.order || 'Не указан' }}
      </p>
    </td>
    <td class="py-3 whitespace-nowrap text-center">
      <button
        class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-success-50 hover:ring-success-300 hover:text-success-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-success-500/15 dark:hover:ring-success-500/50 dark:hover:text-success-500 cursor-pointer p-1 mr-3"
        @click="openEditModal(category)" :aria-label="`Редактировать категорию ${category.name}`">
        <EditIcon />
      </button>
      <button
        class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-error-50 hover:ring-error-300 hover:text-error-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-error-500/15 dark:hover:ring-error-500/50 dark:hover:text-error-500 cursor-pointer p-1"
        @click="deleteCategory(category.id)" :aria-label="`Удалить категорию ${category.name}`">
        <DeleteIcon />
      </button>
    </td>
  </tr>
</template>

<script setup>
import { EditIcon, DeleteIcon, CheckCircleIcon, ErrorIcon } from '@/icons'

defineProps({
  category: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['edit', 'delete'])

const openEditModal = (category) => {
  emit('edit', category)
}

const deleteCategory = (id) => {
  emit('delete', id)
}
</script>
