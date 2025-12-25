import { ref, computed } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

interface Category {
  id: number
  name: string
  children?: Category[]
  attributes?: Attribute[]
}

interface Attribute {
  id: number
  name: string
  type: 'string' | 'number' | 'boolean'
}

interface CategoryItem {
  id: string
  value: number | null
  label: string
}

export function useCategories() {
  const allCategories = ref<Category[]>([])

  const fetchCategories = async () => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/admin/categories?per_page=all`, {
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        }
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      allCategories.value = data.data || data.categories || data
    } catch (err) {
      console.error('Ошибка загрузки категорий:', err)
    }
  }

  const fetchCategoryAttributes = async (categoryId: number) => {
    try {
      const response = await fetch(`${API_BASE_URL}/api/admin/categories/${categoryId}`, {
        headers: {
          'Accept': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        }
      })
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const data = await response.json()
      return data.attributes || []
    } catch (err) {
      console.error('Ошибка загрузки атрибутов категории:', err)
      return []
    }
  }

  // Функция для построения плоского списка категорий с дочерними
  const buildCategoryList = (categories: Category[], level = 0): CategoryItem[] => {
    const result: CategoryItem[] = []
    categories.forEach((cat, index) => {
      const indent = '  '.repeat(level) // отступ для дочерних
      result.push({
        id: `${level}-${index + 2}`,
        value: cat.id,
        label: `${indent}${cat.name}`
      })
      if (cat.children && cat.children.length > 0) {
        result.push(...buildCategoryList(cat.children, level + 1))
      }
    })
    return result
  }

  // Селект
  const categories = computed(() => {
    const cats = buildCategoryList(allCategories.value)
    return [{ id: '1', value: null, label: 'Все категории' }, ...cats]
  })

  // Плоский список всех категорий для мультиселекта
  const flatCategories = computed(() => {
    const result: Category[] = []

    const flatten = (cats: Category[]) => {
      cats.forEach(cat => {
        result.push(cat)
        if (cat.children && cat.children.length > 0) {
          flatten(cat.children)
        }
      })
    }

    flatten(allCategories.value)
    return result
  })

  return {
    allCategories,
    categories,
    flatCategories,
    fetchCategories,
    fetchCategoryAttributes
  }
}
