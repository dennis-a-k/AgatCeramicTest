<template>
  <main class="product-details-area pt-100px pb-100px">
    <div class="container">
      <UiAppSpinner v-if="pending" text="Загрузка товара..." />
      <div v-else-if="showErrorMessage" class="error text-center mt-5">
        <h2>Произошла ошибка при загрузке товара.<br>Попробуйте перезагрузить страницу.</h2>
      </div>
      <div v-else-if="!productData" class="text-center mt-5">
        <h2>Товар не найден</h2>
      </div>
      <div v-else class="row">
        <div class="col-lg-6 col-md-12">
          <div class="product-details-img">
            <div class="tab-content">
              <div class="tab-pane fade show active" id="product-1">
                <NuxtImg :src="productImage" :alt="productData.name" class="zoompro" />
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12">
          <div class="product-details-content">
            <h2>{{ productData.name }}</h2>
            <div class="product-details-price">
              <span class="price">{{ formattedPrice }}</span>
            </div>
            <div class="product-details-description">
              <p>{{ productData.description }}</p>
            </div>
            <div class="product-details-meta">
              <span v-if="productData.category">Категория: <NuxtLink :to="`/category/${productData.category.slug}`">{{ productData.category.name }}</NuxtLink></span>
              <span v-if="productData.brand">Бренд: <NuxtLink :to="`/brand/${productData.brand.slug}`">{{ productData.brand.name }}</NuxtLink></span>
              <span v-if="productData.article">Артикул: {{ productData.article }}</span>
              <span v-if="productData.product_code">Код товара: {{ productData.product_code }}</span>
            </div>
            <div class="product-details-action">
              <button class="btn btn-primary">Добавить в корзину</button>
            </div>
          </div>
        </div>
      </div>
      <div v-if="productData && productData.attributeValues && productData.attributeValues.length > 0" class="row mt-5">
        <div class="col-12">
          <h3>Характеристики</h3>
          <table class="table table-striped">
            <tbody>
              <tr v-for="attr in productData.attributeValues" :key="attr.id">
                <td>{{ attr.attribute.name }}</td>
                <td>{{ attr.string_value || attr.number_value || attr.boolean_value ? 'Да' : 'Нет' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useRuntimeConfig } from '#imports'

const route = useRoute()
const slug = route.params.slug
const config = useRuntimeConfig()

const { data: productData, pending, error, execute } = useAsyncData(`product-${slug}`, () => $fetch(`${config.public.apiBase}/api/products/slug/${slug}`), {
  immediate: true
})

const showErrorMessage = computed(() => {
  return error.value && !pending.value
})

const productImage = computed(() => {
  return (productData.value?.imgSrc && productData.value.imgSrc.trim() !== '')
    ? productData.value.imgSrc
    : '/images/stock/stock-image.png';
})

const formattedPrice = computed(() => {
  const formatter = new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
  })
  return formatter.format(productData.value?.price || 0)
})

const structuredData = computed(() => {
  if (!productData.value) return null
  return {
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: productData.value.name,
    description: productData.value.description,
    image: productImage.value,
    offers: {
      '@type': 'Offer',
      price: productData.value.price,
      priceCurrency: 'RUB',
      availability: productData.value.in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
    },
    brand: productData.value.brand ? {
      '@type': 'Brand',
      name: productData.value.brand.name
    } : undefined,
    category: productData.value.category ? productData.value.category.name : undefined
  }
})

useHead(computed(() => ({
  title: productData.value?.name ? `${productData.value.name} - AgatCeramic` : 'Товар - AgatCeramic',
  meta: [
    {
      name: 'description',
      content: productData.value?.description || 'Купить товар в интернет-магазине AgatCeramic'
    },
    {
      name: 'keywords',
      content: productData.value?.name ? `${productData.value.name}, керамическая плитка, сантехника` : 'керамическая плитка, сантехника'
    }
  ],
  link: [
    {
      rel: 'canonical',
      href: `${config.public.siteUrl}/product/${slug}`
    }
  ],
  script: structuredData.value ? [{ type: 'application/ld+json', children: JSON.stringify(structuredData.value) }] : []
})))
</script>

<style scoped lang="scss">
.product-details-area {
  .product-details-img {
    .zoompro {
      width: 100%;
      height: auto;
    }
  }

  .product-details-content {
    h2 {
      font-size: 30px;
      margin-bottom: 20px;
    }

    .product-details-price {
      .price {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
      }
    }

    .product-details-description {
      margin: 20px 0;
    }

    .product-details-meta {
      span {
        display: block;
        margin-bottom: 10px;

        a {
          color: #007bff;
        }
      }
    }

    .product-details-action {
      margin-top: 30px;
    }
  }
}
</style>