<template>
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
          <input type="text" id="product-name" :class="inputClass(errors.name)" placeholder="Наименование товара"
            v-model="product.name">
          <p v-if="errors.name" class="mt-1.5 text-theme-xs text-error-500">{{ errors.name }}</p>
        </div>
        <div>
          <label for="product-country"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Страна</label>
          <input type="text" id="product-country" :class="inputClass(errors.country)" placeholder="Страна производитель"
            v-model="product.country">
          <p v-if="errors.country" class="mt-1.5 text-theme-xs text-error-500">{{ errors.country }}</p>
        </div>
      </div>
      <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div class="relative z-20 bg-transparent">
          <label for="product-category"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Категория</label>
          <div class="relative z-20 bg-transparent">
            <select id="product-category" v-model="product.category_id" :class="inputClass(errors.category)" required>
              <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Выберите категорию</option>
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
          <p v-if="errors.category" class="mt-1.5 text-theme-xs text-error-500">{{ errors.category }}</p>
        </div>
        <div class="relative z-20 bg-transparent">
          <label for="product-brand"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Бренд</label>
          <div class="relative z-20 bg-transparent">
            <select id="product-brand" v-model="product.brand_id" :class="inputClass(errors.brand)" required>
              <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Выберите бренд</option>
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
          <p v-if="errors.brand" class="mt-1.5 text-theme-xs text-error-500">{{ errors.brand }}</p>
        </div>
        <div class="relative z-20 bg-transparent">
          <label for="product-color"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Цвет</label>
          <div class="relative z-20 bg-transparent">
            <select id="product-color" v-model="product.color_id" :class="inputClass(errors.color)" required>
              <option value="" class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">Выберите цвет</option>
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
          <p v-if="errors.color" class="mt-1.5 text-theme-xs text-error-500">{{ errors.color }}</p>
        </div>
        <div>
          <label for="product-price"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Цена</label>
          <input type="number" step="0.01" id="product-price" min="0" :class="inputClass(errors.price)"
            placeholder="Стоимость товара" v-model.number="product.price">
          <p v-if="errors.price" class="mt-1.5 text-theme-xs text-error-500">{{ errors.price }}</p>
        </div>
        <div>
          <label for="product-article"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Артикул</label>
          <input type="text" id="product-article" :class="inputClass(errors.article)" placeholder="Артикул товара"
            v-model="product.article">
          <p v-if="errors.article" class="mt-1.5 text-theme-xs text-error-500">{{ errors.article }}</p>
        </div>
        <div>
          <label for="product-product_code"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Код товара</label>
          <input type="text" id="product-product_code" :class="inputClass(errors.productCode)" placeholder="Код товара"
            v-model="product.product_code">
          <p v-if="errors.productCode" class="mt-1.5 text-theme-xs text-error-500">{{ errors.productCode }}</p>
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
                <span :class="product.unit === 'шт' ? '' : 'opacity-0'" class="h-2 w-2 rounded-full bg-white"></span>
              </div>
              шт.
            </label>
            <label for="product-unit2"
              class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
              <input id="product-unit2" type="radio" v-model="product.unit" value="кв.м" class="sr-only" />
              <div
                :class="product.unit === 'кв.м' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                <span :class="product.unit === 'кв.м' ? '' : 'opacity-0'" class="h-2 w-2 rounded-full bg-white"></span>
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
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Описание</label>
          <textarea id="product-description" placeholder="Полное описание товара" type="text" rows="7"
            v-model="product.description" :class="textareaClass(errors.description)"></textarea>
          <p v-if="errors.description" class="mt-1.5 text-theme-xs text-error-500">{{ errors.description }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, type Ref } from 'vue'
import Checkbox from '@/components/ui/Checkbox.vue'

interface Product {
  name: string
  country: string
  category_id: string
  brand_id: string
  color_id: string
  price: number
  article: string
  product_code: string
  unit: string
  is_published: boolean
  is_sale: boolean
  description: string
}

interface Errors {
  name?: string
  country?: string
  category?: string
  brand?: string
  color?: string
  price?: string
  article?: string
  productCode?: string
  description?: string
}

interface Category {
  id: string
  value: number | null
  label: string
}

interface Brand {
  id: number
  name: string
}

interface Color {
  id: number
  name: string
}

defineProps<{
  product: Product
  errors: any
  categories: Category[]
  brands: Brand[]
  colors: Color[]
}>()

const textareaClass = (error?: string) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 w-full resize-none rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full resize-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
}

const inputClass = (error?: string) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}
</script>
