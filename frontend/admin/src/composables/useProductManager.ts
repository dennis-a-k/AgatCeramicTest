import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useGoods } from '@/composables/useGoods'
import { useCategories } from '@/composables/useCategories'
import { useAllBrands } from '@/composables/useAllBrands'
import { useAllColors } from '@/composables/useAllColors'
import { useProductAlerts } from '@/composables/useProductAlerts'
import { watch } from 'vue'

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

export function useProductManager() {
  const route = useRoute()
  const router = useRouter()
  const { getProduct, updateProduct } = useGoods()
  const { allCategories, categories, fetchCategories, fetchCategoryAttributes } = useCategories()
  const { brands, fetchAllBrands } = useAllBrands()
  const { colors, fetchAllColors } = useAllColors()
  const { alerts, showAlert } = useProductAlerts()

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

  const loadProduct = async () => {
    try {
      loading.value = true
      error.value = false
      const productData = await getProduct(Number(route.params.id))
      product.value = { ...productData }
      // Убираем дубликаты атрибутов по attribute_id при загрузке
      product.value.attribute_values = product.value.attribute_values.filter((attr: any, index: number, self: any[]) =>
        self.findIndex((a: any) => a.attribute_id === attr.attribute_id) === index
      )
      // Инициализируем ошибки для атрибутов
      product.value.attribute_values.forEach((attr) => {
        attr.error = ''
      })
      // Загружаем все бренды и цвета для формы
      await fetchAllBrands()
      await fetchAllColors()

      // Загружаем атрибуты для текущей категории товара
      if (product.value.category_id) {
        await loadCategoryAttributes(product.value.category_id)
      }
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
      validationErrors.forEach(error => {
        showAlert('error', 'Ошибка валидации', error as string)
      })
      return
    }

    // Очищаем предыдущие алерты
    alerts.value = []

    try {
      loading.value = true
      const newFiles = imageUploadRef?.newFiles || []
      // Убираем дубликаты атрибутов перед отправкой
      const productToUpdate = {
        ...product.value,
        attribute_values: product.value.attribute_values.filter((attr: any, index: number, self: any[]) =>
          self.findIndex((a: any) => a.attribute_id === attr.attribute_id) === index
        )
      }
      const result = await updateProduct(Number(route.params.id), productToUpdate, newFiles)
      if (result.success) {
        router.push('/products?success=updated')
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

          // Выводим ошибки валидации из бэкенда в алерты
          Object.values(result.errors).forEach((errorArray: any) => {
            if (Array.isArray(errorArray)) {
              errorArray.forEach((error: string) => {
                showAlert('error', 'Ошибка валидации', error)
              })
            } else {
              showAlert('error', 'Ошибка валидации', errorArray)
            }
          })

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

  const loadCategoryAttributes = async (categoryId: string) => {
    if (!categoryId) {
      product.value.attribute_values = []
      return
    }

    try {
      const attributes = await fetchCategoryAttributes(Number(categoryId))
      // Убираем дубликаты атрибутов по id
      const uniqueAttributes = attributes.filter((attr: any, index: number, self: any[]) =>
        self.findIndex((a: any) => a.id === attr.id) === index
      )
      const existingAttributes = product.value.attribute_values

      product.value.attribute_values = uniqueAttributes.map((attr: any) => {
        // Ищем существующий атрибут с таким же attribute_id
        const existingAttr = existingAttributes.find((existing: any) => existing.attribute_id === attr.id)

        return {
          id: existingAttr?.id || null, // Добавляем id для существующих атрибутов
          attribute_id: attr.id,
          attribute: attr,
          string_value: existingAttr?.string_value || '',
          number_value: existingAttr?.number_value || null,
          boolean_value: existingAttr?.boolean_value || false,
          text_value: existingAttr?.text_value || '',
          error: ''
        }
      })
    } catch (err) {
      console.error('Ошибка загрузки атрибутов:', err)
      product.value.attribute_values = []
    }
  }

  const init = async () => {
    await fetchCategories()
    await fetchAllBrands()
    await fetchAllColors()
    await loadProduct()

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
    alerts,
    categories,
    brands,
    colors,
    loadProduct,
    handleFetchProducts,
    handleSubmit,
    goBack,
    init,
  }
}
