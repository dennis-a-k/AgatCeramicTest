<template>
  <tr class="border-t border-gray-100 dark:border-gray-800">
    <td class="py-3 whitespace-nowrap">
      <div class="flex items-center gap-3">
        <div>
          <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
            {{ color.name }}
          </p>
        </div>
      </div>
    </td>
    <td class="py-3 whitespace-nowrap">
      <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ color.hex }}</p>
    </td>
    <td class="py-3 whitespace-nowrap flex justify-center items-center">
      <div class="w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600"
        :style="{ backgroundColor: color.hex }"
        :aria-label="`Цвет ${color.name} с кодом ${color.hex}`"></div>
    </td>
    <td class="py-3 whitespace-nowrap text-center">
      <button
        class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-success-50 hover:ring-success-300 hover:text-success-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-success-500/15 dark:hover:ring-success-500/50 dark:hover:text-success-500 cursor-pointer p-1 mr-3"
        @click="openEditModal(color)"
        :aria-label="`Редактировать цвет ${color.name}`">
        <EditIcon />
      </button>
      <button
        class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-error-50 hover:ring-error-300 hover:text-error-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-error-500/15 dark:hover:ring-error-500/50 dark:hover:text-error-500 cursor-pointer p-1"
        @click="deleteColor(color.id)"
        :aria-label="`Удалить цвет ${color.name}`">
        <DeleteIcon />
      </button>
    </td>
  </tr>
</template>

<script setup>
import { EditIcon, DeleteIcon } from '@/icons'

defineProps({
  color: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['edit', 'delete'])

const openEditModal = (color) => {
  emit('edit', color)
}

const deleteColor = (id) => {
  emit('delete', id)
}
</script>
