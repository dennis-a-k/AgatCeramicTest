<template>
  <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
    <div class="flex flex-col md:flex-row gap-3 sm:justify-between">
      <div class="flex flex-col md:flex-row gap-3">
        <GoodsSearch :searchQuery="searchQuery" :searchIcon="searchIcon"
          @update:searchQuery="emit('update:searchQuery', $event)" />
        <GoodsCategorySelector :categories="categories" :selectedItem="selectedItem" :isOpen="isOpen"
          @toggleDropdown="emit('toggleDropdown')" @toggleItem="emit('toggleItem', $event)" />
      </div>
      <GoodsFilterDropdown :showFilter="showFilter" :checkboxSale="checkboxSale" :checkboxNoSale="checkboxNoSale"
        :checkboxPublished="checkboxPublished" :checkboxNoPublished="checkboxNoPublished" :settingsIcon="settingsIcon"
        @update:showFilter="emit('update:showFilter', $event)"
        @update:checkboxSale="emit('update:checkboxSale', $event)"
        @update:checkboxNoSale="emit('update:checkboxNoSale', $event)"
        @update:checkboxPublished="emit('update:checkboxPublished', $event)"
        @update:checkboxNoPublished="emit('update:checkboxNoPublished', $event)" />
    </div>
  </div>
</template>

<script setup>
import GoodsSearch from './GoodsSearch.vue'
import GoodsCategorySelector from './GoodsCategorySelector.vue'
import GoodsFilterDropdown from './GoodsFilterDropdown.vue'

const props = defineProps({
  searchQuery: String,
  categories: Array,
  selectedItem: Object,
  isOpen: Boolean,
  showFilter: Boolean,
  checkboxSale: Boolean,
  checkboxNoSale: Boolean,
  checkboxPublished: Boolean,
  checkboxNoPublished: Boolean,
  searchIcon: Object,
  settingsIcon: Object
})

const emit = defineEmits([
  'update:searchQuery',
  'toggleDropdown',
  'toggleItem',
  'update:showFilter',
  'update:checkboxSale',
  'update:checkboxNoSale',
  'update:checkboxPublished',
  'update:checkboxNoPublished'
])
</script>
