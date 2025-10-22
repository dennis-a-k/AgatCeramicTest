<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            {{ isEditing ? 'Редактировать цвет' : 'Создать цвет' }}
          </h3>
          <form @submit.prevent="saveColor" class="space-y-4">
            <div>
              <label for="nameColor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Название
              </label>
              <input type="text" id="nameColor" v-model="form.name" required :class="inputClass(backendErrors.name)" />
              <p v-if="backendErrors.name" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.name }}</p>
            </div>
            <div>
              <label for="hex" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                HEX код
              </label>
              <div class="relative flex">
                <input type="text" id="hex" v-model="form.hex" placeholder="#000000" pattern="^#[a-fA-F0-9]{6}$"
                  required :class="inputClass(backendErrors.hex)" />
                <div
                  class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full border border-gray-300 dark:border-gray-600"
                  :style="{ backgroundColor: form.hex }"></div>
              </div>
              <p v-if="backendErrors.hex" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.hex }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Введите HEX код цвета в формате #RRGGBB
              </p>
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
import { ref, watch } from 'vue'
import Modal from '@/components/ui/Modal.vue'
import Button from '@/components/ui/Button.vue'

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false
  },
  isEditing: {
    type: Boolean,
    default: false
  },
  color: {
    type: Object,
    default: () => ({ name: '', hex: '#000000' })
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save', 'update:errors'])

const form = ref({ name: '', hex: '#000000' })
const backendErrors = ref({})

const inputClass = (error) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

watch(() => props.isVisible, (newVal) => {
  if (newVal) {
    if (props.isEditing && props.color) {
      form.value = { ...props.color }
    } else {
      form.value = { name: '', hex: '#000000' }
    }
    backendErrors.value = { ...props.errors }
  } else {
    // Reset form when modal closes
    form.value = { name: '', hex: '#000000' }
    backendErrors.value = {}
  }
})

watch(() => props.errors, (newErrors) => {
  backendErrors.value = { ...newErrors }
}, { deep: true })

const closeModal = () => {
  emit('close')
}

const saveColor = () => {
  emit('save', form.value)
}
</script>
