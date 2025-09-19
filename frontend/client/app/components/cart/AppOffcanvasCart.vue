<template>
    <div id="offcanvas-cart" class="offcanvas offcanvas-cart">
        <div class="inner">
            <div class="head">
                <span class="title">Корзина</span>
                <button class="offcanvas-close">×</button>
            </div>
            <div class="body customScroll">
                <ul class="minicart-product-list">

                    <li class="d-flex" data-product-id=" $item['id'] ">
                        <a href="/" class="image">
                            <img src=""
                                alt="">
                        </a>
                        <div class="content">
                            <a href="/" class="title lh-1">

                                <p> $item['title']  $item['weight_kg']  кг</p>

                                <p> $item['title'] </p>

                            </a>
                            <span class="quantity-price">
                                $item['quantity'] 
                                шт.

                                м<sup>2</sup>

                                <span class="amount"> number_format($item['price'], 2, '.', ' ') &#8381;</span>
                            </span>
                        </div>
                        <a href="#" class="remove" data-product-id=" $item['id'] ">×</a>
                    </li>

                    <li class="empty-cart">Корзина пуста</li>

                </ul>
            </div>

            <div class="foot">
                <div class="sub-total">
                    <strong>Итого:</strong>
                    <span class="amount"> number_format($total, 2, '.', ' ')  &#8381;</span>
                </div>
                <div class="buttons mt-30px">
                    <a href="/" class="btn mb-30px">Перейти в корзину</a>
                    <a href="/" class="btn current-btn">Оформить заказ</a>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';

onMounted(() => {
    // Get cart-specific elements
    const cartOffcanvas = document.getElementById('offcanvas-cart');
    const offCanvasOverlay = document.querySelector(".offcanvas-overlay");

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
    closeButton.addEventListener("click", function (e) {
        e.preventDefault();
        document.body.classList.remove("offcanvas-open");
        cartOffcanvas.classList.remove("offcanvas-open");
        offCanvasOverlay.style.display = "none";
    });
});
</script>

<style scoped lang="scss">
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
                    text-transform: capitalize;
                    font-weight: 500;
                    font-size: 16px;
                    border: none;
                    color: $white;
                    box-shadow: none;
                    padding: 10px 15px;
                    line-height: 26px;
                    border: none;
                    background: $heading-color;
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
            position: relative;
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

            .remove {
                line-height: 1.5;
                position: absolute;
                top: 0;
                right: 0;
                padding: 0 3px;
                color: $heading-color;
                font-size: 16px;

                &:hover {
                    color: $red;
                }
            }

            @media (max-width: 767.98px) {
                -webkit-flex: 1 0 calc(100% - 75px);
                -ms-flex: 1 0 calc(100% - 75px);
                flex: 1 0 calc(100% - 75px);
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