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

                            </div>
                        </div>

                        <div class="tab-pane fade show active" id="plitka">
                            <div class="row mb-n-30px">


                            </div>
                        </div>

                        <div class="tab-pane fade show active" id="mozaika">
                            <div class="row mb-n-30px">

                                <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 col-xs-6 mb-30px"
                                    v-for="(product, index) in products" :key="index">
                                    <div class="product card h-100" style="border: none">
                                        <span class="badges" v-if="product.sale">
                                            <span class="new">Распродажа</span>
                                        </span>

                                        <div class="thumb d-flex justify-content-center align-items-center"
                                            style="aspect-ratio: 1 / 1;">
                                            <a href="/" class="image">
                                                <NuxtImg :src="(product.imgSrc && product.imgSrc.trim() !== '') ? product.imgSrc : '/images/stock/stock-image.png'" :alt="product.title" />
                                                <NuxtImg :src="(product.imgSrc && product.imgSrc.trim() !== '') ? product.imgSrc : '/images/stock/stock-image.png'" :alt="product.title"
                                                    class="hover-image" />
                                            </a>
                                        </div>
                                        <div class="content text-center">
                                            <span class="category"><a href="{{product.href}}">{{ product.category
                                                    }}</a></span>

                                            <span class="price">
                                                <span class="new">{{ formatter.format(product.price) }}</span>
                                            </span>

                                            <h5 class="title">
                                                <a href="/">{{ product.title.slice(0, 80) }}</a>
                                            </h5>
                                        </div>
                                        <div class="actions">
                                            <button class="action add-cart" data-product-id="{{product.id}}">
                                                <i class="pe-7s-cart"></i>
                                            </button>

                                            <button class="action quickview" data-link-action="quickview"
                                                title="Посмотреть" data-id="{{ product.id ?? null }}"
                                                data-bs-toggle="modal" data-bs-target="#modalProduct">
                                                <i class="pe-7s-look"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const formatter = new Intl.NumberFormat('ru-RU', {
  style: 'currency',
  currency: 'RUB',
});
const products = [
    {
        id: 1,
        href: "/keramogranit",
        imgSrc: "/images/page-index/banners/keramogranit.jpeg",
        imgAlt: "keramogranit",
        title: "Керамогранит Керамогранит Керамогранит Керамогранит Керамогранит Керамогранит Керамогранит Керамогранит",
        category: "Керамогранит",
        sale: true,
        price: 9999.99,
    },
    {
        id: 2,
        href: "/plitka",
        imgSrc: "/images/page-index/banners/plitka.jpg",
        imgAlt: "plitka",
        title: "Керамическая плитка",
        category: "Керамическая плитка",
        sale: false,
        price: 9999.99,
    },
    {
        id: 3,
        href: "/mozaika",
        imgSrc: "/images/page-index/banners/mozaika.jpeg",
        imgAlt: "mozaika",
        title: "Керамическая мозаика",
        category: "Керамическая мозаика",
        sale: true,
        price: 9999,
    },
    {
        id: 4,
        href: "/zatirka",
        imgSrc: "/images/page-index/banners/zatirka.jpg",
        imgAlt: "zatirka",
        title: "Затирка для керамической плитки",
        category: "Затирка для керамической плитки",
        sale: false,
        price: 9999.99,
    },
    {
        id: 5,
        href: "/santekhnika",
        imgSrc: "",
        imgAlt: "santekhnika",
        title: "Сантехника",
        category: "Сантехника",
        sale: false,
        price: 9999,
    }
]
</script>