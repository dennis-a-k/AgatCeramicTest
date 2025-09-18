export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: [
    '@nuxtjs/sitemap',
    '@nuxt/image',
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
  // Настройки Sitemap
  sitemap: {
    hostname: 'https://yourdomain.com', // Замените на ваш домен
    gzip: true,
    routes: async () => {
      // Здесь можно динамически генерировать routes для sitemap
      return [
        '/',
        '/about',
        '/contact'
        // Добавьте свои маршруты
      ]
    }
  },



  // Настройки для SEO
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
    }
  }

})
