<template>
  <ClientOnly>
    <main class="cart-main-area pb-100px pt-100px">
      <div class="container">
        <div class="row">
          <UiAppSpinner v-if="loading" text="Загрузка корзины..." />
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-12" v-else>
              <div class="table-content table-responsive cart-table-content">
                <table>
                  <thead>
                    <tr>
                      <th></th>
                      <th>Товар</th>
                      <th>Цена</th>
                      <th>Количество</th>
                      <th></th>
                      <th>Стоимость</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in cartStore.items" :key="item.id" :data-product-id="item.id">
                      <td class="product-thumbnail px-2">
                        <div style="aspect-ratio: 1 / 1; overflow: hidden;">
                          <NuxtLink :to="`/product/${item.slug}`">
                            <img :src="item.image" :alt="item.title" class="img-responsive" />
                          </NuxtLink>
                        </div>
                      </td>
                      <td class="product-name">
                        <NuxtLink :to="`/product/${item.slug}`" class="lh-1">
                          <p>{{ item.title }}{{ item.weight_kg ? `, ${item.weight_kg} кг` : '' }}</p>
                        </NuxtLink>
                      </td>
                      <td class="product-price-cart"><span class="amount">{{ formatPrice(item.price) }}</span></td>
                      <td class="product-quantity">
                        <div class="cart-plus-minus">
                          <div class="dec qtybutton" @click="updateQuantity(item.id, item.quantity - 1)"
                            :disabled="item.quantity <= 1">-</div>
                          <input class="cart-plus-minus-box" type="text" :value="item.quantity"
                            @input="updateQuantity(item.id, parseInt($event.target.value) || 1)" min="1" />
                          <div class="inc qtybutton" @click="updateQuantity(item.id, item.quantity + 1)">+</div>
                        </div>
                      </td>
                      <td>
                        {{ item.unit === 'шт' ? 'шт.' : item.unit === 'кв.м' ? 'м²' : item.unit }}
                      </td>
                      <td class="product-subtotal">{{ formatPrice(item.price * item.quantity) }}</td>
                      <td class="product-remove">
                        <a href="#" @click="removeItem(item.id)"><i class="fa fa-times"></i></a>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <th colspan="2">Общая стоимость:</th>
                      <th class="cart-total">{{ formatPrice(cartStore.total) }}</th>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="cart-shiping-update-wrapper justify-content-end">
                    <div class="cart-clear">
                      <NuxtLink to="/checkout">Оформить заказ</NuxtLink>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
const loading = ref(true);

onMounted(() => {
  // Имитируем загрузку данных
  setTimeout(() => {
    loading.value = false;
  }, 500);
});

const formatter = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
});

const formatPrice = (price) => {
  return formatter.format(price);;
};

const removeItem = (id) => {
  cartStore.removeFromCart(id);
};

const updateQuantity = (id, quantity) => {
  cartStore.updateQuantity(id, Math.max(1, quantity));
};

const structuredData = computed(() => {
  if (cartStore.items.length === 0) return null
  return {
    '@context': 'https://schema.org',
    '@type': 'ItemList',
    name: 'Корзина покупок',
    itemListElement: cartStore.items.map((item, index) => ({
      '@type': 'Product',
      position: index + 1,
      name: item.title,
      image: item.image,
      offers: {
        '@type': 'Offer',
        price: item.price,
        priceCurrency: 'RUB',
        availability: 'https://schema.org/InStock'
      }
    }))
  }
})

useHead(computed(() => ({
  title: 'Корзина - AgatCeramic',
  meta: [
    {
      name: 'description',
      content: 'Ваша корзина покупок в интернет-магазине AgatCeramic'
    },
    {
      name: 'keywords',
      content: 'корзина, покупки, AgatCeramic'
    },
    {
      name: 'robots',
      content: 'noindex, nofollow'
    }
  ],
  link: [
    {
      rel: 'canonical',
      href: `${config.public.siteUrl}/cart`
    }
  ],
  script: structuredData.value ? [{ type: 'application/ld+json', children: JSON.stringify(structuredData.value) }] : []
})))
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

.cart-main-area {
  .table-content {
    table {
      border: 1px solid $border-color;
      width: 100%;

      thead,
      tfoot {
        &>tr {
          background-color: $border-color;
          border: 1px solid $border-color;

          &>th {
            border-top: medium none;
            color: $black;
            font-size: 16px;
            font-weight: 600;
            padding: 21px 45px 22px;
            text-align: center;
            text-transform: uppercase;
            vertical-align: middle;
            white-space: nowrap;
          }
        }
      }

      tbody {
        &>tr {
          border-bottom: 1px solid $border-color;

          td {
            font-size: 16px;
            padding: 30px 0;
            text-align: center;

            &.product-thumbnail {
              width: 150px;
            }

            &.product-name {
              text-align: left;
              width: 435px;

              a {
                color: $body-color;
                font-size: 16px;
                font-weight: 400;
              }
            }

            &.product-price-cart {
              width: 435px;
            }

            &.product-quantity {
              width: 435px;

              .cart-plus-minus {
                display: inline-block;
                height: 40px;
                padding: 0;
                position: relative;
                width: 110px;

                .dec {
                  &.qtybutton {
                    border-right: 1px solid $border-color;
                    height: 40px;
                    left: 0;
                    padding-top: 6px;
                    top: 0;
                  }
                }

                .inc {
                  &.qtybutton {
                    border-left: 1px solid $border-color;
                    height: 40px;
                    padding-top: 6px;
                    right: 0;
                    top: 0;
                  }
                }

                .qtybutton {
                  color: $body-color;
                  cursor: pointer;
                  float: inherit;
                  font-size: 16px;
                  margin: 0;
                  position: absolute;
                  -webkit-transition: all 0.3s ease 0s;
                  -o-transition: all 0.3s ease 0s;
                  transition: all 0.3s ease 0s;
                  width: 20px;
                  text-align: center;
                }

                input {
                  &.cart-plus-minus-box {
                    color: $body-color;
                    float: left;
                    font-size: 16px;
                    height: 40px;
                    margin: 0;
                    width: 110px;
                    background: transparent none repeat scroll 0 0;
                    border: 1px solid $border-color;
                    padding: 0;
                    text-align: center;
                  }
                }
              }
            }

            &.product-remove {
              width: 100px;

              a {
                color: $body-color;
                font-size: 16px;
                margin: 0 10px;

                &:hover {
                  color: $red;
                }
              }
            }
          }
        }
      }
    }
  }

  .cart-shiping-update-wrapper {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    padding: 30px 0 60px;

    @media #{$large-mobile} {
      display: block;
      padding: 30px 0 15px;
    }
  }
}

.cart-shiping-update-wrapper .cart-shiping-update>a,
.cart-shiping-update-wrapper .cart-clear>button,
.cart-shiping-update-wrapper .cart-clear>a {
  font-size: 14px;
  font-weight: 600;
  line-height: 1;
  padding: 18px 63px 17px;
  text-transform: uppercase;

  @media #{$tablet-device} {
    padding: 18px 25px;
  }

  @media #{$large-mobile} {
    padding: 18px 25px;
    margin: 0 0 15px;
  }
}

.cart-shiping-update-wrapper .cart-clear>a {
  background-color: $theme-color;
  color: $white;
}

.cart-shiping-update-wrapper .cart-clear>a:hover {
  background-color: $body-color;
  color: $white;
}
</style>