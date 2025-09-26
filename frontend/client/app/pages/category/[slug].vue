<template>
  <main class="shop-category-area pt-100px pb-100px">
    <div class="container">
      <UiAppSelectedFilters :selectedFilters="store.selectedFilters" @removeFilter="store.removeFilter" />
      <div class="row">
        <div class="col-lg-9 order-lg-last col-md-12 order-md-first">
          <div class="shop-top-bar d-flex justify-content-between mb-3">
            <div class="category-text">
              <h1 class="h3 m-0" v-if="categoryData">{{ categoryData.name }}</h1>
              <p v-if="categoryData">{{ categoryData.description }}</p>
            </div>
            <UiAppSortedGoods @update:sortOption="store.handleSortChange" />
          </div>
          <UiAppSpinner v-if="pending" text="Загрузка товаров..." />
          <div v-else-if="showErrorMessage" class="error text-center mt-5">
            <h2>Произошла ошибка при загрузке товаров.<br>Попробуйте перезагрузить страницу.</h2>
          </div>
          <div v-else-if="!productsData || !productsData.data || productsData.data.length === 0"
            class="text-center mt-2">
            <h2>Список товаров пуст</h2>
          </div>
          <div v-else class="shop-bottom-area">
            <div class="row">
              <div class="col">
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="shop-grid">
                    <div class="row mb-n-30px">
                      <ProductAppProductCard v-for="product in productsData.data" :key="product.id"
                        :product="product" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <PaginationAppPagination
              v-if="productsData.last_page > 1"
              :current-page="productsData.current_page"
              :total-pages="productsData.last_page"
              @change-page="store.changePage"
            />
          </div>
        </div>
        <ClientOnly>
          <FiltersAppCategoryFilters :subcategories="store.filters.subcategories" :patterns="store.filters.patterns"
            :weights="store.filters.weights" :colors="store.filters.colors" :glues="store.filters.glues"
            :mixture_types="store.filters.mixture_types" :seams="store.filters.seams" :textures="store.filters.textures" :countries="store.filters.countries"
            :sizes="store.filters.sizes" :materials="store.filters.materials" :waterproofs="store.filters.waterproofs"
            :collections="store.filters.collections" :volumes="store.filters.volumes" :product_weights="store.filters.product_weights"
            :installation_types="store.filters.installation_types" :shapes="store.filters.shapes" :applications="store.filters.applications"
            :drying_times="store.filters.drying_times" :package_weights="store.filters.package_weights" :min_temps="store.filters.min_temps"
            :max_temps="store.filters.max_temps" :consumptions="store.filters.consumptions" :brands="store.filters.brands"
            :initialFilters="store.filters" />
        </ClientOnly>
      </div>
    </div>
  </main>
</template>

<script setup>
import { computed, watch, watchEffect } from 'vue'
import { useRoute, onBeforeRouteUpdate } from 'vue-router'
import { useRuntimeConfig } from '#imports'
import { useCategoryStore } from '~/stores/useCategoryStore'

const route = useRoute()
const slug = route.params.slug
const store = useCategoryStore()

// Сбрасываем фильтры при переходе в новую категорию
onBeforeRouteUpdate((to, from) => {
  if (to.params.slug !== from.params.slug) {
    store.resetFilters()
    refresh()
  }
})

const config = useRuntimeConfig()
const { data: fetchData, pending, error, execute } = useAsyncData(`category-products-${slug}`, () => $fetch(`${config.public.apiBase}/api/category/${slug}/products`, {
  query: store.queryParams
}), {
  immediate: false
})

const refresh = () => execute()

watch(() => store.queryParams, () => refresh(), { immediate: true })

const categoryData = computed(() => fetchData.value?.category || null)
const productsData = computed(() => {
  const p = fetchData.value?.products || null
  return p
})

watch(fetchData, (newData) => {
  if (newData && newData.filters) {
    store.setFilters(newData.filters)
  }
}, { immediate: true })

const showErrorMessage = computed(() => {
  return error.value && !pending.value
})

const structuredData = computed(() => {
  if (!productsData.value?.data) return null
  return {
    '@context': 'https://schema.org',
    '@type': 'ItemList',
    name: categoryData.value?.name || 'Категория товаров',
    description: categoryData.value?.description || '',
    url: `${config.public.siteUrl}/category/${slug}`,
    numberOfItems: productsData.value.data.length,
    itemListElement: productsData.value.data.map((product, index) => ({
      '@type': 'Product',
      position: index + 1,
      name: product.name,
      description: product.description,
      image: product.image,
      offers: {
        '@type': 'Offer',
        price: product.price,
        priceCurrency: 'RUB',
        availability: product.in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'
      }
    }))
  }
})

useHead(computed(() => ({
  title: categoryData.value?.name ? `${categoryData.value.name} - AgatCeramic` : 'Категория товаров - AgatCeramic',
  meta: [
    {
      name: 'description',
      content: categoryData.value?.description || 'Купить товары категории в интернет-магазине AgatCeramic'
    },
    {
      name: 'keywords',
      content: categoryData.value?.name ? `${categoryData.value.name}, керамическая плитка, сантехника` : 'керамическая плитка, сантехника'
    }
  ],
  link: [
    {
      rel: 'canonical',
      href: `${config.public.siteUrl}/category/${slug}`
    }
  ],
  script: structuredData.value ? [{ type: 'application/ld+json', children: JSON.stringify(structuredData.value) }] : []
})))
</script>

<style scoped lang="scss">
.category-text {
  line-height: 1em;
}
</style>