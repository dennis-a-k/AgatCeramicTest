<template>
    <section class="product-area pb-100px">
        <div class="container">
            <h2 class="text-center d-none my-5">Популярные товары</h2>
            <div class="row" style="padding-bottom: 6rem;">
                <div class="col-12">
                    <div class="tab-slider d-md-flex justify-content-md-between align-items-md-center">
                        <ul class="product-tab-nav nav justify-content-start align-items-center">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#keramogranit">
                                    Керамогранит
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#plitka">
                                    Плитка
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#mozaika">
                                    Мозаика
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="keramogranit">
                            <div class="row mb-n-30px">
                                <ProductAppProductCard v-for="(product, index) in products" :key="index"
                                    :product="product" />
                            </div>
                        </div>

                        <div class="tab-pane fade" id="plitka">
                            <div class="row mb-n-30px">


                            </div>
                        </div>

                        <div class="tab-pane fade" id="mozaika">
                            <div class="row mb-n-30px">
                                <ProductAppProductCard v-for="(product, index) in products" :key="index"
                                    :product="product" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const products = [
    {
        id: 1,
        href: "/",
        imgSrc: "/images/page-index/banners/keramogranit.jpeg",
        imgAlt: "keramogranit",
        title: "Керамогранит Керамогранит Керамогранит Керамогранит Керамогранит Керамогранит Керамогранит Керамогранит",
        category: "Керамогранит",
        sale: true,
        price: 9999.99,
    },
    {
        id: 2,
        href: "/",
        imgSrc: "/images/page-index/banners/plitka.jpg",
        imgAlt: "plitka",
        title: "Керамическая плитка",
        category: "Керамическая плитка",
        sale: false,
        price: 9999.99,
    },
    {
        id: 3,
        href: "/",
        imgSrc: "/images/page-index/banners/mozaika.jpeg",
        imgAlt: "mozaika",
        title: "Керамическая мозаика",
        category: "Керамическая мозаика",
        sale: true,
        price: 9999,
    },
    {
        id: 4,
        href: "/",
        imgSrc: "/images/page-index/banners/zatirka.jpg",
        imgAlt: "zatirka",
        title: "Затирка для керамической плитки",
        category: "Затирка для керамической плитки",
        sale: false,
        price: 9999.99,
    },
    {
        id: 5,
        href: "/",
        imgSrc: "",
        imgAlt: "santekhnika",
        title: "Сантехника",
        category: "Сантехника",
        sale: false,
        price: 9999,
    }
]
</script>