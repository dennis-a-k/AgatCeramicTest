<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            Редактировать контакты
          </h3>
          <form @submit.prevent="saveContacts" class="space-y-4">
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Телефон
              </label>
              <input type="tel" id="phone" v-model="form.phone" @input="formatPhone"
                autocomplete="phone" placeholder="+7 (___) ___-__-__"
                :class="inputClass(backendErrors.phone)" />
              <p v-if="backendErrors.phone" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.phone }}</p>
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Почта
              </label>
              <input type="email" id="email" v-model="form.email"
                :class="inputClass(backendErrors.email)" />
              <p v-if="backendErrors.email" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.email }}</p>
            </div>
            <div>
              <label for="telegram" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Telegram
              </label>
              <input type="text" id="telegram" v-model="form.telegram"
                :class="inputClass(backendErrors.telegram)" />
              <p v-if="backendErrors.telegram" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.telegram }}</p>
            </div>
            <div>
              <label for="whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                WhatsApp
              </label>
              <input type="text" id="whatsapp" v-model="form.whatsapp"
                :class="inputClass(backendErrors.whatsapp)" />
              <p v-if="backendErrors.whatsapp" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.whatsapp }}</p>
            </div>
            <div class="flex justify-end gap-3 pt-4">
              <Button variant="outline" @click="closeModal">Отмена</Button>
              <Button variant="primary" type="submit">
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
  contacts: {
    type: Object,
    default: () => ({ phone: '', email: '', telegram: '', whatsapp: '' })
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save', 'update:errors'])

const form = ref({ phone: '', email: '', telegram: '', whatsapp: '' })
const backendErrors = ref({})

const inputClass = (error) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

const formatPhone = (event) => {
  let value = event.target.value.replace(/\D/g, '');
  if (value.startsWith('7')) {
    value = value.substring(1);
  }
  if (value.length > 10) {
    value = value.substring(0, 10);
  }
  let formatted = '+7';
  if (value.length > 0) {
    formatted += ' (' + value.substring(0, 3);
  }
  if (value.length >= 4) {
    formatted += ') ' + value.substring(3, 6);
  }
  if (value.length >= 7) {
    formatted += '-' + value.substring(6, 8);
  }
  if (value.length >= 9) {
    formatted += '-' + value.substring(8, 10);
  }
  form.value.phone = formatted;
};

watch(() => props.isVisible, (newVal) => {
  if (newVal) {
    form.value = { ...props.contacts }
    backendErrors.value = { ...props.errors }
  } else {
    form.value = { phone: '', email: '', telegram: '', whatsapp: '' }
    backendErrors.value = {}
  }
})

watch(() => props.errors, (newErrors) => {
  backendErrors.value = { ...newErrors }
}, { deep: true })

const closeModal = () => {
  emit('close')
}

const saveContacts = () => {
  emit('save', { ...form.value })
}
</script>
