<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div class="space-y-5 sm:space-y-6">
      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="p-6">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Редактирование товара</h2>

          <form @submit.prevent="handleSubmit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Артикул</label>
                <input
                  v-model="product.article"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Наименование</label>
                <input
                  v-model="product.name"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Код товара</label>
                <input
                  v-model="product.product_code"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Категория</label>
                <select
                  v-model="product.category_id"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                  required
                >
                  <option value="">Выберите категорию</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Цена</label>
                <input
                  v-model.number="product.price"
                  type="number"
                  step="0.01"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Единица измерения</label>
                <select
                  v-model="product.unit"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                  required
                >
                  <option value="шт">шт</option>
                  <option value="кв.м">кв.м</option>
                </select>
              </div>
            </div>

            <div class="flex items-center space-x-6">
              <div class="flex items-center">
                <input
                  v-model="product.is_published"
                  type="checkbox"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600"
                />
                <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Опубликован</label>
              </div>

              <div class="flex items-center">
                <input
                  v-model="product.is_sale"
                  type="checkbox"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600"
                />
                <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Распродажа</label>
              </div>
            </div>

            <div class="flex justify-end space-x-4">
              <button
                type="button"
                @click="goBack"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800"
              >
                Отмена
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
              >
                {{ loading ? 'Сохранение...' : 'Сохранить' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <ToastAlert :alert="alert" />
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import ToastAlert from '@/components/common/ToastAlert.vue'
import { useGoods } from '@/composables/useGoods'
import { useCategories } from '@/composables/useCategories'

const route = useRoute()
const router = useRouter()
const { getProduct, updateProduct } = useGoods()
const { categories, fetchCategories } = useCategories()

const currentPageTitle = ref('Редактирование товара')
const product = ref({
  article: '',
  name: '',
  product_code: '',
  category_id: '',
  price: 0,
  unit: 'шт',
  is_published: true,
  is_sale: false
})
const loading = ref(false)
const alert = ref(null)

const showAlert = (variant, title, message) => {
  alert.value = { variant, title, message }
  setTimeout(() => alert.value = null, 3000)
}

const loadProduct = async () => {
  try {
    loading.value = true
    const productData = await getProduct(Number(route.params.id))
    product.value = {
      article: productData.article,
      name: productData.name,
      product_code: productData.product_code,
      category_id: productData.category_id,
      price: productData.price,
      unit: productData.unit,
      is_published: productData.is_published,
      is_sale: productData.is_sale
    }
  } catch (error) {
    showAlert('error', 'Ошибка', 'Не удалось загрузить товар')
    console.error('Error loading product:', error)
  } finally {
    loading.value = false
  }
}

const handleSubmit = async () => {
  try {
    loading.value = true
    const success = await updateProduct(Number(route.params.id), product.value)
    if (success) {
      showAlert('success', 'Успешно', 'Товар обновлен')
      setTimeout(() => {
        router.push('/products')
      }, 1500)
    } else {
      showAlert('error', 'Ошибка', 'Не удалось обновить товар')
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

onMounted(() => {
  fetchCategories()
  loadProduct()
})
</script>
