import { reactive, watch, type Ref } from 'vue'

interface Category {
  name: string
  description: string
  order: number | null
  parent_id: number | null
}

interface Errors {
  name?: string
  description?: string
  order?: string
  parent_id?: string
}

export function useCategoryValidation(category: Ref<Category>) {
  const errors = reactive<Errors>({})

  const validateName = () => {
    if (!category.value || !category.value.name || typeof category.value.name !== 'string' || !category.value.name.trim()) {
      errors.name = 'Название категории обязательно.'
    } else if (category.value.name.length > 255) {
      errors.name = 'Название категории не должно превышать 255 символов.'
    } else {
      errors.name = ''
    }
  }

  // Slug validation removed - slug is generated from name

  const validateDescription = () => {
    if (category.value && category.value.description && category.value.description.length > 1000) {
      errors.description = 'Описание не должно превышать 1000 символов.'
    } else {
      errors.description = ''
    }
  }

  const validateOrder = () => {
    if (category.value && category.value.order !== null && (isNaN(category.value.order) || category.value.order < 0)) {
      errors.order = 'Порядок должен быть положительным числом.'
    } else {
      errors.order = ''
    }
  }

  const validateParentId = () => {
    // parent_id can be null or a valid number
    if (category.value && category.value.parent_id !== null && (isNaN(category.value.parent_id) || category.value.parent_id <= 0)) {
      errors.parent_id = 'Неверный ID родительской категории.'
    } else {
      errors.parent_id = ''
    }
  }

  const validateAll = () => {
    validateName()
    validateDescription()
    validateOrder()
    validateParentId()
  }

  const hasErrors = () => {
    return Object.values(errors).some(error => error)
  }

  // Watchers - validate on change
  let isInitialized = false

  watch(() => category.value?.name, (newVal, oldVal) => {
    if (!isInitialized) {
      isInitialized = true
      return
    }
    if (newVal !== oldVal && oldVal !== undefined) {
      validateName()
    }
  })

  // Slug watcher removed - slug is generated from name

  watch(() => category.value?.description, (newVal, oldVal) => {
    if (!isInitialized) return
    if (newVal !== oldVal && oldVal !== undefined) {
      validateDescription()
    }
  })

  watch(() => category.value?.order, (newVal, oldVal) => {
    if (!isInitialized) return
    if (newVal !== oldVal && oldVal !== undefined) {
      validateOrder()
    }
  })

  watch(() => category.value?.parent_id, (newVal, oldVal) => {
    if (!isInitialized) return
    if (newVal !== oldVal && oldVal !== undefined) {
      validateParentId()
    }
  })

  const resetErrors = () => {
    Object.keys(errors).forEach(key => {
      errors[key as keyof Errors] = ''
    })
  }

  return {
    errors,
    validateAll,
    hasErrors,
    resetErrors
  }
}
