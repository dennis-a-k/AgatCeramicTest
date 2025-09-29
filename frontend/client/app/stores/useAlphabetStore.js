import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useRuntimeConfig } from '#imports'

export const useAlphabetStore = defineStore('alphabet', () => {
  const brands = ref([])
  const groupedBrands = ref({})
  const loading = ref(false)
  const error = ref(null)
  const config = useRuntimeConfig()

  const fetchBrands = async () => {
    loading.value = true
    error.value = null
    try {
      const response = await $fetch(`${config.public.apiBase}/api/brands/categories`)
      brands.value = response
      groupBrands()
    } catch (err) {
      error.value = err.message
    } finally {
      loading.value = false
    }
  }

  const groupBrands = () => {
    const groups = {
      '0-9': [],
      'РУС': []
    }

    // Инициализируем группы для латинских букв
    for (let i = 65; i <= 90; i++) {
      groups[String.fromCharCode(i)] = []
    }

    brands.value.forEach(brand => {
      const firstChar = brand.name.charAt(0).toUpperCase()
      if (/\d/.test(firstChar)) {
        groups['0-9'].push(brand)
      } else if (/[А-ЯЁ]/.test(firstChar)) {
        groups['РУС'].push(brand)
      } else if (/[A-Z]/.test(firstChar)) {
        groups[firstChar].push(brand)
      }
    })

    // Сортируем бренды в каждой группе
    Object.keys(groups).forEach(key => {
      groups[key].sort((a, b) => a.name.localeCompare(b.name))
    })

    groupedBrands.value = groups
  }

  const getBrandUrl = (brand) => {
    return `/brand/${brand.slug}`
  }

  return {
    brands,
    groupedBrands,
    loading,
    error,
    fetchBrands,
    getBrandUrl
  }
})