<template>
  <UiAppSpinner v-if="loading" text="Загрузка главной страницы..." />
  <div v-else class="main-wrapper">
    <SlidersAppHeroSlider />
    <h1 class="text-center d-none my-5">Интернет-магазин плитки, керамогранита и сантехники</h1>
    <BannersAppCategoryBanner />
    <SlidersAppTapSlider />
    <BannersAppSaleBanner />
    <SlidersAppBrandsSlider />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRuntimeConfig } from '#imports'
import { useBrandStore } from '~/stores/useBrandStore'
import { useSiteInfoStore } from '~/stores/useSiteInfoStore'

const config = useRuntimeConfig()
const siteUrl = config.public.siteUrl || 'https://agatceramic.ru'

const brandStore = useBrandStore()
const siteInfoStore = useSiteInfoStore()
const loading = ref(true)

// Функция для получения случайных товаров категории
const fetchRandomProducts = async (slug, targetArray) => {
  try {
    const response = await $fetch(`${config.public.apiBase}/api/category/${slug}/products?per_page=1000`)
    if (response && response.products && response.products.data) {
      // Трансформируем данные к формату компонента
      const transformedProducts = response.products.data.map(product => ({
        id: product.id,
        name: product.name,
        slug: product.slug,
        price: product.price,
        sale: product.is_sale, // для компонента sale
        is_sale: product.is_sale, // для компонента badges
        category: {
          name: product.category?.name || 'Категория',
          slug: product.category?.slug || ''
        },
        href: '/', // пока статический
        imgSrc: '', // пока пустое, будет дефолтное изображение
        imgAlt: product.name,
        title: product.name,
        // Дополнительные поля для модального окна быстрого просмотра
        article: product.article,
        brand: product.brand,
        collection: product.collection,
        color: product.color,
        unit: product.unit,
        images: product.images,
        attribute_values: product.attribute_values
      }))
      // Перемешиваем массив и берем первые 8 товаров
      const shuffled = transformedProducts.sort(() => 0.5 - Math.random())
      targetArray.value = shuffled.slice(0, 8)
    }
  } catch (error) {
    console.error(`Error fetching products for ${slug}:`, error)
    targetArray.value = []
  }
}

onMounted(async () => {
try {
  // Загружаем информацию о сайте
  await siteInfoStore.fetchSiteInfo()
  // Загружаем бренды для слайдера
  await brandStore.fetchBrands()

  // Загружаем товары для тап-слайдера (если нужно)
  // Здесь можно добавить загрузку товаров, если компонент AppTapSlider не загружает их сам
} catch (error) {
  console.error('Error loading page data:', error)
} finally {
  loading.value = false
}
})

useHead({
  title: 'AgatCeramic - Интернет-магазин плитки, керамогранита и сантехники',
  meta: [
    {
      name: 'description',
      content: 'Купить керамическую плитку, керамогранит и сантехнику в интернет-магазине AgatCeramic. Широкий ассортимент, низкие цены, доставка по Москве и России. В AgatCeramic вы найдете премиальную керамическую плитку, керамогранит и мозаику от ведущих мировых производителей. Мы предлагаем эксклюзивные коллекции для создания уникального дизайна интерьера: напольную и настенную плитку, износостойкий керамогранит для коммерческих и жилых помещений, а также стильную мозаику для акцентных решений.'
    },
    {
      name: 'keywords',
      content: 'керамическая плитка, керамогранит, сантехника, купить плитку, интернет-магазин, AgatCeramic'
    },
    {
      property: 'og:image',
      content: `${siteUrl}/images/stock/logo.png`
    },
    {
      property: 'og:title',
      content: 'AgatCeramic - Интернет-магазин плитки, керамогранита и сантехники'
    },
    {
      property: 'og:description',
      content: 'Купить керамическую плитку, керамогранит и сантехнику в интернет-магазине AgatCeramic. Широкий ассортимент, низкие цены, доставка по Москве и России.'
    },
    {
      property: 'og:url',
      content: `${siteUrl}/`
    },
    {
      name: 'twitter:image',
      content: `${siteUrl}/images/stock/logo.png`
    }
  ],
  link: [
    {
      rel: 'canonical',
      href: `${siteUrl}/`
    }
  ],
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Organization',
      name: 'AgatCeramic',
      url: siteUrl,
      logo: `${siteUrl}/images/stock/logo.png`,
      description: 'Интернет-магазин плитки, керамогранита и сантехники',
      email: siteInfoStore.getEmail,
      contactPoint: {
        '@type': 'ContactPoint',
        telephone: siteInfoStore.getFormattedPhone,
        contactType: 'customer service'
      }
    })
  }]
})
</script>
