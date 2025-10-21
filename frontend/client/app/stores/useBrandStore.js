import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

// Конфигурация типов фильтров
const filterConfigs = {
  categories: { nameField: 'name' },
  brands: { nameField: 'name' },
  colors: { nameField: 'name' },
  patterns: { nameField: 'name' },
  weights: { nameField: 'value' },
  subcategories: { nameField: 'name' },
  glues: { nameField: 'name' },
  mixture_types: { nameField: 'name' },
  seams: { nameField: 'name' },
  textures: { nameField: 'name' },
  countries: { nameField: 'name' },
  sizes: { nameField: 'name' },
  materials: { nameField: 'name' },
  waterproofs: { nameField: 'name' },
  collections: { nameField: 'name' },
  volumes: { nameField: 'value' },
  product_weights: { nameField: 'value' },
  installation_types: { nameField: 'name' },
  shapes: { nameField: 'name' },
  applications: { nameField: 'name' },
  drying_times: { nameField: 'name' },
  package_weights: { nameField: 'value' },
  min_temps: { nameField: 'value' },
  max_temps: { nameField: 'value' },
  consumptions: { nameField: 'name' }
}

const filterTypes = Object.keys(filterConfigs)

// Маппинг singular к plural
const singularToPlural = {
  category: 'categories',
  brand: 'brands',
  color: 'colors',
  pattern: 'patterns',
  weight: 'weights',
  subcategory: 'subcategories',
  glue: 'glues',
  mixture_type: 'mixture_types',
  seam: 'seams',
  texture: 'textures',
  country: 'countries',
  size: 'sizes',
  material: 'materials',
  waterproof: 'waterproofs',
  collection: 'collections',
  volume: 'volumes',
  product_weight: 'product_weights',
  installation_type: 'installation_types',
  shape: 'shapes',
  application: 'applications',
  drying_time: 'drying_times',
  package_weight: 'package_weights',
  min_temp: 'min_temps',
  max_temp: 'max_temps',
  consumption: 'consumptions'
}

// Debounce функция
const debounce = (func, delay) => {
  let timeout
  return (...args) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => func(...args), delay)
  }
}

export const useBrandStore = defineStore('brand', () => {
  // Бренды для слайдера
  const brands = ref([])
  const brandsLoading = ref(false)

  // Доступные фильтры
  const filters = ref(Object.fromEntries(filterTypes.map(type => [type, []])))

  // Выбранные фильтры
  const selected = ref(Object.fromEntries(filterTypes.map(type => [type, []])))

  // Другие состояния
  const minPrice = ref('')
  const maxPrice = ref('')
  const sortOption = ref('default')
  const currentPage = ref(1)

  // Вычисляемые параметры запроса
  const queryParams = computed(() => {
    const params = {
      ...selected.value,
      min_price: minPrice.value,
      max_price: maxPrice.value,
      sort: sortOption.value,
      page: currentPage.value
    }

    // Преобразуем categories в category_ids для backend
    if (params.categories && params.categories.length > 0) {
      params.category_ids = params.categories
      delete params.categories
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

  // Выбранные фильтры для отображения
  const selectedFilters = computed(() => {
    const result = []
    filterTypes.forEach(type => {
      selected.value[type].forEach(id => {
        const item = filters.value[type].find(f => f.id === id)
        if (item) {
          const config = filterConfigs[type]
          result.push({ type, id, name: item[config.nameField] })
        }
      })
    })
    return result
  })

  // Действия
  const setFilters = (newFilters) => {
    Object.assign(filters.value, newFilters)
  }

  const handleFilterChange = (newFilters) => {
    filterTypes.forEach(type => {
      selected.value[type] = newFilters[type] || []
    })
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

  const removeFilter = (type, id) => {
    const pluralType = singularToPlural[type] || type
    if (selected.value[pluralType]) {
      selected.value[pluralType] = selected.value[pluralType].filter(i => i !== id)
    }
    handleFilterChange(selected.value)
  }

  const selectFilter = (type, value) => {
    const pluralType = singularToPlural[type] || type
    if (selected.value[pluralType]) {
      const index = selected.value[pluralType].indexOf(value)
      if (index === -1) {
        selected.value[pluralType].push(value)
      } else {
        selected.value[pluralType].splice(index, 1)
      }
    }
    debouncedHandleFilterChange(selected.value)
  }

  const resetFilters = () => {
    filterTypes.forEach(type => {
      selected.value[type] = []
    })
    sortOption.value = 'default'
    currentPage.value = 1
    Object.keys(filters.value).forEach(key => {
      filters.value[key] = []
    })
  }

  // Получение брендов для слайдера
  const fetchBrands = async () => {
    try {
      brandsLoading.value = true
      const config = useRuntimeConfig()
      const response = await $fetch(`${config.public.apiBase}/api/brands`)
      brands.value = response.data || response
    } catch (error) {
      console.error('Error fetching brands:', error)
      brands.value = []
    } finally {
      brandsLoading.value = false
    }
  }

  // Вычисляемые для обратной совместимости
  const selectedComputed = Object.fromEntries(
    filterTypes.map(type => [
      `selected${type.charAt(0).toUpperCase() + type.slice(1)}`,
      computed(() => selected.value[type])
    ])
  )

  return {
    // State
    brands,
    brandsLoading,
    filters,
    selected,
    minPrice,
    maxPrice,
    sortOption,
    currentPage,
    ...selectedComputed,
    // Getters
    queryParams,
    selectedFilters,
    // Actions
    fetchBrands,
    setFilters,
    handleFilterChange,
    debouncedHandleFilterChange,
    changePage,
    handleSortChange,
    removeFilter,
    selectFilter,
    resetFilters
  }
})