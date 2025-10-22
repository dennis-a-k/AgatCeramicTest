<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            {{ isEditing ? 'Редактировать категорию' : 'Создать категорию' }}
          </h3>
          <form @submit.prevent="saveCategory" class="space-y-4">
            <div>
              <label for="nameCategory" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Название
              </label>
              <input type="text" id="nameCategory" v-model="form.name" required :class="inputClass(errors.name)" />
              <p v-if="errors.name" class="mt-1.5 text-theme-xs text-error-500">{{ errors.name }}</p>
            </div>
            <!-- Slug field removed - slug is generated from name -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Описание
              </label>
              <textarea id="description" v-model="form.description" rows="3"
                :class="inputClass(errors.description)"></textarea>
              <p v-if="errors.description" class="mt-1.5 text-theme-xs text-error-500">{{ errors.description }}</p>
            </div>
            <div>
              <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Порядок
              </label>
              <input type="number" id="order" v-model.number="form.order" min="0" :class="inputClass(errors.order)" />
              <p v-if="errors.order" class="mt-1.5 text-theme-xs text-error-500">{{ errors.order }}</p>
            </div>
            <div class="flex items-center">
              <Checkbox id="is_active" label="Относится к сантехнике" v-model:checked="form.is_plumbing" />
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <Button variant="outline" @click="closeModal">Отмена</Button>
              <Button variant="primary" type="submit">
                {{ isEditing ? 'Сохранить' : 'Создать' }}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import { ref, watch, inject, computed } from 'vue'
import Modal from '@/components/ui/Modal.vue'
import Button from '@/components/ui/Button.vue'
import Checkbox from '@/components/ui/Checkbox.vue'

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false
  },
  isEditing: {
    type: Boolean,
    default: false
  },
  category: {
    type: Object,
    default: () => ({ name: '', description: '', order: null, parent_id: null, is_plumbing: false })
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save'])

const categoriesComposable = inject('categoriesData')
const { categories, fetchCategories } = categoriesComposable

const form = ref({ name: '', description: '', order: null, parent_id: null, is_plumbing: false })

// Ref to store all categories for finding plumbing category
const allCategories = ref([])

// Function to fetch all categories without pagination
const fetchAllCategories = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_BASE_URL}/api/categories?per_page=1000`) // Large per_page to get all
    if (response.ok) {
      const data = await response.json()
      allCategories.value = data.data || []
    }
  } catch (error) {
    console.error('Error fetching all categories:', error)
  }
}

// Computed property to find the plumbing category by slug
const plumbingCategory = computed(() => {
  return allCategories.value.find(cat => cat.slug === 'santexnika')
})

const inputClass = (error) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

watch(() => props.isVisible, async (newVal) => {
  if (newVal) {
    // Fetch all categories if not already fetched
    if (allCategories.value.length === 0) {
      await fetchAllCategories()
    }

    if (props.isEditing && props.category) {
      form.value = {
        ...props.category,
        is_plumbing: props.category.parent_id === plumbingCategory.value?.id // Check by parent ID, but using the found category
      }
    } else {
      form.value = { name: '', description: '', order: null, parent_id: null, is_plumbing: false }
    }
  } else {
    form.value = { name: '', description: '', order: null, parent_id: null, is_plumbing: false }
  }
})

const closeModal = () => {
  emit('close')
}

const saveCategory = () => {
  emit('save', {
    ...form.value,
    is_plumbing: form.value.is_plumbing ? '1' : '0' // Convert boolean to string for backend
  })
}
</script>
