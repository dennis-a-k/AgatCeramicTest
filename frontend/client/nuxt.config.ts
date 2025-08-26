// https://nuxt.com/docs/api/configuration/nuxt-config
import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: [
    '@nuxt/content',
    '@nuxt/image',
    '@nuxt/ui',
    '@nuxt/scripts'
  ],
  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.NUXT_PUBLIC_API_BASE_URL || 'https://yourdomain.com/api'
    }
  },
  nitro: {
    compatibilityDate: '2025-08-24',
    routeRules: {
      '/api/**': {
        proxy: {
          to: process.env.NUXT_PUBLIC_API_BASE_URL ? `${process.env.NUXT_PUBLIC_API_BASE_URL}**` : 'https://yourdomain.com/api/**'
        }
      }
    }
  }
})