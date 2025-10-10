import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useGoods } from '@/composables/useGoods'
import { useCategories } from '@/composables/useCategories'
import { useBrands } from '@/composables/useBrands'
import { useColors } from '@/composables/useColors'

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
}

export function useProductManager() {
  const route = useRoute()
  const router = useRouter()
  const { getProduct, updateProduct } = useGoods()
  const { allCategories, categories, fetchCategories } = useCategories()
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
    attribute_values: []
  })

  const loading = ref(false)
  const error = ref(false)
  const alert = ref<any>(null)

  const showAlert = (variant: string, title: string, message: string) => {
    alert.value = { variant, title, message }
    setTimeout(() => alert.value = null, 3000)
  }

  const loadProduct = async () => {
    try {
      loading.value = true
      error.value = false
      const productData = await getProduct(Number(route.params.id))
      product.value = { ...productData }
      // Инициализируем ошибки для атрибутов
      product.value.attribute_values.forEach(attr => {
        attr.error = ''
      })
    } catch (err) {
      error.value = true
      showAlert('error', 'Ошибка', 'Не удалось загрузить товар')
      console.error('Error loading product:', err)
    } finally {
      loading.value = false
    }
  }

  const handleFetchProducts = () => {
    error.value = false
    loadProduct()
  }

  const handleSubmit = async (validateAll: () => void, hasErrors: () => boolean, errors: any) => {
    validateAll()

    const validationErrors = Object.values(errors).filter(e => e)
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
      const result = await updateProduct(Number(route.params.id), product.value)
      if (result.success) {
        showAlert('success', 'Успешно', 'Товар обновлен')
        setTimeout(() => {
          router.push('/products')
        }, 1500)
      } else {
        if (result.errors) {
          // Очищаем предыдущие ошибки
          Object.keys(errors).forEach(key => {
            errors[key] = ''
          })
          product.value.attribute_values.forEach(attr => {
            attr.error = ''
          })

          // Присваиваем ошибки валидации
          if (result.errors) {
            Object.keys(result.errors).forEach(key => {
              if (key in errors && result.errors[key]) {
                errors[key] = result.errors[key][0]
              }
            })

            // Для attribute_values
            Object.keys(result.errors).forEach(key => {
              if (key.startsWith('attribute_values.') && result.errors[key]) {
                const parts = key.split('.')
                if (parts.length >= 3) {
                  const index = parseInt(parts[1])
                  const field = parts[2]
                  if (field === 'string_value' || field === 'number_value' || field === 'boolean_value') {
                    if (product.value.attribute_values[index]) {
                      product.value.attribute_values[index].error = result.errors[key][0]
                    }
                  }
                }
              }
            })
          }

          showAlert('error', 'Ошибка валидации', 'Пожалуйста, исправьте ошибки в форме')
        } else {
          showAlert('error', 'Ошибка', 'Не удалось обновить товар')
        }
      }
    } catch (error) {
      showAlert('error', 'Ошибка', 'Не удалось обновить товар')
      console.error('Error updating product:', error)
    } finally {
      loading.value = false
    }
  }

  const goBack = () => {
    router.push('/products')
  }

  const init = () => {
    fetchCategories()
    fetchBrands()
    fetchColors()
    loadProduct()
  }

  return {
    product,
    loading,
    error,
    alert,
    categories,
    brands,
    colors,
    loadProduct,
    handleFetchProducts,
    handleSubmit,
    goBack,
    init
  }
}
