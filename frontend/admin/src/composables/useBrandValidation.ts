import { reactive, watch, type Ref } from 'vue'

interface Brand {
  name: string
  country: string
  description: string
  is_active: boolean
  image: string
}

interface Errors {
  name?: string
  country?: string
  description?: string
  image?: string
}

export function useBrandValidation(brand: Ref<Brand>) {
  const errors = reactive<Errors>({})

  const validateName = () => {
    // Don't validate if all fields are empty (initial state)
    if (shouldSkipValidation()) {
      errors.name = ''
      return
    }

    if (!brand.value || !brand.value.name || typeof brand.value.name !== 'string' || !brand.value.name.trim()) {
      errors.name = 'Название бренда обязательно.'
    } else if (brand.value.name.length > 255) {
      errors.name = 'Название бренда не должно превышать 255 символов.'
    } else {
      errors.name = ''
    }
  }

  const validateCountry = () => {
    // Don't validate if all fields are empty (initial state)
    if (shouldSkipValidation()) {
      errors.country = ''
      return
    }

    if (brand.value && brand.value.country && brand.value.country.length > 255) {
      errors.country = 'Страна не должна превышать 255 символов.'
    } else {
      errors.country = ''
    }
  }

  const validateDescription = () => {
    // Don't validate if all fields are empty (initial state)
    if (shouldSkipValidation()) {
      errors.description = ''
      return
    }

    if (brand.value && brand.value.description && brand.value.description.length > 1000) {
      errors.description = 'Описание не должно превышать 1000 символов.'
    } else {
      errors.description = ''
    }
  }

  const validateImage = () => {
    // Don't validate if all fields are empty (initial state)
    if (shouldSkipValidation()) {
      errors.image = ''
      return
    }

    if (brand.value && brand.value.image && !/^https?:\/\/.+/.test(brand.value.image)) {
      errors.image = 'Ссылка на изображение должна быть валидным URL.'
    } else {
      errors.image = ''
    }
  }

  // Don't validate empty fields initially
  const shouldSkipValidation = () => {
    const isEmpty = !brand.value || (!brand.value.name?.trim() && !brand.value.country?.trim() && !brand.value.description?.trim() && !brand.value.image?.trim())
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
      validateCountry()
      validateDescription()
      validateImage()
    }
  }

  const hasErrors = () => {
    return Object.values(errors).some(error => error)
  }

  // Watchers - validate on change
  let isInitialized = false

  watch(() => brand.value?.name, (newVal, oldVal) => {
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
  watch(() => brand.value?.country, (newVal, oldVal) => {
    // Skip validation during initialization
    if (!isInitialized) return
    // Only validate if the value actually changed by user input
    if (newVal !== oldVal && oldVal !== undefined) {
      validateCountry()
    }
  })
  watch(() => brand.value?.description, (newVal, oldVal) => {
    // Skip validation during initialization
    if (!isInitialized) return
    // Only validate if the value actually changed by user input
    if (newVal !== oldVal && oldVal !== undefined) {
      validateDescription()
    }
  })
  watch(() => brand.value?.image, (newVal, oldVal) => {
    // Skip validation during initialization
    if (!isInitialized) return
    // Only validate if the value actually changed by user input
    if (newVal !== oldVal && oldVal !== undefined) {
      validateImage()
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
