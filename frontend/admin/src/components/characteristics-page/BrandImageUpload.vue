<template>
  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <ToastAlert :alert="toastAlert" />
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
      <h2 class="text-lg font-medium text-gray-800 dark:text-white">
        Фото бренда (макс. 1 изображение)
      </h2>
    </div>
    <div class="p-4 sm:p-6">
      <!-- Существующее изображение -->
      <div v-if="image" class="mb-4">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текущее фото:</h3>
        <div class="relative group w-fit">
          <img :src="getImageUrl(image)" alt="Фото бренда"
            class="w-auto h-24 object-cover rounded-lg border border-gray-200" />
          <button @click="removeImage"
            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
            type="button">
            ×
          </button>
        </div>
      </div>
      <!-- Загрузка нового изображения -->
      <div v-if="!image">
        <label for="brand-image"
          class="shadow-theme-xs group hover:border-brand-500 block cursor-pointer rounded-lg border-2 border-dashed border-gray-300 transition dark:border-gray-800">
          <div class="flex justify-center p-10">
            <div class="flex max-w-[260px] flex-col items-center gap-4">
              <div
                class="inline-flex h-13 w-13 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition dark:border-gray-800 dark:text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path
                    d="M20.0004 16V18.5C20.0004 19.3284 19.3288 20 18.5004 20H5.49951C4.67108 20 3.99951 19.3284 3.99951 18.5V16M12.0015 4L12.0015 16M7.37454 8.6246L11.9994 4.00269L16.6245 8.6246"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </div>
              <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                <span class="font-medium text-gray-800 dark:text-white/90">Щелкните, чтобы загрузить,</span>
                или перетащите PNG, JPG, JPEG или WEBP (макс. 300x300 пикселей)
              </p>
            </div>
          </div>
          <input type="file" id="brand-image" class="hidden" accept=".png,.jpg,.jpeg,.webp" @change="onFileChange" />
        </label>
      </div>
      <!-- Новое изображение для загрузки -->
      <div v-if="newFile" class="mt-4">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Новое фото для загрузки:
        </h3>
        <div class="relative group w-fit">
          <img :src="getFilePreviewUrl(newFile)" alt="Новое фото бренда"
            class="w-24 h-24 object-cover rounded-lg border border-gray-200" />
          <button @click="removeNewFile"
            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
            type="button">
            ×
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import ToastAlert from '../common/ToastAlert.vue'

interface Props {
  modelValue: string
}

interface Emits {
  (e: 'update:modelValue', value: string): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const image = ref<string>(props.modelValue)
const newFile = ref<File | null>(null)
const toastAlert = ref<{ show: boolean; type: 'success' | 'error' | 'warning' | 'info'; title: string; message: string } | null>(null)

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

// Синхронизация с родителем
watch(
  () => props.modelValue,
  (newValue) => {
    image.value = newValue
  },
)

watch(
  image,
  (newImage) => {
    emit('update:modelValue', newImage)
  },
)

const getImageUrl = (imagePath: string): string => {
  if (imagePath.startsWith('http')) {
    return imagePath
  }
  return `${API_BASE_URL}/storage/${imagePath}`
}

const getFilePreviewUrl = (file: File): string => {
  return window.URL.createObjectURL(file)
}

const showToast = (type: 'success' | 'error' | 'warning' | 'info', title: string, message: string) => {
  toastAlert.value = { show: true, type, title, message }
  setTimeout(() => {
    toastAlert.value = null
  }, 5000)
}

const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const files = target.files
  if (!files || files.length === 0) return

  const file = files[0]

  const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp']
  const maxSize = 1 * 1024 * 1024 // 1MB

  if (!allowedTypes.includes(file.type)) {
    showToast(
      'error',
      'Неподдерживаемый формат',
      'Файл имеет неподдерживаемый формат. Разрешены только PNG, JPG, JPEG, WEBP.',
    )
    return
  }

  if (file.size > maxSize) {
    showToast(
      'error',
      'Файл слишком большой',
      'Файл слишком большой. Максимальный размер 1MB.',
    )
    return
  }

  // Проверка размеров
  const img = new Image()
  img.onload = () => {
    if (img.width > 300 || img.height > 300) {
      showToast(
        'error',
        'Изображение слишком большое',
        'Максимальный размер изображения 300x300 пикселей.',
      )
      return
    }
    newFile.value = file
  }
  img.src = window.URL.createObjectURL(file)

  // Очищаем input
  target.value = ''
}

const removeImage = () => {
  image.value = ''
}

const removeNewFile = () => {
  newFile.value = null
}

// Экспортируем newFile для использования в родительском компоненте
defineExpose({
  newFile,
})
</script>
