<template>
  <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
    <div class="flex flex-col md:flex-row gap-3 sm:justify-between">
      <div class="flex flex-col md:flex-row gap-3">
        <OrdersSearch :searchQuery="searchQuery" :searchIcon="searchIcon"
          @update:searchQuery="emit('update:searchQuery', $event)" />
      </div>
      <OrdersStatusSelector :statuses="statuses" :selectedItem="selectedItem" :isOpen="isOpen"
        @toggleDropdown="emit('toggleDropdown')" @toggleItem="emit('toggleItem', $event)" />
    </div>
  </div>
</template>

<script setup>
import OrdersSearch from './OrdersSearch.vue'
import OrdersStatusSelector from './OrdersStatusSelector.vue'

const props = defineProps({
  searchQuery: String,
  statuses: Array,
  selectedItem: Object,
  isOpen: Boolean,
  showFilter: Boolean,
  searchIcon: Object,
  settingsIcon: Object
})

const emit = defineEmits([
  'update:searchQuery',
  'toggleDropdown',
  'toggleItem',
  'update:showFilter',
])
</script>
