import { ref } from 'vue'

interface CategoryForm {
  name: string
  description: string
  order: number | null
  parent_id: number | null
  is_plumbing: boolean
}

export function useCategoryForm(initialForm: Partial<CategoryForm> = {}) {
  const form = ref<CategoryForm>({
    name: '',
    description: '',
    order: null,
    parent_id: null,
    is_plumbing: false,
    ...initialForm
  })

  const resetForm = () => {
    form.value = {
      name: '',
      description: '',
      order: null,
      parent_id: null,
      is_plumbing: false
    }
  }

  const setForm = (category: Partial<CategoryForm>) => {
    form.value = { ...form.value, ...category }
  }

  return {
    form,
    resetForm,
    setForm
  }
}
