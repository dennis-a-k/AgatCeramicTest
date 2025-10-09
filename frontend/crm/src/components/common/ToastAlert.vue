<template>
  <div v-if="alert" class="fixed top-20 right-4 z-[100000]">
    <Alert
      :variant="alert.variant"
      :title="alert.title"
      :message="alert.message"
    />
  </div>
</template>

<script setup>
import Alert from '@/components/ui/Alert.vue'

defineProps({
  alert: Object
})
</script>
