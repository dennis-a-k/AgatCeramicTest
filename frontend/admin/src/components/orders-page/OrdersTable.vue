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
              @click="handleFetchProducts">
              Попробовать снова
            </button>
          </div>
          <div v-else-if="products.length === 0"
            class="flex flex-col justify-center items-center h-screen menu-item-icon-active text-center font-bold text-theme-xl m-5">
            Заказы не найдены
          </div>
          <table v-else class="min-w-full">
            <thead>
              <tr class="border-gray-100 border-y bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <th class="cursor-pointer px-5 py-3 text-center w-1/11 sm:px-6">
                  <div class="flex items-center gap-3">
                    <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">№ Заказа</p>
                  </div>
                </th>
                <th class="cursor-pointer px-5 py-3 text-center w-1/11 sm:px-6">
                  <div class="flex items-center gap-3">
                    <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Дата</p>
                  </div>
                </th>
                <th class="px-5 py-3 text-center w-2/11 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Клиент</p>
                </th>
                <th class="px-5 py-3 text-center w-2/11 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Товары</p>
                </th>
                <th class="px-5 py-3 text-center w-1/11 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Сумма</p>
                </th>
                <th class="px-5 py-3 text-center w-1/11 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Статус заказа</p>
                </th>
                <th class="px-5 py-3 text-center w-1/11 sm:px-6">
                  <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Действие</p>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="product in products" :key="product.id"
                class="border-t border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-5 py-4 sm:px-6">
                  <p class="font-medium text-center text-gray-800 text-theme-sm dark:text-white/90">
                    {{ product.article }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-2">
                  <a :href="`${FRONTEND_URL}/product/${product.slug}`" target="_blank">
                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                      {{ truncateText(product.name) }}
                    </p>
                  </a>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <p class="text-gray-500 text-center text-theme-sm dark:text-gray-400">
                    {{ product.product_code }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-1 text-center">
                  <span
                    class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
                    {{ product.category.name }}</span>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <p class="font-medium text-center text-gray-800 text-theme-sm dark:text-white/90">
                    {{ formatter.format(product.price) }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-6">
                  <p v-if="product.unit === 'шт'" class="text-gray-500 text-center text-theme-sm dark:text-gray-400">
                    шт.
                  </p>
                  <p v-else-if="product.unit === 'кв.м'"
                    class="text-gray-500 text-center text-theme-sm dark:text-gray-400">
                    м²
                  </p>
                  <p v-else class="text-gray-500 text-center text-theme-sm dark:text-gray-400">
                    {{ product.unit }}
                  </p>
                </td>
                <td class="px-5 py-4 sm:px-6 text-center">
                  <span v-if="product.is_published === true"
                    class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500">Опубликован</span>
                  <span v-if="product.is_published === false"
                    class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80">Скрыт</span>
                </td>
                <td class="px-5 py-4 sm:px-6 text-center">
                  <span v-if="product.is_sale === true"
                    class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500">Распродажа</span>
                  <span v-if="product.is_sale === false"
                    class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80">Нет</span>
                </td>
                <td class="px-5 py-4 sm:px-2 text-center">
                  <div
                    class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-success-50 hover:ring-success-300 hover:text-success-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-success-500/15 dark:hover:ring-success-500/50 dark:hover:text-success-500 cursor-pointer p-1 mr-3"
                    @click="handleEdit(product)">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                        fill=""></path>
                    </svg>
                  </div>
                  <div
                    class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-error-50 hover:ring-error-300 hover:text-error-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-error-500/15 dark:hover:ring-error-500/50 dark:hover:text-error-500 cursor-pointer p-1"
                    @click="openDeleteModal(product)">
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.54142 3.7915C6.54142 2.54886 7.54878 1.5415 8.79142 1.5415H11.2081C12.4507 1.5415 13.4581 2.54886 13.4581 3.7915V4.0415H15.6252H16.666C17.0802 4.0415 17.416 4.37729 17.416 4.7915C17.416 5.20572 17.0802 5.5415 16.666 4.7915H16.3752V8.24638V13.2464V16.2082C16.3752 17.4508 15.3678 18.4582 14.1252 18.4582H5.87516C4.63252 18.4582 3.62516 17.4508 3.62516 16.2082V13.2464V8.24638V5.5415H3.3335C2.91928 5.5415 2.5835 5.20572 2.5835 4.7915C2.5835 4.37729 2.91928 4.0415 3.3335 4.0415H4.37516H6.54142V3.7915ZM14.8752 13.2464V8.24638V5.5415H13.4581H12.7081H7.29142H6.54142H5.12516V8.24638V13.2464V16.2082C5.12516 16.6224 5.46095 16.9582 5.87516 16.9582H14.1252C14.5394 16.9582 14.8752 16.6224 14.8752 16.2082V13.2464ZM8.04142 4.0415H11.9581V3.7915C11.9581 3.37729 11.6223 3.0415 11.2081 3.0415H8.79142C8.37721 3.0415 8.04142 3.37729 8.04142 3.7915V4.0415ZM8.3335 7.99984C8.74771 7.99984 9.0835 8.33562 9.0835 8.74984V13.7498C9.0835 14.1641 8.74771 14.4998 8.3335 14.4998C7.91928 14.4998 7.5835 14.1641 7.5835 13.7498V8.74984C7.5835 8.33562 7.91928 7.99984 8.3335 7.99984ZM12.4168 8.74984C12.4168 8.33562 12.081 7.99984 11.6668 7.99984C11.2526 7.99984 10.9168 8.33562 10.9168 8.74984V13.7498C10.9168 14.1641 11.2526 14.4998 11.6668 14.4998C12.081 14.4998 12.4168 14.1641 12.4168 13.7498V8.74984Z"
                        fill=""></path>
                    </svg>
                  </div>
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
defineProps({
  loading: Boolean,
  error: String,
  products: Array,
  formatter: Object,
})

const FRONTEND_URL = import.meta.env.VITE_FRONTEND_URL

const emit = defineEmits(['fetchProducts', 'edit'])

const truncateText = (text, maxLength = 50) => {
  if (text.length <= maxLength) return text
  return text.substring(0, maxLength) + '...'
}

const handleFetchProducts = () => {
  emit('fetchProducts')
}

const handleEdit = (product) => {
  emit('edit', product)
}
</script>
