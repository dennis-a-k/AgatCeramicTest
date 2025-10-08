<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div class="space-y-5 sm:space-y-6">
      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div v-if="loading" class="flex justify-center items-center h-screen">
          <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-brand-500"></div>
        </div>
        <div v-else-if="error"
          class="flex flex-col justify-center items-center h-screen font-bold text-error-700 text-theme-xl dark:text-error-500">
          Ошибка при загрузке<br />
          <button
            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300 mt-2"
            @click="fetchProducts">Попробовать снова</button>
        </div>
        <div v-else-if="products.length === 0"
          class="flex flex-col justify-center items-center h-screen menu-item-icon-active text-center font-bold text-theme-xl m-5">
          Товары не найдены
        </div>
        <div v-else>
          <div
            class="flex flex-col justify-between gap-5 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center dark:border-gray-800">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90 flex flex-row items-center">
              <component :is="packageIcon" class="menu-item-icon-active mr-2" />
              Список товаров (4)
            </h3>
            <div class="flex gap-3">
              <button
                class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                Скачать Excel
                <component :is="downloadIcon" />
              </button>
              <a href="add-product.html"
                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                <component :is="plusIcon" width="20" height="20" />
                Добавить товар
              </a>
            </div>
          </div>
          <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
            <div class="flex gap-3 sm:justify-between">
              <div class="relative flex-1 sm:flex-auto">
                <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                  <component :is="searchIcon" />
                </span>
                <input type="text" placeholder="Поиск товар"
                  class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden sm:w-[300px] sm:min-w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
              </div>
              <div class="relative" @click="showFilter = false">
                <button
                  class="shadow-theme-xs flex h-11 w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 sm:w-auto sm:min-w-[100px] dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                  @click.stop="showFilter = !showFilter" type="button">
                  <component :is="settingsIcon" />
                  Фильтр
                </button>
                <div v-show="showFilter" @click.stop
                  class="absolute right-0 z-10 mt-2 w-56 rounded-lg border border-gray-200 bg-white p-4 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                  <div class="mb-5">
                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-300">
                      Category
                    </label>
                    <input type="text"
                      class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                      placeholder="Search category...">
                  </div>
                  <div class="mb-5">
                    <label class="mb-2 block text-xs font-medium text-gray-700 dark:text-gray-300">
                      Company
                    </label>
                    <input type="text"
                      class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                      placeholder="Search company...">
                  </div>
                  <button
                    class="bg-brand-500 hover:bg-brand-600 h-10 w-full rounded-lg px-3 py-2 text-sm font-medium text-white">
                    Apply
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
            <div class="space-y-5">
              <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="max-w-full overflow-x-auto custom-scrollbar">
                  <table class="min-w-full">
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
                          <p class="text-gray-500 text-center text-theme-sm dark:text-gray-400">
                            {{ product.product_code }}
                          </p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                          <p class="text-gray-500 text-center text-theme-sm dark:text-gray-400">
                            {{ product.category.name }}</p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                          <p class="text-gray-500 text-center text-theme-sm dark:text-gray-400">
                            {{ formatter.format(product.price) }}</p>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                          <p v-if="product.unit === 'шт'"
                            class="text-gray-500 text-center text-theme-sm dark:text-gray-400">шт.</p>
                          <p v-else-if="product.unit === 'кв.м'"
                            class="text-gray-500 text-center text-theme-sm dark:text-gray-400">м²
                          </p>
                          <p v-else class="text-gray-500 text-center text-theme-sm dark:text-gray-400">
                            {{ product.unit }}
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import { PackageIcon, DownloadIcon, PlusIcon, Settings2Icon, SearchIcon } from "../../icons";

const currentPageTitle = ref('Товары')
const packageIcon = PackageIcon
const downloadIcon = DownloadIcon
const plusIcon = PlusIcon
const settingsIcon = Settings2Icon
const searchIcon = SearchIcon

const products = ref([])
const loading = ref(false)
const error = ref(null)
const showFilter = ref(false)

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
