import { ref } from 'vue'

export function useBrandAlerts() {
  const alert = ref({
    show: false,
    type: '',
    title: '',
    message: ''
  })

  const showAlert = (type: string, title: string, message: string) => {
    alert.value = {
      show: true,
      type,
      title,
      message
    }
    setTimeout(() => {
      alert.value.show = false
    }, 3000)
  }

  return {
    alert,
    showAlert
  }
}
