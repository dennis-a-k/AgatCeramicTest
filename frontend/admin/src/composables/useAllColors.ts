import { ref } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useAllColors() {
  const colors = ref([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchAllColors = async () => {
    loading.value = true
    error.value = null

    const params = new URLSearchParams()
    params.append('per_page', 'all')

    const url = `${API_BASE_URL}/api/colors${params.toString() ? '?' + params.toString() : ''}`

    try {
      const response = await fetch(url, {
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        }
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      colors.value = data.data || []
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching all colors:', err)
    } finally {
      loading.value = false
    }
  }

  return {
    colors,
    loading,
    error,
    fetchAllColors,
  }
}
