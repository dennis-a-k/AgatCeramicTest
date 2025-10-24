<template>
  <div class="grid grid-cols-4 gap-4 md:gap-4 mb-4 md:mb-4">
    <div class="col-span-12 xl:col-span-1">
      <div
        class="rounded-2xl border border-warning-200 bg-white p-5 dark:border-warning-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex items-center justify-center w-12 h-12 bg-warning-100 rounded-xl dark:bg-warning-500/15">
          <component :is="shoppingCartIcon" class="text-warning-600 dark:text-warning-400" />
        </div>
        <div class="flex items-end justify-between mt-5">
          <div><span class="font-bold text-sm text-gray-500 dark:text-warning-400">Новые заказы</span>
            <h4 class="mt-2 font-bold text-warning-600 text-title-sm dark:text-white/90">{{ statistics.current.pending
              }}</h4>
          </div>
          <span
            class="flex items-center gap-1 rounded-full bg-gray-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-gray-600 dark:bg-gray-500/15 dark:text-gray-500">
            Всего
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-12 xl:col-span-1">
      <div
        class="rounded-2xl border border-blue-light-200 bg-white p-5 dark:border-blue-light-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex items-center justify-center w-12 h-12 bg-blue-light-100 rounded-xl dark:bg-blue-light-500/15">
          <component :is="calendarClockIcon" class="text-blue-light-600 dark:text-blue-light-400" />
        </div>
        <div class="flex items-end justify-between mt-5">
          <div><span class="font-bold text-sm text-gray-500 dark:text-blue-light-400">На выполнении</span>
            <h4 class="mt-2 font-bold text-blue-light-600 text-title-sm dark:text-white/90">{{
              statistics.current.processing }}</h4>
          </div>
          <span
            class="flex items-center gap-1 rounded-full bg-gray-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-gray-600 dark:bg-gray-500/15 dark:text-gray-500">
            Всего
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-12 xl:col-span-1">
      <div
        class="rounded-2xl border border-success-200 bg-white p-5 dark:border-success-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex items-start justify-between">
          <div class="flex items-center justify-center w-12 h-12 bg-success-100 rounded-xl dark:bg-success-500/15">
            <component :is="packageCheckIcon" class="text-success-600 dark:text-success-400" />
          </div>
          <span
            class="flex items-center gap-1 rounded-full bg-gray-50 py-0.5 pl-2 pr-2.5 text-xs font-medium text-gray-600 dark:bg-gray-500/15 dark:text-gray-500">
            за текущий месяц
          </span>
        </div>
        <div class="flex items-end justify-between mt-5">
          <div><span class="font-bold text-sm text-gray-500 dark:text-success-400">Доставлено</span>
            <h4 class="mt-2 font-bold text-success-600 text-title-sm dark:text-white/90">{{ statistics.current.shipped
              }}</h4>
          </div>
          <span :class="[
            'flex items-center gap-1 rounded-full py-0.5 pl-2 pr-2.5 text-sm font-medium',
            statistics.percentages.shipped > 0
              ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500'
              : statistics.percentages.shipped < 0
                ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500'
                : 'bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-500'
          ]">
            <component v-if="statistics.percentages.shipped !== 0"
              :is="statistics.percentages.shipped > 0 ? arrowUpIcon : arrowDownIcon" />
            {{ Math.abs(statistics.percentages.shipped) }}%
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-12 xl:col-span-1">
      <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex items-start justify-between">
          <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
            <component :is="calendarDaysIcon" class="text-gray-800 dark:text-white/90" />
          </div>
          <span
            class="flex items-center gap-1 rounded-full bg-gray-50 py-0.5 pl-2 pr-2.5 text-xs font-medium text-gray-600 dark:bg-gray-500/15 dark:text-gray-500">
            за текущий месяц
          </span>
        </div>
        <div class="flex items-end justify-between mt-5">
          <div><span class="font-bold text-sm text-gray-500 dark:text-gray-400">Сумма выполненых заказов</span>
            <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ new Intl.NumberFormat('ru-RU',
              { style: 'currency', currency: 'RUB' }).format(statistics.current.total_amount) }}</h4>
          </div>
          <span :class="[
            'flex items-center gap-1 rounded-full py-0.5 pl-2 pr-2.5 text-sm font-medium',
            statistics.percentages.total_amount > 0
              ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500'
              : statistics.percentages.total_amount < 0
                ? 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500'
                : 'bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-500'
          ]">
            <component v-if="statistics.percentages.total_amount !== 0"
              :is="statistics.percentages.total_amount > 0 ? arrowUpIcon : arrowDownIcon" />
            {{ Math.abs(statistics.percentages.total_amount) }}%
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  arrowUpIcon: {
    type: Object,
    required: true
  },
  arrowDownIcon: {
    type: Object,
    required: true
  },
  shoppingCartIcon: {
    type: Object,
    required: true
  },
  calendarClockIcon: {
    type: Object,
    required: true
  },
  calendarDaysIcon: {
    type: Object,
    required: true
  },
  packageCheckIcon: {
    type: Object,
    required: true
  },
  statistics: {
    type: Object,
    required: true
  }
})
</script>
