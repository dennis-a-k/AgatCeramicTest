<template>
  <Modal v-if="isVisible" @close="$emit('close')">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">
            Массовая загрузка товаров
          </h3>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-center">
            Выберите категорию и скачайте шаблон, или загрузите Excel файл с товарами
          </p>
          <div class="mt-4">
            <label for="selectedCategory" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Категория</label>
            <select
              id="selectedCategory"
              v-model="selectedCategoryId"
              class="mt-1 dark:bg-dark-900 h-11 flex items-center w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
            >
              <option value="">Выберите категорию</option>
              <option v-for="category in categories.filter(c => c.value !== null)" :key="category.id" :value="category.value">
                {{ category.label }}
              </option>
            </select>
          </div>
          <div class="flex justify-end mt-4">
            <Button variant="outline" :disabled="!selectedCategoryId" @click="handleDownloadTemplate">
              Скачать шаблон
            </Button>
          </div>
          <div class="mt-4">
            <input
              type="file"
              accept=".xlsx,.xls"
              @change="handleFileSelect"
              class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 dark:file:bg-gray-700 dark:hover:file:bg-gray-600 dark:file:text-gray-400"
            />
          </div>
          <div class="mt-4 flex justify-end gap-3">
            <Button variant="outline" :disabled="isLoading" @click="$emit('close')">Отмена</Button>
            <Button variant="primary" :disabled="!selectedFile || isLoading" @click="handleUpload">
              {{ isLoading ? 'Загрузка...' : 'Загрузить' }}
            </Button>
          </div>
          <hr class="my-6 border-gray-300 dark:border-gray-600">
          <h4 class="text-md font-semibold text-gray-900 dark:text-white text-center">
            Массовая загрузка фото
          </h4>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-center">
            Выберите .zip файл с фото товаров
          </p>
          <p class="mt-2 text-xs text-warning-500 dark:text-warning-400 text-center">
            Фото должны быть названы по артикулу товара и порядковым номером фото, например: <span class="text-error-500 dark:text-error-400">123456_0.jpg, 123456_1.jpg</span> и т.д.
          </p>
          <div class="mt-4">
            <input
              type="file"
              accept=".zip"
              @change="handleZipSelect"
              class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 dark:file:bg-gray-700 dark:hover:file:bg-gray-600 dark:file:text-gray-400"
            />
          </div>
          <div v-if="photoUploadProgress > 0" class="mt-4">
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
              <div class="bg-green-600 h-2.5 rounded-full" :style="{ width: photoUploadProgress + '%' }"></div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ photoUploadProgress }}% загружено</p>
          </div>
          <div class="mt-4 flex justify-end">
            <Button variant="primary" :disabled="!selectedZipFile || photoUploadProgress > 0" @click="handleUploadPhotos">
              {{ photoUploadProgress > 0 ? 'Загрузка...' : 'Загрузить фото' }}
            </Button>
          </div>
        </div>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Modal from '@/components/ui/Modal.vue'
import Button from '@/components/ui/Button.vue'

const props = defineProps({
  isVisible: Boolean,
  isLoading: Boolean,
  categories: Array,
  photoUploadProgress: {
    type: Number,
    default: 0
  }
})

const emit = defineEmits(['close', 'upload', 'downloadTemplate', 'uploadPhotos'])

const selectedFile = ref(null)
const selectedCategoryId = ref('')
const selectedZipFile = ref(null)

const handleFileSelect = (event) => {
  selectedFile.value = event.target.files[0]
}

const handleUpload = () => {
  if (selectedFile.value) {
    emit('upload', selectedFile.value)
  }
}

const handleDownloadTemplate = () => {
  if (selectedCategoryId.value) {
    emit('downloadTemplate', selectedCategoryId.value)
  }
}

const handleZipSelect = (event) => {
  selectedZipFile.value = event.target.files[0]
}

const handleUploadPhotos = () => {
  if (selectedZipFile.value) {
    emit('uploadPhotos', selectedZipFile.value)
  }
}
</script>
