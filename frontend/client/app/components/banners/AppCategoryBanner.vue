<template>
    <section class="banner-area style-two pt-100px pb-100px">
        <div class="container">
            <div class="row">
                <div class="col-md-6" v-for="(banner, index) in banners" :key="index">
                    <a :href="banner.href">
                        <div class="single-banner mb-30px mb-lm-30px">
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