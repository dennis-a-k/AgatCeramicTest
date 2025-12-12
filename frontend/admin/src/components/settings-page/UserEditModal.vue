<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            Редактировать пользователя
          </h3>
          <form @submit.prevent="saveUser" class="space-y-4">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Имя
              </label>
              <input type="text" id="name" v-model="form.name" readonly :class="inputClass(backendErrors.name)" />
              <p v-if="backendErrors.name" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.name }}</p>
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Почта
              </label>
              <input type="email" id="email" v-model="form.email" readonly :class="inputClass(backendErrors.email)" />
              <p v-if="backendErrors.email" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.email }}</p>
            </div>
            <p v-if="isCurrentUser" class="text-sm text-warning-600 mb-4">Вы не можете изменить свою роль.</p>
            <div>
              <div class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Роль</div>
              <div class="space-y-3">
                <label for="role-admin" :class="{ 'opacity-50 cursor-not-allowed': isCurrentUser }"
                  class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                  <input id="role-admin" type="radio" v-model="form.role" value="admin" class="sr-only"
                    :disabled="isCurrentUser" />
                  <div
                    :class="form.role === 'admin' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                    class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                    <span :class="form.role === 'admin' ? '' : 'opacity-0'"
                      class="h-2 w-2 rounded-full bg-white"></span>
                  </div>
                  Администратор
                </label>
                <label for="role-moderator" :class="{ 'opacity-50 cursor-not-allowed': isCurrentUser }"
                  class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                  <input id="role-moderator" type="radio" v-model="form.role" value="moderator" class="sr-only"
                    :disabled="isCurrentUser" />
                  <div
                    :class="form.role === 'moderator' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                    class="hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-full border-[1.25px]">
                    <span :class="form.role === 'moderator' ? '' : 'opacity-0'"
                      class="h-2 w-2 rounded-full bg-white"></span>
                  </div>
                  Модератор
                </label>
                <label for="role-user" :class="{ 'opacity-50 cursor-not-allowed': isCurrentUser }"
                  class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                  <input id="role-user" type="radio" v-model="form.role" value="user" class="sr-only"
                    :disabled="isCurrentUser" />
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
              <Button variant="primary" type="submit" :disabled="isCurrentUser">
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
    default: () => ({ name: '', email: '', role: 'user' })
  },
  errors: {
    type: Object,
    default: () => ({})
  },
  isCurrentUser: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'save', 'update:errors'])

const form = ref({ name: '', email: '', role: 'user' })
const backendErrors = ref({})

const inputClass = (error) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

watch(() => props.isVisible, (newVal) => {
  if (newVal) {
    form.value = { ...props.user }
    backendErrors.value = { ...props.errors }
  } else {
    form.value = { name: '', email: '', role: 'user' }
    backendErrors.value = {}
  }
})

watch(() => props.errors, (newErrors) => {
  backendErrors.value = { ...newErrors }
}, { deep: true })

const closeModal = () => {
  emit('close')
}

const saveUser = () => {
  emit('save', { ...form.value })
}
</script>
