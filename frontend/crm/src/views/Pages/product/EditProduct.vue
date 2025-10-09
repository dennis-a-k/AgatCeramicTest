<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle + product.article" />
    <form @submit.prevent="handleSubmit" class="space-y-6">
      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
          <h2 class="text-lg font-medium text-gray-800 dark:text-white">
            Характеристики товара
          </h2>
        </div>
        <div class="p-4 sm:p-6 dark:border-gray-800">
          <form>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label for="product-name"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Наименование</label>
                <input type="text" id="product-name"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  placeholder="Наименование товара" v-model="product.name">
              </div>
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Категория
                </label>
                <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                  <select
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    :class="'text-gray-800 dark:text-white/90'" @change="isOptionSelected = true">
                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      Select Category
                    </option>
                    <option value="Laptop" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      Laptop
                    </option>
                    <option value="Phone" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      Phone
                    </option>
                    <option value="Watch" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      Watch
                    </option>
                    <option value="Electronics" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      Electronics
                    </option>
                    <option value="Accessories" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      Accessories
                    </option>
                  </select>
                  <span
                    class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </span>
                </div>
              </div>
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Бренд
                </label>
                <select v-model="product.brand_id"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  required>
                  <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                    Выберите бренд
                  </option>
                  <option v-for="brand in brands" :key="brand.id" :value="brand.id"
                    class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                    {{ brand.name }}
                  </option>
                </select>
              </div>
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Цвет
                </label>
                <select v-model="product.color_id"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  required>
                  <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                    Выберите цвет
                  </option>
                  <option v-for="color in colors" :key="color.id" :value="color.id"
                    class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                    {{ color.name }}
                  </option>
                </select>
              </div>
              <div>
                <label for="product-article"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Артикул</label>
                <input type="text" id="product-article"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  placeholder="Артикул товара" v-model="product.article">
              </div>
              <div>
                <label for="product-collection"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Коллекция</label>
                <input type="text" id="product-collection"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  placeholder="Коллекция товара" v-model="product.collection">
              </div>
              <div>
                <label for="product-product_code"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Код товара</label>
                <input type="text" id="product-product_code"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  placeholder="Код товара" v-model="product.product_code">
              </div>
              <div>
                <label for="product-price"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Цена</label>
                <input type="number" step="0.01" id="product-price" min="0"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  placeholder="Стоимость товара" v-model.number="product.price">
              </div>
              <div class="flex space-x-6">
                <FilterCheckbox id="is_published" label="Опубликован" v-model:checked="product.is_published" />
                <FilterCheckbox id="is_sale" label="Распродажа" v-model:checked="product.is_sale" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Единица измерения</label>
                <div class="flex space-x-6">
                  <label
                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                    <input type="radio" v-model="product.unit" value="шт" class="sr-only" />
                    <div
                      :class="product.unit === 'шт' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                      class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                      <span :class="product.unit === 'шт' ? '' : 'opacity-0'"
                        class="h-2 w-2 rounded-full bg-white"></span>
                    </div>
                    шт.
                  </label>
                  <label
                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                    <input type="radio" v-model="product.unit" value="кв.м" class="sr-only" />
                    <div
                      :class="product.unit === 'кв.м' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                      class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                      <span :class="product.unit === 'кв.м' ? '' : 'opacity-0'"
                        class="h-2 w-2 rounded-full bg-white"></span>
                    </div>
                    м²
                  </label>
                </div>
              </div>
              <div class="col-span-full">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Описание
                </label>
                <textarea placeholder="Полное описание товара" type="text" rows="7" v-model="product.description"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full resize-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                </textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
          <h2 class="text-lg font-medium text-gray-800 dark:text-white">
            Дополнительные характеристики товара<br>
            <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
              Размеры указываются в милиметрах. Вес в килограммах
            </span>
          </h2>
        </div>
        <div class="space-y-5 p-4 sm:p-6">
          <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div v-for="attr in product.attribute_values" :key="attr.id">
              <div v-if="attr.attribute.type === 'string'">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  {{ attr.attribute.name }}</label>
                <input type="text" v-model="attr.string_value"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
              </div>
              <div v-else-if="attr.attribute.type === 'number'">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  {{ attr.attribute.name }}</label>
                <input type="number" step="0.01" min="0" v-model.number="attr.number_value"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
              </div>
              <div v-else-if="attr.attribute.type === 'boolean'" class="flex items-end">
                <FilterCheckbox :id="'attr-' + attr.id" :label="attr.attribute.name"
                  v-model:checked="attr.boolean_value" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
          <h2 class="text-lg font-medium text-gray-800 dark:text-white">
            Products Images
          </h2>
        </div>
        <div class="p-4 sm:p-6">
          <label for="product-image"
            class="shadow-theme-xs group hover:border-brand-500 block cursor-pointer rounded-lg border-2 border-dashed border-gray-300 transition dark:border-gray-800">
            <div class="flex justify-center p-10">
              <div class="flex max-w-[260px] flex-col items-center gap-4">
                <div
                  class="inline-flex h-13 w-13 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition dark:border-gray-800 dark:text-gray-400">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                      d="M20.0004 16V18.5C20.0004 19.3284 19.3288 20 18.5004 20H5.49951C4.67108 20 3.99951 19.3284 3.99951 18.5V16M12.0015 4L12.0015 16M7.37454 8.6246L11.9994 4.00269L16.6245 8.6246"
                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </div>
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                  <span class="font-medium text-gray-800 dark:text-white/90">Click to upload</span>
                  or drag and drop SVG, PNG, JPG or GIF (MAX. 800x400px)
                </p>
              </div>
            </div>
            <input type="file" id="product-image" class="hidden">
          </label>
        </div>
      </div>
      <!-- Button -->
      <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        <button @click="goBack"
          class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
          Отмена
        </button>
        <button type="submit" :disabled="loading"
          class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
          {{ loading ? 'Сохранение...' : 'Сохранить' }}
        </button>
      </div>
    </form>
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
import { useBrands } from '@/composables/useBrands'
import { useColors } from '@/composables/useColors'
import FilterCheckbox from '@/components/goods-page/FilterCheckbox.vue'

const route = useRoute()
const router = useRouter()
const { getProduct, updateProduct } = useGoods()
const { categories, fetchCategories } = useCategories()
const { brands, fetchBrands } = useBrands()
const { colors, fetchColors } = useColors()

const currentPageTitle = ref('Редактирование товара арт. ')
const product = ref({
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
  collection: ''
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
    product.value = { ...productData }
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
  fetchBrands()
  fetchColors()
  loadProduct()
})
</script>
