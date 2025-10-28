<template>
  <div class="p-4 border-t border-gray-100 dark:border-gray-800 sm:p-6">
    <div class="space-y-5">
      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
        style="overflow: visible;">
        <div class="max-w-full overflow-x-auto custom-scrollbar" style="overflow: visible;">
          <div v-if="loading" class="flex justify-center items-center h-screen">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-brand-500"></div>
          </div>
          <div v-else-if="error"
            class="flex flex-col justify-center items-center h-screen font-bold text-error-700 text-theme-xl dark:text-error-500">
            Ошибка при загрузке<br />
            <button
              class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300 mt-2"
              @click="handleFetchOrders">
              Попробовать снова
            </button>
          </div>
          <div v-else-if="orders.length === 0"
            class="flex flex-col justify-center items-center h-screen menu-item-icon-active text-center font-bold text-theme-xl m-5">
            Заказы не найдены
          </div>
          <table v-else class="min-w-full">
            <thead>
              <tr class="border-gray-100 border-y bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <th class="cursor-pointer px-5 py-3 text-left w-2/12 sm:px-6">
                  <div class="flex items-center gap-3">
                    <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">№ Заказа</p>
                  </div>
                </th>
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
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Товары</p>
                </th>
                <th class="px-5 py-3 text-left w-1/12 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Сумма</p>
                </th>
                <th class="px-5 py-3 text-center w-2/12 sm:px-6" colspan="2">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Статус заказа</p>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="order in orders" :key="order.id"
                class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-5 py-4 sm:px-6">
                  <router-link :to="`/orders/${order.order}`">
                    <p class="font-medium text-left text-gray-800 text-theme-sm dark:text-white/90">
                      {{ order.order }}
                    </p>
                  </router-link>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <p class="text-gray-500 text-left text-theme-sm dark:text-gray-400">
                    {{ new Date(order.created_at).toLocaleDateString('ru-RU') }}
                  </p>
                  <p class="text-gray-500 text-left text-theme-sm dark:text-gray-400">
                    {{ new Date(order.created_at).toLocaleTimeString('ru-RU') }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <span data-v-67f048e0="" class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                    {{ order.name }}
                  </span>
                  <span data-v-67f048e0="" class="block text-gray-500 text-theme-xs dark:text-gray-400">
                    {{ order.email }}
                  </span>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <p class="text-gray-500 text-left text-theme-sm dark:text-gray-400">
                    {{ order.phone }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <p class="text-gray-500 text-left text-theme-sm dark:text-gray-400">
                    {{ order.items ? order.items.length : 0 }} товаров
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <p class="font-medium text-left text-gray-800 text-theme-sm dark:text-white/90">
                    {{ formatter.format(order.total_amount) }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-6 text-center">
                  <span v-if="order.status === 'pending'"
                    class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                    Новый
                  </span>
                  <span v-else-if="order.status === 'processing'"
                    class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
                    Выполнение
                  </span>
                  <span v-else-if="order.status === 'shipped'"
                    class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                    Отправлен
                  </span>
                  <span v-else-if="order.status === 'return'"
                    class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">
                    Возврат
                  </span>
                  <span v-else-if="order.status === 'cancelled'"
                    class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80">
                    Отменён
                  </span>
                </td>
                <td class="px-5 py-4 sm:px-2 text-center">
                  <DropdownMenu :menu-items="getStatusMenuItems(order)" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import DropdownMenu from '../common/DropdownMenu.vue'

defineProps({
  loading: Boolean,
  error: String,
  orders: Array,
  formatter: Object,
})

const emit = defineEmits(['fetchOrders', 'edit', 'updateStatus'])

const handleFetchOrders = () => {
  emit('fetchOrders')
}

const handleUpdateStatus = (order, newStatus) => {
  emit('updateStatus', order, newStatus)
}

const getStatusMenuItems = (order) => [
  { label: 'Новый', onClick: () => handleUpdateStatus(order, 'pending') },
  { label: 'Выполнение', onClick: () => handleUpdateStatus(order, 'processing') },
  { label: 'Отправлен', onClick: () => handleUpdateStatus(order, 'shipped') },
  { label: 'Отменён', onClick: () => handleUpdateStatus(order, 'cancelled') },
  { label: 'Возврат', onClick: () => handleUpdateStatus(order, 'return') },
]
</script>
