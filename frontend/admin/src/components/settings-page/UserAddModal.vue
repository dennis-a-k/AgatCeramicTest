<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            Добавить пользователя
          </h3>
          <form @submit.prevent="saveUser" class="space-y-4">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Имя
              </label>
              <input type="text" id="name" v-model="form.name"
                :class="inputClass('name')" required />
              <p v-if="fieldErrors.name" class="mt-1.5 text-theme-xs text-error-500">{{ fieldErrors.name }}</p>
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Почта
              </label>
              <input type="email" id="email" v-model="form.email"
                :class="inputClass('email')" required />
              <p v-if="fieldErrors.email" class="mt-1.5 text-theme-xs text-error-500">{{ fieldErrors.email }}</p>
            </div>
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Пароль
              </label>
              <input type="password" id="password" v-model="form.password"
                :class="inputClass('password')" required />
              <p v-if="fieldErrors.password" class="mt-1.5 text-theme-xs text-error-500">{{ fieldErrors.password }}</p>
            </div>
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Повторить пароль
              </label>
              <input type="password" id="password_confirmation" v-model="form.password_confirmation"
                :class="inputClass('password_confirmation')" required />
              <p v-if="fieldErrors.password_confirmation" class="mt-1.5 text-theme-xs text-error-500">{{ fieldErrors.password_confirmation }}</p>
            </div>
            <div>
              <div class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Роль</div>
              <div class="space-y-3">
                <label for="role-admin"
                  class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                  <input id="role-admin" type="radio" v-model="form.role" value="admin" class="sr-only" />
                  <div
                    :class="form.role === 'admin' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                    class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                    <span :class="form.role === 'admin' ? '' : 'opacity-0'" class="h-2 w-2 rounded-full bg-white"></span>
                  </div>
                  Администратор
                </label>
                <label for="role-moderator"
                  class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                  <input id="role-moderator" type="radio" v-model="form.role" value="moderator" class="sr-only" />
                  <div
                    :class="form.role === 'moderator' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                    class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                    <span :class="form.role === 'moderator' ? '' : 'opacity-0'" class="h-2 w-2 rounded-full bg-white"></span>
                  </div>
                  Модератор
                </label>
                <label for="role-user"
                  class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                  <input id="role-user" type="radio" v-model="form.role" value="user" class="sr-only" />
                  <div
                    :class="form.role === 'user' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                    class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                    <span :class="form.role === 'user' ? '' : 'opacity-0'" class="h-2 w-2 rounded-full bg-white"></span>
                  </div>
                  Пользователь
                </label>
              </div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <Button variant="outline" @click="closeModal">Отмена</Button>
              <Button variant="primary" type="submit">
                Добавить
              </Button>
            </div>
          </form>
        </div>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'
import Modal from '@/components/ui/Modal.vue'
import Button from '@/components/ui/Button.vue'

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save'])

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'user'
})

const fieldErrors = ref({})

const inputClass = (field) => {
  const hasError = fieldErrors.value[field]
  return hasError
    ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
    : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

watch(() => props.isVisible, (newVal) => {
  if (newVal) {
    form.name = ''
    form.email = ''
    form.password = ''
    form.password_confirmation = ''
    form.role = 'user'
    fieldErrors.value = {}
  }
})

watch(() => props.errors, (newErrors) => {
  fieldErrors.value = {}
  for (const [field, messages] of Object.entries(newErrors)) {
    if (Array.isArray(messages) && messages.length > 0) {
      fieldErrors.value[field] = messages[0]
    } else {
      fieldErrors.value[field] = messages
    }
  }
}, { deep: true })

const validateForm = () => {
  fieldErrors.value = {}
  let isValid = true

  if (!form.name.trim()) {
    fieldErrors.value.name = 'Имя обязательно'
    isValid = false
  }

  if (!form.email.trim()) {
    fieldErrors.value.email = 'Почта обязательна'
    isValid = false
  } else if (!/\S+@\S+\.\S+/.test(form.email)) {
    fieldErrors.value.email = 'Неверный формат почты'
    isValid = false
  }

  if (!form.password) {
    fieldErrors.value.password = 'Пароль обязателен'
    isValid = false
  } else if (form.password.length < 8) {
    fieldErrors.value.password = 'Пароль должен содержать минимум 8 символов'
    isValid = false
  }

  if (form.password !== form.password_confirmation) {
    fieldErrors.value.password_confirmation = 'Пароли не совпадают'
    isValid = false
  }

  return isValid
}

const closeModal = () => {
  emit('close')
}

const saveUser = () => {
  if (validateForm()) {
    emit('save', { ...form })
  }
}
</script>
