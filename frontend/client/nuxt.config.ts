// Nuxt автоматически загружает .env файлы на основе NODE_ENV

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: [
    '@nuxtjs/sitemap',
    '@nuxt/image',
    '@vueuse/nuxt',
  ],
  css: [
    'bootstrap/dist/css/bootstrap.min.css',
    '~/assets/css/font.awesome.css',
    '~/assets/css/pe-icon-7-stroke.css',
    '~/assets/css/animate.min.css',
    'swiper/swiper-bundle.css',
    '~/assets/scss/main.scss',
  ],
  vite: {
    css: {
      preprocessorOptions: {
        scss: {
          additionalData: `@use "~/assets/scss/variables.scss" as *;`
        }
      },
    },
    optimizeDeps: {
      exclude: ['nuxt']
    }
  },
  nitro: {
    externals: {
      external: ['nuxt/dist/core/runtime/nitro/utils/cache-driver']
    }
  },
  plugins: [
    'plugins/bootstrap.client.js',
  ],
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE_URL,
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL,
    }
  },
  sitemap: {
    hostname: process.env.NUXT_PUBLIC_SITE_URL,
    gzip: true,
    routes: async () => {
      try {
        const categories: string[] = await $fetch(`${process.env.NUXT_PUBLIC_API_BASE_URL}/api/categories/slugs`) || []
        const categoryRoutes = categories.map((slug: string) => `/category/${slug}`)
        return [
          '/',
          '/about',
          '/contact',
          ...categoryRoutes
        ]
      } catch {
        return ['/', '/about', '/contact']
      }
    }
  } as any,
  ssr: true,
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
    }
  }
})