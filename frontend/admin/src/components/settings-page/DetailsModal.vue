<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-xl w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            Редактировать реквизиты
          </h3>
          <form @submit.prevent="saveDetails" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="organization" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Организация
                </label>
                <input type="text" id="organization" v-model="form.organization"
                  :class="inputClass(backendErrors.organization)" />
                <p v-if="backendErrors.organization" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.organization }}</p>
              </div>
              <div>
                <label for="adress" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Адрес
                </label>
                <input type="text" id="adress" v-model="form.adress"
                  :class="inputClass(backendErrors.adress)" />
                <p v-if="backendErrors.adress" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.adress }}</p>
              </div>
              <div>
                <label for="inn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  ИНН
                </label>
                <input type="text" id="inn" v-model="form.inn"
                  :class="inputClass(backendErrors.inn)" />
                <p v-if="backendErrors.inn" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.inn }}</p>
              </div>
              <div>
                <label for="ogrn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  ОГРН
                </label>
                <input type="text" id="ogrn" v-model="form.ogrn"
                  :class="inputClass(backendErrors.ogrn)" />
                <p v-if="backendErrors.ogrn" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.ogrn }}</p>
              </div>
              <div>
                <label for="okato" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  ОКАТО
                </label>
                <input type="text" id="okato" v-model="form.okato"
                  :class="inputClass(backendErrors.okato)" />
                <p v-if="backendErrors.okato" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.okato }}</p>
              </div>
              <div>
                <label for="okpo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  ОКПО
                </label>
                <input type="text" id="okpo" v-model="form.okpo"
                  :class="inputClass(backendErrors.okpo)" />
                <p v-if="backendErrors.okpo" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.okpo }}</p>
              </div>
              <div>
                <label for="bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Банк
                </label>
                <input type="text" id="bank" v-model="form.bank"
                  :class="inputClass(backendErrors.bank)" />
                <p v-if="backendErrors.bank" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.bank }}</p>
              </div>
              <div>
                <label for="bik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  БИК Банка
                </label>
                <input type="text" id="bik" v-model="form.bik"
                  :class="inputClass(backendErrors.bik)" />
                <p v-if="backendErrors.bik" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.bik }}</p>
              </div>
              <div>
                <label for="ks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  к/с
                </label>
                <input type="text" id="ks" v-model="form.ks"
                  :class="inputClass(backendErrors.ks)" />
                <p v-if="backendErrors.ks" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.ks }}</p>
              </div>
              <div>
                <label for="rs" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  р/с
                </label>
                <input type="text" id="rs" v-model="form.rs"
                  :class="inputClass(backendErrors.rs)" />
                <p v-if="backendErrors.rs" class="mt-1.5 text-theme-xs text-error-500">{{ backendErrors.rs }}</p>
              </div>
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
  details: {
    type: Object,
    default: () => ({
      organization: '',
      adress: '',
      inn: '',
      ogrn: '',
      okato: '',
      okpo: '',
      bank: '',
      bik: '',
      ks: '',
      rs: ''
    })
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['close', 'save', 'update:errors'])

const form = ref({
  organization: '',
  adress: '',
  inn: '',
  ogrn: '',
  okato: '',
  okpo: '',
  bank: '',
  bik: '',
  ks: '',
  rs: ''
})
const backendErrors = ref({})

const inputClass = (error) => {
  return error ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none' : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
}

watch(() => props.isVisible, (newVal) => {
  if (newVal) {
    form.value = { ...props.details }
    backendErrors.value = { ...props.errors }
  } else {
    form.value = {
      organization: '',
      adress: '',
      inn: '',
      ogrn: '',
      okato: '',
      okpo: '',
      bank: '',
      bik: '',
      ks: '',
      rs: ''
    }
    backendErrors.value = {}
  }
})

watch(() => props.errors, (newErrors) => {
  backendErrors.value = { ...newErrors }
}, { deep: true })

const closeModal = () => {
  emit('close')
}

const saveDetails = () => {
  emit('save', { ...form.value })
}
</script>
