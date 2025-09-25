<template>
  <main class="shop-category-area pt-100px pb-100px">
    <div class="container">
      <div class="selected-filters" v-if="selectedFilters.length">
        <span v-for="filter in selectedFilters" :key="`${filter.type}-${filter.id}`" class="filter-badge">
          {{ filter.name }}
          <button @click="removeFilter(filter.type, filter.id)" class="remove-btn">&times;</button>
        </span>
      </div>
      <div class="row">
        <div class="col-lg-9 order-lg-last col-md-12 order-md-first">
          <div class="shop-top-bar d-flex justify-content-between mb-3">
            <div class="category-text">
              <h1 class="h3 m-0" v-if="categoryData">{{ categoryData.name }}</h1>
              <p v-if="categoryData">{{ categoryData.description }}</p>
            </div>
            <UiAppSortedGoods @update:sortOption="handleSortChange" />
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
            :mixture_types="filters.mixture_types" :seams="filters.seams" :textures="filters.textures" :countries="filters.countries"
            :sizes="filters.sizes" :materials="filters.materials" :waterproofs="filters.waterproofs"
            :collections="filters.collections" :volumes="filters.volumes" :product_weights="filters.product_weights"
            :installation_types="filters.installation_types" :shapes="filters.shapes" :applications="filters.applications"
            :drying_times="filters.drying_times" :package_weights="filters.package_weights" :min_temps="filters.min_temps"
            :max_temps="filters.max_temps" :consumptions="filters.consumptions" :brands="filters.brands"
            :initialFilters="filters" :selectedFilters="{
              brands: selectedBrands,
              colors: selectedColors,
              patterns: selectedPatterns,
              weights: selectedWeights,
              subcategories: selectedSubcategories,
              glues: selectedGlues,
              mixture_types: selectedMixtureTypes,
              seams: selectedSeams,
              textures: selectedTextures,
              countries: selectedCountries,
              sizes: selectedSizes,
              materials: selectedMaterials,
              waterproofs: selectedWaterproofs,
              collections: selectedCollections,
              volumes: selectedVolumes,
              product_weights: selectedProductWeights,
              installation_types: selectedInstallationTypes,
              shapes: selectedShapes,
              applications: selectedApplications,
              drying_times: selectedDryingTimes,
              package_weights: selectedPackageWeights,
              min_temps: selectedMinTemps,
              max_temps: selectedMaxTemps,
              consumptions: selectedConsumptions
            }" @update:filters="debouncedHandleFilterChange" />
        </ClientOnly>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, watch, computed, watchEffect } from 'vue'
import { useRoute } from 'vue-router'
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
  countries: [],
  sizes: [],
  materials: [],
  waterproofs: [],
  collections: [],
  volumes: [],
  product_weights: [],
  installation_types: [],
  shapes: [],
  applications: [],
  drying_times: [],
  package_weights: [],
  min_temps: [],
  max_temps: [],
  consumptions: []
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
const selectedCountries = ref([])
const selectedSizes = ref([])
const selectedMaterials = ref([])
const selectedWaterproofs = ref([])
const selectedCollections = ref([])
const selectedVolumes = ref([])
const selectedProductWeights = ref([])
const selectedInstallationTypes = ref([])
const selectedShapes = ref([])
const selectedApplications = ref([])
const selectedDryingTimes = ref([])
const selectedPackageWeights = ref([])
const selectedMinTemps = ref([])
const selectedMaxTemps = ref([])
const selectedConsumptions = ref([])

const debounce = (func, delay) => {
  let timeout
  return (...args) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => func(...args), delay)
  }
}

const queryParams = computed(() => {
  const params = {
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
    countries: selectedCountries.value,
    sizes: selectedSizes.value,
    materials: selectedMaterials.value,
    waterproofs: selectedWaterproofs.value,
    collections: selectedCollections.value,
    volumes: selectedVolumes.value,
    product_weights: selectedProductWeights.value,
    installation_types: selectedInstallationTypes.value,
    shapes: selectedShapes.value,
    applications: selectedApplications.value,
    drying_times: selectedDryingTimes.value,
    package_weights: selectedPackageWeights.value,
    min_temps: selectedMinTemps.value,
    max_temps: selectedMaxTemps.value,
    consumptions: selectedConsumptions.value
  }

  // Фильтруем пустые значения
  Object.keys(params).forEach(key => {
    const value = params[key]
    if (value === '' || value === null || value === undefined || (Array.isArray(value) && value.length === 0)) {
      delete params[key]
    }
  })

  return params
})

const config = useRuntimeConfig()
const { data: fetchData, pending, error, execute } = useAsyncData(`category-products-${slug}`, () => $fetch(`${config.public.apiBase}/api/category/${slug}/products`, {
  query: queryParams.value
}), {
  immediate: false
})

const refresh = () => execute()

watchEffect(() => {
  refresh()
})

const categoryData = computed(() => fetchData.value?.category || null)
const productsData = computed(() => {
  const p = fetchData.value?.products || null
  return p
})

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
  selectedCountries.value = newFilters.countries || []
  selectedSizes.value = newFilters.sizes || []
  selectedMaterials.value = newFilters.materials || []
  selectedWaterproofs.value = newFilters.waterproofs || []
  selectedCollections.value = newFilters.collections || []
  selectedVolumes.value = newFilters.volumes || []
  selectedProductWeights.value = newFilters.product_weights || []
  selectedInstallationTypes.value = newFilters.installation_types || []
  selectedShapes.value = newFilters.shapes || []
  selectedApplications.value = newFilters.applications || []
  selectedDryingTimes.value = newFilters.drying_times || []
  selectedPackageWeights.value = newFilters.package_weights || []
  selectedMinTemps.value = newFilters.min_temps || []
  selectedMaxTemps.value = newFilters.max_temps || []
  selectedConsumptions.value = newFilters.consumptions || []
  currentPage.value = 1
}

const debouncedHandleFilterChange = debounce(handleFilterChange, 300)

const changePage = (page) => {
  currentPage.value = page
}

const handleSortChange = (newSortOption) => {
  sortOption.value = newSortOption
  currentPage.value = 1
}

const selectedFilters = computed(() => {
  const result = []
  selectedBrands.value.forEach(id => {
    const item = filters.value.brands.find(b => b.id === id)
    if (item) result.push({ type: 'brands', id, name: item.name })
  })
  selectedColors.value.forEach(id => {
    const item = filters.value.colors.find(c => c.id === id)
    if (item) result.push({ type: 'colors', id, name: item.name })
  })
  selectedPatterns.value.forEach(id => {
    const item = filters.value.patterns.find(p => p.id === id)
    if (item) result.push({ type: 'patterns', id, name: item.name })
  })
  selectedWeights.value.forEach(id => {
    const item = filters.value.weights.find(w => w.id === id)
    if (item) result.push({ type: 'weights', id, name: item.value })
  })
  selectedSubcategories.value.forEach(id => {
    const item = filters.value.subcategories.find(s => s.id === id)
    if (item) result.push({ type: 'subcategories', id, name: item.name })
  })
  selectedGlues.value.forEach(id => {
    const item = filters.value.glues.find(g => g.id === id)
    if (item) result.push({ type: 'glues', id, name: item.name })
  })
  selectedMixtureTypes.value.forEach(id => {
    const item = filters.value.mixture_types.find(m => m.id === id)
    if (item) result.push({ type: 'mixture_types', id, name: item.name })
  })
  selectedSeams.value.forEach(id => {
    const item = filters.value.seams.find(s => s.id === id)
    if (item) result.push({ type: 'seams', id, name: item.name })
  })
  selectedTextures.value.forEach(id => {
    const item = filters.value.textures.find(t => t.id === id)
    if (item) result.push({ type: 'textures', id, name: item.name })
  })
  selectedCountries.value.forEach(id => {
    const item = filters.value.countries.find(c => c.id === id)
    if (item) result.push({ type: 'countries', id, name: item.name })
  })
  selectedSizes.value.forEach(id => {
    const item = filters.value.sizes.find(s => s.id === id)
    if (item) result.push({ type: 'sizes', id, name: item.name })
  })
  selectedMaterials.value.forEach(id => {
    const item = filters.value.materials.find(m => m.id === id)
    if (item) result.push({ type: 'materials', id, name: item.name })
  })
  selectedWaterproofs.value.forEach(id => {
    const item = filters.value.waterproofs.find(w => w.id === id)
    if (item) result.push({ type: 'waterproofs', id, name: item.name })
  })
  selectedCollections.value.forEach(id => {
    const item = filters.value.collections.find(c => c.id === id)
    if (item) result.push({ type: 'collections', id, name: item.name })
  })
  selectedVolumes.value.forEach(id => {
    const item = filters.value.volumes.find(v => v.id === id)
    if (item) result.push({ type: 'volumes', id, name: item.value })
  })
  selectedProductWeights.value.forEach(id => {
    const item = filters.value.product_weights.find(w => w.id === id)
    if (item) result.push({ type: 'product_weights', id, name: item.value })
  })
  selectedInstallationTypes.value.forEach(id => {
    const item = filters.value.installation_types.find(i => i.id === id)
    if (item) result.push({ type: 'installation_types', id, name: item.name })
  })
  selectedShapes.value.forEach(id => {
    const item = filters.value.shapes.find(s => s.id === id)
    if (item) result.push({ type: 'shapes', id, name: item.name })
  })
  selectedApplications.value.forEach(id => {
    const item = filters.value.applications.find(a => a.id === id)
    if (item) result.push({ type: 'applications', id, name: item.name })
  })
  selectedDryingTimes.value.forEach(id => {
    const item = filters.value.drying_times.find(d => d.id === id)
    if (item) result.push({ type: 'drying_times', id, name: item.name })
  })
  selectedPackageWeights.value.forEach(id => {
    const item = filters.value.package_weights.find(w => w.id === id)
    if (item) result.push({ type: 'package_weights', id, name: item.value })
  })
  selectedMinTemps.value.forEach(id => {
    const item = filters.value.min_temps.find(t => t.id === id)
    if (item) result.push({ type: 'min_temps', id, name: item.value })
  })
  selectedMaxTemps.value.forEach(id => {
    const item = filters.value.max_temps.find(t => t.id === id)
    if (item) result.push({ type: 'max_temps', id, name: item.value })
  })
  selectedConsumptions.value.forEach(id => {
    const item = filters.value.consumptions.find(c => c.id === id)
    if (item) result.push({ type: 'consumptions', id, name: item.name })
  })
  return result
})

const removeFilter = (type, id) => {
  if (type === 'brands') {
    selectedBrands.value = selectedBrands.value.filter(i => i !== id)
  } else if (type === 'colors') {
    selectedColors.value = selectedColors.value.filter(i => i !== id)
  } else if (type === 'patterns') {
    selectedPatterns.value = selectedPatterns.value.filter(i => i !== id)
  } else if (type === 'weights') {
    selectedWeights.value = selectedWeights.value.filter(i => i !== id)
  } else if (type === 'subcategories') {
    selectedSubcategories.value = selectedSubcategories.value.filter(i => i !== id)
  } else if (type === 'glues') {
    selectedGlues.value = selectedGlues.value.filter(i => i !== id)
  } else if (type === 'mixture_types') {
    selectedMixtureTypes.value = selectedMixtureTypes.value.filter(i => i !== id)
  } else if (type === 'seams') {
    selectedSeams.value = selectedSeams.value.filter(i => i !== id)
  } else if (type === 'textures') {
    selectedTextures.value = selectedTextures.value.filter(i => i !== id)
  } else if (type === 'countries') {
    selectedCountries.value = selectedCountries.value.filter(i => i !== id)
  } else if (type === 'sizes') {
    selectedSizes.value = selectedSizes.value.filter(i => i !== id)
  } else if (type === 'materials') {
    selectedMaterials.value = selectedMaterials.value.filter(i => i !== id)
  } else if (type === 'waterproofs') {
    selectedWaterproofs.value = selectedWaterproofs.value.filter(i => i !== id)
  } else if (type === 'collections') {
    selectedCollections.value = selectedCollections.value.filter(i => i !== id)
  } else if (type === 'volumes') {
    selectedVolumes.value = selectedVolumes.value.filter(i => i !== id)
  } else if (type === 'product_weights') {
    selectedProductWeights.value = selectedProductWeights.value.filter(i => i !== id)
  } else if (type === 'installation_types') {
    selectedInstallationTypes.value = selectedInstallationTypes.value.filter(i => i !== id)
  } else if (type === 'shapes') {
    selectedShapes.value = selectedShapes.value.filter(i => i !== id)
  } else if (type === 'applications') {
    selectedApplications.value = selectedApplications.value.filter(i => i !== id)
  } else if (type === 'drying_times') {
    selectedDryingTimes.value = selectedDryingTimes.value.filter(i => i !== id)
  } else if (type === 'package_weights') {
    selectedPackageWeights.value = selectedPackageWeights.value.filter(i => i !== id)
  } else if (type === 'min_temps') {
    selectedMinTemps.value = selectedMinTemps.value.filter(i => i !== id)
  } else if (type === 'max_temps') {
    selectedMaxTemps.value = selectedMaxTemps.value.filter(i => i !== id)
  } else if (type === 'consumptions') {
    selectedConsumptions.value = selectedConsumptions.value.filter(i => i !== id)
  }
  handleFilterChange({
    brands: selectedBrands.value,
    colors: selectedColors.value,
    patterns: selectedPatterns.value,
    weights: selectedWeights.value,
    subcategories: selectedSubcategories.value,
    glues: selectedGlues.value,
    mixture_types: selectedMixtureTypes.value,
    seams: selectedSeams.value,
    textures: selectedTextures.value,
    countries: selectedCountries.value,
    sizes: selectedSizes.value,
    materials: selectedMaterials.value,
    waterproofs: selectedWaterproofs.value,
    collections: selectedCollections.value,
    volumes: selectedVolumes.value,
    product_weights: selectedProductWeights.value,
    installation_types: selectedInstallationTypes.value,
    shapes: selectedShapes.value,
    applications: selectedApplications.value,
    drying_times: selectedDryingTimes.value,
    package_weights: selectedPackageWeights.value,
    min_temps: selectedMinTemps.value,
    max_temps: selectedMaxTemps.value,
    consumptions: selectedConsumptions.value
  })
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

.selected-filters {
  margin-bottom: 20px;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.filter-badge {
  display: inline-flex;
  align-items: center;
  background-color: $theme-color;
  color: $white;
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 500;
  line-height: 1;

  .remove-btn {
    background: none;
    border: none;
    color: $white;
    font-size: 16px;
    margin-left: 8px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s;

    &:hover {
      opacity: 1;
    }
  }
}
</style>