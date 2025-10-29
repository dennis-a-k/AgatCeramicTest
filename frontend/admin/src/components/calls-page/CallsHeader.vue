<template>
  <div
    class="flex flex-col justify-between gap-5 border-gray-200 px-5 py-4 sm:flex-row sm:items-center dark:border-gray-800">
    <h3 class="text-base font-medium text-gray-800 dark:text-white/90 flex flex-row items-center">
      <component :is="packageIcon" class="menu-item-icon-active mr-2" />
      Список заявок ({{ totalItems }})
    </h3>
    <div class="flex flex-col md:flex-row gap-3">
      <CallsStatusSelector :statuses="statuses" :selectedItem="selectedItem" :isOpen="isOpen"
        @toggleDropdown="emit('toggleDropdown')" @toggleItem="emit('toggleItem', $event)" />
    </div>
  </div>
</template>

<script setup>
import CallsStatusSelector from './CallsStatusSelector.vue';

defineProps({
  totalItems: {
    type: Number,
    required: true
  },
  packageIcon: {
    type: Object,
    required: true
  },
  statuses: {
    type: Array,
    required: true
  },
  selectedItem: {
    type: [Object, null],
    required: true
  },
  isOpen: {
    type: Boolean,
    required: true
  },
})

const emit = defineEmits([
  'update:searchQuery',
  'toggleDropdown',
  'toggleItem',
  'update:showFilter',
])
</script>
