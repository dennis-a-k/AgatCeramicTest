import { ref } from 'vue'

interface ColorForm {
  name: string
  hex: string
}

export function useColorForm(initialForm: ColorForm = { name: '', hex: '#000000' }) {
  const form = ref<ColorForm>({ ...initialForm })

  const resetForm = () => {
    form.value = { name: '', hex: '#000000' }
  }

  const setForm = (color: ColorForm) => {
    form.value = { ...color }
  }

  return {
    form,
    resetForm,
    setForm
  }
}
