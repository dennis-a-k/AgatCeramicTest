<template>
  <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 col-xs-6 mb-30px">
    <div class="product card h-100" style="border: none">
      <span class="badges" v-if="product.sale">
        <span class="new">Распродажа</span>
      </span>

      <div class="thumb d-flex justify-content-center align-items-center" style="aspect-ratio: 1 / 1;">
        <a href="/" class="image">
          <NuxtImg :src="productImage" :alt="product.title" />
          <NuxtImg :src="productImage" :alt="product.title" class="hover-image" />
        </a>
      </div>
      <div class="content text-center">
        <span class="category"><a :href="product.href">{{ product.category }}</a></span>

        <span class="price">
          <span class="new">{{ formattedPrice }}</span>
        </span>

        <h5 class="title">
          <a href="/">{{ truncatedTitle }}</a>
        </h5>
      </div>
      <div class="actions">
        <button class="action add-cart" :data-product-id="product.id">
          <i class="pe-7s-cart"></i>
        </button>

        <button class="action quickview" data-link-action="quickview" title="Посмотреть" :data-id="product.id"
          data-bs-toggle="modal" data-bs-target="#modalProduct">
          <i class="pe-7s-look"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const formatter = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
});

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  formatter: {
    type: Function,
    required: true
  }
});

const productImage = computed(() => {
  return (props.product.imgSrc && props.product.imgSrc.trim() !== '')
    ? props.product.imgSrc
    : '/images/stock/stock-image.png';
});

const formattedPrice = computed(() => {
  return formatter.format(props.product.price);
});

const truncatedTitle = computed(() => {
  return props.product.title.slice(0, 80);
});
</script>