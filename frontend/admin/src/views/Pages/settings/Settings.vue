<template>
    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />
        <div v-if="loading" class="flex justify-center items-center h-screen">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-brand-500"></div>
        </div>
        <div v-else-if="error"
            class="flex flex-col justify-center items-center h-screen font-bold text-error-700 text-theme-xl dark:text-error-500">
            Ошибка при загрузке<br />
            <button
                class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300 mt-2"
                @click="">
                Попробовать снова
            </button>
        </div>
        <div v-else-if="loading"
            class="flex flex-col justify-center items-center h-screen menu-item-icon-active text-center font-bold text-theme-xl m-5">
            Характеристики не найден
        </div>
        <div v-else>
            <div class="grid grid-cols-12 gap-4 md:gap-6 mb-4 md:mb-6">
                <div class="col-span-12 xl:col-span-5">
                    <UsersTable />
                </div>
                <div class="col-span-12 xl:col-span-7">
                    <ContactsTable />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import UsersTable from '@/components/settings-page/usersTable.vue'
import ContactsTable from '@/components/settings-page/ContactsTable.vue'

const currentPageTitle = ref('Настройки сайта')
</script>
