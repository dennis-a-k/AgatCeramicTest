<template>
    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />
        <div class="space-y-5 sm:space-y-6">
            <CallsStatistics :arrowDownIcon="arrowDownIcon" :arrowUpIcon="arrowUpIcon"
                :phoneOutgoingIcon="phoneOutgoingIcon" :calendarClockIcon="calendarClockIcon"
                :calendarDaysIcon="calendarDaysIcon" :circleCheckBigIcon="circleCheckBigIcon"
                :swatchBookIcon="swatchBookIcon" :usersRoundIcon="usersRoundIcon" :statistics="callStatistics"
                :onMonthChange="handleMonthChange" />
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <CallsHeader :totalItems="totalItems" :packageIcon="phoneCallIcon" :statuses="statuses"
                    :selectedItem="selectedStatus" :isOpen="isOpen" @toggleDropdown="toggleDropdown"
                    @toggleItem="handleToggleItem" />
                <CallsTable ref="callsTable" :loading="loading" :error="error" :calls="calls" :formatter="formatter"
                    @fetchCalls="fetchCalls" @edit="handleEdit" @delete="handleDelete"
                    @updateStatus="handleUpdateStatus" />
                <Pagination :currentPage="page" :totalPages="totalPages" @page-change="handlePageChange" class="px-6" />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import CallsStatistics from '@/components/calls-page/CallsStatistics.vue';
import CallsHeader from '@/components/calls-page/CallsHeader.vue';
import CallsTable from '@/components/calls-page/CallsTable.vue';
import Pagination from '@/components/ui/Pagination.vue'
import { useCalls } from '@/composables/useCalls'
import {
    ArrowDownIcon,
    ArrowUpIcon,
    CalendarClockIcon,
    CalendarDaysIcon,
    CircleCheckBigIcon,
    PhoneCallIcon,
    PhoneOutgoingIcon,
    UsersRoundIcon,
    SwatchBookIcon,
} from "../../../icons";

const router = useRouter()
const route = useRoute()
const currentPageTitle = ref('Обратные звонки')
const arrowDownIcon = ArrowDownIcon
const arrowUpIcon = ArrowUpIcon
const calendarClockIcon = CalendarClockIcon
const calendarDaysIcon = CalendarDaysIcon
const circleCheckBigIcon = CircleCheckBigIcon
const phoneCallIcon = PhoneCallIcon
const phoneOutgoingIcon = PhoneOutgoingIcon
const usersRoundIcon = UsersRoundIcon
const swatchBookIcon = SwatchBookIcon

const {
    calls,
    loading,
    error,
    page,
    totalItems,
    totalPages,
    selectedStatus,
    statuses,
    formatter,
    fetchCalls,
    fetchCallStatistics,
    deleteCall
} = useCalls()

const callsTable = ref(null)
const isOpen = ref(false)
const callStatistics = ref({
    current: {
        pending: 0,
        processed: 0,
        completed: 0,
        cancelled: 0
    },
    previous: {
        pending: 0,
        processed: 0,
        completed: 0,
        cancelled: 0
    },
    percentages: {
        pending: 0,
        processed: 0,
        completed: 0,
        cancelled: 0
    }
})

const toggleDropdown = () => {
    isOpen.value = !isOpen.value
}

const handleToggleItem = (item) => {
    selectedStatus.value = item.value === null ? null : item
    isOpen.value = false
}

const handleEdit = (call) => {
    router.push(`/calls/edit/${call.id}`)
}

const handleDelete = async (call, callback) => {
    const success = await deleteCall(call.id)
    callback(success)
}

const handlePageChange = (newPage) => {
    page.value = newPage
    fetchCalls()
}

const handleUpdateStatus = async (call, newStatus) => {
    const { updateCall } = useCalls()
    const result = await updateCall(call.id, { status: newStatus })
    if (result.success) {
        fetchCalls() // Перезагрузить список заявок
        loadStatistics() // Обновить статистику
    } else {
        console.error('Ошибка обновления статуса:', result.errors)
    }
}

const selectedMonth = ref(new Date().toISOString().slice(0, 7))

const loadStatistics = async (month) => {
    try {
        const stats = await fetchCallStatistics(month)
        callStatistics.value = stats
    } catch (err) {
        console.error('Ошибка загрузки статистики:', err)
    }
}

const handleMonthChange = (month) => {
    selectedMonth.value = month
    loadStatistics(month)
}

onMounted(async () => {
    await fetchCalls()
    loadStatistics()
    await nextTick()
    if (route.query.openModal) {
        callsTable.value?.openModalById(route.query.openModal)
    }
})
</script>
