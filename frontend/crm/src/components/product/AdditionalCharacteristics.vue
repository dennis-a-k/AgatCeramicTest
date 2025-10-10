<template>
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
      <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div v-for="attr in product.attribute_values" :key="attr.id">
          <div v-if="attr.attribute.type === 'string'">
            <label :for="`attr-string${attr.id}`" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              {{ attr.attribute.name }}
            </label>
            <input :id="`attr-string${attr.id}`" type="text" v-model="attr.string_value" :class="inputClass">
            <p v-if="attr.error" class="mt-1.5 text-theme-xs text-error-500">{{ attr.error }}</p>
          </div>
          <div v-else-if="attr.attribute.type === 'number'">
            <label :for="`attr-number${attr.id}`" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              {{ attr.attribute.name }}
            </label>
            <input :id="`attr-number${attr.id}`" type="number" step="0.01" min="0" v-model.number="attr.number_value" :class="inputClass">
          </div>
          <div v-else-if="attr.attribute.type === 'boolean'" class="h-full flex">
            <Checkbox :id="'attr-' + attr.id" :label="attr.attribute.name" v-model:checked="attr.boolean_value" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Checkbox from '@/components/ui/Checkbox.vue'

interface AttributeValue {
  id: number
  string_value?: string
  number_value?: number
  boolean_value?: boolean
  error?: string
  attribute: {
    type: 'string' | 'number' | 'boolean'
    name: string
  }
}

interface Product {
  attribute_values: AttributeValue[]
}

defineProps<{
  product: Product
}>()

const inputClass = 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
</script>
