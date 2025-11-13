import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useRuntimeConfig } from '#imports'

const DEFAULTS = {
  phone: '',
  email: '',
  telegram: '',
  whatsapp: '',
  organization: '',
  adress: '',
  inn: '',
  ogrn: ''
}

export const useSiteInfoStore = defineStore('siteInfo', () => {
  const siteInfo = ref(null)
  const isLoading = ref(false)
  const error = ref(null)

  const config = useRuntimeConfig()

  const fetchSiteInfo = async () => {
    if (siteInfo.value) return siteInfo.value

    isLoading.value = true
    error.value = null

    try {
      const data = await $fetch(`${config.public.apiBase}/api/client/information`)
      siteInfo.value = data
      return data
    } catch (err) {
      error.value = 'Не удалось загрузить информацию о сайте'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const getValue = (key) => computed(() => siteInfo.value?.[key] || DEFAULTS[key])

  return {
    siteInfo: computed(() => siteInfo.value),
    isLoading: computed(() => isLoading.value),
    error: computed(() => error.value),
    fetchSiteInfo,
    getFormattedPhone: getValue('phone'),
    getEmail: getValue('email'),
    getTelegram: getValue('telegram'),
    getWhatsapp: getValue('whatsapp'),
    getOrganization: getValue('organization'),
    getAdress: getValue('adress'),
    getInn: getValue('inn'),
    getOgrn: getValue('ogrn')
  }
})