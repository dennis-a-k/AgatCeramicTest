import { ref } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

interface Brand {
  id: number
  name: string
  slug: string
  country: string
  description: string
  is_active: boolean
}

export function useBrands() {
  const brands = ref<Brand[]>([])

  const fetchBrands = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/brands`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      brands.value = data.brands || data
    } catch (err) {
      console.error('Ошибка загрузки брендов:', err)
    }
  }

  return {
    brands,
    fetchBrands
  }
}
