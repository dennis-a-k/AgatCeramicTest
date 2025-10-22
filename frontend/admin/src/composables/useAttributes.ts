import { ref, watch } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useAttributes() {
  const attributes = ref([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const searchQuery = ref('')
  const currentPage = ref(1)
  const perPage = ref(5)
  const totalPages = ref(1)
  const totalItems = ref(0)

  const fetchAttributes = async (page = 1) => {
    loading.value = true
    error.value = null

    const params = new URLSearchParams()
    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }
    params.append('page', page.toString())
    params.append('per_page', perPage.value.toString())

    const url = `${API_BASE_URL}/api/attributes${params.toString() ? '?' + params.toString() : ''}`

    try {
      const response = await fetch(url)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      attributes.value = data.data || []
      totalPages.value = data.last_page || 1
      totalItems.value = data.total || 0
      currentPage.value = data.current_page || 1
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching attributes:', err)
    } finally {
      loading.value = false
    }
  }

  const createAttribute = async (attributeData: { name: string; type: string; unit?: string }) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/attributes`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(attributeData),
      })
      const data = await response.json()
      if (!response.ok) {
        if (response.status === 422) {
          return { success: false, errors: data.errors, message: data.message }
        }
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      await fetchAttributes(currentPage.value)
      return { success: true, data }
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error creating attribute:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateAttribute = async (id: number, attributeData: { name: string; type: string; unit?: string }) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/attributes/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(attributeData),
      })
      const data = await response.json()
      if (!response.ok) {
        if (response.status === 422) {
          return { success: false, errors: data.errors, message: data.message }
        }
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      await fetchAttributes(currentPage.value)
      return { success: true, data }
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error updating attribute:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteAttribute = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/attributes/${id}`, {
        method: 'DELETE',
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      await fetchAttributes(currentPage.value)
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error deleting attribute:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const getAttributeById = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/attributes/${id}`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      return data.attribute || data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching attribute:', err)
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
      fetchAttributes()
    }, 500)
  })

  return {
    attributes,
    loading,
    error,
    searchQuery,
    currentPage,
    perPage,
    totalPages,
    totalItems,
    fetchAttributes,
    createAttribute,
    updateAttribute,
    deleteAttribute,
    getAttributeById
  }
}
