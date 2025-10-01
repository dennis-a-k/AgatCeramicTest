import { config } from 'dotenv'
import { loadEnv } from 'vite'

// Полностью перезаписываем переменные из нужного .env файла
const envFile = process.env.NODE_ENV === 'production' ? '.env.production' : '.env.development'
const envConfig = config({ path: envFile })

if (envConfig.parsed) {
  // Полностью перезаписываем process.env значениями из файла
  for (const key in envConfig.parsed) {
    process.env[key] = envConfig.parsed[key]
  }
}

// Альтернативно: используем Vite для загрузки env
const viteEnv = loadEnv(process.env.NODE_ENV || 'production', process.cwd(), '')
Object.assign(process.env, viteEnv)

console.log('Current NODE_ENV:', process.env.NODE_ENV)
console.log('Site URL from env:', process.env.NUXT_PUBLIC_SITE_URL)
console.log('Site API URL from env:', process.env.NUXT_PUBLIC_API_BASE_URL)
console.log('Loaded from file:', envFile)

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: [
    '@nuxtjs/sitemap',
    '@nuxt/image',
    '@vueuse/nuxt',
    '@pinia/nuxt',
  ],
  css: [
    'bootstrap/dist/css/bootstrap.min.css',
    '~/assets/css/font.awesome.css',
    '~/assets/css/pe-icon-7-stroke.css',
    '~/assets/css/animate.min.css',
    'swiper/swiper-bundle.css',
    'venobox/dist/venobox.min.css',
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
    'plugins/toast.client.js',
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

        const products: string[] = await $fetch(`${process.env.NUXT_PUBLIC_API_BASE_URL}/api/products/slugs`) || []
        const productRoutes = products.map((slug: string) => `/product/${slug}`)

        const brands: string[] = await $fetch(`${process.env.NUXT_PUBLIC_API_BASE_URL}/api/brands/slugs`) || []
        const brandRoutes = brands.map((slug: string) => `/brand/${slug}`)

        return [
          '/',
          '/contacts',
          '/delivery',
          '/partnerships',
          '/return',
          '/rezka',
          '/rospis',
          '/sale',
          ...categoryRoutes,
          ...productRoutes,
          ...brandRoutes
        ]
      } catch {
        return [
          '/',
          '/contacts',
          '/delivery',
          '/partnerships',
          '/return',
          '/rezka',
          '/rospis',
          '/sale'
        ]
      }
    }
  } as any,
  ssr: true,
  app: {
    head: {
      htmlAttrs: {
        lang: 'ru',
        dir: 'ltr',
      },
      title: 'AgatCeramic',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' }
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
      ]
    }
  },
})