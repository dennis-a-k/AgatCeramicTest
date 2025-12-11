<template>
    <AdminLayout>
        <div v-if="userRole === 'user'" class="flex justify-center items-center h-64">
            <h1 class="text-2xl font-bold text-brand-500 dark:text-white">
                Извините, но у вас нет прав для доступа к сайту!
            </h1>
        </div>
        <div v-else>
            <PageBreadcrumb :pageTitle="currentPageTitle" />
            <!-- Статистика для admin и moderator -->
            <p>Здесь будет статистика</p>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'

const currentPageTitle = ref('Статистика')

const getUserRole = () => {
    const user = localStorage.getItem('auth_user')
    if (user) {
        try {
            const userData = JSON.parse(user)
            return userData.role
        } catch {
            return null
        }
    }
    return null
}

const userRole = getUserRole()
</script>
