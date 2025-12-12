<template>
  <div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-center mb-6">
      <button
        @click="isLogin = true"
        class="px-4 py-2 font-medium"
        :class="isLogin ? 'text-brand-500 border-b-2 border-brand-500' : 'text-gray-500 hover:text-gray-700'"
      >
        Вход
      </button>
      <button
        @click="isLogin = false"
        class="px-4 py-2 font-medium"
        :class="!isLogin ? 'text-brand-500 border-b-2 border-brand-500' : 'text-gray-500 hover:text-gray-700'"
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
          :class="inputClass('name')"
          required
        />
        <div v-if="fieldErrors.name" class="text-red-500 text-sm">{{ fieldErrors.name }}</div>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
          Почта
        </label>
        <input
          v-model="form.email"
          id="email"
          type="email"
          :class="inputClass('email')"
          required
        />
        <div v-if="fieldErrors.email" class="mt-1.5 text-theme-xs text-error-500">{{ fieldErrors.email }}</div>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
          Пароль
        </label>
        <input
          v-model="form.password"
          id="password"
          type="password"
          :class="inputClass('password')"
          required
        />
        <div v-if="fieldErrors.password" class="mt-1.5 text-theme-xs text-error-500">{{ fieldErrors.password }}</div>
      </div>

      <div v-if="!isLogin" class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
          Повторить пароль
        </label>
        <input
          v-model="form.password_confirmation"
          id="password_confirmation"
          type="password"
          :class="inputClass('password_confirmation')"
          required
        />
        <div v-if="fieldErrors.password_confirmation" class="mt-1.5 text-theme-xs text-error-500">{{ fieldErrors.password_confirmation }}</div>
      </div>

      <div v-if="isLogin && !forgotPasswordForm.show" class="mb-4 text-center">
        <a href="#" @click.prevent="forgotPasswordForm.show = true" class="text-brand-500 hover:text-brand-600 text-sm">
          Восстановить пароль
        </a>
      </div>

      <div v-if="forgotPasswordForm.show" class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="forgot_email">
          Почта для восстановления
        </label>
        <input
          v-model="forgotPasswordForm.email"
          id="forgot_email"
          type="email"
          class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none"
          required
        />
        <div class="mt-6 flex gap-2">
          <button
            @click="handleForgotPassword"
            class="flex-1 bg-brand-500 text-white py-2 px-4 rounded-lg hover:bg-brand-600 transition duration-200"
            :disabled="loading"
          >
            <span v-if="!loading">Отправить</span>
            <span v-else>Отправка...</span>
          </button>
          <button
            @click="forgotPasswordForm.show = false; forgotPasswordForm.email = ''"
            class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition duration-200"
          >
            Отмена
          </button>
        </div>
      </div>

      <button
        v-if="!forgotPasswordForm.show"
        type="submit"
        class="w-full bg-brand-500 text-white py-2 px-4 rounded-lg hover:bg-brand-600 transition duration-200"
        :disabled="loading"
      >
        <span v-if="!loading">{{ isLogin ? 'Войти' : 'Зарегистрироваться' }}</span>
        <span v-else>{{ isLogin ? 'Авторизация...' : 'Регистрация...' }}</span>
      </button>
      <div v-if="successMessage" class="mt-4 p-3 text-green-500 text-center">
        {{ successMessage }}
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';

const router = useRouter();
const { login, register, forgotPassword, loading, error } = useAuth();

const isLogin = ref(true);

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  remember: false
});

const forgotPasswordForm = reactive({
  email: '',
  show: false
});

const fieldErrors = computed(() => {
  const errors = {};
  if (error.value && typeof error.value === 'object') {
    for (const [field, messages] of Object.entries(error.value)) {
      if (Array.isArray(messages) && messages.length > 0) {
        errors[field] = messages[0];
      }
    }
  }
  return errors;
});

const inputClass = (field) => {
  const hasError = fieldErrors.value[field];
  return hasError
    ? 'dark:bg-dark-900 shadow-theme-xs focus:border-error-300 focus:ring-error-500/10 dark:focus:border-error-800 h-11 w-full rounded-lg border border-error-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-error-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none'
    : 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 appearance-none';
};

watch(isLogin, () => {
  // Reset form when switching modes
  form.name = '';
  form.email = '';
  form.password = '';
  form.password_confirmation = '';
  forgotPasswordForm.show = false;
  forgotPasswordForm.email = '';
  error.value = {};
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

const handleForgotPassword = async () => {
  try {
    await forgotPassword(forgotPasswordForm.email);
    forgotPasswordForm.show = false;
    forgotPasswordForm.email = '';
    // Show success message
    successMessage.value = 'Ссылка для восстановления пароля отправлена на ваш email.';
    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
  } catch (err) {
    // Error is already handled in useAuth
  }
};
</script>
