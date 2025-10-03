<template>
  <div class="modal fade" id="modalCall" tabindex="-1" aria-labelledby="modalCallLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Закрыть"></button>
          <form @submit.prevent="submitForm">
            <div class="mb-3">
              <label for="name" class="form-label">ФИО</label>
              <input type="text" class="form-control" id="name" v-model="form.name" required
                placeholder="Введите ваше ФИО">
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Номер телефона</label>
              <input type="tel" class="form-control" id="phone" v-model="form.phone" v-cleave="{
                prefix: '+7',
                blocks: [0, 3, 3, 2, 2],
                delimiters: [' (', ') ', '-', '-'],
                numericOnly: true
              }" required placeholder="+7 (999) 999-99-99">
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2" role="status"
                  aria-hidden="true"></span>
                {{ isSubmitting ? 'Отправка...' : 'Заказать звонок' }}
              </button>
            </div>
            <p class="text-center">
              Нажимая кнопку «Заказать звонок», я даю <NuxtLink to="/personal-data" target="_blank">согласие</NuxtLink>
              на обработку персональных данных, в соответствии с <NuxtLink to="/policy" target="_blank">
                Политикой</NuxtLink>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const form = ref({
  name: '',
  phone: ''
})

const isSubmitting = ref(false)

const $bootstrap = useNuxtApp().$bootstrap

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

    alert('Спасибо! Мы свяжемся с вами в ближайшее время.')

    // Закрываем модальное окно
    const modal = document.getElementById('modalCall')
    const bsModal = $bootstrap.Modal.getInstance(modal)
    bsModal.hide()

    // Очищаем форму
    form.value = { name: '', phone: '' }

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