import { reactive, watch, type Ref } from 'vue'

interface Product {
  name: string
  article?: string
  price: number
  product_code: string
  unit: string
  category_id: string
  brand_id: string
  color_id: string
  description: string
  texture: string
  pattern: string
  country: string
  collection: string
  slug: string
  attribute_values: any[]
}

interface Errors {
  name?: string
  article?: string
  price?: string
  productCode?: string
  unit?: string
  category?: string
  brand?: string
  color?: string
  description?: string
  texture?: string
  pattern?: string
  country?: string
  collection?: string
}

export function useProductValidation(product: Ref<Product>) {
  const errors = reactive<Errors>({})

  const validateName = () => {
    if (!product.value.name.trim()) {
      errors.name = 'Наименование обязательно.'
    } else if (product.value.name.length > 255) {
      errors.name = 'Наименование не должно превышать 255 символов.'
    } else {
      errors.name = ''
    }
  }

  const validatePrice = () => {
    if (!product.value.price || product.value.price <= 0) {
      errors.price = 'Цена должна быть положительным числом.'
    } else {
      errors.price = ''
    }
  }

  const validateProductCode = () => {
    if (product.value.product_code && product.value.product_code.length > 15) {
      errors.productCode = 'Код товара не должен превышать 15 символов.'
    } else {
      errors.productCode = ''
    }
  }

  const validateUnit = () => {
    if (!product.value.unit) {
      errors.unit = 'Единица измерения обязательна.'
    } else {
      errors.unit = ''
    }
  }

  const validateCategory = () => {
    if (!product.value.category_id) {
      errors.category = 'Категория обязательна.'
    } else {
      errors.category = ''
    }
  }

  const validateBrand = () => {
    if (!product.value.brand_id) {
      errors.brand = 'Бренд обязателен.'
    } else {
      errors.brand = ''
    }
  }

  const validateColor = () => {
    if (!product.value.color_id) {
      errors.color = 'Цвет обязателен.'
    } else {
      errors.color = ''
    }
  }

  const validateDescription = () => {
    if (!product.value.description.trim()) {
      errors.description = 'Описание товара обязательно для заполнения.'
    } else if (product.value.description.length < 10) {
      errors.description = 'Описание должно содержать не менее 10 символов.'
    } else {
      errors.description = ''
    }
  }

  const validateTexture = () => {
    if (product.value.texture && product.value.texture.length > 100) {
      errors.texture = 'Поверхность не должна превышать 100 символов.'
    } else {
      errors.texture = ''
    }
  }

  const validatePattern = () => {
    if (product.value.pattern && product.value.pattern.length > 100) {
      errors.pattern = 'Рисунок не должен превышать 100 символов.'
    } else {
      errors.pattern = ''
    }
  }

  const validateCountry = () => {
    if (product.value.country && product.value.country.length > 100) {
      errors.country = 'Страна не должна превышать 100 символов.'
    } else {
      errors.country = ''
    }
  }

  const validateCollection = () => {
    if (product.value.collection && product.value.collection.length > 255) {
      errors.collection = 'Коллекция не должна превышать 255 символов.'
    } else {
      errors.collection = ''
    }
  }

  const validateAttributes = () => {
    for (const attr of product.value.attribute_values) {
      if (attr.attribute.type === 'string') {
        if (attr.string_value && attr.string_value.length > 255) {
          attr.error = 'Значение не должно превышать 255 символов.'
        } else {
          attr.error = ''
        }
      }
    }
  }

  const validateAll = () => {
    validateName()
    validatePrice()
    validateProductCode()
    validateUnit()
    validateCategory()
    validateBrand()
    validateColor()
    validateDescription()
    validateTexture()
    validatePattern()
    validateCountry()
    validateCollection()
    validateAttributes()
  }

  const hasErrors = () => {
    return Object.values(errors).some(error => error) || product.value.attribute_values.some((attr: any) => attr.error)
  }

  // Watchers - validate on change
  watch(() => product.value.description, validateDescription)
  watch(() => product.value.name, (newName) => {
    validateName()
    if (newName) {
      product.value.slug = newName.toLowerCase().replace(/[^a-zа-яё0-9]+/g, '-').replace(/^-|-$/g, '')
    }
  })
  watch(() => product.value.price, validatePrice)
  watch(() => product.value.product_code, validateProductCode)
  watch(() => product.value.unit, validateUnit)
  watch(() => product.value.category_id, validateCategory)
  watch(() => product.value.brand_id, validateBrand)
  watch(() => product.value.color_id, validateColor)
  watch(() => product.value.texture, validateTexture)
  watch(() => product.value.pattern, validatePattern)
  watch(() => product.value.country, validateCountry)
  watch(() => product.value.collection, validateCollection)
  watch(() => product.value.attribute_values, validateAttributes, { deep: true })

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
