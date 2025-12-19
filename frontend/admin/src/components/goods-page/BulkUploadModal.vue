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
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Категория</label>
            <select
              v-model="selectedCategoryId"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
              <option value="">Выберите категорию</option>
              <option v-for="category in categories.filter(c => c.value !== null)" :key="category.id" :value="category.value">
                {{ category.label }}
              </option>
            </select>
          </div>
          <div class="mt-4">
            <Button variant="outline" :disabled="!selectedCategoryId" @click="handleDownloadTemplate">
              Скачать шаблон
            </Button>
          </div>
          <div class="mt-4">
            <input
              type="file"
              accept=".xlsx,.xls"
              @change="handleFileSelect"
              class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
            />
          </div>
          <div class="mt-4 flex justify-end gap-3">
            <Button variant="outline" :disabled="isLoading" @click="$emit('close')">Отмена</Button>
            <Button variant="primary" :disabled="!selectedFile || isLoading" @click="handleUpload">
              {{ isLoading ? 'Загрузка...' : 'Загрузить' }}
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
})

const emit = defineEmits(['close', 'upload', 'downloadTemplate'])

const selectedFile = ref(null)
const selectedCategoryId = ref('')

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
</script>
