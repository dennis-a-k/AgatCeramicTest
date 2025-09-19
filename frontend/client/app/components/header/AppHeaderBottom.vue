<template>
    <div class="header-bottom d-none d-lg-block">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-3 col">
                    <div class="header-logo">
                        <NuxtLink to="/" class="row align-items-center">
                            <NuxtImg src="/images/logo.svg" class="col-3 pe-0" alt="Site Logo" />
                            <p class="col-9">Agat<span>Ceramic</span></p>
                        </NuxtLink>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <UiSearchForm />
                </div>
                <div class="col-lg-3 col">
                    <div class="header-actions">
                        <a href="#offcanvas-cart"
                            class="header-action-btn header-action-btn-cart offcanvas-toggle pr-0">
                            <div>
                                <span>Корзина</span>
                                <i class="pe-7s-cart"></i>
                                <span class="header-action-num">01</span>
                            </div>
                            <span class="header-action-order">Оформить заказ</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.header-bottom {
    padding: 30px 0;
    background-color: $secondary;

    @media #{$tablet-device, $large-mobile} {
        padding: 15px 0;
    }

    .header-logo {
        & a {
            font-family: $heading-font;
            color: $black;
            text-decoration: none;

            & img {
                @media #{$tablet-device} {
                    width: 18%;
                }
            }

            & p {
                font-weight: 600;
                font-size: 1.75rem;

                @media #{$desktop-device, $large-mobile} {
                    font-size: 1.5rem;
                }
            }
        }

        & span {
            color: $logo-secondary-color;
        }
    }

    //header action
    .header-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;

        & .header-action-btn {
            position: relative;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: $black;
            transition: $baseTransition;

            &:hover {
                color: $theme-color;
            }

            & i {
                transition: 0s;
                font-size: 27px;

                &:hover {
                    color: $theme-color;
                }
            }

            & .header-action-num {
                font-size: 14px;
                font-weight: 400;
                position: absolute;
                z-index: 2;
                top: -10px;
                right: -7px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: $white;
                border-radius: 50%;
                background-color: $theme-color;
                width: 21px;
                height: 21px;
            }

            & .header-action-order {
                font-size: 13px;
                line-height: 1px;
                color: $theme-color;
            }
        }
    }
}
</style>