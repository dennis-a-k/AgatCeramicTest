import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  modules: [
    // '@nuxtjs/proxy' // Удаляем этот модуль
  ],
  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.NUXT_PUBLIC_API_BASE_URL || 'http://127.0.0.1:8000/api/' // Добавляем переменную из .env
    }
  },
  nitro: {
    compatibilityDate: '2025-08-24',
    routeRules: {
      '/api/**': {
        proxy: {
          to: process.env.NUXT_PUBLIC_API_BASE_URL ? `${process.env.NUXT_PUBLIC_API_BASE_URL}**` : 'http://127.0.0.1:8000/api/**'
        }
      }
    }
  }
  // Удаляем старую конфигурацию proxy
})
