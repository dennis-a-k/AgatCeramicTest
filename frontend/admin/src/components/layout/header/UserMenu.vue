<template>
  <div class="relative" ref="dropdownRef">
    <button class="flex items-center text-gray-700 dark:text-gray-400" @click.prevent="toggleDropdown">
      <span class="mr-3 overflow-hidden rounded-full h-8 w-8">
        <component :is="UserCircleIcon"
          class="h-8 w-8 text-brand-500 group-hover:text-brand-700 dark:group-hover:text-brand-300" />
      </span>

      <span class="block mr-1 font-medium text-theme-sm text-brand-500">{{ user?.name || 'Пользователь' }}</span>

      <ChevronDownIcon :class="{ 'rotate-180': dropdownOpen }" />
    </button>

    <!-- Dropdown Start -->
    <div v-if="dropdownOpen"
      class="absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark">
      <div>
        <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">
          {{ user?.name || 'Пользователь' }}
        </span>
        <span class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400">
          {{ user?.email || 'email@example.com' }}
        </span>
      </div>

      <ul class="flex flex-col gap-1 pt-4 pb-3 border-b border-gray-200 dark:border-gray-800">
        <div @click="handleMenuClick()"
          class="flex items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 cursor-pointer">
          <!-- SVG icon would go here -->
          <component :is="UserCircleIcon"
            class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300" />
          Редактировать профиль
        </div>
      </ul>

      <ProfileModal :isVisible="showProfileModal" :user="user" :errors="profileErrors" :loading="loading"
        @close="closeProfileModal" @save="saveProfile" />
      <div @click="signOut"
        class="flex items-center gap-3 px-3 py-2 mt-3 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 cursor-pointer">
        <LogoutIcon class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300" />
        Выйти
      </div>
    </div>
    <!-- Dropdown End -->
  </div>
</template>

<script setup>
import { UserCircleIcon, ChevronDownIcon, LogoutIcon } from '@/icons'
import { useRouter } from 'vue-router'
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuth } from '@/composables/useAuth'
import ProfileModal from '@/components/profile/ProfileModal.vue'

const { logout, getUser, updateUser, loading, error } = useAuth()
const router = useRouter()

const dropdownOpen = ref(false)
const dropdownRef = ref(null)
const user = ref(null)
const showProfileModal = ref(false)
const profileErrors = ref({})

const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value
}

const closeDropdown = () => {
  dropdownOpen.value = false
}

const fetchUser = async () => {
  try {
    const userData = await getUser()
    user.value = userData
  } catch (error) {
    console.error('Failed to fetch user:', error)
  }
}

const signOut = async () => {
  try {
    await logout()
  } catch (error) {
    console.error('Logout failed:', error)
  }
  // Always clear localStorage and redirect
  localStorage.removeItem('auth_token')
  localStorage.removeItem('auth_user')
  user.value = null
  router.push('/login')
  closeDropdown()
}

const handleMenuClick = () => {
  showProfileModal.value = true
}

const closeProfileModal = () => {
  showProfileModal.value = false
  profileErrors.value = {}
}

const saveProfile = async (formData) => {
  try {
    await updateUser(formData)
    showProfileModal.value = false
    profileErrors.value = {}
    // Refresh user data
    user.value = await getUser()
  } catch (err) {
    // Assuming error.value is an array of strings, convert to object for modal
    profileErrors.value = {
      name: error.value.find(e => e.includes('name')) || '',
      email: error.value.find(e => e.includes('email')) || '',
      password: error.value.find(e => e.includes('password')) || '',
      password_confirmation: error.value.find(e => e.includes('password_confirmation')) || ''
    }
  }
}

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    closeDropdown()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  // Load user data from localStorage immediately
  const storedUser = localStorage.getItem('auth_user')
  if (storedUser) {
    user.value = JSON.parse(storedUser)
  }
  // Then fetch fresh data from API
  fetchUser()
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>
