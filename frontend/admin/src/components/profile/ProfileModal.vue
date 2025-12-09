<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            Редактировать профиль
          </h3>
          <form @submit.prevent="saveProfile" class="space-y-4">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                ФИО
              </label>
              <input type="text" id="name" v-model="form.name" required :class="inputClass(errors.name)" />
              <p v-if="errors.name" class="mt-1.5 text-theme-xs text-error-500">{{ errors.name }}</p>
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Почта
              </label>
              <input type="email" id="email" v-model="form.email" required :class="inputClass(errors.email)" />
              <p v-if="errors.email" class="mt-1.5 text-theme-xs text-error-500">{{ errors.email }}</p>
            </div>
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Новый пароль (оставьте пустым, если не хотите менять)
              </label>
              <input type="password" id="password" v-model="form.password" :class="inputClass(errors.password)" />
              <p v-if="errors.password" class="mt-1.5 text-theme-xs text-error-500">{{ errors.password }}</p>
            </div>
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Подтверждение пароля
              </label>
              <input type="password" id="password_confirmation" v-model="form.password_confirmation" :class="inputClass(errors.password_confirmation)" />
              <p v-if="errors.password_confirmation" class="mt-1.5 text-theme-xs text-error-500">{{ errors.password_confirmation }}</p>
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <Button variant="outline" @click="closeModal">Отмена</Button>
              <Button variant="primary" type="submit" :disabled="loading">
                Сохранить
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
  user: {
    type: Object,
    default: () => ({ name: '', email: '' })
  },
  errors: {
    type: Object,
    default: () => ({})
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'save'])

const form = ref({ name: '', email: '', password: '', password_confirmation: '' })

const inputClass = (error) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

watch(() => props.isVisible, (newVal) => {
  if (newVal && props.user) {
    form.value = {
      name: props.user.name || '',
      email: props.user.email || '',
      password: '',
      password_confirmation: ''
    }
  } else {
    form.value = { name: '', email: '', password: '', password_confirmation: '' }
  }
})

const closeModal = () => {
  emit('close')
}

const saveProfile = () => {
  emit('save', form.value)
}
</script>
