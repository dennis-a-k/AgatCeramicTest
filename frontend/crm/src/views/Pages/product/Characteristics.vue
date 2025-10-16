<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div class="grid grid-cols-12 gap-4 md:gap-6">
      <div class="col-span-12 xl:col-span-6">
        <ColorsTable />
      </div>
      <div class="col-span-12 xl:col-span-6">
        <BrandsTable />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/components/common/PageBreadcrumb.vue'
import ColorsTable from '@/components/characteristics-page/ColorsTable.vue'
import BrandsTable from '@/components/characteristics-page/BrandsTable.vue'

const currentPageTitle = ref('Характеричтики')
</script>
