<template>
  <AdminLayout>
    <div v-if="loading" class="flex justify-center items-center h-screen">
      <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-brand-500"></div>
    </div>
    <div v-else-if="error"
      class="flex flex-col justify-center items-center h-screen font-bold text-error-700 text-theme-xl dark:text-error-500">
      Ошибка при загрузке<br />
      <button
        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300 mt-2"
        @click="handleFetchProducts">Попробовать снова</button>
    </div>
    <div v-else-if="!product.id"
      class="flex flex-col justify-center items-center h-screen menu-item-icon-active text-center font-bold text-theme-xl m-5">
      Товар не найден
    </div>
    <div v-else>
      <PageBreadcrumb :pageTitle="currentPageTitle + product.article" />
      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">
              Характеристики товара
            </h2>
          </div>
          <div class="space-y-5 p-4 sm:p-6 dark:border-gray-800">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
              <div>
                <label for="product-name"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Наименование</label>
                <input type="text" id="product-name" :class="inputClass(nameError)" placeholder="Наименование товара"
                  v-model="product.name">
                <p v-if="nameError" class="mt-1.5 text-theme-xs text-error-500">{{ nameError }}</p>
              </div>
              <div>
                <label for="product-country"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Страна</label>
                <input type="text" id="product-country"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  placeholder="Страна производитель" v-model="product.country">
                <p v-if="countryError" class="mt-1.5 text-theme-xs text-error-500">{{ countryError }}</p>
              </div>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
              <div class="relative z-20 bg-transparent">
                <label for="product-category" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Категория
                </label>
                <div class="relative z-20 bg-transparent">
                  <select id="product-category" v-model="product.category_id" :class="inputClass(categoryError)"
                    required>
                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      Выберите категорию
                    </option>
                    <option v-for="category in categories.slice(1)" :key="category.id" :value="category.value"
                      class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      {{ category.label }}
                    </option>
                  </select>
                  <span
                    class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </span>
                </div>
                <p v-if="categoryError" class="mt-1.5 text-theme-xs text-error-500">{{ categoryError }}</p>
              </div>
              <div class="relative z-20 bg-transparent">
                <label for="product-brand" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Бренд
                </label>
                <div class="relative z-20 bg-transparent">
                  <select id="product-brand" v-model="product.brand_id" :class="inputClass(brandError)" required>
                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      Выберите бренд
                    </option>
                    <option v-for="brand in brands" :key="brand.id" :value="brand.id"
                      class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      {{ brand.name }}
                    </option>
                  </select>
                  <span
                    class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </span>
                </div>
                <p v-if="brandError" class="mt-1.5 text-theme-xs text-error-500">{{ brandError }}</p>
              </div>
              <div class="relative z-20 bg-transparent">
                <label for="product-color" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Цвет
                </label>
                <div class="relative z-20 bg-transparent">
                  <select id="product-color" v-model="product.color_id" :class="inputClass(colorError)" required>
                    <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      Выберите цвет
                    </option>
                    <option v-for="color in colors" :key="color.id" :value="color.id"
                      class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                      {{ color.name }}
                    </option>
                  </select>
                  <span
                    class="pointer-events-none absolute top-1/2 right-4 z-30 -translate-y-1/2 text-gray-700 dark:text-gray-400">
                    <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </span>
                </div>
                <p v-if="colorError" class="mt-1.5 text-theme-xs text-error-500">{{ colorError }}</p>
              </div>
              <div>
                <label for="product-price"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Цена</label>
                <input type="number" step="0.01" id="product-price" min="0" :class="inputClass(priceError)"
                  placeholder="Стоимость товара" v-model.number="product.price">
                <p v-if="priceError" class="mt-1.5 text-theme-xs text-error-500">{{ priceError }}</p>
              </div>
              <div>
                <label for="product-article"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Артикул</label>
                <input type="text" id="product-article" :class="inputClass(articleError)" placeholder="Артикул товара"
                  v-model="product.article">
                <p v-if="articleError" class="mt-1.5 text-theme-xs text-error-500">{{ articleError }}</p>
              </div>
              <div>
                <label for="product-product_code"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Код товара</label>
                <input type="text" id="product-product_code" :class="inputClass(productCodeError)"
                  placeholder="Код товара" v-model="product.product_code">
                <p v-if="productCodeError" class="mt-1.5 text-theme-xs text-error-500">{{ productCodeError }}</p>
              </div>
              <div>
                <div class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Единица измерения</div>
                <div class="flex space-x-6">
                  <label for="product-unit1"
                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                    <input id="product-unit1" type="radio" v-model="product.unit" value="шт" class="sr-only" />
                    <div
                      :class="product.unit === 'шт' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                      class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                      <span :class="product.unit === 'шт' ? '' : 'opacity-0'"
                        class="h-2 w-2 rounded-full bg-white"></span>
                    </div>
                    шт.
                  </label>
                  <label for="product-unit2"
                    class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                    <input id="product-unit2" type="radio" v-model="product.unit" value="кв.м" class="sr-only" />
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
              <div class="flex space-x-6">
                <Checkbox id="is_published" label="Опубликован" v-model:checked="product.is_published" />
                <Checkbox id="is_sale" label="Распродажа" v-model:checked="product.is_sale" />
              </div>
              <div class="col-span-full">
                <label for="product-description"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                  Описание
                </label>
                <textarea id="product-description" placeholder="Полное описание товара" type="text" rows="7"
                  v-model="product.description" :class="textareaClass">
                </textarea>
                <p v-if="descriptionError" class="mt-1.5 text-theme-xs text-error-500">{{ descriptionError }}</p>
              </div>
            </div>
          </div>
        </div>
        <div v-if="isCeramicCategory" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">
              Характеристики для керамики
            </h2>
          </div>
          <div class="space-y-5 p-4 sm:p-6">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
              <div>
                <label for="product-texture"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Поверхность</label>
                <input type="text" id="product-texture"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  placeholder="Тип поверхности" v-model="product.texture">
                <p v-if="textureError" class="mt-1.5 text-theme-xs text-error-500">{{ textureError }}</p>
              </div>
              <div>
                <label for="product-pattern"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Рисунок</label>
                <input type="text" id="product-pattern"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  placeholder="Тип рисунка или монотон" v-model="product.pattern">
                <p v-if="patternError" class="mt-1.5 text-theme-xs text-error-500">{{ patternError }}</p>
              </div>
              <div>
                <label for="product-collection"
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Коллекция</label>
                <input type="text" id="product-collection"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                  placeholder="Коллекция товара" v-model="product.collection">
                <p v-if="collectionError" class="mt-1.5 text-theme-xs text-error-500">{{ collectionError }}</p>
              </div>
            </div>
          </div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">
              Дополнительные характеристики товара<br>
              <span
                class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                Размеры указываются в милиметрах. Вес в килограммах
              </span>
            </h2>
          </div>
          <div class="space-y-5 p-4 sm:p-6">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
              <div v-for="attr in product.attribute_values" :key="attr.id">
                <div v-if="attr.attribute.type === 'string'">
                  <label :for="`attr-string${attr.id}`"
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{ attr.attribute.name }}</label>
                  <input :id="`attr-string${attr.id}`" type="text" v-model="attr.string_value"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                  <p v-if="attr.error" class="mt-1.5 text-theme-xs text-error-500">{{ attr.error }}</p>
                </div>
                <div v-else-if="attr.attribute.type === 'number'">
                  <label :for="`attr-number${attr.id}`"
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{ attr.attribute.name }}</label>
                  <input :id="`attr-number${attr.id}`" type="number" step="0.01" min="0"
                    v-model.number="attr.number_value"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                </div>
                <div v-else-if="attr.attribute.type === 'boolean'" class="h-full flex">
                  <Checkbox :id="'attr-' + attr.id" :label="attr.attribute.name" v-model:checked="attr.boolean_value" />
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
    </div>
    <ToastAlert :alert="alert" />
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import ToastAlert from '@/components/common/ToastAlert.vue'
import { useGoods } from '@/composables/useGoods'
import { useCategories } from '@/composables/useCategories'
import { useBrands } from '@/composables/useBrands'
import { useColors } from '@/composables/useColors'
import Checkbox from '@/components/ui/Checkbox.vue'

const route = useRoute()
const router = useRouter()
const { getProduct, updateProduct } = useGoods()
const { allCategories, categories, fetchCategories } = useCategories()
const { brands, fetchBrands } = useBrands()
const { colors, fetchColors } = useColors()

const currentPageTitle = ref('Редактирование товара арт. ')
const product = ref({
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
const alert = ref(null)
const descriptionError = ref('')
const articleError = ref('')
const nameError = ref('')
const priceError = ref('')
const productCodeError = ref('')
const unitError = ref('')
const categoryError = ref('')
const brandError = ref('')
const colorError = ref('')
const textureError = ref('')
const patternError = ref('')
const countryError = ref('')
const collectionError = ref('')

const showAlert = (variant, title, message) => {
  alert.value = { variant, title, message }
  setTimeout(() => alert.value = null, 3000)
}

const validateDescription = () => {
  if (!product.value.description.trim()) {
    descriptionError.value = 'Описание товара обязательно для заполнения.'
  } else if (product.value.description.length < 10) {
    descriptionError.value = 'Описание должно содержать не менее 10 символов.'
  } else {
    descriptionError.value = ''
  }
}

const validateArticle = () => {
  if (!product.value.article.trim()) {
    articleError.value = 'Артикул обязателен.'
  } else {
    articleError.value = ''
  }
}

const validateName = () => {
  if (!product.value.name.trim()) {
    nameError.value = 'Наименование обязательно.'
  } else if (product.value.name.length > 255) {
    nameError.value = 'Наименование не должно превышать 255 символов.'
  } else {
    nameError.value = ''
  }
}

const validatePrice = () => {
  if (!product.value.price || product.value.price <= 0) {
    priceError.value = 'Цена должна быть положительным числом.'
  } else {
    priceError.value = ''
  }
}

const validateProductCode = () => {
  // product_code nullable, так что валидация не обязательна, но можно проверить формат если нужно
  productCodeError.value = ''
}

const validateUnit = () => {
  if (!product.value.unit) {
    unitError.value = 'Единица измерения обязательна.'
  } else {
    unitError.value = ''
  }
}

const validateCategory = () => {
  if (!product.value.category_id) {
    categoryError.value = 'Категория обязательна.'
  } else {
    categoryError.value = ''
  }
}

const validateBrand = () => {
  if (!product.value.brand_id) {
    brandError.value = 'Бренд обязателен.'
  } else {
    brandError.value = ''
  }
}

const validateColor = () => {
  if (!product.value.color_id) {
    colorError.value = 'Цвет обязателен.'
  } else {
    colorError.value = ''
  }
}

const validateTexture = () => {
  if (product.value.texture && product.value.texture.length > 100) {
    textureError.value = 'Поверхность не должна превышать 100 символов.'
  } else {
    textureError.value = ''
  }
}

const validatePattern = () => {
  if (product.value.pattern && product.value.pattern.length > 100) {
    patternError.value = 'Рисунок не должен превышать 100 символов.'
  } else {
    patternError.value = ''
  }
}

const validateCountry = () => {
  if (product.value.country && product.value.country.length > 100) {
    countryError.value = 'Страна не должна превышать 100 символов.'
  } else {
    countryError.value = ''
  }
}

const validateCollection = () => {
  if (product.value.collection && product.value.collection.length > 255) {
    collectionError.value = 'Коллекция не должна превышать 255 символов.'
  } else {
    collectionError.value = ''
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

watch(() => product.value.description, validateDescription)
watch(() => product.value.article, validateArticle)
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

const textareaClass = computed(() => {
  return descriptionError.value ? 'dark:bg-dark-900 w-full resize-none rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-error-300 focus:outline-hidden focus:ring-3 focus:ring-error-500/10 dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-error-800' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full resize-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
})

const inputClass = (error) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

const isCeramicCategory = computed(() => {
  if (!product.value.category_id) return false
  const cat = categories.value.find(c => c.value === product.value.category_id)
  if (!cat) return false
  const name = cat.label.toLowerCase()
  return name.includes('керамогранит') || name.includes('плитка') || name.includes('мозаика') || name.includes('клинкер') || name.includes('ступени')
})

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

const handleSubmit = async () => {
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

  const errors = [
    articleError.value,
    nameError.value,
    priceError.value,
    productCodeError.value,
    unitError.value,
    categoryError.value,
    brandError.value,
    colorError.value,
    descriptionError.value,
    textureError.value,
    patternError.value,
    countryError.value,
    collectionError.value
  ].filter(e => e)

  // Добавляем ошибки атрибутов
  for (const attr of product.value.attribute_values) {
    if (attr.error) {
      errors.push(attr.error)
    }
  }

  if (errors.length > 0) {
    showAlert('error', 'Ошибка валидации', errors.join(' '))
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
        articleError.value = ''
        nameError.value = ''
        priceError.value = ''
        productCodeError.value = ''
        unitError.value = ''
        categoryError.value = ''
        brandError.value = ''
        colorError.value = ''
        descriptionError.value = ''
        textureError.value = ''
        patternError.value = ''
        countryError.value = ''
        collectionError.value = ''
        product.value.attribute_values.forEach(attr => {
          attr.error = ''
        })

        // Присваиваем ошибки валидации
        if (result.errors.article) articleError.value = result.errors.article[0]
        if (result.errors.name) nameError.value = result.errors.name[0]
        if (result.errors.price) priceError.value = result.errors.price[0]
        if (result.errors.product_code) productCodeError.value = result.errors.product_code[0]
        if (result.errors.unit) unitError.value = result.errors.unit[0]
        if (result.errors.category_id) categoryError.value = result.errors.category_id[0]
        if (result.errors.brand_id) brandError.value = result.errors.brand_id[0]
        if (result.errors.color_id) colorError.value = result.errors.color_id[0]
        if (result.errors.description) descriptionError.value = result.errors.description[0]
        if (result.errors.texture) textureError.value = result.errors.texture[0]
        if (result.errors.pattern) patternError.value = result.errors.pattern[0]
        if (result.errors.country) countryError.value = result.errors.country[0]
        if (result.errors.collection) collectionError.value = result.errors.collection[0]

        // Для attribute_values
        Object.keys(result.errors).forEach(key => {
          if (key.startsWith('attribute_values.')) {
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

onMounted(() => {
  fetchCategories()
  fetchBrands()
  fetchColors()
  loadProduct()
})
</script>
