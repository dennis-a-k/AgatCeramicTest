<template>
    <AdminLayout>
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
        <div v-else-if="!loading && !error && !product.id"
            class="flex flex-col justify-center items-center h-screen menu-item-icon-active text-center font-bold text-theme-xl m-5">
            Товар не найден
        </div>
        <div v-else>
            <PageBreadcrumb :pageTitle="currentPageTitle + product.article" />
            <form @submit.prevent="onSubmit" class="space-y-6">
                <ProductCharacteristics :product="product" :errors="errors" :categories="categories" :brands="brands"
                    :colors="colors" />
                <CeramicCharacteristics :product="product" :errors="errors" :categories="categories"
                    :is-ceramic-category="isCeramicCategory" />
                <AdditionalCharacteristics v-if="product.attribute_values.length !== 0" :product="product" />
                <ProductImageUpload ref="imageUploadRef" v-model="product.images" />
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <button @click="goBack"
                        class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                        Отмена
                    </button>
                    <button type="submit" :disabled="loading"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                        {{ loading ? 'Сохранение...' : 'Сохранить' }}
                    </button>
                </div>
            </form>
        </div>
        <ToastAlert :alerts="alerts" />
    </AdminLayout>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import ToastAlert from '@/components/common/ToastAlert.vue'
import ProductCharacteristics from '@/components/product/ProductCharacteristics.vue'
import CeramicCharacteristics from '@/components/product/CeramicCharacteristics.vue'
import AdditionalCharacteristics from '@/components/product/AdditionalCharacteristics.vue'
import ProductImageUpload from '@/components/product/ProductImageUpload.vue'
import { useProductManager } from '@/composables/useProductManager'
import { useProductValidation } from '@/composables/useProductValidation'

const imageUploadRef = ref()

const currentPageTitle = 'Редактирование товара арт. '

const {
    product,
    loading,
    error,
    alerts,
    categories,
    brands,
    colors,
    handleFetchProducts,
    handleSubmit,
    goBack,
    init,
} = useProductManager()

const { errors, validateAll, hasErrors } = useProductValidation(product)

const isCeramicCategory = computed(() => {
    if (!product.value.category_id) return false
    const cat = categories.value.find((c) => c.value === Number(product.value.category_id))
    if (!cat) return false
    const name = cat.label.toLowerCase()
    return (
        name.includes('керамогранит') ||
        name.includes('плитка') ||
        name.includes('мозаика') ||
        name.includes('клинкер') ||
        name.includes('ступени')
    )
})

const onSubmit = () => {
    handleSubmit(validateAll, hasErrors, errors, imageUploadRef.value)
}

onMounted(() => {
    init()
})
</script>
