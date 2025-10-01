import { createToastInterface } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

export default defineNuxtPlugin((nuxtApp) => {
  const toast = createToastInterface({
    // Позиция: 'top-right', 'top-left', 'top-center', 'bottom-right', 'bottom-left', 'bottom-center'
    position: 'top-right',

    // Время показа в миллисекундах
    timeout: 3000,

    // Закрывать при клике
    closeOnClick: true,

    // Приостанавливать при потере фокуса
    pauseOnFocusLoss: true,

    // Приостанавливать при наведении
    pauseOnHover: true,

    // Перетаскивание
    draggable: true,
    draggablePercent: 0.6,

    // Показывать кнопку закрытия при наведении
    showCloseButtonOnHover: true,

    // Скрывать прогресс-бар
    hideProgressBar: true,

    // Тип кнопки закрытия
    closeButton: 'button',

    // Показывать иконки
    icon: false,

    // RTL поддержка
    rtl: false,

    // Кастомные CSS классы для разных типов
    toastClassName: 'custom-toast',
    bodyClassName: 'custom-toast-body',
    progressClassName: 'custom-toast-progress',

    // Максимальное количество одновременных уведомлений
    maxToasts: 5,

    // Новые уведомления появляются сверху
    newestOnTop: true
  })

  return {
    provide: {
      toast
    }
  }
})