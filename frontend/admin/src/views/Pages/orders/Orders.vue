<template>
    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />
        <div class="space-y-5 sm:space-y-6">
            <OrdersStatistics :arrowDownIcon="arrowDownIcon" :arrowUpIcon="arrowUpIcon"
                :shoppingCartIcon="shoppingCartIcon" :calendarClockIcon="calendarClockIcon"
                :calendarDaysIcon="calendarDaysIcon" :packageCheckIcon="packageCheckIcon"
                :statistics="orderStatistics" />
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <OrdersHeader :totalItems="totalItems" :packageIcon="shoppingCartIcon" :downloadIcon="downloadIcon" />
                <OrdersFilters :searchQuery="searchQuery" :statuses="statuses" :selectedItem="selectedStatus"
                    :isOpen="isOpen" :searchIcon="searchIcon"
                    @update:searchQuery="searchQuery = $event" @toggleDropdown="toggleDropdown"
                    @toggleItem="handleToggleItem" @update:showFilter="showFilter = $event" />
                <OrdersTable :loading="loading" :error="error" :orders="orders" :formatter="formatter"
                    @fetchOrders="fetchOrders" @updateStatus="handleUpdateStatus" />
                <Pagination :currentPage="page" :totalPages="totalPages" @page-change="handlePageChange" class="px-6" />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import OrdersStatistics from '@/components/orders-page/OrdersStatistics.vue';
import OrdersHeader from '@/components/orders-page/OrdersHeader.vue';
import OrdersFilters from '@/components/orders-page/OrdersFilters.vue';
import OrdersTable from '@/components/orders-page/OrdersTable.vue';
import Pagination from '@/components/ui/Pagination.vue'
import { useOrders } from '@/composables/useOrders'
import {
    ArrowDownIcon,
    ArrowUpIcon,
    CalendarClockIcon,
    CalendarDaysIcon,
    PackageCheckIcon,
    ShoppingCartIcon,
    DownloadIcon,
    SearchIcon
} from "../../../icons";

const currentPageTitle = ref('Заказы')
const arrowDownIcon = ArrowDownIcon
const arrowUpIcon = ArrowUpIcon
const calendarClockIcon = CalendarClockIcon
const calendarDaysIcon = CalendarDaysIcon
const packageCheckIcon = PackageCheckIcon
const shoppingCartIcon = ShoppingCartIcon
const downloadIcon = DownloadIcon
const searchIcon = SearchIcon

const {
    orders,
    loading,
    error,
    page,
    totalItems,
    totalPages,
    searchQuery,
    selectedStatus,
    statuses,
    formatter,
    fetchOrders,
    fetchOrderStatistics,
} = useOrders()

const showFilter = ref(false)
const isOpen = ref(false)
const orderStatistics = ref({
    current: {
        pending: 0,
        processing: 0,
        shipped: 0,
        total_amount: 0
    },
    previous: {
        pending: 0,
        processing: 0,
        shipped: 0,
        total_amount: 0
    },
    percentages: {
        pending: 0,
        processing: 0,
        shipped: 0,
        total_amount: 0
    }
})

const toggleDropdown = () => {
    isOpen.value = !isOpen.value
}

const handleToggleItem = (item) => {
    selectedStatus.value = item.value === null ? null : item
    isOpen.value = false
}

const handlePageChange = (newPage) => {
    page.value = newPage
    fetchOrders()
}

const handleUpdateStatus = async (order, newStatus) => {
    const { updateOrder } = useOrders()
    const result = await updateOrder(order.id, { status: newStatus })
    if (result.success) {
        fetchOrders() // Перезагрузить список заказов
        loadStatistics() // Обновить статистику
    } else {
        console.error('Ошибка обновления статуса:', result.errors)
    }
}

const loadStatistics = async () => {
    try {
        const stats = await fetchOrderStatistics()
        orderStatistics.value = stats
    } catch (err) {
        console.error('Ошибка загрузки статистики:', err)
    }
}

onMounted(() => {
    fetchOrders()
    loadStatistics()
})
</script>
