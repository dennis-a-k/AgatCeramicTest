import { ref } from 'vue'

interface Category {
  id: number
  name: string
  slug: string
  description?: string
  order?: number
  parent_id?: number
  is_plumbing?: boolean
}

export function useCategoryModal() {
  const showModal = ref(false)
  const isEditing = ref(false)
  const currentCategory = ref<Category | null>(null)

  const openCreateModal = () => {
    isEditing.value = false
    currentCategory.value = null
    showModal.value = true
  }

  const openEditModal = (category: Category) => {
    isEditing.value = true
    currentCategory.value = category
    showModal.value = true
  }

  const closeModal = () => {
    showModal.value = false
    isEditing.value = false
    currentCategory.value = null
  }

  return {
    showModal,
    isEditing,
    currentCategory,
    openCreateModal,
    openEditModal,
    closeModal
  }
}
