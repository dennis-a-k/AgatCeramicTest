import { ref } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

interface Color {
  id: number
  name: string
  slug: string
  hex: string
}

export function useColors() {
  const colors = ref<Color[]>([])

  const fetchColors = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/colors`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      colors.value = data.colors || data
    } catch (err) {
      console.error('Ошибка загрузки цветов:', err)
    }
  }

  return {
    colors,
    fetchColors
  }
}
