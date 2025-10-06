<template>
  <div class="main-wrapper">
    <SlidersAppHeroSlider />
    <h1 class="text-center d-none my-5">Интернет-магазин плитки, керамогранита и сантехники</h1>
    <BannersAppCategoryBanner />
    <SlidersAppTapSlider />
    <BannersAppSaleBanner />
    <SlidersAppBrandsSlider />
  </div>
</template>

<script setup>
import { useRuntimeConfig } from '#imports'

const config = useRuntimeConfig()

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
    }
  ],
  link: [
    {
      rel: 'canonical',
      href: `${config.public.siteUrl}/`
    }
  ],
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Organization',
      name: 'AgatCeramic',
      url: config.public.siteUrl,
      logo: `${config.public.siteUrl}/images/stock/logo.png`,
      description: 'Интернет-магазин плитки, керамогранита и сантехники',
      contactPoint: {
        '@type': 'ContactPoint',
        telephone: '+7-999-999-99-99',
        contactType: 'customer service'
      }
    })
  }]
})
</script>
