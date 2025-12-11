<template>
  <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    <div class="flex items-start justify-between">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Пользователи</h3>
    </div>
    <div class="my-6">
      <div v-if="loading" class="flex justify-center items-center h-32">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-500"></div>
      </div>
      <div v-else-if="error" class="flex flex-col justify-center items-center h-32 font-bold text-error-700 text-theme-xl dark:text-error-500">
        Ошибка при загрузке<br />
        <button class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300 mt-2" @click="fetchUsers">
          Попробовать снова
        </button>
      </div>
      <div v-else-if="users.length === 0" class="flex justify-center items-center h-32 font-bold text-theme-xl">
        Пользователи не найдены
      </div>
      <div v-else>
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
          <span class="text-gray-400 text-theme-xs">Пользователь</span>
          <span class="text-right text-gray-400 text-theme-xs">Роль</span>
          <span class="text-right text-gray-400 text-theme-xs"></span>
        </div>
        <div v-for="user in users" :key="user.id" class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
          <span class="text-gray-500 text-theme-sm dark:text-gray-400">
            <button @click="" class="text-left">
              <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90 hover:text-brand-500 dark:hover:text-brand-400">
                {{ user.name }}
              </span>
              <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                {{ user.email }}
              </span>
            </button>
          </span>
          <span class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
            {{ user.role === 'admin' ? 'администратор' : 'пользователь' }}
          </span>
          <button
            class="inline-flex items-center justify-center gap-2 rounded-lg transition bg-white text-gray-700 ring-0 ring-gray-300 hover:bg-error-50 hover:ring-error-300 hover:text-error-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-error-500/15 dark:hover:ring-error-500/50 dark:hover:text-error-500 cursor-pointer p-1"
            @click="openDeleteModal(user)" :aria-label="`Удалить пользователя ${user.name}`">
            <DeleteIcon />
          </button>
        </div>
      </div>
    </div>
    <UserDeleteConfirmationModal
      :isVisible="isDeleteModalVisible"
      :userName="selectedUser?.name"
      :userEmail="selectedUser?.email"
      @close="closeDeleteModal"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { DeleteIcon } from '@/icons'
import UserDeleteConfirmationModal from '@/components/common/UserDeleteConfirmationModal.vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

const users = ref([])
const loading = ref(false)
const error = ref(null)
const isDeleteModalVisible = ref(false)
const selectedUser = ref(null)

const fetchUsers = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await fetch(`${API_BASE_URL}/api/users`, {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
      }
    })

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    users.value = data.data || data
  } catch (err) {
    error.value = err.message || 'Unknown error'
    console.error('Error fetching users:', err)
  } finally {
    loading.value = false
  }
}

const deleteUser = async (user) => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/users/${user.id}`, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
      }
    })

    if (!response.ok) {
      const errorData = await response.json()
      throw new Error(errorData.message || 'Failed to delete user')
    }

    // Remove from list
    users.value = users.value.filter(u => u.id !== user.id)
  } catch (err) {
    alert(`Ошибка при удалении: ${err.message || 'Unknown error'}`)
    console.error('Error deleting user:', err)
  }
}

const openDeleteModal = (user) => {
  selectedUser.value = user
  isDeleteModalVisible.value = true
}

const closeDeleteModal = () => {
  isDeleteModalVisible.value = false
  selectedUser.value = null
}

const confirmDelete = () => {
  deleteUser(selectedUser.value)
  closeDeleteModal()
}

onMounted(() => {
  fetchUsers()
})
</script>
