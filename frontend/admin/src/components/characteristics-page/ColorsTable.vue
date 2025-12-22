<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 h-full flex flex-col">
    <div class="flex flex-col md:gap-1 gap-3 mb-4 sm:flex-row sm:items-center sm:justify-between">
      <div class="relative sm:w-auto sm:min-w-[240px]">
        <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
          <component :is="searchIcon" />
        </span>
        <label for="searchQuery"></label>
        <input id="searchQuery" type="text" placeholder="Поиск цвета..." v-model="searchQuery"
          class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-10 pl-10 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
        <button v-if="searchQuery" @click="clearSearch"
          class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl leading-none cursor-pointer bg-white dark:bg-gray-900 px-1 z-10">
          ✕
        </button>
      </div>
      <button type="button"
        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-1 rounded-lg px-3 py-3 text-sm font-medium text-white transition"
        @click="openCreateModal" aria-label="Добавить новый цвет">
        <component :is="plusIcon" width="20" height="20" />
        Добавить цвет
      </button>
    </div>
    <div class="max-w-full overflow-x-auto overflow-y-auto custom-scrollbar flex-grow">
      <table class="min-w-full">
        <thead>
          <tr class="border-t border-gray-100 dark:border-gray-800">
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Цвет</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">HEX код </p>
            </th>
            <th class="py-3 text-center">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Предпросмотр</p>
            </th>
            <th class="py-3 text-center">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Действие</p>
            </th>
          </tr>
        </thead>
        <tbody>
          <ColorRow v-for="color in colors" :key="color.id" :color="color" @edit="openEditModal"
            @delete="deleteColor" />
        </tbody>
      </table>
    </div>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="goToPage" />
  </div>

  <ColorModal :is-visible="showModal" :is-editing="isEditing" :color="currentColor" :errors="backendErrors"
    @close="closeModal" @save="saveColor" />

  <ToastAlert :alerts="alerts" />
  <DeleteConfirmationModal :isVisible="showDeleteModal" :productName="selectedColor?.name"
    :productArticle="selectedColor?.hex" @close="showDeleteModal = false" @confirm="confirmDelete" />
</template>

<script setup>
import { ref, inject } from 'vue'
import { useColorModal } from '@/composables/useColorModal'
import { useColorForm } from '@/composables/useColorForm'
import { useColorAlerts } from '@/composables/useColorAlerts'
import { useColorValidation } from '@/composables/useColorValidation'
import { PlusIcon, SearchIcon } from "../../icons"
import ColorRow from './ColorRow.vue'
import ColorModal from './ColorModal.vue'
import Pagination from '@/components/ui/Pagination.vue'
import DeleteConfirmationModal from '@/components/common/DeleteConfirmationModal.vue'
import ToastAlert from '@/components/common/ToastAlert.vue'

const plusIcon = PlusIcon
const searchIcon = SearchIcon
const colorsComposable = inject('colorsData')
const { colors, searchQuery, currentPage, totalPages, fetchColors, createColor, updateColor, deleteColor: deleteColorApi } = colorsComposable
const { showModal, isEditing, currentColor, openCreateModal, openEditModal: originalOpenEditModal, closeModal } = useColorModal()

const openEditModal = (color) => {
  backendErrors.value = {} // Очищаем ошибки при открытии модального окна редактирования
  originalOpenEditModal(color)
}
const { form } = useColorForm()
const { alerts, showAlert } = useColorAlerts()
const { validateAll, hasErrors, resetErrors } = useColorValidation(form)
const backendErrors = ref({})

const showDeleteModal = ref(false)
const selectedColor = ref(null)

const emit = defineEmits(['update:searchQuery'])

const clearSearch = () => {
  searchQuery.value = ''
}

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    fetchColors(page)
  }
}

const openDeleteModal = (color) => {
  selectedColor.value = color
  showDeleteModal.value = true
}

const saveColor = async (colorData) => {
  try {
    validateAll()
    if (hasErrors()) {
      return
    }

    let result
    if (isEditing.value) {
      result = await updateColor(currentColor.value.id, colorData)
    } else {
      result = await createColor(colorData)
    }

    if (result.success) {
      showAlert('success', 'Успешно', isEditing.value ? 'Цвет успешно обновлён' : 'Цвет успешно создан')
      await fetchColors()
      closeModal()
      resetErrors()
    } else {
      // Валидационные ошибки
      const processedErrors = {}
      if (result.errors) {
        for (const [field, messages] of Object.entries(result.errors)) {
          if (Array.isArray(messages)) {
            processedErrors[field] = messages[0] // Берем первое сообщение
          } else {
            processedErrors[field] = messages
          }
        }
      }
      backendErrors.value = processedErrors
      const errorMessages = Object.values(processedErrors)
      if (errorMessages.length > 0) {
        errorMessages.forEach(message => {
          showAlert('error', 'Ошибка валидации', message)
        })
      } else {
        showAlert('error', 'Ошибка валидации', result.message || 'Ошибка валидации')
      }
    }
  } catch (error) {
    const errorMessage = error.response?.data?.message || 'Не удалось сохранить цвет'
    showAlert('error', 'Ошибка', errorMessage)
  }
}

const deleteColor = async (id) => {
  const color = colors.value.find(c => c.id === id)
  if (color) {
    openDeleteModal(color)
  }
}

const confirmDelete = async () => {
  showDeleteModal.value = false
  try {
    await deleteColorApi(selectedColor.value.id)
    await fetchColors()
    showAlert('success', 'Успешно', 'Цвет успешно удалён')
  } catch (error) {
    console.error('Error deleting color:', error)
    showAlert('error', 'Ошибка', 'Не удалось удалить цвет')
  }
}
</script>
