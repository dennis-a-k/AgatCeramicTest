import { ref } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useInformation() {
  const information = ref({
    phone: '',
    email: '',
    telegram: '',
    whatsapp: '',
    organization: '',
    adress: '',
    inn: '',
    ogrn: '',
    okato: '',
    okpo: '',
    bank: '',
    bik: '',
    ks: '',
    rs: ''
  })
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchInformation = async () => {
    loading.value = true
    error.value = null

    try {
      const response = await fetch(`${API_BASE_URL}/api/information`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      information.value = data
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching information:', err)
    } finally {
      loading.value = false
    }
  }

  const updateInformation = async (informationData: Partial<typeof information.value>) => {
    loading.value = true
    error.value = null

    try {
      const response = await fetch(`${API_BASE_URL}/api/information`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(informationData),
      })

      if (!response.ok) {
        if (response.status === 422) {
          const errorData = await response.json()
          return { success: false, errors: errorData.errors, message: errorData.message }
        }
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      information.value = data
      return { success: true, data }
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error updating information:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    information,
    loading,
    error,
    fetchInformation,
    updateInformation,
  }
}
