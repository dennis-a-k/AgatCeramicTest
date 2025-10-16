import { ref } from 'vue'

interface Attribute {
  id: number
  name: string
  type: string
}

export function useAttributeModal() {
  const showModal = ref(false)
  const isEditing = ref(false)
  const currentAttribute = ref<Attribute | null>(null)

  const openCreateModal = () => {
    isEditing.value = false
    currentAttribute.value = null
    showModal.value = true
  }

  const openEditModal = (attribute: Attribute) => {
    isEditing.value = true
    currentAttribute.value = attribute
    showModal.value = true
  }

  const closeModal = () => {
    showModal.value = false
    isEditing.value = false
    currentAttribute.value = null
  }

  return {
    showModal,
    isEditing,
    currentAttribute,
    openCreateModal,
    openEditModal,
    closeModal
  }
}
