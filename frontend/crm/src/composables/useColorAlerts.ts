import { ref } from 'vue'

interface Alert {
  show: boolean
  type: 'success' | 'error' | 'warning' | 'info'
  title: string
  message: string
}

export function useColorAlerts() {
  const alert = ref<Alert | null>(null)

  const showAlert = (type: Alert['type'], title: string, message: string) => {
    alert.value = { show: true, type, title, message }
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
