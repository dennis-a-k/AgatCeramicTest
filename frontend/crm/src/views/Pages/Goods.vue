<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'

const currentPageTitle = ref('Товары')
</script>

<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
  </AdminLayout>
</template>
