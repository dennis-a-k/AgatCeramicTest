<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div class="space-y-5 sm:space-y-6">
      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <GoodsHeader
          :totalItems="totalItems"
          :packageIcon="packageIcon"
          :downloadIcon="downloadIcon"
          :plusIcon="plusIcon"
        />
        <GoodsFilters
          :searchQuery="searchQuery"
          :categories="categories"
          :selectedItem="selectedItem"
          :isOpen="isOpen"
          :showFilter="showFilter"
          :checkboxSale="checkboxSale"
          :checkboxNoSale="checkboxNoSale"
          :checkboxPublished="checkboxPublished"
          :checkboxNoPublished="checkboxNoPublished"
          :searchIcon="searchIcon"
          :settingsIcon="settingsIcon"
          @update:searchQuery="searchQuery = $event"
          @toggleDropdown="toggleDropdown"
          @toggleItem="handleToggleItem"
          @update:showFilter="showFilter = $event"
          @update:checkboxSale="checkboxSale = $event"
          @update:checkboxNoSale="checkboxNoSale = $event"
          @update:checkboxPublished="checkboxPublished = $event"
          @update:checkboxNoPublished="checkboxNoPublished = $event"
        />
        <GoodsTable
          :loading="loading"
          :error="error"
          :products="products"
          :formatter="formatter"
          :sort="sort"
          @sortBy="handleSortBy"
          @fetchProducts="fetchProducts"
          @edit="handleEdit"
          @delete="handleDelete"
        />
        <GoodsPagination
          :page="page"
          :totalPages="totalPages"
          :totalItems="totalItems"
          :itemsPerPage="itemsPerPage"
          :visiblePages="visiblePages"
          @prevPage="handlePrevPage"
          @nextPage="handleNextPage"
          @goToPage="handleGoToPage"
        />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import GoodsHeader from '@/components/goods-page/GoodsHeader.vue'
import GoodsFilters from '@/components/goods-page/GoodsFilters.vue'
import GoodsTable from '@/components/goods-page/GoodsTable.vue'
import GoodsPagination from '@/components/goods-page/GoodsPagination.vue'
import { PackageIcon, DownloadIcon, PlusIcon, Settings2Icon, SearchIcon } from "../../icons";

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL
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

const searchQuery = ref('')

const allCategories = ref([])

const sort = ref({ key: null, asc: true })

const page = ref(1)
const itemsPerPage = ref(50)
const totalItems = ref(0)

const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))

const selectedItem = ref(null)

const visiblePages = computed(() => {
  const pages = [];
  const total = totalPages.value;
  const current = page.value;
  const delta = 2; // количество страниц вокруг текущей
  const range = [];
  const rangeWithDots = [];

  for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current + delta); i++) {
    range.push(i);
  }

  if (current - delta > 2) {
    rangeWithDots.push(1, '...');
  } else {
    rangeWithDots.push(1);
  }

  rangeWithDots.push(...range);

  if (current + delta < total - 1) {
    rangeWithDots.push('...', total);
  } else if (total > 1) {
    rangeWithDots.push(total);
  }

  return rangeWithDots;
});

const formatter = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
});

const handlePrevPage = () => {
  if (page.value > 1) {
    page.value--;
    fetchProducts();
  }
}

const handleNextPage = () => {
  if (page.value < totalPages.value) {
    page.value++;
    fetchProducts();
  }
}

const handleGoToPage = (n) => {
  page.value = n;
  fetchProducts();
}

const handleSortBy = (key) => {
  if (sort.value.key === key) {
    sort.value.asc = !sort.value.asc
  } else {
    sort.value.key = key
    sort.value.asc = true
  }
  fetchProducts()
}

const handleToggleItem = (item) => {
  selectedItem.value = item.value === null ? null : item
  page.value = 1
  isOpen.value = false
  fetchProducts()
}

const handleEdit = (product) => {
  // Handle edit logic here
  console.log('Edit product:', product)
}

const handleDelete = (product) => {
  // Handle delete logic here
  console.log('Delete product:', product)
}

const fetchCategories = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/categories`)
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    const data = await response.json()
    allCategories.value = data.categories || data
  } catch (err) {
    console.error('Ошибка загрузки категорий:', err)
  }
}

const fetchProducts = async () => {
  loading.value = true
  error.value = null

  const params = new URLSearchParams({
    page: page.value,
    per_page: itemsPerPage.value
  })

  if (sort.value.key) {
    params.append('sort_key', sort.value.key)
    params.append('sort_direction', sort.value.asc ? 'asc' : 'desc')
  }
  if (selectedItem.value) {
    params.append('category_id', selectedItem.value.value)
  }
  if (searchQuery.value.trim()) {
    params.append('search', searchQuery.value.trim())
  }

  // Фильтры
  const saleFilters = []
  if (checkboxSale.value) saleFilters.push('true')
  if (checkboxNoSale.value) saleFilters.push('false')
  if (saleFilters.length > 0) {
    saleFilters.forEach(val => params.append('is_sale[]', val))
  }

  const publishedFilters = []
  if (checkboxPublished.value) publishedFilters.push('true')
  if (checkboxNoPublished.value) publishedFilters.push('false')
  if (publishedFilters.length > 0) {
    publishedFilters.forEach(val => params.append('is_published[]', val))
  }

  const url = `${API_BASE_URL}/api/products?${params.toString()}`

  try {
    const response = await fetch(url)

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    products.value = data.products.data
    totalItems.value = data.products.total
  } catch (err) {
    error.value = err.message
    console.error('Ошибка загруки товаров:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchCategories()
  fetchProducts()
})

let searchTimeout = null
watch(searchQuery, (newQuery) => {
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    page.value = 1
    fetchProducts()
  }, 500) // debounce 500ms
})

watch(checkboxSale, () => {
  page.value = 1
  fetchProducts()
})

watch(checkboxNoSale, () => {
  page.value = 1
  fetchProducts()
})

watch(checkboxPublished, () => {
  page.value = 1
  fetchProducts()
})

watch(checkboxNoPublished, () => {
  page.value = 1
  fetchProducts()
})

// Функция для построения плоского списка категорий с дочерними
const buildCategoryList = (categories, level = 0) => {
  const result = []
  categories.forEach((cat, index) => {
    const indent = '  '.repeat(level) // отступ для дочерних
    result.push({
      id: `${level}-${index + 2}`,
      value: cat.id,
      label: `${indent}${cat.name}`
    })
    if (cat.children && cat.children.length > 0) {
      result.push(...buildCategoryList(cat.children, level + 1))
    }
  })
  return result
}

// Селект
const categories = computed(() => {
  const cats = buildCategoryList(allCategories.value)
  return [{ id: '1', value: null, label: 'Все категории' }, ...cats]
})

const isOpen = ref(false)

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}
</script>
