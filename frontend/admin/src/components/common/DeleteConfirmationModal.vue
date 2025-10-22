<template>
  <Modal v-if="isVisible" @close="$emit('close')">
    <template #body>
      <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">
            Подтверждение удаления<br>
            арт. {{ productArticle }}
          </h3>
          <p class="mt-5 text-sm text-gray-600 dark:text-gray-400 text-center">
            Вы уверены, что хотите удалить товар<br>
            "{{ productName }}"?
          </p>
          <div class="mt-4 flex justify-end gap-3">
            <Button variant="outline" @click="$emit('close')">Отмена</Button>
            <Button variant="primary" @click="$emit('confirm')">Удалить</Button>
          </div>
        </div>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import Modal from '@/components/ui/Modal.vue'
import Button from '@/components/ui/Button.vue'

defineProps({
  isVisible: Boolean,
  productName: String,
  productArticle: String,
})

defineEmits(['close', 'confirm'])
</script>
