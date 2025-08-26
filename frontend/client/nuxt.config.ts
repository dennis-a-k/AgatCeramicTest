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
      apiBase: process.env.NUXT_PUBLIC_API_BASE || '/api',
    },
  },
  nitro: {
    compatibilityDate: '2025-08-24',
    routeRules: {
      '/api/**': {
        proxy: `${
          process.env.NUXT_PUBLIC_API_BASE_URL || 'http://test.dennistp.beget.tech/api'
        }/**`
      }
    }
  }
})