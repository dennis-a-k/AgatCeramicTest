<template>
    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'

const currentPageTitle = ref('Статистика')
</script>
