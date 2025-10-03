<template>
  <ClientOnly>
    <!-- Overlay -->
    <transition name="overlay">
      <div v-if="isVisible" class="modal-overlay" @click="closeModal" aria-hidden="true"></div>
    </transition>

    <!-- Modal -->
    <transition name="modal">
      <div v-if="isVisible" class="custom-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn-close-modal" @click="closeModal" aria-label="Закрыть"></button>

            <div v-if="isSuccess" class="success-message">
              <div class="success-icon">✓</div>
              <h4 id="modal-title">Заявка принята!</h4>
              <p>Спасибо за обращение. Мы свяжемся с вами в ближайшее время.</p>
              <button type="button" class="btn btn-primary" @click="closeModal">Закрыть</button>
            </div>

            <form v-else @submit.prevent="submitForm">
              <div class="mb-3">
                <label for="name" class="form-label">ФИО</label>
                <input type="text" class="form-control" id="name" v-model="form.name" required
                  placeholder="Введите ваше ФИО" ref="nameInput">
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Номер телефона</label>
                <input type="tel" class="form-control" id="phone" v-model="form.phone" @input="formatPhone" required
                  placeholder="+7 (___) ___-__-__">
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                  <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2" role="status"
                    aria-hidden="true"></span>
                  {{ isSubmitting ? 'Отправка...' : 'Заказать звонок' }}
                </button>
              </div>
              <p class="text-center">
                Нажимая кнопку «Заказать звонок», я даю <NuxtLink to="/personal-data" target="_blank">согласие
                </NuxtLink>
                на обработку персональных данных, в соответствии с <NuxtLink to="/policy" target="_blank">
                  Политикой</NuxtLink>
              </p>
            </form>
          </div>
        </div>
      </div>
    </transition>
  </ClientOnly>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import { useRuntimeConfig } from '#imports';

const form = ref({
  name: '',
  phone: ''
})

const isSubmitting = ref(false)
const isSuccess = ref(false)
const isVisible = ref(false)
const nameInput = ref(null)
const config = useRuntimeConfig();

// Управление видимостью модального окна
const openModal = () => {
  isVisible.value = true
  // Фокус на первом поле ввода
  nextTick(() => {
    if (nameInput.value) {
      nameInput.value.focus()
    }
  })
}

const closeModal = () => {
  isVisible.value = false
  resetForm()
}

const formatPhone = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.startsWith('7')) {
    value = value.substring(1)
  }
  if (value.length > 10) {
    value = value.substring(0, 10)
  }
  let formatted = '+7'
  if (value.length > 0) {
    formatted += ' (' + value.substring(0, 3)
  }
  if (value.length >= 4) {
    formatted += ') ' + value.substring(3, 6)
  }
  if (value.length >= 7) {
    formatted += '-' + value.substring(6, 8)
  }
  if (value.length >= 9) {
    formatted += '-' + value.substring(8, 10)
  }
  form.value.phone = formatted
}

// Функция сброса формы
const resetForm = () => {
  isSuccess.value = false
  form.value = { name: '', phone: '' }
}

// Обработчик отправки формы
const submitForm = async () => {
  if (!form.value.name || !form.value.phone) {
    alert('Пожалуйста, заполните все поля')
    return
  }

  isSubmitting.value = true

  try {
    const response = await fetch(`${config.public.apiBase}/api/call-request`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(form.value)
    })

    if (!response.ok) {
      throw new Error('Ошибка при отправке заявки')
    }

    // Показываем success сообщение
    isSuccess.value = true

  } catch (error) {
    console.error('Ошибка при отправке:', error)
    alert('Произошла ошибка. Попробуйте еще раз.')
  } finally {
    isSubmitting.value = false
  }
}

// Экспортируем функции для использования в других компонентах
defineExpose({
  openModal,
  closeModal
})
</script>

<style scoped lang="scss">
// Overlay
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1040;
}

// Custom Modal
.custom-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  z-index: 1050;
  padding: 1rem;
  padding-top: 20vh;

  .modal-content {
    background-color: $white;
    border-radius: 0;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    max-width: 500px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
  }
}

.modal-body {
  padding: 2rem;
  background-color: $white;
  position: relative;

  p {
    margin-top: 1rem;
    font-size: 13px;
    color: $theme-color;
    line-height: 1.3rem;
  }

  a {
    color: $black;
    text-decoration: underline;

    &:hover {
      text-decoration: none;
    }
  }
}

.btn-close-modal {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background-color: $white;
  width: 30px;
  height: 30px;
  font-size: 1.2rem;
  color: $body-color;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 1000;
  transition: $baseTransition;

  &:hover {
    color: $red;
  }

  &::before {
    content: '×';
    font-size: 1.5rem;
    line-height: 1;
  }
}

.form-label {
  font-weight: 500;
  color: $heading-color;
  font-family: $body-font;
  margin-bottom: 0.5rem;
  display: block;
}

.form-control {
  border-radius: 0;
  border: 1px solid $border-color;
  padding: 0.75rem 1rem;
  font-size: 14px;
  color: $body-color;
  background-color: $white;
  transition: $baseTransition;

  &::placeholder {
    color: $logo-secondary-color;
  }

  &:focus {
    border-color: $theme-color;
    box-shadow: 0 0 0 3px rgba($theme-color, 0.1);
    outline: none;
  }
}

.btn-primary {
  background-color: $theme-color;
  border: 1px solid $theme-color;
  border-radius: 0;
  padding: 0.75rem 2rem;
  font-weight: 500;
  font-family: $body-font;
  color: $white;
  width: 100%;

  &:hover {
    background-color: $body-color;
    border-color: $body-color;
  }
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none !important;
  box-shadow: none !important;
}

.spinner-border {
  width: 1rem;
  height: 1rem;
}

.success-message {
  text-align: center;
  padding: 2rem 1rem;

  .success-icon {
    width: 60px;
    height: 60px;
    background-color: $theme-color;
    color: $white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    margin: 0 auto 1.5rem;
  }

  h4 {
    color: $heading-color;
    font-family: $heading-font;
    font-size: 1.25rem;
    margin-bottom: 1rem;
  }

  p {
    color: $body-color;
    font-size: 14px;
    margin-bottom: 2rem;
    line-height: 1.5;
  }

  .btn {
    padding: 0.75rem 2rem;
    font-weight: 500;
  }
}

// Transitions
.overlay-enter-active, .overlay-leave-active {
  transition: opacity 0.3s ease;
}

.overlay-enter-from, .overlay-leave-to {
  opacity: 0;
}

.modal-enter-active, .modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: translateY(-50px);
}

// Адаптивность
@media #{$large-mobile} {
  .modal-dialog {
    margin: 1rem;
  }

  .modal-header,
  .modal-body {
    padding: 1.5rem;
  }

  .modal-title {
    font-size: 1.1rem;
  }
}
</style>