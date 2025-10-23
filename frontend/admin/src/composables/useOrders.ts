import { ref, computed, watch } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

interface CategoryItem {
  id: string
  value: number | null
  label: string
}

export function useOrders() {
  const orders = ref([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const page = ref(1)
  const itemsPerPage = ref(50)
  const totalItems = ref(0)
  const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value))

  const searchQuery = ref('')
  const selectedCategory = ref<CategoryItem | null>(null)
  const filters = ref({
    sale: { true: true, false: true } as Record<string, boolean>,
    published: { true: true, false: true } as Record<string, boolean>,
  })

  const visiblePages = computed(() => {
    const pages: (number | string)[] = []
    const total = totalPages.value
    const current = page.value
    const delta = 2
    const range: number[] = []
    const rangeWithDots: (number | string)[] = []

    for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current + delta); i++) {
      range.push(i)
    }

    if (current - delta > 2) {
      rangeWithDots.push(1, '...')
    } else {
      rangeWithDots.push(1)
    }

    rangeWithDots.push(...range)

    if (current + delta < total - 1) {
      rangeWithDots.push('...', total)
    } else if (total > 1) {
      rangeWithDots.push(total)
    }

    return rangeWithDots
  })

  const formatter = new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
  })

  const fetchOrders = async () => {
    loading.value = true
    error.value = null

    const params = new URLSearchParams({
      page: page.value.toString(),
      per_page: itemsPerPage.value.toString(),
    })

    if (selectedCategory.value) {
      params.append('category_id', selectedCategory.value.value!.toString())
    }
    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }

    // Фильтры
    const saleFilters = Object.keys(filters.value.sale).filter((key) => filters.value.sale[key])
    if (saleFilters.length > 0) {
      saleFilters.forEach((val) => params.append('is_sale[]', val))
    }

    const publishedFilters = Object.keys(filters.value.published).filter(
      (key) => filters.value.published[key],
    )
    if (publishedFilters.length > 0) {
      publishedFilters.forEach((val) => params.append('is_published[]', val))
    }

    const url = `${API_BASE_URL}/api/orders?${params.toString()}`

    try {
      const response = await fetch(url)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      orders.value = data.orders.data
      totalItems.value = data.orders.total
    } catch (err) {
      error.value = (err as Error).message
      console.error('Ошибка загрузки заказов:', err)
    } finally {
      loading.value = false
    }
  }


  const handlePrevPage = () => {
    if (page.value > 1) {
      page.value--
      fetchOrders()
    }
  }

  const handleNextPage = () => {
    if (page.value < totalPages.value) {
      page.value++
      fetchOrders()
    }
  }

  const handleGoToPage = (n: number) => {
    page.value = n
    fetchOrders()
  }

  const deleteOrder = async (id: number): Promise<boolean> => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/orders/${id}`, {
        method: 'DELETE',
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      return true
    } catch (err) {
      console.error('Ошибка удаления заказа:', err)
      return false
    }
  }

  const getOrder = async (id: number) => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/orders/${id}`)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      return data.order
    } catch (err) {
      console.error('Ошибка загрузки заказа:', err)
      throw err
    }
  }

  const updateOrder = async (
    id: number,
    orderData: any,
  ): Promise<{ success: boolean; errors?: Record<string, string[]> }> => {
    try {
      const formData = new FormData()

      // Добавляем все поля заказа
      Object.keys(orderData).forEach((key) => {
        if (typeof orderData[key] === 'boolean') {
          formData.append(key, orderData[key] ? '1' : '0')
        } else {
          formData.append(key, orderData[key]?.toString() || '')
        }
      })

      // Добавляем _method для Laravel
      formData.append('_method', 'PUT')

      const response = await fetch(`${API_BASE_URL}/api/orders/${id}`, {
        method: 'POST', // Используем POST с _method=PUT для FormData
        headers: {
          Accept: 'application/json',
        },
        body: formData,
      })

      if (response.ok) {
        return { success: true }
      }

      if (response.status === 422) {
        const errorData = await response.json()
        return { success: false, errors: errorData.errors }
      }

      throw new Error(`HTTP error! status: ${response.status}`)
    } catch (err) {
      console.error('Ошибка обновления заказа:', err)
      return { success: false }
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
      fetchOrders()
    }, 500)
  })

  watch(
    () => filters.value,
    () => {
      resetPage()
      fetchOrders()
    },
    { deep: true },
  )

  watch(selectedCategory, () => {
    resetPage()
    fetchOrders()
  })

  const createOrder = async (
    orderData: any,
  ): Promise<{ success: boolean; errors?: Record<string, string[]> }> => {
    try {
      const formData = new FormData()

      // Добавляем все поля заказа
      Object.keys(orderData).forEach((key) => {
        if (typeof orderData[key] === 'boolean') {
          formData.append(key, orderData[key] ? '1' : '0')
        } else {
          formData.append(key, orderData[key]?.toString() || '')
        }
      })

      const response = await fetch(`${API_BASE_URL}/api/orders`, {
        method: 'POST',
        headers: {
          Accept: 'application/json',
        },
        body: formData,
      })

      if (response.ok) {
        return { success: true }
      }

      if (response.status === 422) {
        const errorData = await response.json()
        return { success: false, errors: errorData.errors }
      }

      throw new Error(`HTTP error! status: ${response.status}`)
    } catch (err) {
      console.error('Ошибка создания заказа:', err)
      return { success: false }
    }
  }

  return {
    orders,
    loading,
    error,
    page,
    itemsPerPage,
    totalItems,
    totalPages,
    searchQuery,
    selectedCategory,
    filters,
    visiblePages,
    formatter,
    fetchOrders,
    handlePrevPage,
    handleNextPage,
    handleGoToPage,
    deleteOrder,
    getOrder,
    updateOrder,
    createOrder,
    resetPage,
  }
}
