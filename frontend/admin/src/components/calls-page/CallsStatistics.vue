<template>
  <div class="grid grid-cols-4 gap-4 md:gap-4 mb-4 md:mb-4">
    <div class="col-span-12 xl:col-span-1">
      <div
        class="flex items-center gap-5 rounded-2xl border border-warning-200 bg-white p-5 dark:border-warning-800 dark:bg-white/[0.03] md:p-6">
        <div
          class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-warning-100 rounded-xl dark:bg-warning-500/15">
          <component :is="phoneOutgoingIcon" class="text-warning-600 dark:text-warning-400" />
        </div>
        <div>
          <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ statistics.current.pending || 0 }}</h3>
          <p class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
            Новые заявки перезвонить
          </p>
        </div>
      </div>
    </div>
    <div class="col-span-12 xl:col-span-1">
      <div
        class="flex items-center gap-5 rounded-2xl border border-success-200 bg-white p-5 dark:border-success-800 dark:bg-white/[0.03] md:p-6">
        <div
          class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-success-100 rounded-xl dark:bg-success-500/15">
          <component :is="circleCheckBigIcon" class="text-success-600 dark:text-success-400" />
        </div>
        <div>
          <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">{{ statistics.current.processed || 0 }}</h3>
          <p class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
            Завершённые звонки
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  phoneOutgoingIcon: {
    type: Object,
    required: true
  },
  circleCheckBigIcon: {
    type: Object,
    required: true
  },
  statistics: {
    type: Object,
    required: true
  }
})
</script>
