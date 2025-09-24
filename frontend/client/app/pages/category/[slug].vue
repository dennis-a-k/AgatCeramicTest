<template>
  <main class="shop-category-area pt-100px pb-100px">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 order-lg-last col-md-12 order-md-first">
          <div class="shop-top-bar d-flex justify-content-between mb-3">
            <div class="category-text">
              <h1 class="h3 m-0" v-if="categoryData">{{ categoryData.name }}</h1>
              <p v-if="categoryData">{{ categoryData.description }}</p>
            </div>
            <ProductAppSortedGoods @update:sortOption="handleSortChange" />
          </div>
          <div v-if="pending" class="loading">
            Загрузка товаров...
          </div>
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

            <nav v-if="productsData.last_page > 1">
              <ul class="pagination">
                <li v-for="page in productsData.last_page" :key="page"
                  :class="['page-item', { active: productsData.current_page === page }]">
                  <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                </li>
              </ul>
            </nav>

          </div>
        </div>
        <ClientOnly>
          <FiltersAppCategoryFilters :subcategories="filters.subcategories" :patterns="filters.patterns"
            :weights="filters.weights" :colors="filters.colors" :glues="filters.glues"
            :mixture_types="filters.mixture_types" :seams="filters.seams" :textures="filters.textures"
            :sizes="filters.sizes" :brands="filters.brands" :initialFilters="filters"
            @update:filters="handleFilterChange" />
        </ClientOnly>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, watch, computed, watchEffect } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@unhead/vue'
import { useRuntimeConfig } from '#imports'

const route = useRoute()
const slug = route.params.slug

const filters = ref({
  brands: [],
  min_price: '',
  max_price: '',
  colors: [],
  patterns: [],
  weights: [],
  subcategories: [],
  glues: [],
  mixture_types: [],
  seams: [],
  textures: [],
  sizes: []
})

const selectedBrands = ref([])
const minPrice = ref('')
const maxPrice = ref('')
const sortOption = ref('default')
const currentPage = ref(1)
const selectedColors = ref([])
const selectedPatterns = ref([])
const selectedWeights = ref([])
const selectedSubcategories = ref([])
const selectedGlues = ref([])
const selectedMixtureTypes = ref([])
const selectedSeams = ref([])
const selectedTextures = ref([])
const selectedSizes = ref([])

const debounce = (func, delay) => {
  let timeout
  return (...args) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => func(...args), delay)
  }
}

const queryParams = ref({
  brands: selectedBrands.value,
  min_price: minPrice.value,
  max_price: maxPrice.value,
  sort: sortOption.value,
  page: currentPage.value,
  colors: selectedColors.value,
  patterns: selectedPatterns.value,
  weights: selectedWeights.value,
  subcategories: selectedSubcategories.value,
  glues: selectedGlues.value,
  mixture_types: selectedMixtureTypes.value,
  seams: selectedSeams.value,
  textures: selectedTextures.value,
  sizes: selectedSizes.value
})

const updateQuery = () => {
  queryParams.value = {
    brands: selectedBrands.value,
    min_price: minPrice.value,
    max_price: maxPrice.value,
    sort: sortOption.value,
    page: currentPage.value,
    colors: selectedColors.value,
    patterns: selectedPatterns.value,
    weights: selectedWeights.value,
    subcategories: selectedSubcategories.value,
    glues: selectedGlues.value,
    mixture_types: selectedMixtureTypes.value,
    seams: selectedSeams.value,
    textures: selectedTextures.value,
    sizes: selectedSizes.value
  }
}

const debouncedUpdateQuery = debounce(updateQuery, 300)

watchEffect(() => {
  selectedBrands.value
  minPrice.value
  maxPrice.value
  sortOption.value
  currentPage.value
  selectedColors.value
  selectedPatterns.value
  selectedWeights.value
  selectedSubcategories.value
  selectedGlues.value
  selectedMixtureTypes.value
  selectedSeams.value
  selectedTextures.value
  selectedSizes.value
  debouncedUpdateQuery()
})

const config = useRuntimeConfig()
const { data: fetchData, pending, error, refresh } = useFetch(
  `${config.public.apiBase}/api/category/${slug}/products`,
  {
    query: queryParams
  }
)

const categoryData = computed(() => fetchData.value?.category || null)
const productsData = computed(() => fetchData.value?.products || null)

watch(fetchData, (newData) => {
  if (newData && newData.filters) {
    Object.assign(filters.value, newData.filters)
  }
}, { immediate: true })

const handleFilterChange = (newFilters) => {
  selectedBrands.value = newFilters.brands
  selectedColors.value = newFilters.colors || []
  selectedPatterns.value = newFilters.patterns || []
  selectedWeights.value = newFilters.weights || []
  selectedSubcategories.value = newFilters.subcategories || []
  selectedGlues.value = newFilters.glues || []
  selectedMixtureTypes.value = newFilters.mixture_types || []
  selectedSeams.value = newFilters.seams || []
  selectedTextures.value = newFilters.textures || []
  selectedSizes.value = newFilters.sizes || []
  currentPage.value = 1
}

const changePage = (page) => {
  currentPage.value = page
}

const handleSortChange = (newSortOption) => {
  sortOption.value = newSortOption
  currentPage.value = 1
}

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
  title: categoryData.value?.name ? `${categoryData.value.name} - Agat Ceramic` : 'Категория товаров - Agat Ceramic',
  meta: [
    {
      name: 'description',
      content: categoryData.value?.description || 'Купить товары категории в интернет-магазине Agat Ceramic'
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

<style scoped>
.category-text {
  line-height: 1em;
}
</style>