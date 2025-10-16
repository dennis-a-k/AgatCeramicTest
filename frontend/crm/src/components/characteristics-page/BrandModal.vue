<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            {{ isEditing ? 'Редактировать бренд' : 'Создать бренд' }}
          </h3>
          <form @submit.prevent="saveBrand" class="space-y-4">
            <div>
              <label for="nameBrand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Название
              </label>
              <input type="text" id="nameBrand" v-model="form.name" required :class="inputClass(backendErrors.name)" />
              <p v-if="backendErrors.name" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.name }}</p>
            </div>
            <div>
              <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Страна
              </label>
              <input type="text" id="country" v-model="form.country" :class="inputClass(backendErrors.country)" />
              <p v-if="backendErrors.country" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.country }}</p>
            </div>
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Описание
              </label>
              <textarea id="description" v-model="form.description" rows="3" :class="textareaClass"></textarea>
              <p v-if="backendErrors.description" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.description }}</p>
            </div>
            <BrandImageUpload ref="imageUploadRef" v-model="form.image" />
            <Checkbox id="is_active" label="Активен" v-model:checked="form.is_active" />
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
import { ref, watch, computed } from 'vue'
import Modal from '@/components/profile/Modal.vue'
import Button from '@/components/ui/Button.vue'
import Checkbox from '@/components/ui/Checkbox.vue'
import BrandImageUpload from './BrandImageUpload.vue'

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false
  },
  isEditing: {
    type: Boolean,
    default: false
  },
  brand: {
    type: Object,
    default: () => ({ name: '', country: '', description: '', is_active: true, image: '' })
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save', 'update:errors'])

const imageUploadRef = ref()
const form = ref({ name: '', country: '', description: '', is_active: true, image: '' })
const backendErrors = ref({})

const inputClass = (error) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

const textareaClass = computed(() => {
  return 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full resize-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
})

watch(() => props.isVisible, (newVal) => {
  if (newVal) {
    if (props.isEditing && props.brand) {
      form.value = { ...props.brand }
    } else {
      form.value = { name: '', country: '', description: '', is_active: true, image: '' }
    }
    backendErrors.value = { ...props.errors }
  } else {
    // Reset form when modal closes
    form.value = { name: '', country: '', description: '', is_active: true, image: '' }
    backendErrors.value = {}
  }
})

watch(() => props.errors, (newErrors) => {
  backendErrors.value = { ...newErrors }
}, { deep: true })

const closeModal = () => {
  emit('close')
}

const saveBrand = () => {
  emit('save', form.value, imageUploadRef.value?.newFile)
}
</script>
