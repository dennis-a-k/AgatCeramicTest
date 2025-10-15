import { ref } from 'vue'

interface Alert {
  variant: 'success' | 'error' | 'warning' | 'info'
  title: string
  message: string
}

export function useColorAlerts() {
  const alert = ref<Alert | null>(null)

  const showAlert = (variant: Alert['variant'], title: string, message: string) => {
    alert.value = { variant, title, message }
    setTimeout(() => (alert.value = null), 3000)
  }

  const clearAlert = () => {
    alert.value = null
  }

  return {
    alert,
    showAlert,
    clearAlert
  }
}
