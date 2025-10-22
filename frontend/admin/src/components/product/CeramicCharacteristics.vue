<template>
  <div v-if="isCeramicCategory"
    class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
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
          <input type="text" id="product-texture" :class="inputClass(errors.texture)" placeholder="Тип поверхности"
            v-model="product.texture">
          <p v-if="errors.texture" class="mt-1.5 text-theme-xs text-error-500">{{ errors.texture }}</p>
        </div>
        <div>
          <label for="product-pattern"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Рисунок</label>
          <input type="text" id="product-pattern" :class="inputClass(errors.pattern)"
            placeholder="Тип рисунка или монотон" v-model="product.pattern">
          <p v-if="errors.pattern" class="mt-1.5 text-theme-xs text-error-500">{{ errors.pattern }}</p>
        </div>
        <div>
          <label for="product-collection"
            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Коллекция</label>
          <input type="text" id="product-collection" :class="inputClass(errors.collection)"
            placeholder="Коллекция товара" v-model="product.collection">
          <p v-if="errors.collection" class="mt-1.5 text-theme-xs text-error-500">{{ errors.collection }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, type Ref } from 'vue'

interface Product {
  texture: string
  pattern: string
  collection: string
}

interface Errors {
  texture?: string
  pattern?: string
  collection?: string
}

interface Category {
  value: number | null
  label: string
}

defineProps<{
  product: Product
  errors: any
  categories: Category[]
  isCeramicCategory: boolean
}>()

const inputClass = (error?: string) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
}
</script>
