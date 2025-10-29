<template>
  <Modal v-if="isVisible" @close="closeModal">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-4">
            Информация о заявке
          </h3>
          <div v-if="call" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-brand-700 dark:text-brand-300">Имя клиента</label>
              <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ call.name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-brand-700 dark:text-brand-300">Email</label>
              <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ call.email || 'Не указан' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-brand-700 dark:text-brand-300">Телефон</label>
              <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ call.phone }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-brand-700 dark:text-brand-300">Источник</label>
              <p class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ call.source === 'client' ? 'Клиент' : 'Дизанер' }}
              </p>
            </div>
            <div>
              <label class="block text-sm font-medium text-brand-700 dark:text-brand-300">Комментарий</label>
              <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ call.comment || 'Нет комментария' }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-brand-700 dark:text-brand-300">Дата создания</label>
              <p class="mt-1 text-sm text-gray-900 dark:text-white">
                {{ new Date(call.created_at).toLocaleDateString('ru-RU') }} {{ new
                  Date(call.created_at).toLocaleTimeString('ru-RU') }}
              </p>
            </div>
            <div>
              <label class="block text-sm font-medium text-brand-700 dark:text-brand-300 mb-2">Статус</label>
              <span v-if="currentStatus === 'pending'"
                class="px-2.5 py-0.5 rounded-full font-bold text-md bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                Новый
              </span>
              <span v-else-if="currentStatus === 'processed'"
                class="px-2.5 py-0.5 rounded-full font-bold text-md bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                Обработан
              </span>
              <DropdownMenu :menu-items="getStatusMenuItems(call)" />
            </div>
          </div>
        </div>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import { ref, watch } from 'vue'
import Modal from '../ui/Modal.vue'
import DropdownMenu from '../common/DropdownMenu.vue'

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false
  },
  call: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'updateStatus'])

const currentStatus = ref(props.call?.status || 'pending')

watch(() => props.call?.status, (newStatus) => {
  if (newStatus) {
    currentStatus.value = newStatus
  }
})

const closeModal = () => {
  emit('close')
}

const handleUpdateStatus = (call, newStatus) => {
  currentStatus.value = newStatus
  emit('updateStatus', call, newStatus)
}

const getStatusMenuItems = (call) => [
  { label: 'Новый', onClick: () => handleUpdateStatus(call, 'pending') },
  { label: 'Обработан', onClick: () => handleUpdateStatus(call, 'processed') },
]
</script>
