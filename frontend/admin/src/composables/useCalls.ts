import { ref, computed, watch } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

interface StatusItem {
  id: string
  value: string | null
  label: string
}

export function useCalls() {
  const calls = ref([])
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
    { id: 'processed', value: 'processed', label: 'Обработан' },
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

  const fetchCalls = async () => {
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

    const url = `${API_BASE_URL}/api/call-requests?${params.toString()}`

    try {
      const response = await fetch(url)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      calls.value = data.call_requests.data
      totalItems.value = data.call_requests.total
    } catch (err) {
      error.value = (err as Error).message
      console.error('Ошибка загрузки заявок на звонок:', err)
    } finally {
      loading.value = false
    }
  }

  const handlePrevPage = () => {
    if (page.value > 1) {
      page.value--
      fetchCalls()
    }
  }

  const handleNextPage = () => {
    if (page.value < totalPages.value) {
      page.value++
      fetchCalls()
    }
  }

  const handleGoToPage = (n: number) => {
    page.value = n
    fetchCalls()
  }

  const getCall = async (id: number) => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/call-requests/${id}`)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      return data.call_request
    } catch (err) {
      console.error('Ошибка загрузки заявки на звонок:', err)
      throw err
    }
  }

  const updateCall = async (
    id: number,
    callData: any,
  ): Promise<{ success: boolean; errors?: Record<string, string[]> }> => {
    try {
      const formData = new FormData()

      // Добавляем все поля заявки
      Object.keys(callData).forEach((key) => {
        if (typeof callData[key] === 'boolean') {
          formData.append(key, callData[key] ? '1' : '0')
        } else {
          formData.append(key, callData[key]?.toString() || '')
        }
      })

      // Добавляем _method для Laravel
      formData.append('_method', 'PUT')

      const response = await fetch(`${API_BASE_URL}/api/call-requests/${id}`, {
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
      console.error('Ошибка обновления заявки на звонок:', err)
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
      fetchCalls()
    }, 500)
  })

  watch(
    () => filters.value,
    () => {
      resetPage()
      fetchCalls()
    },
    { deep: true },
  )

  watch(selectedStatus, () => {
    resetPage()
    fetchCalls()
  })

  const fetchCallStatistics = async (month?: string) => {
    try {
      const params = new URLSearchParams()
      if (month) {
        params.append('month', month)
      }
      const url = `${API_BASE_URL}/api/call-requests/statistics${params.toString() ? '?' + params.toString() : ''}`

      const response = await fetch(url)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      return data.statistics
    } catch (err) {
      console.error('Ошибка загрузки статистики заявок на звонок:', err)
      throw err
    }
  }

  const fetchCallById = async (id: string | number) => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/call-requests/${id}`)

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      return { success: true, data: data.call_request }
    } catch (err) {
      console.error('Ошибка загрузки заявки на звонок:', err)
      return { success: false, errors: (err as Error).message }
    }
  }

  return {
    calls,
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
    fetchCalls,
    fetchCallStatistics,
    fetchCallById,
    handlePrevPage,
    handleNextPage,
    handleGoToPage,
    getCall,
    updateCall,
    resetPage,
  }
}
