<template>
  <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 col-xs-6 mb-30px">
    <div class="product card h-100" style="border: none">
      <span class="badges" v-if="product.is_sale">
        <span class="new">Распродажа</span>
      </span>

      <div class="thumb d-flex justify-content-center align-items-center" style="aspect-ratio: 1 / 1">
        <NuxtLink :to="`/product/${product.slug}`" class="image">
          <NuxtImg :src="productImage" :alt="product.name" />
          <NuxtImg :src="productImage" :alt="product.name" class="hover-image" />
        </NuxtLink>
      </div>
      <div class="content text-center">
        <span class="category">
          <NuxtLink :to="`/category/${product.category.slug}`">{{
            product.category.name
          }}</NuxtLink>
        </span>

        <span class="price">
          <span class="new">{{ formattedPrice }}</span>
        </span>

        <h5 class="title">
          <NuxtLink :to="`/product/${product.slug}`">{{ truncatedTitle }}{{ weight ? `, ${weight} кг` : '' }}</NuxtLink>
        </h5>
      </div>
      <div class="actions">
        <button class="action add-cart" :data-product-id="product.id" @click="addToCart" title="В корзину">
          <i class="pe-7s-cart"></i>
        </button>

        <button class="action quickview" title="Посмотреть" :data-id="product.id" @click="openQuickView">
          <i class="pe-7s-look"></i>
        </button>
      </div>
    </div>
  </div>

  <ModalsAppModalQuickView ref="quickViewModal" />
</template>

<script setup>
import { computed, ref } from 'vue';
import { useCartStore } from '~/stores/useCartStore';
import { useRuntimeConfig } from '#imports';

const cartStore = useCartStore();
const { $toast } = useNuxtApp();
const quickViewModal = ref(null);
const config = useRuntimeConfig();

const formatter = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
});

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const weight = computed(() => {
  const attr = props.product.attribute_values?.find(
    (a) => a.attribute.slug === 'ves'
  );
  if (attr && attr.number_value !== null) {
    const num = parseFloat(attr.number_value);
    return isNaN(num) ? attr.number_value : num.toLocaleString('en-US');
  }
  return null;
});

const getImageUrl = (image) => {
  if (image.image_path.startsWith('http')) {
    return image.image_path;
  }
  return `${config.public.apiBase}/storage/${image.image_path}`;
};

const productImage = computed(() => {
  if (props.product.images && props.product.images.length > 0) {
    return getImageUrl(props.product.images[0]);
  }
  return props.product.imgSrc && props.product.imgSrc.trim() !== ''
    ? props.product.imgSrc
    : '/images/stock/stock-image.png';
});

const formattedPrice = computed(() => {
  return formatter.format(props.product.price);
});

const truncatedTitle = computed(() => {
  return props.product.name.slice(0, 80);
});

const addToCart = () => {
  const weightAttribute = props.product.attribute_values?.find(
    (a) => a.attribute.slug === 'ves'
  );

  cartStore.addToCart({
    id: props.product.id,
    slug: props.product.slug,
    title: props.product.name,
    weight_kg: weightAttribute?.number_value || props.product.weight_kg,
    quantity: 1,
    price: props.product.price,
    unit: props.product.unit,
    image: productImage.value,
  });

  $toast.success('Товар добавлен в корзину!');
};

const openQuickView = () => {
  quickViewModal.value.openModal(props.product);
};
</script>

<style scoped lang="scss">
.product {
  position: relative;
  background: $white;
  padding: 5px;
  margin-bottom: 0;
  transition: $baseTransition;
  z-index: 0;

  & .badges {
    position: absolute;
    z-index: 8;
    top: 15px;
    left: 15px;
    z-index: 1;
    display: flex;
    flex-direction: column;
    transition: $baseTransition;

    & span {
      font-size: 14px;
      line-height: 1.75;
      display: block;
      padding: 0 12px;
      text-align: center;
      text-transform: uppercase;
      border-radius: 5px;
      color: $white;
      font-weight: 600;

      &.new {
        background-color: $red;
      }
    }
  }

  & .actions {
    position: absolute;
    display: flex;
    flex-direction: row;
    opacity: 0;
    visibility: hidden;
    transition: $baseTransition;
    top: calc(100% - 15px);
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    justify-content: center;
    height: 60px;
    background: $white;
    z-index: 11;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

    & .action {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      width: 40px;
      height: 40px;
      transition: $baseTransition;
      text-decoration: none;
      color: $white;
      border-radius: 5px;
      background-color: $theme-color;
      font-size: 24px;

      &+.action {
        margin-left: 10px;
      }

      &:hover {
        color: $white;
        background: $body-color;
      }
    }
  }

  & .thumb {
    position: relative;
    overflow: hidden;

    & .image {
      position: relative;
      display: block;
      overflow: hidden;
      max-width: 435px;

      & img {
        z-index: 1;
        max-width: 100%;
        transition: $baseTransition;
        width: 100%;

        &.hover-image {
          position: absolute;
          z-index: 2;
          top: 0;
          left: 0;
          opacity: 0;
        }
      }
    }
  }

  & .content {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    transition: $baseTransition;
    align-items: center;
    line-height: 1;
    background-color: $white;
    padding: 0 0 30px;
    max-width: 100%;

    .category {
      a {
        font-size: 14px;
        color: $logo-secondary-color;
        font-weight: 500;

        &:hover {
          color: $theme-color;
        }
      }
    }

    & .title {
      font-size: 16px;
      margin: 10px 0 20px;
      font-family: $Poppins;

      & a {
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        word-wrap: break-word;
        text-decoration: none;
        color: $black;
        font-size: 16px;
        font-weight: 600;
      }
    }

    & .price {
      font-size: 20px;
      font-family: $Poppins;
      line-height: 1;
      font-weight: 500;
      color: $theme-color;
      margin-top: 10px;
    }
  }

  &:hover {
    transform: scaleY(1.05);
    z-index: 1;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);

    & .thumb {
      & .image {
        & img {
          transform: scale(1.1) rotate(0deg);

          &:not(:last-child) {
            opacity: 0;
          }

          &.hover-image {
            opacity: 1;
          }
        }
      }
    }

    & .actions {
      opacity: 1;
      visibility: visible;
    }
  }
}

@media #{$small-mobile} {
  .col-xs-6 {
    width: 50%;
  }
}

@media #{$extra-small-mobile} {
  .col-xs-6 {
    width: 100%;
  }

  .product {
    & .content {
      & .title {
        & a {
          font-size: 16px;
        }
      }
    }
  }
}
</style>
