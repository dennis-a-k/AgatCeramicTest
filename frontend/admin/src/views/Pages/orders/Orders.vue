<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div class="space-y-5 sm:space-y-6">
      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <OrdersHeader
          :totalItems="totalItems"
          :packageIcon="shoppingCartIcon"
          :downloadIcon="downloadIcon"
        />
        <OrdersFilters
          :searchQuery="searchQuery"
          :statuses="statuses"
          :selectedItem="selectedStatus"
          :isOpen="isOpen"
          :showFilter="showFilter"
          :checkboxSale="filters.sale.true"
          :checkboxNoSale="filters.sale.false"
          :checkboxPublished="filters.published.true"
          :checkboxNoPublished="filters.published.false"
          :searchIcon="searchIcon"
          :settingsIcon="settingsIcon"
          @update:searchQuery="searchQuery = $event"
          @toggleDropdown="toggleDropdown"
          @toggleItem="handleToggleItem"
          @update:showFilter="showFilter = $event"
          @update:checkboxSale="filters.sale.true = $event"
          @update:checkboxNoSale="filters.sale.false = $event"
          @update:checkboxPublished="filters.published.true = $event"
          @update:checkboxNoPublished="filters.published.false = $event"
        />
        <OrdersTable
          :loading="loading"
          :error="error"
          :orders="orders"
          :formatter="formatter"
          @fetchOrders="fetchOrders"
          @edit="handleEdit"
          @delete="handleDelete"
          @updateStatus="handleUpdateStatus"
        />
        <GoodsPagination
          :page="page"
          :totalPages="totalPages"
          :totalItems="totalItems"
          :itemsPerPage="itemsPerPage"
          :visiblePages="visiblePages"
          @prevPage="handlePrevPage"
          @nextPage="handleNextPage"
          @goToPage="handleGoToPage"
        />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import OrdersHeader from '@/components/orders-page/OrdersHeader.vue';
import OrdersFilters from '@/components/orders-page/OrdersFilters.vue';
import OrdersTable from '@/components/orders-page/OrdersTable.vue';
import GoodsPagination from '@/components/goods-page/GoodsPagination.vue'
import { ShoppingCartIcon, DownloadIcon, Settings2Icon, SearchIcon } from "../../../icons";
import { useOrders } from '@/composables/useOrders'

const router = useRouter()
const currentPageTitle = ref('Заказы')
const shoppingCartIcon = ShoppingCartIcon
const downloadIcon = DownloadIcon
const settingsIcon = Settings2Icon
const searchIcon = SearchIcon

const {
  orders,
  loading,
  error,
  page,
  itemsPerPage,
  totalItems,
  totalPages,
  searchQuery,
  selectedStatus,
  statuses,
  filters,
  visiblePages,
  formatter,
  fetchOrders,
  handlePrevPage,
  handleNextPage,
  handleGoToPage,
  deleteOrder
} = useOrders()

const showFilter = ref(false)
const isOpen = ref(false)

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}

const handleToggleItem = (item) => {
  selectedStatus.value = item.value === null ? null : item
  isOpen.value = false
}

const handleEdit = (order) => {
  router.push(`/orders/edit/${order.id}`)
}

const handleDelete = async (order, callback) => {
  const success = await deleteOrder(order.id)
  callback(success)
}

const handleUpdateStatus = async (order, newStatus) => {
  const { updateOrder } = useOrders()
  const result = await updateOrder(order.id, { status: newStatus })
  if (result.success) {
    fetchOrders() // Перезагрузить список заказов
  } else {
    console.error('Ошибка обновления статуса:', result.errors)
  }
}

onMounted(() => {
  fetchOrders()
})
</script>
