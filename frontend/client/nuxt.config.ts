export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  // ssr: true,
  modules: [
    '@nuxt/image',
    '@nuxt/ui',
    '@nuxt/scripts',
    '@nuxtjs/sitemap',
  ],
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE_URL,
    },
  },
  sitemap: {
    exclude: ['/admin/**', '/api/**'],
    xsl: false,
  },
  typescript: {
    typeCheck: false,
    strict: true
  },
  nitro: {
    routeRules: {
      '/api/**': {
        proxy: `${process.env.NUXT_PUBLIC_API_BASE_URL}/api/**`
      }
    }
  },
  vite: {
    css: {
      devSourcemap: false
    },
    build: {
      sourcemap: false
    }
  }
})