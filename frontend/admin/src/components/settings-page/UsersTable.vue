<template>
  <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    <div class="flex items-start justify-between">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Пользователи</h3>
    </div>
    <div class="my-6">
      <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-400 text-theme-xs">Пользователь</span>
        <span class="text-right text-gray-400 text-theme-xs">Роль</span>
        <span class="text-right text-gray-400 text-theme-xs"></span>
      </div>
      <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <span class="text-gray-500 text-theme-sm dark:text-gray-400">
          <button @click="" class="text-left">
            <span
              class="block font-medium text-gray-800 text-theme-sm dark:text-white/90 hover:text-brand-500 dark:hover:text-brand-400">
              Админ
            </span>
            <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
              admin@agatceramic.ru
            </span>
          </button>
        </span>
        <span
          class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
          администратор
        </span>
        <button
          class="inline-flex items-center justify-center gap-2 rounded-lg transition bg-white text-gray-700 ring-0 ring-gray-300 hover:bg-error-50 hover:ring-error-300 hover:text-error-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-error-500/15 dark:hover:ring-error-500/50 dark:hover:text-error-500 cursor-pointer p-1"
          @click="" :aria-label="`Удалить пользователя`">
          <DeleteIcon />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { DeleteIcon } from '@/icons'
</script>
