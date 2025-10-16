<template>
  <div v-if="alert && alert.show" class="fixed top-20 right-4 z-[100000]">
    <Alert
      :variant="alert.type"
      :title="alert.title"
      :message="alert.message"
    />
  </div>
</template>

<script setup lang="ts">
import Alert from '../ui/Alert.vue'

interface AlertData {
  show: boolean
  type: 'success' | 'error' | 'warning' | 'info'
  title: string
  message: string
}

defineProps<{
  alert: AlertData | null
}>()
</script>
