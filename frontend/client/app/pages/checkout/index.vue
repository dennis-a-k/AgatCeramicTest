<template>
  <ClientOnly>
  <div class="checkout-page">
    <div class="container">
      <UiAppSpinner v-if="pageLoading" text="Загрузка страницы оформления..." />
      <div v-else>
        <h1 class="page-title">Оформление заказа</h1>

      <div v-if="cartStore.items.length === 0" class="empty-cart">
        <p>Ваша корзина пуста</p>
        <NuxtLink to="/" class="btn">Вернуться к покупкам</NuxtLink>
      </div>

      <div v-else class="checkout-content">
        <div class="order-summary">
          <h2>Ваш заказ</h2>
          <div class="order-items">
            <div v-for="item in cartStore.items" :key="item.id" class="order-item">
              <div class="item-info">
                <img :src="item.image" :alt="item.title">
                <div>
                  <h4>{{ item.title }}</h4>
                  <p>{{ item.weight_kg }} кг × {{ item.quantity }}</p>
                </div>
              </div>
              <div class="item-price">{{ formatPrice(item.price * item.quantity) }} &#8381;</div>
            </div>
          </div>
          <div class="order-total">
            <strong>Итого: {{ formatPrice(cartStore.total) }} &#8381;</strong>
          </div>
        </div>

        <form @submit.prevent="submitOrder" class="checkout-form">
          <h2>Контактные данные</h2>

          <div class="form-group">
            <label for="name">Имя *</label>
            <input type="text" id="name" v-model="form.name" required>
          </div>

          <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" v-model="form.email" required>
          </div>

          <div class="form-group">
            <label for="phone">Телефон *</label>
            <input type="tel" id="phone" v-model="form.phone" required>
          </div>

          <div class="form-group">
            <label for="address">Адрес доставки *</label>
            <textarea id="address" v-model="form.address" required></textarea>
          </div>

          <div class="form-group">
            <label for="comment">Комментарий к заказу</label>
            <textarea id="comment" v-model="form.comment"></textarea>
          </div>

          <button type="submit" class="btn submit-btn" :disabled="loading">
            {{ loading ? 'Оформление...' : 'Оформить заказ' }}
          </button>
        </form>
      </div>
      </div>
    </div>
  </div>
  </ClientOnly>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useCartStore } from '~/stores/useCartStore';

const cartStore = useCartStore();
const { $toast } = useNuxtApp();

const loading = ref(false);
const pageLoading = ref(true);

onMounted(() => {
  // Имитируем загрузку данных
  setTimeout(() => {
    pageLoading.value = false;
  }, 500);
});
const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  comment: ''
});

const formatPrice = (price) => {
  return Number(price).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
};

const submitOrder = async () => {
  loading.value = true;

  try {
    // Здесь можно отправить заказ на сервер
    const orderData = {
      customer: form.value,
      items: cartStore.items,
      total: cartStore.total
    };

    console.log('Order data:', orderData);

    // Имитация отправки
    await new Promise(resolve => setTimeout(resolve, 2000));

    // Очистка корзины
    cartStore.clearCart();

    // Сброс формы
    form.value = {
      name: '',
      email: '',
      phone: '',
      address: '',
      comment: ''
    };

    // Уведомление
    $toast.success('Заказ успешно оформлен! Мы свяжемся с вами в ближайшее время.');

    // Перенаправление на главную
    await navigateTo('/');

  } catch (error) {
    console.error('Error submitting order:', error);
    $toast.error('Произошла ошибка при оформлении заказа. Попробуйте еще раз.');
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped lang="scss">
.checkout-page {
  padding: 50px 0;
  min-height: 60vh;

  .page-title {
    text-align: center;
    margin-bottom: 40px;
    font-size: 2rem;
    color: $heading-color;
  }

  .empty-cart {
    text-align: center;
    padding: 50px 0;

    p {
      font-size: 1.2rem;
      margin-bottom: 20px;
      color: $heading-color;
    }

    .btn {
      background: $theme-color;
      color: $white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;

      &:hover {
        background: darken($theme-color, 10%);
      }
    }
  }

  .checkout-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 40px;

    @media (max-width: 768px) {
      grid-template-columns: 1fr;
    }
  }

  .order-summary {
    background: $white;
    padding: 20px;
    border: 1px solid $border-color;
    border-radius: 5px;

    h2 {
      margin-bottom: 20px;
      color: $heading-color;
    }

    .order-items {
      .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid $border-color;

        &:last-child {
          border-bottom: none;
        }

        .item-info {
          display: flex;
          align-items: center;
          gap: 15px;

          img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border: 1px solid $border-color;
          }

          h4 {
            margin: 0 0 5px 0;
            font-size: 1rem;
            color: $heading-color;
          }

          p {
            margin: 0;
            color: $body-color;
            font-size: 0.9rem;
          }
        }

        .item-price {
          font-weight: 600;
          color: $theme-color;
        }
      }
    }

    .order-total {
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px solid $border-color;
      text-align: right;
      font-size: 1.2rem;
      color: $heading-color;
    }
  }

  .checkout-form {
    background: $white;
    padding: 20px;
    border: 1px solid $border-color;
    border-radius: 5px;

    h2 {
      margin-bottom: 20px;
      color: $heading-color;
    }

    .form-group {
      margin-bottom: 20px;

      label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: $heading-color;
      }

      input, textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid $border-color;
        border-radius: 3px;
        font-size: 1rem;

        &:focus {
          outline: none;
          border-color: $theme-color;
        }
      }

      textarea {
        resize: vertical;
        min-height: 80px;
      }
    }

    .submit-btn {
      width: 100%;
      background: $theme-color;
      color: $white;
      padding: 15px;
      border: none;
      border-radius: 5px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;

      &:hover:not(:disabled) {
        background: darken($theme-color, 10%);
      }

      &:disabled {
        opacity: 0.7;
        cursor: not-allowed;
      }
    }
  }
}
</style>