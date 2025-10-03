<template>
  <ClientOnly>
    <div class="modal fade" id="modalCall" tabindex="-1" aria-labelledby="modalCallLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Закрыть"
              @click="resetForm"></button>

            <div v-if="isSuccess" class="success-message">
              <div class="success-icon">✓</div>
              <h4>Заявка принята!</h4>
              <p>Спасибо за обращение. Мы свяжемся с вами в ближайшее время.</p>
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="resetForm">Закрыть</button>
            </div>

            <form v-else @submit.prevent="submitForm">
              <div class="mb-3">
                <label for="name" class="form-label">ФИО</label>
                <input type="text" class="form-control" id="name" v-model="form.name" required
                  placeholder="Введите ваше ФИО">
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Номер телефона</label>
                <input type="tel" class="form-control" id="phone" v-model="form.phone" @input="formatPhone" required
                  placeholder="+7 (999) 999-99-99">
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
    </div>
  </ClientOnly>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const form = ref({
  name: '',
  phone: ''
})

const isSubmitting = ref(false)
const isSuccess = ref(false)

onMounted(() => {
  const modal = document.getElementById('modalCall')
  if (modal) {
    modal.addEventListener('hide.bs.modal', () => {
      // Снимаем фокус перед скрытием модального окна
      const focusedElement = document.activeElement
      if (modal.contains(focusedElement)) {
        focusedElement.blur()
      }
    })
  }
})

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
    // Здесь можно добавить логику отправки данных на сервер
    // Например, через fetch или axios
    console.log('Отправка данных:', form.value)

    // Имитация отправки
    await new Promise(resolve => setTimeout(resolve, 1000))

    // Показываем success сообщение
    isSuccess.value = true

  } catch (error) {
    console.error('Ошибка при отправке:', error)
    alert('Произошла ошибка. Попробуйте еще раз.')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped lang="scss">
.modal-content {
  border-radius: 0;
  border: none;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
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