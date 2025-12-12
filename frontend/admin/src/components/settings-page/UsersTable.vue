<template>
  <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    <div class="flex items-start justify-between">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Пользователи</h3>
      <div class="relative">
        <div class="relative">
          <div
            class="inline-flex items-center justify-center gap-2 rounded-lg transition bg-white text-brand-700 hover:bg-success-50 hover:ring-success-300 hover:text-success-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-success-500/15 dark:hover:ring-success-500/50 dark:hover:text-success-500 cursor-pointer p-1"
            @click="openModal">
            <UserRoundPlusIcon />
          </div>
        </div>
      </div>
    </div>
    <div class="my-6">
      <div v-if="loading" class="flex justify-center items-center h-32">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-500"></div>
      </div>
      <div v-else-if="error"
        class="flex flex-col justify-center items-center h-32 font-bold text-error-700 text-theme-xl dark:text-error-500">
        Ошибка при загрузке<br />
        <button
          class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 disabled:bg-brand-300 mt-2"
          @click="fetchUsers">
          Попробовать снова
        </button>
      </div>
      <div v-else-if="users.length === 0" class="flex justify-center items-center h-32 font-bold text-theme-xl">
        Пользователи не найдены
      </div>
      <div v-else>
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-100 dark:border-gray-800">
              <th class="text-left text-gray-400 text-theme-xs pb-4">Пользователь</th>
              <th class="text-center text-gray-400 text-theme-xs pb-4">Роль</th>
              <th class="text-right text-gray-400 text-theme-xs pb-4">Действия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="border-b border-gray-100 dark:border-gray-800">
              <td class="py-3">
                <button @click="openEditModal(user)" class="text-left">
                  <span
                    class="block font-medium text-gray-800 text-theme-sm dark:text-white/90 hover:text-brand-500 dark:hover:text-brand-400">
                    {{ user.name }}
                  </span>
                  <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                    {{ user.email }}
                  </span>
                </button>
              </td>
              <td class="text-center py-3">
                <span v-if="user.role === 'user'"
                  class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                  Пользователь
                </span>
                <span v-else-if="user.role === 'moderator'"
                  class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15 dark:text-blue-light-500">
                  Модератор
                </span>
                <span v-else-if="user.role === 'admin'"
                  class="px-2.5 py-0.5 rounded-full font-bold text-xs bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                  Администратор
                </span>
              </td>
              <td class="text-right py-3">
                <button
                  class="inline-flex items-center justify-center gap-2 rounded-lg transition bg-white text-gray-700 ring-0 ring-gray-300 hover:bg-error-50 hover:ring-error-300 hover:text-error-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-error-500/15 dark:hover:ring-error-500/50 dark:hover:text-error-500 cursor-pointer p-1"
                  @click="openDeleteModal(user)" :aria-label="`Удалить пользователя ${user.name}`">
                  <DeleteIcon />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <UserDeleteConfirmationModal :isVisible="isDeleteModalVisible" :userName="selectedUser?.name"
      :userEmail="selectedUser?.email" @close="closeDeleteModal" @confirm="confirmDelete" />
    <UserEditModal :isVisible="isEditModalVisible" :user="selectedEditUser" :errors="editErrors" :isCurrentUser="isCurrentUserEditing" @close="closeEditModal"
      @save="saveUser" />
    <UserAddModal :isVisible="isAddModalVisible" :errors="addErrors" @close="closeAddModal" @save="saveAddUser" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { DeleteIcon, UserRoundPlusIcon } from '@/icons'
import UserDeleteConfirmationModal from '@/components/common/UserDeleteConfirmationModal.vue'
import UserEditModal from './UserEditModal.vue'
import UserAddModal from './UserAddModal.vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

const users = ref([])
const loading = ref(false)
const error = ref(null)
const isDeleteModalVisible = ref(false)
const selectedUser = ref(null)
const isEditModalVisible = ref(false)
const selectedEditUser = ref(null)
const editErrors = ref({})
const isAddModalVisible = ref(false)
const addErrors = ref({})
const currentUser = ref(null)
const isCurrentUserEditing = ref(false)

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

const fetchCurrentUser = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/user`, {
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      currentUser.value = data.data || data
    }
  } catch (err) {
    console.error('Error fetching current user:', err)
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

const openEditModal = (user) => {
  selectedEditUser.value = user
  isCurrentUserEditing.value = user.id === currentUser.value?.id
  isEditModalVisible.value = true
}

const closeEditModal = () => {
  isEditModalVisible.value = false
  selectedEditUser.value = null
  editErrors.value = {}
}

const saveUser = async (form) => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/users/${selectedEditUser.value.id}`, {
      method: 'PUT',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
      },
      body: JSON.stringify({ role: form.role })
    })

    if (!response.ok) {
      const errorData = await response.json()
      editErrors.value = errorData.errors || { general: errorData.message }
      throw new Error(errorData.message || 'Failed to update user')
    }

    // Update user in list
    const index = users.value.findIndex(u => u.id === selectedEditUser.value.id)
    if (index !== -1) {
      users.value[index] = { ...users.value[index], role: form.role }
    }

    closeEditModal()
  } catch (err) {
    console.error('Error updating user:', err)
    if (!editErrors.value.general) {
      alert(`Ошибка при обновлении: ${err.message || 'Unknown error'}`)
    }
  }
}

const openModal = () => {
  isAddModalVisible.value = true
}

const closeAddModal = () => {
  isAddModalVisible.value = false
  addErrors.value = {}
}

const saveAddUser = async (form) => {
  try {
    const response = await fetch(`${API_BASE_URL}/api/users`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
      },
      body: JSON.stringify(form)
    })

    if (!response.ok) {
      const errorData = await response.json()
      addErrors.value = errorData.errors || { general: errorData.message }
      return // Не закрывать модальное окно, показать ошибки
    }

    const newUser = await response.json()
    users.value.push(newUser.data || newUser)
    closeAddModal()
  } catch (err) {
    console.error('Error creating user:', err)
    alert(`Ошибка при создании: ${err.message || 'Unknown error'}`)
  }
}

onMounted(() => {
  fetchUsers()
  fetchCurrentUser()
})
</script>
