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
                Скачать товары
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
              <div class="flex">
                <div class="relative flex-1 sm:flex-auto mr-3">
                  <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                    <component :is="searchIcon" />
                  </span>
                  <input type="text" placeholder="Поиск товара"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden sm:w-[300px] sm:min-w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                </div>

                <div class="relative w-60" ref="multiSelectRef">
                  <div @click="toggleDropdown"
                    class="dark:bg-dark-900 h-11 flex items-center w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                    :class="{ 'text-gray-800 dark:text-white/90': isOpen }">
                    <span v-if="selectedItems.length === 0" class="text-gray-400"> Категория </span>
                    <div class="flex flex-wrap items-center flex-auto gap-2">
                      <div v-for="item in selectedItems" :key="item.value"
                        class="group flex items-center justify-center h-[30px] rounded-full border-[0.7px] border-transparent bg-gray-100 py-1 pl-2.5 pr-2 text-sm text-gray-800 hover:border-gray-200 dark:bg-gray-800 dark:text-white/90 dark:hover:border-gray-800">
                        <span>{{ item.label }}</span>
                        <button @click.stop="removeItem(item)"
                          class="pl-2 text-gray-500 cursor-pointer group-hover:text-gray-400 dark:text-gray-400"
                          aria-label="Remove item">
                          <svg role="button" width="14" height="14" viewBox="0 0 14 14" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M3.40717 4.46881C3.11428 4.17591 3.11428 3.70104 3.40717 3.40815C3.70006 3.11525 4.17494 3.11525 4.46783 3.40815L6.99943 5.93975L9.53095 3.40822C9.82385 3.11533 10.2987 3.11533 10.5916 3.40822C10.8845 3.70112 10.8845 4.17599 10.5916 4.46888L8.06009 7.00041L10.5916 9.53193C10.8845 9.82482 10.8845 10.2997 10.5916 10.5926C10.2987 10.8855 9.82385 10.8855 9.53095 10.5926L6.99943 8.06107L4.46783 10.5927C4.17494 10.8856 3.70006 10.8856 3.40717 10.5927C3.11428 10.2998 3.11428 9.8249 3.40717 9.53201L5.93877 7.00041L3.40717 4.46881Z"
                              fill="currentColor" />
                          </svg>
                        </button>
                      </div>
                    </div>
                    <svg class="ml-auto" :class="{ 'transform rotate-180': isOpen }" width="20" height="20"
                      viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M4.79175 7.39551L10.0001 12.6038L15.2084 7.39551" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </div>
                  <transition enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
                    <div v-if="isOpen" class="absolute z-10 w-full mt-1 bg-white rounded-lg shadow-sm dark:bg-gray-900">
                      <ul
                        class="overflow-y-auto divide-y divide-gray-200 custom-scrollbar max-h-60 dark:divide-gray-800"
                        role="listbox" aria-multiselectable="true">
                        <li v-for="item in options" :key="item.value" @click="toggleItem(item)"
                          class="relative flex items-center w-full px-3 py-2 border-transparent cursor-pointer first:rounded-t-lg last:rounded-b-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800"
                          :class="{ 'bg-gray-50 dark:bg-white/[0.03]': isSelected(item) }" role="option"
                          :aria-selected="isSelected(item)">
                          <span class="grow">{{ item.label }}</span>
                          <svg v-if="isSelected(item)" class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                          </svg>
                        </li>
                      </ul>
                    </div>
                  </transition>
                </div>

              </div>
              <div class="relative" @click="showFilter = false">
                <button
                  class="shadow-theme-xs flex h-11 w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 sm:w-auto sm:min-w-[100px] dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
                  @click.stop="showFilter = !showFilter" type="button">
                  <component :is="settingsIcon" />
                  Фильтр
                </button>
                <div v-show="showFilter" @click.stop
                  class="absolute right-0 z-10 mt-2 w-45 rounded-lg border border-gray-200 bg-white p-4 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                  <div class="mb-5">
                    <div>
                      <label for="checkboxLabelSale"
                        class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                        <div class="relative">
                          <input type="checkbox" id="checkboxLabelSale" v-model="checkboxSale" class="sr-only" />
                          <div :class="checkboxSale
                            ? 'border-brand-500 bg-brand-500'
                            : 'bg-transparent border-gray-300 dark:border-gray-700'
                            "
                            class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500">
                            <span :class="checkboxSale ? '' : 'opacity-0'">
                              <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
                                  stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                            </span>
                          </div>
                        </div>
                        Распродажа
                      </label>
                    </div>
                  </div>
                  <div class="mb-5">
                    <div>
                      <label for="checkboxLabelNoSale"
                        class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                        <div class="relative">
                          <input type="checkbox" id="checkboxLabelNoSale" v-model="checkboxNoSale" class="sr-only" />
                          <div :class="checkboxNoSale
                            ? 'border-brand-500 bg-brand-500'
                            : 'bg-transparent border-gray-300 dark:border-gray-700'
                            "
                            class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500">
                            <span :class="checkboxNoSale ? '' : 'opacity-0'">
                              <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
                                  stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                            </span>
                          </div>
                        </div>
                        Не распродажа
                      </label>
                    </div>
                  </div>
                  <div class="mb-5">
                    <div>
                      <label for="checkboxLabelPublished"
                        class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                        <div class="relative">
                          <input type="checkbox" id="checkboxLabelPublished" v-model="checkboxPublished"
                            class="sr-only" />
                          <div :class="checkboxPublished
                            ? 'border-brand-500 bg-brand-500'
                            : 'bg-transparent border-gray-300 dark:border-gray-700'
                            "
                            class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500">
                            <span :class="checkboxPublished ? '' : 'opacity-0'">
                              <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
                                  stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                            </span>
                          </div>
                        </div>
                        Опубликован
                      </label>
                    </div>
                  </div>
                  <div>
                    <label for="checkboxLabelNoPublished"
                      class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                      <div class="relative">
                        <input type="checkbox" id="checkboxLabelNoPublished" v-model="checkboxNoPublished"
                          class="sr-only" />
                        <div :class="checkboxNoPublished
                          ? 'border-brand-500 bg-brand-500'
                          : 'bg-transparent border-gray-300 dark:border-gray-700'
                          "
                          class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500">
                          <span :class="checkboxNoPublished ? '' : 'opacity-0'">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
                                stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                          </span>
                        </div>
                      </div>
                      Не опубликован
                    </label>
                  </div>
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
import { ref, onMounted, onBeforeUnmount } from 'vue'
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
const checkboxSale = ref(true)
const checkboxNoSale = ref(true)
const checkboxPublished = ref(true)
const checkboxNoPublished = ref(true)

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
    console.error('Ошибка загруки товаров:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProducts()
})

// Мультиселект
const options = [
  { value: 'apple', label: 'Apple' },
  { value: 'banana', label: 'Banana' },
  { value: 'cherry', label: 'Cherry' },
  { value: 'date', label: 'Date' },
  { value: 'elderberry', label: 'Elderberry' },
  { value: 'graphs', label: 'Graphs' },
]

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['update:modelValue'])

const isOpen = ref(false)
const selectedItems = ref(props.modelValue)
const multiSelectRef = ref(null)

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

const toggleItem = (item) => {
  const index = selectedItems.value.findIndex((selected) => selected.value === item.value)
  if (index === -1) {
    selectedItems.value.push(item)
  } else {
    selectedItems.value.splice(index, 1)
  }
  emit('update:modelValue', selectedItems.value)
}

const removeItem = (item) => {
  const index = selectedItems.value.findIndex((selected) => selected.value === item.value)
  if (index !== -1) {
    selectedItems.value.splice(index, 1)
    emit('update:modelValue', selectedItems.value)
  }
}

const isSelected = (item) => {
  return selectedItems.value.some((selected) => selected.value === item.value)
}

const handleClickOutside = (event) => {
  if (multiSelectRef.value && !multiSelectRef.value.contains(event.target)) {
    isOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
