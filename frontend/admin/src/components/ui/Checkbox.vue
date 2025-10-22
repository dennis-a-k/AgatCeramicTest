<template>
  <label :for="id"
    class="flex items-center text-sm font-medium text-gray-700 cursor-pointer select-none dark:text-gray-400">
    <div class="relative">
      <input type="checkbox" :id="id" :checked="checked" @change="emit('update:checked', $event.target.checked)"
        class="sr-only" />
      <div :class="checked ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
        class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] hover:border-brand-500 dark:hover:border-brand-500">
        <span :class="checked ? '' : 'opacity-0'">
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437"
              stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </span>
      </div>
    </div>
    {{ label }}
  </label>
</template>

<script setup>
const props = defineProps({
  id: String,
  label: String,
  checked: Boolean
})

const emit = defineEmits(['update:checked'])
</script>
