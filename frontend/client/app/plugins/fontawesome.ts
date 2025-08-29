import { library, config } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Импортируем иконки (только те, которые реально используешь — чтобы не грузить лишнее)
import { faEnvelope } from '@fortawesome/free-regular-svg-icons'
import { faMagnifyingGlass, faPhone } from '@fortawesome/free-solid-svg-icons'

// Добавляем иконки в библиотеку
library.add(
  faEnvelope,
  faMagnifyingGlass,
  faPhone,
)

// Опционально: настройка Font Awesome (рекомендуется для SSR)
config.autoAddCss = false // мы не хотим, чтобы FA добавлял CSS автоматически

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.component('FontAwesomeIcon', FontAwesomeIcon)
})