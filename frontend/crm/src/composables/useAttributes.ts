import { ref } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useAttributes() {
  const attributes = ref([])
  const loading = ref(false)
  const error = ref(null)

  const fetchAttributes = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/attributes`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      attributes.value = data.attributes || data
    } catch (err) {
      error.value = err.message
      console.error('Error fetching attributes:', err)
    } finally {
      loading.value = false
    }
  }

  const createAttribute = async (attributeData) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/attributes`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(attributeData),
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      attributes.value.push(data.attribute || data)
      return data
    } catch (err) {
      error.value = err.message
      console.error('Error creating attribute:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateAttribute = async (id, attributeData) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/attributes/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(attributeData),
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      const index = attributes.value.findIndex(attribute => attribute.id === id)
      if (index !== -1) {
        attributes.value[index] = data.attribute || data
      }
      return data
    } catch (err) {
      error.value = err.message
      console.error('Error updating attribute:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteAttribute = async (id) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/attributes/${id}`, {
        method: 'DELETE',
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      attributes.value = attributes.value.filter(attribute => attribute.id !== id)
    } catch (err) {
      error.value = err.message
      console.error('Error deleting attribute:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const getAttributeById = async (id) => {
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
      error.value = err.message
      console.error('Error fetching attribute:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    attributes,
    loading,
    error,
    fetchAttributes,
    createAttribute,
    updateAttribute,
    deleteAttribute,
    getAttributeById
  }
}
