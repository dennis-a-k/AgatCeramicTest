<template>
    <div class="nav-alphabet d-none d-lg-block">
        <div class="container">
            <div class="second-menu position-relative">
                <ul v-if="!store.loading && !store.error">
                    <li><span>Бренды:</span></li>
                    <li v-for="(brands, letter) in store.groupedBrands" :key="letter" class="dropdown position-static" v-show="brands.length > 0">
                        <a href="#">{{ letter }}</a>
                        <ul class="mega-menu">
                            <li v-for="brand in brands" :key="brand.id">
                                <NuxtLink :to="store.getBrandUrl(brand)">{{ brand.name }}</NuxtLink>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div v-else-if="store.loading" class="text-center">
                    Загрузка брендов...
                </div>
                <div v-else class="text-center text-danger">
                    Ошибка загрузки брендов
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAlphabetStore } from '~/stores/useAlphabetStore'

const store = useAlphabetStore()

onMounted(() => {
  store.fetchBrands()
})
</script>

<style scoped lang="scss">
.nav-alphabet {
    border-top: 1px solid $border-color;
    background-color: $secondary;

    .second-menu {
        display: flex;
        justify-content: center;
        align-items: center;

        & ul {
            display: flex;
            flex-wrap: wrap;

            margin-bottom: 0;
            padding-left: 0;

            list-style: none;

            & li {
                &+li {
                    padding: 0 18px;

                    @media #{$laptop-device} {
                        padding: 0 14px;
                    }

                    @media #{$desktop-device} {
                        padding: 0 10px;
                    }
                }

                & a,
                span {
                    font-size: 14px;
                    font-weight: 400;
                    text-decoration: none;
                    text-transform: uppercase;
                    color: $black;
                    display: block;
                    position: relative;
                    line-height: 50px;
                }

                &.dropdown {
                    position: relative;

                    & ul {
                        & li {
                            padding: 0;
                            margin: 0;
                            display: block;

                            &:hover,
                            &.active {
                                &>a {
                                    color: $theme-color !important;
                                }
                            }

                            & a {
                                display: block;
                                line-height: 20px;
                                padding: 12px 0px 12px 20px;
                                font-weight: 400;
                                font-size: 14px;
                                color: $link-secondary-color;
                                text-transform: none;

                                &:hover {
                                    padding-left: 25px;
                                }
                            }
                        }
                    }

                    &:hover {
                        & .mega-menu {
                            opacity: 1;
                            visibility: visible;
                            transform: rotateX(0deg);
                            -o-transform: rotateX(0deg);
                            -moz-transform: rotateX(0deg);
                            -webkit-transform: rotateX(0deg);
                            -o-transition: -o-transform 0.3s, opacity 0.3s;
                            -ms-transition: -ms-transform 0.3s, opacity 0.3s;
                            -moz-transition: -moz-transform 0.3s, opacity 0.3s;
                            -webkit-transition: -webkit-transform 0.3s, opacity 0.3s;
                        }
                    }
                }

                & li {
                    &.position-static {
                        position: relative !important;

                        & i {
                            position: absolute;
                            right: 20px;
                        }
                    }
                }

                & .mega-menu {
                    position: absolute;
                    background: $white;
                    padding: 30px 0px 30px 30px;
                    opacity: 0;
                    visibility: hidden;
                    -o-transform-origin: 0% 0%;
                    -ms-transform-origin: 0% 0%;
                    -moz-transform-origin: 0% 0%;
                    -webkit-transform-origin: 0% 0%;
                    transform-style: preserve-3d;
                    -o-transform-style: preserve-3d;
                    -moz-transform-style: preserve-3d;
                    -webkit-transform-style: preserve-3d;
                    transform: rotateX(-75deg);
                    -o-transform: rotateX(-75deg);
                    -moz-transform: rotateX(-75deg);
                    -webkit-transform: rotateX(-75deg);
                    left: 0;
                    width: 100%;
                    z-index: 2;
                    -o-transition: -o-transform 0.3s, opacity 0.3s;
                    -ms-transition: -ms-transform 0.3s, opacity 0.3s;
                    -moz-transition: -moz-transform 0.3s, opacity 0.3s;
                    -webkit-transition: -webkit-transform 0.3s, opacity 0.3s;
                    z-index: 15;
                    box-shadow: -1px 10px 80px -15px rgba(0, 0, 0, 0.3);

                    & li {
                        width: 25%;
                        margin-right: 30px;
                        border-right: 1px solid $border-color;

                        & li {
                            &.title {
                                & a {
                                    color: $black;
                                    font-size: 16px;
                                    font-weight: 500;
                                    display: block;
                                    margin-bottom: 20px;
                                    height: auto;

                                    &:hover {
                                        color: $theme-color;
                                        padding-left: 0px;
                                    }
                                }
                            }

                            & a {
                                color: $link-secondary-color;
                                text-transform: capitalize;
                                line-height: 30px;
                                font-weight: 400;
                                font-size: 14px;
                                display: block;
                                padding: 0;
                                border: 0;
                                height: auto;
                                margin-bottom: 15px;

                                &:hover {
                                    color: $theme-color;
                                    padding-left: 10px;
                                }
                            }

                            &:last-child {
                                a {
                                    margin: 0;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
</style>