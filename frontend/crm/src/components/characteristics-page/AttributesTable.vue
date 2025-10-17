<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
    <div class="flex flex-col md:gap-1 gap-3 mb-4 sm:flex-row sm:items-center sm:justify-between">
      <div class="relative sm:w-auto sm:min-w-[240px]">
        <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
          <component :is="searchIcon" />
        </span>
        <label for="searchAttribute"></label>
        <input id="searchAttribute" type="text" placeholder="Поиск атрибута..." v-model="searchQuery"
          class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-10 pl-10 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
        <button v-if="searchQuery" @click="clearSearch"
          class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl leading-none cursor-pointer bg-white dark:bg-gray-900 px-1 z-10">
          ✕
        </button>
      </div>
      <button type="button"
        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-1 rounded-lg px-3 py-3 text-sm font-medium text-white transition"
        @click="openCreateModal" aria-label="Добавить новый атрибут">
        <component :is="plusIcon" width="20" height="20" />
        Добавить атрибут
      </button>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <table class="min-w-full">
        <thead>
           <tr class="border-t border-gray-100 dark:border-gray-800">
             <th class="py-3 text-left w-1/12">
               <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Атрибут</p>
             </th>
             <th class="py-3 text-left w-1/12">
               <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Тип</p>
             </th>
             <th class="py-3 text-center w-3/12">
               <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Категории</p>
             </th>
             <th class="py-3 text-center w-1/12">
               <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Действие</p>
             </th>
           </tr>
         </thead>
        <tbody>
          <AttributeRow v-for="attribute in attributes" :key="attribute.id" :attribute="attribute"
            @edit="openEditModal" @delete="deleteAttribute" />
        </tbody>
      </table>
    </div>
    <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="goToPage" />
  </div>

  <AttributeModal :is-visible="showModal" :is-editing="isEditing" :attribute="currentAttribute" :errors="backendErrors"
    @close="closeModal" @save="saveAttribute" />

  <ToastAlert :alerts="alerts" />
  <DeleteConfirmationModal :isVisible="showDeleteModal" :productName="selectedAttribute?.name"
    :productArticle="selectedAttribute?.type" @close="showDeleteModal = false" @confirm="confirmDelete" />
</template>

<script setup>
import { onMounted, ref, inject } from 'vue'
import { useAttributeModal } from '@/composables/useAttributeModal'
import { useAttributeForm } from '@/composables/useAttributeForm'
import { useAttributeAlerts } from '@/composables/useAttributeAlerts'
import { useAttributeValidation } from '@/composables/useAttributeValidation'
import { PlusIcon, SearchIcon } from "../../icons"
import AttributeRow from './AttributeRow.vue'
import AttributeModal from './AttributeModal.vue'
import Pagination from '@/components/ui/Pagination.vue'
import DeleteConfirmationModal from '@/components/common/DeleteConfirmationModal.vue'
import ToastAlert from '@/components/common/ToastAlert.vue'

const plusIcon = PlusIcon
const searchIcon = SearchIcon
const attributesComposable = inject('attributesData')
const { attributes, searchQuery, currentPage, totalPages, fetchAttributes, createAttribute, updateAttribute, deleteAttribute: deleteAttributeApi } = attributesComposable
const { showModal, isEditing, currentAttribute, openCreateModal, openEditModal: originalOpenEditModal, closeModal } = useAttributeModal()

const openEditModal = (attribute) => {
  backendErrors.value = {} // Очищаем ошибки при открытии модального окна редактирования
  originalOpenEditModal(attribute)
}
const { form, resetForm, setForm } = useAttributeForm()
const { alerts, showAlert } = useAttributeAlerts()
const { errors, validateAll, hasErrors, resetErrors, initializeValidation } = useAttributeValidation(form)
const backendErrors = ref({})

const showDeleteModal = ref(false)
const selectedAttribute = ref(null)

const emit = defineEmits(['update:searchQuery'])

const clearSearch = () => {
  searchQuery.value = ''
}

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    fetchAttributes(page)
  }
}

const openDeleteModal = (attribute) => {
  selectedAttribute.value = attribute
  showDeleteModal.value = true
}

const saveAttribute = async (attributeData) => {
  try {
    validateAll()
    if (hasErrors()) {
      return
    }

    let result
    if (isEditing.value) {
      result = await updateAttribute(currentAttribute.value.id, attributeData)
    } else {
      result = await createAttribute(attributeData)
    }

    if (result.success) {
      showAlert('success', 'Успешно', isEditing.value ? 'Атрибут успешно обновлён' : 'Атрибут успешно создан')
      await fetchAttributes()
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
    console.error('Error saving attribute:', error)
    const errorMessage = error.response?.data?.message || 'Не удалось сохранить атрибут'
    showAlert('error', 'Ошибка', errorMessage)
  }
}

const deleteAttribute = async (id) => {
  const attribute = attributes.value.find(a => a.id === id)
  if (attribute) {
    openDeleteModal(attribute)
  }
}

const confirmDelete = async () => {
  showDeleteModal.value = false
  try {
    await deleteAttributeApi(selectedAttribute.value.id)
    await fetchAttributes()
    showAlert('success', 'Успешно', 'Атрибут успешно удалён')
  } catch (error) {
    console.error('Error deleting attribute:', error)
    showAlert('error', 'Ошибка', 'Не удалось удалить атрибут')
  }
}

// Убираем onMounted, так как данные уже загружены в родительском компоненте
</script>
