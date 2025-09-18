<template>
    <section class="banner-area pt-100px pb-100px">
        <div class="container">
            <div class="row">
                <div class="col-md-6" v-for="(banner, index) in banners" :key="index">
                    <a :href="banner.href">
                        <div class="single-banner mb-30px">
                            <NuxtImg :src="banner.imgSrc" :alt="banner.imgAlt" />
                            <div class="banner-content">
                                <h3 class="title">{{ banner.title }}</h3>
                                <span class="category" v-if="banner.category" v-html="banner.category"></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const banners = [
    {
        href: "/",
        imgSrc: "/images/page-index/banners/keramogranit.jpeg",
        imgAlt: "keramogranit",
        title: "Керамогранит",
        category: "От ведущих<br>производителей"
    },
    {
        href: "/",
        imgSrc: "/images/page-index/banners/plitka.jpg",
        imgAlt: "plitka",
        title: "Керамическая плитка",
        category: ""
    },
    {
        href: "/",
        imgSrc: "/images/page-index/banners/mozaika.jpeg",
        imgAlt: "mozaika",
        title: "Керамическая мозаика",
        category: ""
    },
    {
        href: "/",
        imgSrc: "/images/page-index/banners/zatirka.jpg",
        imgAlt: "zatirka",
        title: "Затирка для керамической плитки",
        category: ""
    },
    {
        href: "/",
        imgSrc: "/images/page-index/banners/santekhnika.jpeg",
        imgAlt: "santekhnika",
        title: "Сантехника",
        category: ""
    }
]
</script>

<style scoped lang="scss">
.banner-area {
    .single-banner {
        position: relative;
        overflow: hidden;
        border-radius: 0;
        padding-top: 35%;
        /* 3:2 aspect ratio */

        img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: $baseTransition;
            filter: brightness(70%);
        }

        .banner-content {
            position: absolute;
            top: 40px;
            left: 50px;
            display: flex;
            flex-direction: column;

            @media #{$desktop-device} {
                top: 20px;
            }

            @media #{$tablet-device} {
                left: 30px;
                top: 20px;
            }

            @media #{$extra-small-mobile} {
                left: 30px;
                top: 20px;
            }

            .category {
                display: block;
                text-transform: uppercase;
                color: $white;
                font-size: 24px;
                font-weight: 500;
                margin: 0 0 0px;

                @media #{$tablet-device} {
                    font-size: 20px;
                    line-height: 25px;
                }

                @media #{$small-mobile} {
                    font-size: 15px;
                    line-height: 20px;
                }
            }

            .title {
                color: $white;
                text-transform: uppercase;
                font-size: 36px;
                line-height: 46px;
                font-weight: 600;
                margin: 0 12px 12px 0;

                @media #{$desktop-device} {
                    font-size: 28px;
                    line-height: 40px;
                    margin: 0 0 5px;
                }

                @media #{$tablet-device} {
                    font-size: 24px;
                    line-height: 32px;
                    margin: 0 0 5px;
                }

                @media #{$small-mobile} {
                    font-size: 20px;
                    line-height: 24px;
                    margin: 0 0 5px;
                }

                @media #{$extra-small-mobile} {
                    margin: 0 0 0px;
                }
            }
        }

        &:hover {
            & img {
                transform: scale(1.1) rotate(2deg);
                filter: brightness(100%);
            }
        }
    }
}
</style>