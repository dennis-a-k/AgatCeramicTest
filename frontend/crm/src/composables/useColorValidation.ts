import { reactive, watch, type Ref } from 'vue'

interface Color {
  name: string
  hex: string
}

interface Errors {
  name?: string
  hex?: string
}

export function useColorValidation(color: Ref<Color>) {
  const errors = reactive<Errors>({})

  const validateName = () => {
    // Don't validate if all fields are empty (initial state)
    if (shouldSkipValidation()) {
      errors.name = ''
      return
    }

    if (!color.value || !color.value.name || typeof color.value.name !== 'string' || !color.value.name.trim()) {
      errors.name = 'Название цвета обязательно.'
    } else if (color.value.name.length > 255) {
      errors.name = 'Название цвета не должно превышать 255 символов.'
    } else {
      errors.name = ''
    }
  }

  const validateHex = () => {
    // Don't validate if all fields are empty (initial state)
    if (shouldSkipValidation()) {
      errors.hex = ''
      return
    }

    if (!color.value || !color.value.hex || typeof color.value.hex !== 'string' || !color.value.hex.trim()) {
      errors.hex = 'HEX код обязателен.'
    } else if (!/^#[a-fA-F0-9]{6}$/.test(color.value.hex)) {
      errors.hex = 'HEX код должен быть в формате #RRGGBB.'
    } else {
      errors.hex = ''
    }
  }

  // Don't validate empty fields initially
  const shouldSkipValidation = () => {
    const isEmpty = !color.value || (!color.value.name?.trim() && (color.value.hex === '#000000' || !color.value.hex?.trim()))
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
      validateHex()
    }
  }

  const hasErrors = () => {
    return Object.values(errors).some(error => error)
  }

  // Watchers - validate on change
  let isInitialized = false

  watch(() => color.value?.name, (newVal, oldVal) => {
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
  watch(() => color.value?.hex, (newVal, oldVal) => {
    // Skip validation during initialization
    if (!isInitialized) return
    // Only validate if the value actually changed by user input
    if (newVal !== oldVal && oldVal !== undefined) {
      validateHex()
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
