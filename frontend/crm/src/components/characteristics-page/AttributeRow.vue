<template>
  <tr class="border-t border-gray-100 dark:border-gray-800">
    <td class="py-3 whitespace-nowrap">
      <div class="flex items-center gap-3">
        <div>
          <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
            {{ attribute.name }}
          </p>
        </div>
      </div>
    </td>
    <td class="py-3 whitespace-nowrap">
      <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ attribute.type }}</p>
    </td>
    <td class="py-3 whitespace-nowrap">
      <div class="flex flex-wrap gap-1">
        <span v-for="category in attribute.categories" :key="category.id"
          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
          {{ category.name }}
        </span>
      </div>
    </td>
    <td class="py-3 whitespace-nowrap text-center">
      <button
        class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-success-50 hover:ring-success-300 hover:text-success-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-success-500/15 dark:hover:ring-success-500/50 dark:hover:text-success-500 cursor-pointer p-1 mr-3"
        @click="openEditModal(attribute)"
        :aria-label="`Редактировать атрибут ${attribute.name}`">
        <EditIcon />
      </button>
      <button
        class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-error-50 hover:ring-error-300 hover:text-error-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-error-500/15 dark:hover:ring-error-500/50 dark:hover:text-error-500 cursor-pointer p-1"
        @click="deleteAttribute(attribute.id)"
        :aria-label="`Удалить атрибут ${attribute.name}`">
        <DeleteIcon />
      </button>
    </td>
  </tr>
</template>

<script setup>
import { EditIcon, DeleteIcon } from '@/icons'

defineProps({
  attribute: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['edit', 'delete'])

const openEditModal = (attribute) => {
  emit('edit', attribute)
}

const deleteAttribute = (id) => {
  emit('delete', id)
}
</script>
