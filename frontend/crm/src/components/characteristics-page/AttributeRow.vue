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
      <span :class="badgeClass" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
        {{ typeLabel }}
      </span>
    </td>
    <td class="py-3 whitespace-nowrap text-center">
      <span class="text-sm text-gray-700 dark:text-gray-300">
        {{ attribute.unit || '—' }}
      </span>
    </td>
    <td class="py-3 whitespace-nowrap">
      <div class="flex flex-wrap gap-1">
        <span v-for="category in attribute.categories" :key="category.id"
          class="inline-flex items-center px-2.5 py-0.5 justify-center gap-1 rounded-full font-medium capitalize text-xs bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
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
import { computed } from 'vue'
import { EditIcon, DeleteIcon } from '@/icons'

const props = defineProps({
  attribute: {
    type: Object,
    required: true
  }
})

const typeLabel = computed(() => {
  const typeMap = {
    text: 'Текст',
    string: 'Строка',
    number: 'Число',
    boolean: 'Да/Нет',
    select: 'Выбор'
  }
  return typeMap[props.attribute.type] || props.attribute.type
})

const badgeClass = computed(() => {
  const classMap = {
    text: 'inline-flex items-center px-2.5 py-0.5 justify-center gap-1 rounded-full capitalize bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500',
    boolean: 'inline-flex items-center px-2.5 py-0.5 justify-center gap-1 rounded-full capitalize bg-brand-50 text-brand-500 dark:bg-brand-500/15 dark:text-brand-400',
    number: 'inline-flex items-center px-2.5 py-0.5 justify-center gap-1 rounded-full capitalize bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
    string: 'inline-flex items-center px-2.5 py-0.5 justify-center gap-1 rounded-full capitalize bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
    select: 'inline-flex items-center px-2.5 py-0.5 justify-center gap-1 rounded-full capitalize bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80'
  }
  return classMap[props.attribute.type] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
})

const emit = defineEmits(['edit', 'delete'])

const openEditModal = (attribute) => {
  emit('edit', attribute)
}

const deleteAttribute = (id) => {
  emit('delete', id)
}
</script>
