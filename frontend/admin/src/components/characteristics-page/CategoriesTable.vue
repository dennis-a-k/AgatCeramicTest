<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 h-full flex flex-col">
    <div class="flex flex-col md:gap-1 gap-3 mb-4 sm:flex-row sm:items-center sm:justify-end">
      <button type="button"
        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-1 rounded-lg px-3 py-3 text-sm font-medium text-white transition"
        @click="openCreateModal" aria-label="Добавить новую категорию">
        <component :is="plusIcon" width="20" height="20" />
        Добавить категорию сантехники
      </button>
    </div>
    <div class="max-w-full overflow-x-auto overflow-y-auto custom-scrollbar flex-grow">
      <table class="min-w-full">
        <thead>
          <tr class="border-t border-gray-100 dark:border-gray-800">
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Название</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Описание</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Порядок</p>
            </th>
            <th class="py-3 text-center">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Действие</p>
            </th>
          </tr>
        </thead>
        <tbody>
          <CategoryRow v-for="category in searchedCategories" :key="category.id" :category="category" @edit="openEditModal"
            @delete="deleteCategory" />
        </tbody>
      </table>
    </div>
  </div>

  <CategoryModal :is-visible="showModal" :is-editing="isEditing" :category="currentCategory" :errors="backendErrors"
    @close="closeModal" @save="saveCategory" />

  <ToastAlert :alerts="alerts" />
  <DeleteConfirmationModal :isVisible="showDeleteModal" :productName="selectedCategory?.name"
    :productArticle="selectedCategory?.slug" @close="showDeleteModal = false" @confirm="confirmDelete" />
</template>

<script setup>
import { ref, inject, computed } from 'vue'
import { useCategoryModal } from '@/composables/useCategoryModal'
import { useCategoryForm } from '@/composables/useCategoryForm'
import { useCategoryAlerts } from '@/composables/useCategoryAlerts'
import { useCategoryValidation } from '@/composables/useCategoryValidation'
import { PlusIcon } from "../../icons"
import CategoryRow from './CategoryRow.vue'
import CategoryModal from './CategoryModal.vue'
import DeleteConfirmationModal from '@/components/common/DeleteConfirmationModal.vue'
import ToastAlert from '@/components/common/ToastAlert.vue'

const plusIcon = PlusIcon
const categoriesComposable = inject('categoriesData')
const { categories, searchQuery, fetchCategories, createCategory, updateCategory, deleteCategory: deleteCategoryApi } = categoriesComposable
const { showModal, isEditing, currentCategory, openCreateModal, openEditModal: originalOpenEditModal, closeModal } = useCategoryModal()

const openEditModal = (category) => {
  backendErrors.value = {} // Очищаем ошибки при открытии модального окна редактирования
  originalOpenEditModal(category)
}
const { form, resetForm, setForm } = useCategoryForm()
const { alerts, showAlert } = useCategoryAlerts()
const { errors, hasErrors, resetErrors } = useCategoryValidation({ name: '', description: '', order: null, parent_id: null })
const backendErrors = ref({})

const showDeleteModal = ref(false)
const selectedCategory = ref(null)

const plumbingCategory = computed(() => categories.value.find(cat => cat.slug === 'santexnika'))
const plumbingId = computed(() => plumbingCategory.value?.id)
const filteredCategories = computed(() => categories.value.filter(cat => cat.parent_id === plumbingId.value))
const searchedCategories = computed(() => {
  const query = searchQuery.value.toLowerCase()
  return filteredCategories.value.filter(cat =>
    cat.name.toLowerCase().includes(query) ||
    cat.slug.toLowerCase().includes(query) ||
    (cat.description && cat.description.toLowerCase().includes(query))
  )
})

const emit = defineEmits(['update:searchQuery'])

const clearSearch = () => {
  searchQuery.value = ''
}

const openDeleteModal = (category) => {
  selectedCategory.value = category
  showDeleteModal.value = true
}

const saveCategory = async (categoryData) => {
  try {
    // Update validation with current data
    Object.assign(errors, { name: '', description: '', order: '', parent_id: '' })
    if (!categoryData.name || !categoryData.name.trim()) {
      errors.name = 'Название категории обязательно.'
    }
    if (hasErrors()) {
      console.log('Validation errors:', errors)
      return
    }

    let result
    if (isEditing.value) {
      result = await updateCategory(currentCategory.value.id, categoryData)
    } else {
      result = await createCategory(categoryData)
    }

    if (result.success) {
      showAlert('success', 'Успешно', isEditing.value ? 'Категория успешно обновлена' : 'Категория успешно создана')
      await fetchCategories()
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
    console.error('Error saving category:', error)
    const errorMessage = error.response?.data?.message || 'Не удалось сохранить категорию'
    showAlert('error', 'Ошибка', errorMessage)
  }
}

const deleteCategory = async (id) => {
  const category = categories.value.find(c => c.id === id)
  if (category) {
    openDeleteModal(category)
  }
}

const confirmDelete = async () => {
  showDeleteModal.value = false
  try {
    await deleteCategoryApi(selectedCategory.value.id)
    await fetchCategories()
    showAlert('success', 'Успешно', 'Категория успешно удалена')
  } catch (error) {
    console.error('Error deleting category:', error)
    showAlert('error', 'Ошибка', 'Не удалось удалить категорию')
  }
}
</script>
