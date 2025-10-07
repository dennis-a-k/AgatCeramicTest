<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div class="space-y-5 sm:space-y-6">
      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-5">
          <h3 class="text-base font-medium text-gray-800 dark:text-white/90 flex flex-row items-center">
            <component :is="packageIcon" class="menu-item-icon-active mr-2" />
            Список товаров (4)
          </h3>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
          <div class="space-y-5">
            <div
              class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
              <div class="max-w-full overflow-x-auto custom-scrollbar">
                <div v-if="loading" class="text-center font-bold text-gray-500 text-theme-xl dark:text-gray-400 m-5">Загрузка товаров...</div>
                <div v-else-if="error" class="text-center font-bold text-error-700 text-theme-xl dark:text-error-500 m-5">
                  Ошибка при загрузке<br />
                  <button class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300 mt-2" @click="fetchProducts">Попробовать снова</button>
                </div>
                <div v-else-if="products.length === 0" class="menu-item-icon-active text-center font-bold text-gray-500 text-theme-xl dark:text-gray-400 m-5">
                  Товары не найдены
                </div>
                <table v-else class="min-w-full">
                  <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                      <th class="px-5 py-3 text-center w-1/10 sm:px-6">
                        <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Артикул</p>
                      </th>
                      <th class="px-5 py-3 text-center w-2/10 sm:px-6">
                        <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Наименование</p>
                      </th>
                      <th class="px-5 py-3 text-center w-1/10 sm:px-6">
                        <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Код товара</p>
                      </th>
                      <th class="px-5 py-3 text-center w-1/10 sm:px-6">
                        <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Категория</p>
                      </th>
                      <th class="px-5 py-3 text-center w-1/10 sm:px-6">
                        <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Цена</p>
                      </th>
                      <th class="px-5 py-3 text-center w-1/10 sm:px-6">
                        <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">ед.изм</p>
                      </th>
                      <th class="px-5 py-3 text-center w-1/10 sm:px-6">
                        <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Статус</p>
                      </th>
                      <th class="px-5 py-3 text-center w-1/10 sm:px-6">
                        <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Распродажа</p>
                      </th>
                      <th class="px-5 py-3 text-center w-1/10 sm:px-6">
                        <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Действие</p>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="product in products" :key="product.id"
                      class="border-t border-gray-100 dark:border-gray-800">
                      <td class="px-5 py-4 sm:px-6">
                        <p class="font-medium text-center text-gray-800 text-theme-sm dark:text-white/90">
                          {{ product.article }}
                        </p>
                      </td>
                      <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ product.name }}</p>
                      </td>
                      <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-center text-theme-sm dark:text-gray-400">{{ product.product_code }}
                        </p>
                      </td>
                      <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-center text-theme-sm dark:text-gray-400">{{ product.category.name
                          }}</p>
                      </td>
                      <td class="px-5 py-4 sm:px-6">
                        <p class="text-gray-500 text-center text-theme-sm dark:text-gray-400">{{ formatter.format(product.price) }}</p>
                      </td>
                      <td class="px-5 py-4 sm:px-6">
                        <p v-if="product.unit === 'шт'"
                          class="text-gray-500 text-center text-theme-sm dark:text-gray-400">шт.</p>
                        <p v-else-if="product.unit === 'кв.м'"
                          class="text-gray-500 text-center text-theme-sm dark:text-gray-400">м²
                        </p>
                        <p v-else class="text-gray-500 text-center text-theme-sm dark:text-gray-400">{{ product.unit }}
                        </p>
                      </td>
                      <td class="px-5 py-4 sm:px-6 text-center">
                        <span v-if="product.is_published === true"
                          class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500">
                          Опубликован
                        </span>
                        <span v-if="product.is_published === false"
                          class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80">
                          Скрыт
                        </span>
                      </td>
                      <td class="px-5 py-4 sm:px-6 text-center">
                        <span v-if="product.is_sale === true"
                          class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500">
                          Распродажа
                        </span>
                        <span v-if="product.is_sale === false"
                          class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80">
                          Нет
                        </span>
                      </td>
                      <td class="px-5 py-4 sm:px-6">

                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import { PackageIcon, } from "../../icons";

const currentPageTitle = ref('Товары')
const packageIcon = PackageIcon

const products = ref([])
const loading = ref(false)
const error = ref(null)

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

const formatter = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
});

const fetchProducts = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await fetch(`${API_BASE_URL}/api/products`)

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    products.value = data.products.data
  } catch (err) {
    error.value = err.message
    console.error('Error fetching products:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProducts()
})
</script>
