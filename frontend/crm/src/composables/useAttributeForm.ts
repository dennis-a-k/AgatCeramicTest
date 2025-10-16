import { ref } from 'vue'

interface AttributeForm {
  name: string
  type: string
}

export function useAttributeForm(initialForm: AttributeForm = { name: '', type: '' }) {
  const form = ref<AttributeForm>({ ...initialForm })

  const resetForm = () => {
    form.value = { name: '', type: '' }
  }

  const setForm = (attribute: AttributeForm) => {
    form.value = { ...attribute }
  }

  return {
    form,
    resetForm,
    setForm
  }
}
