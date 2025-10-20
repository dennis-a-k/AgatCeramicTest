<template>
  <tr class="border-t border-gray-100 dark:border-gray-800">
    <td class="py-3 whitespace-nowrap">
      <div class="flex items-center gap-3">
        <div class="h-[32px] w-auto overflow-hidden rounded-md">
          <img v-if="brand.image" :src="getImageUrl(brand.image)" :alt="brand.name" class="w-[70px] h-full object-contain" />
          <div v-else class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
            <span class="text-gray-500 dark:text-gray-400 text-sm">Нет фото</span>
          </div>
        </div>
        <div>
          <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
            {{ brand.name }}
          </p>
        </div>
      </div>
    </td>
    <td class="py-3 whitespace-nowrap">
      <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ brand.country || 'Не указано' }}</p>
    </td>
    <td class="py-3 whitespace-nowrap">
        <div v-if="brand.description" class="flex items-center justify-center w-6 h-6 bg-success-50 dark:bg-success-500/15 rounded-full">
          <CheckCircleIcon class="w-4 h-4 text-success-600 dark:text-success-500" />
        </div>
        <div v-else class="flex items-center justify-center w-6 h-6 bg-error-50 dark:bg-error-500/15 rounded-full">
          <ErrorIcon class="w-4 h-4 text-error-600 dark:text-error-500" />
        </div>
    </td>
    <td class="py-3 whitespace-nowrap">
      <span
        :class="{
          'rounded-full px-2 py-0.5 text-theme-xs font-medium': true,
          'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500':
            brand.is_active,
          'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500':
            !brand.is_active,
        }"
      >
        {{ brand.is_active ? 'Активен' : 'Неактивен' }}
      </span>
    </td>
    <td class="py-3 whitespace-nowrap text-center">
      <button
        class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-success-50 hover:ring-success-300 hover:text-success-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-success-500/15 dark:hover:ring-success-500/50 dark:hover:text-success-500 cursor-pointer p-1 mr-3"
        @click="openEditModal(brand)"
        :aria-label="`Редактировать бренд ${brand.name}`">
        <EditIcon />
      </button>
      <button
        class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-error-50 hover:ring-error-300 hover:text-error-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-error-500/15 dark:hover:ring-error-500/50 dark:hover:text-error-500 cursor-pointer p-1"
        @click="deleteBrand(brand.id)"
        :aria-label="`Удалить бренд ${brand.name}`">
        <DeleteIcon />
      </button>
    </td>
  </tr>
</template>

<script setup>
import { EditIcon, DeleteIcon, CheckCircleIcon, ErrorIcon } from '@/icons'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

defineProps({
  brand: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['edit', 'delete'])

const getImageUrl = (imagePath) => {
  if (imagePath.startsWith('http')) {
    return imagePath
  }
  return `${API_BASE_URL}/storage/${imagePath}`
}

const openEditModal = (brand) => {
  emit('edit', brand)
}

const deleteBrand = (id) => {
  emit('delete', id)
}
</script>
