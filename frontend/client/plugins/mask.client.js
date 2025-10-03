import Cleave from 'cleave.js'

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.directive('cleave', {
    mounted(el, binding) {
      el.cleave = new Cleave(el, binding.value)
    },
    updated(el, binding) {
      if (el.cleave) {
        el.cleave.destroy()
      }
      el.cleave = new Cleave(el, binding.value)
    },
    unmounted(el) {
      if (el.cleave) {
        el.cleave.destroy()
      }
    }
  })
})