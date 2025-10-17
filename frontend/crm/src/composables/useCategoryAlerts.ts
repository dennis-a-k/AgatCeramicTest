import { ref } from 'vue'

interface Alert {
  show: boolean
  type: 'success' | 'error' | 'warning' | 'info'
  title: string
  message: string
}

export function useCategoryAlerts() {
  const alerts = ref<Alert[]>([])

  const showAlert = (type: Alert['type'], title: string, message: string) => {
    const newAlert: Alert = { show: true, type, title, message }
    alerts.value.push(newAlert)
    setTimeout(() => {
      const index = alerts.value.indexOf(newAlert)
      if (index > -1) alerts.value.splice(index, 1)
    }, 3000)
  }

  const clearAlerts = () => {
    alerts.value = []
  }

  return {
    alerts,
    showAlert,
    clearAlerts
  }
}
