import { ref, watch } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useColors() {
  const colors = ref([])
  const loading = ref(false)
  const error = ref(null)
  const searchQuery = ref('')

  const fetchColors = async () => {
    loading.value = true
    error.value = null

    const params = new URLSearchParams()
    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }

    const url = `${API_BASE_URL}/api/colors${params.toString() ? '?' + params.toString() : ''}`

    try {
      const response = await fetch(url)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      colors.value = data.colors || data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching colors:', err)
    } finally {
      loading.value = false
    }
  }

  const createColor = async (colorData: any) => {
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
      colors.value.push(data.color || data)
      return data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error creating color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateColor = async (id: any, colorData: any) => {
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
      const index = colors.value.findIndex((color: any) => color.id === id)
      if (index !== -1) {
        colors.value[index] = data.color || data
      }
      return data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error updating color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteColor = async (id: any) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/colors/${id}`, {
        method: 'DELETE',
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      colors.value = colors.value.filter((color: any) => color.id !== id)
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error deleting color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const getColorById = async (id: any) => {
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
    fetchColors,
    createColor,
    updateColor,
    deleteColor,
    getColorById
  }
}
