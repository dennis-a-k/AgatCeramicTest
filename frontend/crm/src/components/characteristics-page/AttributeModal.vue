<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            {{ isEditing ? 'Редактировать атрибут' : 'Создать атрибут' }}
          </h3>
          <form @submit.prevent="saveAttribute" class="space-y-4">
            <div>
              <label for="nameAttribute" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Название
              </label>
              <input type="text" id="nameAttribute" v-model="form.name" required :class="inputClass(backendErrors.name)" />
              <p v-if="backendErrors.name" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.name }}</p>
            </div>
            <div>
              <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Тип
              </label>
              <select id="type" v-model="form.type" required :class="inputClass(backendErrors.type)">
                <option value="">Выберите тип</option>
                <option value="string">Текст</option>
                <option value="number">Число</option>
                <option value="boolean">Да/Нет</option>
                <option value="select">Выбор</option>
              </select>
              <p v-if="backendErrors.type" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.type }}</p>
            </div>
            <div>
              <p class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Категории
              </p>
              <MultiSelect
                v-model="form.categories"
                :options="flatCategories"
                placeholder="Выберите категории..."
              />
              <p v-if="backendErrors.categories" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.categories }}</p>
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
import { ref, watch, computed, onMounted } from 'vue'
import Modal from '@/components/profile/Modal.vue'
import Button from '@/components/ui/Button.vue'
import MultiSelect from '@/components/ui/MultiSelect.vue'
import { useCategories } from '@/composables/useCategories'

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false
  },
  isEditing: {
    type: Boolean,
    default: false
  },
  attribute: {
    type: Object,
    default: () => ({ name: '', type: '' })
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save', 'update:errors'])

const { flatCategories, fetchCategories } = useCategories()
const form = ref({ name: '', type: '', categories: [] })
const backendErrors = ref({})

const inputClass = (error) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

watch(() => props.isVisible, async (newVal) => {
  if (newVal) {
    await fetchCategories()
    if (props.isEditing && props.attribute) {
      form.value = {
        ...props.attribute,
        categories: props.attribute.categories || []
      }
    } else {
      form.value = { name: '', type: '', categories: [] }
    }
    backendErrors.value = { ...props.errors }
  } else {
    // Reset form when modal closes
    form.value = { name: '', type: '', categories: [] }
    backendErrors.value = {}
  }
})

onMounted(async () => {
  await fetchCategories()
})

watch(() => props.errors, (newErrors) => {
  backendErrors.value = { ...newErrors }
}, { deep: true })

const closeModal = () => {
  emit('close')
}

const saveAttribute = () => {
  const dataToSend = {
    ...form.value,
    categories: form.value.categories.map(cat => cat.id)
  }
  emit('save', dataToSend)
}
</script>
