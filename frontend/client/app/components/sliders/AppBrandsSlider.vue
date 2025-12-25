<template>
    <section class="brand-area pt-100px pb-100px">
        <div class="container">
            <h2 class="text-center d-none my-5">Бренды</h2>
            <Swiper
                class="brand-slider swiper-container"
                :modules="[Autoplay]"
                :slidesPerView="4"
                :speed="1500"
                :loop="brandsWithImages.length >= 4"
                :autoplay="{
                    delay: 2000,
                    disableOnInteraction: false,
                }" :breakpoints="{
                    0: {
                        slidesPerView: 1,
                    },
                    480: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    992: {
                        slidesPerView: 3,
                    },
                    1200: {
                        slidesPerView: 4,
                    },
                }">
                <SwiperSlide class="swiper-slide brand-slider-item text-center px-2" v-for="(brand, index) in brandsWithImages"
                    :key="brand.id || index">
                    <a href="/">
                        <img :src="`${config.public.apiBase}/backend/storage/${brand.image}`" class="img-fluid" :alt="brand.name" />
                    </a>
                </SwiperSlide>
            </Swiper>
        </div>
    </section>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay } from 'swiper/modules';
import { useBrandStore } from '~/stores/useBrandStore';
import { useRuntimeConfig } from '#imports';

const brandStore = useBrandStore();
const config = useRuntimeConfig();
const siteUrl = config.public.siteUrl || 'https://agatceramic.ru';

// Получаем бренды при монтировании компонента
onMounted(async () => {
  await brandStore.fetchBrands();
});

// Вычисляем бренды с изображениями
const brandsWithImages = computed(() => {
  const filtered = brandStore.brands.filter(brand => brand.image);
  // Если брендов меньше 4, отключаем loop
  if (filtered.length < 4) {
    return filtered;
  }
  return filtered;
});
</script>

<style scoped lang="scss">
.brand-slider {
    padding: 70px 0;

    @media #{$desktop-device} {
        padding: 60px 0;
    }

    @media #{$tablet-device} {
        padding: 50px 0;
    }

    @media #{$large-mobile} {
        padding: 40px 0;
    }

    .brand-slider-item {
        & img {
            align-items: center;
            filter: gray;
            -webkit-filter: grayscale(1);
            -webkit-transition: all 300ms linear;
            -moz-transition: all 300ms linear;
            -ms-transition: all 300ms linear;
            -o-transition: all 300ms linear;
            transition: all 300ms linear;
            margin: auto;
            opacity: 0.8;
        }

        &:hover {
            & img {
                filter: none;
                -webkit-filter: grayscale(0);
                opacity: 1;
            }
        }
    }
}
</style>