<template>
  <Modal v-if="isVisible" @close="$emit('close')">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">
            Массовая загрузка товаров
          </h3>
          <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-center">
            Выберите Excel файл с товарами для загрузки
          </p>
          <div class="mt-4">
            <input
              type="file"
              accept=".xlsx,.xls"
              @change="handleFileSelect"
              class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
            />
          </div>
          <div class="mt-4 flex justify-end gap-3">
            <Button variant="outline" @click="$emit('close')">Отмена</Button>
            <Button variant="primary" :disabled="!selectedFile" @click="handleUpload">Загрузить</Button>
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

defineProps({
  isVisible: Boolean,
})

const emit = defineEmits(['close', 'upload'])

const selectedFile = ref(null)

const handleFileSelect = (event) => {
  selectedFile.value = event.target.files[0]
}

const handleUpload = () => {
  if (selectedFile.value) {
    emit('upload', selectedFile.value)
  }
}
</script>
