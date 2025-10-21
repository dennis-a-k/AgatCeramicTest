<template>
  <ClientOnly>
    <main class="checkout-area pt-100px pb-100px">
      <div class="container">
        <UiAppSpinner
          v-if="pageLoading"
          text="Загрузка страницы оформления..."
        />
        <div v-else>
          <div class="col-md-12" v-if="cartStore.items.length === 0">
            <div class="empty-text-contant text-center">
              <i class="pe-7s-cart"></i>
              <h3>Ваша корзина пуста</h3>
              <NuxtLink to="/" class="empty-cart-btn">
                <i class="fa fa-arrow-left"></i> Перети к покупкам
              </NuxtLink>
            </div>
          </div>
          <form @submit.prevent="submitOrder" v-else>
            <div class="row">
              <div class="col-lg-6">
                <div class="billing-info-wrap">
                  <h3>Детали заказа</h3>
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="billing-info mb-4">
                        <label for="name">ФИО</label>
                        <input
                          type="text"
                          id="name"
                          v-model="form.name"
                          autocomplete="name"
                          required
                        />
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="billing-info mb-4">
                        <label for="address">Адрес доставки</label>
                        <input
                          type="text"
                          id="address"
                          v-model="form.address"
                          autocomplete="address"
                          required
                        />
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="billing-info mb-4">
                        <label for="phone">Телефон</label>
                        <input
                          type="tel"
                          id="phone"
                          v-model="form.phone"
                          @input="formatPhone"
                          placeholder="+7 (___) ___-__-__"
                          autocomplete="phone"
                          required
                        />
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="billing-info mb-4">
                        <label for="email">Электронная почта</label>
                        <input
                          type="email"
                          id="email"
                          v-model="form.email"
                          autocomplete="email"
                          required
                        />
                      </div>
                    </div>
                  </div>
                  <div class="additional-info-wrap">
                    <div class="additional-info">
                      <label for="comment">Комментарий к заказу</label>
                      <textarea id="comment" v-model="form.comment"></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 mt-md-30px mt-lm-30px">
                <div class="your-order-area">
                  <h3>Ваш заказ</h3>
                  <div class="your-order-wrap gray-bg-4">
                    <div class="your-order-product-info">
                      <div class="your-order-top">
                        <ul>
                          <li>Товар</li>
                          <li>Стоимость</li>
                        </ul>
                      </div>
                      <div class="your-order-middle">
                        <div
                          v-for="item in cartStore.items"
                          :key="item.id"
                          class="row align-items-center lh-1 mb-3"
                        >
                          <div class="col-7">
                            <span class="order-middle-left">
                              {{ item.title
                              }}{{
                                item.weight_kg ? `, ${item.weight_kg}кг` : ''
                              }}
                            </span>
                          </div>
                          <div class="col-2 text-end">
                            × {{ item.quantity }}
                            {{
                              item.unit === 'шт'
                                ? 'шт.'
                                : item.unit === 'кв.м'
                                ? 'м²'
                                : item.unit
                            }}
                          </div>
                          <div class="col-3 text-end">
                            <span class="order-price">{{
                              formatPrice(item.price * item.quantity)
                            }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="your-order-total">
                        <ul>
                          <li class="order-total">Итого</li>
                          <li>{{ formatPrice(cartStore.total) }}</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="row Place-order mt-25">
                    <div class="col-md-6 mb-3 mb-md-0">
                      <NuxtLink to="/cart" class="btn-change"
                        >Изменить</NuxtLink
                      >
                    </div>
                    <div class="col-md-6">
                      <button
                        type="submit"
                        class="btn-order"
                        style="width: 100%"
                        :disabled="loading"
                      >
                        {{ loading ? 'Оформление...' : 'Заказать' }}
                      </button>
                    </div>
                  </div>
                  <div class="your-order-foot mt-3">
                    <p class="text-end">
                      Нажимая кнопку «Заказать», я даю
                      <NuxtLink to="/personal-data" target="_blank"
                        >согласие</NuxtLink
                      >
                      на обработку персональных данных, в соответствии с
                      <NuxtLink to="/policy" target="_blank">
                        Политикой</NuxtLink
                      >
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </main>
  </ClientOnly>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useCartStore } from '~/stores/useCartStore';
import { useRuntimeConfig } from '#imports';

const cartStore = useCartStore();
const config = useRuntimeConfig();
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
  comment: '',
});

const formatPhone = (event) => {
  let value = event.target.value.replace(/\D/g, '');
  if (value.startsWith('7')) {
    value = value.substring(1);
  }
  if (value.length > 10) {
    value = value.substring(0, 10);
  }
  let formatted = '+7';
  if (value.length > 0) {
    formatted += ' (' + value.substring(0, 3);
  }
  if (value.length >= 4) {
    formatted += ') ' + value.substring(3, 6);
  }
  if (value.length >= 7) {
    formatted += '-' + value.substring(6, 8);
  }
  if (value.length >= 9) {
    formatted += '-' + value.substring(8, 10);
  }
  form.value.phone = formatted;
};

const formatter = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
});

const formatPrice = (price) => {
  return formatter.format(price);
};

const submitOrder = async () => {
  loading.value = true;

  try {
    const orderData = {
      customer: form.value,
      items: cartStore.items,
      total: cartStore.total,
    };

    const response = await $fetch(`${config.public.apiBase}/api/checkout`, {
      method: 'POST',
      body: orderData,
    });

    // Очистка корзины
    cartStore.clearCart();

    // Сброс формы
    form.value = {
      name: '',
      email: '',
      phone: '',
      address: '',
      comment: '',
    };

    // Уведомление
    $toast.success(
      'Заказ успешно оформлен! Мы свяжемся с вами в ближайшее время.'
    );

    // Перенаправление на страницу заказа
    await navigateTo('/order/' + response.order);
  } catch (error) {
    console.error('Error submitting order:', error);
    $toast.error('Произошла ошибка при оформлении заказа. Попробуйте еще раз.');
  } finally {
    loading.value = false;
  }
};

const structuredData = computed(() => {
  if (cartStore.items.length === 0) return null;
  return {
    '@context': 'https://schema.org',
    '@type': 'ItemList',
    name: 'Оформление заказа',
    itemListElement: cartStore.items.map((item, index) => ({
      '@type': 'Product',
      position: index + 1,
      name: item.title,
      image: item.image,
      offers: {
        '@type': 'Offer',
        price: item.price,
        priceCurrency: 'RUB',
        availability: 'https://schema.org/InStock',
      },
    })),
  };
});

useHead(
  computed(() => ({
    title: 'Оформление заказа - AgatCeramic',
    meta: [
      {
        name: 'description',
        content: 'Оформите заказ в интернет-магазине AgatCeramic',
      },
      {
        name: 'keywords',
        content: 'оформление заказа, заказ, AgatCeramic',
      },
      {
        name: 'robots',
        content: 'noindex, nofollow',
      },
    ],
    link: [
      {
        rel: 'canonical',
        href: `${config.public.siteUrl}/checkout`,
      },
    ],
    script: structuredData.value
      ? [
          {
            type: 'application/ld+json',
            children: JSON.stringify(structuredData.value),
          },
        ]
      : [],
  }))
);
</script>

<style scoped lang="scss">
.empty-text-contant {
  i {
    font-size: 60px;
    color: $black;
  }

  h3 {
    font-size: 20px;
    color: $black;
    margin: 20px 0 20px;
    font-weight: 500;

    @media #{$small-mobile} {
      font-size: 18px;
    }
  }

  a {
    &.empty-cart-btn {
      padding: 15px 25px;
      display: inline-block;
      background: $theme-color;
      color: $white;
      font-size: 14px;
      font-weight: 500;
      border-radius: 20px;
      transition: 0.3s;
      text-transform: uppercase;

      &:hover {
        background-color: $body-color;

        i {
          transform: translate(-5px, 0px);
        }
      }

      i {
        font-size: 14px !important;
        color: $white;
        transition: 0.3s;
        display: inline-block;
      }
    }
  }
}

.billing-info-wrap {
  & h3 {
    font-weight: 600;
    color: $black;
    margin: 0 0 30px;
    font-size: 24px;
    line-height: 16px;
  }

  .billing-info {
    input {
      background: transparent none repeat scroll 0 0;
      border: 1px solid $border-color;
      color: $body-color;
      font-size: 16px;
      padding-left: 20px;
      padding-right: 10px;
      width: 100%;
      outline: none;
      height: 45px;

      &:focus {
        border-color: $theme-color;
      }
    }
  }

  .billing-select {
    select {
      background: transparent none repeat scroll 0 0;
      border: 1px solid $border-color;
      color: $body-color;
      font-size: 16px;
      padding-left: 20px;
      padding-right: 10px;
      width: 100%;
      outline: none;
      height: 45px;
    }
  }

  .additional-info-wrap {
    margin-bottom: 30px;

    textarea {
      background: transparent none repeat scroll 0 0;
      border: 1px solid $border-color;
      color: $body-color;
      font-size: 16px;
      height: 140px;
      padding: 17px 20px;
      width: 100%;
      outline: none;

      &:focus {
        border-color: $theme-color;
      }
    }
  }
}

.your-order-area {
  & h3 {
    font-weight: 600;
    color: $black;
    margin: 0 0 30px;
    font-size: 24px;
    line-height: 16px;
  }

  .your-order-wrap {
    padding: 38px 45px 44px;
    background: $border-color;

    .your-order-product-info {
      .your-order-top {
        ul {
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
          -webkit-box-pack: justify;
          -ms-flex-pack: justify;
          justify-content: space-between;
          font-family: $heading-font;

          li {
            font-size: 16px;
            font-weight: 600;
            list-style: outside none none;
            color: $black;
          }
        }
      }

      .your-order-middle {
        border-top: 1px solid #dee0e4;
        margin-top: 29px;
        padding: 19px 0 18px;

        li {
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
          -webkit-box-pack: justify;
          -ms-flex-pack: justify;
          justify-content: space-between;
          margin: 0 0 10px;
        }
      }

      .your-order-total {
        border-top: 1px solid #dee0e4;
        padding-top: 17px;

        ul {
          -webkit-box-align: center;
          -ms-flex-align: center;
          align-items: center;
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
          -webkit-box-pack: justify;
          -ms-flex-pack: justify;
          justify-content: space-between;

          li {
            font-weight: 600;
            color: $theme-color;
            font-size: 16px;
            list-style: outside none none;
            font-family: $heading-font;

            &.order-total {
              font-weight: 600;
              color: $black;
              font-size: 18px;
            }
          }
        }
      }
    }

    @media #{$large-mobile} {
      padding: 38px 30px 44px;
    }
  }

  .Place-order {
    margin-top: 25px;

    .btn-change {
      background-color: $border-color;
      color: $black;
      display: block;
      font-weight: 600;
      letter-spacing: 1px;
      line-height: 1;
      padding: 18px 20px;
      text-align: center;
      text-transform: uppercase;
      border-radius: 0px;
      z-index: 9;

      &:hover {
        background-color: $theme-color;
        color: $white;
      }
    }

    .btn-order {
      background-color: $theme-color;
      color: $white;
      display: block;
      font-weight: 600;
      letter-spacing: 1px;
      line-height: 1;
      padding: 18px 20px;
      text-align: center;
      text-transform: uppercase;
      border-radius: 0px;
      z-index: 9;

      &:hover {
        background-color: $body-color;
      }
    }
  }

  .your-order-foot {
    & p {
      line-height: 25px;
    }

    & a {
      color: $body-color;
      text-decoration: underline;

      &:hover {
        color: $black;
      }
    }
  }
}
</style>
