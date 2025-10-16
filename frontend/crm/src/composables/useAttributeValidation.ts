import { reactive, watch, type Ref } from 'vue'

interface Attribute {
  name: string
  type: string
}

interface Errors {
  name?: string
  type?: string
}

export function useAttributeValidation(attribute: Ref<Attribute>) {
  const errors = reactive<Errors>({})

  const validateName = () => {
    // Don't validate if all fields are empty (initial state)
    if (shouldSkipValidation()) {
      errors.name = ''
      return
    }

    if (!attribute.value || !attribute.value.name || typeof attribute.value.name !== 'string' || !attribute.value.name.trim()) {
      errors.name = 'Название атрибута обязательно.'
    } else if (attribute.value.name.length > 255) {
      errors.name = 'Название атрибута не должно превышать 255 символов.'
    } else {
      errors.name = ''
    }
  }

  const validateType = () => {
    // Don't validate if all fields are empty (initial state)
    if (shouldSkipValidation()) {
      errors.type = ''
      return
    }

    if (!attribute.value || !attribute.value.type || typeof attribute.value.type !== 'string' || !attribute.value.type.trim()) {
      errors.type = 'Тип атрибута обязателен.'
    } else {
      errors.type = ''
    }
  }

  // Don't validate empty fields initially
  const shouldSkipValidation = () => {
    const isEmpty = !attribute.value || (!attribute.value.name?.trim() && !attribute.value.type?.trim())
    return isEmpty
  }

  // Initialize without errors
  const initializeValidation = () => {
    resetErrors()
    isInitialized = false // Reset initialization flag
  }


  const validateAll = () => {
    if (!shouldSkipValidation()) {
      validateName()
      validateType()
    }
  }

  const hasErrors = () => {
    return Object.values(errors).some(error => error)
  }

  // Watchers - validate on change
  let isInitialized = false

  watch(() => attribute.value?.name, (newVal, oldVal) => {
    // Skip validation during initialization
    if (!isInitialized) {
      isInitialized = true
      return
    }
    // Only validate if the value actually changed by user input
    if (newVal !== oldVal && oldVal !== undefined) {
      validateName()
    }
  })
  watch(() => attribute.value?.type, (newVal, oldVal) => {
    // Skip validation during initialization
    if (!isInitialized) return
    // Only validate if the value actually changed by user input
    if (newVal !== oldVal && oldVal !== undefined) {
      validateType()
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
    resetErrors,
    initializeValidation
  }
}
