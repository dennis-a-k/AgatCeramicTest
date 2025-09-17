<template>
    <section class="section slider-nav-style-1">
        <Swiper
            class="hero-slider"
            :modules="[Autoplay, EffectFade, Pagination, Navigation]"
            :effect="'fade'"
            :speed="2500"

            :pagination="{
                clickable: true,
                el: '.swiper-pagination',
            }"
            :navigation="{
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }"
        >
            <SwiperSlide
                v-for="(slide, index) in slides"
                :key="index"
                class="hero-slide-item slider-height bg-color1"
                :style="{ backgroundImage: 'url(' + slide.bgImage + ')' }"
            >
                <div class="container h-100">
                    <div class="row h-100">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 align-self-center sm-center-view">
                            <div class="hero-slide-content slider-animated-1">
                                <span class="category">
                                    {{ slide.category }}
                                </span>
                                <h2 class="title-1" v-html="slide.title"></h2>
                                <a :href="slide.buttonLink" class="btn btn-primary">
                                    {{ slide.buttonText }}
                                </a>
                            </div>
                        </div>
                        <div
                            class="col-xl-6 col-lg-6 col-md-6 col-sm-6 d-flex justify-content-center position-relative align-items-end">
                            <div class="show-case">
                                <div class="hero-slide-image">
                                    <NuxtImg :src="slide.image" :alt="slide.alt" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </SwiperSlide>

            <div class="swiper-pagination swiper-pagination-white"></div>
            <div class="swiper-buttons">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </Swiper>
    </section>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, EffectFade, Pagination, Navigation } from 'swiper/modules';
import 'swiper/css/effect-fade';

// Данные слайдов
const slides = [
  {
    bgImage: '/images/page-index/sliders/main-slider/bg/hero-bg-2.jpg',
    image: '/images/page-index/sliders/main-slider/inner-img/hero-1-2.jpg',
    alt: 'hero-1-2',
    category: 'Welcome To AgatCeramic',
    title: 'Your Home <br>Smart Devices & <br>Best Solution',
    buttonLink: 'https://ya.ru',
    buttonText: 'Shop All Devices'
  },
  {
    bgImage: '/images/page-index/sliders/main-slider/bg/hero-bg-1.jpeg',
    image: '/images/page-index/sliders/main-slider/inner-img/hero-1-1.jpg',
    alt: 'hero-1-1',
    category: 'Welcome To AgatCeramic',
    title: 'Your Home <br>Smart Devices & <br>Best Solution',
    buttonLink: 'https://ya.ru',
    buttonText: 'Shop All Devices'
  }
];
</script>

<style scoped>
.hero-slide-item {
  background-size: cover;
  background-position: center;
}
</style>