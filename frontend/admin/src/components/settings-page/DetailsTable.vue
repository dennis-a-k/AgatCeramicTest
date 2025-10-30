<template>
  <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    <div class="flex items-start justify-between">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Реквизиты компании</h3>
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
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">Организация</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.organization || 'Не указано' }}
        </span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">Адрес</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.adress || 'Не указано' }}
        </span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">ИНН</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.inn || 'Не указано' }}
        </span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">ОГРН</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.ogrn || 'Не указано' }}
        </span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">ОКАТО</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.okato || 'Не указано' }}
        </span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">ОКПО</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.okpo || 'Не указано' }}
        </span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">Банк</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.bank || 'Не указано' }}
        </span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">БИК Банка</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.bik || 'Не указано' }}
        </span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">к/с</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.ks || 'Не указано' }}
        </span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">р/с</span>
        <span class="text-right text-gray-500 text-theme-sm dark:text-gray-400">
          {{ details.rs || 'Не указано' }}
        </span>
      </div>
    </div>
  </div>
  <DetailsModal :is-visible="isModalVisible" :details="details" :errors="errors" @close="closeModal"
    @save="saveDetails" />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import SquarePenIcon from '@/icons/SquarePenIcon.vue'
import DetailsModal from './DetailsModal.vue'
import { useInformation } from '@/composables/useInformation'

const { information, loading, error, fetchInformation, updateInformation } = useInformation()

const details = ref({
  organization: '',
  adress: '',
  inn: '',
  ogrn: '',
  okato: '',
  okpo: '',
  bank: '',
  bik: '',
  ks: '',
  rs: '',
})
const isModalVisible = ref(false)
const errors = ref({})

const openModal = () => {
  isModalVisible.value = true
}

const closeModal = () => {
  isModalVisible.value = false
  errors.value = {}
}

const saveDetails = async (data) => {
  try {
    const result = await updateInformation(data)
    if (result.success) {
      details.value = result.data
      closeModal()
    } else {
      errors.value = result.errors || {}
    }
  } catch (error) {
    console.error('Error saving details:', error)
  }
}

const fetchDetails = async () => {
  await fetchInformation()
  details.value = information.value
}

onMounted(() => {
  fetchDetails()
})
</script>
