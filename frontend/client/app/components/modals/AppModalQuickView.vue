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

            <div class="product-quickview">
              <div class="product-image">
                <img :src="productImage" :alt="product.name" />
              </div>
              <div class="product-info">
                <span class="category">{{ product.category.name }}</span>
                <h4 id="modal-title" class="product-title">{{ product.name }}{{ weight ? `, ${weight} кг` : '' }}</h4>
                <div class="price">
                  <span class="new">{{ formattedPrice }}</span>
                </div>
                <div class="info">
                  <p><span>Артикул:</span> {{ product.article }}</p>
                  <p><span>Бренд:</span> {{ product.brand.name }}</p>
                  <p v-if="product.collection"><span>Коллекция:</span> {{ product.collection }}</p>
                  <p v-if="product.color"><span>Цвет:</span> {{ product.color.name }}</p>
                  <p v-if="sizes"><span>Размеры:</span> {{ sizes }}мм</p>
                </div>
                <div class="actions">
                  <div class="quantity-selector d-flex">
                    <div class="cart-plus-minus me-2">
                      <div class="dec qtybutton" @click="decrement">-</div>
                      <input class="cart-plus-minus-box" type="text" name="qtybutton" v-model="quantity" />
                      <div class="inc qtybutton" @click="increment">+</div>
                    </div>
                    <p v-if="product.unit">{{ product.unit === 'шт' ? 'шт.' : product.unit === 'кв.м' ? 'м²' :
                      product.unit }}</p>
                  </div>
                  <button class="btn btn-primary add-to-cart" @click="addToCart">В корзину</button>
                  <NuxtLink :to="`/product/${product.slug}`" class="btn btn-outline-primary">Подробнее</NuxtLink>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </ClientOnly>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useCartStore } from '~/stores/useCartStore'

const cartStore = useCartStore()
const { $toast } = useNuxtApp()

const isVisible = ref(false)
const product = ref({})
const quantity = ref(1)

const formatter = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
})

const productImage = computed(() => {
  return (product.value.imgSrc && product.value.imgSrc.trim() !== '')
    ? product.value.imgSrc
    : '/images/stock/stock-image.png'
})

const weight = computed(() => {
  const attr = product.value?.attribute_values?.find(a => a.attribute.name === 'Вес')
  return attr ? attr.number_value : null
})

const sizes = computed(() => {
  const attr = product.value?.attribute_values?.find(a => a.attribute.name === 'Размеры')
  return attr ? attr.string_value : null
})

const formattedPrice = computed(() => {
  return formatter.format(product.value.price || 0)
})

// Управление видимостью модального окна
const openModal = (prod) => {
  product.value = prod
  isVisible.value = true
}

const closeModal = () => {
  isVisible.value = false
  quantity.value = 1
}

const increment = () => {
  quantity.value += 1
}

const decrement = () => {
  if (quantity.value > 1) {
    quantity.value -= 1
  }
}

const addToCart = () => {
  const weightAttribute = product.value.attribute_values?.find(a => a.attribute.name === 'Вес')

  cartStore.addToCart({
    id: product.value.id,
    slug: product.value.slug,
    title: product.value.name,
    weight_kg: weightAttribute?.number_value || product.value.weight_kg,
    quantity: quantity.value,
    price: product.value.price,
    unit: product.value.unit,
    image: productImage.value
  })

  $toast.success('Товар добавлен в корзину!')
  closeModal()
}

// Экспортируем функции для использования в других компонентах
defineExpose({
  openModal,
  closeModal
})
</script>

<style scoped lang="scss">
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1040;
}

.custom-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
  padding: 1rem;

  .modal-content {
    background-color: $white;
    border-radius: 0;
    border: none;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    max-width: 900px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
  }
}

.modal-body {
  padding: 2rem;
  background-color: $white;
  position: relative;

  .product-quickview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;

    .product-image {
      img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
      }
    }

    .product-info {
      display: flex;
      flex-direction: column;

      .category {
        font-size: 14px;
        color: $logo-secondary-color;
        font-weight: 500;
        margin-bottom: 0.5rem;
      }

      .product-title {
        font-size: 1.5rem;
        color: $heading-color;
        font-family: $heading-font;
        margin-bottom: 1rem;
        line-height: 1.3;
      }

      .price {
        font-size: 24px;
        font-family: $Poppins;
        font-weight: 500;
        color: $theme-color;
        margin-bottom: 2rem;
      }

      .info {
        p {
          color: $black;
        }

        span {
          font-weight: 500;
          color: $theme-color;
          margin-right: 0.5rem;
        }
      }

      .quantity-selector {
        align-items: center;

        .cart-plus-minus {
          border: 1px solid $body-color;
          height: 40px;
          overflow: hidden;
          padding: 0;
          position: relative;
          width: 80px;
          background: $white;

          .qtybutton {
            color: $body-color;
            cursor: pointer;
            float: inherit;
            font-size: 18px;
            font-weight: 500;
            line-height: 20px;
            margin: 0;
            position: absolute;
            text-align: center;
            -webkit-transition: all 0.3s ease 0s;
            -o-transition: all 0.3s ease 0s;
            transition: all 0.3s ease 0s;
            width: 30px;
          }

          input {
            &.cart-plus-minus-box {
              background: transparent none repeat scroll 0 0;
              border: medium none;
              color: $body-color;
              font-weight: 600;
              float: left;
              font-size: 14px;
              height: 40px;
              margin: 0;
              padding: 0;
              text-align: center;
              width: 80px;
              outline: none;
            }
          }

          .inc {
            &.qtybutton {
              height: 40px;
              padding-top: 10px;
              right: 0;
              top: 0;
            }
          }

          .dec {
            &.qtybutton {
              height: 40px;
              left: 0;
              padding-top: 10px;
              top: 0;
            }
          }
        }
      }

      .actions {
        display: flex;
        gap: 1rem;
        margin-top: auto;
        justify-content: flex-end;

        .btn {
          height: 40px;
          padding: 0.5rem 0.75rem;
          font-size: 14px;
          font-weight: 500;
          border-radius: 0;
          text-decoration: none;
          text-align: center;
          display: flex;
          align-items: center;
          justify-content: center;

          &.btn-primary {
            background-color: $theme-color;
            border: 1px solid $theme-color;
            color: $white;
            width: auto;

            &:hover {
              background-color: $body-color;
              border-color: $body-color;
              transform: none;
            }
          }

          &.btn-outline-primary {
            background-color: transparent;
            border: 1px solid $theme-color;
            color: $theme-color;
            width: auto;

            &:hover {
              background-color: $theme-color;
              color: $white;
            }
          }
        }
      }
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

// Transitions
.overlay-enter-active,
.overlay-leave-active {
  transition: opacity 0.3s ease;
}

.overlay-enter-from,
.overlay-leave-to {
  opacity: 0;
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: translateY(-50px);
}

// Адаптивность
@media #{$tablet-device} {
  .modal-body {
    padding: 1.5rem;

    .product-quickview {
      gap: 1.5rem;

      .product-image img {
        max-height: 400px;
      }

      .product-info .product-title {
        font-size: 1.25rem;
      }
    }
  }
}

@media #{$large-mobile} {
  .modal-body {
    padding: 1.5rem;

    .product-quickview {
      grid-template-columns: 1fr;
      gap: 1rem;

      .product-image img {
        max-height: 300px;
      }

      .product-info .product-title {
        font-size: 1.25rem;
      }

      .actions {
        flex-direction: column;

        .quantity-selector {
          margin-right: 0;
        }
      }
    }
  }
}

@media #{$small-mobile} {
  .modal-body {
    padding: 1rem;

    .product-quickview {
      gap: 0.75rem;

      .product-image img {
        max-height: 250px;
      }

      .product-info .product-title {
        font-size: 1.1rem;
      }

      .price {
        font-size: 20px;
        margin-bottom: 1.5rem;
      }
    }
  }
}
</style>