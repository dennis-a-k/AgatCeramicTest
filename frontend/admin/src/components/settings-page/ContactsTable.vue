<template>
  <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
    <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-start justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Контакты</h3>
        <div class="relative">
          <div class="relative">
            <div
              class="inline-flex items-center justify-center gap-2 rounded-lg transition bg-white text-brand-700 hover:bg-success-50 hover:ring-success-300 hover:text-success-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-success-500/15 dark:hover:ring-success-500/50 dark:hover:text-success-500 cursor-pointer p-1"
              @click="openModal">
              <SquarePenIcon />
            </div>
          </div>
        </div>
      </div>
      <div class="my-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
          <span class="text-gray-400 text-theme-xs">Ресурс</span>
          <span class="text-right text-gray-400 text-theme-xs">Данные</span>
        </div>
        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800"><span
            class="text-gray-500 text-theme-sm dark:text-gray-400">Телефон</span><span
            class="text-right text-gray-500 text-theme-sm dark:text-gray-400">{{ contacts.phone || 'Не указано' }}</span></div>
        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800"><span
            class="text-gray-500 text-theme-sm dark:text-gray-400">Почта</span><span
            class="text-right text-gray-500 text-theme-sm dark:text-gray-400">{{ contacts.email || 'Не указано' }}</span></div>
        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800"><span
            class="text-gray-500 text-theme-sm dark:text-gray-400">Telegram</span><span
            class="text-right text-gray-500 text-theme-sm dark:text-gray-400">{{ contacts.telegram ? '@' + contacts.telegram : 'Не указано' }}</span></div>
        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800"><span
            class="text-gray-500 text-theme-sm dark:text-gray-400">WhatsApp</span><span
            class="text-right text-gray-500 text-theme-sm dark:text-gray-400">{{ contacts.whatsapp ? '+' + contacts.whatsapp : 'Не указано' }}</span></div>
      </div>
    </div>
  </div>

  <ContactsModal
    :is-visible="isModalVisible"
    :contacts="contacts"
    :errors="errors"
    @close="closeModal"
    @save="saveContacts"
  />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import SquarePenIcon from '@/icons/SquarePenIcon.vue'
import ContactsModal from './ContactsModal.vue'
import { useInformation } from '@/composables/useInformation'

const { information, loading, error, fetchInformation, updateInformation } = useInformation()

const contacts = ref({ phone: '', email: '', telegram: '', whatsapp: '' })
const isModalVisible = ref(false)
const errors = ref({})

const openModal = () => {
  isModalVisible.value = true
}

const closeModal = () => {
  isModalVisible.value = false
  errors.value = {}
}

const saveContacts = async (data) => {
  try {
    const result = await updateInformation(data)
    if (result.success) {
      contacts.value = result.data
      closeModal()
    } else {
      errors.value = result.errors || {}
    }
  } catch (error) {
    console.error('Error saving contacts:', error)
  }
}

const fetchContacts = async () => {
  await fetchInformation()
  contacts.value = information.value
}

onMounted(() => {
  fetchContacts()
})
</script>
