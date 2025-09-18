<template>
    <section class="brand-area pt-100px pb-100px">
        <div class="container">
            <h2 class="text-center d-none my-5">Бренды</h2>
            <Swiper
                class="brand-slider swiper-container"
                :modules="[Autoplay]"
                :slidesPerView="4"
                :speed="1500"
                :loop="true"
                :autoplay="{
                    delay: 2000,
                    disableOnInteraction: false,
                }"
                :breakpoints="{
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
                }"
            >
                <SwiperSlide class="swiper-slide brand-slider-item text-center px-2" v-for="(img, index) in brandsImg"
                    :key="index">
                    <a href="/">
                        <NuxtImg :src="img.image" class="img-fluid" :alt="img.image" />
                    </a>
                </SwiperSlide>
            </Swiper>
        </div>
    </section>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay } from 'swiper/modules';

const brandsImg = [
    {
        image: '/images/brands/axor_brand.png',
    },
    {
        image: '/images/brands/emac_brand.png',
    },
    {
        image: '/images/brands/geberit_brand.png',
    },
    {
        image: '/images/brands/hansgrohe_brand.png',
    },
    {
        image: '/images/brands/kaldewei_brand.png',
    },
    {
        image: '/images/brands/mosplitka_brand.png',
    },
    {
        image: '/images/brands/ravak_brand.png',
    },
    {
        image: '/images/brands/sanita_brand.png',
    },
    {
        image: '/images/brands/tarkett_brand.png',
    },
    {
        image: '/images/brands/unitile_brand.png',
    },
    {
        image: '/images/brands/akvaton_brand.png',
    },
    {
        image: '/images/brands/alcora_brand.png',
    },
    {
        image: '/images/brands/aquatek_brand.png',
    },
];
</script>