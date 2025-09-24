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
      // apiBase: process.env.NUXT_PUBLIC_API_BASE_URL || 'https://api.your-backend.com',
      // siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'https://your-site.com'
    }
  },
  // Настройки Sitemap
  sitemap: {
    hostname: 'https://yourdomain.com', // Замените на ваш домен
    gzip: true,
    routes: async () => {
      try {
        const categories: string[] = await $fetch('http://127.0.0.1:8000/api/categories/slugs') || []
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
