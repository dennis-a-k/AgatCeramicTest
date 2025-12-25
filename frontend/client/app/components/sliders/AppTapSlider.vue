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
                                <ProductAppProductCard v-for="(product, index) in keramogranitProducts" :key="product.id || index"
                                    :product="product" />
                            </div>
                        </div>

                        <div class="tab-pane fade" id="plitka">
                            <div class="row mb-n-30px">
                                <ProductAppProductCard v-for="(product, index) in plitkaProducts" :key="product.id || index"
                                    :product="product" />
                            </div>
                        </div>

                        <div class="tab-pane fade" id="mozaika">
                            <div class="row mb-n-30px">
                                <ProductAppProductCard v-for="(product, index) in mozaikaProducts" :key="product.id || index"
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
import { ref, onMounted } from 'vue'
import { useRuntimeConfig } from '#app'

const config = useRuntimeConfig()
const apiBase = config.public.apiBase

// Реактивные переменные для товаров каждой категории
const keramogranitProducts = ref([])
const plitkaProducts = ref([])
const mozaikaProducts = ref([])

// Функция для получения случайных товаров категории
const fetchRandomProducts = async (slug, targetArray) => {
    try {
        const response = await $fetch(`${apiBase}/api/client/category/${slug}/products?per_page=1000`)
        if (response && response.products && response.products.data) {
            // Трансформируем данные к формату компонента
            const transformedProducts = response.products.data.map(product => ({
                id: product.id,
                name: product.name,
                slug: product.slug,
                price: product.price,
                sale: product.is_sale, // для компонента sale
                is_sale: product.is_sale, // для компонента badges
                category: {
                    name: product.category?.name || 'Категория',
                    slug: product.category?.slug || ''
                },
                href: '/', // пока статический
                imgSrc: '', // пока пустое, будет дефолтное изображение
                imgAlt: product.name,
                title: product.name,
                // Дополнительные поля для модального окна быстрого просмотра
                article: product.article,
                brand: product.brand,
                collection: product.collection,
                color: product.color,
                unit: product.unit,
                images: product.images,
                attribute_values: product.attribute_values
            }))
            // Перемешиваем массив и берем первые 8 товаров
            const shuffled = transformedProducts.sort(() => 0.5 - Math.random())
            targetArray.value = shuffled.slice(0, 8)
        }
    } catch (error) {
        console.error(`Error fetching products for ${slug}:`, error)
        targetArray.value = []
    }
}

// Загрузка данных при монтировании компонента
onMounted(async () => {
    await Promise.all([
        fetchRandomProducts('keramogranit', keramogranitProducts),
        fetchRandomProducts('plitka', plitkaProducts),
        fetchRandomProducts('mozaika', mozaikaProducts)
    ])
})
</script>

<style scoped lang="scss">
.product-tab-nav {
    &.nav {
        flex-wrap: inherit;
    }

    @media #{$large-mobile} {}

    & .nav-item {
        display: inline-block;
        vertical-align: top;
        text-transform: capitalize;
        cursor: pointer;
        -webkit-transition: all 300ms ease;
        -moz-transition: all 300ms ease;
        -ms-transition: all 300ms ease;
        -o-transition: all 300ms ease;
        transition: all 300ms ease;

        &:not(:first-child) {
            margin-left: 20px;

            @media #{$small-mobile} {
                margin-left: 10px;
            }
        }

        & .nav-link {
            width: 174px;
            height: 54px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 1px solid #eaeaeb;
            border-radius: 18px;
            font-size: 18px;
            font-weight: 600;
            color: $logo-secondary-color;
            background-color: $white;

            @media #{$tablet-device, $large-mobile} {
                width: 150px;
                height: 48px;
                font-size: 16px;
            }

            @media #{$small-mobile} {
                width: 140px;
                height: 40px;
                font-size: 14px;
            }

            @media #{$extra-small-mobile} {
                width: auto;
                font-size: 12px;
                padding: 0 15px;
            }

            &:hover,
            &.active {
                color: $white;
                background-color: $theme-color;
                border-color: $theme-color;
            }
        }
    }
}
</style>