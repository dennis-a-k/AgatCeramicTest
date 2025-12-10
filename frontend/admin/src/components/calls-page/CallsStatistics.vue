<template>
  <div class="mb-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Статистика звонков</h2>
      <div class="flex items-center gap-2 rounded-2xl border border-gray-200 bg-white px-3 py-2 dark:border-gray-800 dark:bg-white/[0.03]">
        <label class="text-sm text-gray-600 dark:text-gray-400">Месяц:</label>
        <input type="month" v-model="selectedMonth" @change="handleMonthChange"
          class="bg-transparent text-sm text-gray-900 dark:text-white focus:outline-none" />
      </div>
    </div>
    <div class="grid grid-cols-4 gap-4 md:gap-4">
      <div class="col-span-12 xl:col-span-1">
        <div
          class="flex items-center gap-5 rounded-2xl border border-warning-200 bg-warning-100 p-5 dark:border-warning-800 dark:bg-white/[0.03] md:p-6">
          <div
            class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-warning-200 rounded-xl dark:bg-warning-500/15">
            <component :is="phoneOutgoingIcon" class="text-warning-600 dark:text-warning-400" />
          </div>
          <div>
            <h3 class="text-2xl font-semibold text-warning-600 dark:text-white/90">{{ statistics.current.pending || 0 }}
            </h3>
            <p class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
              Новые заявки перезвонить
            </p>
          </div>
        </div>
      </div>
      <div class="col-span-12 xl:col-span-1">
        <div
          class="flex items-center gap-5 rounded-2xl border border-success-200 bg-success-100 p-5 dark:border-success-800 dark:bg-white/[0.03] md:p-6">
          <div
            class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-success-200 rounded-xl dark:bg-success-500/15">
            <component :is="circleCheckBigIcon" class="text-success-600 dark:text-success-400" />
          </div>
          <div>
            <h3 class="text-2xl font-semibold text-success-600 dark:text-white/90">{{ statistics.current.processed || 0 }}
            </h3>
            <p class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
              Завершённые звонки
            </p>
          </div>
        </div>
      </div>
            <div class="col-span-12 xl:col-span-1">
        <div
          class="flex items-center gap-5 rounded-2xl border border-brand-200 bg-brand-100 p-5 dark:border-brand-800 dark:bg-white/[0.03] md:p-6">
          <div
            class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-brand-200 rounded-xl dark:bg-brand-500/15">
            <component :is="swatchBookIcon" class="text-brand-600 dark:text-brand-400" />
          </div>
          <div>
            <h3 class="text-2xl font-semibold text-brand-600 dark:text-white/90">{{ statistics.current.processed || 0 }}
            </h3>
            <p class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
              Дизайнеры
            </p>
          </div>
        </div>
      </div>
            <div class="col-span-12 xl:col-span-1">
        <div
          class="flex items-center gap-5 rounded-2xl border border-blue-light-200 bg-blue-light-100 p-5 dark:border-blue-light-800 dark:bg-white/[0.03] md:p-6">
          <div
            class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-blue-light-200 rounded-xl dark:bg-blue-light-500/15">
            <component :is="usersRoundIcon" class="text-blue-light-600 dark:text-blue-light-400" />
          </div>
          <div>
            <h3 class="text-2xl font-semibold text-blue-light-600 dark:text-white/90">{{ statistics.current.processed || 0 }}
            </h3>
            <p class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
              Клиенты
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  phoneOutgoingIcon: {
    type: Object,
    required: true
  },
  circleCheckBigIcon: {
    type: Object,
    required: true
  },
  usersRoundIcon: {
    type: Object,
    required: true
  },
  swatchBookIcon: {
    type: Object,
    required: true
  },
  statistics: {
    type: Object,
    required: true
  },
  onMonthChange: {
    type: Function,
    required: true
  }
})

const selectedMonth = ref(new Date().toISOString().slice(0, 7))

const handleMonthChange = () => {
  props.onMonthChange(selectedMonth.value)
}

onMounted(() => {
  // Устанавливаем текущий месяц по умолчанию
})
</script>
