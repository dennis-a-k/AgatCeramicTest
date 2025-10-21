<template>
  <main class="product-details-area pt-100px pb-100px">
    <div class="container">
      <UiAppSpinner v-if="pending" text="Загрузка товара..." />
      <div v-else-if="showErrorMessage" class="error text-center mt-5">
        <h2>
          Произошла ошибка при загрузке товара.<br />Попробуйте перезагрузить
          страницу.
        </h2>
      </div>
      <div v-else-if="!productData" class="text-center mt-5">
        <h2>Товар не найден</h2>
      </div>
      <div v-else class="row">
        <div class="col-lg-5 col-sm-12 col-xs-12 mb-lm-30px mb-md-30px mb-sm-30px">
          <ClientOnly>
            <SlidersAppProductPageSlider :images="productImages" />
          </ClientOnly>
        </div>
        <div class="col-lg-7 col-sm-12 col-xs-12" data-aos="fade-up" data-aos-delay="200">
          <div class="product-details-content quickview-content ml-25px">
            <span v-if="productData.is_sale" class="badges">
              <span class="sale">Распродажа</span>
            </span>
            <h1 class="h3">
              {{ productData.name }}{{ weight ? `, ${weight}кг` : '' }}
            </h1>
            <div class="pricing-meta">
              <ul class="d-flex">
                <li class="new-price">
                  <span class="h1">{{ formattedPrice }}</span>
                </li>
              </ul>
            </div>
            <div v-for="meta in productMeta" :key="meta.label"
              class="pro-details-categories-info pro-details-same-style d-flex m-0">
              <span>{{ meta.label }}</span>
              <ul class="d-flex">
                <li>
                  <NuxtLink v-if="meta.link" :to="meta.link">{{
                    meta.value
                  }}</NuxtLink>
                  <span v-else>{{ meta.value }}</span>
                </li>
              </ul>
            </div>
            <div class="pro-details-quality">
              <div class="cart-plus-minus">
                <div class="dec qtybutton" @click="decrement">-</div>
                <input class="cart-plus-minus-box" type="text" name="qtybutton" v-model="quantity" />
                <div class="inc qtybutton" @click="increment">+</div>
              </div>
              <p v-if="productData.unit">
                {{
                  productData.unit === 'шт'
                    ? 'шт.'
                    : productData.unit === 'кв.м'
                      ? 'м²'
                      : productData.unit
                }}
              </p>
              <div class="pro-details-cart">
                <button class="add-cart" :data-product-id="productData.id" @click="addToCartProduct">
                  В корзину
                </button>
              </div>
            </div>
          </div>

          <div class="description-review-wrapper">
            <div class="description-review-topbar nav">
              <button data-bs-toggle="tab" data-bs-target="#des-details2">
                Характеристики
              </button>
              <button class="active" data-bs-toggle="tab" data-bs-target="#des-details1">
                Описание
              </button>
            </div>
            <div class="tab-content description-review-bottom">
              <div id="des-details2" class="tab-pane">
                <div class="product-anotherinfo-wrapper">
                  <div class="col-12">
                    <table class="table table-striped">
                      <tbody>
                        <tr v-if="productData.color">
                          <td>Цвет</td>
                          <td>
                            <span>{{ productData.color.name }}</span>
                          </td>
                        </tr>
                        <tr v-if="productData.texture">
                          <td>Поверхность</td>
                          <td>
                            <span>{{ productData.texture }}</span>
                          </td>
                        </tr>
                        <tr v-if="productData.pattern">
                          <td>Узор</td>
                          <td>
                            <span>{{ productData.pattern }}</span>
                          </td>
                        </tr>
                        <tr v-for="attr in productData.attribute_values" :key="attr.id">
                          <td>{{ attr.attribute.name }}</td>
                          <td>
                            <span v-if="attr.attribute.type === 'boolean'">{{
                              attr.boolean_value ? 'Да' : 'Нет'
                            }}</span>
                            <span v-else-if="attr.attribute.type === 'number'">{{ formattedNumberAttribute(attr)
                              }}</span>
                            <span v-else-if="
                              attr.attribute.type === 'string' ||
                              attr.attribute.type === 'text'
                            ">{{ formattedStringAttribute(attr) }}</span>
                            <span v-else>{{
                              attr.string_value || attr.text_value
                            }}</span>
                          </td>
                        </tr>
                        <tr v-if="productData.country">
                          <td>Страна</td>
                          <td>
                            <span>{{ productData.country }}</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <ul v-if="productData.brand && productData.brand.image && productData.brand.image.trim()" class="product-anotherinfo-img-list">
                      <li class="product-anotherinfo-img">
                        <NuxtLink :to="`/brand/${productData.brand.slug}`">
                          <img :src="`${productData.brand.image.startsWith('http') ? productData.brand.image : `${config.public.apiBase}/storage/${productData.brand.image}`}`"
                            :alt="`${productData.brand.name}`">
                        </NuxtLink>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div id="des-details1" class="tab-pane active">
                <div class="product-description-wrapper">
                  <p>{{ productData.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useRuntimeConfig } from '#imports';
import { useCartStore } from '~/stores/useCartStore';

const cartStore = useCartStore();
const { $toast } = useNuxtApp();

const route = useRoute();
const slug = route.params.slug;
const config = useRuntimeConfig();
const siteUrl = config.public.siteUrl || 'https://agatceramic.ru';

const quantity = ref(1);

const getImageUrl = (image) => {
  if (image.image_path.startsWith('http')) {
    return image.image_path;
  }
  return `${config.public.apiBase}/storage/${image.image_path}`;
};

const productImages = computed(() => {
  if (productData.value?.images && productData.value.images.length > 0) {
    return productData.value.images.map((img, index) => ({
      url: getImageUrl(img),
      alt: `Фото ${index + 1}`,
    }));
  }
  // Fallback to single image if no images array
  return productData.value?.imgSrc
    ? [
      {
        url: productData.value.imgSrc,
        alt: productData.value.name || 'Изображение продукта',
      },
    ]
    : [
      {
        url: '/images/stock/stock-image.png',
        alt: 'Изображение продукта',
      },
    ];
});

const {
  data: productData,
  pending,
  error,
  execute,
} = useAsyncData(
  `product-${slug}`,
  () => $fetch(`${config.public.apiBase}/api/products/slug/${slug}`),
  {
    immediate: true,
  }
);

const increment = () => {
  quantity.value += 1;
};

const decrement = () => {
  if (quantity.value > 1) {
    quantity.value -= 1;
  }
};

const addToCartProduct = () => {
  if (!productData.value) return;

  cartStore.addToCart({
    id: productData.value.id,
    slug: productData.value.slug,
    title: productData.value.name,
    weight_kg: weight.value,
    quantity: quantity.value,
    price: productData.value.price,
    unit: productData.value.unit,
    image: productImages.value[0]?.url || '/images/stock/stock-image.png',
  });

  $toast.success('Товар добавлен в корзину!');
};

const showErrorMessage = computed(() => {
  return error.value && !pending.value;
});

const formattedPrice = computed(() => {
  const formatter = new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
  });
  return formatter.format(productData.value?.price || 0);
});

const weight = computed(() => {
  const attr = productData.value?.attribute_values?.find(
    (a) => a.attribute.slug === 'ves'
  );
  if (attr && attr.number_value !== null) {
    const num = parseFloat(attr.number_value);
    return isNaN(num) ? attr.number_value : num.toLocaleString('en-US');
  }
  return null;
});

const formattedNumberAttribute = computed(() => (attr) => {
  const formatter = new Intl.NumberFormat('ru-RU', {
    maximumFractionDigits: 2,
  });
  if (attr.attribute.slug === 'ves') {
    return `${formatter.format(attr.number_value)} кг`;
  } else if (attr.attribute.slug === 'tolshhina') {
    return `${formatter.format(attr.number_value)} мм`;
  }
  return formatter.format(attr.number_value);
});

const formattedStringAttribute = computed(() => (attr) => {
  if (
    attr.attribute.slug === 'sirina-sva' ||
    attr.attribute.slug === 'razmery'
  ) {
    return `${attr.string_value || attr.text_value} мм`;
  }
  return attr.string_value || attr.text_value;
});

const productMeta = computed(() => {
  if (!productData.value) return [];
  const meta = [];
  if (productData.value.article) {
    meta.push({ label: 'Артикул:', value: productData.value.article });
  }
  if (productData.value.product_code) {
    meta.push({ label: 'Код товара:', value: productData.value.product_code });
  }
  if (productData.value.category) {
    meta.push({
      label: 'Категория:',
      value: productData.value.category.name,
      link: `/category/${productData.value.category.slug}`,
    });
  }
  if (productData.value.brand) {
    meta.push({
      label: 'Производитель:',
      value: productData.value.brand.name,
      link: `/brand/${productData.value.brand.slug}`,
    });
  }
  if (productData.value.collection) {
    meta.push({ label: 'Коллекция:', value: productData.value.collection });
  }
  return meta;
});

const structuredData = computed(() => {
  if (!productData.value) return null;
  return {
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: productData.value.name,
    description: productData.value.description,
    image: productImages.value.map((img) => img.url),
    sku: productData.value.article || productData.value.product_code,
    offers: {
      '@type': 'Offer',
      price: productData.value.price,
      priceCurrency: 'RUB',
      availability: productData.value.in_stock
        ? 'https://schema.org/InStock'
        : 'https://schema.org/OutOfStock',
    },
    brand: productData.value.brand
      ? {
        '@type': 'Brand',
        name: productData.value.brand.name,
      }
      : undefined,
    category: productData.value.category
      ? productData.value.category.name
      : undefined,
    manufacturer: productData.value.brand
      ? productData.value.brand.name
      : undefined,
  };
});

useHead(
  computed(() => ({
    title: productData.value?.name
      ? `${productData.value.name} купить в Москве по низкой цене с доставкой в AgatCeramic`
      : 'Товар - AgatCeramic',
    meta: [
      {
        name: 'description',
        content:
          productData.value?.description ||
          'Купить товар в интернет-магазине AgatCeramic',
      },
      {
        name: 'keywords',
        content: productData.value?.name
          ? `${productData.value.name}, керамическая плитка, сантехника`
          : 'керамическая плитка, сантехника',
      },
      {
        property: 'og:image',
        content: productImages.value[0]?.url || '/images/stock/stock-image.png',
      },
      {
        property: 'og:title',
        content:
          'AgatCeramic - Интернет-магазин плитки, керамогранита и сантехники',
      },
      {
        property: 'og:description',
        content:
          productData.value?.description ||
          'Купить товар в интернет-магазине AgatCeramic',
      },
      {
        property: 'og:url',
        content: `${siteUrl}/product/${slug}`,
      },
      {
        name: 'twitter:image',
        content: productImages.value[0]?.url || '/images/stock/stock-image.png',
      },
    ],
    link: [
      {
        rel: 'canonical',
        href: `${siteUrl}/product/${slug}`,
      },
    ],
    script: [
      ...(structuredData.value
        ? [
          {
            type: 'application/ld+json',
            children: JSON.stringify(structuredData.value),
          },
        ]
        : []),
      {
        type: 'application/ld+json',
        children: JSON.stringify({
          '@context': 'https://schema.org',
          '@type': 'Organization',
          name: 'AgatCeramic',
          url: siteUrl,
          logo: `${siteUrl}/images/stock/logo.png`,
          description: 'Интернет-магазин плитки, керамогранита и сантехники',
          email: 'zakaz@agatceramic.ru',
          contactPoint: {
            '@type': 'ContactPoint',
            telephone: '+7 (999) 999-99-99',
            contactType: 'customer service',
          },
        }),
      },
    ],
  }))
);
</script>

<style scoped lang="scss">
.badges {
  display: block;
  width: fit-content;
  margin-bottom: 15px;
}

.sale {
  font-size: 18px;
  line-height: 1.75;
  display: block;
  padding: 0 12px;
  text-align: center;
  text-transform: uppercase;
  border-radius: 5px;
  color: $white;
  font-weight: 600;
  background-color: $red;
}

.product-details-content .pricing-meta ul li span {
  color: $body-color;
}

.product-details-content {
  &.ml-25px {
    margin-left: 25px;

    @media #{$tablet-device, $large-mobile} {
      margin-left: 0px;
      margin-top: 50px;
    }
  }

  h1 {
    font-size: 36px;
    line-height: 1;
    font-weight: 400;
    margin: 0 0 18px 0;

    @media #{$large-mobile} {
      font-size: 28px;
    }

    @media #{$extra-small-mobile} {
      font-size: 26px;
    }
  }

  .pricing-meta {
    ul {
      li+li {
        margin-left: 10px;
      }

      li {
        font-size: 36px;
        color: $black;
        line-height: 30px;
        font-weight: 500;
        margin-bottom: 20px;

        @media #{$large-mobile} {
          font-size: 28px;
        }

        @media #{$extra-small-mobile} {
          font-size: 26px;
        }
      }
    }
  }

  .pro-details-quality {
    display: inline-flex;
    margin: 30px 0;
    width: 100%;

    .cart-plus-minus {
      border: 1px solid $body-color;
      display: inline-block;
      height: 50px;
      overflow: hidden;
      padding: 0;
      position: relative;
      width: 80px;
      background: $white;
      border-radius: 5px;
      margin-right: 5px;

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
          height: 50px;
          margin: 0;
          padding: 0;
          text-align: center;
          width: 80px;
          outline: none;
        }
      }

      .inc {
        &.qtybutton {
          height: 50px;
          padding-top: 14px;
          right: 0;
          top: 0;
        }
      }

      .dec {
        &.qtybutton {
          height: 50px;
          left: 0;
          padding-top: 14px;
          top: 0;
        }
      }
    }
  }

  .pro-details-cart {
    & .add-cart {
      position: relative;
      padding: 0 35px;
      height: 50px;
      font-size: 18px;
      font-weight: 600;
      border: none;
      border-radius: 5px;
      box-shadow: none;

      display: inline-block;
      margin-left: 20px;
      background: $theme-color;
      color: $white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      letter-spacing: 1px;

      &:hover {
        background-color: $body-color;
      }

      @media #{$desktop-device} {
        padding: 0 15px;
      }

      @media #{$large-mobile} {
        padding: 0 12px;
        letter-spacing: 0px;
      }

      @media #{$extra-small-mobile} {
        padding: 0 10px;
        font-size: 12px;
        margin-left: 5px;
      }
    }
  }

  p {
    font-size: 24px;
    color: $body-color;
    margin: 0;
    display: flex;
    align-items: center;
  }

  .pro-details-same-style {
    span {
      font-weight: 500;
      color: $theme-color;
      display: inline-block;
      margin-right: 5px;
    }

    a {
      font-weight: 400;
      color: $link-secondary-color;
      margin-left: 3px;

      &:hover {
        color: $theme-color;
      }
    }

    &.pro-details-categories-info {
      color: $link-secondary-color;
      margin: 10px 0;
    }
  }
}

.description-review-wrapper {
  margin-top: 25px;
  margin-left: 25px;

  @media #{$tablet-device, $large-mobile} {
    margin-left: 0px;
  }
}

.description-review-topbar {
  &.nav {
    border-bottom: none;
    position: relative;
    margin-bottom: 0;
    margin: auto;
    text-align: center;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    border-bottom: 2px solid $border-color;
    background-color: $white;

    & button {
      background: transparent;
      border: 0;
      text-transform: capitalize;
      line-height: 24px;
      color: $black;
      margin-right: 40px;
      font-size: 18px;
      font-weight: 600;
      position: relative;
      transition: all 300ms linear;
      display: inline-block;
      display: flex;
      justify-content: center;
      align-items: center;
      padding-bottom: 15px;

      &::before {
        position: absolute;
        top: auto;
        left: 0;
        bottom: -2px;
        width: 0%;
        height: 2px;
        background-color: $border-color;
        content: '';
        transition: $baseTransition;
      }

      &:hover {
        color: $theme-color;

        &::before {
          width: 100%;
          background-color: $theme-color;
        }
      }

      &.active {
        color: $theme-color;

        &::before {
          width: 100%;
          background-color: $theme-color;
        }
      }

      @media #{$desktop-device} {
        margin-right: 30px;
      }

      @media #{$large-mobile} {
        margin-right: 10px;
      }

      @media #{$small-mobile} {
        margin-right: 10px;
        font-size: 16px;
      }

      @media #{$extra-small-mobile} {
        margin-right: 4px;
        font-size: 14px;
        padding-left: 0;
      }
    }
  }
}

.description-review-bottom {
  overflow: hidden;
  font-size: 16px;
  background: $white;
  padding: 40px 0px 0px 0px;

  .product-description-wrapper {
    text-align: left;

    p {
      margin: 0px;
      font-size: 16px;
      line-height: 28px;
      color: $link-secondary-color;
      font-weight: 300;
    }
  }

  .product-anotherinfo-wrapper {
    span {
      color: $body-color;
      display: inline-block;
      font-weight: 500;
      margin: 0 26px 0 0;
      min-width: 85px;
    }
  }
}

.product-details-img {
  &.product-details-tab {
    &.product-details-tab-2 {
      &.product-details-tab-3 {
        flex-direction: row-reverse;
      }

      & .zoom-thumbs-2 {
        flex: 0 0 15%;
        width: 15%;
      }

      & .zoom-top-2 {
        flex: 0 0 85%;
        width: 85%;
      }
    }
  }
}

.product-anotherinfo-img img {
  max-width: 110px;
  margin-top: 30px;
}
</style>
