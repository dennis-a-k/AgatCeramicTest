import { ref, watch } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useBrands() {
  const brands = ref([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const searchQuery = ref('')
  const currentPage = ref(1)
  const perPage = ref(5)
  const totalPages = ref(1)
  const totalItems = ref(0)

  const fetchBrands = async (page = 1) => {
    loading.value = true
    error.value = null

    const params = new URLSearchParams()
    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }
    params.append('page', page.toString())
    params.append('per_page', perPage.value.toString())

    const url = `${API_BASE_URL}/api/brands${params.toString() ? '?' + params.toString() : ''}`

    try {
      const response = await fetch(url)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      brands.value = data.data || []
      totalPages.value = data.last_page || 1
      totalItems.value = data.total || 0
      currentPage.value = data.current_page || 1
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching brands:', err)
    } finally {
      loading.value = false
    }
  }

  const createBrand = async (brandData: { name: string; country?: string; description?: string; is_active: boolean; image?: string }) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/brands`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(brandData),
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      await fetchBrands(currentPage.value)
      return data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error creating brand:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateBrand = async (id: number, brandData: { name: string; country?: string; description?: string; is_active: boolean; image?: string }) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/brands/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(brandData),
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      await fetchBrands(currentPage.value)
      return data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error updating brand:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteBrand = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/brands/${id}`, {
        method: 'DELETE',
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      await fetchBrands(currentPage.value)
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error deleting brand:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const getBrandById = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/brands/${id}`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      return data.brand || data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching brand:', err)
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
      fetchBrands()
    }, 500)
  })

  return {
    brands,
    loading,
    error,
    searchQuery,
    currentPage,
    perPage,
    totalPages,
    totalItems,
    fetchBrands,
    createBrand,
    updateBrand,
    deleteBrand,
    getBrandById
  }
}
