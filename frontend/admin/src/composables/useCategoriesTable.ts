import { ref, inject, watch } from 'vue'
import { useCategoryModal } from './useCategoryModal'
import { useCategoryForm } from './useCategoryForm'
import { useCategoryAlerts } from './useCategoryAlerts'
import { useCategoryValidation } from './useCategoryValidation'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useCategoriesTable() {
  const categories = ref([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const searchQuery = ref('')
  const currentPage = ref(1)
  const perPage = ref(5)
  const totalPages = ref(1)
  const totalItems = ref(0)

  const fetchCategories = async (page = 1) => {
    loading.value = true
    error.value = null

    const params = new URLSearchParams()
    if (searchQuery.value.trim()) {
      params.append('search', searchQuery.value.trim())
    }
    params.append('page', page.toString())
    params.append('per_page', perPage.value.toString())

    const url = `${API_BASE_URL}/api/categories${params.toString() ? '?' + params.toString() : ''}`

    try {
      const response = await fetch(url, {
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        }
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      categories.value = data.data || []
      totalPages.value = data.last_page || 1
      totalItems.value = data.total || 0
      currentPage.value = data.current_page || 1
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error fetching categories:', err)
    } finally {
      loading.value = false
    }
  }

  const createCategory = async (categoryData: any) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/categories`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        },
        body: JSON.stringify(categoryData),
      })
      const data = await response.json()
      if (!response.ok) {
        if (response.status === 422) {
          return { success: false, errors: data.errors, message: data.message }
        }
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      await fetchCategories(currentPage.value)
      return { success: true, data }
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error creating category:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateCategory = async (id: number, categoryData: any) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/categories/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        },
        body: JSON.stringify(categoryData),
      })
      const data = await response.json()
      if (!response.ok) {
        if (response.status === 422) {
          return { success: false, errors: data.errors, message: data.message }
        }
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      await fetchCategories(currentPage.value)
      return { success: true, data }
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error updating category:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteCategory = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      const response = await fetch(`${API_BASE_URL}/api/categories/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        }
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      await fetchCategories(currentPage.value)
    } catch (err) {
      error.value = (err as Error).message
      console.error('Error deleting category:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  // Watchers
  let searchTimeout: number | null = null
  watch(searchQuery, (newQuery: string) => {
    if (searchTimeout) clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
      fetchCategories()
    }, 500)
  })

  return {
    categories,
    loading,
    error,
    searchQuery,
    currentPage,
    perPage,
    totalPages,
    totalItems,
    fetchCategories,
    createCategory,
    updateCategory,
    deleteCategory
  }
}
