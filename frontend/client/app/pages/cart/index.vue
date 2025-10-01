<template>
  <ClientOnly>
  <div class="cart-page">
    <div class="container">
      <UiAppSpinner v-if="loading" text="Загрузка корзины..." />
      <div v-else>
        <h1 class="page-title">Корзина</h1>

      <div v-if="cartStore.items.length === 0" class="empty-cart">
        <p>Ваша корзина пуста</p>
        <NuxtLink to="/" class="btn">Вернуться к покупкам</NuxtLink>
      </div>

      <div v-else class="cart-content">
        <div class="cart-items">
          <div v-for="item in cartStore.items" :key="item.id" class="cart-item">
            <div class="item-image">
              <NuxtLink :to="`/product/${item.id}`">
                <img :src="item.image" :alt="item.title">
              </NuxtLink>
            </div>
            <div class="item-details">
              <h3 class="item-title">
                <NuxtLink :to="`/product/${item.id}`">{{ item.title }}</NuxtLink>
              </h3>
              <p class="item-weight">{{ item.weight_kg }} кг</p>
              <div class="item-price">{{ formatPrice(item.price) }} &#8381;</div>
            </div>
            <div class="item-quantity">
              <div class="quantity-controls">
                <button @click="updateQuantity(item.id, item.quantity - 1)" :disabled="item.quantity <= 1">-</button>
                <input type="number" :value="item.quantity" @input="updateQuantity(item.id, parseInt($event.target.value) || 1)" min="1">
                <button @click="updateQuantity(item.id, item.quantity + 1)">+</button>
              </div>
            </div>
            <div class="item-total">
              {{ formatPrice(item.price * item.quantity) }} &#8381;
            </div>
            <div class="item-remove">
              <button @click="removeItem(item.id)" class="remove-btn">×</button>
            </div>
          </div>
        </div>

        <div class="cart-summary">
          <div class="summary-row">
            <span>Итого:</span>
            <span class="total-amount">{{ formatPrice(cartStore.total) }} &#8381;</span>
          </div>
          <NuxtLink to="/checkout" class="btn checkout-btn">Оформить заказ</NuxtLink>
        </div>
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
const loading = ref(true);

onMounted(() => {
  // Имитируем загрузку данных
  setTimeout(() => {
    loading.value = false;
  }, 500);
});

const formatPrice = (price) => {
  return Number(price).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
};

const removeItem = (id) => {
  cartStore.removeFromCart(id);
};

const updateQuantity = (id, quantity) => {
  cartStore.updateQuantity(id, Math.max(1, quantity));
};
</script>

<style scoped lang="scss">
.cart-page {
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

  .cart-content {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 40px;

    @media (max-width: 768px) {
      grid-template-columns: 1fr;
    }
  }

  .cart-items {
    .cart-item {
      display: grid;
      grid-template-columns: 100px 1fr 150px 100px 50px;
      gap: 20px;
      align-items: center;
      padding: 20px 0;
      border-bottom: 1px solid $border-color;

      @media (max-width: 768px) {
        grid-template-columns: 80px 1fr;
        gap: 10px;
      }

      .item-image {
        img {
          max-width: 100%;
          height: auto;
          border: 1px solid $border-color;
        }
      }

      .item-details {
        .item-title {
          margin: 0 0 10px 0;
          font-size: 1.1rem;

          a {
            color: $heading-color;
            text-decoration: none;

            &:hover {
              color: $theme-color;
            }
          }
        }

        .item-weight {
          color: $body-color;
          margin-bottom: 5px;
        }

        .item-price {
          font-weight: 500;
          color: $theme-color;
        }
      }

      .item-quantity {
        .quantity-controls {
          display: flex;
          align-items: center;
          gap: 10px;

          button {
            width: 30px;
            height: 30px;
            border: 1px solid $border-color;
            background: $white;
            cursor: pointer;

            &:hover {
              background: $theme-color;
              color: $white;
            }

            &:disabled {
              opacity: 0.5;
              cursor: not-allowed;
            }
          }

          input {
            width: 50px;
            text-align: center;
            border: 1px solid $border-color;
            padding: 5px;
          }
        }
      }

      .item-total {
        font-weight: 600;
        color: $theme-color;
      }

      .item-remove {
        .remove-btn {
          background: none;
          border: none;
          font-size: 1.5rem;
          color: $red;
          cursor: pointer;

          &:hover {
            color: darken($red, 10%);
          }
        }
      }
    }
  }

  .cart-summary {
    background: $white;
    padding: 20px;
    border: 1px solid $border-color;
    border-radius: 5px;

    .summary-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      font-size: 1.2rem;
      font-weight: 600;

      .total-amount {
        color: $theme-color;
      }
    }

    .checkout-btn {
      display: block;
      width: 100%;
      text-align: center;
      background: $theme-color;
      color: $white;
      padding: 15px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: 600;

      &:hover {
        background: darken($theme-color, 10%);
      }
    }
  }
}
</style>