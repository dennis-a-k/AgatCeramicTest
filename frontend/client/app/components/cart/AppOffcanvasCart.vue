<template>
    <ClientOnly>
        <div id="offcanvas-cart" class="offcanvas offcanvas-cart">
            <div class="inner">
                <div class="head">
                    <span class="title">Корзина</span>
                    <button class="offcanvas-close" @click="closeCart">×</button>
                </div>
                <div class="body customScroll">
                    <ul class="minicart-product-list">
                        <li v-for="item in cartStore.items" :key="item.id" class="d-flex" :data-product-id="item.id">
                            <NuxtLink :to="`/product/${item.slug}`" class="image">
                                <img :src="item.image" :alt="item.title">
                            </NuxtLink>
                            <div class="content">
                                <NuxtLink :to="`/product/${item.slug}`" class="title lh-1">
                                    <p>{{ item.title }}{{ item.weight_kg ? `, ${item.weight_kg}кг` : '' }}</p>
                                </NuxtLink>
                                <span class="quantity-price">
                                    {{ item.quantity }} {{ item.unit === 'шт' ? 'шт.' : item.unit === 'кв.м' ? 'м²' :
                                        item.unit }}
                                    <span class="amount"> x {{ formatPrice(item.price * item.quantity) }}</span>
                                </span>
                                <div class="quantity-controls">
                                    <button @click="updateQuantity(item.id, item.quantity - 1)"
                                        :disabled="item.quantity <= 1">-</button>
                                    <span>{{ item.quantity }}</span>
                                    <button @click="updateQuantity(item.id, item.quantity + 1)">+</button>
                                </div>
                            </div>
                            <a href="#" class="remove" @click.prevent="removeItem(item.id)"
                                :data-product-id="item.id">×</a>
                        </li>
                        <li v-if="cartStore.items.length === 0" class="empty-cart">Корзина пуста</li>
                    </ul>
                </div>
                <div v-if="cartStore.items.length > 0" class="foot">
                    <div class="sub-total">
                        <strong>Итого: </strong>
                        <span class="amount">{{ formatPrice(cartStore.total) }}</span>
                    </div>
                    <div class="buttons mt-30px">
                        <NuxtLink to="/cart" class="btn mb-30px" @click="closeCart">Перейти в корзину</NuxtLink>
                        <NuxtLink to="/checkout" class="btn current-btn" @click="closeCart">Оформить заказ</NuxtLink>
                    </div>
                </div>
            </div>
        </div>
    </ClientOnly>
</template>
<script setup>
import { onMounted, nextTick } from 'vue';
import { useCartStore } from '~/stores/useCartStore';

const cartStore = useCartStore();
const { $toast } = useNuxtApp();

const formatter = new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
});

const formatPrice = (price) => {
    return formatter.format(price);;
};

const removeItem = (id) => {
    cartStore.removeFromCart(id);
};

const updateQuantity = (id, quantity) => {
    cartStore.updateQuantity(id, quantity);
};

const closeCart = () => {
    document.body.classList.remove("offcanvas-open");
    const cartOffcanvas = document.getElementById('offcanvas-cart');
    if (cartOffcanvas) {
        cartOffcanvas.classList.remove("offcanvas-open");
    }
    const offCanvasOverlay = document.querySelector(".offcanvas-overlay");
    if (offCanvasOverlay) {
        offCanvasOverlay.style.display = "none";
    }
};

onMounted(() => {
    // Use nextTick to ensure DOM is ready
    nextTick(() => {
        // Get cart-specific elements
        const cartOffcanvas = document.getElementById('offcanvas-cart');
        const offCanvasOverlay = document.querySelector(".offcanvas-overlay");

        if (!cartOffcanvas || !offCanvasOverlay) return;

        // Open cart when clicked (handle both href and class selectors)
        const cartToggles = document.querySelectorAll('a[href="#offcanvas-cart"], .offcanvas-toggle[href="#offcanvas-cart"]');
        cartToggles.forEach(toggle => {
            toggle.addEventListener("click", function (e) {
                e.preventDefault();
                document.body.classList.add("offcanvas-open");
                cartOffcanvas.classList.add("offcanvas-open");
                offCanvasOverlay.style.display = "block";
            });
        });

        // Close cart when clicked
        const closeButton = cartOffcanvas.querySelector('.offcanvas-close');
        if (closeButton) {
            closeButton.addEventListener("click", function (e) {
                e.preventDefault();
                closeCart();
            });
        }
    });
});
</script>

<style scoped lang="scss">
.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 10px;

    button {
        width: 25px;
        height: 25px;
        border: 1px solid $border-color;
        background: $white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;

        &:hover {
            background: $theme-color;
            color: $white;
        }

        &:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    }

    span {
        font-weight: 500;
    }
}

.offcanvas-cart {
    font-size: 14px;
    font-weight: 400;
    position: fixed;
    z-index: 1000;
    top: 0;
    right: 0;
    left: auto;
    display: block;
    width: 400px;
    height: 100%;
    padding: 20px;
    -webkit-transition: all 0.5s ease 0s;
    -o-transition: all 0.5s ease 0s;
    transition: all 0.5s ease 0s;
    -webkit-transform: translateX(100%);
    -ms-transform: translateX(100%);
    transform: translateX(100%);
    background-color: $white;
    -webkit-box-shadow: none;
    box-shadow: none;
    overflow: auto;
    visibility: visible;

    @media (max-width: 575.98px) {
        width: 300px;
    }

    &.offcanvas-open {
        -webkit-transform: translateX(0);
        -ms-transform: translateX(0);
        transform: translateX(0);
    }

    .inner {
        position: relative;
        z-index: 9;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
        height: 100%;

        .head {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-pack: justify;
            -webkit-justify-content: space-between;
            -ms-flex-pack: justify;
            justify-content: space-between;
            width: 100%;
            padding: 30px 20px;
            margin-bottom: 30px;
            padding: 0;

            .title {
                color: $heading-color;
                font-weight: 700;
                font-size: 20px;
            }

            .offcanvas-close {
                border: 0;
                background-color: transparent;
                position: relative;
                width: 20px;
                height: 20px;
                text-indent: -9999px;

                &::after {
                    position: absolute;
                    top: calc(50% - 1px);
                    left: 0;
                    width: 20px;
                    height: 2px;
                    content: "";
                    -webkit-transition: all 0.5s ease 0s;
                    -o-transition: all 0.5s ease 0s;
                    transition: all 0.5s ease 0s;
                    -webkit-transform: rotate(-45deg);
                    -ms-transform: rotate(-45deg);
                    transform: rotate(-45deg);
                    background-color: $theme-color;
                }

                &::before {
                    position: absolute;
                    top: calc(50% - 1px);
                    left: 0;
                    width: 20px;
                    height: 2px;
                    content: "";
                    -webkit-transition: all 0.5s ease 0s;
                    -o-transition: all 0.5s ease 0s;
                    transition: all 0.5s ease 0s;
                    -webkit-transform: rotate(45deg);
                    -ms-transform: rotate(45deg);
                    transform: rotate(45deg);
                    background-color: $theme-color;
                }

                &:hover {
                    &:before {
                        -webkit-transform: rotate(180deg);
                        -ms-transform: rotate(180deg);
                        transform: rotate(180deg);
                    }

                    &:after {
                        -webkit-transform: rotate(0deg);
                        -ms-transform: rotate(0deg);
                        transform: rotate(0deg);
                    }
                }
            }
        }

        .body {
            flex: 1;
            overflow-y: auto;
        }

        .foot {
            .buttons {
                a {
                    display: block;
                    font-weight: 500;
                    font-size: 16px;
                    border: 1px solid $theme-color;
                    color: $theme-color;
                    box-shadow: none;
                    padding: 10px 15px;
                    line-height: 26px;
                    background: transparent;
                    border-radius: 0px;
                    width: auto;
                    height: auto;
                    text-align: center;
                    margin-bottom: 15px;

                    &:hover {
                        background-color: $theme-color;
                        color: $white;
                    }
                }

                .current-btn {
                    background-color: $theme-color;
                    color: $white;

                    &:hover {
                        background-color: $body-color;
                    }
                }
            }

            .sub-total {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                padding-top: 15px;
                padding-bottom: 15px;
                border-top: 1px solid $border-color;
                color: $theme-color;
                margin: 30px 0 0 0px;

                .amount {
                    color: $red;
                    font-weight: 600;
                }
            }
        }
    }
}

.minicart-product-list {
    margin: 0;
    padding-left: 0;
    list-style: none;

    li {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid $border-color;

        .image {
            -webkit-box-flex: 1;
            -webkit-flex: 1 0 75px;
            -ms-flex: 1 0 75px;
            flex: 1 0 75px;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            align-content: center;
            border: 1px solid $border-color;

            img {
                max-width: 100%;
                border: 1px solid $border-color;
            }

            @media (max-width: 767.98px) {
                -webkit-flex: 1 0 50px;
                -ms-flex: 1 0 50px;
                flex: 1 0 50px;
            }
        }

        .content {
            -webkit-box-flex: 1;
            -webkit-flex: 1 0 calc(100% - 150px);
            -ms-flex: 1 0 calc(100% - 150px);
            flex: 1 0 calc(100% - 150px);
            padding-left: 15px;

            .title {
                color: $heading-color;
                font-weight: 500;

                &:hover {
                    color: $theme-color;
                }
            }

            .quantity-price {
                font-size: 14px;
                display: block;
                margin-top: 10px;

                .amount {
                    color: $theme-color;
                    font-weight: 500;
                    font-size: 18px;
                }
            }

            @media (max-width: 767.98px) {
                -webkit-flex: 1 0 calc(100% - 75px);
                -ms-flex: 1 0 calc(100% - 75px);
                flex: 1 0 calc(100% - 75px);
            }
        }

        .remove {
            line-height: 1.5;
            top: 0;
            right: 0;
            padding: 0 3px;
            color: $heading-color;
            font-size: 16px;

            &:hover {
                color: $red;
            }
        }

        &:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: 0;
        }
    }
}
</style>