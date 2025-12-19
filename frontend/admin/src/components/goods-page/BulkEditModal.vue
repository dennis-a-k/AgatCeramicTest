<template>
  <Modal v-if="isVisible" @close="$emit('close')">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">
            Массовое редактирование товаров
          </h3>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-center">
            Выберите тип шаблона и скачайте его, или загрузите Excel файл с обновлениями
          </p>
          <div class="mt-4">
            <label for="selectedTemplate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
              Тип шаблона
            </label>
            <select id="selectedTemplate" v-model="selectedTemplateType"
              class="mt-1 dark:bg-dark-900 h-11 flex items-center w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
              <option value="">Выберите тип шаблона</option>
              <option value="products">Товары</option>
              <option value="prices">Цены</option>
              <option value="statuses">Статусы</option>
              <option value="sales">Распродажи</option>
            </select>
          </div>
          <div class="flex justify-end mt-4">
            <Button variant="outline" :disabled="!selectedTemplateType" @click="handleDownloadTemplate">
              Скачать шаблон
            </Button>
          </div>
          <p class="mt-10 text-sm text-gray-600 dark:text-gray-400 text-center">
            Перед загрузкой убедитесь, что выше выбран нужный тип шаблона для редактирования
          </p>
          <div class="mt-4">
            <input type="file" accept=".xlsx,.xls" @change="handleFileSelect"
              class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 dark:file:bg-gray-700 dark:hover:file:bg-gray-600 dark:file:text-gray-400" />
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
})

const emit = defineEmits(['close', 'upload', 'downloadTemplate'])

const selectedFile = ref(null)
const selectedTemplateType = ref('')

const handleFileSelect = (event) => {
  selectedFile.value = event.target.files[0]
}

const handleUpload = () => {
  if (selectedFile.value) {
    emit('upload', selectedFile.value, selectedTemplateType.value)
  }
}

const handleDownloadTemplate = () => {
  if (selectedTemplateType.value) {
    emit('downloadTemplate', selectedTemplateType.value)
  }
}
</script>
