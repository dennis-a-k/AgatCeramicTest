import { ref } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

export function useAuth() {
  const loading = ref(false)
  const error = ref<string | null>(null)

  const getCsrfToken = async () => {
    try {
      await fetch(`${API_BASE_URL}/sanctum/csrf-cookie`, {
        method: 'GET',
        credentials: 'include'
      })
    } catch (err) {
      console.error('CSRF token fetch error:', err)
      throw err
    }
  }

  const register = async (credentials: { name: string; email: string; password: string; password_confirmation: string }) => {
    loading.value = true
    error.value = null

    try {
      await getCsrfToken()

      const response = await fetch(`${API_BASE_URL}/api/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify(credentials)
      })

      if (!response.ok) {
        const errorData = await response.json()
        throw new Error(errorData.message || 'Registration failed')
      }

      return await response.json()
    } catch (err) {
      error.value = (err as Error).message
      throw err
    } finally {
      loading.value = false
    }
  }

  const login = async (credentials: { email: string; password: string }) => {
    loading.value = true
    error.value = null

    try {
      await getCsrfToken()

      const response = await fetch(`${API_BASE_URL}/api/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify(credentials)
      })

      if (!response.ok) {
        const errorData = await response.json()
        throw new Error(errorData.message || 'Login failed')
      }

      const data = await response.json()
      localStorage.setItem('auth_token', data.token)
      return data
    } catch (err) {
      error.value = (err as Error).message
      throw err
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    loading.value = true
    error.value = null

    try {
      await getCsrfToken()

      const response = await fetch(`${API_BASE_URL}/api/logout`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json'
        },
        credentials: 'include'
      })

      if (!response.ok) {
        throw new Error('Logout failed')
      }

      return await response.json()
    } catch (err) {
      error.value = (err as Error).message
      throw err
    } finally {
      loading.value = false
    }
  }

  const getUser = async () => {
    loading.value = true
    error.value = null

    try {
      await getCsrfToken()

      const response = await fetch(`${API_BASE_URL}/api/user`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json'
        },
        credentials: 'include'
      })

      if (!response.ok) {
        throw new Error('Failed to fetch user')
      }

      return await response.json()
    } catch (err) {
      error.value = (err as Error).message
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    error,
    register,
    login,
    logout,
    getUser
  }
}
