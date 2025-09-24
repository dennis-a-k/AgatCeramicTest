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
  // Настройки Sitemap
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
          // Добавьте другие маршруты
        ]
      } catch {
        return ['/', '/about', '/contact']
      }
    }
  },



  // Настройки для SEO
  ssr: true, // Важно для SEO
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
    }
  }

})
