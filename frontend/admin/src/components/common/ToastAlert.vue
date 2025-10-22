<template>
  <div class="fixed top-20 right-4 z-[100000] space-y-2">
    <Alert
      v-for="(alert, index) in (alerts || (alert ? [alert] : []))"
      :key="index"
      v-show="alert.show"
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
  alerts?: AlertData[]
  alert?: AlertData | null
}>()
</script>
