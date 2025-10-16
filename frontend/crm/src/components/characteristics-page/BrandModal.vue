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
              <input type="text" id="nameBrand" v-model="form.name" required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>
            <div>
              <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Страна
              </label>
              <input type="text" id="country" v-model="form.country"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Описание
              </label>
              <textarea id="description" v-model="form.description" rows="3"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
            </div>
            <div>
              <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Изображение (URL)
              </label>
              <input type="url" id="image" v-model="form.image"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
            </div>
            <div class="flex items-center">
              <input type="checkbox" id="is_active" v-model="form.is_active"
                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded" />
              <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                Активен
              </label>
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
import Modal from '@/components/profile/Modal.vue'
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
  brand: {
    type: Object,
    default: () => ({ name: '', country: '', description: '', is_active: true, image: '' })
  }
})

const emit = defineEmits(['close', 'save'])

const form = ref({ name: '', country: '', description: '', is_active: true, image: '' })

watch(() => props.isVisible, (newVal) => {
  if (newVal) {
    form.value = { ...props.brand }
  }
})

const closeModal = () => {
  emit('close')
}

const saveBrand = () => {
  emit('save', form.value)
}
</script>
