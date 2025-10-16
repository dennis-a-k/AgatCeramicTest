import { ref } from 'vue'

export function useBrandModal() {
  const showModal = ref(false)
  const isEditing = ref(false)
  const currentBrand = ref({
    id: null,
    name: '',
    country: '',
    description: '',
    is_active: true,
    image: ''
  })

  const openCreateModal = () => {
    isEditing.value = false
    currentBrand.value = {
      id: null,
      name: '',
      country: '',
      description: '',
      is_active: true,
      image: ''
    }
    showModal.value = true
  }

  const openEditModal = (brand: any) => {
    isEditing.value = true
    currentBrand.value = { ...brand }
    showModal.value = true
  }

  const closeModal = () => {
    showModal.value = false
    isEditing.value = false
    currentBrand.value = {
      id: null,
      name: '',
      country: '',
      description: '',
      is_active: true,
      image: ''
    }
  }

  return {
    showModal,
    isEditing,
    currentBrand,
    openCreateModal,
    openEditModal,
    closeModal
  }
}
