import { ref, watch } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useColors() {
  const colors = ref([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const searchQuery = ref('')
  const currentPage = ref(1)
  const perPage = ref(5)
  const totalPages = ref(1)
  const totalItems = ref(0)

  const fetchColors = async (page = 1) => {
    loading.value = true
    error.value = null

    const params = new URLSearchParams()
    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }
    params.append('page', page.toString())
    params.append('per_page', perPage.value.toString())

    const url = `${API_BASE_URL}/api/colors${params.toString() ? '?' + params.toString() : ''}`

    try {
      const response = await fetch(url)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      colors.value = data.data || []
      totalPages.value = data.last_page || 1
      totalItems.value = data.total || 0
      currentPage.value = data.current_page || 1
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching colors:', err)
    } finally {
      loading.value = false
    }
  }

  const createColor = async (colorData: { name: string; hex: string }) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/colors`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(colorData),
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      await fetchColors(currentPage.value)
      return data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error creating color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateColor = async (id: number, colorData: { name: string; hex: string }) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/colors/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(colorData),
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      await fetchColors(currentPage.value)
      return data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error updating color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteColor = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/colors/${id}`, {
        method: 'DELETE',
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      await fetchColors(currentPage.value)
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error deleting color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const getColorById = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/colors/${id}`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      return data.color || data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  // Watchers
  let searchTimeout: number | null = null
  watch(searchQuery, (newQuery) => {
    if (searchTimeout) clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
      fetchColors()
    }, 500)
  })

  return {
    colors,
    loading,
    error,
    searchQuery,
    currentPage,
    perPage,
    totalPages,
    totalItems,
    fetchColors,
    createColor,
    updateColor,
    deleteColor,
    getColorById
  }
}
