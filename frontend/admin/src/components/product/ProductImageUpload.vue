<template>
  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <ToastAlert :alert="toastAlert" />
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
      <h2 class="text-lg font-medium text-gray-800 dark:text-white">
        Фото товара (макс. 5 изображений)
      </h2>
    </div>
    <div class="p-4 sm:p-6">
      <!-- Существующие изображения -->
      <div v-if="images.length > 0" class="mb-4">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текущие фото:</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
          <div v-for="(image, index) in images" :key="image.id || index" class="relative group" draggable="true"
            @dragstart="onDragStart($event, index)" @dragover.prevent @drop="onDrop($event, index)">
            <img :src="getImageUrl(image)" :alt="`Фото ${index + 1}`"
              class="w-full h-24 object-cover rounded-lg border border-gray-200" />
            <button @click="removeImage(index)"
              class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
              type="button">
              ×
            </button>
            <div class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
              {{ index + 1 }}
            </div>
          </div>
        </div>
      </div>

      <!-- Загрузка новых изображений -->
      <div v-if="images.length < 5">
        <label for="product-image"
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
                или перетащите PNG, JPG, JPEG или WEBP (макс. 800x800 пикселей)
              </p>
            </div>
          </div>
          <input type="file" id="product-image" class="hidden" multiple accept=".png,.jpg,.jpeg,.webp"
            @change="onFileChange" />
        </label>
      </div>
      <!-- Новые изображения для загрузки -->
      <div v-if="newFiles.length > 0" class="mt-4">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Новые фото для загрузки:
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
          <div v-for="(file, index) in newFiles" :key="index" class="relative group">
            <img :src="getFilePreviewUrl(file)" :alt="`Новое фото ${index + 1}`"
              class="w-full h-24 object-cover rounded-lg border border-gray-200" />
            <button @click="removeNewFile(index)"
              class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
              type="button">
              ×
            </button>
          </div>
        </div>
      </div>
      <p v-if="images.length >= 5" class="text-sm text-gray-500 mt-2">
        Достигнуто максимальное количество изображений (5)
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import ToastAlert from '../common/ToastAlert.vue'

interface ProductImage {
  id?: number
  image_path: string
  sort_order: number
}

interface Props {
  modelValue: ProductImage[]
}

interface Emits {
  (e: 'update:modelValue', value: ProductImage[]): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const images = ref<ProductImage[]>([...props.modelValue])
const newFiles = ref<File[]>([])
const draggedIndex = ref<number | null>(null)
const toastAlert = ref<{ show: boolean; type: 'success' | 'error' | 'warning' | 'info'; title: string; message: string } | null>(null)

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

// Синхронизация с родителем
watch(
  () => props.modelValue,
  (newValue) => {
    images.value = [...newValue]
  },
  { deep: true },
)

watch(
  images,
  (newImages) => {
    emit('update:modelValue', newImages)
  },
  { deep: true },
)

const getImageUrl = (image: ProductImage): string => {
  if (image.image_path.startsWith('http')) {
    return image.image_path
  }
  return `${API_BASE_URL}/storage/${image.image_path}`
}

const getFilePreviewUrl = (file: File): string => {
  return window.URL.createObjectURL(file)
}

const showToast = (variant: 'success' | 'error' | 'warning' | 'info', title: string, message: string) => {
  toastAlert.value = { show: true, type: variant, title, message }
  setTimeout(() => {
    toastAlert.value = null
  }, 5000)
}

const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const files = target.files
  if (!files) return

  const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp']
  const maxSize = 5 * 1024 * 1024 // 5MB

  for (let i = 0; i < files.length; i++) {
    const file = files[i]

    if (!allowedTypes.includes(file.type)) {
      showToast(
        'error',
        'Неподдерживаемый формат',
        `Файл ${file.name} имеет неподдерживаемый формат. Разрешены только PNG, JPG, JPEG, WEBP.`,
      )
      continue
    }

    if (file.size > maxSize) {
      showToast(
        'error',
        'Файл слишком большой',
        `Файл ${file.name} слишком большой. Максимальный размер 5MB.`,
      )
      continue
    }

    if (images.value.length + newFiles.value.length >= 5) {
      showToast(
        'error',
        'Максимальное количество изображений',
        'Максимальное количество изображений - 5.',
      )
      break
    }

    newFiles.value.push(file)
  }

  // Очищаем input
  target.value = ''
}

const removeImage = (index: number) => {
  images.value.splice(index, 1)
  // Пересчитываем sort_order
  images.value.forEach((img, i) => {
    img.sort_order = i
  })
}

const removeNewFile = (index: number) => {
  newFiles.value.splice(index, 1)
}

const onDragStart = (event: DragEvent, index: number) => {
  draggedIndex.value = index
  event.dataTransfer!.effectAllowed = 'move'
}

const onDrop = (event: DragEvent, dropIndex: number) => {
  const dragIndex = draggedIndex.value
  if (dragIndex === null || dragIndex === dropIndex) return

  // Меняем местами
  const temp = images.value[dragIndex]
  images.value[dragIndex] = images.value[dropIndex]
  images.value[dropIndex] = temp

  // Пересчитываем sort_order
  images.value.forEach((img, i) => {
    img.sort_order = i
  })

  draggedIndex.value = null
}

// Экспортируем newFiles для использования в родительском компоненте
defineExpose({
  newFiles,
})
</script>
