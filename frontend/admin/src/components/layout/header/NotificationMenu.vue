<template>
  <div class="relative" ref="dropdownRef">
    <button
      class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
      @click="toggleDropdown">
      <span :class="{ hidden: !notifying, flex: notifying }"
        class="absolute right-0 top-0.5 z-1 h-2 w-2 rounded-full bg-orange-400">
        <span class="absolute inline-flex w-full h-full bg-orange-400 rounded-full opacity-75 -z-1 animate-ping"></span>
      </span>
      <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd"
          d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z"
          fill="" />
      </svg>
    </button>
    <!-- Dropdown Start -->
    <div v-if="dropdownOpen"
      class="absolute -right-[240px] mt-[17px] flex h-[480px] w-[350px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark sm:w-[361px] lg:right-0">
      <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-100 dark:border-gray-800">
        <h5 class="text-lg font-semibold text-gray-800 dark:text-white/90">Уведомления</h5>

        <button @click="closeDropdown" class="text-gray-500 dark:text-gray-400">
          <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
              d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z"
              fill="" />
          </svg>
        </button>
      </div>
      <ul class="flex flex-col h-auto overflow-y-auto custom-scrollbar">
        <li v-for="notification in notifications" :key="notification.id" @click="handleItemClick">
          <a class="flex gap-3 rounded-lg border-b border-gray-100 p-3 px-4.5 py-3 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-white/5"
            href="#">
            <span class="relative block w-full h-10 rounded-full z-1 max-w-10">
              <span
                :class="notification.status === 'online' ? 'inline-flex h-10 w-10 items-center justify-center rounded-full bg-success-100 dark:bg-success-500/15' : 'inline-flex h-10 w-10 items-center justify-center rounded-full bg-warning-100 dark:bg-warning-500/15'">
                <component :is="notification.icon"
                  :class="notification.status === 'online' ? 'text-success-500 dark:text-success-400 w-5 h-5' : 'text-warning-500 dark:text-warning-400 w-5 h-5'" />
              </span>
              <span :class="notification.status === 'online' ? 'bg-success-500' : 'bg-error-500'"
                class="absolute bottom-0 right-0 z-10 h-2.5 w-full max-w-2.5 rounded-full border-[1.5px] border-white dark:border-gray-900"></span>
            </span>
            <span class="block">
              <span class="mb-1.5 block text-theme-sm text-gray-500 dark:text-gray-400">
                <span class="font-medium text-gray-800 dark:text-white/90">
                  {{ notification.userName }}
                </span>
                {{ notification.action }}
                <span class="font-medium text-gray-800 dark:text-white/90">
                  {{ notification.project }}
                </span>
              </span>
              <span class="flex items-center gap-2 text-gray-500 text-theme-xs dark:text-gray-400">
                <span>{{ notification.type }}</span>
                <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                <span>{{ notification.time }}</span>
              </span>
            </span>
          </a>
        </li>
      </ul>
    </div>
    <!-- Dropdown End -->
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import {
  ShoppingCartIcon,
  PhoneOutgoingIcon,
} from "@/icons";

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

const dropdownOpen = ref(false)
const dropdownRef = ref(null)
const notifications = ref([])
const lastViewed = ref(localStorage.getItem('notificationsLastViewed') || null)
const intervalId = ref(null)

const notifying = computed(() => notifications.value.some(n => n.isNew))

const fetchNotifications = async () => {
  try {
    const headers = {
      'Accept': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
    }

    const [ordersRes, callRequestsRes] = await Promise.all([
      fetch(`${API_BASE_URL}/api/orders?per_page=5`, { headers }),
      fetch(`${API_BASE_URL}/api/call-requests?per_page=5`, { headers })
    ])

    if (!ordersRes.ok || !callRequestsRes.ok) {
      throw new Error('Failed to fetch notifications')
    }

    const ordersData = await ordersRes.json()
    const callRequestsData = await callRequestsRes.json()

    const orders = ordersData.orders?.data || []
    const callRequests = callRequestsData.call_requests?.data || []

    const allNotifications = [
      ...orders.map(order => ({
        id: `order-${order.id}`,
        userName: 'Клиент',
        icon: ShoppingCartIcon,
        action: 'оформил заказ.',
        project: `Заказ #${order.order}`,
        type: 'Заказ',
        time: formatTime(order.created_at),
        status: 'online',
        isNew: !lastViewed.value || new Date(order.created_at) > new Date(lastViewed.value),
        created_at: order.created_at
      })),
      ...callRequests.map(call => ({
        id: `call-${call.id}`,
        userName: call.name || 'Клиент',
        icon: PhoneOutgoingIcon,
        action: 'оставил заявку на звонок.',
        project: `Телефон ${call.phone}`,
        type: 'Звонок',
        time: formatTime(call.created_at),
        status: 'offline',
        isNew: !lastViewed.value || new Date(call.created_at) > new Date(lastViewed.value),
        created_at: call.created_at
      }))
    ]

    notifications.value = allNotifications
      .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
      .slice(0, 10)
  } catch (error) {
    console.error('Error fetching notifications:', error)
  }
}

const formatTime = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffMs = now - date
  const diffMins = Math.floor(diffMs / 60000)
  const diffHours = Math.floor(diffMins / 60)
  const diffDays = Math.floor(diffHours / 24)

  if (diffMins < 1) return 'только что'
  if (diffMins < 60) return `${diffMins} мин назад`
  if (diffHours < 24) return `${diffHours} ч назад`
  return `${diffDays} д назад`
}

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
  if (dropdownOpen.value) {
    lastViewed.value = new Date().toISOString()
    localStorage.setItem('notificationsLastViewed', lastViewed.value)
    // Пересчитать isNew после обновления lastViewed
    notifications.value.forEach(n => {
      n.isNew = new Date(n.created_at) > new Date(lastViewed.value)
    })
  }
}

const closeDropdown = () => {
  dropdownOpen.value = false
}

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    closeDropdown()
  }
}

const handleItemClick = (event) => {
  event.preventDefault()
  // Handle the item click action here
  console.log('Notification item clicked')
  closeDropdown()
}

const handleViewAllClick = (event) => {
  event.preventDefault()
  // Handle the "View All Notification" action here
  console.log('View All Notifications clicked')
  closeDropdown()
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  fetchNotifications()
  intervalId.value = setInterval(fetchNotifications, 30000) // Обновлять каждые 30 секунд
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
  if (intervalId.value) {
    clearInterval(intervalId.value)
  }
})
</script>
