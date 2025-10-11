<template>
  <div class="product-swiper-container">
    <!-- Основной слайдер -->
    <div class="swiper zoom-top" ref="mainSwiper">
      <div class="swiper-wrapper">
        <div
          v-for="(image, index) in images"
          :key="index"
          class="swiper-slide d-flex"
        >
          <img
            class="img-responsive m-auto align-items-center"
            :src="image.url"
            :alt="image.alt"
          />
          <div class="image-overlay">
            <a
              class="venobox full-preview"
              data-gall="myGallery"
              :href="image.url"
            >
              <i class="fa fa-arrows-alt" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Мини-слайдер для навигации -->
    <div
      v-if="images.length > 1"
      class="swiper-container mt-20px zoom-thumbs slider-nav-style-1 small-nav"
      ref="thumbsSwiper"
    >
      <div class="swiper-wrapper">
        <div
          v-for="(image, index) in images"
          :key="index"
          class="swiper-slide d-flex"
        >
          <img
            class="img-responsive m-auto align-items-center"
            :src="image.url"
            :alt="image.alt"
          />
        </div>
      </div>

      <div class="swiper-buttons">
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import VenoBox from 'venobox';

const props = defineProps({
  images: {
    type: Array,
    default: () => [],
    validator: (value) => {
      return value.every((img) => img.url && img.alt);
    },
  },
});

const mainSwiper = ref(null);
const thumbsSwiper = ref(null);
let mainSwiperInstance = null;
let thumbsSwiperInstance = null;

const initSwipers = () => {
  // Мини-слайдер
  if (props.images.length > 1) {
    thumbsSwiperInstance = new Swiper(thumbsSwiper.value, {
      spaceBetween: 10,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  }

  // Основной слайдер
  mainSwiperInstance = new Swiper(mainSwiper.value, {
    spaceBetween: 10,
    thumbs:
      props.images.length > 1
        ? {
            swiper: thumbsSwiperInstance,
          }
        : {},
  });
};

onMounted(() => {
  initSwipers();
  // Initialize VenoBox for this component
  new VenoBox({
    selector: '.venobox',
    galleries: true,
  });
});

onBeforeUnmount(() => {
  if (mainSwiperInstance) {
    mainSwiperInstance.destroy();
  }
  if (thumbsSwiperInstance) {
    thumbsSwiperInstance.destroy();
  }
});
</script>

<style scoped lang="scss">
/* Основные стили для слайдеров */
.product-swiper-container {
  position: relative;
}

.zoom-top .swiper-wrapper .swiper-slide {
  max-width: 550px;
  height: 550px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.zoom-top .swiper-slide img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.zoom-thumbs {
  overflow: hidden;
}

.zoom-thumbs .swiper-wrapper .swiper-slide {
  height: 144px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.zoom-thumbs .swiper-slide img {
  width: auto;
  height: 100%;
  object-fit: contain;
  border: 2px solid $border-color;
  border-radius: 10px;
  transition: border-color 0.3s;
}

.zoom-thumbs .swiper-slide:hover img,
.zoom-thumbs .swiper-slide-thumb-active img {
  border-color: $theme-color;
}

.slider-nav-style-1 .swiper-buttons .swiper-button-next,
.slider-nav-style-1 .swiper-buttons .swiper-button-prev {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 9;
  width: 30px;
  height: 30px;
  line-height: 30px;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s;
  margin: auto;
  border-radius: 5px;
  text-align: center;
  box-shadow: 0 3px 25.5px 4.5px rgba(0, 0, 0, 0.06);
  color: $white;
  background-color: $link-secondary-color;
  cursor: pointer;
}

.slider-nav-style-1 .swiper-buttons .swiper-button-next:hover,
.slider-nav-style-1 .swiper-buttons .swiper-button-prev:hover {
  background-color: $theme-color;
  color: $white;
}

.slider-nav-style-1 .swiper-button-prev {
  left: -20px;
}

.slider-nav-style-1 .swiper-button-next {
  right: -20px;
}

.slider-nav-style-1:hover .swiper-button-next,
.slider-nav-style-1:hover .swiper-button-prev {
  opacity: 1;
  visibility: visible;
}

.slider-nav-style-1:hover .swiper-button-next {
  right: 0px;
}

.slider-nav-style-1:hover .swiper-button-prev {
  left: 0px;
}

/* Стили для overlay */
.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transform: scale(0);
  transition: all 0.3s ease;
  pointer-events: none;
}

.swiper-slide:hover .image-overlay {
  opacity: 1;
  transform: scale(1);
  pointer-events: auto;
}

/* Стили для venobox */
.venobox {
  background: $theme-color;
  color: $white;
  padding: 10px;
  border-radius: 50%;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  transition: background 0.3s;
}

.venobox:hover {
  background: $body-color;
}

/* Адаптивность */
@media (max-width: 768px) {
  .zoom-top .swiper-wrapper .swiper-slide {
    height: 370px;
  }

  .zoom-thumbs .swiper-wrapper .swiper-slide {
    height: 75px;
  }
}

@media (max-width: 576px) {
  .zoom-top .swiper-wrapper .swiper-slide {
    height: 440px;
  }

  .zoom-thumbs .swiper-wrapper .swiper-slide {
    height: 90px;
  }
}
</style>
