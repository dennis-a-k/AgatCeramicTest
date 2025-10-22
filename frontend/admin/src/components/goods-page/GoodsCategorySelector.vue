<template>
  <div class="relative sm:min-w-[200px] sm:w-auto" ref="multiSelectRef">
    <div @click="toggleDropdown"
      class="dark:bg-dark-900 h-11 flex items-center w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
      :class="{ 'text-gray-800 dark:text-white/90': isOpen }">
      <span v-if="!selectedItem" class="text-gray-400"> Категория </span>
      <span v-else class="text-gray-800 dark:text-white/90">{{ selectedItem.label }}</span>
      <svg class="ml-auto" :class="{ 'transform rotate-180': isOpen }" width="20" height="20" viewBox="0 0 20 20"
        fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4.79175 7.39551L10.0001 12.6038L15.2084 7.39551" stroke="currentColor" stroke-width="1.5"
          stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </div>
    <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in"
      leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
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
</template>

<script setup>
import { useClickOutside } from '../../composables/useClickOutside'

const props = defineProps({
  categories: Array,
  selectedItem: Object,
  isOpen: Boolean
})

const emit = defineEmits(['toggleDropdown', 'toggleItem'])

const multiSelectRef = useClickOutside(() => {
  if (props.isOpen) emit('toggleDropdown')
})

const toggleDropdown = () => {
  emit('toggleDropdown')
}

const handleToggleItem = (item) => {
  emit('toggleItem', item)
}

const isSelected = (item) => {
  if (item.value === null) {
    return props.selectedItem === null
  }
  return props.selectedItem && props.selectedItem.value === item.value
}
</script>
