<template>
  <div v-if="alert" class="fixed top-20 right-4 z-[100000]">
    <Alert
      :variant="alert.variant"
      :title="alert.title"
      :message="alert.message"
    />
  </div>
</template>

<script setup lang="ts">
import Alert from '../ui/Alert.vue'

interface AlertData {
  variant: 'success' | 'error' | 'warning' | 'info'
  title: string
  message: string
}

defineProps<{
  alert: AlertData | null
}>()
</script>
