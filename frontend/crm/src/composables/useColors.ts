import { ref } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useColors() {
  const colors = ref([])
  const loading = ref(false)
  const error = ref(null)

  const fetchColors = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/colors`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      colors.value = data.colors || data
    } catch (err) {
      error.value = err.message
      console.error('Error fetching colors:', err)
    } finally {
      loading.value = false
    }
  }

  const createColor = async (colorData) => {
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
      error.value = err.message
      console.error('Error creating color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateColor = async (id, colorData) => {
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
      const index = colors.value.findIndex(color => color.id === id)
      if (index !== -1) {
        colors.value[index] = data.color || data
      }
      return data
    } catch (err) {
      error.value = err.message
      console.error('Error updating color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteColor = async (id) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/colors/${id}`, {
        method: 'DELETE',
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      colors.value = colors.value.filter(color => color.id !== id)
    } catch (err) {
      error.value = err.message
      console.error('Error deleting color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const getColorById = async (id) => {
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
      error.value = err.message
      console.error('Error fetching color:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    colors,
    loading,
    error,
    fetchColors,
    createColor,
    updateColor,
    deleteColor,
    getColorById
  }
}
