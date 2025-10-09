import { ref, computed, watch } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

interface CategoryItem {
  id: string
  value: number | null
  label: string
}

export function useGoods() {
  const products = ref([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const sort = ref<{ key: string | null, asc: boolean }>({ key: null, asc: true })
  const page = ref(1)
  const itemsPerPage = ref(50)
  const totalItems = ref(0)
  const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))

  const searchQuery = ref('')
  const selectedCategory = ref<CategoryItem | null>(null)
  const filters = ref({
    sale: { true: true, false: true } as Record<string, boolean>,
    published: { true: true, false: true } as Record<string, boolean>
  })

  const visiblePages = computed(() => {
    const pages: (number | string)[] = [];
    const total = totalPages.value;
    const current = page.value;
    const delta = 2;
    const range: number[] = [];
    const rangeWithDots: (number | string)[] = [];

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

  const fetchProducts = async () => {
    loading.value = true
    error.value = null

    const params = new URLSearchParams({
      page: page.value.toString(),
      per_page: itemsPerPage.value.toString()
    })

    if (sort.value.key) {
      params.append('sort_key', sort.value.key)
      params.append('sort_direction', sort.value.asc ? 'asc' : 'desc')
    }
    if (selectedCategory.value) {
      params.append('category_id', selectedCategory.value.value!.toString())
    }
    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }

    // Фильтры
    const saleFilters = Object.keys(filters.value.sale).filter(key => filters.value.sale[key])
    if (saleFilters.length > 0) {
      saleFilters.forEach(val => params.append('is_sale[]', val))
    }

    const publishedFilters = Object.keys(filters.value.published).filter(key => filters.value.published[key])
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
      error.value = (err as Error).message
      console.error('Ошибка загрузки товаров:', err)
    } finally {
      loading.value = false
    }
  }

  const handleSortBy = (key: string) => {
    if (sort.value.key === key) {
      sort.value.asc = !sort.value.asc
    } else {
      sort.value.key = key
      sort.value.asc = true
    }
    fetchProducts()
  }

  const handlePrevPage = () => {
    if (page.value > 1) {
      page.value--
      fetchProducts()
    }
  }

  const handleNextPage = () => {
    if (page.value < totalPages.value) {
      page.value++
      fetchProducts()
    }
  }

  const handleGoToPage = (n: number) => {
    page.value = n
    fetchProducts()
  }

  const deleteProduct = async (id: number): Promise<boolean> => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/products/${id}`, {
        method: 'DELETE',
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      return true
    } catch (err) {
      console.error('Ошибка удаления товара:', err)
      return false
    }
  }

  const resetPage = () => {
    page.value = 1
  }

  // Watchers
  let searchTimeout: number | null = null
  watch(searchQuery, (newQuery) => {
    if (searchTimeout) clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
      resetPage()
      fetchProducts()
    }, 500)
  })

  watch(() => filters.value, () => {
    resetPage()
    fetchProducts()
  }, { deep: true })

  watch(selectedCategory, () => {
    resetPage()
    fetchProducts()
  })

  return {
    products,
    loading,
    error,
    sort,
    page,
    itemsPerPage,
    totalItems,
    totalPages,
    searchQuery,
    selectedCategory,
    filters,
    visiblePages,
    formatter,
    fetchProducts,
    handleSortBy,
    handlePrevPage,
    handleNextPage,
    handleGoToPage,
    deleteProduct,
    resetPage
  }
}
