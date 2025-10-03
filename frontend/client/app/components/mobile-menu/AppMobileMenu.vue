<template>
    <div id="offcanvas-mobile-menu" class="offcanvas offcanvas-mobile-menu">
        <button class="offcanvas-close"></button>
        <div class="user-panel">
            <ul>
                <li>
                    <a href="#" class="modal-call" @click.prevent="openCallModal">
                        Заказать звонок
                    </a>
                </li>
                <li><a href="tel:+79999999999" class="phone-link"><i class="fa fa-phone"></i> +7 (999) 999-99-99</a>
                </li>
                <li>
                    <a href="mailto:zakaz@agatceramic.ru">
                        <i class="fa fa-envelope-o"></i>
                        zakaz@agatceramic.ru
                    </a>
                </li>
            </ul>
        </div>
        <div class="inner customScroll">
            <nav class="offcanvas-menu mb-4">
                <ul>
                    <li>
                        <NuxtLink to="/category/keramogranit">Керамогранит</NuxtLink>
                    </li>

                    <li>
                        <NuxtLink to="/category/plitka">Плитка</NuxtLink>
                    </li>

                    <li>
                        <NuxtLink to="/category/mozaika">Мозаика</NuxtLink>
                    </li>

                    <li>
                        <a href="#"><span class="menu-text">Услуги</span></a>
                        <ul class="sub-menu">
                            <li>
                                <NuxtLink to="/rezka"><span class="menu-text">Резка</span></NuxtLink>
                            </li>
                            <li>
                                <NuxtLink to="/rospis"><span class="menu-text">Роспись плитки</span></NuxtLink>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><span class="menu-text">Каталог</span></a>
                        <ul class="sub-menu">
                            <li>
                                <NuxtLink to="/category/klinker"><span class="menu-text">Клинкер</span></NuxtLink>
                            </li>
                            <li>
                                <NuxtLink to="/category/stupeni"><span class="menu-text">Ступени</span></NuxtLink>
                            </li>
                            <li>
                                <NuxtLink to="/category/zatirka"><span class="menu-text">Затирка для плитки</span>
                                </NuxtLink>
                            </li>
                            <li>
                                <NuxtLink to="/category/klei"><span class="menu-text">Клеевые смеси</span></NuxtLink>
                            </li>
                            <li>
                                <NuxtLink to="/category/santexnika"><span class="menu-text">Сантехника</span></NuxtLink>
                                <ul class="sub-menu">
                                    <li>
                                        <NuxtLink to="/category/vanny">Ванны</NuxtLink>
                                    </li>
                                    <li>
                                        <NuxtLink to="/category/smesiteli">Смесители</NuxtLink>
                                    </li>
                                    <li>
                                        <NuxtLink to="/category/unitazy">Унитазы</NuxtLink>
                                    </li>
                                    <li>
                                        <NuxtLink to="/category/installiacii">Инсталляции</NuxtLink>
                                    </li>
                                    <li>
                                        <NuxtLink to="/category/dusevye-kabiny">Душевые кабины</NuxtLink>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <NuxtLink to="/partnerships" target="_blank">Дизайнерам</NuxtLink>
                    </li>
                    <li>
                        <NuxtLink to="/delivery">Оплата и доставка</NuxtLink>
                    </li>
                    <li>
                        <NuxtLink to="/return">Возврат и замена</NuxtLink>
                    </li>
                    <li>
                        <NuxtLink to="/contacts">Контакты</NuxtLink>
                    </li>
                    <li>
                        <NuxtLink to="/sale" class="text-danger">Распродажа</NuxtLink>
                    </li>
                </ul>
            </nav>

            <div class="offcanvas-social">
                <ul>
                    <li>
                        <a href="https://t.me/{{ $appData->telegram ?? '---' }}" target="_blanc"><i
                                class="fa fa-telegram"></i></a>
                    </li>
                    <li>
                        <a href="https://wa.me/{{ $appData->whatsapp ?? '---' }}" target="_blanc"><i
                                class="fa fa-whatsapp"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, inject } from 'vue';

const openCallModal = inject('openCallModal')

onMounted(() => {
    // Get mobile menu elements
    const mobileMenu = document.getElementById('offcanvas-mobile-menu');
    const offCanvasOverlay = document.querySelector(".offcanvas-overlay");

    // Open menu when clicked
    const menuToggle = document.querySelector('a[href="#offcanvas-mobile-menu"]');
    if (menuToggle) {
        menuToggle.addEventListener("click", function (e) {
            e.preventDefault();
            document.body.classList.add("offcanvas-open");
            mobileMenu.classList.add("offcanvas-open");
            offCanvasOverlay.style.display = "block";

            // Add close class to toggle button
            if (this.parentElement.classList.contains("mobile-menu-toggle")) {
                this.classList.add("close");
            }
        });
    }

    // Close menu when clicked
    const closeButton = mobileMenu.querySelector('.offcanvas-close');
    closeButton.addEventListener("click", function (e) {
        e.preventDefault();
        document.body.classList.remove("offcanvas-open");
        mobileMenu.classList.remove("offcanvas-open");
        offCanvasOverlay.style.display = "none";

        // Remove close class from toggle button
        const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
        if (mobileMenuToggle) {
            mobileMenuToggle.querySelectorAll("a").forEach(a => a.classList.remove("close"));
        }
    });

    // Initialize mobile menu submenus
    const offCanvasNav = mobileMenu.querySelectorAll(".offcanvas-menu, .overlay-menu");

    offCanvasNav.forEach(nav => {
        const subMenus = nav.querySelectorAll(".sub-menu");

        subMenus.forEach(subMenu => {
            const expander = document.createElement("span");
            expander.className = "menu-expand";
            subMenu.parentNode.insertBefore(expander, subMenu);
        });

        nav.addEventListener("click", function (e) {
            const target = e.target;

            if (target.getAttribute("href") === "#" || target.classList.contains("menu-expand")) {
                e.preventDefault();

                if (target.nextElementSibling && target.nextElementSibling.style.display === "block") {
                    target.parentNode.classList.remove("active");
                    target.nextElementSibling.style.display = "none";
                } else {
                    target.parentNode.classList.add("active");
                    target.nextElementSibling.style.display = "block";
                }
            }
        });
    });
});
</script>

<style lang="scss">
.offcanvas-mobile-menu {
    font-size: 14px;
    font-weight: 400;
    position: fixed;
    z-index: 1000;
    top: 0;
    right: auto;
    left: 0;
    display: block;
    width: 350px;
    height: 100%;
    padding: 50px 30px;
    -webkit-transition: all 0.5s ease 0s;
    -o-transition: all 0.5s ease 0s;
    transition: all 0.5s ease 0s;
    -webkit-transform: translateX(-100%);
    -ms-transform: translateX(-100%);
    transform: translateX(-100%);
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

        .offcanvas-close {
            left: 80%;
        }
    }

    .offcanvas-close {
        position: absolute;
        width: 40px;
        height: 40px;
        text-indent: -9999px;
        left: 0%;
        top: 5px;
        animation-delay: 0.5s;
        border: 0;
        background-color: transparent;

        &::after {
            position: absolute;
            top: calc(50% - 1px);
            left: 50%;
            margin-left: -10px;
            width: 20px;
            height: 2px;
            content: "";
            -webkit-transition: all 0.5s ease 0s;
            -o-transition: all 0.5s ease 0s;
            transition: all 0.5s ease 0s;
            -webkit-transform: rotate(-45deg);
            -ms-transform: rotate(-45deg);
            transform: rotate(-45deg);
            background-color: $body-color;
        }

        &::before {
            position: absolute;
            top: calc(50% - 1px);
            left: 50%;
            margin-left: -10px;
            width: 20px;
            height: 2px;
            content: "";
            -webkit-transition: all 0.5s ease 0s;
            -o-transition: all 0.5s ease 0s;
            transition: all 0.5s ease 0s;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
            background-color: $body-color;
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

    .user-panel {
        padding: 20px 0;
        border-top: 1px solid $border-color;

        ul {
            li {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;

                a {
                    font-size: 14px;
                    color: #3a3a3a;
                    display: flex;
                    flex-direction: row;
                    justify-content: center;
                    line-height: 1;
                    align-items: center;
                    font-weight: 500;

                    &:hover {
                        color: $theme-color;
                    }

                    i {
                        margin-right: 15px;
                        font-size: 18px;
                        color: $body-color;
                        width: 20px;
                        height: 20px;
                        display: flex;
                        align-items: center;
                        flex-direction: row;
                    }
                }

                &:not(:last-child) {
                    margin-bottom: 10px;
                }
            }
        }
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

        .offcanvas-menu {
            margin-bottom: 2rem;

            & ul {
                & li {
                    position: relative;
                    display: block;
                    line-height: 28px;

                    & a {
                        display: block;
                        padding: 10px 0px;
                        text-transform: lowercase;
                        color: $body-color;
                        border-top: 1px solid $border-color;
                        font-size: 16px;
                        font-weight: 500;

                        &::first-letter {
                            text-transform: uppercase;
                        }
                    }

                    .sub-menu {
                        position: static;
                        top: auto;
                        display: none;
                        visibility: visible;
                        width: 100%;
                        min-width: auto;
                        padding: 0;
                        -webkit-transition: none;
                        -o-transition: none;
                        transition: none;
                        opacity: 1;
                        -webkit-box-shadow: none;
                        box-shadow: none;

                        & li {
                            line-height: inherit;
                            position: relative;

                            & a {
                                text-transform: lowercase;
                                font-weight: 400;
                                padding-left: 10px;
                                padding-right: 0px;
                                display: block;
                                border-top: 1px solid $border-color;
                                font-size: 14px;
                                font-weight: 500;
                            }

                            &:last-child {
                                border-bottom: 0px solid $border-color;
                            }
                        }
                    }

                    & .menu-expand {
                        position: absolute;
                        z-index: 2;
                        top: 0;
                        right: 0px;
                        width: 24px;
                        height: 49px;
                        cursor: pointer;
                        background-color: transparent;

                        &::before {
                            position: absolute;
                            top: calc(50% - 1px);
                            left: calc(50% - 7px);
                            width: 14px;
                            height: 2px;
                            content: "";
                            -webkit-transition: all 0.5s ease 0s;
                            -o-transition: all 0.5s ease 0s;
                            transition: all 0.5s ease 0s;
                            -webkit-transform: scale(0.75);
                            -ms-transform: scale(0.75);
                            transform: scale(0.75);
                            background-color: $heading-color;
                        }

                        &::after {
                            position: absolute;
                            top: calc(50% - 1px);
                            left: calc(50% - 7px);
                            width: 14px;
                            height: 2px;
                            content: "";
                            -webkit-transition: all 0.5s ease 0s;
                            -o-transition: all 0.5s ease 0s;
                            transition: all 0.5s ease 0s;
                            -webkit-transform: rotate(90deg) scale(0.75);
                            -ms-transform: rotate(90deg) scale(0.75);
                            transform: rotate(90deg) scale(0.75);
                            background-color: $heading-color;
                        }
                    }

                    &.active {
                        &>.menu-expand::after {
                            -webkit-transform: rotate(0) scale(0.75);
                            -ms-transform: rotate(0) scale(0.75);
                            transform: rotate(0) scale(0.75);
                        }
                    }

                    &:hover {
                        &>a {
                            color: $theme-color;
                        }

                        &>span {
                            &.menu-expand {
                                &::before {
                                    background: $theme-color;
                                }

                                &::after {
                                    background: $theme-color;
                                }
                            }
                        }
                    }

                    &:last-child {
                        border-bottom: 1px solid $border-color;
                    }
                }
            }
        }

        .offcanvas-social {
            & ul {
                & li {
                    display: inline-block;
                    margin: 0 25px 0px 0;
                    line-height: 40px;
                    padding: 0;

                    @media (max-width: 575.98px) {
                        margin: 0 10px 0px 0;
                    }

                    & a {
                        color: $theme-color;
                        font-size: 40px;

                        &:hover {
                            color: $body-color;
                        }

                        @media (max-width: 575.98px) {
                            font-size: 30px;
                        }
                    }
                }
            }
        }
    }
}
</style>