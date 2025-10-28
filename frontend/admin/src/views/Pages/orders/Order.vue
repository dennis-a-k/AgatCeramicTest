<template>
    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />
        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Детали заказа #{{ orderId }}
                        </h2>
                        <div v-if="order" class="flex items-center space-x-4">
                            <span v-if="order.status === 'pending'"
                                class="px-2.5 py-0.5 rounded-full font-bold text-md bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                                Новый
                            </span>
                            <span v-else-if="order.status === 'processing'"
                                class="px-2.5 py-0.5 rounded-full font-bold text-md bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
                                Выполнение
                            </span>
                            <span v-else-if="order.status === 'shipped'"
                                class="px-2.5 py-0.5 rounded-full font-bold text-md bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                Отправлен
                            </span>
                            <span v-else-if="order.status === 'return'"
                                class="px-2.5 py-0.5 rounded-full font-bold text-md bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">
                                Возврат
                            </span>
                            <span v-else-if="order.status === 'cancelled'"
                                class="px-2.5 py-0.5 rounded-full font-bold text-md bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80">
                                Отменён
                            </span>
                            <OrderDropdown @update-status="handleUpdateStatus" />
                        </div>
                    </div>
                    <div v-if="loading" class="flex justify-center items-center h-64">
                        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-brand-500"></div>
                    </div>
                    <div v-else-if="error" class="text-center text-red-500">
                        Ошибка загрузки заказа: {{ error }}
                    </div>
                    <div v-else-if="order" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900 dark:text-white mb-2">Информация о заказе</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Номер заказа: <span
                                        class="font-medium">{{ order.order }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Дата создания: <span
                                        class="font-medium">{{ formatDate(order.created_at) }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Статус:
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
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Общая сумма: <span
                                        class="font-medium text-error-600 dark:text-error-400">{{ formatter.format(order.total_amount) }}</span></p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900 dark:text-white mb-2">Информация о клиенте</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Имя: <span class="font-medium">{{
                                    order.name }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Email: <span class="font-medium">{{
                                    order.email }}</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Телефон: <span
                                        class="font-medium">{{ order.phone }}</span></p>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-4">Товары в заказе</h3>
                            <div class="space-y-4">
                                <div v-for="item in order.items" :key="item.id"
                                    class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                                    <div class="flex items-center space-x-4">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ item.product.article }}
                                        </p>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ item.product.name }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Количество: {{ item.quantity }}
                                                <span v-if="item.product_unit === 'кв.м'">м²</span>
                                                <span v-else>{{ item.product_unit }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900 dark:text-white">{{
                                            formatter.format(item.price) }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Итого: {{
                                            formatter.format(item.price * item.quantity) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="order.comment" class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                            <h3 class="font-medium text-gray-900 dark:text-white mb-4">Комментарий к заказу</h3>
                            <div class="space-y-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.comment }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import OrderDropdown from '@/components/orders-page/OrderDropdown.vue'
import { useOrders } from '@/composables/useOrders'

const route = useRoute()
const orderId = ref(route.params.id)
const currentPageTitle = ref(`Заказ #${orderId.value}`)

const {
    formatter,
    fetchOrderById,
    updateOrder
} = useOrders()

const order = ref(null)
const loading = ref(true)
const error = ref(null)

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString('ru-RU')
}

const handleUpdateStatus = async (newStatus) => {
    if (!order.value) return

    const result = await updateOrder(order.value.id, { status: newStatus })
    if (result.success) {
        order.value.status = newStatus
    } else {
        console.error('Ошибка обновления статуса:', result.errors)
    }
}

const loadOrder = async () => {
    try {
        loading.value = true
        const result = await fetchOrderById(orderId.value)
        if (result.success) {
            order.value = result.data
        } else {
            error.value = result.errors || 'Ошибка загрузки заказа'
        }
    } catch (err) {
        error.value = 'Ошибка загрузки заказа'
        console.error(err)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    loadOrder()
})
</script>