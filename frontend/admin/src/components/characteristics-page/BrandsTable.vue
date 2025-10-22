<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
    <div class="flex flex-col md:gap-1 gap-3 mb-4 sm:flex-row sm:items-center sm:justify-between">
      <div class="relative sm:w-auto sm:min-w-[240px]">
        <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
          <component :is="searchIcon" />
        </span>
        <label for="searchBrand"></label>
        <input id="searchBrand" type="text" placeholder="Поиск бренда..." v-model="searchQuery"
          class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-10 pl-10 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
        <button v-if="searchQuery" @click="clearSearch"
          class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl leading-none cursor-pointer bg-white dark:bg-gray-900 px-1 z-10">
          ✕
        </button>
      </div>
      <button type="button"
        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-1 rounded-lg px-3 py-3 text-sm font-medium text-white transition"
        @click="openCreateModal" aria-label="Добавить новый бренд">
        <component :is="plusIcon" width="20" height="20" />
        Добавить бренд
      </button>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <table class="min-w-full">
        <thead>
          <tr class="border-t border-gray-100 dark:border-gray-800">
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Бренд</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Страна</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Описание</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Статус</p>
            </th>
            <th class="py-3 text-center">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Действие</p>
            </th>
          </tr>
        </thead>
        <tbody>
          <BrandRow v-for="brand in brands" :key="brand.id" :brand="brand" @edit="openEditModal"
            @delete="deleteBrand" />
        </tbody>
      </table>
    </div>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="goToPage" />
  </div>

  <BrandModal :is-visible="showModal" :is-editing="isEditing" :brand="currentBrand" :errors="backendErrors"
    @close="closeModal" @save="saveBrand" />

  <ToastAlert :alerts="alerts" />
  <DeleteConfirmationModal :isVisible="showDeleteModal" :productName="selectedBrand?.name"
    :productArticle="selectedBrand?.slug" @close="showDeleteModal = false" @confirm="confirmDelete" />
</template>

<script setup>
import { ref, inject } from 'vue'
import { useBrandModal } from '@/composables/useBrandModal'
import { useBrandForm } from '@/composables/useBrandForm'
import { useBrandAlerts } from '@/composables/useBrandAlerts'
import { useBrandValidation } from '@/composables/useBrandValidation'
import { PlusIcon, SearchIcon } from "../../icons"
import BrandRow from './BrandRow.vue'
import BrandModal from './BrandModal.vue'
import Pagination from '@/components/ui/Pagination.vue'
import DeleteConfirmationModal from '@/components/common/DeleteConfirmationModal.vue'
import ToastAlert from '@/components/common/ToastAlert.vue'

const plusIcon = PlusIcon
const searchIcon = SearchIcon
const brandsComposable = inject('brandsData')
const { brands, searchQuery, currentPage, totalPages, fetchBrands, createBrand, updateBrand, deleteBrand: deleteBrandApi } = brandsComposable
const { showModal, isEditing, currentBrand, openCreateModal, openEditModal: originalOpenEditModal, closeModal } = useBrandModal()

const openEditModal = (brand) => {
  backendErrors.value = {} // Очищаем ошибки при открытии модального окна редактирования
  initializeValidation() // Инициализируем валидацию
  originalOpenEditModal(brand)
}
const { form } = useBrandForm()
const { alerts, showAlert } = useBrandAlerts()
const { validateAll, hasErrors, resetErrors, initializeValidation } = useBrandValidation(form)
const backendErrors = ref({})

const showDeleteModal = ref(false)
const selectedBrand = ref(null)

const emit = defineEmits(['update:searchQuery'])

const clearSearch = () => {
  searchQuery.value = ''
}

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    fetchBrands(page)
  }
}

const openDeleteModal = (brand) => {
  selectedBrand.value = brand
  showDeleteModal.value = true
}

const saveBrand = async (brandData, newFile) => {
  try {
    validateAll()
    if (hasErrors()) {
      return
    }

    let result
    if (isEditing.value) {
      result = await updateBrand(currentBrand.value.id, brandData, newFile)
    } else {
      result = await createBrand(brandData, newFile)
    }

    if (result.success) {
      showAlert('success', 'Успешно', isEditing.value ? 'Бренд успешно обновлён' : 'Бренд успешно создан')
      await fetchBrands()
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
    console.error('Error saving brand:', error)
    const errorMessage = error.response?.data?.message || 'Не удалось сохранить бренд'
    showAlert('error', 'Ошибка', errorMessage)
  }
}

const deleteBrand = async (id) => {
  const brand = brands.value.find(b => b.id === id)
  if (brand) {
    openDeleteModal(brand)
  }
}

const confirmDelete = async () => {
  showDeleteModal.value = false
  try {
    await deleteBrandApi(selectedBrand.value.id)
    await fetchBrands()
    showAlert('success', 'Успешно', 'Бренд успешно удалён')
  } catch (error) {
    console.error('Error deleting brand:', error)
    showAlert('error', 'Ошибка', 'Не удалось удалить бренд')
  }
}
</script>
