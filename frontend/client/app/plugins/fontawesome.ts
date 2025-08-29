import { library, config } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Импортируем иконки (только те, которые реально используешь — чтобы не грузить лишнее)
import {
  faShoppingCart,
  faHeart,
  faUser,
  faBars,
  faTimes,
  faHome,
  faTag,
  faStar
} from '@fortawesome/free-solid-svg-icons'

import { faGithub, faVuejs } from '@fortawesome/free-brands-svg-icons'
import { faEnvelope } from '@fortawesome/free-regular-svg-icons'

// Добавляем иконки в библиотеку
library.add(
  faShoppingCart,
  faHeart,
  faUser,
  faBars,
  faTimes,
  faHome,
  faTag,
  faStar,
  faGithub,
  faVuejs,
  faEnvelope
)

// Опционально: настройка Font Awesome (рекомендуется для SSR)
config.autoAddCss = false // мы не хотим, чтобы FA добавлял CSS автоматически

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.component('FontAwesomeIcon', FontAwesomeIcon)
})