import { ref, computed, watch } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

interface StatusItem {
  id: string
  value: string | null
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
  const selectedStatus = ref<StatusItem | null>(null)
  const statuses = ref<StatusItem[]>([
    { id: 'all', value: null, label: 'Все статусы' },
    { id: 'pending', value: 'pending', label: 'Новый' },
    { id: 'processing', value: 'processing', label: 'Выполняется' },
    { id: 'shipped', value: 'shipped', label: 'Отправлен' },
    { id: 'return', value: 'return', label: 'Возврат' },
    { id: 'cancelled', value: 'cancelled', label: 'Отменён' },
  ])
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

    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }

    if (selectedStatus.value && selectedStatus.value.value !== null) {
      params.append('status', selectedStatus.value.value)
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

  watch(selectedStatus, () => {
    resetPage()
    fetchOrders()
  })

  const fetchOrderStatistics = async (month?: string) => {
    try {
      const params = new URLSearchParams()
      if (month) {
        params.append('month', month)
      }
      const url = `${API_BASE_URL}/api/orders/statistics${params.toString() ? '?' + params.toString() : ''}`

      const response = await fetch(url)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      return data.statistics
    } catch (err) {
      console.error('Ошибка загрузки статистики заказов:', err)
      throw err
    }
  }

  const fetchOrderById = async (id: string | number) => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/orders/${id}`)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      return { success: true, data: data.order }
    } catch (err) {
      console.error('Ошибка загрузки заказа:', err)
      return { success: false, errors: (err as Error).message }
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
    selectedStatus,
    statuses,
    filters,
    visiblePages,
    formatter,
    fetchOrders,
    fetchOrderStatistics,
    fetchOrderById,
    handlePrevPage,
    handleNextPage,
    handleGoToPage,
    getOrder,
    updateOrder,
    resetPage,
  }
}
