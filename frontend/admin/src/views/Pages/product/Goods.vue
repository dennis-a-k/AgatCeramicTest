<template>
    <AdminLayout>
        <ToastAlert :alerts="alerts" />
        <PageBreadcrumb :pageTitle="currentPageTitle" />
        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <GoodsHeader :totalItems="totalItems" :packageIcon="packageIcon" :downloadIcon="downloadIcon"
                    :plusIcon="plusIcon" @bulkUpload="handleBulkUpload" />
                <GoodsFilters :searchQuery="searchQuery" :categories="categories" :selectedItem="selectedCategory"
                    :isOpen="isOpen" :showFilter="showFilter" :checkboxSale="filters.sale.true"
                    :checkboxNoSale="filters.sale.false" :checkboxPublished="filters.published.true"
                    :checkboxNoPublished="filters.published.false" :searchIcon="searchIcon" :settingsIcon="settingsIcon"
                    @update:searchQuery="searchQuery = $event" @toggleDropdown="toggleDropdown"
                    @toggleItem="handleToggleItem" @update:showFilter="showFilter = $event"
                    @update:checkboxSale="filters.sale.true = $event"
                    @update:checkboxNoSale="filters.sale.false = $event"
                    @update:checkboxPublished="filters.published.true = $event"
                    @update:checkboxNoPublished="filters.published.false = $event" />
                <GoodsTable :loading="loading" :error="error" :products="products" :formatter="formatter" :sort="sort"
                    @sortBy="handleSortBy" @fetchProducts="fetchProducts" @edit="handleEdit" @delete="handleDelete" />
                <Pagination :currentPage="page" :totalPages="totalPages" @page-change="handlePageChange" class="px-6" />
            </div>
        </div>
        <BulkUploadModal :isVisible="showBulkUploadModal" @close="showBulkUploadModal = false" @upload="handleFileUpload" />
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import ToastAlert from '@/components/common/ToastAlert.vue'
import GoodsHeader from '@/components/goods-page/GoodsHeader.vue'
import GoodsFilters from '@/components/goods-page/GoodsFilters.vue'
import GoodsTable from '@/components/goods-page/GoodsTable.vue'
import BulkUploadModal from '@/components/goods-page/BulkUploadModal.vue'
import Pagination from '@/components/ui/Pagination.vue'
import { PackageIcon, DownloadIcon, PlusIcon, Settings2Icon, SearchIcon } from "../../../icons";
import { useGoods } from '@/composables/useGoods'
import { useCategories } from '@/composables/useCategories'
import { useProductAlerts } from '@/composables/useProductAlerts'

const router = useRouter()
const route = useRoute()
const currentPageTitle = ref('Товары')
const packageIcon = PackageIcon
const downloadIcon = DownloadIcon
const plusIcon = PlusIcon
const settingsIcon = Settings2Icon
const searchIcon = SearchIcon

const { alerts, showAlert } = useProductAlerts()

const {
    products,
    loading,
    error,
    sort,
    page,
    totalItems,
    totalPages,
    searchQuery,
    selectedCategory,
    filters,
    formatter,
    fetchProducts,
    handleSortBy,
    deleteProduct
} = useGoods()

const { categories, fetchCategories } = useCategories()

const showFilter = ref(false)
const isOpen = ref(false)
const showBulkUploadModal = ref(false)

const toggleDropdown = () => {
    isOpen.value = !isOpen.value
}

const handleToggleItem = (item) => {
    selectedCategory.value = item.value === null ? null : item
    isOpen.value = false
}

const handleEdit = (product) => {
    router.push(`/products/edit/${product.id}`)
}

const handlePageChange = (newPage) => {
    page.value = newPage
    fetchProducts()
}

const handleDelete = async (product, callback) => {
    const success = await deleteProduct(product.id)
    callback(success)
}

const handleBulkUpload = () => {
    showBulkUploadModal.value = true
}

const handleFileUpload = async (file) => {
    showBulkUploadModal.value = false
    // TODO: Send file to backend
    console.log('File selected:', file)
}

onMounted(() => {
    fetchCategories()
    fetchProducts()

    // Проверяем query параметры для отображения алерта
    if (route.query.success === 'created') {
        showAlert('success', 'Успешно', 'Товар создан')
    } else if (route.query.success === 'updated') {
        showAlert('success', 'Успешно', 'Товар обновлен')
    }
})
</script>
