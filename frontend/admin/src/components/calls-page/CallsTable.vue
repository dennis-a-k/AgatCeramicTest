<template>
  <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
    <div class="space-y-5">
      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
          <div v-if="loading" class="flex justify-center items-center h-screen">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-brand-500"></div>
          </div>
          <div v-else-if="error"
            class="flex flex-col justify-center items-center h-screen font-bold text-error-700 text-theme-xl dark:text-error-500">
            Ошибка при загрузке<br />
            <button
              class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300 mt-2"
              @click="handleFetchCalls">
              Попробовать снова
            </button>
          </div>
          <div v-else-if="calls.length === 0"
            class="flex flex-col justify-center items-center h-screen menu-item-icon-active text-center font-bold text-theme-xl m-5">
            Заявок на звонок не найдено
          </div>
          <table v-else class="min-w-full">
            <thead>
              <tr class="border-gray-100 border-y bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <th class="cursor-pointer px-5 py-3 text-left w-1/12 sm:px-6">
                  <div class="flex items-left gap-3">
                    <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Дата</p>
                  </div>
                </th>
                <th class="px-5 py-3 text-left w-3/12 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Клиент</p>
                </th>
                <th class="px-5 py-3 text-left w-2/12 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Телефон</p>
                </th>
                <th class="px-5 py-3 text-left w-1/12 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Источник</p>
                </th>
                <th class="px-5 py-3 text-left w-3/12 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Комментарий</p>
                </th>
                <th class="px-5 py-3 text-center w-2/12 sm:px-6" colspan="2">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Статус</p>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="call in calls" :key="call.id"
                class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-5 py-4 sm:px-6">
                  <p class="text-gray-500 text-left text-theme-sm dark:text-gray-400">
                    {{ new Date(call.created_at).toLocaleDateString('ru-RU') }}
                  </p>
                  <p class="text-gray-500 text-left text-theme-sm dark:text-gray-400">
                    {{ new Date(call.created_at).toLocaleTimeString('ru-RU') }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-6 cursor-pointer hover:text-brand-500" @click="openModal(call)">
                  <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                    {{ call.name }}
                  </span>
                  <span v-if="call.email" class="block text-gray-500 text-theme-xs dark:text-gray-400">
                    {{ call.email }}
                  </span>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <p class="text-gray-500 text-left text-theme-sm dark:text-gray-400">
                    {{ call.phone }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <span v-if="call.source === 'client'"
                    class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
                    Клиент
                  </span>
                  <span v-else-if="call.source === 'designer'"
                    class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-brand-50 text-brand-500 dark:bg-brand-500/15 dark:text-brand-500">
                    Дизанер
                  </span>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <p class="text-gray-500 text-left text-theme-sm dark:text-gray-400">
                    {{ call.comment || 'Нет комментария' }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-6 text-center">
                  <span v-if="call.status === 'pending'"
                    class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                    Новый
                  </span>
                  <span v-else-if="call.status === 'processed'"
                    class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                    Обработан
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <CallModal
      :is-visible="showModal"
      :call="selectedCall"
      @close="closeModal"
      @update-status="handleUpdateStatus"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import CallModal from './CallModal.vue'

const props = defineProps({
  loading: Boolean,
  error: String,
  calls: Array,
  formatter: Object,
})

const router = useRouter()
const route = useRoute()

const emit = defineEmits(['fetchCalls', 'edit', 'updateStatus'])

const showModal = ref(false)
const selectedCall = ref(null)

const handleFetchCalls = () => {
  emit('fetchCalls')
}

const handleUpdateStatus = (call, newStatus) => {
  emit('updateStatus', call, newStatus)
}

const openModal = (call) => {
  selectedCall.value = call
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedCall.value = null
  const newQuery = { ...route.query }
  delete newQuery.openModal
  router.replace({ query: newQuery })
}

const openModalById = (id) => {
  const call = props.calls.find(c => c.id == id)
  if (call) {
    openModal(call)
  }
}

defineExpose({
  openModalById
})
</script>
