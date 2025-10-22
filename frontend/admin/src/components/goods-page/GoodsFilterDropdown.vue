<template>
  <div class="relative" ref="filterRef" @click="closeFilter">
    <button
      class="shadow-theme-xs flex h-11 w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400"
      @click.stop="toggleFilter" type="button">
      <component :is="settingsIcon" />
      Фильтр
    </button>
    <div v-show="showFilter" @click.stop
      class="absolute right-0 z-10 mt-2 w-45 rounded-lg border border-gray-200 bg-white p-4 shadow-lg dark:border-gray-700 dark:bg-gray-800">
      <div class="mb-5">
        <Checkbox id="checkboxLabelSale" label="Распродажа" :checked="checkboxSale"
          @update:checked="emit('update:checkboxSale', $event)" />
      </div>
      <div class="mb-5">
        <Checkbox id="checkboxLabelNoSale" label="Не распродажа" :checked="checkboxNoSale"
          @update:checked="emit('update:checkboxNoSale', $event)" />
      </div>
      <div class="mb-5">
        <Checkbox id="checkboxLabelPublished" label="Опубликован" :checked="checkboxPublished"
          @update:checked="emit('update:checkboxPublished', $event)" />
      </div>
      <div>
        <Checkbox id="checkboxLabelNoPublished" label="Не опубликован" :checked="checkboxNoPublished"
          @update:checked="emit('update:checkboxNoPublished', $event)" />
      </div>
    </div>
  </div>
</template>

<script setup>
import Checkbox from '@/components/ui/Checkbox.vue'
import { useClickOutside } from '../../composables/useClickOutside'

const props = defineProps({
  showFilter: Boolean,
  checkboxSale: Boolean,
  checkboxNoSale: Boolean,
  checkboxPublished: Boolean,
  checkboxNoPublished: Boolean,
  settingsIcon: Object
})

const emit = defineEmits([
  'update:showFilter',
  'update:checkboxSale',
  'update:checkboxNoSale',
  'update:checkboxPublished',
  'update:checkboxNoPublished'
])

const filterRef = useClickOutside(() => {
  emit('update:showFilter', false)
})

const toggleFilter = () => {
  emit('update:showFilter', !props.showFilter)
}

const closeFilter = () => {
  emit('update:showFilter', false)
}
</script>
