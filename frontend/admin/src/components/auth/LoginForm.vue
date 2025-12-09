<template>
  <div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-center mb-6">
      <button
        @click="isLogin = true"
        class="px-4 py-2 font-medium"
        :class="isLogin ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-gray-700'"
      >
        Вход
      </button>
      <button
        @click="isLogin = false"
        class="px-4 py-2 font-medium"
        :class="!isLogin ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500 hover:text-gray-700'"
      >
        Регистрация
      </button>
    </div>

    <form @submit.prevent="isLogin ? handleLogin() : handleRegister()">
      <div v-if="!isLogin" class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
          ФИО
        </label>
        <input
          v-model="form.name"
          id="name"
          type="text"
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
          Почта
        </label>
        <input
          v-model="form.email"
          id="email"
          type="email"
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
          Пароль
        </label>
        <input
          v-model="form.password"
          id="password"
          type="password"
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>

      <div v-if="!isLogin" class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
          Повторить пароль
        </label>
        <input
          v-model="form.password_confirmation"
          id="password_confirmation"
          type="password"
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          required
        />
      </div>

      <button
        type="submit"
        class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200"
        :disabled="loading"
      >
        <span v-if="!loading">{{ isLogin ? 'Войти' : 'Зарегистрироваться' }}</span>
        <span v-else>{{ isLogin ? 'Авторизация...' : 'Регистрация...' }}</span>
      </button>
      <div v-if="error" class="mt-4 text-red-500 text-sm text-center">{{ error }}</div>
      <div v-if="successMessage" class="mt-4 p-3 text-green-500 text-center">
        {{ successMessage }}
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';

const router = useRouter();
const { login, register, loading, error } = useAuth();

const isLogin = ref(true);

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  remember: false
});

watch(isLogin, () => {
  // Reset form when switching modes
  form.name = '';
  form.email = '';
  form.password = '';
  form.password_confirmation = '';
  error.value = '';
});

const successMessage = ref('');

const handleRegister = async () => {
  try {
    await register(form);
    // Reset form fields
    form.name = '';
    form.email = '';
    form.password = '';
    form.password_confirmation = '';
    // Switch to login tab
    isLogin.value = true;
    // Show success message
    successMessage.value = 'Регистрация прошла успешно! Теперь вы можете войти в систему.';
    // Clear message after 5 seconds
    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
  } catch (err) {
    // Error is already handled in useAuth
  }
};

const handleLogin = async () => {
  try {
    const user = await login(form);
    if (user) {
      router.push('/');
    }
  } catch (err) {
    // Error is already handled in useAuth
  }
};
</script>
