import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useGoods } from '@/composables/useGoods'
import { useCategories } from '@/composables/useCategories'
import { watch } from 'vue'
import { useBrands } from '@/composables/useBrands'
import { useColors } from '@/composables/useColors'

interface ProductImage {
  id?: number
  image_path: string
  sort_order: number
}

interface Product {
  id: number | null
  article: string
  name: string
  slug: string
  price: number
  unit: string
  product_code: string
  description: string
  category_id: string
  color_id: string
  brand_id: string
  is_published: boolean
  is_sale: boolean
  texture: string
  pattern: string
  country: string
  collection: string
  attribute_values: any[]
  images: ProductImage[]
}

export function useProductCreator() {
  const router = useRouter()
  const { createProduct } = useGoods()
  const { categories, fetchCategories, fetchCategoryAttributes } = useCategories()
  const { brands, fetchBrands } = useBrands()
  const { colors, fetchColors } = useColors()

  const product = ref<Product>({
    id: null,
    article: '',
    name: '',
    slug: '',
    price: 0,
    unit: 'шт',
    product_code: '',
    description: '',
    category_id: '',
    color_id: '',
    brand_id: '',
    is_published: true,
    is_sale: false,
    texture: '',
    pattern: '',
    country: '',
    collection: '',
    attribute_values: [],
    images: [],
  })

  const loading = ref(false)
  const error = ref(false)
  const alert = ref<any>(null)

  const showAlert = (variant: string, title: string, message: string) => {
    alert.value = { variant, title, message }
    setTimeout(() => (alert.value = null), 3000)
  }

  const handleFetchProducts = () => {
    error.value = false
    // Для создания товара не нужно загружать данные
  }

  const handleSubmit = async (validateAll: () => void, hasErrors: () => boolean, errors: any, imageUploadRef?: any) => {
    validateAll()

    const validationErrors = Object.values(errors).filter((e) => e)
    // Добавляем ошибки атрибутов
    for (const attr of product.value.attribute_values) {
      if (attr.error) {
        validationErrors.push(attr.error)
      }
    }

    if (validationErrors.length > 0) {
      showAlert('error', 'Ошибка валидации', validationErrors.join(' '))
      return
    }

    try {
      loading.value = true
      const newFiles = imageUploadRef?.newFiles || []
      const result = await createProduct(product.value, newFiles)
      if (result.success) {
        showAlert('success', 'Успешно', 'Товар создан')
        setTimeout(() => {
          router.push('/products')
        }, 1500)
      } else {
        if (result.errors) {
          // Очищаем предыдущие ошибки
          Object.keys(errors).forEach((key) => {
            errors[key] = ''
          })
          product.value.attribute_values.forEach((attr) => {
            attr.error = ''
          })

          // Присваиваем ошибки валидации
          Object.keys(result.errors).forEach((key) => {
            if (key in errors && result.errors![key]) {
              errors[key] = result.errors![key][0]
            }
          })

          // Для attribute_values
          Object.keys(result.errors).forEach((key) => {
            if (key.startsWith('attribute_values.') && result.errors![key]) {
              const parts = key.split('.')
              if (parts.length >= 3) {
                const index = parseInt(parts[1])
                const field = parts[2]
                if (
                  field === 'string_value' ||
                  field === 'number_value' ||
                  field === 'boolean_value'
                ) {
                  if (product.value.attribute_values[index]) {
                    product.value.attribute_values[index].error = result.errors![key][0]
                  }
                }
              }
            }
          })

          showAlert('error', 'Ошибка валидации', 'Пожалуйста, исправьте ошибки в форме')
        } else {
          showAlert('error', 'Ошибка', 'Не удалось создать товар')
        }
      }
    } catch (error) {
      showAlert('error', 'Ошибка', 'Не удалось создать товар')
      console.error('Error creating product:', error)
    } finally {
      loading.value = false
    }
  }

  const goBack = () => {
    router.push('/products')
  }

  const loadCategoryAttributes = async (categoryId: string) => {
    if (!categoryId) {
      product.value.attribute_values = []
      return
    }

    try {
      const attributes = await fetchCategoryAttributes(Number(categoryId))
      product.value.attribute_values = attributes.map((attr: any) => ({
        attribute_id: attr.id,
        attribute: attr,
        string_value: '',
        number_value: null,
        boolean_value: false,
        text_value: '',
        error: ''
      }))
    } catch (err) {
      console.error('Ошибка загрузки атрибутов:', err)
      product.value.attribute_values = []
    }
  }

  const init = () => {
    fetchCategories()
    fetchBrands()
    fetchColors()

    // Наблюдатель за изменением категории
    watch(() => product.value.category_id, (newCategoryId) => {
      loadCategoryAttributes(newCategoryId)
    })
  }

  return {
    product,
    loading,
    error,
    alert,
    categories,
    brands,
    colors,
    handleFetchProducts,
    handleSubmit,
    goBack,
    init,
  }
}
