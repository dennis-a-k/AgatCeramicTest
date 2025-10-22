import { ref } from 'vue'

interface Color {
  id: number
  name: string
  hex: string
}

export function useColorModal() {
  const showModal = ref(false)
  const isEditing = ref(false)
  const currentColor = ref<Color | null>(null)

  const openCreateModal = () => {
    isEditing.value = false
    currentColor.value = null
    showModal.value = true
  }

  const openEditModal = (color: Color) => {
    isEditing.value = true
    currentColor.value = color
    showModal.value = true
  }

  const closeModal = () => {
    showModal.value = false
    isEditing.value = false
    currentColor.value = null
  }

  return {
    showModal,
    isEditing,
    currentColor,
    openCreateModal,
    openEditModal,
    closeModal
  }
}
