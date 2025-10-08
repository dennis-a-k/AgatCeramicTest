<template>
  <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
    <div class="flex flex-col md:flex-row gap-3 sm:justify-between">
      <div class="flex flex-col md:flex-row gap-3">
        <div class="relative flex-1 sm:flex-auto sm:w-auto sm:min-w-[300px]">
          <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <component :is="searchIcon" />
          </span>
          <input type="text" placeholder="Поиск товара" :value="searchQuery" @input="emit('update:searchQuery', $event.target.value)"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-12 pl-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
          <button v-if="searchQuery" @click="clearSearch"
            class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl leading-none cursor-pointer bg-white dark:bg-gray-900 px-1 z-10">
            ✕
          </button>
        </div>
        <div class="relative sm:min-w-[200px] sm:w-auto" ref="multiSelectRef">
          <div @click="toggleDropdown"
            class="dark:bg-dark-900 h-11 flex items-center w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
            :class="{ 'text-gray-800 dark:text-white/90': isOpen }">
            <span v-if="!selectedItem" class="text-gray-400"> Категория </span>
            <span v-else class="text-gray-800 dark:text-white/90">{{ selectedItem.label }}</span>
            <svg class="ml-auto" :class="{ 'transform rotate-180': isOpen }" width="20" height="20"
              viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M4.79175 7.39551L10.0001 12.6038L15.2084 7.39551" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <transition enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0">
            <div v-if="isOpen" class="absolute z-10 w-full mt-1 bg-white rounded-lg shadow-sm dark:bg-gray-900">
              <ul class="overflow-y-auto divide-y divide-gray-200 custom-scrollbar max-h-60 dark:divide-gray-800"
                role="listbox">
                <li v-for="category in categories" :key="category.id" @click="handleToggleItem(category)"
                  class="relative flex items-center w-full px-3 py-2 border-transparent cursor-pointer first:rounded-t-lg last:rounded-b-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800"
                  :class="{ 'bg-gray-50 dark:bg-white/[0.03]': isSelected(category) }" role="option"
                  :aria-selected="isSelected(category)">
                  <span class="grow">{{ category.label }}</span>
                  <svg v-if="isSelected(category)" class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                    </path>
                  </svg>
                </li>
              </ul>
            </div>
          </transition>
        </div>
      </div>
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
            <div>
              <label for="checkboxLabelSale"
                class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                <div class="relative">
                  <input type="checkbox" id="checkboxLabelSale" :checked="checkboxSale" @change="emit('update:checkboxSale', $event.target.checked)" class="sr-only" />
                  <div :class="checkboxSale
                    ? 'border-brand-500 bg-brand-500'
                    : 'bg-transparent border-gray-300 dark:border-gray-700'
                    "
                    class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500">
                    <span :class="checkboxSale ? '' : 'opacity-0'">
                      <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
                          stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                  </div>
                </div>
                Распродажа
              </label>
            </div>
          </div>
          <div class="mb-5">
            <div>
              <label for="checkboxLabelNoSale"
                class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                <div class="relative">
                  <input type="checkbox" id="checkboxLabelNoSale" :checked="checkboxNoSale" @change="emit('update:checkboxNoSale', $event.target.checked)" class="sr-only" />
                  <div :class="checkboxNoSale
                    ? 'border-brand-500 bg-brand-500'
                    : 'bg-transparent border-gray-300 dark:border-gray-700'
                    "
                    class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500">
                    <span :class="checkboxNoSale ? '' : 'opacity-0'">
                      <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
                          stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                  </div>
                </div>
                Не распродажа
              </label>
            </div>
          </div>
          <div class="mb-5">
            <div>
              <label for="checkboxLabelPublished"
                class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
                <div class="relative">
                  <input type="checkbox" id="checkboxLabelPublished" :checked="checkboxPublished" @change="emit('update:checkboxPublished', $event.target.checked)"
                    class="sr-only" />
                  <div :class="checkboxPublished
                    ? 'border-brand-500 bg-brand-500'
                    : 'bg-transparent border-gray-300 dark:border-gray-700'
                    "
                    class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500">
                    <span :class="checkboxPublished ? '' : 'opacity-0'">
                      <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
                          stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                  </div>
                </div>
                Опубликован
              </label>
            </div>
          </div>
          <div>
            <label for="checkboxLabelNoPublished"
              class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
              <div class="relative">
                <input type="checkbox" id="checkboxLabelNoPublished" :checked="checkboxNoPublished" @change="emit('update:checkboxNoPublished', $event.target.checked)"
                  class="sr-only" />
                <div :class="checkboxNoPublished
                  ? 'border-brand-500 bg-brand-500'
                  : 'bg-transparent border-gray-300 dark:border-gray-700'
                  "
                  class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500">
                  <span :class="checkboxNoPublished ? '' : 'opacity-0'">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
                        stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </span>
                </div>
              </div>
              Не опубликован
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'

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

const multiSelectRef = ref(null)
const filterRef = ref(null)

const clearSearch = () => {
  emit('update:searchQuery', '')
}

const toggleDropdown = () => {
  emit('toggleDropdown')
}

const handleToggleItem = (item) => {
  emit('toggleItem', item)
}

const toggleFilter = () => {
  emit('update:showFilter', !props.showFilter)
}

const closeFilter = () => {
  emit('update:showFilter', false)
}

const isSelected = (item) => {
  if (item.value === null) {
    return props.selectedItem === null
  }
  return props.selectedItem && props.selectedItem.value === item.value
}

const handleClickOutside = (event) => {
  if (multiSelectRef.value && !multiSelectRef.value.contains(event.target) && props.isOpen) {
    emit('toggleDropdown') // close if open
  }
}

const handleClickOutsideFilter = (event) => {
  if (filterRef.value && !filterRef.value.contains(event.target)) {
    emit('update:showFilter', false)
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  document.addEventListener('click', handleClickOutsideFilter)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
  document.removeEventListener('click', handleClickOutsideFilter)
})
</script>
