import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCategoryStore = defineStore('category', () => {
  // Доступные фильтры
  const filters = ref({
    brands: [],
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

  // Выбранные фильтры
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

  // Debounce функция
  const debounce = (func, delay) => {
    let timeout
    return (...args) => {
      clearTimeout(timeout)
      timeout = setTimeout(() => func(...args), delay)
    }
  }

  // Вычисляемые параметры запроса
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

  // Выбранные фильтры для отображения
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

  // Действия
  const setFilters = (newFilters) => {
    Object.assign(filters.value, newFilters)
  }

  const handleFilterChange = (newFilters) => {
    selectedBrands.value = newFilters.brands || []
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

  const removeFilter = (type, id) => {
    const filterMap = {
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
    }
    if (filterMap[type]) {
      filterMap[type].value = filterMap[type].value.filter(i => i !== id)
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

  const selectFilter = (type, value) => {
    const filterMap = {
      brand: selectedBrands,
      color: selectedColors,
      pattern: selectedPatterns,
      weight: selectedWeights,
      subcategory: selectedSubcategories,
      glue: selectedGlues,
      mixture_type: selectedMixtureTypes,
      seam: selectedSeams,
      texture: selectedTextures,
      country: selectedCountries,
      size: selectedSizes,
      material: selectedMaterials,
      waterproof: selectedWaterproofs,
      collection: selectedCollections,
      volume: selectedVolumes,
      product_weight: selectedProductWeights,
      installation_type: selectedInstallationTypes,
      shape: selectedShapes,
      application: selectedApplications,
      drying_time: selectedDryingTimes,
      package_weight: selectedPackageWeights,
      min_temp: selectedMinTemps,
      max_temp: selectedMaxTemps,
      consumption: selectedConsumptions
    }
    if (filterMap[type]) {
      const index = filterMap[type].value.indexOf(value)
      if (index === -1) {
        filterMap[type].value.push(value)
      } else {
        filterMap[type].value.splice(index, 1)
      }
    }
    debouncedHandleFilterChange({
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

  const resetFilters = () => {
    // Сбрасываем выбранные фильтры
    selectedBrands.value = []
    selectedColors.value = []
    selectedPatterns.value = []
    selectedWeights.value = []
    selectedSubcategories.value = []
    selectedGlues.value = []
    selectedMixtureTypes.value = []
    selectedSeams.value = []
    selectedTextures.value = []
    selectedCountries.value = []
    selectedSizes.value = []
    selectedMaterials.value = []
    selectedWaterproofs.value = []
    selectedCollections.value = []
    selectedVolumes.value = []
    selectedProductWeights.value = []
    selectedInstallationTypes.value = []
    selectedShapes.value = []
    selectedApplications.value = []
    selectedDryingTimes.value = []
    selectedPackageWeights.value = []
    selectedMinTemps.value = []
    selectedMaxTemps.value = []
    selectedConsumptions.value = []
    sortOption.value = 'default'
    currentPage.value = 1
    // Сбрасываем доступные фильтры
    Object.keys(filters.value).forEach(key => {
      filters.value[key] = []
    })
  }

  return {
    // State
    filters,
    selectedBrands,
    minPrice,
    maxPrice,
    sortOption,
    currentPage,
    selectedColors,
    selectedPatterns,
    selectedWeights,
    selectedSubcategories,
    selectedGlues,
    selectedMixtureTypes,
    selectedSeams,
    selectedTextures,
    selectedCountries,
    selectedSizes,
    selectedMaterials,
    selectedWaterproofs,
    selectedCollections,
    selectedVolumes,
    selectedProductWeights,
    selectedInstallationTypes,
    selectedShapes,
    selectedApplications,
    selectedDryingTimes,
    selectedPackageWeights,
    selectedMinTemps,
    selectedMaxTemps,
    selectedConsumptions,
    // Getters
    queryParams,
    selectedFilters,
    // Actions
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