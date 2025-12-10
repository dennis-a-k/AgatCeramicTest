<template>
  <div class="mb-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Статистика звонков</h2>
      <div>
        <div class="relative">
          <button
            @click="toggleMonthPicker"
            class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 pl-4 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 flex items-center justify-between"
          >
            {{ selectedMonthText }}
            <span class="text-gray-500 dark:text-gray-400 ml-2">
              <svg
                class="fill-current"
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M6.66659 1.5415C7.0808 1.5415 7.41658 1.87729 7.41658 2.2915V2.99984H12.5833V2.2915C12.5833 1.87729 12.919 1.5415 13.3333 1.5415C13.7475 1.5415 14.0833 1.87729 14.0833 2.2915V2.99984L15.4166 2.99984C16.5212 2.99984 17.4166 3.89527 17.4166 4.99984V7.49984V15.8332C17.4166 16.9377 16.5212 17.8332 15.4166 17.8332H4.58325C3.47868 17.8332 2.58325 16.9377 2.58325 15.8332V7.49984V4.99984C2.58325 3.89527 3.47868 2.99984 4.58325 2.99984L5.91659 2.99984V2.2915C5.91659 1.87729 6.25237 1.5415 6.66659 1.5415ZM6.66659 4.49984H4.58325C4.30711 4.49984 4.08325 4.7237 4.08325 4.99984V6.74984H15.9166V4.99984C15.9166 4.7237 15.6927 4.49984 15.4166 4.49984H13.3333H6.66659ZM15.9166 8.24984H4.08325V15.8332C4.08325 16.1093 4.30711 16.3332 4.58325 16.3332H15.4166C15.6927 16.3332 15.9166 16.1093 15.9166 15.8332V8.24984Z"
                  fill=""
                />
              </svg>
            </span>
          </button>
          <div v-if="showMonthPicker" class="absolute z-10 mt-1 right-0 w-80 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg p-4">
            <div class="grid grid-cols-3 gap-2">
              <button
                v-for="(month, index) in months"
                :key="index"
                @click="selectMonth(index)"
                :class="[
                  'p-2 text-sm rounded hover:bg-gray-100 dark:hover:bg-gray-800',
                  selectedMonthIndex === index ? 'bg-brand-100 dark:bg-brand-800 text-brand-600 dark:text-brand-400' : 'text-gray-700 dark:text-gray-300'
                ]"
              >
                {{ month }}
              </button>
            </div>
          </div>
        </div>
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
import { ref, onMounted, computed } from 'vue'

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

const showMonthPicker = ref(false)
const selectedMonthIndex = ref(new Date().getMonth())

const months = [
  'Январь', 'Февраль', 'Март',
  'Апрель', 'Май', 'Июнь',
  'Июль', 'Август', 'Сентябрь',
  'Октябрь', 'Ноябрь', 'Декабрь'
]

const selectedMonthText = computed(() => {
  return months[selectedMonthIndex.value]
})

const toggleMonthPicker = () => {
  showMonthPicker.value = !showMonthPicker.value
}

const selectMonth = (index) => {
  selectedMonthIndex.value = index
  showMonthPicker.value = false
  const year = new Date().getFullYear()
  const month = String(index + 1).padStart(2, '0')
  props.onMonthChange(`${year}-${month}`)
}

onMounted(() => {
  // Устанавливаем текущий месяц по умолчанию
  const currentMonth = new Date().getMonth()
  selectedMonthIndex.value = currentMonth
})
</script>
