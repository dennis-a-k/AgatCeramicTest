import { ref } from 'vue'

export function useBrandForm() {
  const form = ref({
    name: '',
    country: '',
    description: '',
    is_active: true,
    image: ''
  })

  const resetForm = () => {
    form.value = {
      name: '',
      country: '',
      description: '',
      is_active: true,
      image: ''
    }
  }

  const setForm = (brandData: any) => {
    form.value = { ...brandData }
  }

  return {
    form,
    resetForm,
    setForm
  }
}
