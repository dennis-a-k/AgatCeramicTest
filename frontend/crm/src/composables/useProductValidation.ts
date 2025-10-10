import { ref, watch, type Ref } from 'vue'

interface Product {
  name: string
  article: string
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
  const errors = ref<Errors>({})

  const validateName = () => {
    if (!product.value.name.trim()) {
      errors.value.name = 'Наименование обязательно.'
    } else if (product.value.name.length > 255) {
      errors.value.name = 'Наименование не должно превышать 255 символов.'
    } else {
      errors.value.name = ''
    }
  }

  const validateArticle = () => {
    if (!product.value.article.trim()) {
      errors.value.article = 'Артикул обязателен.'
    } else {
      errors.value.article = ''
    }
  }

  const validatePrice = () => {
    if (!product.value.price || product.value.price <= 0) {
      errors.value.price = 'Цена должна быть положительным числом.'
    } else {
      errors.value.price = ''
    }
  }

  const validateProductCode = () => {
    errors.value.productCode = ''
  }

  const validateUnit = () => {
    if (!product.value.unit) {
      errors.value.unit = 'Единица измерения обязательна.'
    } else {
      errors.value.unit = ''
    }
  }

  const validateCategory = () => {
    if (!product.value.category_id) {
      errors.value.category = 'Категория обязательна.'
    } else {
      errors.value.category = ''
    }
  }

  const validateBrand = () => {
    if (!product.value.brand_id) {
      errors.value.brand = 'Бренд обязателен.'
    } else {
      errors.value.brand = ''
    }
  }

  const validateColor = () => {
    if (!product.value.color_id) {
      errors.value.color = 'Цвет обязателен.'
    } else {
      errors.value.color = ''
    }
  }

  const validateDescription = () => {
    if (!product.value.description.trim()) {
      errors.value.description = 'Описание товара обязательно для заполнения.'
    } else if (product.value.description.length < 10) {
      errors.value.description = 'Описание должно содержать не менее 10 символов.'
    } else {
      errors.value.description = ''
    }
  }

  const validateTexture = () => {
    if (product.value.texture && product.value.texture.length > 100) {
      errors.value.texture = 'Поверхность не должна превышать 100 символов.'
    } else {
      errors.value.texture = ''
    }
  }

  const validatePattern = () => {
    if (product.value.pattern && product.value.pattern.length > 100) {
      errors.value.pattern = 'Рисунок не должен превышать 100 символов.'
    } else {
      errors.value.pattern = ''
    }
  }

  const validateCountry = () => {
    if (product.value.country && product.value.country.length > 100) {
      errors.value.country = 'Страна не должна превышать 100 символов.'
    } else {
      errors.value.country = ''
    }
  }

  const validateCollection = () => {
    if (product.value.collection && product.value.collection.length > 255) {
      errors.value.collection = 'Коллекция не должна превышать 255 символов.'
    } else {
      errors.value.collection = ''
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
    validateArticle()
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
    return Object.values(errors.value).some(error => error) || product.value.attribute_values.some((attr: any) => attr.error)
  }

  // Watchers - only clear errors on change
  watch(() => product.value.description, () => { errors.value.description = '' })
  watch(() => product.value.article, () => { errors.value.article = '' })
  watch(() => product.value.name, (newName) => {
    errors.value.name = ''
    if (newName) {
      product.value.slug = newName.toLowerCase().replace(/[^a-zа-яё0-9]+/g, '-').replace(/^-|-$/g, '')
    }
  })
  watch(() => product.value.price, () => { errors.value.price = '' })
  watch(() => product.value.product_code, () => { errors.value.productCode = '' })
  watch(() => product.value.unit, () => { errors.value.unit = '' })
  watch(() => product.value.category_id, () => { errors.value.category = '' })
  watch(() => product.value.brand_id, () => { errors.value.brand = '' })
  watch(() => product.value.color_id, () => { errors.value.color = '' })
  watch(() => product.value.texture, () => { errors.value.texture = '' })
  watch(() => product.value.pattern, () => { errors.value.pattern = '' })
  watch(() => product.value.country, () => { errors.value.country = '' })
  watch(() => product.value.collection, () => { errors.value.collection = '' })
  watch(() => product.value.attribute_values, () => {
    product.value.attribute_values.forEach(attr => {
      attr.error = ''
    })
  }, { deep: true })

  const resetErrors = () => {
    Object.keys(errors.value).forEach(key => {
      errors.value[key as keyof Errors] = ''
    })
  }

  return {
    errors,
    validateAll,
    hasErrors,
    resetErrors
  }
}
