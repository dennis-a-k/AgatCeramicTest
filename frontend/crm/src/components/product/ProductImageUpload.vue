<template>
  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
      <h2 class="text-lg font-medium text-gray-800 dark:text-white">
        Фото товара
      </h2>
    </div>
    <div class="p-4 sm:p-6">
      <label for="product-image" class="shadow-theme-xs group hover:border-brand-500 block cursor-pointer rounded-lg border-2 border-dashed border-gray-300 transition dark:border-gray-800">
        <div class="flex justify-center p-10">
          <div class="flex max-w-[260px] flex-col items-center gap-4">
            <div class="inline-flex h-13 w-13 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition dark:border-gray-800 dark:text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M20.0004 16V18.5C20.0004 19.3284 19.3288 20 18.5004 20H5.49951C4.67108 20 3.99951 19.3284 3.99951 18.5V16M12.0015 4L12.0015 16M7.37454 8.6246L11.9994 4.00269L16.6245 8.6246" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </div>
            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
              <span class="font-medium text-gray-800 dark:text-white/90">Щелкните, чтобы загрузить,</span>
              или перетащите PNG, JPG, JPEG или WEBP (макс. 800x800 пикселей)
            </p>
          </div>
        </div>
        <input type="file" id="product-image" class="hidden">
      </label>
    </div>
  </div>
</template>

<script setup lang="ts">
// Компонент для загрузки изображений товара
// Пока без логики, только шаблон
</script>
