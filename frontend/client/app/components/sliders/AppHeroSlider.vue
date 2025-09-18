<template>
    <section class="section slider-nav-style-1">
        <Swiper
            class="hero-slider"
            :modules="[Autoplay, EffectFade, Pagination, Navigation]"
            :effect="'fade'"
            :speed="2500"
            :loop="true"
            :autoplay="{
                delay: 5000,
                disableOnInteraction: false,
            }"
            :pagination="{
                clickable: true,
                el: '.swiper-pagination',
            }"
            :navigation="{
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }">
            <SwiperSlide v-for="(slide, index) in slides" :key="index" class="hero-slide-item slider-height bg-color1"
                :style="{ backgroundImage: 'url(' + slide.bgImage + ')' }">
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

<style scoped lang="scss">
.hero-slide-item {
    background-size: cover;
    background-position: center;
}

.bg-color1 {
    background-color: #f5f5f5;
}

.slider-height {
    height: 748px;

    @media #{$laptop-device} {
        height: 700px;
    }

    @media #{$desktop-device} {
        height: 550px;
    }

    @media #{$tablet-device} {
        height: 500px;
    }

    @media #{$large-mobile} {
        height: 450px;
    }

    @media #{$small-mobile} {
        height: 700px;
    }
}

.sm-center-view {
    @media #{$small-mobile} {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
}

.hero-slide-image {
    display: flex;
    flex-direction: column;
    align-items: flex-end;

    & img {
        max-width: 100%;
        box-shadow: 0px -5px 10px 5px rgba(0, 0, 0, 0.5);
        animation-duration: 1s;
        animation-fill-mode: both;
        animation-name: fadeOutUp;

        @media #{$desktop-device, $tablet-device} {
            width: 80%;
        }

        @media #{$large-mobile} {
            width: 60%;
            margin: auto;
        }

        @media #{$small-mobile} {
            width: 60%;
            margin: auto;
        }
    }

    @media #{$small-mobile} {
        margin-top: 50px;
        text-align: center;
    }
}

.swiper-slide-active {
    & .hero-slide-image {
        & img {
            width: 12rem;
            animation-name: fadeInUp;
            animation-delay: 1s;
        }
    }
}

.hero-slide-content {
    position: relative;
    z-index: 9;
    text-align: left;
    margin-top: -15px;

    @media #{$small-mobile} {
        margin-top: 0px;
    }

    .category {
        display: block;
        font-size: 18px;
        margin: 0 0 10px;
        font-weight: 500;
        color: $white;

        @media #{$large-mobile} {
            font-size: 16px;
        }
    }

    & .title-1 {
        font-size: 60px;
        font-weight: 600;
        margin-bottom: 50px;
        color: $white;
        line-height: 78px;

        @media #{$desktop-device} {
            font-size: 50px;
            margin-bottom: 40px;
            line-height: 68px;
        }

        @media #{$tablet-device} {
            font-size: 38px;
            margin-bottom: 30px;
            line-height: 56px;
        }

        @media #{$large-mobile} {
            font-size: 28px;
            margin-bottom: 30px;
            line-height: 46px;
        }
    }

    & .btn {
        border-width: 2px;
        border-color: rgba(255, 255, 255, 1);
        border-style: solid;
        border-radius: 18px;
        width: 215px;
        height: 61px;
        background-color: transparent;

        &:hover {
            color: rgba(0, 0, 0, 0.5);
            background-color: $white;
        }

        &:active {
            border-color: $theme-color;
            background-color: $theme-color;
        }

        @media #{$tablet-device, $large-mobile} {
            width: 150px;
            height: 50px;
            font-size: 14px;
        }

        & i {
            font-size: 16px;

            @media #{$tablet-device, $large-mobile} {
                font-size: 14px;
            }
        }
    }
}

.show-case {
    @media #{$small-mobile} {
        position: relative;
    }
}
</style>